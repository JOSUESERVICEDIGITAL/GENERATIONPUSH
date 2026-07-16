<?php

namespace App\Http\Controllers\Admin\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Orders\StoreOrderRequest;
use App\Http\Requests\Admin\Orders\UpdateOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $orders = Order::query()
            ->with(['user', 'items'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            })
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Order::count(),
            'paid' => Order::where('status', 'paid')->count(),
            'revenue' => (float) Order::where('status', 'paid')->sum('total'),
            'pending' => Order::where('status', 'pending')->count(),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email']);
        $products = Product::where('status', 'published')->orderBy('title')->get(['id', 'title', 'price', 'is_free']);

        return view('admin.shop.orders.index', compact('orders', 'stats', 'search', 'status', 'users', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        DB::transaction(function () use ($request) {
            $order = Order::create([
                'order_number' => Order::nextOrderNumber(),
                'user_id' => $request->validated('user_id'),
                'status' => $request->validated('status'),
                'total' => 0,
            ]);

            $total = $this->createItems($order, $request->validated('items'));

            $order->update(['total' => $total]);
        });

        return redirect()
            ->route('admin.shop.orders.index')
            ->with('success', 'Commande créée avec succès.');
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        DB::transaction(function () use ($request, $order) {
            $order->items()->delete();

            $total = $this->createItems($order, $request->validated('items'));

            $order->update([
                'user_id' => $request->validated('user_id'),
                'status' => $request->validated('status'),
                'total' => $total,
            ]);
        });

        return redirect()
            ->route('admin.shop.orders.index')
            ->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('admin.shop.orders.index')
            ->with('success', 'Commande supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);

        Order::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.shop.orders.index')
            ->with('success', count($ids) . ' commande(s) supprimée(s).');
    }

    /**
     * Crée les lignes de commande à partir des produits réels (prix non falsifiable côté client)
     * et retourne le total calculé.
     */
    private function createItems(Order $order, array $items): float
    {
        $total = 0;

        foreach ($items as $item) {
            $product = Product::find($item['product_id']);

            if (! $product) {
                continue;
            }

            $price = $product->is_free ? 0 : (float) $product->price;
            $quantity = (int) $item['quantity'];

            $order->items()->create([
                'product_id' => $product->id,
                'title' => $product->title,
                'price' => $price,
                'quantity' => $quantity,
            ]);

            $total += $price * $quantity;
        }

        return $total;
    }
}
