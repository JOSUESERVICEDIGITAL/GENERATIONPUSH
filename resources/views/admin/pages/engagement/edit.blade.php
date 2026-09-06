<x-layouts.admin :title="$type === 'partner' ? 'Devenir partenaire' : 'Devenir bénévole'">

    @php
        $isPartner = $type === 'partner';

        $pageTitle = $isPartner
            ? 'Page Devenir Partenaire'
            : 'Page Devenir Bénévole';

        $pageDescription = $isPartner
            ? 'Gère le contenu public de la page dédiée aux partenaires de Generation PUSH.'
            : 'Gère le contenu public de la page dédiée aux bénévoles de Generation PUSH.';

        $publicRoute = $isPartner
            ? 'front.partner'
            : 'front.volunteer';
    @endphp


    {{-- ============================================================
         EN-TÊTE
    ============================================================ --}}

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

        <div>

            <div class="flex items-center gap-2 mb-2">

                <a
    href="{{ route('admin.pages.custom.index') }}"
    class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-accent transition"
>
    <x-icon name="arrow-left" class="w-4 h-4" />
    Retour aux pages
</a>

            </div>


            <div class="flex items-center gap-3">

                <div
                    class="w-11 h-11 rounded-xl flex items-center justify-center
                    {{ $isPartner
                        ? 'bg-blue-50 dark:bg-blue-950/30 text-blue-600'
                        : 'bg-green-50 dark:bg-green-950/30 text-green-600' }}"
                >

                    <x-icon
                        name="{{ $isPartner ? 'building-2' : 'heart-handshake' }}"
                        class="w-5 h-5"
                    />

                </div>


                <div>

                    <h1 class="text-2xl font-bold text-foreground">
                        {{ $pageTitle }}
                    </h1>

                    <p class="text-muted-foreground mt-1">
                        {{ $pageDescription }}
                    </p>

                </div>

            </div>

        </div>


        {{-- APERÇU PUBLIC --}}

        <a
            href="{{ route($publicRoute) }}"
            target="_blank"
            rel="noopener noreferrer"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-border bg-card text-sm font-medium text-foreground hover:border-accent hover:text-accent transition shrink-0"
        >

            <x-icon name="globe" class="w-4 h-4" />

            Voir la page publique

        </a>

    </div>


    {{-- ============================================================
         MESSAGE SUCCÈS
    ============================================================ --}}

    @if(session('success'))

        <div
            class="mt-6 flex items-start gap-3 bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl"
        >

            <x-icon name="circle-check" class="w-5 h-5 shrink-0 mt-0.5" />

            <div class="text-sm font-medium">
                {{ session('success') }}
            </div>

        </div>

    @endif


    {{-- ============================================================
         ERREURS
    ============================================================ --}}

    @if($errors->any())

        <div
            class="mt-6 bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-400 px-4 py-4 rounded-xl"
        >

            <div class="flex items-center gap-2 font-semibold text-sm mb-2">

                <x-icon name="circle-alert" class="w-5 h-5" />

                Impossible d'enregistrer les modifications.

            </div>


            <ul class="text-sm space-y-1 list-disc list-inside">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- ============================================================
         FORMULAIRE
    ============================================================ --}}

    <form
        method="POST"
        action="{{ route('admin.pages.engagement.update', $type) }}"
        enctype="multipart/form-data"
        class="mt-6 space-y-6"
    >

        @csrf
        @method('PUT')


        {{-- ========================================================
             VISIBILITÉ
        ========================================================= --}}

        <div class="bg-card border border-border rounded-xl overflow-hidden">

            <div class="px-6 py-4 border-b border-border">

                <h2 class="font-semibold text-foreground">
                    Publication
                </h2>

                <p class="text-sm text-muted-foreground mt-1">
                    Contrôle si cette page est accessible publiquement.
                </p>

            </div>


            <div class="p-6">

                <x-toggle
                    name="is_visible"
                    label="Afficher cette page publiquement"
                    :checked="$page->is_visible"
                />

            </div>

        </div>


        {{-- ========================================================
             CONTENU PRINCIPAL
        ========================================================= --}}

        <div class="bg-card border border-border rounded-xl overflow-hidden">

            <div class="px-6 py-4 border-b border-border">

                <h2 class="font-semibold text-foreground">
                    Contenu de la page
                </h2>

                <p class="text-sm text-muted-foreground mt-1">
                    Tous les textes ci-dessous peuvent être modifiés sans toucher au code.
                </p>

            </div>


            <div class="p-6 space-y-6">


                {{-- TITRE + SOUS-TITRE --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>

                        <x-input-label
                            for="title"
                            value="Titre principal"
                        />

                        <x-text-input
                            id="title"
                            name="title"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('title', $page->title)"
                            placeholder="{{ $isPartner ? 'Devenir partenaire' : 'Devenir bénévole' }}"
                            required
                        />

                        @error('title')

                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    <div>

                        <x-input-label
                            for="subtitle"
                            value="Sous-titre"
                        />

                        <x-text-input
                            id="subtitle"
                            name="subtitle"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('subtitle', $page->subtitle)"
                            placeholder="Un sous-titre accrocheur..."
                        />

                        @error('subtitle')

                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>

                </div>


                {{-- DESCRIPTION --}}

                <div>

                    <x-input-label
                        for="description"
                        value="Description"
                    />

                    <textarea
                        id="description"
                        name="description"
                        rows="6"
                        class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"
                        placeholder="Présentez cette page, son objectif et l'appel à rejoindre Generation PUSH..."
                    >{{ old('description', $page->description) }}</textarea>

                    @error('description')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                    <p class="text-xs text-muted-foreground mt-2">
                        Ce texte sera affiché sur la page publique.
                    </p>

                </div>


                {{-- AVANTAGES --}}

                <div>

                    <x-input-label
                        for="benefits"
                        value="Avantages"
                    />

                    <textarea
                        id="benefits"
                        name="benefits"
                        rows="7"
                        class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"
                        placeholder="Un avantage par ligne&#10;Visibilité auprès de notre communauté&#10;Accès aux événements&#10;Opportunités de collaboration"
                    >{{ old('benefits', $page->benefits) }}</textarea>

                    @error('benefits')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                    <p class="text-xs text-muted-foreground mt-2">
                        Écris <strong>un avantage par ligne</strong>. Le site les affichera automatiquement sous forme de liste.
                    </p>

                </div>


                {{-- ====================================================
                     IMAGE
                ===================================================== --}}

                <div>

                    <x-input-label
                        for="image"
                        value="Image d'illustration"
                    />


                    @if($page->imageUrl())

                        <div class="mt-3">

                            <div class="relative overflow-hidden rounded-xl border border-border bg-muted">

                                <img
                                    src="{{ $page->imageUrl() }}"
                                    alt="{{ $page->title }}"
                                    class="w-full max-h-80 object-cover"
                                >

                            </div>


                            <div class="flex items-center gap-2 mt-2 text-xs text-muted-foreground">

                                <x-icon name="image" class="w-4 h-4" />

                                <span>
                                    Image actuellement utilisée
                                </span>

                            </div>

                        </div>

                    @else

                        <div
                            class="mt-3 border-2 border-dashed border-border rounded-xl p-8 text-center"
                        >

                            <x-icon
                                name="image"
                                class="w-8 h-8 mx-auto text-muted-foreground mb-2"
                            />

                            <p class="text-sm text-muted-foreground">
                                Aucune image n'est actuellement définie.
                            </p>

                        </div>

                    @endif


                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/jpeg,image/png,image/webp,image/jpg"
                        class="mt-3 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium file:cursor-pointer"
                    >


                    @error('image')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror


                    <p class="text-xs text-muted-foreground mt-2">
                        Sélectionne une nouvelle image uniquement si tu souhaites remplacer l'image actuelle.
                    </p>

                </div>


                {{-- ====================================================
                     CTA
                ===================================================== --}}

                <div>

                    <x-input-label
                        for="cta_label"
                        value="Texte du bouton"
                    />

                    <x-text-input
                        id="cta_label"
                        name="cta_label"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old('cta_label', $page->cta_label)"
                        placeholder="{{ $isPartner ? 'Devenir partenaire' : 'Devenir bénévole' }}"
                    />

                    @error('cta_label')

                        <p class="text-xs text-red-500 mt-1">
                            {{ $message }}
                        </p>

                    @enderror

                    <p class="text-xs text-muted-foreground mt-2">
                        Exemple : « Rejoindre Generation PUSH », « Devenir partenaire », etc.
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================
             APERÇU DES VALEURS
        ========================================================= --}}

        <div class="bg-card border border-border rounded-xl overflow-hidden">

            <div class="px-6 py-4 border-b border-border">

                <div class="flex items-center gap-2">

                    <x-icon
                        name="eye"
                        class="w-5 h-5 text-accent"
                    />

                    <h2 class="font-semibold text-foreground">
                        Aperçu du contenu
                    </h2>

                </div>

                <p class="text-sm text-muted-foreground mt-1">
                    Vérifie rapidement les informations actuellement enregistrées.
                </p>

            </div>


            <div class="p-6">

                <div class="rounded-xl border border-border bg-background p-5">

                    <p class="text-xs uppercase tracking-widest text-accent font-bold mb-2">
                        {{ $isPartner ? 'PARTENARIAT' : 'BÉNÉVOLAT' }}
                    </p>


                    <h3 class="text-xl font-bold text-foreground">
                        {{ $page->title ?: 'Titre non renseigné' }}
                    </h3>


                    @if($page->subtitle)

                        <p class="text-muted-foreground mt-2">
                            {{ $page->subtitle }}
                        </p>

                    @endif


                    @if($page->description)

                        <div class="mt-4 text-sm text-muted-foreground whitespace-pre-line">
                            {{ $page->description }}
                        </div>

                    @endif


                    @if($page->benefitsList())

                        <div class="mt-5">

                            <p class="text-sm font-semibold text-foreground mb-3">
                                Avantages
                            </p>

                            <div class="space-y-2">

                                @foreach($page->benefitsList() as $benefit)

                                    <div class="flex items-start gap-2 text-sm text-muted-foreground">

                                        <x-icon
                                            name="check"
                                            class="w-4 h-4 text-accent shrink-0 mt-0.5"
                                        />

                                        <span>
                                            {{ $benefit }}
                                        </span>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @endif


                    @if($page->cta_label)

                        <div class="mt-5">

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground text-sm font-medium">

                                {{ $page->cta_label }}

                                <x-icon
                                    name="arrow-right"
                                    class="w-4 h-4"
                                />

                            </span>

                        </div>

                    @endif

                </div>

            </div>

        </div>



        @php
    $form = array_replace_recursive(
        \App\Models\EngagementPage::defaultFormContent($type),
        $page->form_content ?? []
    );
