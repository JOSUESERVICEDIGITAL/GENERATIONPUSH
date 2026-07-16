<?php

namespace Database\Seeders;

use App\Models\SmsCampaign;
use App\Models\User;
use Illuminate\Database\Seeder;

class SmsCampaignSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['title' => 'Rappel conférence', 'message' => 'Rappel : la conférence Leadership débute demain à 9h. À bientôt !', 'audience' => 'all', 'status' => 'sent', 'sent_at' => now()->subDays(2), 'recipients_count' => User::count()],
            ['title' => 'Confirmation paiement', 'message' => 'Votre paiement a bien été reçu. Merci pour votre confiance.', 'audience' => 'members', 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            SmsCampaign::updateOrCreate(['title' => $row['title']], $row);
        }
    }
}
