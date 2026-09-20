<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use F9WebLtd\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Affiche le formulaire de commande.
     */
    public function create(Product $product): View|RedirectResponse
    {
        abort_if($product->status !== 'published', 404);

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | PROFIL OBLIGATOIRE
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'Member'
            && $user->status === 'active'
            && $user->needsProfileCompletion()
        ) {
            session()->put(
                'url.intended',
                route('front.shop.order.create', $product->slug)
            );

            return redirect()
                ->route('profile.edit')
                ->with(
                    'profile_required',
                    'Avant de passer votre commande, veuillez compléter votre profil.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | OPTIONS DE LIVRAISON / FORMAT
        |--------------------------------------------------------------------------
        */

        $fulfillmentOptions = $this->fulfillmentOptions($product);

        /*
        |--------------------------------------------------------------------------
        | RUPTURE DE STOCK
        |--------------------------------------------------------------------------
        |
        | Le stock concerne uniquement les versions physiques.
        | Une version numérique reste disponible même si le stock physique
        | est épuisé.
        |
        */

        if (
            in_array('physical', $fulfillmentOptions, true)
            && $product->stock !== null
            && $product->stock <= 0
        ) {
            /*
            | Pour un livre ou une clé USB, la version numérique peut
            | toujours être commandée.
            */

            if (in_array('digital', $fulfillmentOptions, true)) {
                $fulfillmentOptions = ['digital'];
            } else {
                return redirect()
                    ->route('front.shop.show', $product->slug)
                    ->with(
                        'error',
                        'Ce produit est actuellement en rupture de stock.'
                    );
            }
        }

        return view('front.shop.orders.create', [
            'product' => $product,
            'fulfillmentOptions' => $fulfillmentOptions,
            'user' => $user,
        ]);
    }

    /**
     * Enregistre une demande de commande.
     */
    public function store(
        StoreOrderRequest $request,
        Product $product
    ): RedirectResponse {
        abort_if($product->status !== 'published', 404);

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | PROFIL COMPLET OBLIGATOIRE
        |--------------------------------------------------------------------------
        */

        if (
            $user->role === 'Member'
            && $user->status === 'active'
            && $user->needsProfileCompletion()
        ) {
            session()->put(
                'url.intended',
                route('front.shop.order.create', $product->slug)
            );

            return redirect()
                ->route('profile.edit')
                ->with(
                    'profile_required',
                    'Veuillez compléter votre profil avant de passer votre commande.'
                );
        }

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | TYPES AUTORISÉS
        |--------------------------------------------------------------------------
        */

        $allowedTypes = $this->fulfillmentOptions($product);

        if (
            ! in_array(
                $validated['fulfillment_type'],
                $allowedTypes,
                true
            )
        ) {
            return back()
                ->withErrors([
                    'fulfillment_type' =>
                    'La version sélectionnée n’est pas disponible pour ce produit.',
                ])
                ->withInput();
        }

        $quantity = (int) $validated['quantity'];

        /*
        |--------------------------------------------------------------------------
        | STOCK PHYSIQUE
        |--------------------------------------------------------------------------
        |
        | On vérifie le stock au moment de la demande.
        | Mais on ne le décrémente PAS encore.
        |
        | La commande est seulement "pending".
        |
        */

        if (
            $validated['fulfillment_type'] === 'physical'
            && $product->stock !== null
            && $quantity > $product->stock
        ) {
            return back()
                ->withErrors([
                    'quantity' =>
                    "Il ne reste que {$product->stock} exemplaire(s) disponible(s).",
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | PRIX
        |--------------------------------------------------------------------------
        |
        | Le prix est récupéré directement depuis la base.
        | Le navigateur ne peut donc pas falsifier le montant.
        |
        */

        $unitPrice = $product->is_free
            ? 0
            : (float) $product->price;

        $total = $unitPrice * $quantity;

        /*
        |--------------------------------------------------------------------------
        | STATUT DE LIVRAISON
        |--------------------------------------------------------------------------
        */

        $deliveryStatus = $validated['fulfillment_type'] === 'physical'
            ? 'pending'
            : 'not_required';

        /*
        |--------------------------------------------------------------------------
        | CRÉATION DE LA COMMANDE
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(function () use (
            $validated,
            $user,
            $product,
            $quantity,
            $unitPrice,
            $total,
            $deliveryStatus
        ) {
            /*
            |--------------------------------------------------------------------------
            | COMMANDE
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'order_number' => Order::nextOrderNumber(),
                'user_id' => $user->id,

                /*
                | Snapshot des informations du client.
                | Les informations historiques de cette commande
                | ne dépendront donc plus des futures modifications
                | du profil.
                */
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'customer_country' => $user->country,
                'customer_city' => $user->city,
                'customer_address' => $user->address,

                'notes' => $validated['notes'] ?? null,

                'total' => $total,
                'status' => 'pending',
                'delivery_status' => $deliveryStatus,

                /*
                | Le QR sera généré après la création
                | des articles de la commande.
                */
                'qr_content' => null,
                'qr_code_path' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ARTICLE DE LA COMMANDE
            |--------------------------------------------------------------------------
            */

            $order->items()->create([
                'product_id' => $product->id,
                'title' => $product->title,
                'price' => $unitPrice,
                'quantity' => $quantity,

                'fulfillment_type' =>
                $validated['fulfillment_type'],

                /*
                | Le fichier numérique ne doit jamais être
                | automatiquement associé à la commande.
                |
                | Il sera ajouté par l'administrateur plus tard.
                */
                'digital_file_path' => null,

                /*
                | L'accès numérique est accordé manuellement
                | par l'administrateur.
                |
                | Si cette colonne existe déjà dans order_items,
                | elle reste donc NULL ici.
                */
                'digital_access_granted_at' => null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | CHARGEMENT DES ARTICLES
            |--------------------------------------------------------------------------
            */

            $order->load('items');
            /*
|--------------------------------------------------------------------------
| CONSTRUCTION DU CONTENU DU QR CODE
|--------------------------------------------------------------------------
|
| Le QR contient directement le résumé de la commande.
| Aucune URL et aucun token ne sont utilisés.
|
*/

            $qrContent = "GENERATION PUSH\n";
            $qrContent .= "==============================\n";

            $qrContent .= "COMMANDE : {$order->order_number}\n";

            if ($order->created_at) {
                $qrContent .= "DATE : "
                    . $order->created_at->format('d/m/Y H:i')
                    . "\n";
            }

            $qrContent .= "\n";

            /*
|--------------------------------------------------------------------------
| CLIENT
|--------------------------------------------------------------------------
*/

            $qrContent .= "CLIENT\n";
            $qrContent .= "------------------------------\n";

            $qrContent .= "Nom : {$order->customer_name}\n";
            $qrContent .= "Email : {$order->customer_email}\n";

            if ($order->customer_phone) {
                $qrContent .= "Téléphone : {$order->customer_phone}\n";
            }

            if ($order->customer_country) {
                $qrContent .= "Pays : {$order->customer_country}\n";
            }

            if ($order->customer_city) {
                $qrContent .= "Ville : {$order->customer_city}\n";
            }

            if ($order->customer_address) {
                $qrContent .= "Adresse : {$order->customer_address}\n";
            }

            /*
|--------------------------------------------------------------------------
| PRODUITS
|--------------------------------------------------------------------------
*/

            $qrContent .= "\n";
            $qrContent .= "COMMANDE\n";
            $qrContent .= "------------------------------\n";

            foreach ($order->items as $item) {
                $qrContent .= "Produit : {$item->title}\n";
                $qrContent .= "Quantité : {$item->quantity}\n";
                $qrContent .= "Format : {$item->fulfillmentTypeLabel()}\n";

                $qrContent .= "Prix unitaire : "
                    . number_format(
                        (float) $item->price,
                        2,
                        ',',
                        ' '
                    )
                    . " $\n";

                $qrContent .= "Sous-total : "
                    . number_format(
                        (float) $item->subtotal(),
                        2,
                        ',',
                        ' '
                    )
                    . " $\n";

                $qrContent .= "------------------------------\n";
            }

            /*
|--------------------------------------------------------------------------
| TOTAL
|--------------------------------------------------------------------------
*/

            $qrContent .= "\n";
            $qrContent .= "TOTAL : "
                . number_format(
                    (float) $order->total,
                    2,
                    ',',
                    ' '
                )
                . " $\n";

            /*
|--------------------------------------------------------------------------
| STATUT
|--------------------------------------------------------------------------
*/

            $qrContent .= "\n";
            $qrContent .= "STATUT : {$order->statusLabel()}\n";
            $qrContent .= "LIVRAISON : {$order->deliveryStatusLabel()}\n";

            /*
|--------------------------------------------------------------------------
| NOTE CLIENT
|--------------------------------------------------------------------------
*/

            if ($order->notes) {
                $qrContent .= "\n";
                $qrContent .= "NOTE CLIENT\n";
                $qrContent .= "------------------------------\n";
                $qrContent .= $order->notes . "\n";
            }

            /*
|--------------------------------------------------------------------------
| SIGNATURE
|--------------------------------------------------------------------------
*/

            $qrContent .= "\n";
            $qrContent .= "GENERATION PUSH\n";
            $qrContent .= "Merci pour votre commande.";

            /*
|--------------------------------------------------------------------------
| ENREGISTREMENT DU CONTENU
|--------------------------------------------------------------------------
*/

            $order->update([
                'qr_content' => $qrContent,
            ]);

            /*
|--------------------------------------------------------------------------
| GÉNÉRATION DU FICHIER QR
|--------------------------------------------------------------------------
*/

            $qrCodePath = 'orders/qr/'
                . $order->order_number
                . '.svg';

            $qrCode = QrCode::format('svg')
                ->size(600)
                ->margin(3)
                ->encoding('UTF-8')
                ->generate($qrContent);

            Storage::disk('private')->put(
                $qrCodePath,
                $qrCode
            );

            /*
|--------------------------------------------------------------------------
| ENREGISTREMENT DU CHEMIN
|--------------------------------------------------------------------------
*/

            $order->update([
                'qr_code_path' => $qrCodePath,
            ]);
            return $order;
        });

        /*
        |--------------------------------------------------------------------------
        | REDIRECTION VERS LA PAGE DE REMERCIEMENT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('front.shop.order.success', $order)
            ->with(
                'success',
                'Votre demande de commande a bien été enregistrée.'
            );
    }

    /**
     * Affiche la confirmation de commande.
     */
    public function success(Order $order): View
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load('items.product');

        return view('front.shop.orders.success', [
            'order' => $order,
        ]);
    }

    /**
     * Détermine les formats disponibles pour un produit.
     */
    private function fulfillmentOptions(Product $product): array
    {
        return match ($product->type) {
            'book', 'usb_key' => [
                'physical',
                'digital',
            ],

            'video' => [
                'digital',
            ],

            default => [
                'physical',
            ],
        };
    }
}
