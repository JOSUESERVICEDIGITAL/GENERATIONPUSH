<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'Admin')
            ->orderBy('id')
            ->take(4)
            ->get();

        $rows = [
            [
                'amount' => 99.00,
                'status' => 'paid',
                'issued_at' => now()->subDays(20),
                'due_at' => now()->subDays(10),
            ],
            [
                'amount' => 49.00,
                'status' => 'paid',
                'issued_at' => now()->subDays(15),
                'due_at' => now()->subDays(5),
            ],
            [
                'amount' => 129.00,
                'status' => 'pending',
                'issued_at' => now()->subDays(5),
                'due_at' => now()->addDays(10),
            ],
            [
                'amount' => 79.00,
                'status' => 'overdue',
                'issued_at' => now()->subDays(30),
                'due_at' => now()->subDays(15),
            ],
        ];

        foreach ($users as $i => $user) {
            if (!isset($rows[$i])) {
                continue;
            }

            $row = $rows[$i];

            $issuedAt = $row['issued_at'];

            /*
             * Chercher une facture existante pour cet utilisateur
             * à la même date.
             */
            $invoice = Invoice::where('user_id', $user->id)
                ->whereDate('issued_at', $issuedAt->toDateString())
                ->first();

            /*
             * Si elle n'existe pas, on la crée.
             */
            if (!$invoice) {
                $invoice = new Invoice();

                $invoice->invoice_number = $this->getNextAvailableInvoiceNumber();
                $invoice->user_id = $user->id;
                $invoice->issued_at = $issuedAt;
            }

            /*
             * Mise à jour des informations.
             */
            $invoice->amount = $row['amount'];
            $invoice->status = $row['status'];
            $invoice->due_at = $row['due_at'];

            $invoice->save();
        }
    }

    /**
     * Génère un numéro de facture réellement disponible.
     */
    private function getNextAvailableInvoiceNumber(): string
    {
        $number = 1;

        while (Invoice::where('invoice_number', sprintf('INV-%05d', $number))->exists()) {
            $number++;
        }

        return sprintf('INV-%05d', $number);
    }
}
