<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminNotificationSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Maintenance prévue', 'message' => 'Une maintenance est prévue ce week-end de 22h à 23h.', 'audience' => 'all', 'status' => 'sent', 'sent_at' => now()->subDay(), 'recipients_count' => User::count()],
            ['title' => 'Nouveau badge Leader', 'message' => 'Félicitations, vous avez débloqué le badge Leader !', 'audience' => 'leaders', 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            AdminNotification::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
