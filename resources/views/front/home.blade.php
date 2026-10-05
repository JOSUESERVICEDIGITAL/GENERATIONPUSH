@extends('layouts.front')

@section('title', $settings->meta_title ?? 'Generation PUSH')

@section(
    'meta_description',
    $settings->meta_description
    ?? 'Generation PUSH - La communauté des leaders africains.'
)

@section('content')


    {{-- ================================================================
    01. HERO
    ================================================================ --}}
    <section id="home" class="gp-home-hero">

        {{-- MEDIA --}}
        <div class="gp-home-hero-media">

            @if($settings->heroDirectVideoUrl())

                <video autoplay muted loop playsinline preload="metadata" @if($settings->heroPosterUrl())
                poster="{{ $settings->heroPosterUrl() }}" @endif>
                    <source src="{{ $settings->heroDirectVideoUrl() }}" type="video/mp4">
                </video>

            @elseif($settings->heroEmbedUrl())

                <iframe src="{{ $settings->heroEmbedUrl() }}" class="gp-home-hero-iframe" title="Generation PUSH"
                    frameborder="0" allow="autoplay; fullscreen"></iframe>

            @elseif($settings->heroPosterUrl())

                <img src="{{ $settings->heroPosterUrl() }}" alt="Generation PUSH" fetchpriority="high">

            @else

                <div class="gp-home-hero-fallback"></div>

            @endif

        </div>


        {{-- OVERLAYS --}}
        <div class="gp-home-hero-overlay"></div>

        <div class="gp-home-hero-glow gp-home-hero-glow-one"></div>
        <div class="gp-home-hero-glow gp-home-hero-glow-two"></div>


        {{-- CONTENT --}}
        <div class="container position-relative">

            <div class="gp-home-hero-content">


                {{-- TOP INFO --}}
                {{-- TOP INFO --}}
@php
    $ongoingEventsCount = $ongoingEvents->count();
@endphp

<div class="gp-home-hero-info" data-aos="fade-down">

    <span class="gp-home-greeting">

        <i class="bi bi-sun"></i>

        <span id="gpGreeting">
            Bienvenue
        </span>

    </span>


    <span class="gp-home-info-separator"></span>


    @if($ongoingEventsCount > 0)

        <a
            href="{{ $ongoingEventsCount === 1
                ? route('events.show', $ongoingEvents->first())
                : route('events.index') }}"
            class="gp-home-live-events"
        >

            <i class="bi bi-broadcast-pin"></i>

            <span>
                <strong>{{ $ongoingEventsCount }}</strong>
                événement{{ $ongoingEventsCount > 1 ? 's' : '' }}
                en cours
            </span>

        </a>

    @else

        <a
            href="{{ route('events.index') }}"
            class="gp-home-live-events"
        >

            <i class="bi bi-calendar-event"></i>

            <span>
                Découvrir nos événements
            </span>

        </a>

    @endif

