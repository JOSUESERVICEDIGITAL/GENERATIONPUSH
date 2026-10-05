<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Front\EventController as FrontEventController;
use App\Http\Controllers\Front\EventReservationController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| FRONT OFFICE
|--------------------------------------------------------------------------
*/

Route::get(
    '/evenements',
    [FrontEventController::class, 'index']
)->name('events.index');


Route::get(
    '/evenements/{event}',
    [FrontEventController::class, 'show']
)->name('events.show');


Route::post(
    '/evenements/{event}/reservation',
    [EventReservationController::class, 'store']
)->name('events.reservations.store');


Route::get(
    '/reservations/{reservation}/confirmation',
    [EventReservationController::class, 'success']
)->name('events.reservations.success');

/*
|--------------------------------------------------------------------------
| BACK OFFICE
|--------------------------------------------------------------------------
*/

Route::prefix('admin/events')
    ->name('admin.events.')
    ->middleware(['auth'])
    ->group(function () {

        Route::get('/', [AdminEventController::class, 'index'])
            ->name('index');

        Route::get('/create', [AdminEventController::class, 'create'])
            ->name('create');

        Route::post('/', [AdminEventController::class, 'store'])
            ->name('store');

        Route::get('/{event}', [AdminEventController::class, 'show'])
            ->name('show');

        Route::get('/{event}/edit', [AdminEventController::class, 'edit'])
            ->name('edit');

        Route::put('/{event}', [AdminEventController::class, 'update'])
            ->name('update');

        Route::delete('/{event}', [AdminEventController::class, 'destroy'])
            ->name('destroy');
    });
