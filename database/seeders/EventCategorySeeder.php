<?php

namespace Database\Seeders;

use App\Models\EventCategory;
use Illuminate\Database\Seeder;

class EventCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [

            [
                'name' => 'Conférence',
                'slug' => 'conference',
                'icon' => 'bi-mic-fill',
                'color' => '#E8631A',
                'description' => 'Conférences et rencontres autour du leadership, de l’impact et du développement personnel.',
                'is_active' => true,
            ],

            [
                'name' => 'Masterclass',
                'slug' => 'masterclass',
                'icon' => 'bi-mortarboard-fill',
                'color' => '#1A1A1A',
                'description' => 'Sessions intensives animées par des experts et professionnels.',
                'is_active' => true,
            ],

            [
                'name' => 'Coaching',
                'slug' => 'coaching',
                'icon' => 'bi-person-check-fill',
                'color' => '#E8631A',
                'description' => 'Sessions individuelles ou collectives de coaching.',
                'is_active' => true,
            ],

            [
                'name' => 'PushConnect',
                'slug' => 'pushconnect',
                'icon' => 'bi-people-fill',
                'color' => '#1A1A1A',
                'description' => 'Rencontres Generation PUSH dédiées au réseautage et aux connexions.',
                'is_active' => true,
            ],

            [
                'name' => 'Push Reading Session',
                'slug' => 'push-reading-session',
                'icon' => 'bi-book-fill',
                'color' => '#E8631A',
                'description' => 'Rencontres de lecture, d’échange et de partage autour de livres sélectionnés.',
                'is_active' => true,
            ],

            [
                'name' => 'Atelier',
                'slug' => 'atelier',
                'icon' => 'bi-tools',
                'color' => '#1A1A1A',
                'description' => 'Ateliers pratiques permettant aux participants de développer des compétences.',
                'is_active' => true,
            ],

            [
                'name' => 'Webinaire',
                'slug' => 'webinaire',
                'icon' => 'bi-camera-video-fill',
                'color' => '#E8631A',
                'description' => 'Événements et formations organisés entièrement en ligne.',
                'is_active' => true,
            ],

            [
                'name' => 'Sommet',
                'slug' => 'sommet',
                'icon' => 'bi-globe-africa',
                'color' => '#1A1A1A',
                'description' => 'Grands rassemblements et sommets organisés par Generation PUSH.',
                'is_active' => true,
            ],

            [
                'name' => 'Networking',
                'slug' => 'networking',
                'icon' => 'bi-diagram-3-fill',
                'color' => '#E8631A',
                'description' => 'Rencontres professionnelles et sessions de mise en relation.',
                'is_active' => true,
            ],

        ];

        foreach ($categories as $category) {

            EventCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );

        }
    }
}
