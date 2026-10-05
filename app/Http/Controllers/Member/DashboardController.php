<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Masterclass;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    /**
     * Tableau de bord membre.
     */
    public function index(): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | MESSAGES
        |--------------------------------------------------------------------------
        */

        $unreadMessages = $user->chatMessages()
            ->where('is_from_admin', true)
            ->whereNull('read_by_member_at')
            ->count();

        $totalMessages = $user->chatMessages()->count();

        /*
        |--------------------------------------------------------------------------
        | FAVORIS
        |--------------------------------------------------------------------------
        */

        $favoritesCount = $user->bookmarkedPosts()->count();

        /*
        |--------------------------------------------------------------------------
        | RÉSERVATIONS
        |--------------------------------------------------------------------------
        */

        $reservationsCount = $user->reservations()->count();

        $confirmedReservationsCount = $user->reservations()
            ->where('status', 'confirmed')
            ->count();

        $pendingReservationsCount = $user->reservations()
            ->where('status', 'pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENTS / MASTERCLASS
        |--------------------------------------------------------------------------
        |
        | Les Masterclass utilisent la relation polymorphique "reservable".
        |
        */

        $eventsCount = $user->reservations()
            ->where('reservable_type', Masterclass::class)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | COMMANDES
        |--------------------------------------------------------------------------
        */

        $ordersCount = $user->orders()->count();

        $paidOrdersCount = $user->orders()
            ->where('status', 'paid')
            ->count();

        $pendingOrdersCount = $user->orders()
            ->where('status', 'pending')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | PAIEMENTS / TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $paymentsCount = $user->transactions()->count();

        $completedPaymentsCount = $user->transactions()
            ->where('status', 'completed')
            ->count();

        $pendingPaymentsCount = $user->transactions()
            ->where('status', 'pending')
            ->count();

        $totalPaid = $user->transactions()
            ->where('status', 'completed')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        $profileFields = [
            $user->name,
            $user->email,
            $user->phone,
            $user->country,
            $user->city,
            $user->address,
            $user->profile_photo,
            $user->profile_completed_at,
        ];

        $completedProfileFields = collect($profileFields)
            ->filter(fn ($value) => filled($value))
            ->count();

        $profileCompletion = (int) round(
            ($completedProfileFields / count($profileFields)) * 100
        );

        /*
        |--------------------------------------------------------------------------
        | DERNIÈRES DONNÉES
        |--------------------------------------------------------------------------
        */

        $recentOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();

        $recentReservations = $user->reservations()
            ->with('reservable')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = $user->transactions()
            ->latest('date')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view('member.dashboard', [
            'user' => $user,

            // Messages
            'unreadMessages' => $unreadMessages,
            'totalMessages' => $totalMessages,

            // Favoris
            'favoritesCount' => $favoritesCount,

            // Réservations
            'reservationsCount' => $reservationsCount,
            'confirmedReservationsCount' => $confirmedReservationsCount,
            'pendingReservationsCount' => $pendingReservationsCount,

            // Événements
            'eventsCount' => $eventsCount,

            // Commandes
            'ordersCount' => $ordersCount,
            'paidOrdersCount' => $paidOrdersCount,
            'pendingOrdersCount' => $pendingOrdersCount,

            // Paiements
            'paymentsCount' => $paymentsCount,
            'completedPaymentsCount' => $completedPaymentsCount,
            'pendingPaymentsCount' => $pendingPaymentsCount,
            'totalPaid' => $totalPaid,

            // Profil
            'profileCompletion' => $profileCompletion,

            // Activité récente
            'recentOrders' => $recentOrders,
            'recentReservations' => $recentReservations,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
