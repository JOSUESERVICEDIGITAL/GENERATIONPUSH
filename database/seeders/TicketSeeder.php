<?php

namespace Database\Seeders;

use App\Models\Conference;
use App\Models\Masterclass;
use App\Models\Ticket;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $conference = Conference::first();
        $masterclass = Masterclass::first();

        if ($conference) {
            Ticket::updateOrCreate(
                ['ticketable_type' => $conference->getMorphClass(), 'ticketable_id' => $conference->id, 'title' => 'Standard'],
                ['price' => 25, 'quantity_total' => 400, 'quantity_sold' => 310, 'status' => 'active']
            );
            Ticket::updateOrCreate(
                ['ticketable_type' => $conference->getMorphClass(), 'ticketable_id' => $conference->id, 'title' => 'VIP'],
                ['price' => 75, 'quantity_total' => 50, 'quantity_sold' => 50, 'status' => 'sold_out']
            );
        }

        if ($masterclass) {
            Ticket::updateOrCreate(
                ['ticketable_type' => $masterclass->getMorphClass(), 'ticketable_id' => $masterclass->id, 'title' => 'Accès en ligne'],
                ['price' => 15, 'quantity_total' => 150, 'quantity_sold' => 90, 'status' => 'active']
            );
        }
    }
}
