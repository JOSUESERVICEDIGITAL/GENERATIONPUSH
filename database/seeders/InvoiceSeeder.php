<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'Admin')->inRandomOrder()->take(4)->get();

        $rows = [
            ['amount' => 99.00, 'status' => 'paid', 'issued_at' => now()->subDays(20), 'due_at' => now()->subDays(10)],
            ['amount' => 49.00, 'status' => 'paid', 'issued_at' => now()->subDays(15), 'due_at' => now()->subDays(5)],
            ['amount' => 129.00, 'status' => 'pending', 'issued_at' => now()->subDays(5), 'due_at' => now()->addDays(10)],
            ['amount' => 79.00, 'status' => 'overdue', 'issued_at' => now()->subDays(30), 'due_at' => now()->subDays(15)],
        ];

        foreach ($users as $i => $user) {
            if (! isset($rows[$i])) {
                continue;
            }

            Invoice::updateOrCreate(
                ['user_id' => $user->id, 'issued_at' => $rows[$i]['issued_at']->toDateString()],
                [
                    'invoice_number' => Invoice::nextInvoiceNumber(),
                    'amount' => $rows[$i]['amount'],
                    'status' => $rows[$i]['status'],
                    'due_at' => $rows[$i]['due_at'],
                ]
            );
        }
    }
}
