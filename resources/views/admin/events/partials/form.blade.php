@php
    $editing = isset($event) && $event->exists;

    $selectedFormat = old(
        'format',
        $event->format ?? 'physical'
    );

    $isFree = (bool) old(
        'is_free',
        isset($event) ? $event->is_free : true
    );

    $reservationEnabled = (bool) old(
        'reservation_enabled',
        isset($event) ? $event->reservation_enabled : true
    );

    $featured = (bool) old(
        'featured',
        $event->featured ?? false
    );
@endphp


<div
    x-data="{
        format: @js($selectedFormat),
        isFree: @js($isFree),
        reservationEnabled: @js($reservationEnabled)
    }"
    class="space-y-6"
>

    {{-- ============================================================
         ERREURS
    ============================================================ --}}

    @if($errors->any())

        <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-900/50 dark:bg-red-950/30">

            <div class="flex gap-3">

                <x-icon
                    name="circle-alert"
                    class="w-5 h-5 text-red-600 shrink-0 mt-0.5"
                />

                <div>

                    <p class="font-semibold text-red-700 dark:text-red-400">
                        Le formulaire contient des erreurs.
                    </p>

                    <ul class="mt-2 list-disc ps-5 text-sm text-red-600 dark:text-red-400 space-y-1">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
         INFORMATIONS GÉNÉRALES
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Informations générales
            </h2>

            <p class="text-sm text-muted-foreground mt-1">
                Définissez l'identité et la catégorie de l'événement.
            </p>

        </div>


        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- CATÉGORIE --}}

            <div>

                <label
                    for="event_category_id"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Catégorie
                    <span class="text-destructive">*</span>
                </label>

                <select
                    id="event_category_id"
                    name="event_category_id"
                    required
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

                    <option value="">
                        Sélectionner une catégorie
                    </option>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            @selected(
                                (string) old(
                                    'event_category_id',
                                    $event->event_category_id ?? ''
                                ) === (string) $category->id
                            )
                        >
                            {{ $category->name }}
                        </option>

                    @endforeach

                </select>

                @error('event_category_id')
                    <p class="text-xs text-destructive mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- TITRE --}}

            <div>

                <label
                    for="title"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Titre
                    <span class="text-destructive">*</span>
                </label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title', $event->title ?? '') }}"
                    required
                    placeholder="Ex : PushConnect 2026"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

                @error('title')
                    <p class="text-xs text-destructive mt-1">
                        {{ $message }}
                    </p>
                @enderror

            </div>


            {{-- SOUS-TITRE --}}

            <div class="md:col-span-2">

                <label
                    for="subtitle"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Sous-titre
                </label>

                <input
                    type="text"
                    id="subtitle"
                    name="subtitle"
                    value="{{ old('subtitle', $event->subtitle ?? '') }}"
                    placeholder="Une courte phrase pour présenter l'événement"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

            </div>


            {{-- DESCRIPTION COURTE --}}

            <div class="md:col-span-2">

                <label
                    for="short_description"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Description courte
                </label>

                <textarea
                    id="short_description"
                    name="short_description"
                    rows="3"
                    placeholder="Résumé destiné aux cartes et listes d'événements..."
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent resize-y"
                >{{ old('short_description', $event->short_description ?? '') }}</textarea>

            </div>


            {{-- DESCRIPTION --}}

            <div class="md:col-span-2">

                <label
                    for="description"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Description complète
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="8"
                    placeholder="Présentez le programme, les objectifs et toutes les informations utiles..."
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent resize-y"
                >{{ old('description', $event->description ?? '') }}</textarea>

            </div>

        </div>

    </section>


    {{-- ============================================================
         MÉDIAS
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Médias
            </h2>

            <p class="text-sm text-muted-foreground mt-1">
                Images utilisées pour présenter l'événement.
            </p>

        </div>


        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- IMAGE --}}

            <div>

                <label
                    for="image"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Image principale
                </label>

                @if($editing && $event->image)

                    <div class="mb-3">

                        <img
                            src="{{ asset('storage/' . $event->image) }}"
                            alt="{{ $event->title }}"
                            class="w-full max-w-xs h-40 rounded-lg object-cover border border-border"
                        >

                    </div>

                @endif

                <input
                    type="file"
                    id="image"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-muted-foreground file:me-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium hover:file:bg-secondary/80"
                >

                <p class="text-xs text-muted-foreground mt-2">
                    JPG, PNG ou WEBP.
                </p>

            </div>


            {{-- BANNIÈRE --}}

            <div>

                <label
                    for="banner"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Bannière
                </label>

                @if($editing && $event->banner)

                    <div class="mb-3">

                        <img
                            src="{{ asset('storage/' . $event->banner) }}"
                            alt="Bannière {{ $event->title }}"
                            class="w-full max-w-xs h-40 rounded-lg object-cover border border-border"
                        >

                    </div>

                @endif

                <input
                    type="file"
                    id="banner"
                    name="banner"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-muted-foreground file:me-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium hover:file:bg-secondary/80"
                >

            </div>

        </div>

    </section>


    {{-- ============================================================
         INTERVENANT
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Intervenant
            </h2>

            <p class="text-sm text-muted-foreground mt-1">
                Informations sur la personne qui anime ou présente l'événement.
            </p>

        </div>


        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>

                <label
                    for="speaker"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Nom de l'intervenant
                </label>

                <input
                    type="text"
                    id="speaker"
                    name="speaker"
                    value="{{ old('speaker', $event->speaker ?? '') }}"
                    placeholder="Nom et prénom"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

            </div>


            <div>

                <label
                    for="speaker_title"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Fonction / titre
                </label>

                <input
                    type="text"
                    id="speaker_title"
                    name="speaker_title"
                    value="{{ old('speaker_title', $event->speaker_title ?? '') }}"
                    placeholder="Ex : Entrepreneur, Coach, CEO..."
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

            </div>


            <div class="md:col-span-2">

                <label
                    for="speaker_image"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Photo de l'intervenant
                </label>

                @if($editing && $event->speaker_image)

                    <img
                        src="{{ asset('storage/' . $event->speaker_image) }}"
                        alt="{{ $event->speaker }}"
                        class="w-20 h-20 object-cover rounded-full border border-border mb-3"
                    >

                @endif

                <input
                    type="file"
                    id="speaker_image"
                    name="speaker_image"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="block w-full text-sm text-muted-foreground file:me-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium"
                >

            </div>

        </div>

    </section>


    {{-- ============================================================
         DATE ET HEURE
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Date et heure
            </h2>

        </div>


        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>

                <label
                    for="starts_at"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Début
                    <span class="text-destructive">*</span>
                </label>

                <input
                    type="datetime-local"
                    id="starts_at"
                    name="starts_at"
                    required
                    value="{{ old(
                        'starts_at',
                        isset($event) && $event->starts_at
                            ? $event->starts_at->format('Y-m-d\TH:i')
                            : ''
                    ) }}"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

            </div>


            <div>

                <label
                    for="ends_at"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Fin
                </label>

                <input
                    type="datetime-local"
                    id="ends_at"
                    name="ends_at"
                    value="{{ old(
                        'ends_at',
                        isset($event) && $event->ends_at
                            ? $event->ends_at->format('Y-m-d\TH:i')
                            : ''
                    ) }}"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

            </div>

        </div>

    </section>


    {{-- ============================================================
         FORMAT ET LOCALISATION
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Format et localisation
            </h2>

        </div>


        <div class="p-5 space-y-5">

            {{-- FORMAT --}}

            <div>

                <label
                    for="format"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Format
                    <span class="text-destructive">*</span>
                </label>

                <select
                    id="format"
                    name="format"
                    x-model="format"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

                    <option value="physical">
                        Présentiel
                    </option>

                    <option value="online">
                        En ligne
                    </option>

                    <option value="hybrid">
                        Hybride
                    </option>

                </select>

            </div>


            {{-- LOCALISATION PHYSIQUE --}}

            <div
                x-show="format === 'physical' || format === 'hybrid'"
                x-cloak
                class="grid grid-cols-1 md:grid-cols-2 gap-5"
            >

                <div>

                    <label
                        for="venue"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Lieu
                    </label>

                    <input
                        type="text"
                        id="venue"
                        name="venue"
                        value="{{ old('venue', $event->venue ?? '') }}"
                        placeholder="Ex : Salle de conférence..."
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>


                <div>

                    <label
                        for="address"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Adresse
                    </label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        value="{{ old('address', $event->address ?? '') }}"
                        placeholder="Adresse complète"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>


                <div>

                    <label
                        for="city"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Ville
                    </label>

                    <input
                        type="text"
                        id="city"
                        name="city"
                        value="{{ old('city', $event->city ?? '') }}"
                        placeholder="Ex : Ouagadougou"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>


                <div>

                    <label
                        for="country"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Pays
                    </label>

                    <input
                        type="text"
                        id="country"
                        name="country"
                        value="{{ old('country', $event->country ?? '') }}"
                        placeholder="Ex : Burkina Faso"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>

            </div>


            {{-- URL EN LIGNE --}}

            <div
                x-show="format === 'online' || format === 'hybrid'"
                x-cloak
            >

                <label
                    for="online_url"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Lien de participation en ligne
                </label>

                <input
                    type="url"
                    id="online_url"
                    name="online_url"
                    value="{{ old('online_url', $event->online_url ?? '') }}"
                    placeholder="https://..."
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

            </div>

        </div>

    </section>


    {{-- ============================================================
         RÉSERVATIONS
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Réservations
            </h2>

            <p class="text-sm text-muted-foreground mt-1">
                Configurez l'inscription et le nombre de places.
            </p>

        </div>


        <div class="p-5 space-y-5">

            <input
                type="hidden"
                name="reservation_enabled"
                value="0"
            >

            <label class="flex items-center justify-between gap-4 cursor-pointer">

                <div>

                    <p class="font-medium text-foreground">
                        Activer les réservations
                    </p>

                    <p class="text-sm text-muted-foreground">
                        Les visiteurs pourront réserver une place.
                    </p>

                </div>


                <input
                    type="checkbox"
                    name="reservation_enabled"
                    value="1"
                    x-model="reservationEnabled"
                    @checked($reservationEnabled)
                    class="w-5 h-5 rounded border-border text-accent focus:ring-accent"
                >

            </label>


            <div
                x-show="reservationEnabled"
                x-cloak
                class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-3"
            >

                <div>

                    <label
                        for="capacity"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Capacité
                    </label>

                    <input
                        type="number"
                        min="1"
                        id="capacity"
                        name="capacity"
                        value="{{ old('capacity', $event->capacity ?? '') }}"
                        placeholder="Ex : 500"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                    <p class="text-xs text-muted-foreground mt-1">
                        Laisser vide pour illimité.
                    </p>

                </div>


                <div>

                    <label
                        for="reservation_starts_at"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Ouverture
                    </label>

                    <input
                        type="datetime-local"
                        id="reservation_starts_at"
                        name="reservation_starts_at"
                        value="{{ old(
                            'reservation_starts_at',
                            isset($event) && $event->reservation_starts_at
                                ? $event->reservation_starts_at->format('Y-m-d\TH:i')
                                : ''
                        ) }}"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>


                <div>

                    <label
                        for="reservation_ends_at"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Fermeture
                    </label>

                    <input
                        type="datetime-local"
                        id="reservation_ends_at"
                        name="reservation_ends_at"
                        value="{{ old(
                            'reservation_ends_at',
                            isset($event) && $event->reservation_ends_at
                                ? $event->reservation_ends_at->format('Y-m-d\TH:i')
                                : ''
                        ) }}"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
         TARIFICATION
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Tarification
            </h2>

        </div>


        <div class="p-5 space-y-5">

            <input
                type="hidden"
                name="is_free"
                value="0"
            >

            <label class="flex items-center justify-between gap-4 cursor-pointer">

                <div>

                    <p class="font-medium text-foreground">
                        Événement gratuit
                    </p>

                    <p class="text-sm text-muted-foreground">
                        Désactivez cette option si une participation financière est demandée.
                    </p>

                </div>

                <input
                    type="checkbox"
                    name="is_free"
                    value="1"
                    x-model="isFree"
                    @checked($isFree)
                    class="w-5 h-5 rounded border-border text-accent focus:ring-accent"
                >

            </label>


            <div
                x-show="!isFree"
                x-cloak
                class="grid grid-cols-1 md:grid-cols-2 gap-5"
            >

                <div>

                    <label
                        for="price"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Prix
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        min="0"
                        id="price"
                        name="price"
                        value="{{ old('price', $event->price ?? 0) }}"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                </div>


                <div>

                    <label
                        for="currency"
                        class="block text-sm font-medium text-foreground mb-2"
                    >
                        Devise
                    </label>

                    <select
                        id="currency"
                        name="currency"
                        class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                    >

                        @foreach([
                            'XOF' => 'FCFA (XOF)',
                            'MAD' => 'Dirham marocain (MAD)',
                            'EUR' => 'Euro (EUR)',
                            'USD' => 'Dollar US (USD)',
                        ] as $code => $label)

                            <option
                                value="{{ $code }}"
                                @selected(
                                    old(
                                        'currency',
                                        $event->currency ?? 'XOF'
                                    ) === $code
                                )
                            >
                                {{ $label }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
         PUBLICATION
    ============================================================ --}}

    <section class="bg-card border border-border rounded-xl shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-border">

            <h2 class="font-semibold text-foreground">
                Publication
            </h2>

        </div>


        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>

                <label
                    for="status"
                    class="block text-sm font-medium text-foreground mb-2"
                >
                    Statut
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full px-3 py-2.5 rounded-lg border border-border bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-accent"
                >

                    <option
                        value="draft"
                        @selected(
                            old(
                                'status',
                                $event->status ?? 'draft'
                            ) === 'draft'
                        )
                    >
                        Brouillon
                    </option>

                    <option
                        value="published"
                        @selected(
                            old(
                                'status',
                                $event->status ?? 'draft'
                            ) === 'published'
                        )
                    >
                        Publié
                    </option>

                    <option
                        value="cancelled"
                        @selected(
                            old(
                                'status',
                                $event->status ?? 'draft'
                            ) === 'cancelled'
                        )
                    >
                        Annulé
                    </option>

                </select>

            </div>


            <div class="flex items-center">

                <div class="w-full">

                    <input
                        type="hidden"
                        name="featured"
                        value="0"
                    >

                    <label class="flex items-center justify-between gap-4 cursor-pointer border border-border rounded-lg p-4">

                        <div>

                            <p class="font-medium text-foreground">
                                Mettre à la une
                            </p>

                            <p class="text-sm text-muted-foreground mt-1">
                                L'événement pourra être davantage mis en avant sur le site.
                            </p>

                        </div>

                        <input
                            type="checkbox"
                            name="featured"
                            value="1"
                            @checked($featured)
                            class="w-5 h-5 rounded border-border text-accent focus:ring-accent"
                        >

                    </label>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
         ACTIONS
    ============================================================ --}}

    <div class="bg-card border border-border rounded-xl p-4 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">

        <a
            href="{{ route('admin.events.index') }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-border text-foreground hover:bg-secondary transition"
        >
            <x-icon
                name="arrow-left"
                class="w-4 h-4"
            />

            Annuler
        </a>


        <button
            type="submit"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition shadow-sm"
        >

            <x-icon
                name="{{ $editing ? 'save' : 'plus' }}"
                class="w-4 h-4"
            />

            {{ $editing ? 'Enregistrer les modifications' : 'Créer l’événement' }}

        </button>

    </div>

</div>
