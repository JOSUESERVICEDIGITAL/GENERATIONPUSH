@extends('layouts.admin')

@section('title', 'Détails de l’événement')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | PLACES RÉSERVÉES
    |--------------------------------------------------------------------------
    */

    $reservedPlaces = $event->reservations
        ->where('status', 'confirmed')
        ->sum('quantity');

    $remainingPlaces = $event->capacity
        ? max($event->capacity - $reservedPlaces, 0)
        : null;

    $fillRate = $event->capacity > 0
        ? min(round(($reservedPlaces / $event->capacity) * 100), 100)
        : 0;


    /*
    |--------------------------------------------------------------------------
    | STATUT TEMPOREL
    |--------------------------------------------------------------------------
    */

    if ($event->starts_at->isFuture()) {

        $temporalLabel = 'À venir';

        $temporalClasses =
            'bg-blue-500/10 text-blue-600 dark:text-blue-400';

    } elseif (
        $event->ends_at &&
        now()->between(
            $event->starts_at,
            $event->ends_at
        )
    ) {

        $temporalLabel = 'En cours';

        $temporalClasses =
            'bg-green-500/10 text-green-600 dark:text-green-400';

    } else {

        $temporalLabel = 'Terminé';

        $temporalClasses =
            'bg-secondary text-muted-foreground';
    }


    /*
    |--------------------------------------------------------------------------
    | STATUT DE PUBLICATION
    |--------------------------------------------------------------------------
    */

    $publicationLabel = match($event->status) {
        'published' => 'Publié',
        'draft' => 'Brouillon',
        'cancelled' => 'Annulé',
        default => ucfirst($event->status),
    };

    $publicationClasses = match($event->status) {

        'published' =>
            'bg-green-500/10 text-green-600 dark:text-green-400',

        'draft' =>
            'bg-yellow-500/10 text-yellow-600 dark:text-yellow-400',

        'cancelled' =>
            'bg-red-500/10 text-red-600 dark:text-red-400',

        default =>
            'bg-secondary text-muted-foreground',
    };


    /*
    |--------------------------------------------------------------------------
    | FORMAT
    |--------------------------------------------------------------------------
    */

    $formatLabel = match($event->format) {
        'physical' => 'Présentiel',
        'online' => 'En ligne',
        'hybrid' => 'Hybride',
        default => ucfirst($event->format),
    };


    /*
    |--------------------------------------------------------------------------
    | RÉSERVATIONS
    |--------------------------------------------------------------------------
    */

    $confirmedReservations = $event->reservations
        ->where('status', 'confirmed');

    $pendingReservations = $event->reservations
        ->where('status', 'pending');

    $cancelledReservations = $event->reservations
        ->where('status', 'cancelled');

@endphp


<div class="space-y-6">

    {{-- ================================================================
         EN-TÊTE
    ================================================================ --}}

    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">

        <div class="min-w-0">

            {{-- BREADCRUMB --}}

            <div class="flex flex-wrap items-center gap-2 text-sm text-muted-foreground mb-3">

                <a
                    href="{{ route('admin.events.index') }}"
                    class="hover:text-accent transition-colors"
                >
                    Événements
                </a>

                <x-icon
                    name="chevron-right"
                    class="w-4 h-4"
                />

                <span class="text-foreground">
                    Détails
                </span>

            </div>


            {{-- TITRE --}}

            <div class="flex flex-wrap items-center gap-3">

                <h1 class="text-2xl md:text-3xl font-bold text-foreground">
                    {{ $event->title }}
                </h1>


                @if($event->featured)

                    <span
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-accent/10 text-accent text-xs font-semibold"
                    >

                        <x-icon
                            name="star"
                            class="w-3.5 h-3.5"
                        />

                        À la une

                    </span>

                @endif

            </div>


            @if($event->subtitle)

                <p class="text-muted-foreground mt-2 max-w-3xl">
                    {{ $event->subtitle }}
                </p>

            @endif


            {{-- BADGES --}}

            <div class="flex flex-wrap gap-2 mt-4">

    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-secondary text-foreground text-xs font-semibold">
        ID #{{ $event->id }}
    </span>

    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $temporalClasses }}">
        {{ $temporalLabel }}
    </span>

    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $publicationClasses }}">
        {{ $publicationLabel }}
    </span>

    @if($event->category)
        <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-accent/10 text-accent text-xs font-medium">
            {{ $event->category->name }}
        </span>
    @endif

    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-secondary text-foreground text-xs font-medium">
        {{ $formatLabel }}
    </span>

