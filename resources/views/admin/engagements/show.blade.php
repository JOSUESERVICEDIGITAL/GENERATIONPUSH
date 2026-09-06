<x-layouts.admin title="Détail de la candidature">

    @php
        $isPartner = $application->type === 'partner';

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

        $availabilityLabel = match($application->availability ?? null) {
            'part_time' => 'Temps partiel',
            'events_only' => 'Uniquement lors des événements',
            'full_time' => 'Temps plein',
            default => $application->availability ?? '—',
        };

        $budgetLabel = match($application->budget ?? null) {
            'less_5000' => 'Moins de 5 000',
            '5000_20000' => '5 000 – 20 000',
            'more_20000' => 'Plus de 20 000',
            'discuss' => 'À discuter',
            default => '—',
        };

        $discoveryLabel = match($application->discovery_source ?? null) {
            'social_media' => 'Réseaux sociaux',
            'word_of_mouth' => 'Bouche-à-oreille',
            'gp_event' => 'Événement Generation PUSH',
            'other' => 'Autre',
            default => '—',
        };

        $partnershipLabels = [
            'event' => 'Événement',
            'content' => 'Contenu',
            'institutional' => 'Partenariat institutionnel',
            'sponsorship' => 'Sponsoring',
        ];

        $contributionLabels = [
            'communication' => 'Communication & réseaux sociaux',
            'events' => "Organisation d'événements",
            'content' => 'Création de contenu (vidéo, photo, design)',
            'coaching' => 'Coaching & formation',
            'web' => 'Développement web & digital',
            'translation' => 'Traduction français / anglais',
        ];

        $contributionAreas = $application->contribution_areas ?? [];

if (is_string($contributionAreas)) {
    $decoded = json_decode($contributionAreas, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $contributionAreas = $decoded;
    } else {
        $contributionAreas = [];
    }
}

if (!is_array($contributionAreas)) {
    $contributionAreas = [];
}
        $partnershipTypes = $application->partnership_types ?? [];

if (is_string($partnershipTypes)) {
    $decoded = json_decode($partnershipTypes, true);

    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
        $partnershipTypes = $decoded;
    } else {
        $partnershipTypes = [];
    }
}

