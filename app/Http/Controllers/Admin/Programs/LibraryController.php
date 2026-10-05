<?php

namespace App\Http\Controllers\Admin\Programs;

use App\Http\Controllers\Controller;
use App\Models\LibraryResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class LibraryController extends Controller
{
    /**
     * Liste des livres.
     */
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $resources = LibraryResource::query()
            ->with('product')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('author', 'like', "%{$search}%")
                        ->orWhere('isbn', 'like', "%{$search}%");
                });
            })
            ->when(
                in_array($status, ['published', 'draft'], true),
                fn ($query) => $query->where('status', $status)
            )
            ->orderByDesc('show_on_podium')
            ->orderByDesc('is_bestseller')
            ->orderBy('display_order')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => LibraryResource::count(),

            'published' => LibraryResource::where(
                'status',
                'published'
            )->count(),

            'draft' => LibraryResource::where(
                'status',
                'draft'
            )->count(),

            'bestsellers' => LibraryResource::where(
                'is_bestseller',
                true
            )->count(),

            'podium' => LibraryResource::where(
                'show_on_podium',
                true
            )->count(),
        ];

        return view(
            'admin.programs.library.index',
            compact(
                'resources',
                'stats',
                'search',
                'status'
            )
        );
    }

    /**
     * Ajouter un livre.
     *
     * Le livre est créé dans library_resources
     * et son produit Boutique est créé automatiquement.
     */
    public function store(Request $request)
    {
        $validated = $this->validateBook($request);

        $coverPath = null;
        $showcasePath = null;

        try {

            /*
            |--------------------------------------------------------------------------
            | UPLOAD DES IMAGES
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('cover_image')) {
                $coverPath = $request
                    ->file('cover_image')
                    ->store('books/covers', 'public');

                $validated['cover_image'] = $coverPath;
            }

            if ($request->hasFile('showcase_image')) {
                $showcasePath = $request
                    ->file('showcase_image')
                    ->store('books/showcase', 'public');

                $validated['showcase_image'] = $showcasePath;
            }

            /*
            |--------------------------------------------------------------------------
            | VALEURS TECHNIQUES
            |--------------------------------------------------------------------------
            */

            $validated['slug'] =
                LibraryResource::generateUniqueSlug(
                    $validated['title']
                );

            $validated['is_bestseller'] =
                $request->boolean('is_bestseller');

            $validated['show_on_podium'] =
                $request->boolean('show_on_podium');

            $validated['display_order'] =
                (int) ($validated['display_order'] ?? 0);

            /*
            |--------------------------------------------------------------------------
            | TRANSACTION
            |--------------------------------------------------------------------------
            */

            DB::transaction(function () use (&$validated) {

                /*
                |--------------------------------------------------------------------------
                | UN SEUL LIVRE SUR LE PLATEAU
                |--------------------------------------------------------------------------
                */

                if ($validated['show_on_podium']) {
                    LibraryResource::query()
                        ->where('show_on_podium', true)
                        ->update([
                            'show_on_podium' => false,
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | CRÉATION DU PRODUIT BOUTIQUE
                |--------------------------------------------------------------------------
                |
                | LibraryResource contient la partie éditoriale.
                | Product contient la partie commerciale.
                |
                */

                $product = Product::create([
                    'title' => $validated['title'],

                    'description' =>
                        $validated['description'] ?? null,

                    'type' => 'book',

                    'is_free' =>
                        $this->effectivePrice($validated) <= 0,

                    'price' =>
                        $this->effectivePrice($validated),

                    'image_path' =>
                        $validated['cover_image'] ?? null,

                    'file_path' => null,

                    'video_url' => null,

                    /*
                    | Le stock Product concerne le physique.
                    | Pour un livre uniquement numérique,
                    | le stock n'est pas nécessaire.
                    */
                    'stock' =>
                        ($validated['format'] ?? 'physical') === 'digital'
                            ? null
                            : ($validated['stock'] ?? null),

                    'sales' => 0,

                    'status' =>
                        $validated['status'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | CRÉATION DU LIVRE
                |--------------------------------------------------------------------------
                */

                $validated['product_id'] = $product->id;

                LibraryResource::create($validated);
            });

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | NETTOYAGE EN CAS D'ÉCHEC
            |--------------------------------------------------------------------------
            |
            | Évite de laisser des images orphelines lorsque SQL échoue.
            |
            */

            if (
                $coverPath
                && Storage::disk('public')->exists($coverPath)
            ) {
                Storage::disk('public')->delete($coverPath);
            }

            if (
                $showcasePath
                && Storage::disk('public')->exists($showcasePath)
            ) {
                Storage::disk('public')->delete($showcasePath);
            }

            throw $e;
        }

        return redirect()
            ->route('admin.programs.library.index')
            ->with(
                'success',
                'Livre ajouté et synchronisé avec la Boutique.'
            );
    }

    /**
     * Modifier un livre.
     */
    public function update(
    Request $request,
    LibraryResource $library
) {
    $resource = $library;

    $validated = $this->validateBook(
        $request,
        $resource
        );

        $oldCover = $resource->cover_image;
        $oldShowcase = $resource->showcase_image;

        $newCoverPath = null;
        $newShowcasePath = null;

        /*
        |--------------------------------------------------------------------------
        | NOUVELLE COUVERTURE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('cover_image')) {
            $newCoverPath = $request
                ->file('cover_image')
                ->store('books/covers', 'public');

            $validated['cover_image'] =
                $newCoverPath;
        }

        /*
        |--------------------------------------------------------------------------
        | NOUVEAU VISUEL DU PLATEAU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('showcase_image')) {
            $newShowcasePath = $request
                ->file('showcase_image')
                ->store('books/showcase', 'public');

            $validated['showcase_image'] =
                $newShowcasePath;
        }

        /*
        |--------------------------------------------------------------------------
        | SLUG
        |--------------------------------------------------------------------------
        */

        if (
            $resource->title !== $validated['title']
            || blank($resource->slug)
        ) {
            $validated['slug'] =
                LibraryResource::generateUniqueSlug(
                    $validated['title'],
                    $resource->id
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKBOX
        |--------------------------------------------------------------------------
        */

        $validated['is_bestseller'] =
            $request->boolean('is_bestseller');

        $validated['show_on_podium'] =
            $request->boolean('show_on_podium');

        $validated['display_order'] =
            (int) ($validated['display_order'] ?? 0);

        try {

            DB::transaction(function () use (
                $resource,
                &$validated
            ) {

                /*
                |--------------------------------------------------------------------------
                | PLATEAU
                |--------------------------------------------------------------------------
                */

                if ($validated['show_on_podium']) {
                    LibraryResource::query()
                        ->whereKeyNot($resource->id)
                        ->where('show_on_podium', true)
                        ->update([
                            'show_on_podium' => false,
                        ]);
                }

                /*
                |--------------------------------------------------------------------------
                | PRODUIT BOUTIQUE
                |--------------------------------------------------------------------------
                */

                $product = $resource->product;

                /*
                | Cas d'un ancien livre créé avant l'ajout de product_id :
                | on lui crée automatiquement son produit.
                */

                if (! $product) {

                    $product = Product::create([
                        'title' =>
                            $validated['title'],

                        'description' =>
                            $validated['description'] ?? null,

                        'type' => 'book',

                        'is_free' =>
                            $this->effectivePrice($validated) <= 0,

                        'price' =>
                            $this->effectivePrice($validated),

                        'image_path' =>
                            $validated['cover_image']
                            ?? $resource->cover_image,

                        'file_path' => null,

                        'video_url' => null,

                        'stock' =>
                            ($validated['format'] ?? 'physical') === 'digital'
                                ? null
                                : ($validated['stock'] ?? null),

                        'sales' => 0,

                        'status' =>
                            $validated['status'],
                    ]);

                    $validated['product_id'] =
                        $product->id;

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | SYNCHRONISATION DU PRODUIT EXISTANT
                    |--------------------------------------------------------------------------
                    */

                    $product->update([
                        'title' =>
                            $validated['title'],

                        'description' =>
                            $validated['description'] ?? null,

                        'type' => 'book',

                        'is_free' =>
                            $this->effectivePrice($validated) <= 0,

                        'price' =>
                            $this->effectivePrice($validated),

                        'image_path' =>
                            $validated['cover_image']
                            ?? $resource->cover_image,

                        'stock' =>
                            ($validated['format'] ?? 'physical') === 'digital'
                                ? null
                                : ($validated['stock'] ?? null),

                        'status' =>
                            $validated['status'],
                    ]);
                }

                /*
                |--------------------------------------------------------------------------
                | MISE À JOUR DU LIVRE
                |--------------------------------------------------------------------------
                */

                $resource->update($validated);
            });

        } catch (\Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | SUPPRIMER LES NOUVEAUX FICHIERS SI SQL ÉCHOUE
            |--------------------------------------------------------------------------
            */

            if (
                $newCoverPath
                && Storage::disk('public')->exists(
                    $newCoverPath
                )
            ) {
                Storage::disk('public')->delete(
                    $newCoverPath
                );
            }

            if (
                $newShowcasePath
                && Storage::disk('public')->exists(
                    $newShowcasePath
                )
            ) {
                Storage::disk('public')->delete(
                    $newShowcasePath
                );
            }

            throw $e;
        }

        /*
        |--------------------------------------------------------------------------
        | SUPPRIMER LES ANCIENS FICHIERS APRÈS SUCCÈS
        |--------------------------------------------------------------------------
        */

        if (
            $newCoverPath
            && $oldCover
            && $oldCover !== $newCoverPath
            && Storage::disk('public')->exists($oldCover)
        ) {
            Storage::disk('public')->delete(
                $oldCover
            );
        }

        if (
            $newShowcasePath
            && $oldShowcase
            && $oldShowcase !== $newShowcasePath
            && Storage::disk('public')->exists($oldShowcase)
        ) {
            Storage::disk('public')->delete(
                $oldShowcase
            );
        }

        return redirect()
            ->route('admin.programs.library.index')
            ->with(
                'success',
                'Livre et produit Boutique mis à jour avec succès.'
            );
    }

    /**
     * Supprimer un livre.
     */
    public function destroy(
    LibraryResource $library
) {
    $resource = $library;
        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | On ne supprime PAS automatiquement le Product.
        |
        | Pourquoi ?
        | Des OrderItem historiques peuvent déjà référencer ce produit.
        |
        | On le passe simplement en brouillon.
        |
        */

        DB::transaction(function () use ($resource) {

            if ($resource->product) {
                $resource->product->update([
                    'status' => 'draft',
                ]);
            }

            $resource->delete();
        });

        $this->deleteBookFiles($resource);

        return redirect()
            ->route('admin.programs.library.index')
            ->with(
                'success',
                'Livre supprimé. Son produit Boutique a été désactivé.'
            );
    }

    /**
     * Suppression multiple.
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ids.*' => [
                'integer',
                'exists:library_resources,id',
            ],
        ]);

        $resources = LibraryResource::query()
            ->with('product')
            ->whereIn(
                'id',
                $validated['ids']
            )
            ->get();

        foreach ($resources as $resource) {

            DB::transaction(function () use ($resource) {

                if ($resource->product) {
                    $resource->product->update([
                        'status' => 'draft',
                    ]);
                }

                $resource->delete();
            });

            $this->deleteBookFiles($resource);
        }

        return redirect()
            ->route('admin.programs.library.index')
            ->with(
                'success',
                $resources->count()
                . ' livre(s) supprimé(s).'
            );
    }

    /**
     * Validation création / modification.
     */
    private function validateBook(
        Request $request,
        ?LibraryResource $resource = null
    ): array {
        return $request->validate([

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'author' => [
                'nullable',
                'string',
                'max:255',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:500',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'showcase_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:10240',
            ],

            'isbn' => [
                'nullable',
                'string',
                'max:50',

                Rule::unique(
    'library_resources',
    'isbn'
)->ignore($resource?->getKey()),
            ],

            'publisher' => [
                'nullable',
                'string',
                'max:255',
            ],

            'publication_date' => [
                'nullable',
                'date',
            ],

            'pages' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'promotional_price' => [
                'nullable',
                'numeric',
                'min:0',
                'lte:price',
            ],

            'format' => [
                'required',

                Rule::in([
                    'physical',
                    'digital',
                    'both',
                ]),
            ],

            'stock' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'value_description' => [
                'nullable',
                'string',
            ],

            'discover_content' => [
                'nullable',
                'string',
            ],

            'is_bestseller' => [
                'nullable',
                'boolean',
            ],

            'show_on_podium' => [
                'nullable',
                'boolean',
            ],

            'display_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'status' => [
                'required',

                Rule::in([
                    'draft',
                    'published',
                ]),
            ],
        ]);
    }

    /**
     * Prix réellement envoyé à la Boutique.
     */
    private function effectivePrice(
        array $validated
    ): float {
        $normalPrice =
            (float) ($validated['price'] ?? 0);

        $promotionalPrice =
            $validated['promotional_price'] ?? null;

        if (
            $promotionalPrice !== null
            && (float) $promotionalPrice < $normalPrice
        ) {
            return (float) $promotionalPrice;
        }

        return $normalPrice;
    }

    /**
     * Supprimer les fichiers du livre.
     */
    private function deleteBookFiles(
        LibraryResource $resource
    ): void {
        if (
            $resource->cover_image
            && Storage::disk('public')->exists(
                $resource->cover_image
            )
        ) {
            Storage::disk('public')->delete(
                $resource->cover_image
            );
        }

        if (
            $resource->showcase_image
            && Storage::disk('public')->exists(
                $resource->showcase_image
            )
        ) {
            Storage::disk('public')->delete(
                $resource->showcase_image
            );
        }
    }
}
