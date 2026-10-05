<?php

use App\Http\Controllers\Front\BookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes publiques - Livres
|--------------------------------------------------------------------------
|
| Routes liées à la présentation publique des livres Generation PUSH.
|
*/

Route::get('/le-livre', [BookController::class, 'index'])
    ->name('front.books.index');