<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\ProgramController;
use App\Http\Controllers\Front\EventController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\ShopController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\NewsletterSubscriptionController;
use App\Http\Controllers\Front\CustomPageController;
    use App\Http\Controllers\Front\FounderController as FrontFounderController;
    use App\Http\Controllers\Front\EngagementController;


use App\Http\Controllers\Front\ChatController;



Route::get('/', \App\Http\Controllers\Front\HomeController::class)->name('front.home');

Route::get('/a-propos', AboutController::class)->name('front.about');
Route::get('/programmes', [ProgramController::class, 'index'])->name('front.programs');
Route::get('/evenements', [EventController::class, 'index'])->name('front.events');


Route::get('/blog', [BlogController::class, 'index'])->name('front.blog.index');
Route::get('/mes-articles-sauvegardes', [BlogController::class, 'bookmarked'])->name('front.blog.bookmarked')->middleware('auth');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('front.blog.show');
Route::post('/blog/{post:slug}/like', [BlogController::class, 'toggleLike'])->name('front.blog.like');
Route::post('/blog/{post:slug}/bookmark', [BlogController::class, 'toggleBookmark'])->name('front.blog.bookmark');
Route::post('/blog/{post:slug}/rate', [BlogController::class, 'rate'])->name('front.blog.rate');
Route::post('/blog/{post:slug}/read-time', [BlogController::class, 'trackReadTime'])->name('front.blog.read-time');

Route::get('/boutique', [ShopController::class, 'index'])->name('front.shop.index');
Route::get('/boutique/{product:slug}', [ShopController::class, 'show'])->name('front.shop.show');

Route::get('/contact', [ContactController::class, 'index'])->name('front.contact');
Route::post('/contact', [ContactController::class, 'store'])->name('front.contact.store');



Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])->name('front.newsletter.store');

Route::get('/fondatrice', [FrontFounderController::class, 'show'])->name('front.founder');

Route::middleware(['auth'])->group(function () {
    Route::get('/mes-messages', [ChatController::class, 'index'])->name('front.chat.index');
    Route::post('/mes-messages', [ChatController::class, 'store'])->name('front.chat.store');
    Route::put('/mes-messages/{message}', [ChatController::class, 'update'])->name('front.chat.update');
    Route::delete('/mes-messages/{message}', [ChatController::class, 'destroy'])->name('front.chat.destroy');
});


Route::get('/devenir-partenaire', [EngagementController::class, 'partner'])->name('front.partner');
Route::get('/devenir-benevole', [EngagementController::class, 'volunteer'])->name('front.volunteer');
Route::post('/candidature', [EngagementController::class, 'store'])->name('front.engagement.store');

Route::get('/page/{slug}', [CustomPageController::class, 'show'])->name('front.page.show');

Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
