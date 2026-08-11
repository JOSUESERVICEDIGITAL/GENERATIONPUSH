<?php

namespace Database\Seeders;

use App\Models\FounderProfile;
use Illuminate\Database\Seeder;

class FounderSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'name' => 'Débora Tapsoba',
            'role_title' => 'Fondatrice & Directrice générale',

            'bio' => "Débora Tapsoba est entrepreneure sociale et formatrice en leadership depuis plus de dix ans. "
                . "Diplômée en sciences politiques et passionnée par le développement du continent africain, elle a "
                . "travaillé pendant plusieurs années auprès d'organisations panafricaines avant de se consacrer "
                . "pleinement à l'accompagnement des jeunes leaders.\n\n"
                . "Convaincue que le changement en Afrique passera par une nouvelle génération mieux formée, mieux "
                . "connectée et mieux accompagnée, elle a consacré sa carrière à créer des espaces où cette "
                . "génération peut apprendre, se rencontrer et grandir ensemble.",

            'why_founded' => "Tout est parti d'un constat simple : à travers les formations, les conférences et le "
                . "mentorat qu'elle animait bénévolement, Débora voyait un potentiel immense chez les jeunes "
                . "qu'elle rencontrait, mais aussi un manque criant de structures pour les accompagner sur la "
                . "durée. Trop de talents restaient isolés, sans réseau, sans repères, sans accès aux bonnes "
                . "ressources.\n\n"
                . "Generation PUSH est né de cette frustration transformée en projet concret : offrir enfin un "
                . "cadre structuré, une vraie communauté et des outils accessibles à tous les jeunes leaders "
                . "africains, où qu'ils se trouvent sur le continent.",

            'mission' => "Faire émerger, former et connecter la prochaine génération de leaders africains, en "
                . "leur donnant accès à des formations de qualité, à un réseau solide et à des opportunités "
                . "concrètes de croissance — pour qu'ils puissent, à leur tour, avoir un impact durable sur "
                . "leurs communautés et sur le continent.",

            'facebook_url' => 'https://facebook.com/generationpush',
            'instagram_url' => 'https://instagram.com/generationpush',
            'twitter_url' => 'https://twitter.com/generationpush',
            'linkedin_url' => 'https://linkedin.com/company/generationpush',
            'youtube_url' => 'https://youtube.com/@generationpush',

            'show_bio' => true,
            'show_why_founded' => true,
            'show_mission' => true,
            'show_social' => true,
            'show_gallery' => true,
            'is_page_enabled' => true,
        ];

        // On met à jour la ligne existante si elle existe déjà (peu importe son contenu actuel),
        // sinon on la crée. Contrairement à firstOrCreate(), ceci écrase bien les valeurs.
        $founder = FounderProfile::first();

        if ($founder) {
            $founder->update($data);
        } else {
            FounderProfile::create($data);
        }
    }
}
