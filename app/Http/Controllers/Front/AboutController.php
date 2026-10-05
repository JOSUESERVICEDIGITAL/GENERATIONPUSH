<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Formation;
use App\Models\Page;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;

class AboutController extends Controller
{
    public function __invoke()
    {
        /*
        |--------------------------------------------------------------------------
        | Contenu administrable de la page À propos
        |--------------------------------------------------------------------------
        */
        $page = Page::query()
            ->where('key', 'about')
            ->where('status', 'active')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | Paramètres généraux du site
        |--------------------------------------------------------------------------
        */
        $settings = SiteSetting::current();


        /*
        |--------------------------------------------------------------------------
        | Équipe
        |--------------------------------------------------------------------------
        */
        $team = TeamMember::query()
            ->where('status', 'active')
            ->orderBy('order')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Statistiques
        |--------------------------------------------------------------------------
        */
        $stats = [
            'members' => User::count(),
            'formations' => Formation::count(),
            'events' => Conference::count(),
        ];


        /*
        |--------------------------------------------------------------------------
        | Vue
        |--------------------------------------------------------------------------
        */
        return view('front.about', compact(
            'page',
            'settings',
            'team',
            'stats'
        ));
    }
}