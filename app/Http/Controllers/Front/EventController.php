<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;

class EventController extends Controller
{
    /**
     * Liste publique des événements.
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENTS À VENIR + EN COURS
        |--------------------------------------------------------------------------
        */

        $upcoming = Event::query()
            ->with('category')
            ->withSum([
                'reservations as confirmed_reserved_places' => function ($query) {
                    $query->where('status', 'confirmed');
                }
            ], 'quantity')
            ->where('status', 'published')
            ->where(function ($query) {

                // Événements à venir
                $query->where('starts_at', '>=', now())

                    // Événements actuellement en cours
                    ->orWhere(function ($subQuery) {
                        $subQuery
                            ->where('starts_at', '<=', now())
                            ->whereNotNull('ends_at')
                            ->where('ends_at', '>=', now());
                    });
            })
            ->orderBy('starts_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENTS PASSÉS
        |--------------------------------------------------------------------------
        */

        $past = Event::query()
            ->with('category')
            ->where('status', 'published')
            ->where(function ($query) {

                // Événement avec une date de fin dépassée
                $query->where('ends_at', '<', now())

                    // Événement sans date de fin dont le début est dépassé
                    ->orWhere(function ($subQuery) {
                        $subQuery
                            ->whereNull('ends_at')
                            ->where('starts_at', '<', now());
                    });
            })
            ->orderByDesc('starts_at')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CATÉGORIES ACTIVES
        |--------------------------------------------------------------------------
        */

        $categories = EventCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VUE
        |--------------------------------------------------------------------------
        */

        return view(
            'front.events.index',
            compact(
                'upcoming',
                'past',
                'categories'
            )
        );
    }


    /**
     * Affiche la fiche publique d'un événement.
     */
    public function show(Event $event)
    {
        /*
        |--------------------------------------------------------------------------
        | Seuls les événements publiés sont accessibles publiquement
        |--------------------------------------------------------------------------
        */

        abort_if(
            $event->status !== 'published',
            404
        );


        /*
        |--------------------------------------------------------------------------
        | Charger la catégorie
        |--------------------------------------------------------------------------
        */

        $event->load('category');


        /*
        |--------------------------------------------------------------------------
        | Nombre réel de places confirmées
        |--------------------------------------------------------------------------
        */

        $event->loadSum([
            'reservations as confirmed_reserved_places' => function ($query) {
                $query->where('status', 'confirmed');
            }
        ], 'quantity');


        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENTS SIMILAIRES
        |--------------------------------------------------------------------------
        */

        $relatedEvents = Event::query()
            ->with('category')
            ->where('status', 'published')
            ->where('id', '!=', $event->id)
            ->where('starts_at', '>=', now())
            ->when(
                $event->event_category_id,
                function ($query) use ($event) {
                    $query->where(
                        'event_category_id',
                        $event->event_category_id
                    );
                }
            )
            ->orderBy('starts_at')
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | VUE DÉTAIL
        |--------------------------------------------------------------------------
        */

        return view(
            'front.events.show',
            compact(
                'event',
                'relatedEvents'
            )
        );
    }
}