</div>

                {{-- MACHINE À ÉCRIRE --}}
                <h1 class="gp-home-machine-title" data-aos="fade-up" data-aos-delay="100">

                    <span class="gp-home-machine-prefix">
                        WE ARE HERE TO
                    </span>


                    <span class="gp-home-machine-dynamic">

                        <span data-gp-typing data-gp-text="PUSH YOU"></span>

                        <span class="gp-typing-cursor"></span>

                    </span>

                </h1>



                {{-- DESCRIPTION --}}
                <p class="gp-home-hero-description" data-aos="fade-up" data-aos-delay="200">

                    {{ $settings->hero_description
        ?? 'Formations, conférences et un réseau de mentors pour révéler le leader qui est en toi.' }}

                </p>



                {{-- CTA --}}
                <div class="gp-home-hero-actions" data-aos="fade-up" data-aos-delay="300">

                    <a href="{{ $settings->hero_cta_url ?? route('register') }}" class="gp-home-main-button">

                        {{ $settings->hero_cta_label ?? 'Rejoindre la communauté' }}

                        <i class="bi bi-arrow-up-right"></i>

                    </a>


                    <a href="#impact" class="gp-home-discover-link">

                        Découvrir

                        <i class="bi bi-arrow-down"></i>

                    </a>

                </div>


            </div>

        </div>


        {{-- SCROLL INDICATOR --}}
        <a href="#impact" class="gp-home-scroll-indicator" aria-label="Découvrir la suite">

            <span>SCROLL</span>

            <span class="gp-home-scroll-line"></span>

        </a>

    </section>



    {{-- ================================================================
    02. IMPACT / STATS
    ================================================================ --}}
    <section id="impact" class="gp-home-impact">

        <div class="container">

            <div class="row g-0">

                <div class="col-6 col-lg-3">

                    <div class="gp-home-stat" data-aos="fade-up">

                        <strong data-gp-counter="{{ $stats['members'] ?? 0 }}" data-gp-suffix="+">
                            0
                        </strong>

                        <span>
                            Membres actifs
                        </span>

                    </div>

                </div>


                <div class="col-6 col-lg-3">

                    <div class="gp-home-stat" data-aos="fade-up" data-aos-delay="100">

                        <strong data-gp-counter="{{ $stats['formations'] ?? 0 }}" data-gp-suffix="+">
                            0
                        </strong>

                        <span>
                            Formations
                        </span>

                    </div>

                </div>


                <div class="col-6 col-lg-3">

                    <div class="gp-home-stat" data-aos="fade-up" data-aos-delay="200">

                        <strong data-gp-counter="{{ $stats['events'] ?? 0 }}" data-gp-suffix="+">
                            0
                        </strong>

                        <span>
                            Événements
                        </span>

                    </div>

                </div>


                <div class="col-6 col-lg-3">

                    <div class="gp-home-stat" data-aos="fade-up" data-aos-delay="300">

                        <strong data-gp-counter="{{ $stats['satisfaction'] ?? 0 }}" data-gp-suffix="%">
                            0
                        </strong>

                        <span>
                            Satisfaction
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>



    {{-- ================================================================
    03. À PROPOS
    ================================================================ --}}
    <section class="gp-home-about">

        <div class="container">

            <div class="row align-items-center g-5">


                {{-- MEDIA --}}
                <div class="col-lg-6" data-aos="fade-right">

                    <div class="gp-home-about-media">

                        @if($settings->aboutImageUrl())

                            <img src="{{ $settings->aboutImageUrl() }}" alt="À propos de Generation PUSH" loading="lazy">

                        @else

                            <div class="gp-home-about-placeholder">

                                <span>GP</span>

                            </div>

                        @endif


                        <span class="gp-home-about-index">
                            01
                        </span>

                    </div>

                </div>



                {{-- CONTENT --}}
                <div class="col-lg-6" data-aos="fade-left">

                    <span class="gp-home-small-label">
                        À PROPOS DE NOUS
                    </span>


                    <h2 class="gp-home-large-title">

                        {{ $settings->about_title
        ?? 'Une communauté panafricaine de leadership' }}

                    </h2>


                    <div class="gp-home-about-text">

                        {!! nl2br(e(
        $settings->about_text
        ?? 'Generation PUSH accompagne une nouvelle génération de jeunes à travers des formations, des conférences, des rencontres et un réseau engagé.'
    )) !!}

                    </div>


                    <a href="{{ url('/a-propos') }}" class="gp-home-arrow-link">

                        En savoir plus

                        <i class="bi bi-arrow-right"></i>

                    </a>

                </div>

            </div>

        </div>

    </section>



    {{-- ================================================================
    SIGNAL 01 — PROGRAMMES
    ================================================================ --}}
    <section class="gp-home-section-signal">

        <div class="gp-home-signal-content" data-aos="fade-up">

            <span class="gp-home-signal-index">
                02 / 07
            </span>

            <span class="gp-home-signal-title">
                PROGRAMMES
            </span>

            <span class="gp-home-signal-line"></span>

            <i class="bi bi-arrow-down"></i>

        </div>

    </section>



    {{-- ================================================================
    04. AGENDA — DÉBORA + ÉVÉNEMENTS DYNAMIQUES
    ================================================================ --}}
{{-- ================================================================
    04. DÉBORA — PROCHAINS ÉVÉNEMENTS
================================================================ --}}

