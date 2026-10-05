<?php

namespace App\Mail;

use App\Models\EventReservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EventReservationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * La réservation concernée.
     */
    public EventReservation $reservation;

    /**
     * Create a new message instance.
     */
    public function __construct(EventReservation $reservation)
    {
        $this->reservation = $reservation;

        // On charge l'événement si ce n'est pas déjà fait.
        $this->reservation->loadMissing('event');
    }

    /**
     * Sujet du mail.
     */
    public function envelope(): Envelope
    {
        $eventTitle = $this->reservation->event?->title
            ?? 'votre événement';

        return new Envelope(
            subject: 'Votre réservation est confirmée — ' . $eventTitle,
        );
    }

    /**
     * Vue utilisée pour le mail.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.events.reservation-confirmation',
            with: [
                'reservation' => $this->reservation,
                'event' => $this->reservation->event,
            ],
        );
    }

    /**
     * Pièces jointes.
     */
    public function attachments(): array
    {
        return [];
    }
}
