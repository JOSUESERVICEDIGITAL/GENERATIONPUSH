<?php

namespace Database\Seeders;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'Admin')->inRandomOrder()->take(5)->get();

        $plans = [
            ['plan' => 'Basique', 'price' => 9.99, 'billing_cycle' => 'monthly', 'status' => 'active'],
            ['plan' => 'Pro', 'price' => 24.99, 'billing_cycle' => 'monthly', 'status' => 'active'],
            ['plan' => 'Premium', 'price' => 199.00, 'billing_cycle' => 'yearly', 'status' => 'active'],
            ['plan' => 'Pro', 'price' => 24.99, 'billing_cycle' => 'monthly', 'status' => 'cancelled'],
            ['plan' => 'Basique', 'price' => 9.99, 'billing_cycle' => 'monthly', 'status' => 'expired'],
        ];

        foreach ($users as $i => $user) {
            if (! isset($plans[$i])) {
                continue;
            }

            Subscription::updateOrCreate(
                ['user_id' => $user->id],
                [
                    ...$plans[$i],
                    'started_at' => now()->subMonths(2),
                    'next_billing_at' => now()->addDays(15),
                ]
            );
        }
    }
}
