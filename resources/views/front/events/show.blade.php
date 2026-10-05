@extends('layouts.front')

@section('title', $event->title . ' — Generation PUSH')

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('front/css/event-show.css') }}"
    >
@endpush


@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | DONNÉES DE L'ÉVÉNEMENT
    |--------------------------------------------------------------------------
    */

    $reserved = (int) ($event->confirmed_reserved_places ?? 0);

    $remaining = $event->capacity !== null
        ? max(0, $event->capacity - $reserved)
        : null;

    $percentage = ($event->capacity && $event->capacity > 0)
        ? min(100, round(($reserved / $event->capacity) * 100))
        : 0;

    $isOngoing =
        $event->starts_at->lte(now())
        && $event->ends_at
        && $event->ends_at->gte(now());

    $isPast =
        ($event->ends_at && $event->ends_at->lt(now()))
        || (!$event->ends_at && $event->starts_at->lt(now()));

    $mainImage = $event->banner
        ?: $event->image;

    $reservationAvailable =
        $event->can_reserve
        && !$event->is_full;
@endphp


{{-- ================================================================
    HERO
================================================================ --}}

<section class="gp-event-detail-hero">

    <div class="gp-event-detail-background">

        @if($mainImage)

            <img
                src="{{ asset('storage/' . $mainImage) }}"
                alt="{{ $event->title }}"
            >

        @endif

        <div class="gp-event-detail-overlay"></div>

    </div>


    <div class="container position-relative">

        <div class="gp-event-breadcrumb">

            <a href="{{ route('events.index') }}">
                Événements
            </a>

            <i class="bi bi-chevron-right"></i>

            <span>
                {{ $event->title }}
            </span>

        </div>


        <div class="row">

            <div class="col-xl-9">

                <div class="gp-event-detail-badges">

                    @if($event->category)

                        <span class="gp-event-category-badge">
                            {{ $event->category->name }}
                        </span>

                    @endif


                    @if($isOngoing)

                        <span class="gp-event-status-live">
                            <i></i>
                            En cours
                        </span>

                    @elseif($isPast)

                        <span class="gp-event-status-past">
                            Terminé
                        </span>

                    @elseif($event->featured)

                        <span class="gp-event-featured">
                            <i class="bi bi-star-fill"></i>
                            À la une
                        </span>

                    @endif

                </div>


                <h1 class="gp-event-detail-title">
                    {{ $event->title }}
                </h1>


                @if($event->subtitle)

                    <p class="gp-event-detail-subtitle">
                        {{ $event->subtitle }}
                    </p>

                @endif


                <div class="gp-event-hero-meta">

                    <div>

                        <span class="gp-event-meta-icon">
                            <i class="bi bi-calendar3"></i>
                        </span>

                        <span>
                            <small>Date</small>

                            <strong>
                                {{ $event->starts_at->translatedFormat('d F Y') }}
                            </strong>
                        </span>

                    </div>


                    <div>

                        <span class="gp-event-meta-icon">
                            <i class="bi bi-clock"></i>
                        </span>

                        <span>
                            <small>Heure</small>

                            <strong>
                                {{ $event->starts_at->format('H:i') }}

                                @if($event->ends_at)
                                    —
                                    {{ $event->ends_at->format('H:i') }}
                                @endif
                            </strong>
                        </span>

                    </div>


                    <div>

                        <span class="gp-event-meta-icon">
                            @if($event->format === 'online')
                                <i class="bi bi-camera-video"></i>
                            @else
                                <i class="bi bi-geo-alt"></i>
                            @endif
                        </span>

                        <span>
                            <small>
                                {{ $event->format === 'online' ? 'Format' : 'Lieu' }}
                            </small>

                            <strong>

                                @if($event->format === 'online')

                                    En ligne

                                @else

                                    {{ collect([
                                        $event->venue,
                                        $event->city,
                                        $event->country
                                    ])->filter()->implode(', ') }}

                                @endif

                            </strong>
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- ================================================================
    MAIN
================================================================ --}}

