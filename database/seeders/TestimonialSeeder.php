<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['author_name' => 'Aisha Kone', 'author_role' => 'Entrepreneure, Bamako', 'content' => 'Generation PUSH a transformé ma façon de voir le leadership. Les formations sont d\'une qualité exceptionnelle.', 'rating' => 5, 'featured' => true, 'status' => 'published'],
            ['author_name' => 'Jean Claude Mvondo', 'author_role' => 'Directeur, Yaoundé', 'content' => 'Un réseau incroyable et des mentors qui se soucient vraiment de notre réussite.', 'rating' => 5, 'featured' => true, 'status' => 'published'],
            ['author_name' => 'Grace Mwangi', 'author_role' => 'Coach, Nairobi', 'content' => 'Les conférences sont toujours inspirantes, j\'y retourne à chaque édition.', 'rating' => 4, 'featured' => false, 'status' => 'published'],
            ['author_name' => 'Pierre Durand', 'author_role' => 'Consultant, Abidjan', 'content' => 'Bon programme, encore en train de terminer ma formation mais déjà très satisfait.', 'rating' => 4, 'featured' => false, 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            Testimonial::updateOrCreate(['author_name' => $row['author_name']], $row);
        }
    }
}
