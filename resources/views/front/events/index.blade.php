@extends('layouts.front')

@section('title', 'Événements — Generation PUSH')

@section(
    'meta_description',
    'Découvrez les conférences, masterclass, ateliers, rencontres et expériences Generation PUSH.'
)

@push('styles')
    <link
        rel="stylesheet"
        href="{{ asset('front/css/events.css') }}"
    >
@endpush


@section('content')

{{-- ============================================================
     HERO
============================================================ --}}

<section class="gp-events-hero">

    <div class="gp-events-hero-glow gp-events-hero-glow-one"></div>
    <div class="gp-events-hero-glow gp-events-hero-glow-two"></div>

    <div class="container position-relative">

        <div class="row align-items-center min-vh-75">

            <div class="col-lg-8">

                <div
                    class="gp-events-eyebrow"
                    data-aos="fade-up"
                >
                    <span></span>
                    Expériences Generation PUSH
                </div>


                <h1
                    class="gp-events-title"
                    data-aos="fade-up"
                    data-aos-delay="100"
                >
                    Des événements qui vous
                    <strong>PUSH</strong>
                    plus loin.
                </h1>


                <p
                    class="gp-events-lead"
                    data-aos="fade-up"
                    data-aos-delay="200"
                >
                    Conférences, masterclass, coaching,
                    PushConnect, ateliers et rencontres conçus
                    pour apprendre, créer des connexions et
                    passer à l'action.
                </p>


                <div
                    class="d-flex flex-wrap gap-3 mt-4"
                    data-aos="fade-up"
                    data-aos-delay="300"
                >

                    <a
                        href="#events"
                        class="btn gp-btn-orange"
                    >
                        Explorer les événements
                        <i class="bi bi-arrow-down"></i>
                    </a>

                    @if($past->isNotEmpty())
                        <a
                            href="#past-events"
                            class="btn gp-btn-outline-light"
                        >
                            Événements passés
                        </a>
                    @endif

                </div>

            </div>

        </div>

    </div>

</section>


{{-- ============================================================
     ÉVÉNEMENTS À VENIR / EN COURS
============================================================ --}}

<section
    id="events"
    class="gp-events-section"
