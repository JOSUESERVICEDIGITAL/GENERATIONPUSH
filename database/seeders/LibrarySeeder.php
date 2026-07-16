<?php

namespace Database\Seeders;

use App\Models\LibraryResource;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Guide du leader africain 2026', 'type' => 'pdf', 'url' => 'https://example.com/guide-leader.pdf', 'downloads' => 128, 'status' => 'published'],
            ['title' => 'Modèle de plan d\'affaires', 'type' => 'doc', 'url' => 'https://example.com/business-plan.docx', 'downloads' => 76, 'status' => 'published'],
            ['title' => 'Webinaire : lever des fonds en Afrique', 'type' => 'video', 'url' => 'https://www.youtube.com/watch?v=demo2', 'downloads' => 210, 'status' => 'published'],
            ['title' => 'Boîte à outils entrepreneuriat', 'type' => 'link', 'url' => 'https://example.com/toolkit', 'downloads' => 45, 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            LibraryResource::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
