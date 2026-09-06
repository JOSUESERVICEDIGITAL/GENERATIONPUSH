<x-layouts.public :title="($page->title ?? 'Nous rejoindre') . ' — Generation PUSH'">

@php
    /*
    |--------------------------------------------------------------------------
    | FORM CONTENT
    |--------------------------------------------------------------------------
    | On fusionne les valeurs par défaut du modèle avec les valeurs
    | enregistrées dans la page.
    |--------------------------------------------------------------------------
    */

    $form = array_replace_recursive(
        \App\Models\EngagementPage::defaultFormContent($type),
        $page->form_content ?? []
    );

    $isPartner = $type === 'partner';
@endphp


{{-- ============================================================= --}}
{{-- BANNIÈRE --}}
{{-- ============================================================= --}}

<x-front.page-banner
    :title="$page->title ?? ($isPartner ? 'Devenir partenaire' : 'Devenir bénévole')"
    :subtitle="$page->subtitle"
/>


{{-- ============================================================= --}}
{{-- CONTENU PRINCIPAL --}}
{{-- ============================================================= --}}

<section class="py-16 md:py-20 bg-white">

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">


        {{-- ========================================================= --}}
        {{-- COLONNE CONTENU --}}
        {{-- ========================================================= --}}

        <div x-data x-reveal="'left'">

            @if ($page->imageUrl())

                <img
                    src="{{ $page->imageUrl() }}"
                    alt="{{ $page->title ?? 'Generation PUSH' }}"
                    class="w-full aspect-video object-cover rounded-2xl shadow-lg mb-8"
                >

            @else

                <div
                    class="w-full aspect-video rounded-2xl mb-8 bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center"
                >

                    <x-icon
                        :name="$isPartner ? 'award' : 'users-round'"
                        class="w-14 h-14 text-accent/50"
                    />

                </div>

            @endif


            @if ($page->description)

                <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                    {{ $page->description }}
                </p>

            @endif


            @if (!empty($page->benefitsList()))

                <div class="mt-8 space-y-3">

                    @foreach ($page->benefitsList() as $benefit)

                        <div class="flex items-start gap-3">

                            <div
                                class="w-6 h-6 rounded-full bg-accent/10 flex items-center justify-center shrink-0 mt-0.5"
                            >

                                <x-icon
                                    name="check"
                                    class="w-3.5 h-3.5 text-accent"
                                />

                            </div>

                            <p class="text-gray-700 text-sm">
                                {{ $benefit }}
                            </p>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>


        {{-- ========================================================= --}}
        {{-- COLONNE FORMULAIRE --}}
        {{-- ========================================================= --}}

        <div
            class="bg-[#F8F9FA] rounded-2xl p-6 md:p-8"
            x-data
            x-reveal="'right'"
        >

            {{-- ===================================================== --}}
            {{-- TITRE FORMULAIRE --}}
            {{-- ===================================================== --}}

            <h2 class="font-bold text-xl text-[#1A1A1A] mb-2">

                {{ $page->cta_label ?? (
                    $isPartner
                        ? 'Devenir partenaire'
                        : 'Devenir bénévole'
                ) }}

            </h2>


            <p class="text-sm text-gray-500 mb-6">

                {{ $isPartner
                    ? 'Construisons ensemble des opportunités pour la jeunesse africaine.'
                    : "Rejoins l’équipe Generation PUSH et contribue à faire la différence."
                }}

            </p>


            {{-- ===================================================== --}}
            {{-- SUCCÈS --}}
            {{-- ===================================================== --}}

            @if (session('success'))

                <div
                    class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6"
                >
                    {{ session('success') }}
                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- ERREURS --}}
            {{-- ===================================================== --}}

            @if ($errors->any())

                <div
                    class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm mb-6"
                >

                    <p class="font-semibold mb-2">
                        Veuillez corriger les erreurs dans le formulaire.
                    </p>

                    <ul class="list-disc list-inside space-y-1">

                        @foreach ($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- FORM --}}
            {{-- ===================================================== --}}

            <form
                method="POST"
                action="{{ route('front.engagement.store') }}"
                enctype="multipart/form-data"
                class="space-y-6"
            >

                @csrf

                <input
                    type="hidden"
                    name="type"
                    value="{{ $type }}"
                >


                {{-- ================================================= --}}
                {{-- PARTENAIRE --}}
                {{-- ================================================= --}}

                @if ($isPartner)


                    {{-- ================================================= --}}
                    {{-- ORGANISATION --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['organization_section'] ?? "Informations sur l'organisation" }}

                        </h3>


                        <div class="space-y-4">


                            {{-- ORGANISATION --}}

                            <div>

                                <label
                                    for="organization"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['organization']['label'] ?? "Nom de l'organisation / entreprise" }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="organization"
                                    type="text"
                                    name="organization"
                                    value="{{ old('organization') }}"
                                    placeholder="{{ $form['organization']['placeholder'] ?? '' }}"
                                    required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('organization')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- SECTEUR --}}

                            <div>

                                <label
                                    for="sector"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['sector']['label'] ?? "Secteur d'activité" }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="sector"
                                    type="text"
                                    name="sector"
                                    value="{{ old('sector') }}"
                                    placeholder="{{ $form['sector']['placeholder'] ?? '' }}"
                                    required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('sector')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- CONTACT + POSTE --}}

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                                {{-- NOM --}}

                                <div>

                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >

                                        {{ $form['name']['label'] ?? 'Nom et prénom du contact' }}

                                        <span class="text-red-500">*</span>

                                    </label>


                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="{{ $form['name']['placeholder'] ?? '' }}"
                                        required
                                        autocomplete="name"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >


                                    @error('name')

                                        <p class="text-xs text-red-500 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- POSTE --}}

                                <div>

                                    <label
                                        for="position"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >

                                        {{ $form['position']['label'] ?? 'Fonction / poste' }}

                                        <span class="text-red-500">*</span>

                                    </label>


                                    <input
                                        id="position"
                                        type="text"
                                        name="position"
                                        value="{{ old('position') }}"
                                        placeholder="{{ $form['position']['placeholder'] ?? 'Ex. Directeur Général' }}"
                                        required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >


                                    @error('position')

                                        <p class="text-xs text-red-500 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>


                            {{-- EMAIL + TÉLÉPHONE --}}

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                                {{-- EMAIL --}}

                                <div>

                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >

                                        {{ $form['email']['label'] ?? 'Email professionnel' }}

                                        <span class="text-red-500">*</span>

                                    </label>


                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="{{ $form['email']['placeholder'] ?? 'contact@entreprise.com' }}"
                                        required
                                        autocomplete="email"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >


                                    @error('email')

                                        <p class="text-xs text-red-500 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                {{-- TÉLÉPHONE --}}

                                <div>

                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >

                                        {{ $form['phone']['label'] ?? 'Numéro WhatsApp' }}

                                        <span class="text-red-500">*</span>

                                    </label>


                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="{{ $form['phone']['placeholder'] ?? '+212...' }}"
                                        required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >


                                    @error('phone')

                                        <p class="text-xs text-red-500 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>


                            {{-- PAYS --}}

                            <div>

                                <label
                                    for="country"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['country']['label'] ?? 'Pays' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="country"
                                    type="text"
                                    name="country"
                                    value="{{ old('country') }}"
                                    placeholder="{{ $form['country']['placeholder'] ?? 'Ex. Burkina Faso' }}"
                                    required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('country')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- PARTENARIAT --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['partnership_section'] ?? 'Type de partenariat' }}

                        </h3>


                        <label class="block text-sm font-medium text-gray-700 mb-3">

                            {{ $form['partnership_question']['label'] ?? 'Type de partenariat souhaité' }}


                            @if (!empty($form['partnership_question']['help']))

                                <span class="text-gray-400 font-normal">

                                    ({{ $form['partnership_question']['help'] }})

                                </span>

                            @endif


                            <span class="text-red-500">*</span>

                        </label>


                        <div class="space-y-2">


                            @foreach (($form['partnerships'] ?? []) as $value => $label)

                                <label
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white cursor-pointer hover:border-accent/50 transition"
                                >

                                    <input
                                        type="checkbox"
                                        name="partnership_types[]"
                                        value="{{ $value }}"
                                        @checked(
                                            in_array(
                                                $value,
                                                old('partnership_types', [])
                                            )
                                        )
                                        class="rounded border-gray-300 text-accent focus:ring-accent"
                                    >


                                    <span class="text-sm text-gray-700">
                                        {{ $label }}
                                    </span>

                                </label>

                            @endforeach


                            {{-- AUTRE --}}

                            <div
                                class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white"
                            >

                                <input
                                    type="checkbox"
                                    id="partnership_other_check"
                                    class="rounded border-gray-300 text-accent focus:ring-accent"
                                    @checked(old('partnership_other'))
                                >


                                <label
                                    for="partnership_other_check"
                                    class="text-sm text-gray-700 shrink-0"
                                >

                                    {{ $form['other_label'] ?? 'Autre :' }}

                                </label>


                                <input
                                    type="text"
                                    name="partnership_other"
                                    value="{{ old('partnership_other') }}"
                                    placeholder="{{ $form['other_placeholder'] ?? 'Précisez...' }}"
                                    class="flex-1 min-w-0 px-2 py-1 border-b border-gray-200 focus:outline-none focus:border-accent"
                                >

                            </div>

                        </div>


                        @error('partnership_types')

                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- PROJET --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['project_section'] ?? 'Projet de collaboration' }}

                        </h3>


                        <div class="space-y-5">


                            {{-- PROJET --}}

                            <div>

                                <label
                                    for="message"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['collaboration_project']['label'] ?? 'Décrivez votre projet de collaboration' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <textarea
                                    id="message"
                                    name="message"
                                    rows="5"
                                    required
                                    placeholder="{{ $form['collaboration_project']['placeholder'] ?? '' }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >{{ old('message') }}</textarea>


                                @error('message')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- BUDGET --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-3">

                                    {{ $form['budget']['label'] ?? 'Quel est votre budget indicatif ?' }}


                                    @if (!empty($form['budget']['help']))

                                        <span class="text-gray-400 font-normal">

                                            ({{ $form['budget']['help'] }})

                                        </span>

                                    @endif

                                </label>


                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">


                                    @foreach (($form['budget']['options'] ?? []) as $value => $label)

                                        <label
                                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white cursor-pointer"
                                        >

                                            <input
                                                type="radio"
                                                name="budget"
                                                value="{{ $value }}"
                                                @checked(old('budget') === $value)
                                                class="border-gray-300 text-accent focus:ring-accent"
                                            >


                                            <span class="text-sm text-gray-700">
                                                {{ $label }}
                                            </span>

                                        </label>

                                    @endforeach

                                </div>


                                @error('budget')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- SITE WEB --}}

                            <div>

                                <label
                                    for="website"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['website']['label'] ?? 'Site web de votre organisation' }}


                                    @if (!empty($form['website']['help']))

                                        <span class="text-gray-400 font-normal">

                                            ({{ $form['website']['help'] }})

                                        </span>

                                    @endif

                                </label>


                                <input
                                    id="website"
                                    type="url"
                                    name="website"
                                    value="{{ old('website') }}"
                                    placeholder="{{ $form['website']['placeholder'] ?? 'https://...' }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('website')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- ================================================= --}}
                            {{-- DÉCOUVERTE --}}
                            {{-- ================================================= --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-3">

                                    {{ $form['discovery']['label'] ?? 'Comment avez-vous connu Generation PUSH ?' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <div class="space-y-2">


                                    @foreach (($form['discovery']['options'] ?? []) as $value => $label)

                                        <label
                                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white cursor-pointer"
                                        >

                                            <input
                                                type="radio"
                                                name="discovery_source"
                                                value="{{ $value }}"
                                                required
                                                @checked(old('discovery_source') === $value)
                                                class="border-gray-300 text-accent focus:ring-accent"
                                            >


                                            <span class="text-sm text-gray-700">
                                                {{ $label }}
                                            </span>

                                        </label>

                                    @endforeach


                                    <input
                                        type="text"
                                        name="discovery_other"
                                        value="{{ old('discovery_other') }}"
                                        placeholder="{{ $form['discovery']['other_placeholder'] ?? 'Si autre, précisez...' }}"
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >

                                </div>


                                @error('discovery_source')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror


                                @error('discovery_other')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                    </div>


                {{-- ===================================================== --}}
                {{-- BÉNÉVOLE --}}
                {{-- ===================================================== --}}

                @else


                    {{-- ================================================= --}}
                    {{-- INFORMATIONS PERSONNELLES --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['personal_section'] ?? 'Informations personnelles' }}

                        </h3>


                        <div class="space-y-4">


                            {{-- NOM --}}

                            <div>

                                <label
                                    for="name"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['name']['label'] ?? 'Prénom et nom' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="name"
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    placeholder="{{ $form['name']['placeholder'] ?? '' }}"
                                    required
                                    autocomplete="name"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('name')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- EMAIL --}}

                            <div>

                                <label
                                    for="email"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['email']['label'] ?? 'Email' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="{{ $form['email']['placeholder'] ?? '' }}"
                                    required
                                    autocomplete="email"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('email')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- TÉLÉPHONE + ÂGE --}}

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">


                                <div>

                                    <label
                                        for="phone"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >

                                        {{ $form['phone']['label'] ?? 'Numéro WhatsApp' }}

                                        <span class="text-red-500">*</span>

                                    </label>


                                    <input
                                        id="phone"
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        placeholder="{{ $form['phone']['placeholder'] ?? '+212...' }}"
                                        required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >


                                    @error('phone')

                                        <p class="text-xs text-red-500 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>


                                <div>

                                    <label
                                        for="age"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                    >

                                        {{ $form['age']['label'] ?? 'Âge' }}

                                        <span class="text-red-500">*</span>

                                    </label>


                                    <input
                                        id="age"
                                        type="number"
                                        name="age"
                                        value="{{ old('age') }}"
                                        placeholder="{{ $form['age']['placeholder'] ?? '' }}"
                                        min="15"
                                        max="120"
                                        required
                                        class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                    >


                                    @error('age')

                                        <p class="text-xs text-red-500 mt-1">
                                            {{ $message }}
                                        </p>

                                    @enderror

                                </div>

                            </div>


                            {{-- VILLE / PAYS --}}

                            <div>

                                <label
                                    for="city_country"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['city_country']['label'] ?? 'Ville et pays de résidence' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="city_country"
                                    type="text"
                                    name="city_country"
                                    value="{{ old('city_country') }}"
                                    placeholder="{{ $form['city_country']['placeholder'] ?? 'Ex. Rabat, Maroc' }}"
                                    required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('city_country')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- PROFESSION --}}

                            <div>

                                <label
                                    for="profession"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['profession']['label'] ?? "Domaine d'études ou profession actuelle" }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <input
                                    id="profession"
                                    type="text"
                                    name="profession"
                                    value="{{ old('profession') }}"
                                    placeholder="{{ $form['profession']['placeholder'] ?? '' }}"
                                    required
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('profession')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- CONTRIBUTION --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['contribution_section'] ?? 'Domaine de contribution' }}

                        </h3>


                        <label class="block text-sm font-medium text-gray-700 mb-3">

                            {{ $form['contribution_question']['label'] ?? 'Dans quel domaine veux-tu contribuer ?' }}


                            @if (!empty($form['contribution_question']['help']))

                                <span class="text-gray-400 font-normal">

                                    ({{ $form['contribution_question']['help'] }})

                                </span>

                            @endif


                            <span class="text-red-500">*</span>

                        </label>


                        <div class="space-y-2">


                            @foreach (($form['contributions'] ?? []) as $index => $contribution)

                                <label
                                    class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white cursor-pointer hover:border-accent/50 transition"
                                >

                                    <input
                                        type="checkbox"
                                        name="contribution_areas[]"
                                        value="{{ $contribution }}"
                                        @checked(
                                            in_array(
                                                $contribution,
                                                old('contribution_areas', [])
                                            )
                                        )
                                        class="rounded border-gray-300 text-accent focus:ring-accent"
                                    >


                                    <span class="text-sm text-gray-700">
                                        {{ $contribution }}
                                    </span>

                                </label>

                            @endforeach


                            <div
                                class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white"
                            >

                                <input
                                    type="checkbox"
                                    id="contribution_other_check"
                                    class="rounded border-gray-300 text-accent focus:ring-accent"
                                    @checked(old('contribution_other'))
                                >


                                <label
                                    for="contribution_other_check"
                                    class="text-sm text-gray-700 shrink-0"
                                >

                                    {{ $form['other_label'] ?? 'Autre :' }}

                                </label>


                                <input
                                    type="text"
                                    name="contribution_other"
                                    value="{{ old('contribution_other') }}"
                                    placeholder="{{ $form['other_placeholder'] ?? 'Précisez...' }}"
                                    class="flex-1 min-w-0 px-2 py-1 border-b border-gray-200 focus:outline-none focus:border-accent"
                                >

                            </div>

                        </div>


                        @error('contribution_areas')

                            <p class="text-xs text-red-500 mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- ================================================= --}}
                    {{-- MOTIVATION --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['motivation_section'] ?? 'Motivation' }}

                        </h3>


                        <div class="space-y-4">


                            {{-- MOTIVATION --}}

                            <div>

                                <label
                                    for="motivation"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['motivation']['label'] ?? 'Pourquoi veux-tu rejoindre Generation PUSH ?' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <textarea
                                    id="motivation"
                                    name="motivation"
                                    rows="4"
                                    required
                                    placeholder="{{ $form['motivation']['placeholder'] ?? '' }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >{{ old('motivation') }}</textarea>


                                @error('motivation')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- SKILLS --}}

                            <div>

                                <label
                                    for="skills"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['skills']['label'] ?? "Qu'est-ce que tu apportes à GP ? Parle-nous de toi." }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <textarea
                                    id="skills"
                                    name="skills"
                                    rows="4"
                                    required
                                    placeholder="{{ $form['skills']['placeholder'] ?? '' }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >{{ old('skills') }}</textarea>


                                @error('skills')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DISPONIBILITÉ --}}
                    {{-- ================================================= --}}

                    <div>

                        <h3 class="text-xs font-bold uppercase tracking-widest text-accent mb-4">

                            {{ $form['availability_section'] ?? 'Disponibilité & expérience' }}

                        </h3>


                        <div class="space-y-5">


                            {{-- DISPONIBILITÉ --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-3">

                                    {{ $form['availability']['label'] ?? 'Disponibilité' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <div class="space-y-2">


                                    @foreach (($form['availability']['options'] ?? []) as $value => $label)

                                        <label
                                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 bg-white cursor-pointer"
                                        >

                                            <input
                                                type="radio"
                                                name="availability"
                                                value="{{ $value }}"
                                                required
                                                @checked(old('availability') === $value)
                                                class="border-gray-300 text-accent focus:ring-accent"
                                            >


                                            <span class="text-sm text-gray-700">
                                                {{ $label }}
                                            </span>

                                        </label>

                                    @endforeach

                                </div>


                                @error('availability')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- PARTICIPATION --}}

                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-3">

                                    {{ $form['participated_before']['label'] ?? 'As-tu déjà participé à un événement Generation PUSH ?' }}

                                    <span class="text-red-500">*</span>

                                </label>


                                <div class="flex gap-4">


                                    <label class="flex items-center gap-2">

                                        <input
                                            type="radio"
                                            name="participated_before"
                                            value="yes"
                                            required
                                            @checked(old('participated_before') === 'yes')
                                            class="border-gray-300 text-accent focus:ring-accent"
                                        >

                                        <span class="text-sm text-gray-700">
                                            {{ $form['participated_before']['yes'] ?? 'Oui' }}
                                        </span>

                                    </label>


                                    <label class="flex items-center gap-2">

                                        <input
                                            type="radio"
                                            name="participated_before"
                                            value="no"
                                            @checked(old('participated_before') === 'no')
                                            class="border-gray-300 text-accent focus:ring-accent"
                                        >

                                        <span class="text-sm text-gray-700">
                                            {{ $form['participated_before']['no'] ?? 'Non' }}
                                        </span>

                                    </label>

                                </div>


                                @error('participated_before')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>


                            {{-- RÉSEAU SOCIAL --}}

                            <div>

                                <label
                                    for="social_link"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                >

                                    {{ $form['social_link']['label'] ?? 'Lien LinkedIn ou réseau social' }}


                                    @if (!empty($form['social_link']['help']))

                                        <span class="text-gray-400 font-normal">

                                            ({{ $form['social_link']['help'] }})

                                        </span>

                                    @endif

                                </label>


                                <input
                                    id="social_link"
                                    type="url"
                                    name="social_link"
                                    value="{{ old('social_link') }}"
                                    placeholder="{{ $form['social_link']['placeholder'] ?? 'https://linkedin.com/in/...' }}"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                                >


                                @error('social_link')

                                    <p class="text-xs text-red-500 mt-1">
                                        {{ $message }}
                                    </p>

                                @enderror

                            </div>

                        </div>

                    </div>

                @endif


                {{-- ===================================================== --}}
                {{-- BOUTON --}}
                {{-- ===================================================== --}}

                <button
                    type="submit"
                    class="w-full px-8 py-3.5 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200 cursor-pointer shadow-sm"
                >

                    {{ $page->cta_label ?? (
                        $isPartner
                            ? 'Envoyer ma demande de partenariat'
                            : 'Envoyer ma candidature'
                    ) }}

                </button>


                {{-- ===================================================== --}}
                {{-- NOTE --}}
                {{-- ===================================================== --}}

                <p class="text-xs text-center text-gray-400">

                    Les champs marqués d'un
                    <span class="text-red-500">*</span>
                    sont obligatoires.

                </p>

            </form>

        </div>

    </div>

</section>

</x-layouts.public>
