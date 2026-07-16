<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'Admin')->inRandomOrder()->take(4)->get();
        $products = Product::where('status', 'published')->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['paid', 'paid', 'pending', 'cancelled'];

        foreach ($users as $i => $user) {
            $picked = $products->random(min(2, $products->count()));
            $total = 0;

            $order = Order::updateOrCreate(
                ['order_number' => Order::nextOrderNumber()],
                [
                    'user_id' => $user->id,
                    'status' => $statuses[$i] ?? 'pending',
                    'total' => 0,
                ]
            );

            $order->items()->delete();

            foreach ($picked as $product) {
                $price = $product->is_free ? 0 : (float) $product->price;

                $order->items()->create([
                    'product_id' => $product->id,
                    'title' => $product->title,
                    'price' => $price,
                    'quantity' => 1,
                ]);

                $total += $price;
            }

            $order->update(['total' => $total]);
        }
    }
}
