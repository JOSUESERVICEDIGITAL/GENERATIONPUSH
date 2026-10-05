<?php

namespace Database\Seeders;

use App\Models\CoachingSession;
use Illuminate\Database\Seeder;

class CoachingSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [

            /*
            |--------------------------------------------------------------------------
            | SESSIONS À VENIR — OCTOBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Coaching individuel : construire son plan de carrière',
                'coach' => 'Grace Mwangi',
                'duration' => '1h',
                'date' => '2026-10-12',
                'capacity' => 1,
                'registered' => 0,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Prise de parole en public : parler avec impact',
                'coach' => 'Kwame Asante',
                'duration' => '2h',
                'date' => '2026-10-17',
                'capacity' => 15,
                'registered' => 9,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Développer sa confiance en soi',
                'coach' => 'Débora Hermine N. Tapsoba',
                'duration' => '1h30',
                'date' => '2026-10-24',
                'capacity' => 20,
                'registered' => 13,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Leadership personnel : devenir acteur de son avenir',
                'coach' => 'Aïcha Traoré',
                'duration' => '2h',
                'date' => '2026-10-31',
                'capacity' => 25,
                'registered' => 18,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | NOVEMBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Construire une vision claire pour son avenir',
                'coach' => 'Moussa Ouédraogo',
                'duration' => '1h30',
                'date' => '2026-11-07',
                'capacity' => 20,
                'registered' => 8,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Entrepreneuriat : passer de l’idée à l’action',
                'coach' => 'Fatou Ndiaye',
                'duration' => '2h',
                'date' => '2026-11-14',
                'capacity' => 30,
                'registered' => 21,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Personal Branding : construire une image professionnelle forte',
                'coach' => 'Sarah Kouamé',
                'duration' => '2h',
                'date' => '2026-11-21',
                'capacity' => 25,
                'registered' => 14,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Gestion du temps et discipline personnelle',
                'coach' => 'David Mensah',
                'duration' => '1h30',
                'date' => '2026-11-28',
                'capacity' => 20,
                'registered' => 11,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | DÉCEMBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Transformer ses objectifs en résultats',
                'coach' => 'Débora Hermine N. Tapsoba',
                'duration' => '2h',
                'date' => '2026-12-05',
                'capacity' => 30,
                'registered' => 19,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Développer son intelligence émotionnelle',
                'coach' => 'Grace Mwangi',
                'duration' => '1h30',
                'date' => '2026-12-12',
                'capacity' => 20,
                'registered' => 7,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Préparer son année 2027 : vision, objectifs et stratégie',
                'coach' => 'Kwame Asante',
                'duration' => '2h30',
                'date' => '2026-12-19',
                'capacity' => 40,
                'registered' => 24,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | 2027
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Leadership africain et impact communautaire',
                'coach' => 'Moussa Ouédraogo',
                'duration' => '2h',
                'date' => '2027-01-09',
                'capacity' => 30,
                'registered' => 6,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Construire son réseau professionnel',
                'coach' => 'Aïcha Traoré',
                'duration' => '1h30',
                'date' => '2027-01-16',
                'capacity' => 25,
                'registered' => 5,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Jeune entrepreneur : structurer son premier projet',
                'coach' => 'Fatou Ndiaye',
                'duration' => '2h',
                'date' => '2027-01-23',
                'capacity' => 35,
                'registered' => 12,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Négociation et communication professionnelle',
                'coach' => 'David Mensah',
                'duration' => '2h',
                'date' => '2027-02-06',
                'capacity' => 25,
                'registered' => 4,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Devenir un leader qui inspire par l’action',
                'coach' => 'Débora Hermine N. Tapsoba',
                'duration' => '2h',
                'date' => '2027-02-20',
                'capacity' => 40,
                'registered' => 9,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | SESSIONS TERMINÉES — POUR TESTER L'HISTORIQUE
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Gestion du stress entrepreneurial',
                'coach' => 'Pierre Durand',
                'duration' => '1h30',
                'date' => '2026-06-10',
                'capacity' => 10,
                'registered' => 10,
                'status' => 'completed',
            ],

            [
                'title' => 'Trouver sa voie professionnelle',
                'coach' => 'Grace Mwangi',
                'duration' => '1h',
                'date' => '2026-07-20',
                'capacity' => 12,
                'registered' => 12,
                'status' => 'completed',
            ],

            [
                'title' => 'Les fondamentaux du leadership',
                'coach' => 'Kwame Asante',
                'duration' => '2h',
                'date' => '2026-08-15',
                'capacity' => 25,
                'registered' => 25,
                'status' => 'completed',
            ],

            [
                'title' => 'Oser entreprendre en Afrique',
                'coach' => 'Fatou Ndiaye',
                'duration' => '2h',
                'date' => '2026-09-12',
                'capacity' => 30,
                'registered' => 30,
                'status' => 'completed',
            ],

            [
                'title' => 'Confiance en soi et passage à l’action',
                'coach' => 'Débora Hermine N. Tapsoba',
                'duration' => '1h30',
                'date' => '2026-09-26',
                'capacity' => 25,
                'registered' => 25,
                'status' => 'completed',
            ],
        ];


        foreach ($demo as $row) {

            CoachingSession::updateOrCreate(
                [
                    'title' => $row['title'],
                ],
                $row
            );

        }
    }
}