>

    <div class="container">

        <div class="row align-items-end g-4 mb-5">

            <div class="col-lg-7">

                <span class="gp-section-kicker">
                    Nos expériences
                </span>

                <h2 class="gp-section-title">
                    Les prochains rendez-vous
                </h2>

                <p class="gp-section-description">
                    Découvrez les prochaines expériences
                    Generation PUSH et réservez votre place.
                </p>

            </div>


            @if($upcoming->count() > 1)

                <div class="col-lg-5">

                    <div class="gp-slider-controls">

                        <button
                            class="gp-slider-button gp-events-prev"
                            type="button"
                            aria-label="Événement précédent"
                        >
                            <i class="bi bi-arrow-left"></i>
                        </button>

                        <button
                            class="gp-slider-button gp-events-next"
                            type="button"
                            aria-label="Événement suivant"
                        >
                            <i class="bi bi-arrow-right"></i>
                        </button>

                    </div>

                </div>

            @endif

        </div>


        {{-- CATÉGORIES --}}

        @if($categories->isNotEmpty())

            <div class="gp-event-filters mb-5">

                <button
                    type="button"
                    class="gp-event-filter active"
                    data-filter="all"
                >
                    Tous
                </button>

                @foreach($categories as $category)

                    <button
                        type="button"
                        class="gp-event-filter"
                        data-filter="{{ $category->slug }}"
                    >
                        @if($category->icon)
                            <i class="bi {{ $category->icon }}"></i>
                        @endif

                        {{ $category->name }}
                    </button>

                @endforeach

            </div>

        @endif


        {{-- ÉVÉNEMENTS --}}

        @if($upcoming->isNotEmpty())

            <div class="swiper gp-events-swiper">

                <div class="swiper-wrapper">

                    @foreach($upcoming as $event)

                        @php
                            $reserved = (int) (
                                $event->confirmed_reserved_places ?? 0
                            );

                            $remaining = $event->capacity !== null
                                ? max(0, $event->capacity - $reserved)
                                : null;

                            $fillPercentage =
                                $event->capacity && $event->capacity > 0
                                    ? min(
                                        100,
                                        round(
                                            ($reserved / $event->capacity) * 100
                                        )
                                    )
                                    : 0;

                            $isOngoing =
                                $event->starts_at->lte(now())
                                && $event->ends_at
                                && $event->ends_at->gte(now());
                        @endphp


                        <div
                            class="swiper-slide gp-event-slide"
                            data-category="{{ $event->category?->slug ?? 'other' }}"
                        >

                            <article class="gp-event-card">

                                {{-- IMAGE --}}

                                <a
                                    href="{{ route('events.show', $event) }}"
                                    class="gp-event-media"
                                >

                                    @if($event->image)

                                        <img
                                            src="{{ asset('storage/' . $event->image) }}"
                                            alt="{{ $event->title }}"
                                            loading="lazy"
                                        >

                                    @elseif($event->banner)

                                        <img
                                            src="{{ asset('storage/' . $event->banner) }}"
                                            alt="{{ $event->title }}"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="gp-event-placeholder">
                                            <span>PUSH</span>
                                        </div>

                                    @endif


                                    <div class="gp-event-media-overlay"></div>


                                    @if($event->category)

                                        <span class="gp-event-category">

                                            @if($event->category->icon)
                                                <i class="bi {{ $event->category->icon }}"></i>
                                            @endif

                                            {{ $event->category->name }}

                                        </span>

                                    @endif


                                    @if($isOngoing)

                                        <span class="gp-event-live">
                                            <span></span>
                                            En cours
                                        </span>

                                    @elseif($event->featured)

                                        <span class="gp-event-live">
                                            <i class="bi bi-star-fill"></i>
                                            À la une
                                        </span>

                                    @endif


                                    <div class="gp-event-date">

                                        <strong>
                                            {{ $event->starts_at->format('d') }}
                                        </strong>

                                        <span>
                                            {{ $event->starts_at->translatedFormat('M') }}
                                        </span>

                                    </div>


                                    <div class="gp-event-price">

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

                                    </div>

                                </a>


                                {{-- CONTENU --}}

                                <div class="gp-event-body">

                                    <div class="gp-event-meta">

                                        <span>
                                            <i class="bi bi-clock"></i>

                                            {{ $event->starts_at->format('H:i') }}
                                        </span>


                                        <span>
                                            <i class="bi bi-broadcast"></i>

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
                                        </span>

                                    </div>


                                    <a
                                        href="{{ route('events.show', $event) }}"
                                        class="gp-event-title-link"
                                    >

                                        <h3>
                                            {{ \Illuminate\Support\Str::limit(
                                                $event->title,
                                                65
                                            ) }}
                                        </h3>

                                    </a>


                                    @if($event->subtitle)

                                        <p class="gp-event-description">
                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags($event->subtitle),
                                                100
                                            ) }}
                                        </p>

                                    @elseif($event->short_description)

                                        <p class="gp-event-description">
                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags($event->short_description),
                                                100
                                            ) }}
                                        </p>

                                    @endif


                                    <div class="gp-event-info">

                                        @if($event->format === 'online')

                                            <div>
                                                <i class="bi bi-camera-video"></i>

                                                <span>
                                                    Événement en ligne
                                                </span>
                                            </div>

                                        @else

                                            <div>
                                                <i class="bi bi-geo-alt"></i>

                                                <span>
                                                    {{ \Illuminate\Support\Str::limit(
                                                        collect([
                                                            $event->venue,
                                                            $event->city,
                                                            $event->country
                                                        ])->filter()->implode(', ')
                                                        ?: 'Lieu à confirmer',
                                                        55
                                                    ) }}
                                                </span>
                                            </div>

                                        @endif


                                        @if($event->speaker)

                                            <div>
                                                <i class="bi bi-person"></i>

                                                <span>
                                                    Avec
                                                    <strong>
                                                        {{ \Illuminate\Support\Str::limit(
                                                            $event->speaker,
                                                            35
                                                        ) }}
                                                    </strong>
                                                </span>
                                            </div>

                                        @endif

                                    </div>


                                    {{-- CAPACITÉ --}}

                                    @if($event->capacity)

                                        <div class="gp-event-capacity">

                                            <div class="gp-capacity-header">

                                                <span>
                                                    {{ $reserved }}
                                                    place{{ $reserved > 1 ? 's' : '' }}
                                                    réservée{{ $reserved > 1 ? 's' : '' }}
                                                </span>


                                                @if($remaining > 0)

                                                    <strong>
                                                        {{ $remaining }}
                                                        place{{ $remaining > 1 ? 's' : '' }}
                                                        restante{{ $remaining > 1 ? 's' : '' }}
                                                    </strong>

                                                @else

                                                    <strong class="text-danger">
                                                        Complet
                                                    </strong>

                                                @endif

                                            </div>


                                            <div class="gp-capacity-progress">
                                                <span
                                                    style="width: {{ $fillPercentage }}%"
                                                ></span>
                                            </div>

                                        </div>

                                    @endif


                                    {{-- ACTION --}}

                                    <div class="gp-event-actions">

                                        @if($event->can_reserve)

                                            <a
                                                href="{{ route('events.show', $event) }}#reservation"
                                                class="gp-event-reserve"
                                            >
                                                Réserver ma place

                                                <i class="bi bi-arrow-right"></i>
                                            </a>

                                        @elseif($event->is_full)

                                            <span class="gp-event-disabled">
                                                Événement complet
                                            </span>

                                        @else

                                            <a
                                                href="{{ route('events.show', $event) }}"
                                                class="gp-event-details"
                                            >
                                                Voir les détails

                                                <i class="bi bi-arrow-right"></i>
                                            </a>

                                        @endif

                                    </div>

                                </div>

                            </article>

                        </div>

                    @endforeach

                </div>


                @if($upcoming->count() > 1)
                    <div class="gp-events-pagination"></div>
                @endif

            </div>

        @else

            <div class="gp-events-empty">

                <div class="gp-empty-icon">
                    <i class="bi bi-calendar-event"></i>
                </div>

                <h3>
                    De nouvelles expériences arrivent bientôt.
                </h3>

                <p>
                    Les prochains événements Generation PUSH
                    seront annoncés ici.
                </p>

            </div>

        @endif

    </div>

