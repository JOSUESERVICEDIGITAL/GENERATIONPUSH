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
use App\Http\Controllers\Front\MySpaceController;



/*
|--------------------------------------------------------------------------
| SITE PUBLIC
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    \App\Http\Controllers\Front\HomeController::class
)->name('front.home');

Route::get(
    '/a-propos',
    AboutController::class
)->name('front.about');

Route::get(
    '/programmes',
    [ProgramController::class, 'index']
)->name('front.programs');

Route::get(
    '/evenements',
    [EventController::class, 'index']
)->name('front.events');


/*
|--------------------------------------------------------------------------
| BLOG
|--------------------------------------------------------------------------
*/

Route::get(
    '/blog',
    [BlogController::class, 'index']
)->name('front.blog.index');

Route::get(
    '/mes-articles-sauvegardes',
    [BlogController::class, 'bookmarked']
)->name('front.blog.bookmarked')
  ->middleware('auth');

Route::get(
    '/blog/{post:slug}',
    [BlogController::class, 'show']
)->name('front.blog.show');

Route::post(
    '/blog/{post:slug}/like',
    [BlogController::class, 'toggleLike']
)->name('front.blog.like');

Route::post(
    '/blog/{post:slug}/bookmark',
    [BlogController::class, 'toggleBookmark']
)->name('front.blog.bookmark');

Route::post(
    '/blog/{post:slug}/rate',
    [BlogController::class, 'rate']
)->name('front.blog.rate');

Route::post(
    '/blog/{post:slug}/read-time',
    [BlogController::class, 'trackReadTime']
)->name('front.blog.read-time');


/*
|--------------------------------------------------------------------------
| BOUTIQUE
|--------------------------------------------------------------------------
*/

Route::get(
    '/boutique',
    [ShopController::class, 'index']
)->name('front.shop.index');

Route::get(
    '/boutique/{product:slug}',
    [ShopController::class, 'show']
)->name('front.shop.show');


/*
|--------------------------------------------------------------------------
| CONTACT
|--------------------------------------------------------------------------
*/

Route::get(
    '/contact',
    [ContactController::class, 'index']
)->name('front.contact');

Route::post(
    '/contact',
    [ContactController::class, 'store']
)->name('front.contact.store');


/*
|--------------------------------------------------------------------------
| NEWSLETTER
|--------------------------------------------------------------------------
*/

Route::post(
    '/newsletter',
    [NewsletterSubscriptionController::class, 'store']
)->name('front.newsletter.store');


/*
|--------------------------------------------------------------------------
| FONDATRICE
|--------------------------------------------------------------------------
*/

Route::get(
    '/fondatrice',
    [FrontFounderController::class, 'show']
)->name('front.founder');


/*
|--------------------------------------------------------------------------
| CHAT — UTILISATEURS CONNECTÉS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/mes-messages',
        [ChatController::class, 'index']
    )->name('front.chat.index');

    Route::post(
        '/mes-messages',
        [ChatController::class, 'store']
    )->name('front.chat.store');

    Route::put(
        '/mes-messages/{message}',
        [ChatController::class, 'update']
    )->name('front.chat.update');

    Route::delete(
        '/mes-messages/{message}',
        [ChatController::class, 'destroy']
    )->name('front.chat.destroy');

});


/*
|--------------------------------------------------------------------------
| DEVENIR PARTENAIRE
|--------------------------------------------------------------------------
*/

Route::get(
    '/devenir-partenaire',
    [EngagementController::class, 'partner']
)->name('front.partner');


/*
|--------------------------------------------------------------------------
| DEVENIR BÉNÉVOLE
|--------------------------------------------------------------------------
*/

Route::get(
    '/devenir-benevole',
    [EngagementController::class, 'volunteer']
)->name('front.volunteer');



/*
|--------------------------------------------------------------------------
| ENVOI CANDIDATURE
|--------------------------------------------------------------------------
*/

Route::post(
    '/nous-rejoindre',
    [EngagementController::class, 'store']
)->name('front.engagement.store');



Route::get(
    '/nous-rejoindre/confirmation/{type}',
    [EngagementController::class, 'success']
)->name('front.engagement.success');


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
| PAGES PERSONNALISÉES
|--------------------------------------------------------------------------
*/

Route::get(
    '/page/{slug}',
    [CustomPageController::class, 'show']
)->name('front.page.show');


/*
|--------------------------------------------------------------------------
| DASHBOARD UTILISATEUR
|--------------------------------------------------------------------------
|
| IMPORTANT :
| Cette route ne doit PLUS rediriger tous les utilisateurs vers
| admin.dashboard.
|
| Le back-office est totalement séparé et protégé par admin.only.
|
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {

        $user = request()->user();

        /*
        |--------------------------------------------------------------------------
        | Administrateur
        |--------------------------------------------------------------------------
        |
        | Un administrateur peut aller dans son back-office.
        |
        */

        if (
            $user->is_admin
            && $user->is_active
        ) {
            return redirect()->route('admin.dashboard');
        }

        /*
        |--------------------------------------------------------------------------
        | Utilisateur simple
        |--------------------------------------------------------------------------
        |
        | Il ne connaît pas et ne doit pas connaître l'existence
        | du back-office.
        |
        | On le renvoie simplement vers le site public.
        |
        */

        return redirect()->route('front.home');

    })->name('dashboard');

});


/*
|--------------------------------------------------------------------------
| PROFIL UTILISATEUR
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| BACK-OFFICE ADMINISTRATEUR
|--------------------------------------------------------------------------
|
| TOUT ce qui se trouve dans admin.php est maintenant protégé par :
|
| - auth
| - verified
| - admin.only
|
| Un utilisateur simple qui tape manuellement /admin/... reçoit une 404.
|
*/

Route::middleware([
    'auth',
    'verified',
    'admin.only',
])->group(function () {

    require __DIR__ . '/admin.php';

});








/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION
|--------------------------------------------------------------------------
*/



Route::middleware('auth')->group(function () {

    Route::get('/mon-espace', [MySpaceController::class, 'index'])
        ->name('front.my-space');

});






require __DIR__ . '/front/shop.blade.php';
require __DIR__ . '/member.php';

require __DIR__ . '/auth.php';