<section class="gp-agenda" id="events">

    {{-- NAVIGATION LATÉRALE --}}

    @if(isset($events) && $events->count() > 1)

        <button
            type="button"
            class="gp-agenda-screen-arrow gp-agenda-prev"
            aria-label="Événement précédent"
        >
            <i class="bi bi-chevron-left"></i>
        </button>

        <button
            type="button"
            class="gp-agenda-screen-arrow gp-agenda-next"
            aria-label="Événement suivant"
        >
            <i class="bi bi-chevron-right"></i>
        </button>

    @endif


    {{-- DÉCORATION --}}

    <div class="gp-agenda-decoration gp-agenda-decoration-one"></div>
    <div class="gp-agenda-decoration gp-agenda-decoration-two"></div>


    <div class="container">

        {{-- ========================================================
            EN-TÊTE
        ======================================================== --}}

        <div class="gp-agenda-heading">

            <div data-aos="fade-up">

                <span class="gp-agenda-eyebrow">
                    <span></span>
                    GENERATION PUSH
                </span>

                <h2>
                    Les prochains
                    <strong>événements.</strong>
                </h2>

                <p>
                    Conférences, masterclasses, rencontres, ateliers
                    et expériences conçus pour faire avancer une génération.
                </p>

            </div>

        </div>


        {{-- ========================================================
            DÉBORA + ÉVÉNEMENTS
        ======================================================== --}}

        <div class="gp-agenda-stage">


            {{-- ====================================================
                DÉBORA
            ===================================================== --}}

            <div
                class="gp-agenda-presenter"
                data-aos="fade-right"
            >

                <div class="gp-agenda-presenter-image">

                    <img
                        src="{{ asset('front/images/debora-events.png') }}"
                        alt="Generation PUSH"
                        loading="lazy"
                    >

                </div>


                <div class="gp-agenda-presenter-caption">

                    <span class="gp-agenda-dot"></span>

                    <div>
                        <strong>
                            GENERATION PUSH
                        </strong>

                        <small>
                            Expériences & événements
                        </small>
                    </div>

                </div>

            </div>


            {{-- ====================================================
                ÉVÉNEMENTS
            ===================================================== --}}

            <div class="gp-agenda-events">

                @if(isset($events) && $events->isNotEmpty())

                    <div class="swiper gp-agenda-swiper">

                        <div class="swiper-wrapper">

                            @foreach($events as $event)

                                @php

                                    $reservedPlaces =
                                        (int) ($event->confirmed_reserved_places ?? 0);

                                    $remainingPlaces =
                                        $event->capacity !== null
                                            ? max(
                                                0,
                                                $event->capacity - $reservedPlaces
                                            )
                                            : null;

                                    $percentage =
                                        ($event->capacity && $event->capacity > 0)
                                            ? min(
                                                100,
                                                round(
                                                    ($reservedPlaces / $event->capacity) * 100
                                                )
                                            )
                                            : 0;

                                    $isOngoing =
                                        $event->starts_at->lte(now())
                                        && $event->ends_at
                                        && $event->ends_at->gte(now());

                                @endphp


                                <div class="swiper-slide">

                                    <article class="gp-agenda-card">


                                        {{-- =====================================
                                            HAUT
                                        ====================================== --}}

                                        <div class="gp-agenda-card-top">

                                            <span class="gp-agenda-card-type">

                                                @if($event->category?->icon)

                                                    <i class="bi {{ $event->category->icon }}"></i>

                                                @else

                                                    <i class="bi bi-calendar-event"></i>

                                                @endif

                                                {{ strtoupper(
                                                    $event->category?->name
                                                    ?? 'ÉVÉNEMENT'
                                                ) }}

                                            </span>


                                            @if($isOngoing)

                                                <span class="gp-event-live-badge">
                                                    <i></i>
                                                    EN COURS
                                                </span>

                                            @elseif($event->featured)

                                                <span class="gp-event-featured-badge">
                                                    <i class="bi bi-star-fill"></i>
                                                    À LA UNE
                                                </span>

                                            @else

                                                <span class="gp-agenda-card-number">

                                                    {{ str_pad(
                                                        $loop->iteration,
                                                        2,
                                                        '0',
                                                        STR_PAD_LEFT
                                                    ) }}

                                                </span>

                                            @endif

                                        </div>


                                        {{-- =====================================
                                            DATE
                                        ====================================== --}}

                                        <div class="gp-agenda-date">

                                            <strong>
                                                {{ $event->starts_at->format('d') }}
                                            </strong>

                                            <div>

                                                <span>
                                                    {{ strtoupper(
                                                        $event->starts_at
                                                            ->locale('fr')
                                                            ->translatedFormat('M')
                                                    ) }}
                                                </span>

                                                <small>
                                                    {{ $event->starts_at->format('Y') }}
                                                </small>

                                            </div>

                                        </div>


                                        {{-- =====================================
                                            TITRE
                                        ====================================== --}}

                                        <h3 class="gp-agenda-card-title">
                                            {{ $event->title }}
                                        </h3>


                                        @if($event->subtitle)

                                            <p class="gp-event-card-subtitle">
                                                {{ \Illuminate\Support\Str::limit(
                                                    $event->subtitle,
                                                    90
                                                ) }}
                                            </p>

                                        @endif


                                        {{-- =====================================
                                            INFORMATIONS
                                        ====================================== --}}

                                        <div class="gp-agenda-card-information">


                                            {{-- DATE + HEURE --}}

                                            <div class="gp-agenda-info">

                                                <span class="gp-agenda-info-icon">
                                                    <i class="bi bi-calendar3"></i>
                                                </span>

                                                <div>

                                                    <strong>
                                                        {{ ucfirst(
                                                            $event->starts_at
                                                                ->locale('fr')
                                                                ->translatedFormat(
                                                                    'l d F Y'
                                                                )
                                                        ) }}
                                                    </strong>

                                                    <small>

                                                        {{ $event->starts_at->format('H:i') }}

                                                        @if($event->ends_at)

                                                            —
                                                            {{ $event->ends_at->format('H:i') }}

                                                        @endif

                                                    </small>

                                                </div>

                                            </div>


                                            {{-- FORMAT / LIEU --}}

                                            <div class="gp-agenda-info">

                                                <span class="gp-agenda-info-icon">

                                                    @if($event->format === 'online')

                                                        <i class="bi bi-camera-video"></i>

                                                    @elseif($event->format === 'hybrid')

                                                        <i class="bi bi-broadcast"></i>

                                                    @else

                                                        <i class="bi bi-geo-alt"></i>

                                                    @endif

                                                </span>


                                                <div>

                                                    <strong>

                                                        @switch($event->format)

                                                            @case('online')

                                                                En ligne

                                                                @break


                                                            @case('hybrid')

                                                                Hybride

                                                                @break


                                                            @default

                                                                {{ $event->venue
                                                                    ?: $event->city
                                                                    ?: 'Lieu à confirmer' }}

                                                        @endswitch

                                                    </strong>


                                                    <small>

                                                        @if($event->format !== 'online')

                                                            {{ collect([
                                                                $event->city,
                                                                $event->country
                                                            ])->filter()->implode(', ') }}

                                                        @else

                                                            Événement digital

                                                        @endif

                                                    </small>

                                                </div>

                                            </div>


                                            {{-- INTERVENANT --}}

                                            @if($event->speaker)

                                                <div class="gp-agenda-info">

                                                    <span class="gp-agenda-info-icon">
                                                        <i class="bi bi-person"></i>
                                                    </span>

                                                    <div>

                                                        <strong>
                                                            {{ $event->speaker }}
                                                        </strong>

                                                        @if($event->speaker_title)

                                                            <small>
                                                                {{ $event->speaker_title }}
                                                            </small>

                                                        @endif

                                                    </div>

                                                </div>

                                            @endif


                                            {{-- PRIX --}}

                                            <div class="gp-agenda-info">

                                                <span class="gp-agenda-info-icon">

                                                    @if($event->is_free)

                                                        <i class="bi bi-gift"></i>

                                                    @else

                                                        <i class="bi bi-ticket-perforated"></i>

                                                    @endif

                                                </span>

                                                <div>

                                                    <strong>

                                                        @if($event->is_free)

                                                            Gratuit

                                                        @else

                                                            {{ number_format(
                                                                $event->price,
                                                                0,
                                                                ',',
                                                                ' '
                                                            ) }}

                                                            {{ $event->currency }}

                                                        @endif

                                                    </strong>

                                                    <small>
                                                        Participation
                                                    </small>

                                                </div>

                                            </div>

                                        </div>


                                        {{-- =====================================
                                            PLACES
                                        ====================================== --}}

                                        @if($event->capacity)

                                            <div class="gp-home-event-capacity">

                                                <div class="gp-home-event-capacity-top">

                                                    <span>
                                                        <i class="bi bi-people"></i>

                                                        {{ $reservedPlaces }}
                                                        /
                                                        {{ $event->capacity }}
                                                        places réservées
                                                    </span>


                                                    @if($remainingPlaces > 0)

                                                        <strong>
                                                            {{ $remainingPlaces }}
                                                            restante{{ $remainingPlaces > 1 ? 's' : '' }}
                                                        </strong>

                                                    @else

                                                        <strong class="is-full">
                                                            Complet
                                                        </strong>

                                                    @endif

                                                </div>


                                                <div class="gp-home-event-progress">

                                                    <span
                                                        style="width: {{ $percentage }}%"
                                                    ></span>

                                                </div>

                                            </div>

                                        @endif


                                        {{-- =====================================
                                            FOOTER
                                        ====================================== --}}

                                        <div class="gp-agenda-card-footer">

                                            <div class="gp-agenda-mini-brand">

                                                <span>
                                                    GP
                                                </span>

                                                <div>

                                                    <strong>
                                                        GENERATION
                                                    </strong>

                                                    <small>
                                                        PUSH
                                                    </small>

                                                </div>

                                            </div>


                                            <a
                                                href="{{ route(
                                                    'events.show',
                                                    $event
                                                ) }}"
                                                class="gp-agenda-discover"
                                                aria-label="Découvrir {{ $event->title }}"
                                            >

                                                @if($event->can_reserve)

                                                    Réserver

                                                @else

                                                    Découvrir

                                                @endif

                                                <i class="bi bi-arrow-up-right"></i>

                                            </a>

                                        </div>


                                        <span class="gp-agenda-card-ring"></span>
                                        <span class="gp-agenda-card-line"></span>

                                    </article>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    {{-- SLIDER FOOTER --}}

                    <div class="gp-agenda-slider-footer">

                        <div class="gp-agenda-pagination"></div>

                        <div class="gp-agenda-auto">

                            <span class="gp-agenda-auto-icon">
                                <i class="bi bi-play-fill"></i>
                            </span>

                            <span>
                                Défilement automatique
                            </span>

                        </div>

                    </div>


                @else

                    {{-- ====================================================
                        AUCUN ÉVÉNEMENT
                    ===================================================== --}}

                    <div class="gp-agenda-empty">

                        <span class="gp-agenda-empty-icon">
                            <i class="bi bi-calendar2-event"></i>
                        </span>

                        <span class="gp-agenda-eyebrow">
                            ÉVÉNEMENTS
                        </span>

                        <h3>
                            Le prochain PUSH arrive bientôt.
                        </h3>

                        <p>
                            De nouvelles expériences seront
                            prochainement annoncées.
                        </p>

                    </div>

                @endif

            </div>

        </div>


        {{-- ========================================================
            TOUS LES ÉVÉNEMENTS
        ======================================================== --}}

        <div class="gp-agenda-all">

            <a
                href="{{ route('events.index') }}"
                class="gp-home-arrow-link"
            >

                <span>
                    Explorer tous les événements
                </span>

                <i class="bi bi-arrow-right"></i>

            </a>

        </div>

    </div>