<section class="gp-event-detail-section">

    <div class="container">

        <div class="row g-5">


            {{-- ====================================================
                COLONNE CONTENU
            ==================================================== --}}

            <div class="col-lg-7 col-xl-8">


                {{-- IMAGE PRINCIPALE --}}

                @if($event->image)

                    <div
                        class="gp-event-feature-image"
                        data-aos="fade-up"
                    >

                        <img
                            src="{{ asset('storage/' . $event->image) }}"
                            alt="{{ $event->title }}"
                        >

                    </div>

                @endif



                {{-- INTRODUCTION --}}

                @if($event->short_description)

                    <div
                        class="gp-event-introduction"
                        data-aos="fade-up"
                    >
                        {{ $event->short_description }}
                    </div>

                @endif



                {{-- DESCRIPTION --}}

                @if($event->description)

                    <div
                        class="gp-event-content-block"
                        data-aos="fade-up"
                    >

                        <span class="gp-detail-kicker">
                            À propos
                        </span>

                        <h2>
                            À propos de cet événement
                        </h2>

                        <div class="gp-event-description-content">
                            {!! nl2br(e($event->description)) !!}
                        </div>

                    </div>

                @endif



                {{-- INFORMATIONS --}}

                <div
                    class="gp-event-content-block"
                    data-aos="fade-up"
                >

                    <span class="gp-detail-kicker">
                        Informations pratiques
                    </span>

                    <h2>
                        Tout ce qu'il faut savoir
                    </h2>


                    <div class="gp-event-info-grid">

                        {{-- DATE --}}

                        <div class="gp-event-info-card">

                            <span>
                                <i class="bi bi-calendar3"></i>
                            </span>

                            <div>
                                <small>Date</small>

                                <strong>
                                    {{ $event->starts_at
                                        ->translatedFormat('d F Y') }}
                                </strong>
                            </div>

                        </div>


                        {{-- HEURE --}}

                        <div class="gp-event-info-card">

                            <span>
                                <i class="bi bi-clock"></i>
                            </span>

                            <div>
                                <small>Horaire</small>

                                <strong>
                                    {{ $event->starts_at->format('H:i') }}

                                    @if($event->ends_at)

                                        —
                                        {{ $event->ends_at->format('H:i') }}

                                    @endif
                                </strong>
                            </div>

                        </div>


                        {{-- FORMAT --}}

                        <div class="gp-event-info-card">

                            <span>
                                <i class="bi bi-broadcast"></i>
                            </span>

                            <div>
                                <small>Format</small>

                                <strong>
                                    @switch($event->format)

                                        @case('online')
                                            En ligne
                                            @break

                                        @case('hybrid')
                                            Hybride
                                            @break

                                        @default
                                            Présentiel

                                    @endswitch
                                </strong>
                            </div>

                        </div>


                        {{-- CAPACITÉ --}}

                        @if($event->capacity)

                            <div class="gp-event-info-card">

                                <span>
                                    <i class="bi bi-people"></i>
                                </span>

                                <div>
                                    <small>Capacité</small>

                                    <strong>
                                        {{ $event->capacity }}
                                        places
                                    </strong>
                                </div>

                            </div>

                        @endif


                        {{-- LIEU --}}

                        @if($event->format !== 'online')

                            <div class="gp-event-info-card gp-event-info-wide">

                                <span>
                                    <i class="bi bi-geo-alt"></i>
                                </span>

                                <div>
                                    <small>Lieu</small>

                                    <strong>
                                        {{ collect([
                                            $event->venue,
                                            $event->address,
                                            $event->city,
                                            $event->country
                                        ])->filter()->implode(', ') }}
                                    </strong>
                                </div>

                            </div>

                        @endif

                    </div>

                </div>



                {{-- ====================================================
                    INTERVENANT
                ==================================================== --}}

                @if($event->speaker)

                    <div
                        class="gp-event-content-block"
                        data-aos="fade-up"
                    >

                        <span class="gp-detail-kicker">
                            Intervenant
                        </span>

                        <h2>
                            Rencontrez votre intervenant
                        </h2>


                        <div class="gp-speaker-card">

                            <div class="gp-speaker-photo">

                                @if($event->speaker_image)

                                    <img
                                        src="{{ asset(
                                            'storage/' .
                                            $event->speaker_image
                                        ) }}"
                                        alt="{{ $event->speaker }}"
                                    >

                                @else

                                    <div class="gp-speaker-placeholder">
                                        <i class="bi bi-person"></i>
                                    </div>

                                @endif

                            </div>


                            <div class="gp-speaker-info">

                                <span>
                                    Intervenant
                                </span>

                                <h3>
                                    {{ $event->speaker }}
                                </h3>


                                @if($event->speaker_title)

                                    <p>
                                        {{ $event->speaker_title }}
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif


            </div>



            {{-- ====================================================
                COLONNE RÉSERVATION
            ==================================================== --}}

            <div class="col-lg-5 col-xl-4">

                <div
                    id="reservation"
                    class="gp-reservation-wrapper"
                >

                    <div class="gp-reservation-card">


                        {{-- PRIX --}}

                        <div class="gp-reservation-price">

                            <span>
                                Participation
                            </span>

                            @if($event->is_free)

                                <strong>
                                    Gratuit
                                </strong>

                                <small>
                                    Aucun paiement requis
                                </small>

                            @else

                                <strong>
                                    {{ number_format(
                                        $event->price,
                                        0,
                                        ',',
                                        ' '
                                    ) }}

                                    {{ $event->currency }}
                                </strong>

                                <small>
                                    par place
                                </small>

                            @endif

                        </div>



                        {{-- PLACES --}}

                        @if($event->capacity)

                            <div class="gp-reservation-capacity">

                                <div>

                                    <span>
                                        Places réservées
                                    </span>

                                    <strong>
                                        {{ $reserved }}
                                        /
                                        {{ $event->capacity }}
                                    </strong>

                                </div>


                                <div class="gp-reservation-progress">

                                    <span
                                        style="width:
                                        {{ $percentage }}%"
                                    ></span>

                                </div>


                                @if($remaining > 0)

                                    <p>
                                        <i class="bi bi-check-circle-fill"></i>

                                        Il reste

                                        <strong>
                                            {{ $remaining }}
                                            place{{ $remaining > 1 ? 's' : '' }}
                                        </strong>
                                    </p>

                                @else

                                    <p class="gp-no-place">
                                        <i class="bi bi-x-circle-fill"></i>

                                        Événement complet
                                    </p>

                                @endif

                            </div>

                        @endif



                        {{-- ====================================================
                            FORMULAIRE
                        ==================================================== --}}

                        @if($reservationAvailable)

                            <div class="gp-reservation-heading">

                                <span>
                                    Réservation
                                </span>

                                <h2>
                                    Réservez votre place
                                </h2>

                                <p>
                                    Remplissez vos informations
                                    pour participer à cet événement.
                                </p>

                            </div>


                            @if($errors->any())

                                <div class="gp-form-errors">

                                    <div>
                                        <i class="bi bi-exclamation-circle"></i>

                                        <strong>
                                            Vérifiez les informations saisies.
                                        </strong>
                                    </div>

                                    <ul>
                                        @foreach($errors->all() as $error)

                                            <li>
                                                {{ $error }}
                                            </li>

                                        @endforeach
                                    </ul>

                                </div>

                            @endif


                            <form
                                method="POST"
                                action="{{ route(
                                    'events.reservations.store',
                                    $event
                                ) }}"
                                class="gp-reservation-form"
                                id="eventReservationForm"
                            >

                                @csrf


                                {{-- PRÉNOM --}}

                                <div class="gp-form-group">

                                    <label for="first_name">
                                        Prénom
                                        <span>*</span>
                                    </label>

                                    <div class="gp-input-wrapper">

                                        <i class="bi bi-person"></i>

                                        <input
                                            type="text"
                                            id="first_name"
                                            name="first_name"
                                            value="{{ old(
                                                'first_name',
                                                auth()->user()?->first_name
                                            ) }}"
                                            placeholder="Votre prénom"
                                            required
                                            autocomplete="given-name"
                                        >

                                    </div>

                                    @error('first_name')
                                        <small class="gp-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror

                                </div>



                                {{-- NOM --}}

                                <div class="gp-form-group">

                                    <label for="last_name">
                                        Nom
                                        <span>*</span>
                                    </label>

                                    <div class="gp-input-wrapper">

                                        <i class="bi bi-person"></i>

                                        <input
                                            type="text"
                                            id="last_name"
                                            name="last_name"
                                            value="{{ old(
                                                'last_name',
                                                auth()->user()?->last_name
                                            ) }}"
                                            placeholder="Votre nom"
                                            required
                                            autocomplete="family-name"
                                        >

                                    </div>

                                    @error('last_name')
                                        <small class="gp-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror

                                </div>



                                {{-- EMAIL --}}

                                <div class="gp-form-group">

                                    <label for="email">
                                        Adresse e-mail
                                        <span>*</span>
                                    </label>

                                    <div class="gp-input-wrapper">

                                        <i class="bi bi-envelope"></i>

                                        <input
                                            type="email"
                                            id="email"
                                            name="email"
                                            value="{{ old(
                                                'email',
                                                auth()->user()?->email
                                            ) }}"
                                            placeholder="vous@exemple.com"
                                            required
                                            autocomplete="email"
                                        >

                                    </div>

                                    @error('email')
                                        <small class="gp-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror

                                </div>



                                {{-- TÉLÉPHONE --}}

                                <div class="gp-form-group">

                                    <label for="phone">
                                        Téléphone
                                    </label>

                                    <div class="gp-input-wrapper">

                                        <i class="bi bi-telephone"></i>

                                        <input
                                            type="tel"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            placeholder="+226 ..."
                                            autocomplete="tel"
                                        >

                                    </div>

                                    @error('phone')
                                        <small class="gp-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror

                                </div>



                                {{-- QUANTITÉ --}}

                                <div class="gp-form-group">

                                    <label>
                                        Nombre de places
                                        <span>*</span>
                                    </label>

                                    <div class="gp-quantity-selector">

                                        <button
                                            type="button"
                                            class="gp-quantity-button"
                                            data-action="minus"
                                            aria-label="Retirer une place"
                                        >
                                            <i class="bi bi-dash"></i>
                                        </button>


                                        <input
                                            type="number"
                                            id="quantity"
                                            name="quantity"
                                            value="{{ old('quantity', 1) }}"
                                            min="1"
                                            max="{{ $remaining !== null
                                                ? min(10, $remaining)
                                                : 10 }}"
                                            readonly
                                        >


                                        <button
                                            type="button"
                                            class="gp-quantity-button"
                                            data-action="plus"
                                            aria-label="Ajouter une place"
                                        >
                                            <i class="bi bi-plus"></i>
                                        </button>

                                    </div>

                                    @error('quantity')
                                        <small class="gp-field-error">
                                            {{ $message }}
                                        </small>
                                    @enderror

                                </div>



                                {{-- TOTAL --}}

                                @if(!$event->is_free)

                                    <div
                                        class="gp-reservation-total"
                                        data-price="{{ (float) $event->price }}"
                                        data-currency="{{ $event->currency }}"
                                    >

                                        <span>
                                            Total
                                        </span>

                                        <strong id="reservationTotal">
                                            {{ number_format(
                                                $event->price,
                                                0,
                                                ',',
                                                ' '
                                            ) }}
                                            {{ $event->currency }}
                                        </strong>

                                    </div>

                                @endif



                                {{-- SUBMIT --}}

                                <button
                                    type="submit"
                                    class="gp-reservation-submit"
                                    id="reservationSubmit"
                                >

                                    <span class="gp-submit-default">

                                        @if($event->is_free)

                                            Confirmer ma réservation

                                        @else

                                            Réserver et continuer au paiement

                                        @endif

                                        <i class="bi bi-arrow-right"></i>

                                    </span>


                                    <span class="gp-submit-loading d-none">

                                        <span
                                            class="spinner-border spinner-border-sm"
                                            aria-hidden="true"
                                        ></span>

                                        Traitement...

                                    </span>

                                </button>


                                <p class="gp-reservation-security">

                                    <i class="bi bi-shield-check"></i>

                                    Vos informations sont utilisées
                                    uniquement pour gérer votre réservation.

                                </p>

                            </form>


                        @elseif($event->is_full)

                            <div class="gp-reservation-unavailable">

                                <div>
                                    <i class="bi bi-people"></i>
                                </div>

                                <h3>
                                    Événement complet
                                </h3>

                                <p>
                                    Toutes les places disponibles
                                    ont déjà été réservées.
                                </p>

                            </div>


                        @elseif($isPast)

                            <div class="gp-reservation-unavailable">

                                <div>
                                    <i class="bi bi-calendar-check"></i>
                                </div>

                                <h3>
                                    Événement terminé
                                </h3>

                                <p>
                                    Les réservations pour cet événement
                                    sont maintenant fermées.
                                </p>

                            </div>


                        @else

                            <div class="gp-reservation-unavailable">

                                <div>
                                    <i class="bi bi-lock"></i>
                                </div>

                                <h3>
                                    Réservations indisponibles
                                </h3>

                                <p>
                                    Les réservations ne sont actuellement
                                    pas ouvertes pour cet événement.
                                </p>

                            </div>

                        @endif


                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- ================================================================
    AUTRES ÉVÉNEMENTS
