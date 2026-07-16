<?php

namespace Database\Seeders;

use App\Models\CoachingSession;
use Illuminate\Database\Seeder;

class CoachingSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Coaching individuel : plan de carrière', 'coach' => 'Grace Mwangi', 'duration' => '1h', 'date' => '2026-07-20', 'capacity' => 1, 'registered' => 1, 'status' => 'upcoming'],
            ['title' => 'Coaching de groupe : prise de parole', 'coach' => 'Kwame Asante', 'duration' => '2h', 'date' => '2026-07-25', 'capacity' => 15, 'registered' => 9, 'status' => 'upcoming'],
            ['title' => 'Coaching : gestion du stress entrepreneurial', 'coach' => 'Pierre Durand', 'duration' => '1h30', 'date' => '2026-06-10', 'capacity' => 10, 'registered' => 10, 'status' => 'completed'],
        ];

        foreach ($demo as $row) {
            CoachingSession::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
