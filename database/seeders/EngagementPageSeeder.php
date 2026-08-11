<?php

namespace Database\Seeders;

use App\Models\EngagementPage;
use Illuminate\Database\Seeder;

class EngagementPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'type' => 'partner',
                'title' => 'Devenir partenaire',
                'subtitle' => 'Associe ta marque à une communauté de leaders engagés',
                'description' => "Generation PUSH collabore avec des entreprises, institutions et organisations qui "
                    . "partagent notre vision d'un continent africain porté par une nouvelle génération de leaders. "
                    . "En devenant partenaire, tu soutiens directement nos formations, nos conférences et nos "
                    . "programmes de mentorat, tout en gagnant en visibilité auprès d'une communauté de plus de "
                    . "2500 jeunes talents à travers l'Afrique de l'Ouest.",
                'benefits' => "Visibilité auprès de 2500+ membres actifs\nLogo affiché sur le site et lors des événements\nAccès privilégié à nos conférences et formations\nRapport d'impact personnalisé\nOpportunités de co-création de contenu",
                'cta_label' => 'Devenir partenaire',
                'is_visible' => true,
            ],
            [
                'type' => 'volunteer',
                'title' => 'Devenir bénévole',
                'subtitle' => 'Donne de ton temps pour faire grandir la prochaine génération',
                'description' => "Que tu sois formateur, mentor, ou simplement passionné par le développement du "
                    . "leadership en Afrique, Generation PUSH a besoin de bénévoles engagés pour animer des "
                    . "ateliers, accompagner des membres, ou soutenir l'organisation de nos événements. "
                    . "Rejoins une équipe qui a un impact concret, à ton rythme.",
                'benefits' => "Impact direct sur la vie de jeunes leaders\nFlexibilité selon ton emploi du temps\nRéseau de bénévoles et mentors engagés\nCertificat de bénévolat\nAccès gratuit à certaines formations",
                'cta_label' => 'Devenir bénévole',
                'is_visible' => true,
            ],
        ];

        foreach ($pages as $data) {
            EngagementPage::updateOrCreate(['type' => $data['type']], $data);
        }
    }
}