================================================================ --}}

@if($relatedEvents->isNotEmpty())

<section class="gp-related-events">

    <div class="container">

        <div class="row align-items-end mb-5">

            <div class="col-lg-8">

                <span class="gp-detail-kicker">
                    Continuez l'expérience
                </span>

                <h2 class="gp-related-title">
                    D'autres événements à découvrir
                </h2>

            </div>


            <div class="col-lg-4">

                <div class="gp-related-navigation">

                    <button
                        type="button"
                        class="gp-related-prev"
                    >
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <button
                        type="button"
                        class="gp-related-next"
                    >
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </div>

            </div>

        </div>


        <div class="swiper gp-related-swiper">

            <div class="swiper-wrapper">

                @foreach($relatedEvents as $related)

                    <div class="swiper-slide">

                        <article class="gp-related-card">

                            <a
                                href="{{ route(
                                    'events.show',
                                    $related
                                ) }}"
                                class="gp-related-image"
                            >

                                @if($related->image)

                                    <img
                                        src="{{ asset(
                                            'storage/' .
                                            $related->image
                                        ) }}"
                                        alt="{{ $related->title }}"
                                        loading="lazy"
                                    >

                                @elseif($related->banner)

                                    <img
                                        src="{{ asset(
                                            'storage/' .
                                            $related->banner
                                        ) }}"
                                        alt="{{ $related->title }}"
                                        loading="lazy"
                                    >

                                @else

                                    <div class="gp-related-placeholder">
                                        PUSH
                                    </div>

                                @endif


                                @if($related->category)

                                    <span>
                                        {{ $related->category->name }}
                                    </span>

                                @endif

                            </a>


                            <div class="gp-related-content">

                                <small>
                                    {{ $related->starts_at
                                        ->translatedFormat(
                                            'd F Y · H:i'
                                        ) }}
                                </small>


                                <h3>
                                    <a
                                        href="{{ route(
                                            'events.show',
                                            $related
                                        ) }}"
                                    >
                                        {{ $related->title }}
                                    </a>
                                </h3>


                                <a
                                    href="{{ route(
                                        'events.show',
                                        $related
                                    ) }}"
                                    class="gp-related-link"
                                >
                                    Découvrir

                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>

                    </div>

                @endforeach

            </div>

        </div>

    </div>

</section>

@endif

@endsection



@push('scripts')
    <script src="{{ asset('front/js/event-show.js') }}"></script>
@endpush
