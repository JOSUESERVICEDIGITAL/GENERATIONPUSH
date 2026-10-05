<?php

namespace Database\Seeders;

use App\Models\Conference;
use Illuminate\Database\Seeder;

class ConferenceSeeder extends Seeder
{
    public function run(): void
    {
        $demo = [

            /*
            |--------------------------------------------------------------------------
            | OCTOBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Leadership africain : oser prendre sa place',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Urban Hotel Kénitra',
                'country' => 'Maroc',
                'date' => '2026-10-10',
                'capacity' => 250,
                'registered' => 184,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Jeunesse africaine : de la vision à l’impact',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Urban Hotel Kénitra',
                'country' => 'Maroc',
                'date' => '2026-10-24',
                'capacity' => 350,
                'registered' => 218,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Entreprendre jeune en Afrique',
                'speaker' => 'Kwame Asante',
                'location' => 'Rabat Business Center',
                'country' => 'Maroc',
                'date' => '2026-10-31',
                'capacity' => 300,
                'registered' => 156,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | NOVEMBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Leadership dans un monde en transformation',
                'speaker' => 'Grace Mwangi',
                'location' => 'Casablanca Conference Center',
                'country' => 'Maroc',
                'date' => '2026-11-07',
                'capacity' => 500,
                'registered' => 285,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Innovation et entrepreneuriat africain',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Yaoundé Business Hub',
                'country' => 'Cameroun',
                'date' => '2026-11-14',
                'capacity' => 300,
                'registered' => 198,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Femmes leaders : construire une nouvelle génération',
                'speaker' => 'Aisha Koné',
                'location' => 'Dakar Convention Center',
                'country' => 'Sénégal',
                'date' => '2026-11-21',
                'capacity' => 400,
                'registered' => 245,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Transformer son potentiel en impact',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Marrakech Convention Center',
                'country' => 'Maroc',
                'date' => '2026-11-28',
                'capacity' => 450,
                'registered' => 271,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | DÉCEMBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Communication persuasive et influence positive',
                'speaker' => 'Aisha Koné',
                'location' => 'Ouaga 2000 Conference Hall',
                'country' => 'Burkina Faso',
                'date' => '2026-12-05',
                'capacity' => 500,
                'registered' => 310,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Stratégie business pour jeunes entrepreneurs',
                'speaker' => 'Kwame Asante',
                'location' => 'Accra Tech Hub',
                'country' => 'Ghana',
                'date' => '2026-12-12',
                'capacity' => 350,
                'registered' => 204,
                'status' => 'upcoming',
            ],

            [
                'title' => 'La jeunesse au cœur de la transformation africaine',
                'speaker' => 'Moussa Ouédraogo',
                'location' => 'Bamako Conference Center',
                'country' => 'Mali',
                'date' => '2026-12-19',
                'capacity' => 450,
                'registered' => 240,
                'status' => 'upcoming',
            ],

            [
                'title' => 'PUSH 2027 : vision, leadership et passage à l’action',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Kénitra Convention Hall',
                'country' => 'Maroc',
                'date' => '2026-12-26',
                'capacity' => 600,
                'registered' => 348,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | JANVIER 2027
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Construire une vision qui transforme',
                'speaker' => 'Grace Mwangi',
                'location' => 'Nairobi Convention Hall',
                'country' => 'Kenya',
                'date' => '2027-01-09',
                'capacity' => 400,
                'registered' => 124,
                'status' => 'upcoming',
            ],

            [
                'title' => 'La discipline derrière les grands accomplissements',
                'speaker' => 'David Mensah',
                'location' => 'Accra International Center',
                'country' => 'Ghana',
                'date' => '2027-01-16',
                'capacity' => 350,
                'registered' => 97,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Entrepreneuriat digital et nouvelles opportunités africaines',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Abidjan Innovation Hub',
                'country' => 'Côte d’Ivoire',
                'date' => '2027-01-23',
                'capacity' => 450,
                'registered' => 138,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Une génération qui ose : leadership et responsabilité',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Rabat Conference Center',
                'country' => 'Maroc',
                'date' => '2027-01-30',
                'capacity' => 500,
                'registered' => 165,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | FÉVRIER / MARS 2027
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Créer un réseau professionnel qui ouvre des portes',
                'speaker' => 'Sarah Kouamé',
                'location' => 'Dakar Business Center',
                'country' => 'Sénégal',
                'date' => '2027-02-13',
                'capacity' => 350,
                'registered' => 89,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Devenir un leader d’impact en Afrique',
                'speaker' => 'Moussa Ouédraogo',
                'location' => 'Ouagadougou International Conference Center',
                'country' => 'Burkina Faso',
                'date' => '2027-02-27',
                'capacity' => 600,
                'registered' => 173,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Femmes, innovation et leadership',
                'speaker' => 'Grace Mwangi',
                'location' => 'Nairobi Convention Hall',
                'country' => 'Kenya',
                'date' => '2027-03-13',
                'capacity' => 500,
                'registered' => 126,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Generation PUSH Africa Summit 2027',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Casablanca International Conference Center',
                'country' => 'Maroc',
                'date' => '2027-03-27',
                'capacity' => 1000,
                'registered' => 284,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | ÉVÉNEMENTS TERMINÉS
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Communication et influence pour jeunes leaders',
                'speaker' => 'Aisha Koné',
                'location' => 'Dakar Convention Center',
                'country' => 'Sénégal',
                'date' => '2026-06-10',
                'capacity' => 400,
                'registered' => 350,
                'status' => 'completed',
            ],

            [
                'title' => 'Entrepreneuriat africain : saisir les opportunités',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Yaoundé Business Hub',
                'country' => 'Cameroun',
                'date' => '2026-07-20',
                'capacity' => 300,
                'registered' => 298,
                'status' => 'completed',
            ],

            [
                'title' => 'Femmes leaders en Afrique',
                'speaker' => 'Grace Mwangi',
                'location' => 'Nairobi Convention Hall',
                'country' => 'Kenya',
                'date' => '2026-09-05',
                'capacity' => 350,
                'registered' => 350,
                'status' => 'completed',
            ],
        ];


        foreach ($demo as $row) {

            Conference::updateOrCreate(
                ['title' => $row['title']],
                $row
            );

        }
    }
}
