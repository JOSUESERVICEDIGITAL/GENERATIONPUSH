<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        SiteSetting::query()->firstOrCreate([], [
            'hero_title' => 'Formons les leaders africains de demain',
            'hero_subtitle' => 'Rejoins +2 500 membres',
            'hero_description' => "Formations, conférences et un réseau de mentors pour révéler le leader qui est en toi.",
            'hero_cta_label' => 'Rejoindre la communauté',
            'hero_cta_url' => '/register',
            'about_title' => 'Une communauté panafricaine de leadership',
            'about_text' => "Generation PUSH accompagne des milliers de jeunes leaders à travers l'Afrique de l'Ouest à travers des formations, des conférences et un réseau de mentors engagés depuis 2020.",
            'contact_email' => 'contact@generationpush.com',
            'contact_phone' => '+226 25 00 00 00',
            'contact_address' => 'Ouagadougou, Burkina Faso',
            'newsletter_title' => 'Reste informé',
            'newsletter_text' => 'Reçois nos actualités, formations et événements directement par email.',
            'meta_title' => 'Generation PUSH — La communauté des leaders africains',
            'meta_description' => 'Generation PUSH forme et connecte les leaders africains de demain à travers formations, conférences et mentorat.',
        ]);

        $navbar = [
            ['label' => 'Accueil', 'url' => '/', 'order' => 1],
            ['label' => 'Programmes', 'url' => '/programmes', 'order' => 2],
            ['label' => 'Événements', 'url' => '/evenements', 'order' => 3],
            ['label' => 'Blog', 'url' => '/blog', 'order' => 4],
            ['label' => 'Boutique', 'url' => '/boutique', 'order' => 5],
            ['label' => 'Contact', 'url' => '/contact', 'order' => 6],
        ];

        foreach ($navbar as $item) {
            MenuItem::updateOrCreate(
                ['label' => $item['label'], 'location' => 'navbar'],
                [...$item, 'location' => 'navbar', 'status' => 'active']
            );
        }

        $footer = [
            ['label' => 'À propos', 'url' => '/a-propos', 'footer_column' => 'Liens rapides', 'order' => 1],
            ['label' => 'Programmes', 'url' => '/programmes', 'footer_column' => 'Liens rapides', 'order' => 2],
            ['label' => 'Blog', 'url' => '/blog', 'footer_column' => 'Liens rapides', 'order' => 3],
            ['label' => 'FAQ', 'url' => '/page/faq', 'footer_column' => 'Ressources', 'order' => 1],
            ['label' => 'Contact', 'url' => '/contact', 'footer_column' => 'Ressources', 'order' => 2],
            ['label' => "Conditions d'utilisation", 'url' => '/page/cgu', 'footer_column' => 'Légal', 'order' => 1],
            ['label' => 'Confidentialité', 'url' => '/page/confidentialite', 'footer_column' => 'Légal', 'order' => 2],
        ];

        foreach ($footer as $item) {
            MenuItem::updateOrCreate(
                ['label' => $item['label'], 'location' => 'footer'],
                [...$item, 'location' => 'footer', 'status' => 'active']
            );
        }
    }
}