@endphp

<div class="bg-card border border-border rounded-xl p-6 space-y-8">

    <div>
        <h2 class="text-lg font-bold text-foreground">
            Contenu du formulaire
        </h2>

        <p class="text-sm text-muted-foreground mt-1">
            Modifie les textes affichés directement sur le formulaire public.
        </p>
    </div>

    @if ($type === 'partner')

        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Section organisation
            </h3>

            <div>
                <x-input-label
                    for="form_organization_section"
                    value="Titre de la section"
                />

                <x-text-input
                    id="form_organization_section"
                    name="form_content[organization_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.organization_section',
                        $form['organization_section'] ?? ''
                    )"
                />
            </div>

            @foreach ([
                'organization' => 'Organisation',
                'sector' => 'Secteur',
                'name' => 'Personne de contact',
                'position' => 'Fonction',
                'email' => 'Email',
                'phone' => 'Téléphone',
                'country' => 'Pays',
            ] as $field => $label)

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <x-input-label
                            :value="$label . ' — intitulé'"
                        />

                        <x-text-input
                            name="form_content[{{ $field }}][label]"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old(
                                'form_content.' . $field . '.label',
                                $form[$field]['label'] ?? ''
                            )"
                        />
                    </div>

                    <div>
                        <x-input-label
                            :value="$label . ' — placeholder'"
                        />

                        <x-text-input
                            name="form_content[{{ $field }}][placeholder]"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old(
                                'form_content.' . $field . '.placeholder',
                                $form[$field]['placeholder'] ?? ''
                            )"
                        />
                    </div>

                </div>

            @endforeach

        </div>


        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Type de partenariat
            </h3>

            <div>
                <x-input-label
                    value="Titre de la section"
                />

                <x-text-input
                    name="form_content[partnership_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.partnership_section',
                        $form['partnership_section'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label
                    value="Question"
                />

                <x-text-input
                    name="form_content[partnership_question][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.partnership_question.label',
                        $form['partnership_question']['label'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label
                    value="Texte d'aide"
                />

                <x-text-input
                    name="form_content[partnership_question][help]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.partnership_question.help',
                        $form['partnership_question']['help'] ?? ''
                    )"
                />
            </div>

            @foreach ($form['partnerships'] as $key => $label)

                <div>
                    <x-input-label
                        :value="'Option : ' . $key"
                    />

                    <x-text-input
                        name="form_content[partnerships][{{ $key }}]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.partnerships.' . $key,
                            $label
                        )"
                    />
                </div>

            @endforeach

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <x-input-label value="Texte Autre" />

                    <x-text-input
                        name="form_content[other_label]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.other_label',
                            $form['other_label'] ?? ''
                        )"
                    />
                </div>

                <div>
                    <x-input-label value="Placeholder Autre" />

                    <x-text-input
                        name="form_content[other_placeholder]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.other_placeholder',
                            $form['other_placeholder'] ?? ''
                        )"
                    />
                </div>

            </div>

        </div>


        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Projet de collaboration
            </h3>

            <div>
                <x-input-label value="Titre de la section" />

                <x-text-input
                    name="form_content[project_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.project_section',
                        $form['project_section'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Question / intitulé" />

                <x-text-input
                    name="form_content[collaboration_project][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.collaboration_project.label',
                        $form['collaboration_project']['label'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Placeholder" />

                <textarea
                    name="form_content[collaboration_project][placeholder]"
                    rows="3"
                    class="mt-1 block w-full rounded-lg border-border bg-background text-foreground"
                >{{ old(
                    'form_content.collaboration_project.placeholder',
                    $form['collaboration_project']['placeholder'] ?? ''
                ) }}</textarea>
            </div>

        </div>


        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Budget
            </h3>

            <div>
                <x-input-label value="Question" />

                <x-text-input
                    name="form_content[budget][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.budget.label',
                        $form['budget']['label'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Texte d'aide" />

                <x-text-input
                    name="form_content[budget][help]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.budget.help',
                        $form['budget']['help'] ?? ''
                    )"
                />
            </div>

            @foreach ($form['budget']['options'] as $key => $label)

                <div>
                    <x-input-label :value="'Option : ' . $key" />

                    <x-text-input
                        name="form_content[budget][options][{{ $key }}]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.budget.options.' . $key,
                            $label
                        )"
                    />
                </div>

            @endforeach

        </div>


        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Découverte de Generation PUSH
            </h3>

            <div>
                <x-input-label value="Question" />

                <x-text-input
                    name="form_content[discovery][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.discovery.label',
                        $form['discovery']['label'] ?? ''
                    )"
                />
            </div>

            @foreach ($form['discovery']['options'] as $key => $label)

                <div>
                    <x-input-label :value="'Option : ' . $key" />

                    <x-text-input
                        name="form_content[discovery][options][{{ $key }}]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.discovery.options.' . $key,
                            $label
                        )"
                    />
                </div>

            @endforeach

            <div>
                <x-input-label value="Placeholder Autre" />

                <x-text-input
                    name="form_content[discovery][other_placeholder]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.discovery.other_placeholder',
                        $form['discovery']['other_placeholder'] ?? ''
                    )"
                />
            </div>

        </div>

    @else

        {{-- FORMULAIRE BÉNÉVOLE --}}

        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Informations personnelles
            </h3>

            <div>
                <x-input-label value="Titre de la section" />

                <x-text-input
                    name="form_content[personal_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.personal_section',
                        $form['personal_section'] ?? ''
                    )"
                />
            </div>

            @foreach ([
                'name' => 'Nom',
                'email' => 'Email',
                'phone' => 'Téléphone',
                'age' => 'Âge',
                'city_country' => 'Ville / pays',
                'profession' => 'Profession',
            ] as $field => $label)

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div>
                        <x-input-label
                            :value="$label . ' — intitulé'"
                        />

                        <x-text-input
                            name="form_content[{{ $field }}][label]"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old(
                                'form_content.' . $field . '.label',
                                $form[$field]['label'] ?? ''
                            )"
                        />
                    </div>

                    <div>
                        <x-input-label
                            :value="$label . ' — placeholder'"
                        />

                        <x-text-input
                            name="form_content[{{ $field }}][placeholder]"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old(
                                'form_content.' . $field . '.placeholder',
                                $form[$field]['placeholder'] ?? ''
                            )"
                        />
                    </div>

                </div>

            @endforeach

        </div>

        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Domaine de contribution
            </h3>

            <div>
                <x-input-label value="Titre de la section" />

                <x-text-input
                    name="form_content[contribution_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.contribution_section',
                        $form['contribution_section'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Question" />

                <x-text-input
                    name="form_content[contribution_question][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.contribution_question.label',
                        $form['contribution_question']['label'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Texte d'aide" />

                <x-text-input
                    name="form_content[contribution_question][help]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.contribution_question.help',
                        $form['contribution_question']['help'] ?? ''
                    )"
                />
            </div>

            @foreach ($form['contributions'] as $index => $label)

                <div>
                    <x-input-label :value="'Domaine ' . ($index + 1)" />

                    <x-text-input
                        name="form_content[contributions][{{ $index }}]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.contributions.' . $index,
                            $label
                        )"
                    />
                </div>

            @endforeach

        </div>

        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Motivation
            </h3>

            <div>
                <x-input-label value="Titre de la section" />

                <x-text-input
                    name="form_content[motivation_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.motivation_section',
                        $form['motivation_section'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Question motivation" />

                <x-text-input
                    name="form_content[motivation][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.motivation.label',
                        $form['motivation']['label'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Question compétences / profil" />

                <x-text-input
                    name="form_content[skills][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.skills.label',
                        $form['skills']['label'] ?? ''
                    )"
                />
            </div>

        </div>

        <div class="space-y-4">

            <h3 class="font-semibold text-foreground">
                Disponibilité & expérience
            </h3>

            <div>
                <x-input-label value="Titre de la section" />

                <x-text-input
                    name="form_content[availability_section]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.availability_section',
                        $form['availability_section'] ?? ''
                    )"
                />
            </div>

            <div>
                <x-input-label value="Question disponibilité" />

                <x-text-input
                    name="form_content[availability][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.availability.label',
                        $form['availability']['label'] ?? ''
                    )"
                />
            </div>

            @foreach ($form['availability']['options'] as $key => $label)

                <div>
                    <x-input-label :value="'Option : ' . $key" />

                    <x-text-input
                        name="form_content[availability][options][{{ $key }}]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.availability.options.' . $key,
                            $label
                        )"
                    />
                </div>

            @endforeach

            <div>
                <x-input-label value="Question événement précédent" />

                <x-text-input
                    name="form_content[participated_before][label]"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old(
                        'form_content.participated_before.label',
                        $form['participated_before']['label'] ?? ''
                    )"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <x-input-label value="Réponse Oui" />

                    <x-text-input
                        name="form_content[participated_before][yes]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.participated_before.yes',
                            $form['participated_before']['yes'] ?? 'Oui'
                        )"
                    />
                </div>

                <div>
                    <x-input-label value="Réponse Non" />

                    <x-text-input
                        name="form_content[participated_before][no]"
                        type="text"
                        class="mt-1 block w-full"
                        :value="old(
                            'form_content.participated_before.no',
                            $form['participated_before']['no'] ?? 'Non'
                        )"
                    />
                </div>

            </div>

        </div>

    @endif

</div>

        {{-- ========================================================
             ACTIONS
        ========================================================= --}}

        <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3">

           <a
    href="{{ route('admin.pages.custom.index') }}"
    class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-accent transition"
>
    <x-icon name="arrow-left" class="w-4 h-4" />
    Annuler
</a>



            <button
                type="submit"
                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition"
            >

                <x-icon
                    name="save"
                    class="w-4 h-4"
                />

                Enregistrer les modifications

            </button>

        </div>

    </form>

</x-layouts.admin>
