<?php

namespace Database\Seeders;

use App\Models\Conference;
use Illuminate\Database\Seeder;

class ConferenceSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Leadership dans un monde en transformation', 'speaker' => 'Débora Tapsoba', 'location' => 'Bamako Conference Center', 'country' => 'Mali', 'date' => '2026-08-15', 'capacity' => 500, 'registered' => 385, 'status' => 'upcoming'],
            ['title' => 'Innovation et entrepreneuriat africain', 'speaker' => 'Jean Claude Mvondo', 'location' => 'Yaoundé Business Hub', 'country' => 'Cameroon', 'date' => '2026-07-20', 'capacity' => 300, 'registered' => 298, 'status' => 'ongoing'],
            ['title' => 'Communication persuasive et influence', 'speaker' => 'Aisha Kone', 'location' => 'Dakar Convention Center', 'country' => 'Senegal', 'date' => '2026-06-10', 'capacity' => 400, 'registered' => 350, 'status' => 'completed'],
            ['title' => 'Stratégie business pour jeunes entrepreneurs', 'speaker' => 'Kwame Asante', 'location' => 'Accra Tech Hub', 'country' => 'Ghana', 'date' => '2026-08-25', 'capacity' => 250, 'registered' => 180, 'status' => 'upcoming'],
            ['title' => 'Femmes leaders en Afrique', 'speaker' => 'Grace Mwangi', 'location' => 'Nairobi Convention Hall', 'country' => 'Kenya', 'date' => '2026-09-05', 'capacity' => 350, 'registered' => 0, 'status' => 'upcoming'],
        ];

        foreach ($demo as $row) {
            Conference::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
