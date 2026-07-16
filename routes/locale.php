<?php

use Illuminate\Support\Facades\Route;

Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['fr', 'en', 'ar'], true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('locale.switch');
