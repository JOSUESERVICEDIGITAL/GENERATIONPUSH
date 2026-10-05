<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Formation;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\Sponsor;
use App\Models\Testimonial;
use App\Models\User;

class HomeController extends Controller
{
    public function __invoke()
    {
        /*
        |--------------------------------------------------------------------------
        | PARAMÈTRES DU SITE
        |--------------------------------------------------------------------------
        */

        $settings = SiteSetting::current();


        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        $averageRating = Testimonial::avg('rating');

        $stats = [
            'members' => User::count(),

            'formations' => Formation::count(),

            // Tous les événements enregistrés dans le nouveau module unifié
            'events' => Event::count(),

            'satisfaction' => $averageRating
                ? round($averageRating / 5 * 100)
                : 98,
        ];


        /*
        |--------------------------------------------------------------------------
        | FORMATIONS
        |--------------------------------------------------------------------------
        */

        $formations = Formation::where('status', 'active')
            ->latest()
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENTS À VENIR
        |--------------------------------------------------------------------------
        |
        | Nouvelle architecture :
        |
        | Conference, Masterclass, Coaching, PushConnect, etc.
        | ne sont plus des modèles séparés.
        |
        | Tout passe désormais par Event.
        |
        | La catégorie est récupérée avec la relation "category".
        |--------------------------------------------------------------------------
        */

        $events = Event::query()
    ->with('category')
    ->withSum([
        'reservations as confirmed_reserved_places' => function ($query) {
            $query->where('status', 'confirmed');
        }
    ], 'quantity')
    ->where('status', 'published')
    ->where(function ($query) {

        // Événements qui n'ont pas encore commencé
        $query->where('starts_at', '>=', now())

            // OU événements actuellement en cours
            ->orWhere(function ($subQuery) {
                $subQuery
                    ->where('starts_at', '<=', now())
                    ->whereNotNull('ends_at')
                    ->where('ends_at', '>=', now());
            });
    })
    ->orderByDesc('featured')
    ->orderBy('starts_at')
    ->take(12)
    ->get();

        /*
        |--------------------------------------------------------------------------
        | TÉMOIGNAGES
        |--------------------------------------------------------------------------
        */

        $testimonials = Testimonial::where('status', 'published')
            ->where('featured', true)
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | SPONSORS
        |--------------------------------------------------------------------------
        */

        $sponsors = Sponsor::where('status', 'active')
            ->orderBy('order')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | BLOG
        |--------------------------------------------------------------------------
        */

        $posts = Post::where('status', 'published')
            ->with('category')
            ->latest('published_at')
            ->take(3)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | HOME
        |--------------------------------------------------------------------------
        */
        $ongoingEvents = Event::query()
            ->with('category')
            ->where('status', 'published')
            ->where('starts_at', '<=', now())
            ->whereNotNull('ends_at')
            ->where('ends_at', '>=', now())
            ->orderBy('starts_at')
            ->get();

        return view('front.home', compact(
            'settings',
            'stats',
            'events',
            'ongoingEvents',
            'testimonials',
            'sponsors',
            'posts'
        ));
    }
}
