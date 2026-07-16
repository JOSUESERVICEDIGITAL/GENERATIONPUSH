<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Leadership', 'color' => '#E8631A', 'description' => 'Articles sur le leadership et le développement personnel.'],
            ['name' => 'Entrepreneuriat', 'color' => '#2563EB', 'description' => 'Conseils et retours d\'expérience pour entrepreneurs.'],
            ['name' => 'Actualités', 'color' => '#22C55E', 'description' => 'Les dernières nouvelles de Generation PUSH.'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['name' => $cat['name']], $cat);
        }

        $admin = User::where('email', 'admin@generationpush.com')->first();
        $leadership = Category::where('name', 'Leadership')->first();
        $entrepreneuriat = Category::where('name', 'Entrepreneuriat')->first();

        $posts = [
            ['title' => '5 qualités essentielles d\'un bon leader', 'category_id' => $leadership?->id, 'excerpt' => 'Ce qui distingue les grands leaders.', 'content' => "Le leadership ne s'improvise pas...", 'status' => 'published', 'views' => 340],
            ['title' => 'Comment lever des fonds en Afrique de l\'Ouest', 'category_id' => $entrepreneuriat?->id, 'excerpt' => 'Guide pratique pour les entrepreneurs.', 'content' => "Lever des fonds est un défi majeur...", 'status' => 'published', 'views' => 512],
            ['title' => 'Bilan de la conférence 2026', 'category_id' => null, 'excerpt' => 'Retour sur un événement marquant.', 'content' => "La conférence annuelle s'est tenue...", 'status' => 'draft', 'views' => 0],
        ];

        foreach ($posts as $row) {
            Post::updateOrCreate(
                ['title' => $row['title']],
                [...$row, 'user_id' => $admin?->id]
            );
        }
    }
}
