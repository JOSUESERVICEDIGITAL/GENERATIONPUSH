<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Ouverture de la conférence', 'album' => 'Conférence Bamako 2026', 'status' => 'published'],
            ['title' => 'Session de networking', 'album' => 'Conférence Bamako 2026', 'status' => 'published'],
            ['title' => 'Remise des certificats', 'album' => 'Formations 2026', 'status' => 'published'],
        ];

        foreach ($demo as $row) {
            GalleryImage::updateOrCreate(
                ['title' => $row['title']],
                [...$row, 'image_path' => null]
            );
        }
    }
}
