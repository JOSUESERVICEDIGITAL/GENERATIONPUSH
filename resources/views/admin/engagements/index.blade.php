<x-layouts.admin title="Candidatures">

    <div class="space-y-6">

        {{-- ============================================================
             EN-TÊTE
        ============================================================= --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="clipboard-list" class="h-6 w-6" />
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold tracking-tight text-foreground">
                            Candidatures
                        </h1>

                        <p class="text-sm text-muted-foreground">
                            Gérez les candidatures partenaires et bénévoles de Generation PUSH.
                        </p>
                    </div>
                </div>
            </div>
        </div>


        {{-- ============================================================
             MESSAGE DE SUCCÈS
        ============================================================= --}}
        @if(session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition
                class="relative flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-green-900/50 dark:bg-green-950/30 dark:text-green-300"
            >
                <x-icon name="circle-check" class="mt-0.5 h-5 w-5 shrink-0" />

                <div class="flex-1 text-sm font-medium">
                    {{ session('success') }}
                </div>

                <button
                    type="button"
                    @click="show = false"
                    class="rounded-lg p-1 hover:bg-green-100 dark:hover:bg-green-900/30"
                >
                    <x-icon name="x" class="h-4 w-4" />
                </button>
            </div>
        @endif


        {{-- ============================================================
             STATISTIQUES
        ============================================================= --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

            {{-- Total --}}
            <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            Total
                        </p>

                        <p class="mt-2 text-3xl font-bold text-foreground">
                            {{ $stats['total'] ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-primary/10 text-primary">
                        <x-icon name="clipboard-list" class="h-5 w-5" />
                    </div>
                </div>
            </div>

            {{-- Partenaires --}}
            <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            Partenaires
                        </p>

                        <p class="mt-2 text-3xl font-bold text-foreground">
                            {{ $stats['partners'] ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600">
                        <x-icon name="building-2" class="h-5 w-5" />
                    </div>
                </div>
            </div>

            {{-- Bénévoles --}}
            <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            Bénévoles
                        </p>

                        <p class="mt-2 text-3xl font-bold text-foreground">
                            {{ $stats['volunteers'] ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-purple-500/10 text-purple-600">
                        <x-icon name="heart-handshake" class="h-5 w-5" />
                    </div>
                </div>
            </div>

            {{-- En attente --}}
            <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            En attente
                        </p>

                        <p class="mt-2 text-3xl font-bold text-foreground">
                            {{ $stats['pending'] ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                        <x-icon name="clock-3" class="h-5 w-5" />
                    </div>
                </div>
            </div>

            {{-- Acceptées --}}
            <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-muted-foreground">
                            Acceptées
                        </p>

                        <p class="mt-2 text-3xl font-bold text-foreground">
                            {{ $stats['accepted'] ?? 0 }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-500/10 text-green-600">
                        <x-icon name="circle-check" class="h-5 w-5" />
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================================================
             FILTRES
        ============================================================= --}}
        <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">

            <form
                method="GET"
                action="{{ route('admin.engagements.index') }}"
                class="grid grid-cols-1 gap-4 lg:grid-cols-12"
            >

                {{-- Recherche --}}
                <div class="lg:col-span-6">
                    <label class="mb-2 block text-sm font-medium text-foreground">
                        Rechercher
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
                            placeholder="Nom, organisation, email, téléphone, secteur..."
                            class="h-11 w-full rounded-xl border border-border bg-background pl-10 pr-4 text-sm text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                        />
                    </div>
                </div>


                {{-- Type --}}
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-medium text-foreground">
                        Type
                    </label>

                    <select
                        name="type"
                        class="h-11 w-full rounded-xl border border-border bg-background px-3 text-sm text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                    >
                        <option value="">Toutes les candidatures</option>
                        <option value="partner" @selected(request('type') === 'partner')}>
                            Partenaires
                        </option>
                        <option value="volunteer" @selected(request('type') === 'volunteer')}>
                            Bénévoles
                        </option>
                    </select>
                </div>


                {{-- Statut --}}
                <div class="lg:col-span-3">
                    <label class="mb-2 block text-sm font-medium text-foreground">
                        Statut
                    </label>

                    <select
                        name="status"
                        class="h-11 w-full rounded-xl border border-border bg-background px-3 text-sm text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                    >
                        <option value="">Tous les statuts</option>

                        <option value="pending" @selected(request('status') === 'pending')}>
                            En attente
                        </option>

                        <option value="reviewing" @selected(request('status') === 'reviewing')}>
                            En cours d'étude
                        </option>

                        <option value="accepted" @selected(request('status') === 'accepted')}>
                            Acceptée
                        </option>

                        <option value="rejected" @selected(request('status') === 'rejected')}>
                            Refusée
                        </option>
                    </select>
                </div>


                {{-- Boutons --}}
                <div class="flex flex-wrap items-center gap-2 lg:col-span-12">

                    <button
                        type="submit"
                        class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground shadow-sm transition hover:opacity-90"
                    >
                        <x-icon name="search" class="h-4 w-4" />
                        Rechercher
                    </button>

                    @if(request()->hasAny(['search', 'type', 'status']))
                        <a
                            href="{{ route('admin.engagements.index') }}"
                            class="inline-flex h-10 items-center gap-2 rounded-xl border border-border bg-background px-4 text-sm font-medium text-foreground transition hover:bg-secondary"
                        >
                            <x-icon name="x" class="h-4 w-4" />
                            Réinitialiser
                        </a>
                    @endif

                </div>

            </form>
        </div>


        {{-- ============================================================
             TABLEAU
        ============================================================= --}}
        <div class="overflow-hidden rounded-2xl border border-border bg-card shadow-sm">

            <div class="flex flex-col gap-3 border-b border-border px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-semibold text-foreground">
                        Liste des candidatures
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        {{ $applications->total() }}
                        candidature{{ $applications->total() > 1 ? 's' : '' }}
                    </p>
                </div>
            </div>


            {{-- Desktop --}}
            <div class="hidden overflow-x-auto lg:block">

                <table class="w-full text-left">

                    <thead class="border-b border-border bg-secondary/40">
                        <tr>
                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Candidature
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Contact
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Localisation
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Type
                            </th>

                            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Statut
                            </th>

                            <th class="px-5 py-3 text-right text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                Action
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-border">

                        @forelse($applications as $application)

                            @php
                                $isPartner = $application->type === 'partner';

                                $displayName = $isPartner
                                    ? $application->organization
                                    : $application->name;

                                $secondary = $isPartner
                                    ? $application->contact_name
                                    : $application->profession;

                                $location = $isPartner
                                    ? $application->country
                                    : $application->city_country;

                                $statusClasses = match($application->status) {
                                    'pending' => 'bg-amber-500/10 text-amber-700 dark:text-amber-400',
                                    'reviewing' => 'bg-blue-500/10 text-blue-700 dark:text-blue-400',
                                    'accepted' => 'bg-green-500/10 text-green-700 dark:text-green-400',
                                    'rejected' => 'bg-red-500/10 text-red-700 dark:text-red-400',
                                    default => 'bg-secondary text-muted-foreground',
                                };

                                $statusLabel = match($application->status) {
                                    'pending' => 'En attente',
                                    'reviewing' => 'En cours d’étude',
                                    'accepted' => 'Acceptée',
                                    'rejected' => 'Refusée',
                                    default => ucfirst($application->status),
                                };
                            @endphp

                            <tr class="transition hover:bg-secondary/30">

                                {{-- Candidature --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">

                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $isPartner ? 'bg-blue-500/10 text-blue-600' : 'bg-purple-500/10 text-purple-600' }}">
                                            <x-icon
                                                name="{{ $isPartner ? 'building-2' : 'heart-handshake' }}"
                                                class="h-5 w-5"
                                            />
                                        </div>

                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-foreground">
                                                {{ $displayName }}
                                            </p>

                                            <p class="truncate text-sm text-muted-foreground">
                                                {{ $secondary }}
                                            </p>
                                        </div>

                                    </div>
                                </td>


                                {{-- Contact --}}
                                <td class="px-5 py-4">
                                    <div class="space-y-1">
                                        <p class="text-sm text-foreground">
                                            {{ $application->email }}
                                        </p>

                                        <p class="text-xs text-muted-foreground">
                                            {{ $application->phone }}
                                        </p>
                                    </div>
                                </td>


                                {{-- Localisation --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 text-sm text-foreground">
                                        <x-icon
                                            name="map-pin"
                                            class="h-4 w-4 text-muted-foreground"
                                        />

                                        {{ $location }}
                                    </div>
                                </td>


                                {{-- Type --}}
                                <td class="px-5 py-4">
                                    @if($isPartner)

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-500/10 px-3 py-1 text-xs font-semibold text-blue-700 dark:text-blue-400">
                                            <x-icon name="building-2" class="h-3.5 w-3.5" />
                                            Partenaire
                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-purple-500/10 px-3 py-1 text-xs font-semibold text-purple-700 dark:text-purple-400">
                                            <x-icon name="heart-handshake" class="h-3.5 w-3.5" />
                                            Bénévole
                                        </span>

                                    @endif
                                </td>


                                {{-- Statut --}}
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>


                                {{-- Action --}}
                                <td class="px-5 py-4 text-right">
                                    <a
                                        href="{{ route('admin.engagements.show', [$application->type, $application->id]) }}"
                                        class="inline-flex h-9 items-center gap-2 rounded-lg border border-border bg-background px-3 text-sm font-medium text-foreground transition hover:bg-secondary"
                                    >
                                        <x-icon name="eye" class="h-4 w-4" />
                                        Voir
                                    </a>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">

                                    <div class="mx-auto flex max-w-md flex-col items-center">

                                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-muted-foreground">
                                            <x-icon name="search-x" class="h-7 w-7" />
                                        </div>

                                        <h3 class="mt-4 font-semibold text-foreground">
                                            Aucune candidature trouvée
                                        </h3>

                                        <p class="mt-1 text-sm text-muted-foreground">
                                            Aucune candidature ne correspond aux critères sélectionnés.
                                        </p>

                                        @if(request()->hasAny(['search', 'type', 'status']))
                                            <a
                                                href="{{ route('admin.engagements.index') }}"
                                                class="mt-4 inline-flex items-center gap-2 rounded-xl bg-primary px-4 py-2 text-sm font-semibold text-primary-foreground"
                                            >
                                                Réinitialiser les filtres
                                            </a>
                                        @endif

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Mobile --}}
            <div class="divide-y divide-border lg:hidden">

                @forelse($applications as $application)

                    @php
                        $isPartner = $application->type === 'partner';

                        $displayName = $isPartner
                            ? $application->organization
                            : $application->name;

                        $secondary = $isPartner
                            ? $application->contact_name
                            : $application->profession;

                        $location = $isPartner
                            ? $application->country
                            : $application->city_country;

                        $statusClasses = match($application->status) {
                            'pending' => 'bg-amber-500/10 text-amber-700 dark:text-amber-400',
                            'reviewing' => 'bg-blue-500/10 text-blue-700 dark:text-blue-400',
                            'accepted' => 'bg-green-500/10 text-green-700 dark:text-green-400',
                            'rejected' => 'bg-red-500/10 text-red-700 dark:text-red-400',
                            default => 'bg-secondary text-muted-foreground',
                        };

                        $statusLabel = match($application->status) {
                            'pending' => 'En attente',
                            'reviewing' => 'En cours d’étude',
                            'accepted' => 'Acceptée',
                            'rejected' => 'Refusée',
                            default => ucfirst($application->status),
                        };
                    @endphp

                    <div class="p-5">

                        <div class="flex items-start gap-3">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl {{ $isPartner ? 'bg-blue-500/10 text-blue-600' : 'bg-purple-500/10 text-purple-600' }}">
                                <x-icon
                                    name="{{ $isPartner ? 'building-2' : 'heart-handshake' }}"
                                    class="h-5 w-5"
                                />
                            </div>

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="font-semibold text-foreground">
                                        {{ $displayName }}
                                    </h3>

                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>
                                </div>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    {{ $secondary }}
                                </p>

                            </div>

                        </div>


                        <div class="mt-4 grid grid-cols-1 gap-2 text-sm">

                            <div class="flex items-center gap-2 text-muted-foreground">
                                <x-icon name="mail" class="h-4 w-4 shrink-0" />
                                <span class="truncate">{{ $application->email }}</span>
                            </div>

                            <div class="flex items-center gap-2 text-muted-foreground">
                                <x-icon name="phone" class="h-4 w-4 shrink-0" />
                                <span>{{ $application->phone }}</span>
                            </div>

                            <div class="flex items-center gap-2 text-muted-foreground">
                                <x-icon name="map-pin" class="h-4 w-4 shrink-0" />
                                <span>{{ $location }}</span>
                            </div>

                        </div>


                        <div class="mt-4 flex items-center justify-between border-t border-border pt-4">

                            <span class="text-xs text-muted-foreground">
                                {{ $application->created_at?->format('d/m/Y à H:i') }}
                            </span>

                            <a
                                href="{{ route('admin.engagements.show', [$application->type, $application->id]) }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-primary px-3 py-2 text-sm font-semibold text-primary-foreground"
                            >
                                <x-icon name="eye" class="h-4 w-4" />
                                Consulter
                            </a>

                        </div>

                    </div>

                @empty

                    <div class="px-5 py-16 text-center">

                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-secondary text-muted-foreground">
                            <x-icon name="search-x" class="h-7 w-7" />
                        </div>

                        <h3 class="mt-4 font-semibold text-foreground">
                            Aucune candidature
                        </h3>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Aucun résultat ne correspond aux filtres.
                        </p>

                    </div>

                @endforelse

            </div>


            {{-- Pagination --}}
            @if($applications->hasPages())
                <div class="border-t border-border px-5 py-4">
                    {{ $applications->links() }}
                </div>
            @endif

        </div>

    </div>

</x-layouts.admin>
