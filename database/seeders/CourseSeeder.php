<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Formation;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $formation = Formation::where('name', 'Leadership africain au XXIe siècle')->first();

        if (! $formation) {
            return;
        }

        $lessons = [
            ['title' => 'Introduction au leadership', 'duration' => '10 min', 'order' => 1, 'status' => 'published'],
            ['title' => 'Les fondamentaux de la vision', 'duration' => '15 min', 'order' => 2, 'status' => 'published'],
            ['title' => 'Communiquer avec impact', 'duration' => '18 min', 'order' => 3, 'status' => 'published'],
            ['title' => 'Gérer les situations de crise', 'duration' => '20 min', 'order' => 4, 'status' => 'draft'],
        ];

        foreach ($lessons as $lesson) {
            Course::updateOrCreate(
                ['formation_id' => $formation->id, 'title' => $lesson['title']],
                [...$lesson, 'video_url' => 'https://www.youtube.com/watch?v=demo']
            );
        }
    }
}