</section>



    {{-- ================================================================
    SIGNAL 03 — TÉMOIGNAGES
    ================================================================ --}}
    <section class="gp-home-section-signal">

        <div class="gp-home-signal-content" data-aos="fade-up">

            <span class="gp-home-signal-index">
                04 / 07
            </span>

            <span class="gp-home-signal-title">
                TÉMOIGNAGES
            </span>

            <span class="gp-home-signal-line"></span>

            <i class="bi bi-arrow-down"></i>

        </div>

    </section>



    {{-- ================================================================
    06. TÉMOIGNAGES — SLIDER
    ================================================================ --}}
    <section class="gp-home-testimonials">

        <div class="container">


            <div class="gp-home-list-header">

                <div data-aos="fade-right">

                    <span class="gp-home-small-label">
                        LA COMMUNAUTÉ
                    </span>

                    <h2 class="gp-home-list-title">
                        Ce que dit notre communauté
                    </h2>

                </div>


                <div class="gp-home-slider-controls" data-aos="fade-left">

                    <button type="button" class="gp-slider-button gp-testimonials-prev" aria-label="Témoignage précédent">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <button type="button" class="gp-slider-button gp-testimonials-next" aria-label="Témoignage suivant">
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </div>

            </div>



            @if(isset($testimonials) && $testimonials->isNotEmpty())

                <div class="swiper gp-testimonials-swiper" data-aos="fade-up">

                    <div class="swiper-wrapper">

                        @foreach($testimonials as $testimonial)

                            <div class="swiper-slide">

                                <article class="gp-home-testimonial-card">

                                    <i class="bi bi-quote gp-home-quote"></i>


                                    <p>
                                        {{ $testimonial->content }}
                                    </p>


                                    <div class="gp-home-testimonial-author">

                                        @if($testimonial->photoUrl())

                                            <img src="{{ $testimonial->photoUrl() }}" alt="{{ $testimonial->author_name }}"
                                                loading="lazy">

                                        @else

                                                            <div class="gp-home-testimonial-avatar">

                                                                {{ strtoupper(
                                                mb_substr(
                                                    $testimonial->author_name,
                                                    0,
                                                    1
                                                )
                                            ) }}

                                                            </div>

                                        @endif


                                        <div>

                                            <strong>
                                                {{ $testimonial->author_name }}
                                            </strong>

                                            <span>
                                                {{ $testimonial->author_role }}
                                            </span>

                                        </div>

                                    </div>

                                </article>

                            </div>

                        @endforeach

                    </div>


                    <div class="gp-testimonials-pagination"></div>

                </div>

            @endif

        </div>

    </section>



    {{-- ================================================================
    SIGNAL 04 — SPONSORS
    ================================================================ --}}
    <section class="gp-home-section-signal gp-home-signal-small">

        <div class="gp-home-signal-content" data-aos="fade-up">

            <span class="gp-home-signal-index">
                05 / 07
            </span>

            <span class="gp-home-signal-title">
                SPONSORS
            </span>

            <span class="gp-home-signal-line"></span>

        </div>

    </section>



    {{-- ================================================================
    07. SPONSORS — DÉFILEMENT CONTINU
    ================================================================ --}}
    @if(isset($sponsors) && $sponsors->isNotEmpty())

        <section class="gp-home-sponsors">

            <div class="container">

                <h2 class="gp-home-sponsors-title" data-aos="fade-up">
                    Ils nous soutiennent
                </h2>

            </div>


            <div class="swiper gp-sponsors-swiper" data-aos="fade-up">

                <div class="swiper-wrapper">

                    @foreach($sponsors as $sponsor)

                        <div class="swiper-slide">

                            <div class="gp-home-sponsor" data-bs-toggle="tooltip" data-bs-title="{{ $sponsor->name }}">

                                @if($sponsor->logoUrl())

                                    <img src="{{ $sponsor->logoUrl() }}" alt="{{ $sponsor->name }}" loading="lazy">

                                @else

                                    <strong>
                                        {{ $sponsor->name }}
                                    </strong>

                                @endif

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        </section>

    @endif



    {{-- ================================================================
    SIGNAL 05 — BLOG
    ================================================================ --}}
    <section class="gp-home-section-signal">

        <div class="gp-home-signal-content" data-aos="fade-up">

            <span class="gp-home-signal-index">
                06 / 07
            </span>

            <span class="gp-home-signal-title">
                BLOG
            </span>

            <span class="gp-home-signal-line"></span>

            <i class="bi bi-arrow-down"></i>

        </div>

    </section>



    {{-- ================================================================
    08. BLOG — SLIDER MANUEL
    ================================================================ --}}
    <section class="gp-home-blog">

        <div class="container">


            <div class="gp-home-list-header">

                <div data-aos="fade-right">

                    <span class="gp-home-small-label">
                        ACTUALITÉS & IDÉES
                    </span>

                    <h2 class="gp-home-list-title">
                        Derniers articles
                    </h2>

                </div>


                <div class="gp-home-slider-controls" data-aos="fade-left">

                    <button type="button" class="gp-slider-button gp-blog-prev" aria-label="Article précédent">
                        <i class="bi bi-arrow-left"></i>
                    </button>

                    <button type="button" class="gp-slider-button gp-blog-next" aria-label="Article suivant">
                        <i class="bi bi-arrow-right"></i>
                    </button>

                </div>

            </div>



            @if(isset($posts) && $posts->isNotEmpty())

                <div class="swiper gp-blog-swiper" data-aos="fade-up">

                    <div class="swiper-wrapper">

                        @foreach($posts as $post)

                            <div class="swiper-slide">

                                <article class="gp-home-blog-card">


                                    <a href="{{ route('front.blog.show', $post) }}" class="gp-home-blog-image">

                                        @if($post->coverImageUrl())

                                            <img src="{{ $post->coverImageUrl() }}" alt="{{ $post->title }}" loading="lazy">

                                        @else

                                            <div class="gp-home-blog-placeholder">

                                                <i class="bi bi-file-earmark-text"></i>

                                            </div>

                                        @endif


                                        <span class="gp-home-blog-arrow">

                                            <i class="bi bi-arrow-up-right"></i>

                                        </span>

                                    </a>


                                    <div class="gp-home-blog-content">

                                        @if($post->category)

                                                        <span class="gp-home-blog-category">

                                                            {{ is_object($post->category)
                                            ? ($post->category->name ?? '')
                                            : $post->category }}

                                                        </span>

                                        @endif


                                        <h3>

                                            <a href="{{ route('front.blog.show', $post) }}">
                                                {{ $post->title }}
                                            </a>

                                        </h3>

                                    </div>

                                </article>

                            </div>

                        @endforeach

                    </div>


                    <div class="gp-blog-pagination"></div>

                </div>

            @endif

        </div>

    </section>



    {{-- ================================================================
    SIGNAL 06 — S'IMPLIQUER
    ================================================================ --}}
    <section class="gp-home-section-signal">

        <div class="gp-home-signal-content" data-aos="fade-up">

            <span class="gp-home-signal-index">
                07 / 07
            </span>

            <span class="gp-home-signal-title">
                S'IMPLIQUER
            </span>

            <span class="gp-home-signal-line"></span>

            <i class="bi bi-arrow-down"></i>

        </div>

    </section>



    {{-- ================================================================
    09. PARTENAIRE / BÉNÉVOLE
    ================================================================ --}}
    <section class="gp-home-involvement">

        <div class="container">

            <div class="row g-4">


                {{-- PARTENAIRE --}}
                <div class="col-lg-6" data-aos="fade-right">

                    <article class="gp-home-involvement-card">

                        <span class="gp-home-involvement-number">
                            01
                        </span>


                        <div class="gp-home-involvement-icon">

                            <i class="bi bi-award"></i>

                        </div>


                        <h3>
                            Devenir partenaire
                        </h3>


                        <p>

                            Entreprise, institution ou organisation :
                            associez votre marque à une génération
                            ambitieuse et engagée.

                        </p>


                        <a href="{{ route('front.partner') }}" class="gp-home-arrow-link">

                            Devenir partenaire

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </article>

                </div>



                {{-- BÉNÉVOLE --}}
                <div class="col-lg-6" data-aos="fade-left">

                    <article class="gp-home-involvement-card gp-home-involvement-dark">

                        <span class="gp-home-involvement-number">
                            02
                        </span>


                        <div class="gp-home-involvement-icon">

                            <i class="bi bi-people"></i>

                        </div>


                        <h3>
                            Devenir bénévole
                        </h3>


                        <p>

                            Donne de ton temps et de tes compétences
                            pour accompagner la prochaine génération
                            de leaders.

                        </p>


                        <a href="{{ route('front.volunteer') }}" class="gp-home-arrow-link">

                            Rejoindre l'équipe

                            <i class="bi bi-arrow-right"></i>

                        </a>

                    </article>

                </div>

            </div>

        </div>

    </section>



    {{-- ================================================================
    10. NEWSLETTER
    ================================================================ --}}
    <section class="gp-home-newsletter">

        <div class="container">

            <div class="gp-home-newsletter-inner" data-aos="zoom-out">

                <span class="gp-home-small-label">
                    RESTE CONNECTÉ
                </span>


                <h2>
                    Reste informé.
                </h2>


                <p>

                    Reçois nos actualités, formations et événements
                    directement par email.

                </p>


                <form method="POST" action="{{ route('front.newsletter.store') }}" class="gp-home-newsletter-form"
                    data-gp-form>

                    @csrf


                    <div class="gp-home-newsletter-input">

                        <i class="bi bi-envelope"></i>


                        <input type="email" name="email" value="{{ old('email') }}" placeholder="ton@email.com" required>


                        <button type="submit" data-loading-text="Inscription...">

                            S'abonner

                            <i class="bi bi-arrow-right"></i>

                        </button>

                    </div>

                </form>


                @error('email')

                    <div class="text-danger small mt-3">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>

    </section>


@endsection
