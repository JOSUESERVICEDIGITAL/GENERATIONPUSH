<?php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['email' => 'aisha.kone@email.com', 'amount' => 99.00, 'type' => 'payment', 'method' => 'stripe', 'status' => 'completed', 'date' => '2026-06-15'],
            ['email' => 'jc.mvondo@email.com', 'amount' => 49.00, 'type' => 'payment', 'method' => 'paypal', 'status' => 'completed', 'date' => '2026-06-14'],
            ['email' => 'fatou.diallo@email.com', 'amount' => 25.00, 'type' => 'refund', 'method' => 'orange', 'status' => 'completed', 'date' => '2026-06-13'],
            ['email' => 'kwame.asante@email.com', 'amount' => 129.00, 'type' => 'payment', 'method' => 'wave', 'status' => 'pending', 'date' => '2026-06-12'],
            ['email' => 'grace.mwangi@email.com', 'amount' => 79.00, 'type' => 'payment', 'method' => 'stripe', 'status' => 'completed', 'date' => '2026-06-11'],
            ['email' => 'amina.hassan@email.com', 'amount' => 49.00, 'type' => 'payment', 'method' => 'paypal', 'status' => 'failed', 'date' => '2026-06-10'],
        ];

        foreach ($demo as $row) {
            $user = User::where('email', $row['email'])->first();

            if (! $user) {
                continue;
            }

            Transaction::updateOrCreate(
                ['user_id' => $user->id, 'date' => $row['date'], 'amount' => $row['amount']],
                [
                    'type' => $row['type'],
                    'method' => $row['method'],
                    'status' => $row['status'],
                ]
            );
        }
    }
}
