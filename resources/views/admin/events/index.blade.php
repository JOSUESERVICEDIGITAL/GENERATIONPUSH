@extends('layouts.admin')

@section('title', 'Événements')

@section('content')

<div class="space-y-6">

    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}

    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

        <div>
            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                <span>Gestion</span>
                <x-icon name="chevron-right" class="w-4 h-4" />
                <span class="text-foreground">Événements</span>
            </div>

            <h1 class="text-2xl md:text-3xl font-bold text-foreground">
                Gestion des événements
            </h1>

            <p class="text-sm text-muted-foreground mt-1">
                Gérez tous les événements Generation PUSH depuis un seul espace.
            </p>
        </div>


        <a
            href="{{ route('admin.events.create') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition-all duration-200 shadow-sm"
        >
            <x-icon name="plus" class="w-5 h-5" />

            Nouvel événement
        </a>

    </div>



    {{-- ============================================================
         STATISTIQUES
    ============================================================ --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

        {{-- TOTAL --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-muted-foreground">
                        Tous
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ $stats['total'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-secondary flex items-center justify-center">
                    <x-icon
                        name="calendar"
                        class="w-5 h-5 text-foreground"
                    />
                </div>

            </div>

        </div>


        {{-- À VENIR --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-muted-foreground">
                        À venir
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ $stats['upcoming'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center">
                    <x-icon
                        name="calendar-days"
                        class="w-5 h-5 text-accent"
                    />
                </div>

            </div>

        </div>


        {{-- EN COURS --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-muted-foreground">
                        En cours
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ $stats['ongoing'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-green-500/10 flex items-center justify-center">
                    <x-icon
                        name="activity"
                        class="w-5 h-5 text-green-600"
                    />
                </div>

            </div>

        </div>


        {{-- PASSÉS --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-muted-foreground">
                        Passés
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ $stats['past'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-secondary flex items-center justify-center">
                    <x-icon
                        name="history"
                        class="w-5 h-5 text-muted-foreground"
                    />
                </div>

            </div>

        </div>


        {{-- BROUILLONS --}}

        <div class="bg-card border border-border rounded-xl p-5 shadow-sm">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-muted-foreground">
                        Brouillons
                    </p>

                    <p class="text-2xl font-bold text-foreground mt-1">
                        {{ $stats['draft'] ?? 0 }}
                    </p>
                </div>

                <div class="w-11 h-11 rounded-xl bg-yellow-500/10 flex items-center justify-center">
                    <x-icon
                        name="file-text"
                        class="w-5 h-5 text-yellow-600"
                    />
                </div>

            </div>

        </div>

    </div>



    {{-- ============================================================
         FILTRES
    ============================================================ --}}

    <div class="bg-card border border-border rounded-xl shadow-sm">

        <form
            method="GET"
            action="{{ route('admin.events.index') }}"
            class="p-4 md:p-5"
        >

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">

                {{-- RECHERCHE --}}

                <div class="xl:col-span-2">

                    <label
                        for="search"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Rechercher
                    </label>

                    <div class="relative">

                        <x-icon
                            name="search"
                            class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                        />

                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nom de l'événement..."
                            class="w-full ps-10 pe-4 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                        >

                    </div>

                </div>


                {{-- CATÉGORIE --}}

                <div>

                    <label
                        for="category"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Catégorie
                    </label>

                    <select
                        id="category"
                        name="category"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                    >

                        <option value="">
                            Toutes
                        </option>

                        @foreach($categories ?? [] as $category)

                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    (string) request('category')
                                    === (string) $category->id
                                )
                            >
                                {{ $category->name }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- PÉRIODE --}}

                <div>

                    <label
                        for="period"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Période
                    </label>

                    <select
                        id="period"
                        name="period"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                    >

                        <option value="">
                            Toutes
                        </option>

                        <option
                            value="upcoming"
                            @selected(request('period') === 'upcoming')
                        >
                            À venir
                        </option>

                        <option
                            value="ongoing"
                            @selected(request('period') === 'ongoing')
                        >
                            En cours
                        </option>

                        <option
                            value="past"
                            @selected(request('period') === 'past')
                        >
                            Passés
                        </option>

                    </select>

                </div>


                {{-- PUBLICATION --}}

                <div>

                    <label
                        for="status"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Publication
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                    >

                        <option value="">
                            Tous
                        </option>

                        <option
                            value="published"
                            @selected(request('status') === 'published')
                        >
                            Publiés
                        </option>

                        <option
                            value="draft"
                            @selected(request('status') === 'draft')
                        >
                            Brouillons
                        </option>

                        <option
                            value="cancelled"
                            @selected(request('status') === 'cancelled')
                        >
                            Annulés
                        </option>

                    </select>

                </div>

            </div>


            {{-- BOUTONS FILTRES --}}

            <div class="flex flex-wrap items-center gap-3 mt-4">

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition"
                >
                    <x-icon
                        name="filter"
                        class="w-4 h-4"
                    />

                    Filtrer
                </button>


                @if(
                    request()->filled('search')
                    || request()->filled('category')
                    || request()->filled('period')
                    || request()->filled('status')
                )

                    <a
                        href="{{ route('admin.events.index') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition"
                    >
                        <x-icon
                            name="x"
                            class="w-4 h-4"
                        />

                        Réinitialiser
                    </a>

                @endif

            </div>

        </form>

    </div>



    {{-- ============================================================
         TABLEAU
    ============================================================ --}}

    <div class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        {{-- EN-TÊTE TABLEAU --}}

        <div class="px-5 py-4 border-b border-border flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

            <div>

                <h2 class="font-semibold text-foreground">
                    Liste des événements
                </h2>

                <p class="text-sm text-muted-foreground mt-0.5">
                    {{ $events->total() }} événement{{ $events->total() > 1 ? 's' : '' }}
                </p>

            </div>

        </div>


        @if($events->count())


            {{-- ========================================================
                 DESKTOP
            ======================================================== --}}

            <div class="hidden lg:block overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-secondary/50 border-b border-border">

                        <tr class="text-start">

                            <th class="px-5 py-3 text-start font-medium text-muted-foreground">
                                Événement
                            </th>

                            <th class="px-4 py-3 text-start font-medium text-muted-foreground">
                                Type
                            </th>

                            <th class="px-4 py-3 text-start font-medium text-muted-foreground">
                                Date
                            </th>

                            <th class="px-4 py-3 text-start font-medium text-muted-foreground">
                                Réservations
                            </th>

                            <th class="px-4 py-3 text-start font-medium text-muted-foreground">
                                Places
                            </th>

                            <th class="px-4 py-3 text-start font-medium text-muted-foreground">
                                État
                            </th>

                            <th class="px-4 py-3 text-start font-medium text-muted-foreground">
                                Publication
                            </th>

                            <th class="px-5 py-3 text-end font-medium text-muted-foreground">
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-border">

                        @foreach($events as $event)

                            @php

                                $reservedPlaces =
                                    $event->confirmed_reserved_places
                                    ?? 0;

                                $remainingPlaces =
                                    $event->capacity
                                    ? max(
                                        $event->capacity - $reservedPlaces,
                                        0
                                    )
                                    : null;

                                /*
                                |--------------------------------------------------------------------------
                                | ÉTAT TEMPOREL
                                |--------------------------------------------------------------------------
                                */

                                if ($event->starts_at->isFuture()) {

                                    $temporalStatus = 'upcoming';
                                    $temporalLabel = 'À venir';

                                    $temporalClasses =
                                        'bg-blue-500/10 text-blue-600';

                                } elseif (
                                    $event->ends_at
                                    && now()->between(
                                        $event->starts_at,
                                        $event->ends_at
                                    )
                                ) {

                                    $temporalStatus = 'ongoing';
                                    $temporalLabel = 'En cours';

                                    $temporalClasses =
                                        'bg-green-500/10 text-green-600';

                                } else {

                                    $temporalStatus = 'completed';
                                    $temporalLabel = 'Terminé';

                                    $temporalClasses =
                                        'bg-secondary text-muted-foreground';
                                }


                                /*
                                |--------------------------------------------------------------------------
                                | PUBLICATION
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
                                        'bg-green-500/10 text-green-600',

                                    'draft' =>
                                        'bg-yellow-500/10 text-yellow-600',

                                    'cancelled' =>
                                        'bg-red-500/10 text-red-600',

                                    default =>
                                        'bg-secondary text-muted-foreground',
                                };

                            @endphp


                            <tr class="hover:bg-secondary/30 transition-colors">

                                {{-- ÉVÉNEMENT --}}

                                <td class="px-5 py-4">

                                    <div class="flex items-center gap-3 min-w-[240px]">

                                        <div class="w-14 h-14 rounded-lg overflow-hidden bg-secondary shrink-0">

                                            @if($event->image)

                                                <img
                                                    src="{{ asset('storage/' . $event->image) }}"
                                                    alt="{{ $event->title }}"
                                                    class="w-full h-full object-cover"
                                                >

                                            @else

                                                <div class="w-full h-full flex items-center justify-center">

                                                    <x-icon
                                                        name="calendar"
                                                        class="w-5 h-5 text-muted-foreground"
                                                    />

                                                </div>

                                            @endif

                                        </div>


                                        <div class="min-w-0">

                                            <a
                                                href="{{ route('admin.events.show', $event) }}"
                                                class="font-semibold text-foreground hover:text-accent transition-colors line-clamp-1"
                                            >
                                                {{ $event->title }}
                                            </a>


                                            @if($event->city || $event->country)

                                                <p class="text-xs text-muted-foreground mt-1 truncate">

                                                    {{ collect([
                                                        $event->city,
                                                        $event->country
                                                    ])->filter()->implode(', ') }}

                                                </p>

                                            @elseif($event->format === 'online')

                                                <p class="text-xs text-muted-foreground mt-1">
                                                    En ligne
                                                </p>

                                            @endif


                                            @if($event->featured)

                                                <span class="inline-flex items-center gap-1 mt-1 text-[11px] font-medium text-accent">

                                                    <x-icon
                                                        name="star"
                                                        class="w-3 h-3"
                                                    />

                                                    À la une

                                                </span>

                                            @endif

                                        </div>

                                    </div>

                                </td>


                                {{-- CATÉGORIE --}}

                                <td class="px-4 py-4">

                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-accent/10 text-accent text-xs font-medium">

                                        {{ $event->category?->name ?? 'Non définie' }}

                                    </span>

                                </td>


                                {{-- DATE --}}

                                <td class="px-4 py-4 whitespace-nowrap">

                                    <p class="font-medium text-foreground">
                                        {{ $event->starts_at->translatedFormat('d M Y') }}
                                    </p>

                                    <p class="text-xs text-muted-foreground mt-1">
                                        {{ $event->starts_at->format('H:i') }}
                                    </p>

                                </td>


                                {{-- RÉSERVATIONS --}}

                                <td class="px-4 py-4">

                                    <div class="flex items-center gap-2">

                                        <x-icon
                                            name="users"
                                            class="w-4 h-4 text-muted-foreground"
                                        />

                                        <span class="font-medium text-foreground">
                                            {{ $reservedPlaces }}
                                        </span>

                                    </div>

                                </td>


                                {{-- PLACES --}}

                                <td class="px-4 py-4 whitespace-nowrap">

                                    @if($event->capacity)

                                        <p class="font-medium text-foreground">
                                            {{ $reservedPlaces }}
                                            /
                                            {{ $event->capacity }}
                                        </p>

                                        <p class="text-xs text-muted-foreground mt-1">
                                            {{ $remainingPlaces }} disponible{{ $remainingPlaces > 1 ? 's' : '' }}
                                        </p>

                                    @else

                                        <span class="text-muted-foreground">
                                            Illimité
                                        </span>

                                    @endif

                                </td>


                                {{-- ÉTAT TEMPOREL --}}

                                <td class="px-4 py-4">

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $temporalClasses }}"
                                    >
                                        {{ $temporalLabel }}
                                    </span>

                                </td>


                                {{-- PUBLICATION --}}

                                <td class="px-4 py-4">

                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $publicationClasses }}"
                                    >
                                        {{ $publicationLabel }}
                                    </span>

                                </td>


                                {{-- ACTIONS --}}

                                <td class="px-5 py-4 text-end">

                                    <div
                                        class="relative inline-block text-start"
                                        x-data="{ open: false }"
                                        @click.outside="open = false"
                                    >

                                        <button
                                            type="button"
                                            @click="open = !open"
                                            class="p-2 rounded-lg hover:bg-secondary text-muted-foreground hover:text-foreground transition"
                                        >

                                            <x-icon
                                                name="more-horizontal"
                                                class="w-5 h-5"
                                            />

                                        </button>


                                        <div
                                            x-show="open"
                                            x-cloak
                                            x-transition
                                            class="absolute end-0 mt-2 w-44 bg-card border border-border rounded-lg shadow-xl z-50 p-1"
                                        >

                                            <a
                                                href="{{ route('admin.events.show', $event) }}"
                                                class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-foreground hover:bg-secondary"
                                            >

                                                <x-icon
                                                    name="eye"
                                                    class="w-4 h-4"
                                                />

                                                Voir

                                            </a>


                                            <a
                                                href="{{ route('admin.events.edit', $event) }}"
                                                class="flex items-center gap-2 px-3 py-2 rounded-md text-sm text-foreground hover:bg-secondary"
                                            >

                                                <x-icon
                                                    name="pencil"
                                                    class="w-4 h-4"
                                                />

                                                Modifier

                                            </a>


                                            <div class="my-1 border-t border-border"></div>


                                            <form
                                                method="POST"
                                                action="{{ route('admin.events.destroy', $event) }}"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cet événement ?');"
                                            >

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-sm text-destructive hover:bg-destructive/10"
                                                >

                                                    <x-icon
                                                        name="trash-2"
                                                        class="w-4 h-4"
                                                    />

                                                    Supprimer

                                                </button>

                                            </form>

                                        </div>

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>



            {{-- ========================================================
                 MOBILE / TABLETTE
            ======================================================== --}}

            <div class="lg:hidden divide-y divide-border">

                @foreach($events as $event)

                    @php

                        $reservedPlaces =
                            $event->confirmed_reserved_places
                            ?? 0;

                        if ($event->starts_at->isFuture()) {

                            $mobileTemporalLabel = 'À venir';

                            $mobileTemporalClasses =
                                'bg-blue-500/10 text-blue-600';

                        } elseif (
                            $event->ends_at
                            && now()->between(
                                $event->starts_at,
                                $event->ends_at
                            )
                        ) {

                            $mobileTemporalLabel = 'En cours';

                            $mobileTemporalClasses =
                                'bg-green-500/10 text-green-600';

                        } else {

                            $mobileTemporalLabel = 'Terminé';

                            $mobileTemporalClasses =
                                'bg-secondary text-muted-foreground';
                        }

                    @endphp


                    <div class="p-4">

                        <div class="flex gap-3">

                            <div class="w-16 h-16 rounded-lg overflow-hidden bg-secondary shrink-0">

                                @if($event->image)

                                    <img
                                        src="{{ asset('storage/' . $event->image) }}"
                                        alt="{{ $event->title }}"
                                        class="w-full h-full object-cover"
                                    >

                                @else

                                    <div class="w-full h-full flex items-center justify-center">

                                        <x-icon
                                            name="calendar"
                                            class="w-6 h-6 text-muted-foreground"
                                        />

                                    </div>

                                @endif

                            </div>


                            <div class="min-w-0 flex-1">

                                <div class="flex items-start justify-between gap-2">

                                    <div class="min-w-0">

                                        <a
                                            href="{{ route('admin.events.show', $event) }}"
                                            class="font-semibold text-foreground hover:text-accent line-clamp-2"
                                        >
                                            {{ $event->title }}
                                        </a>

                                        <p class="text-xs text-muted-foreground mt-1">

                                            {{ $event->category?->name ?? 'Événement' }}

                                            ·

                                            {{ $event->starts_at->translatedFormat('d M Y') }}

                                        </p>

                                    </div>


                                    <div
                                        class="relative shrink-0"
                                        x-data="{ open: false }"
                                        @click.outside="open = false"
                                    >

                                        <button
                                            type="button"
                                            @click="open = !open"
                                            class="p-2 rounded-lg hover:bg-secondary text-muted-foreground"
                                        >

                                            <x-icon
                                                name="more-horizontal"
                                                class="w-5 h-5"
                                            />

                                        </button>


                                        <div
                                            x-show="open"
                                            x-cloak
                                            x-transition
                                            class="absolute end-0 mt-1 w-40 bg-card border border-border rounded-lg shadow-xl z-50 p-1"
                                        >

                                            <a
                                                href="{{ route('admin.events.show', $event) }}"
                                                class="block px-3 py-2 rounded-md text-sm text-foreground hover:bg-secondary"
                                            >
                                                Voir
                                            </a>

                                            <a
                                                href="{{ route('admin.events.edit', $event) }}"
                                                class="block px-3 py-2 rounded-md text-sm text-foreground hover:bg-secondary"
                                            >
                                                Modifier
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>


                        <div class="grid grid-cols-2 gap-3 mt-4">

                            <div class="bg-secondary/50 rounded-lg p-3">

                                <p class="text-xs text-muted-foreground">
                                    Réservations
                                </p>

                                <p class="font-semibold text-foreground mt-1">
                                    {{ $reservedPlaces }}

                                    @if($event->capacity)
                                        / {{ $event->capacity }}
                                    @endif
                                </p>

                            </div>


                            <div class="bg-secondary/50 rounded-lg p-3">

                                <p class="text-xs text-muted-foreground">
                                    État
                                </p>

                                <span
                                    class="inline-flex mt-1 px-2 py-1 rounded-full text-xs font-medium {{ $mobileTemporalClasses }}"
                                >
                                    {{ $mobileTemporalLabel }}
                                </span>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>



            {{-- ========================================================
                 PAGINATION
            ======================================================== --}}

            @if($events->hasPages())

                <div class="px-5 py-4 border-t border-border">

                    {{ $events->withQueryString()->links() }}

                </div>

            @endif


        @else


            {{-- ========================================================
                 ÉTAT VIDE
            ======================================================== --}}

            <div class="py-16 px-6 text-center">

                <div class="w-16 h-16 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto">

                    <x-icon
                        name="calendar"
                        class="w-8 h-8 text-accent"
                    />

                </div>


                <h3 class="text-lg font-semibold text-foreground mt-5">
                    Aucun événement
                </h3>


                <p class="text-sm text-muted-foreground max-w-md mx-auto mt-2">

                    Aucun événement ne correspond actuellement à votre recherche.
                    Vous pouvez modifier vos filtres ou créer votre premier événement.

                </p>


                <div class="flex flex-wrap justify-center gap-3 mt-6">

                    @if(
                        request()->filled('search')
                        || request()->filled('category')
                        || request()->filled('period')
                        || request()->filled('status')
                    )

                        <a
                            href="{{ route('admin.events.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-border text-foreground hover:bg-secondary transition"
                        >

                            <x-icon
                                name="x"
                                class="w-4 h-4"
                            />

                            Effacer les filtres

                        </a>

                    @endif


                    <a
                        href="{{ route('admin.events.create') }}"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition"
                    >

                        <x-icon
                            name="plus"
                            class="w-4 h-4"
                        />

                        Créer un événement

                    </a>

                </div>

            </div>

        @endif

    </div>

</div>

@endsection