if (!is_array($partnershipTypes)) {
    $partnershipTypes = [];
}
    @endphp


    <div class="space-y-6">

        {{-- ============================================================
             EN-TÊTE
        ============================================================= --}}
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">

            <div class="flex items-start gap-3">

                <a
                    href="{{ route('admin.engagements.index') }}"
                    class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-border bg-card text-muted-foreground transition hover:bg-secondary hover:text-foreground"
                    title="Retour aux candidatures"
                >
                    <x-icon name="arrow-left" class="h-5 w-5" />
                </a>

                <div>

                    <div class="flex flex-wrap items-center gap-2">

                        <h1 class="text-2xl font-bold tracking-tight text-foreground">
                            {{ $isPartner ? $application->organization : $application->name }}
                        </h1>

                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $statusClasses }}">
                            {{ $statusLabel }}
                        </span>

                    </div>

                    <p class="mt-1 text-sm text-muted-foreground">
                        {{ $isPartner ? 'Candidature partenaire' : 'Candidature bénévole' }}
                        · #{{ $application->id }}
                    </p>

                </div>

            </div>


            {{-- Type --}}
            <div>
                @if($isPartner)

                    <span class="inline-flex items-center gap-2 rounded-xl bg-blue-500/10 px-4 py-2 text-sm font-semibold text-blue-700 dark:text-blue-400">
                        <x-icon name="building-2" class="h-4 w-4" />
                        Partenaire
                    </span>

                @else

                    <span class="inline-flex items-center gap-2 rounded-xl bg-purple-500/10 px-4 py-2 text-sm font-semibold text-purple-700 dark:text-purple-400">
                        <x-icon name="heart-handshake" class="h-4 w-4" />
                        Bénévole
                    </span>

                @endif
            </div>

        </div>


        {{-- ============================================================
             MESSAGE
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
             CONTENU
        ============================================================= --}}
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">

            {{-- ========================================================
                 COLONNE PRINCIPALE
            ========================================================= --}}
            <div class="space-y-6 xl:col-span-2">


                {{-- ====================================================
                     PARTENAIRE
                ===================================================== --}}
                @if($isPartner)

                    {{-- Informations organisation --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-500/10 text-blue-600">
                                    <x-icon name="building-2" class="h-5 w-5" />
                                </div>

                                <div>
                                    <h2 class="font-semibold text-foreground">
                                        Informations sur l'organisation
                                    </h2>

                                    <p class="text-sm text-muted-foreground">
                                        Identité et informations professionnelles
                                    </p>
                                </div>

                            </div>
                        </div>


                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            {{-- Organisation --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Organisation
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->organization }}
                                </p>
                            </div>


                            {{-- Secteur --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Secteur
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->sector }}
                                </p>
                            </div>


                            {{-- Contact --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Personne de contact
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->contact_name }}
                                </p>
                            </div>


                            {{-- Poste --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Fonction / poste
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->position }}
                                </p>
                            </div>


                            {{-- Pays --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Pays
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->country }}
                                </p>
                            </div>


                            {{-- Site --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Site web
                                </p>

                                @if($application->website)

                                    <a
                                        href="{{ $application->website }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:underline"
                                    >
                                        {{ $application->website }}
                                        <x-icon name="external-link" class="h-3.5 w-3.5" />
                                    </a>

                                @else

                                    <p class="mt-1 text-sm text-muted-foreground">
                                        Non renseigné
                                    </p>

                                @endif
                            </div>

                        </div>

                    </div>


                    {{-- Coordonnées --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-secondary text-muted-foreground">
                                    <x-icon name="contact" class="h-5 w-5" />
                                </div>

                                <div>
                                    <h2 class="font-semibold text-foreground">
                                        Coordonnées
                                    </h2>

                                    <p class="text-sm text-muted-foreground">
                                        Informations de contact
                                    </p>
                                </div>

                            </div>
                        </div>


                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Adresse email
                                </p>

                                <a
                                    href="mailto:{{ $application->email }}"
                                    class="mt-1 inline-flex text-sm font-medium text-primary hover:underline"
                                >
                                    {{ $application->email }}
                                </a>
                            </div>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Téléphone / WhatsApp
                                </p>

                                <a
                                    href="tel:{{ $application->phone }}"
                                    class="mt-1 inline-flex text-sm font-medium text-foreground hover:underline"
                                >
                                    {{ $application->phone }}
                                </a>
                            </div>

                        </div>

                    </div>


                    {{-- Partenariat --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <h2 class="font-semibold text-foreground">
                                Proposition de partenariat
                            </h2>

                            <p class="text-sm text-muted-foreground">
                                Type de collaboration et projet proposé
                            </p>
                        </div>


                        <div class="space-y-6 p-5">

                            {{-- Types --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Types de partenariat souhaités
                                </p>

                                <div class="mt-3 flex flex-wrap gap-2">

                                    @forelse($partnershipTypes as $type)

                                        <span class="inline-flex rounded-full bg-primary/10 px-3 py-1.5 text-xs font-semibold text-primary">
                                            {{ $partnershipLabels[$type] ?? $type }}
                                        </span>

                                    @empty

                                        <span class="text-sm text-muted-foreground">
                                            Aucun type renseigné.
                                        </span>

                                    @endforelse

                                </div>

                            </div>


                            {{-- Autre --}}
                            @if($application->partnership_other)

                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        Autre type de partenariat
                                    </p>

                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-foreground">
                                        {{ $application->partnership_other }}
                                    </p>
                                </div>

                            @endif


                            {{-- Projet --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Projet / message de collaboration
                                </p>

                                <div class="mt-2 rounded-xl bg-secondary/50 p-4">
                                    <p class="whitespace-pre-line text-sm leading-7 text-foreground">
                                        {{ $application->collaboration_project }}
                                    </p>
                                </div>

                            </div>


                            {{-- Budget --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Budget envisagé
                                </p>

                                <p class="mt-2 text-sm font-medium text-foreground">
                                    {{ $budgetLabel }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Découverte --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <h2 class="font-semibold text-foreground">
                                Origine de la candidature
                            </h2>

                            <p class="text-sm text-muted-foreground">
                                Comment l'organisation a découvert Generation PUSH
                            </p>
                        </div>


                        <div class="p-5">

                            <p class="text-sm font-medium text-foreground">
                                {{ $discoveryLabel }}
                            </p>

                            @if($application->discovery_other)

                                <p class="mt-2 text-sm text-muted-foreground">
                                    {{ $application->discovery_other }}
                                </p>

                            @endif

                        </div>

                    </div>


                {{-- ====================================================
                     BÉNÉVOLE
                ===================================================== --}}
                @else

                    {{-- Identité --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-500/10 text-purple-600">
                                    <x-icon name="user-round" class="h-5 w-5" />
                                </div>

                                <div>
                                    <h2 class="font-semibold text-foreground">
                                        Informations personnelles
                                    </h2>

                                    <p class="text-sm text-muted-foreground">
                                        Identité et situation du candidat
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            {{-- Nom --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Prénom et nom
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->name }}
                                </p>
                            </div>


                            {{-- Age --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Âge
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->age }} ans
                                </p>
                            </div>


                            {{-- Profession --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Profession
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->profession }}
                                </p>
                            </div>


                            {{-- Ville --}}
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Ville / pays
                                </p>

                                <p class="mt-1 text-sm font-medium text-foreground">
                                    {{ $application->city_country }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- Coordonnées --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <h2 class="font-semibold text-foreground">
                                Coordonnées
                            </h2>
                        </div>


                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Adresse email
                                </p>

                                <a
                                    href="mailto:{{ $application->email }}"
                                    class="mt-1 inline-flex text-sm font-medium text-primary hover:underline"
                                >
                                    {{ $application->email }}
                                </a>
                            </div>


                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Téléphone / WhatsApp
                                </p>

                                <a
                                    href="tel:{{ $application->phone }}"
                                    class="mt-1 inline-flex text-sm font-medium text-foreground hover:underline"
                                >
                                    {{ $application->phone }}
                                </a>
                            </div>


                            <div class="md:col-span-2">

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Réseau social
                                </p>

                                @if($application->social_link)

                                    <a
                                        href="{{ $application->social_link }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="mt-1 inline-flex items-center gap-1.5 text-sm font-medium text-primary hover:underline"
                                    >
                                        {{ $application->social_link }}
                                        <x-icon name="external-link" class="h-3.5 w-3.5" />
                                    </a>

                                @else

                                    <p class="mt-1 text-sm text-muted-foreground">
                                        Non renseigné
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- Contributions --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <h2 class="font-semibold text-foreground">
                                Domaines de contribution
                            </h2>

                            <p class="text-sm text-muted-foreground">
                                Comment le candidat souhaite contribuer à Generation PUSH
                            </p>
                        </div>


                        <div class="space-y-5 p-5">

                            <div class="flex flex-wrap gap-2">

                                @forelse($contributionAreas as $area)

                                    <span class="inline-flex rounded-full bg-purple-500/10 px-3 py-1.5 text-xs font-semibold text-purple-700 dark:text-purple-400">
                                        {{ $contributionLabels[$area] ?? $area }}
                                    </span>

                                @empty

                                    <span class="text-sm text-muted-foreground">
                                        Aucun domaine renseigné.
                                    </span>

                                @endforelse

                            </div>


                            @if($application->contribution_other)

                                <div>

                                    <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                        Autre domaine
                                    </p>

                                    <p class="mt-2 whitespace-pre-line text-sm leading-6 text-foreground">
                                        {{ $application->contribution_other }}
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>


                    {{-- Motivation et compétences --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <h2 class="font-semibold text-foreground">
                                Motivation et compétences
                            </h2>
                        </div>


                        <div class="space-y-6 p-5">

                            {{-- Motivation --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Motivation
                                </p>

                                <div class="mt-2 rounded-xl bg-secondary/50 p-4">
                                    <p class="whitespace-pre-line text-sm leading-7 text-foreground">
                                        {{ $application->motivation }}
                                    </p>
                                </div>

                            </div>


                            {{-- Skills --}}
                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Compétences
                                </p>

                                <div class="mt-2 rounded-xl bg-secondary/50 p-4">
                                    <p class="whitespace-pre-line text-sm leading-7 text-foreground">
                                        {{ $application->skills }}
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Disponibilité --}}
                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">
                            <h2 class="font-semibold text-foreground">
                                Disponibilité et expérience
                            </h2>
                        </div>


                        <div class="grid grid-cols-1 gap-5 p-5 md:grid-cols-2">

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Disponibilité
                                </p>

                                <p class="mt-2 text-sm font-medium text-foreground">
                                    {{ $availabilityLabel }}
                                </p>

                            </div>


                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground">
                                    Participation antérieure
                                </p>

                                <div class="mt-2">

                                    @if($application->participated_before)

                                        <span class="inline-flex rounded-full bg-green-500/10 px-3 py-1.5 text-xs font-semibold text-green-700 dark:text-green-400">
                                            Oui
                                        </span>

                                    @else

                                        <span class="inline-flex rounded-full bg-secondary px-3 py-1.5 text-xs font-semibold text-muted-foreground">
                                            Non
                                        </span>

                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ====================================================
                     DOCUMENT
                ===================================================== --}}
                @if($application->document_path)

                    <div class="rounded-2xl border border-border bg-card shadow-sm">

                        <div class="border-b border-border px-5 py-4">

                            <div class="flex items-center gap-3">

                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary/10 text-primary">
                                    <x-icon name="file-text" class="h-5 w-5" />
                                </div>

                                <div>
                                    <h2 class="font-semibold text-foreground">
                                        Document joint
                                    </h2>

                                    <p class="text-sm text-muted-foreground">
                                        Document transmis avec la candidature
                                    </p>
                                </div>

                            </div>

                        </div>


                        <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex min-w-0 items-center gap-3">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-secondary text-muted-foreground">
                                    <x-icon name="file" class="h-5 w-5" />
                                </div>

                                <div class="min-w-0">

                                    <p class="truncate text-sm font-medium text-foreground">
                                        {{ $application->document_name ?: basename($application->document_path) }}
                                    </p>

                                    <p class="truncate text-xs text-muted-foreground">
                                        {{ $application->document_path }}
                                    </p>

                                </div>

                            </div>


                            <div class="flex shrink-0 gap-2">

                                <a
                                    href="{{ route('admin.engagements.document.preview', [$application->type, $application->id]) }}"
                                    target="_blank"
                                    class="inline-flex h-10 items-center gap-2 rounded-xl border border-border bg-background px-4 text-sm font-semibold text-foreground transition hover:bg-secondary"
                                >
                                    <x-icon name="eye" class="h-4 w-4" />
                                    Aperçu
                                </a>

                                <a
                                    href="{{ route('admin.engagements.document.download', [$application->type, $application->id]) }}"
                                    class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition hover:opacity-90"
                                >
                                    <x-icon name="download" class="h-4 w-4" />
                                    Télécharger
                                </a>

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ====================================================
                     NOTES ADMINISTRATIVES
                ===================================================== --}}
                <div class="rounded-2xl border border-border bg-card shadow-sm">

                    <div class="border-b border-border px-5 py-4">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500/10 text-amber-600">
                                <x-icon name="notebook-pen" class="h-5 w-5" />
                            </div>

                            <div>
                                <h2 class="font-semibold text-foreground">
                                    Notes administratives
                                </h2>

                                <p class="text-sm text-muted-foreground">
                                    Informations internes visibles uniquement dans le back-office
                                </p>
                            </div>

                        </div>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('admin.engagements.notes', [$application->type, $application->id]) }}"
                        class="p-5"
                    >

                        @csrf
                        @method('PATCH')

                        <textarea
                            name="admin_notes"
                            rows="6"
                            placeholder="Ajouter une note interne..."
                            class="w-full rounded-xl border border-border bg-background px-4 py-3 text-sm leading-6 text-foreground outline-none transition placeholder:text-muted-foreground focus:border-primary focus:ring-2 focus:ring-primary/20"
                        >{{ old('admin_notes', $application->admin_notes) }}</textarea>

                        @error('admin_notes')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <div class="mt-4 flex justify-end">

                            <button
                                type="submit"
                                class="inline-flex h-10 items-center gap-2 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition hover:opacity-90"
                            >
                                <x-icon name="save" class="h-4 w-4" />
                                Enregistrer les notes
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- ========================================================
                 COLONNE DROITE
            ========================================================= --}}
            <div class="space-y-6">


                {{-- ====================================================
                     GESTION DU STATUT
                ===================================================== --}}
                <div class="rounded-2xl border border-border bg-card shadow-sm">

                    <div class="border-b border-border px-5 py-4">

                        <h2 class="font-semibold text-foreground">
                            Gestion de la candidature
                        </h2>

                        <p class="text-sm text-muted-foreground">
                            Modifier le statut
                        </p>

                    </div>


                    <form
                        method="POST"
                        action="{{ route('admin.engagements.status', [$application->type, $application->id]) }}"
                        class="p-5"
                    >

                        @csrf
                        @method('PATCH')

                        <label class="mb-2 block text-sm font-medium text-foreground">
                            Statut
                        </label>

                        <select
                            name="status"
                            class="h-11 w-full rounded-xl border border-border bg-background px-3 text-sm text-foreground outline-none transition focus:border-primary focus:ring-2 focus:ring-primary/20"
                        >

                            <option value="pending" @selected($application->status === 'pending')}>
                                En attente
                            </option>

                            <option value="reviewing" @selected($application->status === 'reviewing')}>
                                En cours d'étude
                            </option>

                            <option value="accepted" @selected($application->status === 'accepted')}>
                                Acceptée
                            </option>

                            <option value="rejected" @selected($application->status === 'rejected')}>
                                Refusée
                            </option>

                        </select>

                        @error('status')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror

                        <button
                            type="submit"
                            class="mt-4 inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-primary px-4 text-sm font-semibold text-primary-foreground transition hover:opacity-90"
                        >
                            <x-icon name="save" class="h-4 w-4" />
                            Mettre à jour
                        </button>

                    </form>

                </div>


                {{-- ====================================================
                     RÉSUMÉ
                ===================================================== --}}
                <div class="rounded-2xl border border-border bg-card shadow-sm">

                    <div class="border-b border-border px-5 py-4">
                        <h2 class="font-semibold text-foreground">
                            Résumé
                        </h2>
                    </div>


                    <div class="divide-y divide-border">

                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-muted-foreground">
                                Type
                            </span>

                            <span class="text-right text-sm font-medium text-foreground">
                                {{ $isPartner ? 'Partenaire' : 'Bénévole' }}
                            </span>
                        </div>


                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-muted-foreground">
                                ID
                            </span>

                            <span class="text-sm font-medium text-foreground">
                                #{{ $application->id }}
                            </span>
                        </div>


                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-muted-foreground">
                                Statut
                            </span>

                            <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $statusClasses }}">
                                {{ $statusLabel }}
                            </span>
                        </div>


                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-muted-foreground">
                                Créée le
                            </span>

                            <span class="text-right text-sm font-medium text-foreground">
                                {{ $application->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </span>
                        </div>


                        <div class="flex items-center justify-between gap-4 px-5 py-4">
                            <span class="text-sm text-muted-foreground">
                                Modifiée le
                            </span>

                            <span class="text-right text-sm font-medium text-foreground">
                                {{ $application->updated_at?->format('d/m/Y H:i') ?? '—' }}
                            </span>
                        </div>

                    </div>

                </div>


                {{-- ====================================================
                     ACTIONS RAPIDES
                ===================================================== --}}
                <div class="rounded-2xl border border-border bg-card p-5 shadow-sm">

                    <h2 class="font-semibold text-foreground">
                        Actions rapides
                    </h2>


                    <div class="mt-4 space-y-2">

                        <a
                            href="mailto:{{ $application->email }}"
                            class="flex h-10 w-full items-center gap-3 rounded-xl border border-border bg-background px-4 text-sm font-medium text-foreground transition hover:bg-secondary"
                        >
                            <x-icon name="mail" class="h-4 w-4 text-muted-foreground" />
                            Envoyer un email
                        </a>


                        <a
                            href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $application->phone) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="flex h-10 w-full items-center gap-3 rounded-xl border border-border bg-background px-4 text-sm font-medium text-foreground transition hover:bg-secondary"
                        >
                            <x-icon name="message-circle" class="h-4 w-4 text-muted-foreground" />
                            Contacter sur WhatsApp
                        </a>

                    </div>

                </div>


                {{-- ====================================================
                     ZONE DANGEREUSE
                ===================================================== --}}
                <div class="rounded-2xl border border-red-200 bg-red-50/50 shadow-sm dark:border-red-900/50 dark:bg-red-950/10">

                    <div class="border-b border-red-200 px-5 py-4 dark:border-red-900/50">

                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-500/10 text-red-600">
                                <x-icon name="triangle-alert" class="h-5 w-5" />
                            </div>

                            <div>
                                <h2 class="font-semibold text-red-700 dark:text-red-400">
                                    Zone dangereuse
                                </h2>

                                <p class="text-sm text-red-600/80 dark:text-red-400/70">
                                    Cette action est irréversible.
                                </p>
                            </div>

                        </div>

                    </div>


                    <div class="p-5">

                        <p class="text-sm leading-6 text-red-700/80 dark:text-red-300/80">
                            La suppression supprimera définitivement cette candidature et toutes
                            les informations qui lui sont associées.
                        </p>


                        <form
                            method="POST"
                            action="{{ route('admin.engagements.destroy', [$application->type, $application->id]) }}"
                            class="mt-4"
                            onsubmit="return confirm('Êtes-vous absolument sûr de vouloir supprimer cette candidature ? Cette action est irréversible.');"
                        >

                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="inline-flex h-10 w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 text-sm font-semibold text-white transition hover:bg-red-700"
                            >
                                <x-icon name="trash-2" class="h-4 w-4" />
                                Supprimer définitivement
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-layouts.admin>
