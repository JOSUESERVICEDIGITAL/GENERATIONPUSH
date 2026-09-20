<x-layouts.admin title="Candidatures">

    @php
        /*
        |--------------------------------------------------------------------------
        | STATISTIQUES
        |--------------------------------------------------------------------------
        */

        $total = $stats['total'] ?? 0;
        $community = $stats['community'] ?? 0;
        $partners = $stats['partners'] ?? 0;
        $volunteers = $stats['volunteers'] ?? 0;
        $pending = $stats['pending'] ?? 0;
        $accepted = $stats['accepted'] ?? 0;
    @endphp


    <div class="space-y-6">


        {{-- ================================================================
             EN-TÊTE
        ================================================================= --}}

        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <h1 class="text-2xl font-bold tracking-tight text-foreground">
                    Candidatures
                </h1>

                <p class="mt-1 text-sm text-muted-foreground">
                    Gérez les inscriptions à la communauté ainsi que les candidatures
                    partenaires et bénévoles de Generation PUSH.
                </p>

            </div>

        </div>



        {{-- ================================================================
             STATISTIQUES
        ================================================================= --}}

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">


            {{-- Total --}}

            <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Total
                        </p>

                        <p class="mt-2 text-2xl font-bold text-foreground">
                            {{ $total }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="layers-3" class="h-5 w-5" />
                    </div>

                </div>

            </div>



            {{-- Communauté --}}

            <div class="rounded-2xl border border-primary/20 bg-primary/[0.03] p-5 shadow-sm">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-primary/70">
                            Communauté
                        </p>

                        <p class="mt-2 text-2xl font-bold text-primary">
                            {{ $community }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="users" class="h-5 w-5" />
                    </div>

                </div>

            </div>



            {{-- Partenaires --}}

            <div class="rounded-2xl border border-blue-200/70 bg-blue-50/40 p-5 shadow-sm dark:border-blue-900/40 dark:bg-blue-950/10">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-blue-700/70 dark:text-blue-400/70">
                            Partenaires
                        </p>

                        <p class="mt-2 text-2xl font-bold text-blue-700 dark:text-blue-400">
                            {{ $partners }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400">
                        <x-icon name="building-2" class="h-5 w-5" />
                    </div>

                </div>

            </div>



            {{-- Bénévoles --}}

            <div class="rounded-2xl border border-purple-200/70 bg-purple-50/40 p-5 shadow-sm dark:border-purple-900/40 dark:bg-purple-950/10">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-purple-700/70 dark:text-purple-400/70">
                            Bénévoles
                        </p>

                        <p class="mt-2 text-2xl font-bold text-purple-700 dark:text-purple-400">
                            {{ $volunteers }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                        <x-icon name="heart-handshake" class="h-5 w-5" />
                    </div>

                </div>

            </div>



            {{-- En attente --}}

            <div class="rounded-2xl border border-amber-200/70 bg-amber-50/40 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-950/10">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-amber-700/70 dark:text-amber-400/70">
                            En attente
                        </p>

                        <p class="mt-2 text-2xl font-bold text-amber-700 dark:text-amber-400">
                            {{ $pending }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                        <x-icon name="clock-3" class="h-5 w-5" />
                    </div>

                </div>

            </div>



            {{-- Acceptées --}}

            <div class="rounded-2xl border border-green-200/70 bg-green-50/40 p-5 shadow-sm dark:border-green-900/40 dark:bg-green-950/10">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wider text-green-700/70 dark:text-green-400/70">
                            Acceptées
                        </p>

                        <p class="mt-2 text-2xl font-bold text-green-700 dark:text-green-400">
                            {{ $accepted }}
                        </p>

                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-500/10 text-green-600 dark:text-green-400">
                        <x-icon name="circle-check" class="h-5 w-5" />
                    </div>

                </div>

            </div>

        </div>



        {{-- ================================================================
             FILTRES
        ================================================================= --}}

        <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">

            <form
                method="GET"
                action="{{ route('admin.engagements.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4"
            >


                {{-- Recherche --}}

                <div class="xl:col-span-2">

                    <label class="mb-2 block text-sm font-medium text-foreground">
                        Recherche
                    </label>

                    <div class="relative">

                        <x-icon
                            name="search"
                            class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground"
                        />

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Nom, email, téléphone, organisation..."
                            class="h-11 w-full rounded-xl border border-border bg-background pl-10 pr-4 text-sm text-foreground outline-none transition placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                        >

                    </div>

                </div>



                {{-- Type --}}

                <div>

                    <label class="mb-2 block text-sm font-medium text-foreground">
                        Type
                    </label>

                    <select
                        name="type"
                        class="h-11 w-full rounded-xl border border-border bg-background px-3 text-sm text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                    >

                        <option value="">
                            Tous les types
                        </option>

                        <option
                            value="community"
                            @selected(request('type') === 'community')
                        >
                            Communauté
                        </option>

                        <option
                            value="partner"
                            @selected(request('type') === 'partner')
                        >
                            Partenaires
                        </option>

                        <option
                            value="volunteer"
                            @selected(request('type') === 'volunteer')
                        >
                            Bénévoles
                        </option>

                    </select>

                </div>



                {{-- Statut --}}

                <div>

                    <label class="mb-2 block text-sm font-medium text-foreground">
                        Statut
                    </label>

                    <select
                        name="status"
                        class="h-11 w-full rounded-xl border border-border bg-background px-3 text-sm text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                    >

                        <option value="">
                            Tous les statuts
                        </option>

                        <option
                            value="pending"
                            @selected(request('status') === 'pending')
                        >
                            En attente
                        </option>

                        <option
                            value="reviewing"
                            @selected(request('status') === 'reviewing')
                        >
                            En cours d'étude
                        </option>

                        <option
                            value="accepted"
                            @selected(request('status') === 'accepted')
                        >
                            Acceptée
                        </option>

                        <option
                            value="rejected"
                            @selected(request('status') === 'rejected')
                        >
                            Refusée
                        </option>

                    </select>

                </div>



                {{-- Boutons --}}

                <div class="flex items-end gap-2 md:col-span-2 xl:col-span-4">

                    <button
                        type="submit"
                        class="inline-flex h-11 items-center gap-2 rounded-xl bg-primary px-5 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-90"
                    >
                        <x-icon name="search" class="h-4 w-4" />
                        Filtrer
                    </button>


                    @if(request()->hasAny(['search', 'type', 'status']))

                        <a
                            href="{{ route('admin.engagements.index') }}"
                            class="inline-flex h-11 items-center gap-2 rounded-xl border border-border bg-background px-5 text-sm font-semibold text-foreground transition hover:bg-secondary"
                        >
                            <x-icon name="rotate-ccw" class="h-4 w-4" />
                            Réinitialiser
                        </a>

                    @endif

                </div>

            </form>

        </div>



        {{-- ================================================================
             TABLEAU
        ================================================================= --}}

        <div class="overflow-hidden rounded-2xl border border-border bg-card shadow-sm">


            {{-- En-tête du tableau --}}

            <div class="border-b border-border px-5 py-4">

                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h2 class="font-semibold text-foreground">
                            Liste des candidatures
                        </h2>

                        <p class="text-sm text-muted-foreground">
                            {{ $applications->total() }} résultat(s)
                        </p>

                    </div>

                </div>

            </div>



        {{-- ============================================================
             DESKTOP
        ============================================================= --}}

        <div class="block w-full overflow-x-auto">

            <table class="w-full">

                <thead class="bg-secondary/40">

                    <tr class="border-b border-border text-left">

                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Candidat
                        </th>

                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Type
                        </th>

                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Localisation
                        </th>

                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Statut
                        </th>

                        <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Date
                        </th>

                        <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                            Action
                        </th>

                    </tr>

                </thead>


              <tbody class="divide-y divide-border">
<tr>
    <td colspan="6" class="p-10 text-center text-xl font-bold text-red-600">
      LE TABLEAU S'AFFICHE
    </td>
</tr>

@forelse($applications as $application)

                        @php

                            $isCommunity = $application->type === 'community';
                            $isPartner = $application->type === 'partner';
                            $isVolunteer = $application->type === 'volunteer';


                            /*
                            |--------------------------------------------------------------------------
                            | NOM AFFICHÉ
                            |--------------------------------------------------------------------------
                            */

                            $displayName = $isCommunity
                                ? ($application->name ?: 'Membre')
                                : ($isPartner
                                    ? ($application->organization ?: 'Organisation')
                                    : ($application->name ?: 'Bénévole'));



                            /*
                            |--------------------------------------------------------------------------
                            | INFORMATIONS SECONDAIRES
                            |--------------------------------------------------------------------------
                            */


                            $secondary = $isCommunity
                                ? 'Inscription à la communauté'
                                : ($isPartner
                                    ? ($application->contact_name ?: 'Contact non renseigné')
                                    : ($application->profession ?: 'Profession non renseignée'));


                            /*
                            |--------------------------------------------------------------------------
                            | LOCALISATION
                            |--------------------------------------------------------------------------
                            */

                            $location = $isCommunity
                                ? ($application->city_country ?? '')
                                : ($isPartner
                                    ? ($application->country ?? '')
                                    : ($application->city_country ?? ''));


                            /*
                            |--------------------------------------------------------------------------
                            | STATUT
                            |--------------------------------------------------------------------------
                            */

                            $statusLabel = match ($application->status) {

                                'pending' => $isCommunity
                                    ? 'En attente de validation'
                                    : 'En attente',

                                'reviewing' =>
                                    'En cours d’étude',

                                'accepted' =>
                                    'Acceptée',

                                'rejected' =>
                                    'Refusée',

                                default =>
                                    ucfirst((string) $application->status),
                            };


                            $statusClass = match ($application->status) {

                                'pending' =>
                                    'bg-amber-500/10 text-amber-700 dark:text-amber-400',

                                'reviewing' =>
                                    'bg-blue-500/10 text-blue-700 dark:text-blue-400',

                                'accepted' =>
                                    'bg-green-500/10 text-green-700 dark:text-green-400',

                                'rejected' =>
                                    'bg-red-500/10 text-red-700 dark:text-red-400',

                                default =>
                                    'bg-secondary text-muted-foreground',
                            };

                        @endphp



                        <tr class="transition hover:bg-secondary/20">


                            {{-- Candidat --}}

                            <td class="px-5 py-4">

                                <div class="flex items-center gap-3">


                                    <div
                                        class="
                                            flex h-10 w-10 shrink-0 items-center justify-center rounded-xl

                                            @if($isCommunity)
                                                bg-primary/10 text-primary
                                            @elseif($isPartner)
                                                bg-blue-500/10 text-blue-600 dark:text-blue-400
                                            @else
                                                bg-purple-500/10 text-purple-600 dark:text-purple-400
                                            @endif
                                        "
                                    >

                                        @if($isCommunity)

                                            <x-icon
                                                name="users"
                                                class="h-5 w-5"
                                            />

                                        @elseif($isPartner)

                                            <x-icon
                                                name="building-2"
                                                class="h-5 w-5"
                                            />

                                        @else

                                            <x-icon
                                                name="heart-handshake"
                                                class="h-5 w-5"
                                            />

                                        @endif

                                    </div>



                                    <div class="min-w-0">

                                        <p class="truncate text-sm font-semibold text-foreground">
                                            {{ $displayName }}
                                        </p>

                                        <p class="truncate text-xs text-muted-foreground">
                                            {{ $application->email ?: '—' }}
                                        </p>

                                        @if($secondary)

                                            <p class="truncate text-xs text-muted-foreground/80">
                                                {{ $secondary }}
                                            </p>

                                        @endif

                                    </div>

                                </div>
                            </td>


                            {{-- Type --}}

                            <td class="px-5 py-4">

                                @if($isCommunity)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary">

                                        <x-icon
                                            name="users"
                                            class="h-3.5 w-3.5"
                                        />

                                        Communauté

                                    </span>

                                @elseif($isPartner)

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400">

                                        <x-icon
                                            name="building-2"
                                            class="h-3.5 w-3.5"
                                        />

                                        Partenaire

                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-500/10 px-3 py-1.5 text-xs font-semibold text-purple-700 dark:text-purple-400">

                                        <x-icon
                                            name="heart-handshake"
                                            class="h-3.5 w-3.5"
                                        />

                                        Bénévole

                                    </span>

                                @endif

                            </td>



                            {{-- Localisation --}}

                            <td class="px-5 py-4">

                                <span class="text-sm text-muted-foreground">
                                    {{ $location ?: '—' }}
                                </span>

                            </td>



                            {{-- Statut --}}

                            <td class="px-5 py-4">

                                <span
                                    class="inline-flex rounded-full px-3 py-1.5 text-xs font-semibold {{ $statusClass }}"
                                >
                                    {{ $statusLabel }}
                                </span>

                            </td>



                            {{-- Date --}}

                            <td class="whitespace-nowrap px-5 py-4">

                                <span class="text-sm text-muted-foreground">

                                    {{ $application->created_at?->format('d/m/Y H:i') ?? '—' }}

                                </span>

                            </td>



                            {{-- Action --}}

                            <td class="px-5 py-4 text-right">
 <div class="flex items-center justify-end gap-2">

    {{-- Voir --}}
    <a href="{{ route('admin.engagements.show', [
        'type' => $application->type,
        'id' => $application->id,
    ]) }}"
       class="inline-flex items-center gap-2 rounded-lg border border-border px-3 py-2 text-sm font-medium hover:bg-gray-50">
        <i class="fas fa-eye"></i>
        Voir
    </a>

    {{-- Supprimer --}}
    <form action="{{ route('admin.engagements.destroy', [
        'type' => $application->type,
        'id' => $application->id,
    ]) }}"
          method="POST"
          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette candidature ?');">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg border border-red-200 px-3 py-2 text-sm font-medium text-red-600 hover:bg-red-50">
            <i class="fas fa-trash"></i>
            Supprimer
        </button>
    </form>

