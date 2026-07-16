<?php

namespace Database\Seeders;

use App\Models\NewsletterCampaign;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsletterCampaignSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [
            ['subject' => 'Bienvenue chez Generation PUSH !', 'content' => 'Merci de rejoindre notre communauté de leaders africains.', 'audience' => 'all', 'status' => 'sent', 'sent_at' => now()->subDays(10), 'recipients_count' => User::count()],
            ['subject' => 'Nouvelle formation disponible', 'content' => 'Découvrez notre nouvelle formation sur le leadership.', 'audience' => 'members', 'status' => 'sent', 'sent_at' => now()->subDays(3), 'recipients_count' => User::where('role', 'Member')->count()],
            ['subject' => 'Newsletter mensuelle — Juillet', 'content' => 'Le récapitulatif du mois.', 'audience' => 'all', 'status' => 'draft'],
        ];

        foreach ($demo as $row) {
            NewsletterCampaign::updateOrCreate(['subject' => $row['subject']], $row);
        }
    }
}
