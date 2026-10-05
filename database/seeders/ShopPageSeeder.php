<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class ShopPageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            ['key' => 'shop'],
            [
                /*
                |--------------------------------------------------------------------------
                | Informations générales
                |--------------------------------------------------------------------------
                */

                'title' => 'Boutique',
                'subtitle' => 'Des ressources pour apprendre, grandir et passer à l’action.',
                'status' => 'active',

                /*
                |--------------------------------------------------------------------------
                | Hero
                |--------------------------------------------------------------------------
                */

                'shop_hero_eyebrow' => 'Boutique Generation PUSH',

                'shop_hero_title' => 'Des ressources pour',

                'shop_hero_highlight' => 'passer à l’action.',

                'shop_hero_text' => 'Livres, contenus vidéo et ressources sélectionnées pour développer tes compétences, renforcer ton leadership et transformer tes ambitions en actions concrètes.',

                /*
                |--------------------------------------------------------------------------
                | Catalogue
                |--------------------------------------------------------------------------
                */

                'shop_catalogue_eyebrow' => 'Ressources',

                'shop_catalogue_title' => 'Continue à',

                'shop_catalogue_highlight' => 'grandir.',

                'shop_catalogue_text' => 'Explore les ressources Generation PUSH et trouve les outils qui peuvent accompagner ta prochaine étape.',

                /*
                |--------------------------------------------------------------------------
                | CTA final
                |--------------------------------------------------------------------------
                */

                'shop_cta_eyebrow' => 'Generation PUSH',

                'shop_cta_title' => 'Apprendre ne suffit pas.',

                'shop_cta_highlight' => 'Il faut agir.',

                'shop_cta_text' => 'Chaque ressource Generation PUSH est pensée pour t’aider à transformer ce que tu apprends en compétences, décisions et actions concrètes.',
            ]
        );
    }
}