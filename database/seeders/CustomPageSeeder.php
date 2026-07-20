<?php

namespace Database\Seeders;

use App\Models\CustomPage;
use Illuminate\Database\Seeder;

class CustomPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'content' => "Questions fréquentes\n\nComment devenir membre ?\nInscris-toi via le bouton \"Rejoindre la communauté\" en haut du site.\n\nLes formations sont-elles payantes ?\nCertaines sont gratuites, d'autres payantes — le prix est indiqué sur chaque fiche formation.\n\nComment contacter l'équipe ?\nUtilise le formulaire de la page Contact.",
                'status' => 'published',
            ],
            [
                'title' => "Conditions d'utilisation",
                'slug' => 'cgu',
                'content' => "Conditions générales d'utilisation\n\nEn utilisant ce site, tu acceptes les présentes conditions. Le contenu de Generation PUSH est destiné à un usage personnel et non commercial sauf accord contraire.",
                'status' => 'published',
            ],
            [
                'title' => 'Confidentialité',
                'slug' => 'confidentialite',
                'content' => "Politique de confidentialité\n\nGeneration PUSH collecte uniquement les données nécessaires à la gestion de ton compte et de tes inscriptions. Aucune donnée n'est revendue à des tiers.",
                'status' => 'published',
            ],
        ];

        foreach ($pages as $page) {
            CustomPage::updateOrCreate(['slug' => $page['slug']], $page);
        }
    }
}
