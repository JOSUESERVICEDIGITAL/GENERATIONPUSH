<?php

namespace Database\Seeders;

use App\Models\Formation;
use Illuminate\Database\Seeder;

class FormationSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['name' => 'Leadership africain au XXIe siècle', 'trainer' => 'Débora Tapsoba', 'duration' => '12 semaines', 'participants' => 250, 'price' => 99, 'status' => 'active'],
            ['name' => 'Entrepreneuriat et innovation', 'trainer' => 'Jean Claude Mvondo', 'duration' => '8 semaines', 'participants' => 180, 'price' => 79, 'status' => 'active'],
            ['name' => 'Communication persuasive', 'trainer' => 'Aisha Kone', 'duration' => '6 semaines', 'participants' => 120, 'price' => 49, 'status' => 'active'],
            ['name' => 'Gestion de projet', 'trainer' => 'Kwame Asante', 'duration' => '10 semaines', 'participants' => 95, 'price' => 89, 'status' => 'draft'],
            ['name' => 'Développement personnel avancé', 'trainer' => 'Grace Mwangi', 'duration' => '12 semaines', 'participants' => 0, 'price' => 129, 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            Formation::updateOrCreate(['name' => $row['name']], $row);
        }
    }
}
