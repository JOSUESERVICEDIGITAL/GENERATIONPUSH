<?php

namespace Database\Seeders;

use App\Models\Sponsor;
use Illuminate\Database\Seeder;

class SponsorSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['name' => 'Orange Burkina Faso', 'tier' => 'platinum', 'status' => 'active', 'order' => 1],
            ['name' => 'Ecobank', 'tier' => 'gold', 'status' => 'active', 'order' => 2],
            ['name' => 'MTN Group', 'tier' => 'gold', 'status' => 'active', 'order' => 3],
            ['name' => 'UVBF', 'tier' => 'silver', 'status' => 'active', 'order' => 4],
            ['name' => 'Bank of Africa', 'tier' => 'bronze', 'status' => 'inactive', 'order' => 5],
        ];

        foreach ($demo as $row) {
            Sponsor::updateOrCreate(['name' => $row['name']], $row);
        }
    }
}