</div>

        </div>


        {{-- ACTIONS --}}

        <div class="flex flex-wrap gap-2 shrink-0">

            <a
                href="{{ route('admin.events.index') }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-border text-foreground hover:bg-secondary transition"
            >

                <x-icon
                    name="arrow-left"
                    class="w-4 h-4"
                />

                Retour

            </a>


            <a
                href="{{ route('admin.events.edit', $event) }}"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition"
            >

                <x-icon
                    name="pencil"
                    class="w-4 h-4"
                />

                Modifier

            </a>


            {{-- SUPPRESSION --}}

            <div
                x-data="{ deleteOpen: false }"
                class="relative"
            >

                <button
                    type="button"
                    @click="deleteOpen = true"
                    class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-destructive/30 text-destructive hover:bg-destructive/10 transition"
                >

                    <x-icon
                        name="trash-2"
                        class="w-4 h-4"
                    />

                    Supprimer

                </button>


                {{-- MODALE --}}

                <div
                    x-show="deleteOpen"
                    x-cloak
                    class="fixed inset-0 z-[100] flex items-center justify-center p-4"
                >

                    <div
                        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                        @click="deleteOpen = false"
                    ></div>


                    <div
                        x-show="deleteOpen"
                        x-transition
                        class="relative w-full max-w-md bg-card border border-border rounded-2xl shadow-2xl p-6"
                    >

                        <div
                            class="w-12 h-12 rounded-full bg-destructive/10 flex items-center justify-center mb-4"
                        >

                            <x-icon
                                name="trash-2"
                                class="w-5 h-5 text-destructive"
                            />

                        </div>


                        <h3 class="text-lg font-semibold text-foreground">
                            Supprimer cet événement ?
                        </h3>


                        <p class="text-sm text-muted-foreground mt-2">

                            Vous êtes sur le point de supprimer

                            <strong class="text-foreground">
                                {{ $event->title }}
                            </strong>.

                        </p>


                        <div class="flex justify-end gap-3 mt-6">

                            <button
                                type="button"
                                @click="deleteOpen = false"
                                class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary"
                            >
                                Annuler
                            </button>


                            <form
                                method="POST"
                                action="{{ route('admin.events.destroy', $event) }}"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="px-4 py-2 rounded-lg bg-destructive text-white font-medium hover:opacity-90"
                                >
                                    Supprimer
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- ================================================================
         BANNIÈRE
    ================================================================ --}}
{{-- ================================================================
     MÉDIAS DE L'ÉVÉNEMENT
================================================================ --}}

@if(
    $event->banner ||
    $event->image ||
    $event->speaker_image
)

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Médias de l'événement
            </h2>

            <p class="text-sm text-muted-foreground mt-1">
                Images enregistrées pour cet événement.
            </p>

        </div>


        {{-- BANNIÈRE --}}

        @if($event->banner)

            <div class="relative">

                <img
                    src="{{ asset('storage/' . $event->banner) }}"
                    alt="Bannière {{ $event->title }}"
                    class="w-full h-[220px] sm:h-[300px] lg:h-[380px] object-cover"
                >

                <div class="absolute top-4 start-4">

                    <span class="px-3 py-1.5 rounded-full bg-black/70 text-white text-xs font-semibold">
                        Bannière
                    </span>

                </div>

            </div>

        @endif


        <div class="p-5">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- IMAGE PRINCIPALE --}}

                @if($event->image)

                    <div>

                        <p class="text-sm font-medium text-foreground mb-2">
                            Image principale
                        </p>

                        <img
                            src="{{ asset('storage/' . $event->image) }}"
                            alt="{{ $event->title }}"
                            class="w-full h-64 object-cover rounded-xl border border-border"
                        >

                    </div>

                @endif


                {{-- PHOTO INTERVENANT --}}

                @if($event->speaker_image)

                    <div>

                        <p class="text-sm font-medium text-foreground mb-2">
                            Photo de l'intervenant
                        </p>

                        <div class="h-64 rounded-xl border border-border bg-secondary flex items-center justify-center">

                            <img
                                src="{{ asset('storage/' . $event->speaker_image) }}"
                                alt="{{ $event->speaker ?? 'Intervenant' }}"
                                class="w-40 h-40 object-cover rounded-full border-4 border-card shadow-lg"
                            >

                        </div>

                    </div>

                @endif

            </div>

        </div>

    </section>

