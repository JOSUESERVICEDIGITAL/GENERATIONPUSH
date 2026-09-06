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
use App\Http\Controllers\Admin\Pages\CustomPageController;
use App\Http\Controllers\Admin\Pages\FounderController;
use App\Http\Controllers\Admin\Pages\EngagementPageController;

use App\Http\Controllers\Admin\Partners\SponsorController;
use App\Http\Controllers\Admin\Partners\TestimonialController;
use App\Http\Controllers\Admin\Partners\TeamMemberController;

use App\Http\Controllers\Admin\Communications\NewsletterController;
use App\Http\Controllers\Admin\Communications\SmsController;
use App\Http\Controllers\Admin\Communications\NotificationController;
use App\Http\Controllers\Admin\Communications\ContactMessageController;
use App\Http\Controllers\Admin\Communications\NewsletterSubscriberController;
use App\Http\Controllers\Admin\Communications\ChatController as AdminChatController;
use App\Http\Controllers\Admin\Communications\ApplicationController;

use App\Http\Controllers\Admin\Shop\ProductController;
use App\Http\Controllers\Admin\Shop\OrderController;

/*
|--------------------------------------------------------------------------
| CONTRÔLEUR CENTRAL DES CANDIDATURES
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\EngagementController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Routes Admin — Generation PUSH
|--------------------------------------------------------------------------
|
| Toutes les routes sont :
| - préfixées par /admin
| - nommées admin.*
| - protégées par auth + verified
|
*/

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


        // ================================================================
        // DASHBOARD
        // ================================================================

        Route::get('/dashboard', DashboardController::class)
            ->name('dashboard');


        // ================================================================
        // UTILISATEURS
        // ================================================================

        Route::resource('users', UserController::class);


        Route::name('members.')
            ->prefix('members')
            ->controller(MemberController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::put('/{member}', 'update')
                    ->name('update');

                Route::delete('/{member}', 'destroy')
                    ->name('destroy');

                Route::delete('/', 'bulkDestroy')
                    ->name('bulk-destroy');
            });


        Route::name('leaders.')
            ->prefix('leaders')
            ->controller(LeaderController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::put('/{leader}', 'update')
                    ->name('update');

                Route::delete('/{leader}', 'destroy')
                    ->name('destroy');

                Route::delete('/', 'bulkDestroy')
                    ->name('bulk-destroy');
            });


        // ================================================================
        // PROGRAMMES
        // ================================================================

        Route::name('programs.')
            ->prefix('programs')
            ->group(function () {

                // FORMATIONS
                Route::name('formations.')
                    ->prefix('formations')
                    ->controller(FormationController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{formation}', 'update')
                            ->name('update');

                        Route::delete('/{formation}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });


                // COURS
                Route::name('courses.')
                    ->prefix('courses')
                    ->controller(CourseController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{course}', 'update')
                            ->name('update');

                        Route::delete('/{course}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });


                // BIBLIOTHÈQUE
                Route::name('library.')
                    ->prefix('library')
                    ->controller(LibraryController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{library}', 'update')
                            ->name('update');

                        Route::delete('/{library}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });
            });


        // ================================================================
        // ÉVÉNEMENTS
        // ================================================================

        Route::name('events.')
            ->prefix('events')
            ->group(function () {

                // CONFÉRENCES
                Route::name('conferences.')
                    ->prefix('conferences')
                    ->controller(ConferenceController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{conference}', 'update')
                            ->name('update');

                        Route::delete('/{conference}', 'destroy')
                            ->name('destroy');
                    });


                // MASTERCLASSES
                Route::name('masterclasses.')
                    ->prefix('masterclasses')
                    ->controller(MasterclassController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{masterclass}', 'update')
                            ->name('update');

                        Route::delete('/{masterclass}', 'destroy')
                            ->name('destroy');
                    });


                // COACHING
                Route::name('coaching.')
                    ->prefix('coaching')
                    ->controller(CoachingController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{coaching}', 'update')
                            ->name('update');

                        Route::delete('/{coaching}', 'destroy')
                            ->name('destroy');
                    });


                // RÉSERVATIONS
                Route::name('reservations.')
                    ->prefix('reservations')
                    ->controller(ReservationController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{reservation}', 'update')
                            ->name('update');

                        Route::delete('/{reservation}', 'destroy')
                            ->name('destroy');
                    });


                // BILLETS
                Route::name('tickets.')
                    ->prefix('tickets')
                    ->controller(TicketController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{ticket}', 'update')
                            ->name('update');

                        Route::delete('/{ticket}', 'destroy')
                            ->name('destroy');
                    });
            });


        // ================================================================
        // PAIEMENTS
        // ================================================================

        Route::name('payments.')
            ->prefix('payments')
            ->group(function () {

                // TRANSACTIONS
                Route::name('transactions.')
                    ->prefix('transactions')
                    ->controller(TransactionController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // ABONNEMENTS
                Route::name('subscriptions.')
                    ->prefix('subscriptions')
                    ->controller(SubscriptionController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // FACTURES
                Route::name('invoices.')
                    ->prefix('invoices')
                    ->controller(InvoiceController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });
            });


        // ================================================================
        // CONTENU
        // ================================================================

        Route::name('content.')
            ->prefix('content')
            ->group(function () {

                Route::resource('blog', BlogController::class);

                Route::resource('categories', CategoryController::class);
            });


        // ================================================================
        // COMMUNICATIONS
        // ================================================================

        Route::name('communications.')
            ->prefix('communications')
            ->group(function () {

                // NEWSLETTER
                Route::name('newsletter.')
                    ->prefix('newsletter')
                    ->controller(NewsletterController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // SMS
                Route::name('sms.')
                    ->prefix('sms')
                    ->controller(SmsController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // NOTIFICATIONS
                Route::name('notifications.')
                    ->prefix('notifications')
                    ->controller(NotificationController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // MESSAGES DE CONTACT
                Route::name('messages.')
                    ->prefix('messages')
                    ->controller(ContactMessageController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // ABONNÉS NEWSLETTER
                Route::name('subscribers.')
                    ->prefix('subscribers')
                    ->controller(NewsletterSubscriberController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');
                    });


                // CHAT ADMIN
                Route::name('chat.')
                    ->prefix('chat')
                    ->controller(AdminChatController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/{user}', 'show')
                            ->name('show');

                        Route::post('/{user}/reply', 'reply')
                            ->name('reply');

                        Route::post('/{user}/toggle', 'toggleAccess')
                            ->name('toggle');

                        Route::put('/messages/{message}', 'updateMessage')
                            ->name('messages.update');

                        Route::delete('/messages/{message}', 'destroyMessage')
                            ->name('messages.destroy');
                    });
            });


        // ================================================================
        // MÉDIAS
        // ================================================================

        Route::name('media.')
            ->prefix('media')
            ->group(function () {

                // GALERIE
                Route::name('gallery.')
                    ->prefix('gallery')
                    ->controller(GalleryController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{image}', 'update')
                            ->name('update');

                        Route::delete('/{image}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });


                // VIDÉOS
                Route::name('videos.')
                    ->prefix('videos')
                    ->controller(VideoController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{video}', 'update')
                            ->name('update');

                        Route::delete('/{video}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });
            });


        // ================================================================
        // CANDIDATURES / ENGAGEMENTS
        // ================================================================
        //
        // UNE SEULE INTERFACE pour :
        // - partenaires
        // - bénévoles
        //
        // Les deux tables restent séparées en base de données.
        //
        // ================================================================

        Route::name('engagements.')
            ->prefix('engagements')
            ->controller(EngagementController::class)
            ->group(function () {

                // --------------------------------------------------------
                // LISTE
                // GET /admin/engagements
                // admin.engagements.index
                // --------------------------------------------------------

                Route::get('/', 'index')
                    ->name('index');


                // --------------------------------------------------------
                // DÉTAIL
                // GET /admin/engagements/{type}/{id}
                //
                // Exemples :
                // /admin/engagements/partner/15
                // /admin/engagements/volunteer/8
                // --------------------------------------------------------

                Route::get('/{type}/{id}', 'show')
                    ->name('show');


                // --------------------------------------------------------
                // STATUT
                // PATCH /admin/engagements/{type}/{id}/status
                // --------------------------------------------------------

                Route::patch('/{type}/{id}/status', 'updateStatus')
                    ->name('status');


                // --------------------------------------------------------
                // NOTES ADMINISTRATIVES
                // PATCH /admin/engagements/{type}/{id}/notes
                // --------------------------------------------------------

                Route::patch('/{type}/{id}/notes', 'updateNotes')
                    ->name('notes');


                // --------------------------------------------------------
                // DOCUMENT — PRÉVISUALISATION
                // --------------------------------------------------------

                Route::get('/{type}/{id}/document/preview', 'previewDocument')
                    ->name('document.preview');


                // --------------------------------------------------------
                // DOCUMENT — TÉLÉCHARGEMENT
                // --------------------------------------------------------

                Route::get('/{type}/{id}/document/download', 'downloadDocument')
                    ->name('document.download');


                // --------------------------------------------------------
                // SUPPRESSION
                // DELETE /admin/engagements/{type}/{id}
                // --------------------------------------------------------

                Route::delete('/{type}/{id}', 'destroy')
                    ->name('destroy');
            });


        // ================================================================
        // PARTENAIRES
        // ================================================================

        Route::name('partners.')
            ->prefix('partners')
            ->group(function () {

                // SPONSORS
                Route::name('sponsors.')
                    ->prefix('sponsors')
                    ->controller(SponsorController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{sponsor}', 'update')
                            ->name('update');

                        Route::delete('/{sponsor}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });


                // TÉMOIGNAGES
                Route::name('testimonials.')
                    ->prefix('testimonials')
                    ->controller(TestimonialController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{testimonial}', 'update')
                            ->name('update');

                        Route::delete('/{testimonial}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });


                // ÉQUIPE
                Route::name('team.')
                    ->prefix('team')
                    ->controller(TeamMemberController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{member}', 'update')
                            ->name('update');

                        Route::delete('/{member}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });
            });


        // ================================================================
        // BOUTIQUE
        // ================================================================

        Route::name('shop.')
            ->prefix('shop')
            ->group(function () {

                // PRODUITS
                Route::name('products.')
                    ->prefix('products')
                    ->controller(ProductController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::post('/', 'store')
                            ->name('store');

                        Route::put('/{product}', 'update')
                            ->name('update');

                        Route::delete('/{product}', 'destroy')
                            ->name('destroy');

                        Route::delete('/', 'bulkDestroy')
                            ->name('bulk-destroy');
                    });


                // COMMANDES
                Route::name('orders.')
                    ->prefix('orders')
                    ->controller(OrderController::class)
                    ->group(function () {

                        Route::get('/', 'index')
                            ->name('index');

                        Route::get('/{order}', 'show')
                            ->name('show');

                        Route::put('/{order}', 'update')
                            ->name('update');

                        Route::delete('/{order}', 'destroy')
                            ->name('destroy');
                    });
            });


        // ================================================================
        // PAGES FRONT-OFFICE
        // ================================================================

        Route::name('pages.')
            ->prefix('pages')
            ->group(function () {

                // HOME
                Route::get('home', [HomePageController::class, 'edit'])
                    ->name('home.edit');

                Route::put('home', [HomePageController::class, 'update'])
                    ->name('home.update');


                // NAVIGATION
                Route::get('navigation', [MenuItemController::class, 'navigation'])
                    ->name('navigation.index');

                Route::post('navigation', [MenuItemController::class, 'store'])
                    ->name('navigation.store');

                Route::put('navigation/{menuItem}', [MenuItemController::class, 'update'])
                    ->name('navigation.update');

                Route::delete('navigation/{menuItem}', [MenuItemController::class, 'destroy'])
                    ->name('navigation.destroy');


                // FOOTER
                Route::get('footer', [MenuItemController::class, 'footer'])
                    ->name('footer.index');

                Route::post('footer', [MenuItemController::class, 'store'])
                    ->name('footer.store');

                Route::put('footer/{menuItem}', [MenuItemController::class, 'update'])
                    ->name('footer.update');

                Route::delete('footer/{menuItem}', [MenuItemController::class, 'destroy'])
                    ->name('footer.destroy');


                // PAGES ENGAGEMENT
                Route::get('engagement/{type}', [EngagementPageController::class, 'edit'])
                    ->name('engagement.edit');

                Route::put('engagement/{type}', [EngagementPageController::class, 'update'])
                    ->name('engagement.update');


                // PAGES PERSONNALISÉES
                Route::resource('custom', CustomPageController::class);


                // FONDATEUR
                Route::get('founder', [FounderController::class, 'edit'])
                    ->name('founder.edit');

                Route::put('founder', [FounderController::class, 'update'])
                    ->name('founder.update');

                Route::post('founder/photos', [FounderController::class, 'storePhoto'])
                    ->name('founder.photos.store');

                Route::delete('founder/photos/{photo}', [FounderController::class, 'destroyPhoto'])
                    ->name('founder.photos.destroy');
            });


        // ================================================================
        // PARAMÈTRES
        // ================================================================

        Route::get('settings', SettingController::class)
            ->name('settings.index');
    });
