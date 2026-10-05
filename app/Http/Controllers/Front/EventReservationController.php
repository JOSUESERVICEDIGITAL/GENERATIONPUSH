<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventReservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


//undefined method 'id'.intelephense(P1013)

use Illuminate\Support\Facades\Auth;


class EventReservationController extends Controller
{
    /**
     * Enregistrer une réservation.
     */
    public function store(Request $request, Event $event)
    {
        /*
        |--------------------------------------------------------------------------
        | Vérifier que l'événement est publié
        |--------------------------------------------------------------------------
        */

        abort_if($event->status !== 'published', 404);


        /*
        |--------------------------------------------------------------------------
        | Validation du formulaire
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:10',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Création sécurisée de la réservation
        |--------------------------------------------------------------------------
        */

        $reservation = DB::transaction(function () use (
            $validated,
            $event
        ) {

            /*
            |--------------------------------------------------------------------------
            | Recharger et verrouiller l'événement
            |--------------------------------------------------------------------------
            |
            | Cela évite que deux personnes prennent simultanément
            | la dernière place disponible.
            |--------------------------------------------------------------------------
            */

            $lockedEvent = Event::query()
                ->lockForUpdate()
                ->findOrFail($event->id);


            /*
            |--------------------------------------------------------------------------
            | Vérifier si les réservations sont ouvertes
            |--------------------------------------------------------------------------
            */

            if (!$lockedEvent->can_reserve) {

                throw ValidationException::withMessages([
                    'quantity' =>
                        'Les réservations ne sont actuellement pas disponibles pour cet événement.',
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Nombre de places déjà confirmées
            |--------------------------------------------------------------------------
            */

            $reservedPlaces = (int) $lockedEvent
                ->reservations()
                ->where('status', 'confirmed')
                ->sum('quantity');


            /*
            |--------------------------------------------------------------------------
            | Vérifier la capacité
            |--------------------------------------------------------------------------
            */

            if ($lockedEvent->capacity !== null) {

                $remainingPlaces = max(
                    0,
                    $lockedEvent->capacity - $reservedPlaces
                );

                if ($validated['quantity'] > $remainingPlaces) {

                    throw ValidationException::withMessages([
                        'quantity' => $remainingPlaces > 0
                            ? "Il ne reste que {$remainingPlaces} place(s) disponible(s)."
                            : 'Cet événement est complet.',
                    ]);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Prix
            |--------------------------------------------------------------------------
            */

            $amount = $lockedEvent->is_free
                ? 0
                : (
                    (float) $lockedEvent->price
                    * $validated['quantity']
                );


            /*
            |--------------------------------------------------------------------------
            | Statut
            |--------------------------------------------------------------------------
            |
            | Événement gratuit :
            | réservation directement confirmée.
            |
            | Événement payant :
            | réservation en attente du paiement PayDunya.
            |--------------------------------------------------------------------------
            */

            $reservationStatus = $lockedEvent->is_free
                ? 'confirmed'
                : 'pending';

            $paymentStatus = $lockedEvent->is_free
                ? 'not_required'
                : 'pending';


            /*
            |--------------------------------------------------------------------------
            | Création
            |--------------------------------------------------------------------------
            */

            return EventReservation::create([

                'event_id' => $lockedEvent->id,

                'user_id' => Auth::id(),

                'first_name' => $validated['first_name'],

                'last_name' => $validated['last_name'],

                'email' => $validated['email'],

                'phone' => $validated['phone'] ?? null,

                'reference' => $this->generateReference(),

                'quantity' => $validated['quantity'],

                'status' => $reservationStatus,

                'payment_status' => $paymentStatus,

                'amount' => $amount,

                'currency' => $lockedEvent->currency,

                'notes' => $validated['notes'] ?? null,

                'confirmed_at' => $lockedEvent->is_free
                    ? now()
                    : null,
            ]);
        });


        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENT PAYANT
        |--------------------------------------------------------------------------
        |
        | Nous brancherons PayDunya ici.
        |--------------------------------------------------------------------------
        */

        if (!$event->is_free) {

            return redirect()
                ->route(
                    'events.show',
                    $event
                )
                ->with(
                    'info',
                    'Votre réservation a été créée. Le paiement doit maintenant être effectué.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | ÉVÉNEMENT GRATUIT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'events.reservations.success',
                $reservation
            )
            ->with(
                'success',
                'Votre réservation a été confirmée avec succès.'
            );
    }


    /**
     * Page de confirmation.
     */
    public function success(EventReservation $reservation)
    {
        return view(
            'front.events.success',
            compact('reservation')
        );
    }


    /**
     * Générer une référence unique.
     */
    private function generateReference(): string
    {
        do {

            $reference =
                'PUSH-' .
                now()->format('Ymd') .
                '-' .
                Str::upper(Str::random(8));

        } while (
            EventReservation::where(
                'reference',
                $reference
            )->exists()
        );

        return $reference;
    }
}
