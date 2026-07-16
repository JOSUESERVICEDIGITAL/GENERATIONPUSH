<?php

namespace Database\Seeders;

use App\Models\Masterclass;
use Illuminate\Database\Seeder;

class MasterclassSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Masterclass : Négocier comme un pro', 'speaker' => 'Débora Tapsoba', 'location' => 'En ligne', 'country' => 'Burkina Faso', 'date' => '2026-08-01', 'capacity' => 150, 'registered' => 90, 'status' => 'upcoming'],
            ['title' => 'Masterclass : Pitch investisseur', 'speaker' => 'Jean Claude Mvondo', 'location' => 'Douala Tech Park', 'country' => 'Cameroon', 'date' => '2026-07-18', 'capacity' => 100, 'registered' => 100, 'status' => 'ongoing'],
            ['title' => 'Masterclass : Storytelling de marque', 'speaker' => 'Aisha Kone', 'location' => 'En ligne', 'country' => 'Mali', 'date' => '2026-06-05', 'capacity' => 200, 'registered' => 175, 'status' => 'completed'],
        ];

        foreach ($demo as $row) {
            Masterclass::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
