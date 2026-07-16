<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Le Guide du Leader Africain', 'type' => 'book', 'is_free' => false, 'price' => 19.99, 'status' => 'published'],
            ['title' => 'Extrait gratuit : Premiers pas en entrepreneuriat', 'type' => 'book', 'is_free' => true, 'price' => 0, 'status' => 'published'],
            ['title' => 'Masterclass : Négocier comme un pro (replay)', 'type' => 'video', 'is_free' => false, 'price' => 29.99, 'video_url' => 'https://www.youtube.com/watch?v=demo3', 'status' => 'published'],
            ['title' => 'Clé USB — Pack complet Formations 2026', 'type' => 'usb_key', 'is_free' => false, 'price' => 15.00, 'stock' => 42, 'status' => 'published'],
            ['title' => 'Clé USB — Archives Conférences 2025', 'type' => 'usb_key', 'is_free' => false, 'price' => 12.00, 'stock' => 0, 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            Product::updateOrCreate(
                ['title' => $row['title']],
                [...$row, 'slug' => Str::slug($row['title']) . '-' . Str::random(5)]
            );
        }
    }
}