</section>


{{-- ============================================================
     ÉVÉNEMENTS PASSÉS
============================================================ --}}

<section
    id="past-events"
    class="gp-past-section"
>

    <div class="container">

        <div class="row align-items-end g-4 mb-5">

            <div class="col-lg-8">

                <span class="gp-section-kicker">
                    Rétrospective
                </span>

                <h2 class="gp-section-title">
                    Ils ont déjà vécu l'expérience.
                </h2>

                <p class="gp-section-description">
                    Retrouvez les événements qui ont marqué
                    la communauté Generation PUSH.
                </p>

            </div>


            @if($past->count() > 1)

                <div class="col-lg-4">

                    <div class="gp-slider-controls">

                        <button
                            class="gp-slider-button gp-past-prev"
                            type="button"
                            aria-label="Événement précédent"
                        >
                            <i class="bi bi-arrow-left"></i>
                        </button>

                        <button
                            class="gp-slider-button gp-past-next"
                            type="button"
                            aria-label="Événement suivant"
                        >
                            <i class="bi bi-arrow-right"></i>
                        </button>

                    </div>

                </div>

            @endif

        </div>


        @if($past->isNotEmpty())

            <div class="swiper gp-past-swiper">

                <div class="swiper-wrapper">

                    @foreach($past as $event)

                        <div class="swiper-slide">

                            <article class="gp-past-card">

                                <a
                                    href="{{ route('events.show', $event) }}"
                                    class="gp-past-image"
                                >

                                    @if($event->image)

                                        <img
                                            src="{{ asset('storage/' . $event->image) }}"
                                            alt="{{ $event->title }}"
                                            loading="lazy"
                                        >

                                    @elseif($event->banner)

                                        <img
                                            src="{{ asset('storage/' . $event->banner) }}"
                                            alt="{{ $event->title }}"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="gp-event-placeholder">
                                            PUSH
                                        </div>

                                    @endif


                                    <div class="gp-past-overlay"></div>


                                    <span class="gp-past-badge">
                                        Terminé
                                    </span>


                                    @if($event->category)

                                        <span class="gp-past-category">
                                            {{ $event->category->name }}
                                        </span>

                                    @endif

                                </a>


                                <div class="gp-past-content">

                                    <span class="gp-past-date">
                                        {{ $event->starts_at->translatedFormat('d F Y') }}
                                    </span>


                                    <h3>
                                        {{ \Illuminate\Support\Str::limit(
                                            $event->title,
                                            65
                                        ) }}
                                    </h3>


                                    @if(
                                        $event->format === 'online'
                                        || $event->city
                                        || $event->country
                                    )

                                        <p>

                                            @if($event->format === 'online')

                                                <i class="bi bi-camera-video"></i>
                                                En ligne

                                            @else

                                                <i class="bi bi-geo-alt"></i>

                                                {{ collect([
                                                    $event->city,
                                                    $event->country
                                                ])->filter()->implode(', ') }}

                                            @endif

                                        </p>

                                    @endif


                                    <a
                                        href="{{ route('events.show', $event) }}"
                                    >
                                        Découvrir l'événement

                                        <i class="bi bi-arrow-right"></i>
                                    </a>

                                </div>

                            </article>

                        </div>

                    @endforeach

                </div>

                @if($past->count() > 1)
                    <div class="gp-past-pagination"></div>
                @endif

            </div>

        @else

            <div class="gp-events-empty">

                <div class="gp-empty-icon">
                    <i class="bi bi-clock-history"></i>
                </div>

                <h3>
                    Aucun événement passé pour le moment.
                </h3>

                <p>
                    Les événements terminés apparaîtront
                    automatiquement dans cette section.
                </p>

            </div>

        @endif

    </div>

</section>


{{-- ============================================================
     CTA
============================================================ --}}

<section class="gp-events-cta-section">

    <div class="container">

        <div class="gp-events-cta">

            <div class="gp-cta-glow"></div>

            <div class="position-relative text-center">

                <span>
                    GENERATION PUSH
                </span>

                <h2>
                    Votre prochain déclic
                    peut commencer ici.
                </h2>

                <p>
                    Rencontrez, apprenez, échangez et passez
                    à l'action avec une communauté qui refuse
                    de rester immobile.
                </p>

                <a
                    href="#events"
                    class="btn gp-btn-orange"
                >
                    Trouver mon prochain événement

                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>

        </div>

    </div>

</section>

@endsection


@push('scripts')
    <script src="{{ asset('front/js/events.js') }}"></script>
@endpush
