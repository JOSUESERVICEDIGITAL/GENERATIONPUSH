<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class MemberPageController extends Controller
{
    public function notifications(): View
    {
        return view('member.pages.index', [
            'title' => 'Mes notifications',
            'section' => 'notifications',
            'description' => 'Retrouvez ici toutes les notifications liées à votre compte.',
            'icon' => 'bell',
        ]);
    }


    public function formations(): View
    {
        return view('member.pages.index', [
            'title' => 'Mes formations',
            'section' => 'formations',
            'description' => 'Retrouvez ici vos formations, parcours et apprentissages.',
            'icon' => 'graduation-cap',
        ]);
    }


    public function events(): View
    {
        return view('member.pages.index', [
            'title' => 'Mes événements',
            'section' => 'events',
            'description' => 'Retrouvez les événements auxquels vous participez ou que vous suivez.',
            'icon' => 'calendar-days',
        ]);
    }


    public function reservations(): View
    {
        return view('member.pages.index', [
            'title' => 'Mes réservations',
            'section' => 'reservations',
            'description' => 'Gérez ici vos réservations et inscriptions.',
            'icon' => 'ticket',
        ]);
    }


    public function orders(): View
    {
        return view('member.pages.index', [
            'title' => 'Mes commandes',
            'section' => 'orders',
            'description' => 'Retrouvez ici l’historique de vos commandes.',
            'icon' => 'shopping-bag',
        ]);
    }


    public function payments(): View
    {
        return view('member.pages.index', [
            'title' => 'Mes paiements',
            'section' => 'payments',
            'description' => 'Retrouvez ici vos paiements et transactions.',
            'icon' => 'credit-card',
        ]);
    }


    public function settings(): View
    {
        return view('member.pages.index', [
            'title' => 'Paramètres',
            'section' => 'settings',
            'description' => 'Gérez les paramètres de votre espace membre.',
            'icon' => 'settings',
        ]);
    }
}