<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Liste des événements + filtres.
     */
    public function index(Request $request)
    {
        $query = Event::with('category')
            ->withCount([
                'reservations',
                'confirmedReservations',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Recherche
        |--------------------------------------------------------------------------
        */
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('speaker', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('country', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Catégorie
        |--------------------------------------------------------------------------
        */
        if ($request->filled('category')) {
            $query->where('event_category_id', $request->category);
        }

        /*
        |--------------------------------------------------------------------------
        | Publication
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | État temporel
        |--------------------------------------------------------------------------
        */
        if ($request->filled('period')) {

            switch ($request->period) {

                case 'upcoming':
                    $query->where('starts_at', '>', now());
                    break;

                case 'ongoing':
                    $query
                        ->where('starts_at', '<=', now())
                        ->where(function ($q) {
                            $q->whereNull('ends_at')
                                ->orWhere('ends_at', '>=', now());
                        });
                    break;

                case 'past':
                    $query->where(function ($q) {
                        $q->where('ends_at', '<', now())
                            ->orWhere(function ($sub) {
                                $sub->whereNull('ends_at')
                                    ->where('starts_at', '<', now());
                            });
                    });
                    break;
            }
        }

        $events = $query
            ->orderByDesc('starts_at')
            ->paginate(15)
            ->withQueryString();

        $categories = EventCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistiques dashboard
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => Event::count(),

            'upcoming' => Event::where(
                'starts_at',
                '>',
                now()
            )->count(),

            'ongoing' => Event::where('starts_at', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('ends_at')
                        ->orWhere('ends_at', '>=', now());
                })
                ->count(),

            'past' => Event::where(function ($q) {
                $q->where('ends_at', '<', now())
                    ->orWhere(function ($sub) {
                        $sub->whereNull('ends_at')
                            ->where('starts_at', '<', now());
                    });
            })->count(),

            'draft' => Event::where('status', 'draft')->count(),
        ];

        return view(
            'admin.events.index',
            compact('events', 'categories', 'stats')
        );
    }

    /**
     * Formulaire de création.
     */
    public function create()
    {
        $categories = EventCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.events.create',
            compact('categories')
        );
    }

    /**
     * Enregistrement.
     */
  public function store(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    $validated = $this->validateEvent($request);


    /*
    |--------------------------------------------------------------------------
    | SLUG UNIQUE
    |--------------------------------------------------------------------------
    */

    $validated['slug'] = $this->generateUniqueSlug(
        $validated['title']
    );


    /*
    |--------------------------------------------------------------------------
    | IMAGE PRINCIPALE
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('image')) {
        $validated['image'] = $request
            ->file('image')
            ->store('events/images', 'public');
    }


    /*
    |--------------------------------------------------------------------------
    | BANNIÈRE
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('banner')) {
        $validated['banner'] = $request
            ->file('banner')
            ->store('events/banners', 'public');
    }


    /*
    |--------------------------------------------------------------------------
    | PHOTO DE L'INTERVENANT
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('speaker_image')) {
        $validated['speaker_image'] = $request
            ->file('speaker_image')
            ->store('events/speakers', 'public');
    }


    /*
    |--------------------------------------------------------------------------
    | CHECKBOX
    |--------------------------------------------------------------------------
    */

    $validated['reservation_enabled'] =
        $request->boolean('reservation_enabled');

    $validated['is_free'] =
        $request->boolean('is_free');

    $validated['featured'] =
        $request->boolean('featured');


    /*
    |--------------------------------------------------------------------------
    | TARIFICATION
    |--------------------------------------------------------------------------
    */

    if ($validated['is_free']) {
        $validated['price'] = 0;
    }


    /*
    |--------------------------------------------------------------------------
    | CRÉATION DE L'ÉVÉNEMENT
    |--------------------------------------------------------------------------
    */

    $event = Event::create($validated);


    /*
    |--------------------------------------------------------------------------
    | REDIRECTION VERS LA FICHE DE L'ÉVÉNEMENT
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('admin.events.show', $event)
        ->with(
            'success',
            'Événement créé avec succès.'
        );
}
    /**
     * Détails.
     */
    public function show(Event $event)
    {
        $event->load([
            'category',
            'reservations' => function ($query) {
                $query->latest();
            },
        ]);

        $event->loadCount([
            'reservations',
            'confirmedReservations',
        ]);

        return view(
            'admin.events.show',
            compact('event')
        );
    }

    /**
     * Modification.
     */
    public function edit(Event $event)
    {
        $categories = EventCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view(
            'admin.events.edit',
            compact('event', 'categories')
        );
    }

    /**
     * Mise à jour.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $this->validateEvent(
            $request,
            $event
        );

        /*
        |--------------------------------------------------------------------------
        | Image principale
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            if ($event->image) {
                Storage::disk('public')->delete($event->image);
            }

            $validated['image'] = $request
                ->file('image')
                ->store('events/images', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Bannière
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner')) {

            if ($event->banner) {
                Storage::disk('public')->delete($event->banner);
            }

            $validated['banner'] = $request
                ->file('banner')
                ->store('events/banners', 'public');
        }

        /*
        |--------------------------------------------------------------------------
        | Photo intervenant
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('speaker_image')) {

            if ($event->speaker_image) {
                Storage::disk('public')->delete(
                    $event->speaker_image
                );
            }

            $validated['speaker_image'] = $request
                ->file('speaker_image')
                ->store('events/speakers', 'public');
        }

        $validated['reservation_enabled'] =
            $request->boolean('reservation_enabled');

        $validated['is_free'] =
            $request->boolean('is_free');

        $validated['featured'] =
            $request->boolean('featured');

        if ($validated['is_free']) {
            $validated['price'] = 0;
        }

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with(
                'success',
                'Événement mis à jour avec succès.'
            );
    }

    /**
     * Suppression.
     */
    public function destroy(Event $event)
    {
        if ($event->image) {
            Storage::disk('public')->delete($event->image);
        }

        if ($event->banner) {
            Storage::disk('public')->delete($event->banner);
        }

        if ($event->speaker_image) {
            Storage::disk('public')->delete(
                $event->speaker_image
            );
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with(
                'success',
                'Événement supprimé avec succès.'
            );
    }

    /**
     * Validation commune CREATE / UPDATE.
     */
    private function validateEvent(
        Request $request,
        ?Event $event = null
    ): array {

        return $request->validate([

            'event_category_id' => [
                'required',
                'exists:event_categories,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'short_description' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'banner' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:8192',
            ],

            'speaker' => [
                'nullable',
                'string',
                'max:255',
            ],

            'speaker_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'speaker_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'starts_at' => [
                'required',
                'date',
            ],

            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],

            'format' => [
                'required',
                Rule::in([
                    'physical',
                    'online',
                    'hybrid',
                ]),
            ],

            'venue' => [
                'nullable',
                'string',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],

            'city' => [
                'nullable',
                'string',
                'max:150',
            ],

            'country' => [
                'nullable',
                'string',
                'max:150',
            ],

            'online_url' => [
                'nullable',
                'url',
                'max:1000',
            ],

            'reservation_enabled' => [
                'nullable',
                'boolean',
            ],

            'capacity' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'reservation_starts_at' => [
                'nullable',
                'date',
            ],

            'reservation_ends_at' => [
                'nullable',
                'date',
                'after_or_equal:reservation_starts_at',
                'before_or_equal:starts_at',
            ],

            'is_free' => [
                'nullable',
                'boolean',
            ],

            'price' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'currency' => [
                'required',
                'string',
                'max:10',
            ],

            'status' => [
                'required',
                Rule::in([
                    'draft',
                    'published',
                    'cancelled',
                ]),
            ],

            'featured' => [
                'nullable',
                'boolean',
            ],
        ]);
    }

    /**
     * Slug unique.
     */
    private function generateUniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);

        $slug = $baseSlug;
        $counter = 1;

        while (
            Event::where('slug', $slug)->exists()
        ) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
