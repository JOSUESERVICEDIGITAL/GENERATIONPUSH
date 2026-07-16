<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['name' => 'Débora Tapsoba', 'role' => 'Fondatrice & Directrice générale', 'bio' => 'Passionnée de leadership et d\'entrepreneuriat en Afrique de l\'Ouest.', 'email' => 'debora@generationpush.com', 'order' => 1, 'status' => 'active'],
            ['name' => 'Jean Claude Mvondo', 'role' => 'Directeur des Programmes', 'bio' => 'Expert en développement de programmes de formation.', 'email' => 'jc@generationpush.com', 'order' => 2, 'status' => 'active'],
            ['name' => 'Aisha Kone', 'role' => 'Responsable Communication', 'bio' => 'Spécialiste en storytelling et communication de marque.', 'email' => 'aisha@generationpush.com', 'order' => 3, 'status' => 'active'],
            ['name' => 'Kwame Asante', 'role' => 'Responsable Partenariats', 'bio' => 'Construit des ponts entre entreprises et jeunes leaders.', 'email' => 'kwame@generationpush.com', 'order' => 4, 'status' => 'inactive'],
        ];

        foreach ($demo as $row) {
            TeamMember::updateOrCreate(['name' => $row['name']], $row);
        }
    }
}