@endif



    {{-- ================================================================
         STATISTIQUES
    ================================================================ --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- CAPACITÉ --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm text-muted-foreground">
                        Capacité
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">

                        {{ $event->capacity
                            ? number_format($event->capacity)
                            : '∞'
                        }}

                    </p>

                    <p class="text-xs text-muted-foreground mt-1">
                        {{ $event->capacity ? 'places' : 'Illimitée' }}
                    </p>

                </div>


                <div
                    class="w-11 h-11 rounded-xl bg-secondary flex items-center justify-center"
                >

                    <x-icon
                        name="users"
                        class="w-5 h-5 text-foreground"
                    />

                </div>

            </div>

        </div>


        {{-- RÉSERVÉES --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm text-muted-foreground">
                        Places réservées
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ number_format($reservedPlaces) }}
                    </p>

                    <p class="text-xs text-muted-foreground mt-1">
                        réservations confirmées
                    </p>

                </div>


                <div
                    class="w-11 h-11 rounded-xl bg-green-500/10 flex items-center justify-center"
                >

                    <x-icon
                        name="user-check"
                        class="w-5 h-5 text-green-600"
                    />

                </div>

            </div>

        </div>


        {{-- DISPONIBLES --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div>

                    <p class="text-sm text-muted-foreground">
                        Places disponibles
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">

                        {{ $remainingPlaces !== null
                            ? number_format($remainingPlaces)
                            : '∞'
                        }}

                    </p>

                    <p class="text-xs text-muted-foreground mt-1">
                        restantes
                    </p>

                </div>


                <div
                    class="w-11 h-11 rounded-xl bg-blue-500/10 flex items-center justify-center"
                >

                    <x-icon
                        name="ticket"
                        class="w-5 h-5 text-blue-600"
                    />

                </div>

            </div>

        </div>


        {{-- REMPLISSAGE --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-start justify-between gap-4">

                <div class="flex-1">

                    <p class="text-sm text-muted-foreground">
                        Taux de remplissage
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ $event->capacity ? $fillRate . '%' : '—' }}
                    </p>


                    @if($event->capacity)

                        <div
                            class="w-full h-1.5 bg-secondary rounded-full overflow-hidden mt-3"
                        >

                            <div
                                class="h-full bg-accent rounded-full"
                                style="width: {{ $fillRate }}%"
                            ></div>

                        </div>

                    @endif

                </div>


                <div
                    class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center shrink-0"
                >

                    <x-icon
                        name="chart-no-axes-column-increasing"
                        class="w-5 h-5 text-accent"
                    />

                </div>

            </div>

        </div>

    </div>



    {{-- ================================================================
         CONTENU
    ================================================================ --}}

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ============================================================
             COLONNE PRINCIPALE
        ============================================================ --}}

        <div class="xl:col-span-2 space-y-6">


            {{-- DESCRIPTION --}}

            <section
                class="bg-card border border-border rounded-xl shadow-sm overflow-hidden"
            >

                <div class="px-5 py-4 border-b border-border">

                    <h2 class="font-semibold text-foreground">
                        À propos de l'événement
                    </h2>

                </div>


                <div class="p-5">

                    @if($event->short_description)

                        <p class="font-medium text-foreground leading-relaxed mb-4">
                            {{ $event->short_description }}
                        </p>

                    @endif


                    @if($event->description)

                        <div
                            class="text-sm md:text-base text-muted-foreground leading-7 whitespace-pre-line"
                        >{{ $event->description }}</div>

                    @else

                        <p class="text-sm text-muted-foreground">
                            Aucune description n'a été renseignée.
                        </p>

                    @endif

                </div>

            </section>



            {{-- ========================================================
                 RÉSERVATIONS
            ======================================================== --}}

            <section
                class="bg-card border border-border rounded-xl shadow-sm overflow-hidden"
            >

                <div
                    class="px-5 py-4 border-b border-border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3"
                >

                    <div>

                        <h2 class="font-semibold text-foreground">
                            Réservations
                        </h2>

                        <p class="text-sm text-muted-foreground mt-1">
                            Participants inscrits à cet événement.
                        </p>

                    </div>


                    <div class="flex flex-wrap gap-2">

                        <span
                            class="px-2.5 py-1 rounded-full bg-green-500/10 text-green-600 text-xs font-medium"
                        >
                            {{ $confirmedReservations->count() }}
                            confirmée(s)
                        </span>


                        @if($pendingReservations->count())

                            <span
                                class="px-2.5 py-1 rounded-full bg-yellow-500/10 text-yellow-600 text-xs font-medium"
                            >
                                {{ $pendingReservations->count() }}
                                en attente
                            </span>

                        @endif

                    </div>

                </div>


                @if($event->reservations->count())

                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead
                                class="bg-secondary/50 border-b border-border"
                            >

                                <tr>

                                    <th
                                        class="px-5 py-3 text-start font-medium text-muted-foreground"
                                    >
                                        Participant
                                    </th>

                                    <th
                                        class="px-4 py-3 text-start font-medium text-muted-foreground"
                                    >
                                        Contact
                                    </th>

                                    <th
                                        class="px-4 py-3 text-start font-medium text-muted-foreground"
                                    >
                                        Places
                                    </th>

                                    <th
                                        class="px-4 py-3 text-start font-medium text-muted-foreground"
                                    >
                                        Réservation
                                    </th>

                                    <th
                                        class="px-4 py-3 text-start font-medium text-muted-foreground"
                                    >
                                        Paiement
                                    </th>

                                    <th
                                        class="px-5 py-3 text-end font-medium text-muted-foreground"
                                    >
                                        Date
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-border">

                                @foreach(
                                    $event->reservations
                                        ->sortByDesc('created_at')
                                    as $reservation
                                )

                                    @php

                                        $reservationStatusLabel =
                                            match($reservation->status) {

                                                'confirmed' =>
                                                    'Confirmée',

                                                'pending' =>
                                                    'En attente',

                                                'cancelled' =>
                                                    'Annulée',

                                                default =>
                                                    ucfirst(
                                                        $reservation->status
                                                    ),
                                            };


                                        $reservationStatusClasses =
                                            match($reservation->status) {

                                                'confirmed' =>
                                                    'bg-green-500/10 text-green-600',

                                                'pending' =>
                                                    'bg-yellow-500/10 text-yellow-600',

                                                'cancelled' =>
                                                    'bg-red-500/10 text-red-600',

                                                default =>
                                                    'bg-secondary text-muted-foreground',
                                            };


                                        $paymentLabel =
                                            match($reservation->payment_status) {

                                                'not_required' =>
                                                    'Non requis',

                                                'pending' =>
                                                    'En attente',

                                                'paid' =>
                                                    'Payé',

                                                'failed' =>
                                                    'Échoué',

                                                'refunded' =>
                                                    'Remboursé',

                                                default =>
                                                    ucfirst(
                                                        $reservation->payment_status
                                                    ),
                                            };


                                        $paymentClasses =
                                            match($reservation->payment_status) {

                                                'paid' =>
                                                    'bg-green-500/10 text-green-600',

                                                'pending' =>
                                                    'bg-yellow-500/10 text-yellow-600',

                                                'failed' =>
                                                    'bg-red-500/10 text-red-600',

                                                'refunded' =>
                                                    'bg-blue-500/10 text-blue-600',

                                                default =>
                                                    'bg-secondary text-muted-foreground',
                                            };

                                    @endphp


                                    <tr
                                        class="hover:bg-secondary/30 transition-colors"
                                    >

                                        {{-- PARTICIPANT --}}

                                        <td class="px-5 py-4">

                                            <div class="flex items-center gap-3">

                                                <div
                                                    class="w-9 h-9 rounded-full bg-accent/10 text-accent flex items-center justify-center font-semibold text-xs shrink-0"
                                                >

                                                    {{ strtoupper(
                                                        substr(
                                                            $reservation->first_name,
                                                            0,
                                                            1
                                                        )
                                                        .
                                                        substr(
                                                            $reservation->last_name,
                                                            0,
                                                            1
                                                        )
                                                    ) }}

                                                </div>


                                                <div>

                                                    <p
                                                        class="font-medium text-foreground whitespace-nowrap"
                                                    >
                                                        {{ $reservation->first_name }}
                                                        {{ $reservation->last_name }}
                                                    </p>

                                                    <p
                                                        class="text-xs text-muted-foreground mt-0.5"
                                                    >
                                                        {{ $reservation->reference }}
                                                    </p>

                                                </div>

                                            </div>

                                        </td>


                                        {{-- CONTACT --}}

                                        <td class="px-4 py-4">

                                            <p class="text-foreground whitespace-nowrap">
                                                {{ $reservation->email }}
                                            </p>

                                            @if($reservation->phone)

                                                <p
                                                    class="text-xs text-muted-foreground mt-1 whitespace-nowrap"
                                                >
                                                    {{ $reservation->phone }}
                                                </p>

                                            @endif

                                        </td>


                                        {{-- QUANTITÉ --}}

                                        <td class="px-4 py-4">

                                            <span class="font-semibold text-foreground">
                                                {{ $reservation->quantity }}
                                            </span>

                                        </td>


                                        {{-- STATUT --}}

                                        <td class="px-4 py-4">

                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $reservationStatusClasses }}"
                                            >
                                                {{ $reservationStatusLabel }}
                                            </span>

                                        </td>


                                        {{-- PAIEMENT --}}

                                        <td class="px-4 py-4">

                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium whitespace-nowrap {{ $paymentClasses }}"
                                            >
                                                {{ $paymentLabel }}
                                            </span>

                                        </td>


                                        {{-- DATE --}}

                                        <td
                                            class="px-5 py-4 text-end whitespace-nowrap"
                                        >

                                            <p class="text-foreground">
                                                {{ $reservation->created_at->format('d/m/Y') }}
                                            </p>

                                            <p class="text-xs text-muted-foreground mt-1">
                                                {{ $reservation->created_at->format('H:i') }}
                                            </p>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <div class="py-12 px-5 text-center">

                        <div
                            class="w-12 h-12 rounded-xl bg-secondary flex items-center justify-center mx-auto"
                        >

                            <x-icon
                                name="users"
                                class="w-5 h-5 text-muted-foreground"
                            />

                        </div>


                        <h3 class="font-semibold text-foreground mt-4">
                            Aucune réservation
                        </h3>

                        <p class="text-sm text-muted-foreground mt-1">
                            Cet événement ne possède encore aucune réservation.
                        </p>

                    </div>

                @endif

            </section>

        </div>



        {{-- ============================================================
             COLONNE LATÉRALE
        ============================================================ --}}

        <div class="space-y-6">


            {{-- DATE / LIEU --}}

            <section
                class="bg-card border border-border rounded-xl shadow-sm overflow-hidden"
            >

                <div class="px-5 py-4 border-b border-border">

                    <h2 class="font-semibold text-foreground">
                        Informations pratiques
                    </h2>

                </div>


                <div class="p-5 space-y-5">

                    {{-- DATE --}}

                    <div class="flex gap-3">

                        <div
                            class="w-10 h-10 rounded-lg bg-accent/10 flex items-center justify-center shrink-0"
                        >

                            <x-icon
                                name="calendar"
                                class="w-4 h-4 text-accent"
                            />

                        </div>


                        <div>

                            <p class="text-xs text-muted-foreground">
                                Date de début
                            </p>

                            <p class="font-medium text-foreground mt-0.5">
                                {{ $event->starts_at->translatedFormat(
                                    'd F Y'
                                ) }}
                            </p>

                            <p class="text-sm text-muted-foreground">
                                {{ $event->starts_at->format('H:i') }}
                            </p>

                        </div>

                    </div>


                    {{-- FIN --}}

                    @if($event->ends_at)

                        <div class="flex gap-3">

                            <div
                                class="w-10 h-10 rounded-lg bg-secondary flex items-center justify-center shrink-0"
                            >

                                <x-icon
                                    name="clock"
                                    class="w-4 h-4 text-muted-foreground"
                                />

                            </div>


                            <div>

                                <p class="text-xs text-muted-foreground">
                                    Date de fin
                                </p>

                                <p class="font-medium text-foreground mt-0.5">
                                    {{ $event->ends_at->translatedFormat(
                                        'd F Y'
                                    ) }}
                                </p>

                                <p class="text-sm text-muted-foreground">
                                    {{ $event->ends_at->format('H:i') }}
                                </p>

                            </div>

                        </div>

                    @endif


                    {{-- FORMAT --}}

                    <div class="flex gap-3">

                        <div
                            class="w-10 h-10 rounded-lg bg-secondary flex items-center justify-center shrink-0"
                        >

                            <x-icon
                                name="{{ $event->format === 'online' ? 'video' : 'map-pin' }}"
                                class="w-4 h-4 text-muted-foreground"
                            />

                        </div>


                        <div class="min-w-0">

                            <p class="text-xs text-muted-foreground">
                                Format
                            </p>

                            <p class="font-medium text-foreground mt-0.5">
                                {{ $formatLabel }}
                            </p>

                        </div>

                    </div>


                    {{-- LIEU --}}

                    @if(
                        in_array(
                            $event->format,
                            ['physical', 'hybrid']
                        )
                    )

                        <div class="flex gap-3">

                            <div
                                class="w-10 h-10 rounded-lg bg-secondary flex items-center justify-center shrink-0"
                            >

                                <x-icon
                                    name="map-pin"
                                    class="w-4 h-4 text-muted-foreground"
                                />

                            </div>


                            <div class="min-w-0">

                                <p class="text-xs text-muted-foreground">
                                    Lieu
                                </p>


                                @if($event->venue)

                                    <p class="font-medium text-foreground mt-0.5">
                                        {{ $event->venue }}
                                    </p>

                                @endif


                                @if($event->address)

                                    <p class="text-sm text-muted-foreground">
                                        {{ $event->address }}
                                    </p>

                                @endif


                                @if($event->city || $event->country)

                                    <p class="text-sm text-muted-foreground">

                                        {{ collect([
                                            $event->city,
                                            $event->country
                                        ])->filter()->implode(', ') }}

                                    </p>

                                @endif

                            </div>

                        </div>

                    @endif


                    {{-- LIEN EN LIGNE --}}

                    @if(
                        in_array(
                            $event->format,
                            ['online', 'hybrid']
                        )
                        && $event->online_url
                    )

                        <div class="flex gap-3">

                            <div
                                class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center shrink-0"
                            >

                                <x-icon
                                    name="video"
                                    class="w-4 h-4 text-blue-600"
                                />

                            </div>


                            <div class="min-w-0">

                                <p class="text-xs text-muted-foreground">
                                    Participation en ligne
                                </p>

                                <a
                                    href="{{ $event->online_url }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1 text-sm font-medium text-accent hover:underline mt-1"
                                >

                                    Ouvrir le lien

                                    <x-icon
                                        name="external-link"
                                        class="w-3.5 h-3.5"
                                    />

                                </a>

                            </div>

                        </div>

                    @endif

                </div>

            </section>



            {{-- ========================================================
                 INTERVENANT
            ======================================================== --}}

            @if(
                $event->speaker ||
                $event->speaker_title ||
                $event->speaker_image
            )

                <section
                    class="bg-card border border-border rounded-xl shadow-sm overflow-hidden"
                >

                    <div class="px-5 py-4 border-b border-border">

                        <h2 class="font-semibold text-foreground">
                            Intervenant
                        </h2>

                    </div>


                    <div class="p-5">

                        <div class="flex items-center gap-4">

                            @if($event->speaker_image)

                                <img
                                    src="{{ asset(
                                        'storage/' .
                                        $event->speaker_image
                                    ) }}"
                                    alt="{{ $event->speaker }}"
                                    class="w-16 h-16 rounded-full object-cover border border-border"
                                >

                            @else

                                <div
                                    class="w-16 h-16 rounded-full bg-accent/10 flex items-center justify-center"
                                >

                                    <x-icon
                                        name="user"
                                        class="w-6 h-6 text-accent"
                                    />

                                </div>

                            @endif


                            <div class="min-w-0">

                                @if($event->speaker)

                                    <p class="font-semibold text-foreground">
                                        {{ $event->speaker }}
                                    </p>

                                @endif


                                @if($event->speaker_title)

                                    <p class="text-sm text-muted-foreground mt-1">
                                        {{ $event->speaker_title }}
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                </section>

            @endif



            {{-- ========================================================
                 TARIFICATION
            ======================================================== --}}

            <section
                class="bg-card border border-border rounded-xl shadow-sm overflow-hidden"
            >

                <div class="px-5 py-4 border-b border-border">

                    <h2 class="font-semibold text-foreground">
                        Tarification
                    </h2>

                </div>


                <div class="p-5">

                    @if($event->is_free)

                        <div
                            class="flex items-center gap-3 p-4 rounded-xl bg-green-500/10"
                        >

                            <div
                                class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center"
                            >

                                <x-icon
                                    name="circle-check"
                                    class="w-5 h-5 text-green-600"
                                />

                            </div>


                            <div>

                                <p class="font-semibold text-green-600">
                                    Événement gratuit
                                </p>

                                <p class="text-xs text-muted-foreground mt-0.5">
                                    Aucun paiement requis
                                </p>

                            </div>

                        </div>

                    @else

                        <p class="text-sm text-muted-foreground">
                            Prix par place
                        </p>

                        <p class="text-3xl font-bold text-foreground mt-1">

                            {{ number_format(
                                $event->price,
                                0,
                                ',',
                                ' '
                            ) }}

                            <span class="text-base font-medium text-muted-foreground">
                                {{ $event->currency }}
                            </span>

                        </p>

                    @endif

                </div>

            </section>



            {{-- ========================================================
                 PARAMÈTRES DE RÉSERVATION
            ======================================================== --}}

            <section
                class="bg-card border border-border rounded-xl shadow-sm overflow-hidden"
            >

                <div class="px-5 py-4 border-b border-border">

                    <h2 class="font-semibold text-foreground">
                        Paramètres de réservation
                    </h2>

                </div>


                <div class="p-5 space-y-4">

                    <div class="flex items-center justify-between gap-4">

                        <span class="text-sm text-muted-foreground">
                            Réservations
                        </span>

                        @if($event->reservation_enabled)

                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-full bg-green-500/10 text-green-600"
                            >
                                Activées
                            </span>

                        @else

                            <span
                                class="text-xs font-medium px-2.5 py-1 rounded-full bg-secondary text-muted-foreground"
                            >
                                Désactivées
                            </span>

                        @endif

                    </div>


                    <div class="flex items-center justify-between gap-4">

                        <span class="text-sm text-muted-foreground">
                            Capacité
                        </span>

                        <span class="text-sm font-medium text-foreground">
                            {{ $event->capacity
                                ? number_format($event->capacity)
                                : 'Illimitée'
                            }}
                        </span>

                    </div>


                    @if($event->reservation_starts_at)

                        <div class="flex items-center justify-between gap-4">

                            <span class="text-sm text-muted-foreground">
                                Ouverture
                            </span>

                            <span class="text-sm font-medium text-foreground text-end">
                                {{ $event->reservation_starts_at->format(
                                    'd/m/Y H:i'
                                ) }}
                            </span>

                        </div>

                    @endif


                    @if($event->reservation_ends_at)

                        <div class="flex items-center justify-between gap-4">

                            <span class="text-sm text-muted-foreground">
                                Fermeture
                            </span>

                            <span class="text-sm font-medium text-foreground text-end">
                                {{ $event->reservation_ends_at->format(
                                    'd/m/Y H:i'
                                ) }}
                            </span>

                        </div>

                    @endif

                </div>

            </section>

        </div>

    </div>

</div>

@endsection
