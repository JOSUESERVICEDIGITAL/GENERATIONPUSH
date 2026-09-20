<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Orders\StoreOrderRequest;
use App\Http\Requests\Admin\Orders\UpdateOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES COMMANDES
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $orders = Order::query()
            ->with(['user', 'items.product'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery
                                ->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = $this->computeStats();

        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
                'phone',
                'country',
                'city',
                'address',
            ]);

        $products = Product::query()
            ->where('status', 'published')
            ->orderBy('title')
            ->get([
                'id',
                'title',
                'price',
                'is_free',
                'type',
                'stock',
            ]);

        return view(
            'admin.shop.orders.index',
            compact(
                'orders',
                'stats',
                'search',
                'status',
                'users',
                'products'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FICHE D'UNE COMMANDE
    |--------------------------------------------------------------------------
    */

    public function show(Order $order): View
    {
        $order->load(['user', 'items.product']);

        return view('admin.shop.orders.show', compact('order'));
    }

    /*
    |--------------------------------------------------------------------------
    | CRÉATION ADMIN
    |--------------------------------------------------------------------------
    */

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = DB::transaction(function () use ($request) {
            $validated = $request->validated();

            $user = User::findOrFail($validated['user_id']);

            $order = Order::create([
                'order_number' => Order::nextOrderNumber(),

                'user_id' => $user->id,

                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'customer_country' => $user->country,
                'customer_city' => $user->city,
                'customer_address' => $user->address,

                'notes' => $validated['notes'] ?? null,
                'admin_notes' => null,

                'contacted_at' => null,
                'confirmed_at' => null,

                'status' => $validated['status'] ?? 'pending',
                'delivery_status' => 'not_required',
                'total' => 0,
            ]);

            $total = $this->createItems($order, $validated['items']);

            $hasPhysicalItem = $order->items()
                ->where('fulfillment_type', 'physical')
                ->exists();

            $order->update([
                'total' => $total,
                'delivery_status' => $hasPhysicalItem
                    ? 'pending'
                    : 'not_required',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Si l'admin crée directement une commande "paid",
            | on consomme l'inventaire immédiatement.
            |--------------------------------------------------------------------------
            */

            if ($order->status === 'paid') {
                $this->deductInventory($order->fresh('items.product'));
            }

            return $order;
        });

        return redirect()
            ->route('admin.shop.orders.show', $order)
            ->with(
                'success',
                "Commande {$order->order_number} créée avec succès."
            );
    }

    /*
    |--------------------------------------------------------------------------
    | MODIFICATION
    |--------------------------------------------------------------------------
    |
    | Règle d'inventaire :
    |   - Ancien statut "paid" → on restitue l'inventaire des anciens articles.
    |   - Nouveau statut "paid" → on consomme l'inventaire des nouveaux articles.
    |
    | Cela couvre aussi le cas "paid → paid" avec des articles modifiés.
    |
    */

    public function update(
        UpdateOrderRequest $request,
        Order $order
    ): RedirectResponse {
        DB::transaction(function () use ($request, $order) {
            $validated = $request->validated();

            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            /*
            |--------------------------------------------------------------------------
            | Snapshot des anciens articles physiques avant suppression
            |--------------------------------------------------------------------------
            */

            $oldPhysicalSnapshot = $order->items
                ->where('fulfillment_type', 'physical')
                ->map(fn (OrderItem $item) => [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ])
                ->values()
                ->all();

            /*
            |--------------------------------------------------------------------------
            | Snapshot complet des articles (pour conserver les fichiers numériques)
            |--------------------------------------------------------------------------
            */

            $existingItems = $order->items
                ->map(fn (OrderItem $item) => [
                    'product_id' => $item->product_id,
                    'title' => $item->title,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                    'fulfillment_type' => $item->fulfillment_type,
                    'digital_file_path' => $item->digital_file_path,
                    'digital_access_granted_at' => $item->digital_access_granted_at,
                ])
                ->keyBy('product_id')
                ->all();

            /*
            |--------------------------------------------------------------------------
            | Reconstruction des articles
            |--------------------------------------------------------------------------
            */

            $order->items()->delete();

            $total = $this->createItems(
                $order,
                $validated['items'],
                $existingItems
            );

            $hasPhysicalItem = $order->items()
                ->where('fulfillment_type', 'physical')
                ->exists();

            $deliveryStatus = $this->resolveDeliveryStatus(
                $order,
                $hasPhysicalItem
            );

            /*
            |--------------------------------------------------------------------------
            | Mise à jour commande
            |--------------------------------------------------------------------------
            */

            $user = User::findOrFail($validated['user_id']);

            $order->update([
                'user_id' => $user->id,

                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone,
                'customer_country' => $user->country,
                'customer_city' => $user->city,
                'customer_address' => $user->address,

                'notes' => $validated['notes'] ?? $order->notes,

                'status' => $newStatus,
                'total' => $total,
                'delivery_status' => $deliveryStatus,
            ]);

            /*
            |--------------------------------------------------------------------------
            | RÉCONCILIATION D'INVENTAIRE
            |--------------------------------------------------------------------------
            */

            $wasPaid = $oldStatus === 'paid';
            $isPaid = $newStatus === 'paid';

            if ($wasPaid) {
                $this->restoreInventoryFromSnapshot($oldPhysicalSnapshot);
            }

            if ($isPaid) {
                $this->deductInventory($order->fresh('items.product'));
            }
        });

        return redirect()
            ->route('admin.shop.orders.show', $order)
            ->with('success', 'Commande mise à jour avec succès.');
    }

    /*
    |--------------------------------------------------------------------------
    | CONTACT CLIENT
    |--------------------------------------------------------------------------
    */

    public function markContacted(Order $order): RedirectResponse
    {
        if ($order->contacted_at) {
            return back()->with(
                'error',
                'Le contact a déjà été enregistré.'
            );
        }

        $order->update(['contacted_at' => now()]);

        return back()->with(
            'success',
            'Le contact avec le membre a été enregistré.'
        );
    }

    public function unmarkContacted(Order $order): RedirectResponse
    {
        if ($order->status === 'paid') {
            return back()->with(
                'error',
                'Impossible de retirer le contact d’une commande déjà confirmée.'
            );
        }

        $order->update(['contacted_at' => null]);

        return back()->with(
            'success',
            'Le contact avec le membre a été retiré.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONFIRMER LA COMMANDE (pending → paid)
    |--------------------------------------------------------------------------
    */

    public function confirm(Order $order): RedirectResponse
    {
        if ($order->status === 'cancelled') {
            return back()->with(
                'error',
                'Une commande annulée ne peut pas être confirmée.'
            );
        }

        if ($order->status === 'paid') {
            return back()->with(
                'error',
                'Cette commande est déjà confirmée.'
            );
        }

        if (! $order->contacted_at) {
            return back()->with(
                'error',
                'Veuillez d’abord enregistrer le contact avec le membre.'
            );
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'paid',
                'confirmed_at' => now(),
            ]);

            $this->deductInventory($order->fresh('items.product'));
        });

        return back()->with(
            'success',
            'Commande confirmée. La commande est maintenant en traitement.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ANNULER LA COMMANDE
    |--------------------------------------------------------------------------
    */

    public function cancel(Order $order): RedirectResponse
    {
        if ($order->status === 'cancelled') {
            return back()->with(
                'error',
                'Cette commande est déjà annulée.'
            );
        }

        DB::transaction(function () use ($order) {
            $wasPaid = $order->status === 'paid';

            $order->update([
                'status' => 'cancelled',
                'delivery_status' => 'not_required',
            ]);

            if ($wasPaid) {
                $this->restoreInventoryFromSnapshot(
                    $order->items
                        ->where('fulfillment_type', 'physical')
                        ->map(fn (OrderItem $item) => [
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ])
                        ->values()
                        ->all()
                );
            }
        });

        return back()->with(
            'success',
            'La commande a été annulée et l’inventaire a été restitué.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REMBOURSER LA COMMANDE
    |--------------------------------------------------------------------------
    */

    public function refund(Order $order): RedirectResponse
    {
        if ($order->status === 'refunded') {
            return back()->with(
                'error',
                'Cette commande est déjà remboursée.'
            );
        }

        if ($order->status !== 'paid') {
            return back()->with(
                'error',
                'Seule une commande payée peut être remboursée.'
            );
        }

        DB::transaction(function () use ($order) {
            $order->update([
                'status' => 'refunded',
                'delivery_status' => 'not_required',
            ]);

            $this->restoreInventoryFromSnapshot(
                $order->items
                    ->where('fulfillment_type', 'physical')
                    ->map(fn (OrderItem $item) => [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ])
                    ->values()
                    ->all()
            );
        });

        return back()->with(
            'success',
            'La commande a été remboursée et l’inventaire a été restitué.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | NOTES ADMINISTRATIVES
    |--------------------------------------------------------------------------
    */

    public function updateNotes(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $order->update([
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        return back()->with(
            'success',
            'Les notes administratives ont été enregistrées.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STATUT DE LIVRAISON
    |--------------------------------------------------------------------------
    */

    public function updateDeliveryStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'delivery_status' => [
                'required',
                'in:pending,processing,shipped,delivered',
            ],
        ]);

        if (! $order->requiresDelivery()) {
            return back()->with(
                'error',
                'Cette commande ne nécessite pas de livraison physique.'
            );
        }

        if ($order->status !== 'paid') {
            return back()->with(
                'error',
                'La commande doit être confirmée avant son traitement logistique.'
            );
        }

        $order->update([
            'delivery_status' => $validated['delivery_status'],
        ]);

        return back()->with(
            'success',
            'Le statut de livraison a été mis à jour.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENU NUMÉRIQUE — AJOUT / REMPLACEMENT
    |--------------------------------------------------------------------------
    */

    public function uploadDigitalFile(
        Request $request,
        OrderItem $item
    ): RedirectResponse {
        $order = $item->order;

        abort_unless(
            $item->isDigital(),
            422,
            'Cet article n’est pas numérique.'
        );

        $validated = $request->validate([
            'digital_file' => [
                'required',
                'file',
                'max:102400',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Suppression de l'ancien fichier
        |--------------------------------------------------------------------------
        */

        if (
            $item->digital_file_path
            && Storage::disk('private')->exists($item->digital_file_path)
        ) {
            Storage::disk('private')->delete($item->digital_file_path);
        }

        /*
        |--------------------------------------------------------------------------
        | Stockage privé
        |--------------------------------------------------------------------------
        */

        $path = $request
            ->file('digital_file')
            ->store('orders/' . $order->id, 'private');

        $item->update([
            'digital_file_path' => $path,
            'digital_access_granted_at' => null,
        ]);

        return back()->with(
            'success',
            'Le contenu numérique a été ajouté. L’accès reste verrouillé jusqu’à sa validation.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENU NUMÉRIQUE — AUTORISER L'ACCÈS
    |--------------------------------------------------------------------------
    */

    public function grantDigitalAccess(OrderItem $item): RedirectResponse
    {
        $order = $item->order;

        abort_unless(
            $item->isDigital(),
            422,
            'Cet article n’est pas numérique.'
        );

        if (! $item->digital_file_path) {
            return back()->with(
                'error',
                'Ajoutez d’abord le contenu numérique.'
            );
        }

        if (! Storage::disk('private')->exists($item->digital_file_path)) {
            return back()->with(
                'error',
                'Le fichier numérique est introuvable.'
            );
        }

        if ($order->status !== 'paid') {
            return back()->with(
                'error',
                'La commande doit être confirmée avant de donner accès au contenu.'
            );
        }

        $item->update([
            'digital_access_granted_at' => now(),
        ]);

        return back()->with(
            'success',
            'L’accès au contenu numérique a été autorisé pour le membre.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CONTENU NUMÉRIQUE — RÉVOQUER L'ACCÈS
    |--------------------------------------------------------------------------
    */

    public function revokeDigitalAccess(OrderItem $item): RedirectResponse
    {
        $item->update([
            'digital_access_granted_at' => null,
        ]);

        return back()->with(
            'success',
            'L’accès au contenu numérique a été révoqué.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRESSION
    |--------------------------------------------------------------------------
    */

    public function destroy(Order $order): RedirectResponse
    {
        DB::transaction(function () use ($order) {
            /*
            |--------------------------------------------------------------------------
            | Si la commande était payée, on restitue l'inventaire
            |--------------------------------------------------------------------------
            */

            if ($order->status === 'paid') {
                $this->restoreInventoryFromSnapshot(
                    $order->items
                        ->where('fulfillment_type', 'physical')
                        ->map(fn (OrderItem $item) => [
                            'product_id' => $item->product_id,
                            'quantity' => $item->quantity,
                        ])
                        ->values()
                        ->all()
                );
            }

            $this->deleteOrderFiles($order);

            $order->delete();
        });

        return redirect()
            ->route('admin.shop.orders.index')
            ->with('success', 'Commande supprimée.');
    }

    /*
    |--------------------------------------------------------------------------
    | SUPPRESSION GROUPÉE
    |--------------------------------------------------------------------------
    */

    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = array_filter((array) $request->input('ids', []));

        if (empty($ids)) {
            return redirect()
                ->route('admin.shop.orders.index')
                ->with('error', 'Aucune commande sélectionnée.');
        }

        $orders = Order::with('items')
            ->whereIn('id', $ids)
            ->get();

        DB::transaction(function () use ($orders) {
            foreach ($orders as $order) {
                if ($order->status === 'paid') {
                    $this->restoreInventoryFromSnapshot(
                        $order->items
                            ->where('fulfillment_type', 'physical')
                            ->map(fn (OrderItem $item) => [
                                'product_id' => $item->product_id,
                                'quantity' => $item->quantity,
                            ])
                            ->values()
                            ->all()
                    );
                }

                $this->deleteOrderFiles($order);

                $order->delete();
            }
        });

        return redirect()
            ->route('admin.shop.orders.index')
            ->with(
                'success',
                $orders->count() . ' commande(s) supprimée(s).'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS PRIVÉS — STATISTIQUES
    |--------------------------------------------------------------------------
    */

    private function computeStats(): array
    {
        return [
            'total' => Order::count(),
            'paid' => Order::where('status', 'paid')->count(),
            'revenue' => (float) Order::where('status', 'paid')->sum('total'),
            'pending' => Order::where('status', 'pending')->count(),

            'contacted' => Order::whereNotNull('contacted_at')->count(),
            'uncontacted' => Order::whereNull('contacted_at')
                ->where('status', 'pending')
                ->count(),

            'delivery_pending' => Order::where('delivery_status', 'pending')->count(),
            'delivery_processing' => Order::where('delivery_status', 'processing')->count(),
            'delivery_shipped' => Order::where('delivery_status', 'shipped')->count(),
            'delivery_delivered' => Order::where('delivery_status', 'delivered')->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS PRIVÉS — CRÉATION DES ARTICLES
    |--------------------------------------------------------------------------
    */

    private function createItems(
        Order $order,
        array $items,
        array $existingItems = []
    ): float {
        $total = 0;

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (! $product) {
                continue;
            }

            $price = $product->is_free
                ? 0
                : (float) $product->price;

            $quantity = max(1, (int) $item['quantity']);

            $fulfillmentType = $item['fulfillment_type']
                ?? $this->defaultFulfillmentType($product);

            $allowedTypes = $this->allowedFulfillmentTypes($product);

            if (! in_array($fulfillmentType, $allowedTypes, true)) {
                $fulfillmentType = $allowedTypes[0];
            }

            /*
            |--------------------------------------------------------------------------
            | Conservation éventuelle du contenu numérique existant
            |--------------------------------------------------------------------------
            */

            $existing = $existingItems[$product->id] ?? null;

            $digitalFilePath = null;
            $digitalAccessGrantedAt = null;

            if (
                $fulfillmentType === 'digital'
                && $existing
                && $existing['fulfillment_type'] === 'digital'
            ) {
                $digitalFilePath = $existing['digital_file_path'];
                $digitalAccessGrantedAt = $existing['digital_access_granted_at'];
            }

            $order->items()->create([
                'product_id' => $product->id,
                'title' => $product->title,
                'price' => $price,
                'quantity' => $quantity,
                'fulfillment_type' => $fulfillmentType,
                'digital_file_path' => $digitalFilePath,
                'digital_access_granted_at' => $digitalAccessGrantedAt,
            ]);

            $total += $price * $quantity;
        }

        return $total;
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS PRIVÉS — TYPES DE LIVRAISON
    |--------------------------------------------------------------------------
    */

    private function allowedFulfillmentTypes(Product $product): array
    {
        return match ($product->type) {
            'book',
            'usb_key' => ['physical', 'digital'],
            'video' => ['digital'],
            default => ['physical'],
        };
    }

    private function defaultFulfillmentType(Product $product): string
    {
        return match ($product->type) {
            'video' => 'digital',
            'book',
            'usb_key' => 'physical',
            default => 'physical',
        };
    }

    private function resolveDeliveryStatus(
        Order $order,
        bool $hasPhysicalItem
    ): string {
        if (! $hasPhysicalItem) {
            return 'not_required';
        }

        return in_array(
            $order->delivery_status,
            ['pending', 'processing', 'shipped', 'delivered'],
            true
        )
            ? $order->delivery_status
            : 'pending';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS PRIVÉS — INVENTAIRE
    |--------------------------------------------------------------------------
    */

    /**
     * Consomme le stock des articles physiques de la commande.
     * Incrémente le compteur de ventes. Ne fait rien pour les articles digitaux.
     */
    private function deductInventory(Order $order): void
    {
        $order->loadMissing('items.product');

        foreach ($order->items as $item) {
            if ($item->fulfillment_type !== 'physical') {
                continue;
            }

            $product = $item->product;

            if (! $product) {
                continue;
            }

            if ($product->stock === null) {
                $product->increment('sales', $item->quantity);
                continue;
            }

            if ($item->quantity > $product->stock) {
                throw new RuntimeException(
                    "Stock insuffisant pour le produit : {$product->title}."
                );
            }

            $product->decrement('stock', $item->quantity);
            $product->increment('sales', $item->quantity);
        }
    }

    /**
     * Restitue le stock à partir d'un snapshot d'articles physiques
     * (utile quand la commande n'a plus ses anciens items, ou annulation).
     */
    private function restoreInventoryFromSnapshot(array $snapshot): void
    {
        foreach ($snapshot as $row) {
            $product = Product::find($row['product_id'] ?? null);

            if (! $product) {
                continue;
            }

            $quantity = max(1, (int) ($row['quantity'] ?? 1));

            if ($product->stock !== null) {
                $product->increment('stock', $quantity);
            }

            $product->decrement('sales', $quantity);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS PRIVÉS — SUPPRESSION DES FICHIERS
    |--------------------------------------------------------------------------
    */

    private function deleteOrderFiles(Order $order): void
    {
        foreach ($order->items as $item) {
            if (
                $item->digital_file_path
                && Storage::disk('private')->exists($item->digital_file_path)
            ) {
                Storage::disk('private')->delete($item->digital_file_path);
            }
        }

        if (
            $order->qr_code_path
            && Storage::disk('private')->exists($order->qr_code_path)
        ) {
            Storage::disk('private')->delete($order->qr_code_path);
        }
    }
}