</div>
              

                            </td>


                                    </tr>


                    @empty


                        <tr>

                            <td
                                colspan="6"
                                class="px-5 py-16 text-center"
                            >

                                <div class="mx-auto flex max-w-sm flex-col items-center">

                                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-muted-foreground">

                                        <x-icon
                                            name="inbox"
                                            class="h-7 w-7"
                                        />

                                    </div>

                                    <h3 class="mt-4 font-semibold text-foreground">
                                        Aucune candidature
                                    </h3>

                                    <p class="mt-1 text-sm leading-6 text-muted-foreground">
                                        Aucune inscription ou candidature ne correspond aux critères sélectionnés.
                                    </p>

                                </div>

                            </td>

                        </tr>


                    @endforelse

                </tbody>
            </table>

        </div>








            {{-- ============================================================
                 MOBILE
            ============================================================= --}}

            <div class="divide-y divide-border md:hidden">

                @forelse($applications as $application)

                    @php

                        $isCommunity = $application->type === 'community';
                        $isPartner = $application->type === 'partner';
                        $isVolunteer = $application->type === 'volunteer';


                        $displayName = $isCommunity
                            ? ($application->name ?: 'Membre')
                            : ($isPartner
                                ? ($application->organization ?: 'Organisation')
                                : ($application->name ?: 'Bénévole'));


                        $secondary = $isCommunity
                            ? 'Inscription à la communauté'
                            : ($isPartner
                                ? ($application->contact_name ?: 'Contact non renseigné')
                                : ($application->profession ?: 'Profession non renseignée'));


                        $location = $isCommunity
                            ? ($application->city_country ?? '')
                            : ($isPartner
                                ? ($application->country ?? '')
                                : ($application->city_country ?? ''));


                        $statusLabel = match ($application->status) {

                            'pending' => $isCommunity
                                ? 'En attente de validation'
                                : 'En attente',

                            'reviewing' =>
                                'En cours d’étude',

                            'accepted' =>
                                'Acceptée',

                            'rejected' =>
                                'Refusée',

                            default =>
                                ucfirst((string) $application->status),
                        };


                        $statusClass = match ($application->status) {

                            'pending' =>
                                'bg-amber-500/10 text-amber-700 dark:text-amber-400',

                            'reviewing' =>
                                'bg-blue-500/10 text-blue-700 dark:text-blue-400',

                            'accepted' =>
                                'bg-green-500/10 text-green-700 dark:text-green-400',

                            'rejected' =>
                                'bg-red-500/10 text-red-700 dark:text-red-400',

                            default =>
                                'bg-secondary text-muted-foreground',
                        };

                    @endphp



                    <div class="p-5">


                        {{-- Identité --}}

                        <div class="flex items-start gap-3">


                            <div
                                class="
                                    flex h-11 w-11 shrink-0 items-center justify-center rounded-xl

                                    @if($isCommunity)
                                        bg-primary/10 text-primary
                                    @elseif($isPartner)
                                        bg-blue-500/10 text-blue-600 dark:text-blue-400
                                    @else
                                        bg-purple-500/10 text-purple-600 dark:text-purple-400
                                    @endif
                                "
                            >

                                @if($isCommunity)

                                    <x-icon
                                        name="users"
                                        class="h-5 w-5"
                                    />

                                @elseif($isPartner)

                                    <x-icon
                                        name="building-2"
                                        class="h-5 w-5"
                                    />

                                @else

                                    <x-icon
                                        name="heart-handshake"
                                        class="h-5 w-5"
                                    />

                                @endif

                            </div>



                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <h3 class="truncate text-sm font-semibold text-foreground">
                                        {{ $displayName }}
                                    </h3>

                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}"
                                    >
                                        {{ $statusLabel }}
                                    </span>

                                </div>


                                <p class="mt-1 truncate text-xs text-muted-foreground">
                                    {{ $application->email ?: '—' }}
                                </p>


                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ $secondary ?: '—' }}
                                </p>

                            </div>

                        </div>



                        {{-- Badges --}}

                        <div class="mt-4 flex flex-wrap items-center gap-2">


                            @if($isCommunity)

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary">

                                    <x-icon
                                        name="users"
                                        class="h-3.5 w-3.5"
                                    />

                                    Communauté

                                </span>

                            @elseif($isPartner)

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-700 dark:text-blue-400">

                                    <x-icon
                                        name="building-2"
                                        class="h-3.5 w-3.5"
                                    />

                                    Partenaire

                                </span>

                            @else

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-500/10 px-3 py-1.5 text-xs font-semibold text-purple-700 dark:text-purple-400">

                                    <x-icon
                                        name="heart-handshake"
                                        class="h-3.5 w-3.5"
                                    />

                                    Bénévole

                                </span>

                            @endif



                            @if($location)

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-secondary px-3 py-1.5 text-xs font-medium text-muted-foreground">

                                    <x-icon
                                        name="map-pin"
                                        class="h-3.5 w-3.5"
                                    />

                                    {{ $location }}

                                </span>

                            @endif

                        </div>



                        {{-- Date + action --}}

                        <div class="mt-4 flex items-center justify-between gap-3">


                            <span class="text-xs text-muted-foreground">

                                {{ $application->created_at?->format('d/m/Y H:i') ?? '—' }}

                            </span>



                            <a
                                href="{{ route('admin.engagements.show', [
                                    'type' => $application->type,
                                    'id' => $application->id,
                                ]) }}"
                                class="inline-flex h-9 items-center gap-2 rounded-lg border border-border bg-background px-3 text-sm font-semibold text-foreground transition hover:border-primary/30 hover:bg-primary/5 hover:text-primary"
                            >

                                <x-icon
                                    name="eye"
                                    class="h-4 w-4"
                                />

                                Voir

                            </a>

                        </div>

                    </div>


                @empty


                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-muted-foreground">

                            <x-icon
                                name="inbox"
                                class="h-7 w-7"
                            />

                        </div>


                        <h3 class="mt-4 font-semibold text-foreground">
                            Aucune candidature
                        </h3>


                        <p class="mx-auto mt-1 max-w-sm text-sm leading-6 text-muted-foreground">
                            Aucune inscription ou candidature ne correspond aux critères sélectionnés.
                        </p>

                    </div>


                @endforelse

            </div>



            {{-- ============================================================
                 PAGINATION
            ============================================================= --}}

            @if($applications->hasPages())

                <div class="border-t border-border px-5 py-4">

                    {{ $applications->withQueryString()->links() }}

                </div>

            @endif

        </div>

    </div>

</x-layouts.admin>
