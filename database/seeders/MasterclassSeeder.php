<?php

namespace Database\Seeders;

use App\Models\Masterclass;
use Illuminate\Database\Seeder;

class MasterclassSeeder extends Seeder
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
                'title' => 'Masterclass : Négocier comme un pro',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'En ligne',
                'country' => 'Burkina Faso',
                'date' => '2026-10-08',
                'capacity' => 150,
                'registered' => 90,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Construire un pitch qui convainc',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Rabat Business Center',
                'country' => 'Maroc',
                'date' => '2026-10-15',
                'capacity' => 120,
                'registered' => 74,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Prendre la parole avec confiance',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Urban Hotel Kénitra',
                'country' => 'Maroc',
                'date' => '2026-10-22',
                'capacity' => 180,
                'registered' => 112,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Storytelling et communication d’impact',
                'speaker' => 'Aisha Koné',
                'location' => 'En ligne',
                'country' => 'Sénégal',
                'date' => '2026-10-29',
                'capacity' => 250,
                'registered' => 137,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | NOVEMBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Masterclass : Transformer une idée en projet',
                'speaker' => 'Kwame Asante',
                'location' => 'Casablanca Innovation Hub',
                'country' => 'Maroc',
                'date' => '2026-11-05',
                'capacity' => 160,
                'registered' => 88,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Personal Branding pour jeunes leaders',
                'speaker' => 'Sarah Kouamé',
                'location' => 'En ligne',
                'country' => 'Côte d’Ivoire',
                'date' => '2026-11-12',
                'capacity' => 300,
                'registered' => 164,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Les fondamentaux du leadership',
                'speaker' => 'Grace Mwangi',
                'location' => 'Nairobi Leadership Center',
                'country' => 'Kenya',
                'date' => '2026-11-19',
                'capacity' => 180,
                'registered' => 105,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Créer son réseau professionnel',
                'speaker' => 'Moussa Ouédraogo',
                'location' => 'Ouagadougou Business Hub',
                'country' => 'Burkina Faso',
                'date' => '2026-11-26',
                'capacity' => 200,
                'registered' => 96,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | DÉCEMBRE 2026
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Masterclass : Entreprendre avec peu de moyens',
                'speaker' => 'Fatou Ndiaye',
                'location' => 'Dakar Entrepreneurship Center',
                'country' => 'Sénégal',
                'date' => '2026-12-03',
                'capacity' => 250,
                'registered' => 143,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Discipline et productivité personnelle',
                'speaker' => 'David Mensah',
                'location' => 'En ligne',
                'country' => 'Ghana',
                'date' => '2026-12-10',
                'capacity' => 300,
                'registered' => 173,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Comment transformer ses objectifs en résultats',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Kénitra Leadership Center',
                'country' => 'Maroc',
                'date' => '2026-12-17',
                'capacity' => 200,
                'registered' => 119,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Préparer sa stratégie personnelle pour 2027',
                'speaker' => 'Grace Mwangi',
                'location' => 'En ligne',
                'country' => 'Kenya',
                'date' => '2026-12-29',
                'capacity' => 400,
                'registered' => 221,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | JANVIER 2027
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Masterclass : Définir sa vision et ses priorités',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Rabat Leadership Hub',
                'country' => 'Maroc',
                'date' => '2027-01-07',
                'capacity' => 220,
                'registered' => 75,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Devenir un leader influent',
                'speaker' => 'Kwame Asante',
                'location' => 'Accra Leadership Center',
                'country' => 'Ghana',
                'date' => '2027-01-14',
                'capacity' => 180,
                'registered' => 64,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Lancer son premier business',
                'speaker' => 'Fatou Ndiaye',
                'location' => 'En ligne',
                'country' => 'Sénégal',
                'date' => '2027-01-21',
                'capacity' => 350,
                'registered' => 129,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Communication digitale et image de marque',
                'speaker' => 'Sarah Kouamé',
                'location' => 'Abidjan Digital Hub',
                'country' => 'Côte d’Ivoire',
                'date' => '2027-01-28',
                'capacity' => 200,
                'registered' => 71,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | FÉVRIER 2027
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Masterclass : Intelligence émotionnelle et leadership',
                'speaker' => 'Grace Mwangi',
                'location' => 'En ligne',
                'country' => 'Kenya',
                'date' => '2027-02-04',
                'capacity' => 300,
                'registered' => 84,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Trouver des partenaires pour son projet',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Douala Tech Park',
                'country' => 'Cameroun',
                'date' => '2027-02-11',
                'capacity' => 160,
                'registered' => 53,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Gérer son argent quand on est jeune',
                'speaker' => 'David Mensah',
                'location' => 'En ligne',
                'country' => 'Ghana',
                'date' => '2027-02-18',
                'capacity' => 400,
                'registered' => 127,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : De l’inspiration au passage à l’action',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Casablanca Business Center',
                'country' => 'Maroc',
                'date' => '2027-02-25',
                'capacity' => 250,
                'registered' => 91,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | MARS 2027
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Masterclass : Développer son mindset entrepreneurial',
                'speaker' => 'Kwame Asante',
                'location' => 'En ligne',
                'country' => 'Ghana',
                'date' => '2027-03-04',
                'capacity' => 350,
                'registered' => 103,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Construire une communauté autour de sa vision',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'Kénitra Innovation Hub',
                'country' => 'Maroc',
                'date' => '2027-03-11',
                'capacity' => 200,
                'registered' => 68,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : Marketing digital pour entrepreneurs africains',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Yaoundé Business Hub',
                'country' => 'Cameroun',
                'date' => '2027-03-18',
                'capacity' => 300,
                'registered' => 87,
                'status' => 'upcoming',
            ],

            [
                'title' => 'Masterclass : L’art de convaincre et d’influencer',
                'speaker' => 'Aisha Koné',
                'location' => 'Dakar Convention Center',
                'country' => 'Sénégal',
                'date' => '2027-03-25',
                'capacity' => 250,
                'registered' => 73,
                'status' => 'upcoming',
            ],


            /*
            |--------------------------------------------------------------------------
            | MASTERCLASSES TERMINÉES
            |--------------------------------------------------------------------------
            */

            [
                'title' => 'Masterclass : Storytelling de marque',
                'speaker' => 'Aisha Koné',
                'location' => 'En ligne',
                'country' => 'Mali',
                'date' => '2026-06-05',
                'capacity' => 200,
                'registered' => 175,
                'status' => 'completed',
            ],

            [
                'title' => 'Masterclass : Pitch investisseur',
                'speaker' => 'Jean Claude Mvondo',
                'location' => 'Douala Tech Park',
                'country' => 'Cameroun',
                'date' => '2026-07-18',
                'capacity' => 100,
                'registered' => 100,
                'status' => 'completed',
            ],

            [
                'title' => 'Masterclass : Construire son personal branding',
                'speaker' => 'Sarah Kouamé',
                'location' => 'Abidjan',
                'country' => 'Côte d’Ivoire',
                'date' => '2026-08-22',
                'capacity' => 180,
                'registered' => 180,
                'status' => 'completed',
            ],

            [
                'title' => 'Masterclass : Leadership et confiance en soi',
                'speaker' => 'Débora Hermine N. Tapsoba',
                'location' => 'En ligne',
                'country' => 'Burkina Faso',
                'date' => '2026-09-19',
                'capacity' => 250,
                'registered' => 250,
                'status' => 'completed',
            ],
        ];


        foreach ($demo as $row) {

            Masterclass::updateOrCreate(
                [
                    'title' => $row['title'],
                ],
                $row
            );

        }
    }
}
