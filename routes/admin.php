<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Users\MemberController;
use App\Http\Controllers\Admin\Users\LeaderController;
use App\Http\Controllers\Admin\Programs\FormationController;
use App\Http\Controllers\Admin\Programs\CourseController;
use App\Http\Controllers\Admin\Programs\LibraryController;
use App\Http\Controllers\Admin\Events\ConferenceController;
use App\Http\Controllers\Admin\Events\MasterclassController;
use App\Http\Controllers\Admin\Events\CoachingController;
use App\Http\Controllers\Admin\Events\ReservationController;
use App\Http\Controllers\Admin\Events\TicketController;
use App\Http\Controllers\Admin\Payments\TransactionController;
use App\Http\Controllers\Admin\Payments\SubscriptionController;
use App\Http\Controllers\Admin\Payments\InvoiceController;
use App\Http\Controllers\Admin\Content\BlogController;
use App\Http\Controllers\Admin\Content\CategoryController;
use App\Http\Controllers\Admin\Media\GalleryController;
use App\Http\Controllers\Admin\Media\VideoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\Pages\HomePageController;
use App\Http\Controllers\Admin\Pages\MenuItemController;
use App\Http\Controllers\Admin\Partners\SponsorController;
use App\Http\Controllers\Admin\Partners\TestimonialController;
use App\Http\Controllers\Admin\Partners\TeamMemberController;
use App\Http\Controllers\Admin\Communications\NewsletterController;
use App\Http\Controllers\Admin\Communications\SmsController;
use App\Http\Controllers\Admin\Communications\NotificationController;
use App\Http\Controllers\Admin\Communications\ContactMessageController;
use App\Http\Controllers\Admin\Communications\NewsletterSubscriberController;
use App\Http\Controllers\Admin\Communications\ChatController as AdminChatController;
use App\Http\Controllers\Admin\Pages\CustomPageController;
use App\Http\Controllers\Admin\Shop\ProductController;
use App\Http\Controllers\Admin\Shop\OrderController;
use App\Http\Controllers\Admin\Pages\FounderController;
use App\Http\Controllers\Admin\Pages\EngagementPageController;
use App\Http\Controllers\Admin\Communications\ApplicationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes Admin — Generation PUSH
|--------------------------------------------------------------------------
| Toutes préfixées /admin, nommées admin.*, protégées par auth+verified.
| Regroupées par domaine métier. Les routes marquées TODO sont des
| placeholders ("en construction") en attendant leur développement complet.
|
| Convention "SaaS" (Membres, Leaders, ...) : pas de create/edit/show
| dédiés — tout se fait via modals sur la page index. On garde donc
| uniquement index/store/update/destroy + une route bulk-destroy.
*/

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        // --- Utilisateurs (réel) ---------------------------------------
        Route::resource('users', UserController::class);

        Route::name('members.')->prefix('members')->controller(MemberController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{member}', 'update')->name('update');
            Route::delete('/{member}', 'destroy')->name('destroy');
            Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
        });

        Route::name('leaders.')->prefix('leaders')->controller(LeaderController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::put('/{leader}', 'update')->name('update');
            Route::delete('/{leader}', 'destroy')->name('destroy');
            Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
        });

        // --- Programmes ---------------------------------------------------
        Route::name('programs.')->prefix('programs')->group(function () {
            Route::name('formations.')->prefix('formations')->controller(FormationController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{formation}', 'update')->name('update');
                Route::delete('/{formation}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Cours
            Route::name('courses.')->prefix('courses')->controller(CourseController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{course}', 'update')->name('update');
                Route::delete('/{course}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Bibliothèque
            Route::name('library.')->prefix('library')->controller(LibraryController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{resource}', 'update')->name('update');
                Route::delete('/{resource}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Événements -----------------------------------------------
        Route::name('events.')->prefix('events')->group(function () {
            Route::name('conferences.')->prefix('conferences')->controller(ConferenceController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{conference}', 'update')->name('update');
                Route::delete('/{conference}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Masterclass
            Route::name('masterclass.')->prefix('masterclass')->controller(MasterclassController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{masterclass}', 'update')->name('update');
                Route::delete('/{masterclass}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Coaching
            Route::name('coaching.')->prefix('coaching')->controller(CoachingController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{coaching}', 'update')->name('update');
                Route::delete('/{coaching}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Réservations
            Route::name('reservations.')->prefix('reservations')->controller(ReservationController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{reservation}', 'update')->name('update');
                Route::delete('/{reservation}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Billetterie
            Route::name('tickets.')->prefix('tickets')->controller(TicketController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{ticket}', 'update')->name('update');
                Route::delete('/{ticket}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Paiements --------------------------------------------------
        Route::name('payments.')->prefix('payments')->group(function () {
            Route::resource('transactions', TransactionController::class)->only(['index', 'show']); // réel

            // Abonnements
            Route::name('subscriptions.')->prefix('subscriptions')->controller(SubscriptionController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{subscription}', 'update')->name('update');
                Route::delete('/{subscription}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // Factures
            Route::name('invoices.')->prefix('invoices')->controller(InvoiceController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{invoice}', 'update')->name('update');
                Route::delete('/{invoice}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Boutique -------------------------------------
        Route::name('shop.')->prefix('shop')->group(function () {
            Route::name('products.')->prefix('products')->controller(ProductController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{product}', 'update')->name('update');
                Route::delete('/{product}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            // TODO: Commandes
            Route::name('orders.')->prefix('orders')->controller(OrderController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{order}', 'update')->name('update');
                Route::delete('/{order}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Contenu ------------------------------------------------------
        Route::name('content.')->prefix('content')->group(function () {
            Route::resource('blog', BlogController::class); // réel
            Route::delete('blog', [BlogController::class, 'bulkDestroy'])->name('blog.bulk-destroy');

            // "Articles" pointe vers la même liste que "Blog" (même concept, voir note)
            Route::redirect('articles', '/admin/content/blog')->name('articles.index');

            Route::name('categories.')->prefix('categories')->controller(CategoryController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{category}', 'update')->name('update');
                Route::delete('/{category}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Communications ------------------------------------
        Route::name('communications.')->prefix('communications')->group(function () {
            Route::name('newsletter.')->prefix('newsletter')->controller(NewsletterController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{newsletter}', 'update')->name('update');
                Route::delete('/{newsletter}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
                Route::post('/{newsletter}/send', 'send')->name('send');
            });

            Route::name('sms.')->prefix('sms')->controller(SmsController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{sm}', 'update')->name('update');
                Route::delete('/{sm}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
                Route::post('/{sm}/send', 'send')->name('send');
            });

            Route::name('notifications.')->prefix('notifications')->controller(NotificationController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{notification}', 'update')->name('update');
                Route::delete('/{notification}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
                Route::post('/{notification}/send', 'send')->name('send');
            });

            Route::name('messages.')->prefix('messages')->controller(ContactMessageController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/{message}/read', 'markRead')->name('read');
                Route::delete('/{message}', 'destroy')->name('destroy');
            });

            Route::name('subscribers.')->prefix('subscribers')->controller(NewsletterSubscriberController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::delete('/{subscriber}', 'destroy')->name('destroy');
            });

            Route::name('chat.')->prefix('chat')->controller(AdminChatController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{user}', 'show')->name('show');
                Route::post('/{user}/reply', 'reply')->name('reply');
                Route::post('/{user}/toggle', 'toggleAccess')->name('toggle');
                Route::put('/messages/{message}', 'updateMessage')->name('messages.update');
                Route::delete('/messages/{message}', 'destroyMessage')->name('messages.destroy');
            });
        });

        // --- Médias -----------------------------------------
        Route::name('media.')->prefix('media')->group(function () {
            Route::name('gallery.')->prefix('gallery')->controller(GalleryController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{image}', 'update')->name('update');
                Route::delete('/{image}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            Route::name('videos.')->prefix('videos')->controller(VideoController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{video}', 'update')->name('update');
                Route::delete('/{video}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Partenaires ------------------------------------
        Route::name('partners.')->prefix('partners')->group(function () {
            Route::name('sponsors.')->prefix('sponsors')->controller(SponsorController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{sponsor}', 'update')->name('update');
                Route::delete('/{sponsor}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            Route::name('testimonials.')->prefix('testimonials')->controller(TestimonialController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{testimonial}', 'update')->name('update');
                Route::delete('/{testimonial}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });

            Route::name('team.')->prefix('team')->controller(TeamMemberController::class)->group(function () {
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');
                Route::put('/{member}', 'update')->name('update');
                Route::delete('/{member}', 'destroy')->name('destroy');
                Route::delete('/', 'bulkDestroy')->name('bulk-destroy');
            });
        });

        // --- Pages (édition du frontoffice) ------------------------------------
        Route::name('pages.')->prefix('pages')->group(function () {
            Route::get('home', [HomePageController::class, 'edit'])->name('home.edit');
            Route::put('home', [HomePageController::class, 'update'])->name('home.update');

            Route::get('navigation', [MenuItemController::class, 'navigation'])->name('navigation.index');
            Route::get('footer', [MenuItemController::class, 'footer'])->name('footer.index');
            Route::post('navigation', [MenuItemController::class, 'store'])->name('navigation.store');
            Route::post('footer', [MenuItemController::class, 'store'])->name('footer.store');
            Route::put('navigation/{menuItem}', [MenuItemController::class, 'update'])->name('navigation.update');
            Route::put('footer/{menuItem}', [MenuItemController::class, 'update'])->name('footer.update');
            Route::delete('navigation/{menuItem}', [MenuItemController::class, 'destroy'])->name('navigation.destroy');
            Route::delete('footer/{menuItem}', [MenuItemController::class, 'destroy'])->name('footer.destroy');

            Route::get('engagement/{type}', [EngagementPageController::class, 'edit'])->name('engagement.edit');
            Route::put('engagement/{type}', [EngagementPageController::class, 'update'])->name('engagement.update');
            Route::resource('custom', CustomPageController::class);
            Route::get('founder', [FounderController::class, 'edit'])->name('founder.edit');
            Route::put('founder', [FounderController::class, 'update'])->name('founder.update');
            Route::post('founder/photos', [FounderController::class, 'storePhoto'])->name('founder.photos.store');
            Route::delete('founder/photos/{photo}', [FounderController::class, 'destroyPhoto'])->name('founder.photos.destroy');
        });

       Route::get('settings', SettingController::class)->name('settings.index');
    });
