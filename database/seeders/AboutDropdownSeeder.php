<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class AboutDropdownSeeder extends Seeder
{
    public function run(): void
    {
        // Supprime l'ancien lien simple "À propos" (créé par PagesSeeder), s'il existe et n'a pas d'enfants,
        // pour éviter un doublon avec le nouveau menu déroulant ci-dessous.
        MenuItem::where('label', 'À propos')
            ->where('location', 'navbar')
            ->whereNull('parent_id')
            ->where('url', '/a-propos')
            ->delete();

        $parent = MenuItem::updateOrCreate(
            ['label' => 'À propos', 'location' => 'navbar', 'parent_id' => null],
            ['url' => '#', 'order' => 2, 'status' => 'active']
        );

        $children = [
            ['label' => 'Notre histoire', 'url' => '/a-propos', 'order' => 1],
            ['label' => 'Notre fondatrice', 'url' => '/fondatrice', 'order' => 2],
            ['label' => 'Devenir partenaire', 'url' => '/devenir-partenaire', 'order' => 3],
            ['label' => 'Devenir bénévole', 'url' => '/devenir-benevole', 'order' => 4],
        ];

        foreach ($children as $child) {
            MenuItem::updateOrCreate(
                ['parent_id' => $parent->id, 'label' => $child['label']],
                [...$child, 'location' => 'navbar', 'status' => 'active']
            );
        }
    }
}
