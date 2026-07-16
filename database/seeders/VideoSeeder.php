<?php

namespace Database\Seeders;

use App\Models\Video;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Présentation de Generation PUSH', 'video_url' => 'https://www.youtube.com/watch?v=demo4', 'duration' => '3:45', 'views' => 890, 'status' => 'published'],
            ['title' => 'Interview : parcours d\'une leader', 'video_url' => 'https://www.youtube.com/watch?v=demo5', 'duration' => '8:12', 'views' => 456, 'status' => 'published'],
            ['title' => 'Best-of Conférence 2025', 'video_url' => null, 'duration' => '5:30', 'views' => 0, 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            Video::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
