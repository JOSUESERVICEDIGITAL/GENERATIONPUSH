<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Front\AboutController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\ChatController;
use App\Http\Controllers\Front\ContactController;
use App\Http\Controllers\Front\CustomPageController;
use App\Http\Controllers\Front\EngagementController;
use App\Http\Controllers\Front\FounderController as FrontFounderController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\MySpaceController;
use App\Http\Controllers\Front\NewsletterSubscriptionController;
use App\Http\Controllers\Front\ProgramController;
use App\Http\Controllers\Front\ShopController;


/*
|--------------------------------------------------------------------------
| Site public
|--------------------------------------------------------------------------
*/

Route::get('/', HomeController::class)
    ->name('front.home');

Route::get('/a-propos', AboutController::class)
    ->name('front.about');

Route::get('/programmes', [ProgramController::class, 'index'])
    ->name('front.programs');


/*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
*/

Route::get('/blog', [BlogController::class, 'index'])
    ->name('front.blog.index');

Route::get('/mes-articles-sauvegardes', [BlogController::class, 'bookmarked'])
    ->middleware('auth')
    ->name('front.blog.bookmarked');

Route::get('/blog/{post:slug}', [BlogController::class, 'show'])
    ->name('front.blog.show');

Route::post('/blog/{post:slug}/like', [BlogController::class, 'toggleLike'])
    ->name('front.blog.like');

Route::post('/blog/{post:slug}/bookmark', [BlogController::class, 'toggleBookmark'])
    ->name('front.blog.bookmark');

Route::post('/blog/{post:slug}/rate', [BlogController::class, 'rate'])
    ->name('front.blog.rate');

Route::post('/blog/{post:slug}/read-time', [BlogController::class, 'trackReadTime'])
    ->name('front.blog.read-time');


/*
|--------------------------------------------------------------------------
| Boutique
|--------------------------------------------------------------------------
*/

Route::get('/boutique', [ShopController::class, 'index'])
    ->name('front.shop.index');

Route::get('/boutique/{product:slug}', [ShopController::class, 'show'])
    ->name('front.shop.show');


/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/

Route::get('/contact', [ContactController::class, 'index'])
    ->name('front.contact');

Route::post('/contact', [ContactController::class, 'store'])
    ->name('front.contact.store');


/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/

Route::post('/newsletter', [NewsletterSubscriptionController::class, 'store'])
    ->name('front.newsletter.store');


/*
|--------------------------------------------------------------------------
| Fondatrice
|--------------------------------------------------------------------------
*/

Route::get('/fondatrice', [FrontFounderController::class, 'show'])
    ->name('front.founder');


/*
|--------------------------------------------------------------------------
| Engagement
|--------------------------------------------------------------------------
*/

Route::get('/devenir-partenaire', [EngagementController::class, 'partner'])
    ->name('front.partner');

Route::get('/devenir-benevole', [EngagementController::class, 'volunteer'])
    ->name('front.volunteer');

Route::post('/nous-rejoindre', [EngagementController::class, 'store'])
    ->name('front.engagement.store');

Route::get('/nous-rejoindre/confirmation/{type}', [EngagementController::class, 'success'])
    ->name('front.engagement.success');


/*
|--------------------------------------------------------------------------
| Inscription en attente
|--------------------------------------------------------------------------
*/

Route::get('/inscription-en-attente', function () {
    $registration = session('registration_user');

    abort_unless($registration, 404);

    return view('front.registration-pending', [
        'name' => $registration['name'],
        'email' => $registration['email'],
        'type' => $registration['type'],
    ]);
})->name('registration.pending');


/*
|--------------------------------------------------------------------------
| Pages personnalisées
|--------------------------------------------------------------------------
*/

Route::get('/page/{slug}', [CustomPageController::class, 'show'])
    ->name('front.page.show');


/*
|--------------------------------------------------------------------------
| Utilisateurs authentifiés
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/mes-messages', [ChatController::class, 'index'])
        ->name('front.chat.index');

    Route::post('/mes-messages', [ChatController::class, 'store'])
        ->name('front.chat.store');

    Route::put('/mes-messages/{message}', [ChatController::class, 'update'])
        ->name('front.chat.update');

    Route::delete('/mes-messages/{message}', [ChatController::class, 'destroy'])
        ->name('front.chat.destroy');

    Route::get('/mon-espace', [MySpaceController::class, 'index'])
        ->name('front.my-space');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = request()->user();

        if ($user->is_admin && $user->is_active) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('front.home');
    })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Administration
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'verified',
    'admin.only',
])->group(function () {
    require __DIR__.'/admin.php';
});


/*
|--------------------------------------------------------------------------
| Fichiers de routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/page.php';
require __DIR__.'/book.php';
require __DIR__.'/front/shop.php';
require __DIR__.'/member.php';
require __DIR__.'/event.php';
require __DIR__.'/auth.php';
