<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use App\Models\Formation;
use App\Models\Masterclass;
use Illuminate\Contracts\View\View;

class MemberPageController extends Controller
{
    /**
     * ==========================================================
     * MON PROFIL
     * ==========================================================
     */
    public function profile(): View
    {
        $user = auth()->user();

        return view('member.pages.index', [
            'title' => 'Mon profil',
            'section' => 'profile',
            'description' => 'Consultez les informations associées à votre compte membre.',
            'icon' => 'user',
            'user' => $user,
        ]);
    }

    /**
     * ==========================================================
     * MES MESSAGES
     * ==========================================================
     */
    public function messages(): View
    {
        $user = auth()->user();

        $messages = $user->chatMessages()
            ->with('author')
            ->latest()
            ->get();

        $unreadMessages = $messages
            ->where('is_from_admin', true)
            ->whereNull('read_by_member_at')
            ->count();

        return view('member.pages.index', [
            'title' => 'Mes messages',
            'section' => 'messages',
            'description' => 'Échangez avec l’équipe de Generation PUSH depuis votre espace membre.',
            'icon' => 'message-circle',
            'messages' => $messages,
            'unreadMessages' => $unreadMessages,
        ]);
    }

    /**
     * ==========================================================
     * MES NOTIFICATIONS
     * ==========================================================
     */
    public function notifications(): View
    {
        /*
        |--------------------------------------------------------------------------
        | AdminNotification
        |--------------------------------------------------------------------------
        |
        | Le modèle actuel ne contient pas de user_id.
        | Les notifications sont donc diffusées par audience.
        |
        | On récupère uniquement :
        | - les notifications envoyées
        | - destinées aux membres
        | - ou destinées à tout le monde
        |
        */

        $notifications = AdminNotification::query()
            ->where('status', 'sent')
            ->whereIn('audience', ['members', 'all'])
            ->latest('sent_at')
            ->latest('id')
            ->get();

        return view('member.pages.index', [
            'title' => 'Mes notifications',
            'section' => 'notifications',
            'description' => 'Retrouvez les informations importantes communiquées par Generation PUSH.',
            'icon' => 'bell',
            'notifications' => $notifications,
        ]);
    }

    /**
     * ==========================================================
     * MES FAVORIS
     * ==========================================================
     */
    public function bookmarks(): View
    {
        $user = auth()->user();

        $bookmarks = $user->bookmarkedPosts()
            ->latest('post_bookmarks.created_at')
            ->get();

        return view('member.pages.index', [
            'title' => 'Mes favoris',
            'section' => 'bookmarks',
            'description' => 'Retrouvez les articles que vous avez enregistrés dans vos favoris.',
            'icon' => 'heart',
            'bookmarks' => $bookmarks,
        ]);
    }

    /**
     * ==========================================================
     * FORMATIONS
     * ==========================================================
     */
    public function formations(): View
    {
        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Formation n'est actuellement pas reliée à User.
        |
        | On affiche donc les formations réellement présentes
        | dans la base et actives.
        |
        */

        $formations = Formation::query()
            ->where('status', 'active')
            ->with([
                'courses' => function ($query) {
                    $query
                        ->where('status', 'published')
                        ->orderBy('order');
                },
            ])
            ->latest()
            ->get();

        return view('member.pages.index', [
            'title' => 'Mes formations',
            'section' => 'formations',
            'description' => 'Découvrez les formations actuellement disponibles sur Generation PUSH.',
            'icon' => 'graduation-cap',
            'formations' => $formations,
        ]);
    }

    /**
     * ==========================================================
     * MES ÉVÉNEMENTS
     * ==========================================================
     */
    public function events(): View
    {
        $user = auth()->user();

        $events = $user->reservations()
            ->where('reservable_type', Masterclass::class)
            ->with('reservable')
            ->latest()
            ->get();

        return view('member.pages.index', [
            'title' => 'Mes événements',
            'section' => 'events',
            'description' => 'Retrouvez les événements auxquels vous participez ou que vous suivez.',
            'icon' => 'calendar-days',
            'events' => $events,
        ]);
    }

    /**
     * ==========================================================
     * MES RÉSERVATIONS
     * ==========================================================
     */
    public function reservations(): View
    {
        $user = auth()->user();

        $reservations = $user->reservations()
            ->with('reservable')
            ->latest()
            ->get();

        return view('member.pages.index', [
            'title' => 'Mes réservations',
            'section' => 'reservations',
            'description' => 'Gérez ici vos réservations et inscriptions.',
            'icon' => 'ticket',
            'reservations' => $reservations,
        ]);
    }

    /**
     * ==========================================================
     * MES COMMANDES
     * ==========================================================
     */
    public function orders(): View
    {
        $user = auth()->user();

        $orders = $user->orders()
            ->with([
                'items.product',
            ])
            ->latest()
            ->get();

        return view('member.pages.index', [
            'title' => 'Mes commandes',
            'section' => 'orders',
            'description' => 'Retrouvez ici l’historique de vos commandes.',
            'icon' => 'shopping-bag',
            'orders' => $orders,
        ]);
    }

    /**
     * ==========================================================
     * MES PAIEMENTS
     * ==========================================================
     */
    public function payments(): View
    {
        $user = auth()->user();

        $transactions = $user->transactions()
            ->latest('date')
            ->get();

        $completedAmount = $user->transactions()
            ->where('status', 'completed')
            ->sum('amount');

        return view('member.pages.index', [
            'title' => 'Mes paiements',
            'section' => 'payments',
            'description' => 'Retrouvez ici vos paiements et transactions.',
            'icon' => 'credit-card',
            'transactions' => $transactions,
            'completedAmount' => $completedAmount,
        ]);
    }

    /**
     * ==========================================================
     * PARAMÈTRES
     * ==========================================================
     */
    public function settings(): View
    {
        $user = auth()->user();

        return view('member.pages.index', [
            'title' => 'Paramètres',
            'section' => 'settings',
            'description' => 'Gérez les paramètres de votre espace membre.',
            'icon' => 'settings',
            'user' => $user,
        ]);
    }
}
