<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\MemberPageController;
use App\Http\Controllers\Member\MemberChatController;

Route::middleware(['auth', 'member'])
    ->prefix('espace-membre')
    ->name('member.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | TABLEAU DE BORD
        |--------------------------------------------------------------------------
        */

        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | MON PROFIL
        |--------------------------------------------------------------------------
        */

        Route::get('/profil', [MemberPageController::class, 'profile'])
            ->name('profile');


        /*
        |--------------------------------------------------------------------------
        | MES MESSAGES
        |--------------------------------------------------------------------------
        */

        Route::get('/messages', [MemberPageController::class, 'messages'])
            ->name('messages');


        /*
        |--------------------------------------------------------------------------
        | MES NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/notifications', [MemberPageController::class, 'notifications'])
            ->name('notifications');


        /*
        |--------------------------------------------------------------------------
        | MES FAVORIS
        |--------------------------------------------------------------------------
        */

        Route::get('/favoris', [MemberPageController::class, 'bookmarks'])
            ->name('bookmarks');


        /*
        |--------------------------------------------------------------------------
        | MES FORMATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/formations', [MemberPageController::class, 'formations'])
            ->name('formations');


        /*
        |--------------------------------------------------------------------------
        | MES ÉVÉNEMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/evenements', [MemberPageController::class, 'events'])
            ->name('events');


        /*
        |--------------------------------------------------------------------------
        | MES RÉSERVATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/reservations', [MemberPageController::class, 'reservations'])
            ->name('reservations');


        /*
        |--------------------------------------------------------------------------
        | MES COMMANDES
        |--------------------------------------------------------------------------
        */

        Route::get('/commandes', [MemberPageController::class, 'orders'])
            ->name('orders');


        /*
        |--------------------------------------------------------------------------
        | MES PAIEMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/paiements', [MemberPageController::class, 'payments'])
            ->name('payments');


        /*
        |--------------------------------------------------------------------------
        | PARAMÈTRES
        |--------------------------------------------------------------------------
        */

        Route::get('/parametres', [MemberPageController::class, 'settings'])
            ->name('settings');

        Route::get('/chat', [MemberChatController::class, 'index'])
            ->name('chat');

        Route::post('/chat', [MemberChatController::class, 'store'])
            ->name('chat.store');

        Route::post('/chat/{message}/read', [MemberChatController::class, 'markAsRead'])
            ->name('chat.read');
    });
