<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        Page::updateOrCreate(
            [
                'key' => 'about',
            ],
            [
                /*
                |--------------------------------------------------------------------------
                | BANNIÈRE
                |--------------------------------------------------------------------------
                */

                'title' => 'À propos de nous',

                'subtitle' => "Découvre la mission, l'histoire et les visages qui donnent vie à Generation PUSH",

                'banner_video_enabled' => false,

                'status' => 'active',


                /*
                |--------------------------------------------------------------------------
                | NOTRE HISTOIRE
                |--------------------------------------------------------------------------
                */

                'story_eyebrow' => 'Notre histoire',

                'story_title' => 'Plus qu’une communauté, un mouvement.',

                'story_text' => <<<'TEXT'
Generation PUSH est un mouvement dédié à une génération qui refuse de rester spectatrice de son avenir.

Nous croyons que chaque jeune possède un potentiel qui ne demande pas simplement à être motivé, mais à être poussé vers l’action, la discipline, le leadership et l’accomplissement de sa destinée.

À travers nos rencontres, conférences, masterclasses, sessions de lecture, accompagnements et différentes initiatives, nous créons des espaces où les jeunes peuvent apprendre, se connecter, grandir et surtout passer à l’action.

Generation PUSH rassemble ainsi une communauté de jeunes déterminés à développer leur potentiel et à produire un impact positif autour d’eux.
TEXT,

                'story_card_title' => 'Passer à l’action',

                'story_card_text' => 'Plus qu’une communauté, un mouvement.',


                /*
                |--------------------------------------------------------------------------
                | FONDATRICE
                |--------------------------------------------------------------------------
                */

                'founder_eyebrow' => 'La vision derrière le mouvement',

                'founder_title' => 'Une vision portée par Débora Hermine N. Tapsoba',

                'founder_text' => <<<'TEXT'
Generation PUSH est né d’une vision : créer un cadre capable de pousser une génération à sortir de la passivité pour devenir actrice de son développement et de son avenir.

Cette vision est portée par Débora Hermine N. Tapsoba, qui œuvre à travers Generation PUSH pour encourager les jeunes à développer leur leadership, renforcer leurs capacités et transformer leurs ambitions en actions concrètes.
TEXT,

                'founder_button_text' => 'Découvrir son histoire',


                /*
                |--------------------------------------------------------------------------
                | VALEURS
                |--------------------------------------------------------------------------
                */

                'values_eyebrow' => 'Nos valeurs',

                'values_title' => 'Ce qui nous fait avancer.',

                'values_intro' => "Generation PUSH s'appuie sur des valeurs fortes qui orientent notre manière d'agir, de grandir et de construire ensemble.",


                /*
                |--------------------------------------------------------------------------
                | VALEUR 01
                |--------------------------------------------------------------------------
                */

                'value_1_title' => 'Excellence',

                'value_1_short' => 'Viser plus haut.',

                'value_1_text' => "Nous encourageons chaque jeune à donner le meilleur de lui-même, à développer ses compétences et à rechercher constamment la progression.",


                /*
                |--------------------------------------------------------------------------
                | VALEUR 02
                |--------------------------------------------------------------------------
                */

                'value_2_title' => 'Communauté',

                'value_2_short' => 'Avancer ensemble.',

                'value_2_text' => "Nous croyons à la force des connexions, du partage d'expériences et d'une communauté dans laquelle chacun peut apprendre, contribuer et grandir.",


                /*
                |--------------------------------------------------------------------------
                | VALEUR 03
                |--------------------------------------------------------------------------
                */

                'value_3_title' => 'Impact',

                'value_3_short' => 'Transformer durablement.',

                'value_3_text' => "Nous voulons transformer les connaissances et les ambitions en actions concrètes capables de produire un impact positif et durable.",


                /*
                |--------------------------------------------------------------------------
                | ÉQUIPE
                |--------------------------------------------------------------------------
                */

                'team_eyebrow' => 'Notre équipe',

                'team_title' => 'Les visages derrière Generation PUSH.',

                'team_intro' => "Une équipe engagée, réunie autour d'une même vision : accompagner une génération à révéler son potentiel et à passer à l'action.",


                /*
                |--------------------------------------------------------------------------
                | CTA FINAL
                |--------------------------------------------------------------------------
                */

                'cta_eyebrow' => 'Generation PUSH',

                'cta_title' => 'Ne regarde pas le changement.',

                'cta_highlight' => 'Deviens-en acteur.',

                'cta_text' => "Rejoins une génération qui choisit d'apprendre, de grandir, de construire et de transformer son environnement.",

                'cta_button_text' => 'Rejoindre la communauté',

                'cta_button_url' => '/register',
            ]
        );
    }
}