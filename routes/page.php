<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\Pages\AboutPageController;
use App\Http\Controllers\Admin\Pages\ShopPageController;

Route::prefix('admin/pages')
    ->name('admin.pages.')
    ->middleware(['auth'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | PAGE À PROPOS
        |--------------------------------------------------------------------------
        */

        Route::get('/about', [AboutPageController::class, 'edit'])
            ->name('about.edit');

        Route::put('/about', [AboutPageController::class, 'update'])
            ->name('about.update');

        Route::delete(
            '/about/banner-video',
            [AboutPageController::class, 'destroyBannerVideo']
        )->name('about.banner-video.destroy');

        Route::delete(
            '/about/banner-poster',
            [AboutPageController::class, 'destroyBannerPoster']
        )->name('about.banner-poster.destroy');

        Route::delete(
            '/about/story-image',
            [AboutPageController::class, 'destroyStoryImage']
        )->name('about.story-image.destroy');


        /*
        |--------------------------------------------------------------------------
        | PAGE BOUTIQUE
        |--------------------------------------------------------------------------
        | Routes spécifiques AVANT /{page}
        */

        Route::get('/shop', [ShopPageController::class, 'edit'])
            ->name('shop.edit');

        Route::put('/shop', [ShopPageController::class, 'update'])
            ->name('shop.update');

        Route::delete(
            '/shop/banner-video',
            [ShopPageController::class, 'destroyBannerVideo']
        )->name('shop.banner-video.destroy');

        Route::delete(
            '/shop/banner-poster',
            [ShopPageController::class, 'destroyBannerPoster']
        )->name('shop.banner-poster.destroy');


        /*
        |--------------------------------------------------------------------------
        | ROUTES GÉNÉRIQUES
        |--------------------------------------------------------------------------
        | Toujours après les pages spécifiques.
        */

        Route::get('/', [PageController::class, 'index'])
            ->name('index');

        Route::get('/{page}/edit', [PageController::class, 'edit'])
            ->name('edit');

        Route::put('/{page}', [PageController::class, 'update'])
            ->name('update');

        Route::delete(
            '/{page}/banner-video',
            [PageController::class, 'destroyBannerVideo']
        )->name('banner-video.destroy');

        Route::delete(
            '/{page}/banner-poster',
            [PageController::class, 'destroyBannerPoster']
        )->name('banner-poster.destroy');

        //optimisation
    });
