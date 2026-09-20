<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\MemberPageController;

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
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/notifications', [MemberPageController::class, 'notifications'])
            ->name('notifications');


        /*
        |--------------------------------------------------------------------------
        | FORMATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/formations', [MemberPageController::class, 'formations'])
            ->name('formations');


        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENTS
        |--------------------------------------------------------------------------
        */

        Route::get('/evenements', [MemberPageController::class, 'events'])
            ->name('events');


        /*
        |--------------------------------------------------------------------------
        | RÉSERVATIONS
        |--------------------------------------------------------------------------
        */

        Route::get('/reservations', [MemberPageController::class, 'reservations'])
            ->name('reservations');


        /*
        |--------------------------------------------------------------------------
        | COMMANDES
        |--------------------------------------------------------------------------
        */

        Route::get('/commandes', [MemberPageController::class, 'orders'])
            ->name('orders');


        /*
        |--------------------------------------------------------------------------
        | PAIEMENTS
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

    });