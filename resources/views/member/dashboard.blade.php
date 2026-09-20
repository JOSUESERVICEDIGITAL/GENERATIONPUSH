<x-layouts.member title="Tableau de bord">

    @php
        $chatUrl = \Illuminate\Support\Facades\Route::has('front.chat.index')
            ? route('front.chat.index')
            : route('member.dashboard');

        $bookmarksUrl = \Illuminate\Support\Facades\Route::has('front.blog.bookmarked')
            ? route('front.blog.bookmarked')
            : route('member.dashboard');

        $profileUrl = route('front.my-space');
    @endphp


    {{-- ==========================================================
        EN-TÊTE
    =========================================================== --}}

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

        <div>

            <div class="flex items-center gap-2 mb-2">

                <span
                    class="
                        inline-flex
                        h-8 w-8
                        items-center justify-center
                        rounded-lg
                        bg-primary/10
                        text-primary
                    "
                >
                    <x-icon
                        name="layout-dashboard"
                        class="w-4 h-4"
                    />
                </span>

                <span class="text-sm font-medium text-muted-foreground">
                    Espace membre
                </span>

            </div>

            <h2 class="text-2xl md:text-3xl font-bold tracking-tight">
                Bonjour, {{ $user->name }}
            </h2>

            <p class="mt-1 text-muted-foreground">
                Bienvenue dans votre espace personnel Generation PUSH.
            </p>

        </div>


        {{-- STATUT --}}
        <div>

            <span
                class="
                    inline-flex items-center gap-2
                    rounded-full
                    px-3 py-1.5
                    text-sm font-medium
                    bg-emerald-500/10
                    text-emerald-600
                    dark:text-emerald-400
                "
            >

                <span
                    class="
                        h-2 w-2
                        rounded-full
                        bg-emerald-500
                    "
                ></span>

                {{ $user->statusLabel() }}

            </span>

        </div>

    </div>


    {{-- ==========================================================
        PROFIL INCOMPLET
    =========================================================== --}}

    @if(!$profileComplete)

        <div
            class="
                rounded-2xl
                border border-amber-200
                dark:border-amber-900/50
                bg-amber-50
                dark:bg-amber-950/20
                p-4 md:p-5
            "
        >

            <div class="flex flex-col md:flex-row md:items-center gap-4">

                <div
                    class="
                        h-11 w-11
                        rounded-xl
                        bg-amber-500/10
                        text-amber-600
                        flex items-center justify-center
                        shrink-0
                    "
                >

                    <x-icon
                        name="circle-alert"
                        class="w-5 h-5"
                    />

                </div>


                <div class="flex-1">

                    <h3 class="font-semibold text-amber-900 dark:text-amber-200">
                        Votre profil n'est pas encore complet
                    </h3>

                    <p class="text-sm text-amber-800/80 dark:text-amber-300/80 mt-1">
                        Complétez vos informations pour profiter pleinement
                        de votre espace membre.
                    </p>


                    {{-- PROGRESS --}}
                    <div class="mt-3">

                        <div class="flex justify-between text-xs mb-1.5">

                            <span class="text-amber-800/70 dark:text-amber-300/70">
                                Progression
                            </span>

                            <span class="font-semibold">
                                {{ $profileCompletion }}%
                            </span>

                        </div>

                        <div
                            class="
                                h-2
                                rounded-full
                                bg-amber-200
                                dark:bg-amber-900/50
                                overflow-hidden
                            "
                        >

                            <div
                                class="
                                    h-full
                                    rounded-full
                                    bg-amber-500
                                    transition-all
                                "
                                style="width: {{ $profileCompletion }}%"
                            ></div>

                        </div>

                    </div>

                </div>


                <a
                    href="{{ $profileUrl }}"
                    class="
                        inline-flex
                        items-center justify-center
                        gap-2
                        rounded-xl
                        px-4 py-2.5
                        bg-amber-500
                        text-white
                        text-sm font-semibold
                        hover:bg-amber-600
                        transition
                        shrink-0
                    "
                >

                    Compléter mon profil

                    <x-icon
                        name="arrow-right"
                        class="w-4 h-4"
                    />

                </a>

            </div>

        </div>

    @endif


    {{-- ==========================================================
        KPI
    =========================================================== --}}

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">


        {{-- PROFIL --}}
        @can('member.profile')

            <a
                href="{{ $profileUrl }}"
                class="
                    group
                    relative
                    overflow-hidden
                    rounded-2xl
                    border border-border
                    bg-card
                    p-5
                    shadow-sm
                    hover:shadow-md
                    hover:-translate-y-0.5
                    transition-all duration-200
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-muted-foreground">
                            Mon profil
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight">
                            {{ $profileCompletion }}%
                        </p>

                    </div>

                    <div
                        class="
                            h-11 w-11
                            rounded-xl
                            bg-primary/10
                            text-primary
                            flex items-center justify-center
                        "
                    >

                        <x-icon
                            name="user"
                            class="w-5 h-5"
                        />

                    </div>

                </div>


                <div class="mt-4 flex items-center justify-between">

                    <span class="text-xs text-muted-foreground">
                        {{ $profileComplete ? 'Profil complet' : 'À compléter' }}
                    </span>

                    <x-icon
                        name="arrow-up-right"
                        class="
                            w-4 h-4
                            text-muted-foreground
                            group-hover:text-foreground
                            transition
                        "
                    />

                </div>

            </a>

        @endcan


        {{-- MESSAGES --}}
        @can('member.messages')

            <a
                href="{{ $chatUrl }}"
                class="
                    group
                    relative
                    overflow-hidden
                    rounded-2xl
                    border border-border
                    bg-card
                    p-5
                    shadow-sm
                    hover:shadow-md
                    hover:-translate-y-0.5
                    transition-all duration-200
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-muted-foreground">
                            Mes messages
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight">
                            {{ $messagesCount }}
                        </p>

                    </div>

                    <div
                        class="
                            h-11 w-11
                            rounded-xl
                            bg-blue-500/10
                            text-blue-600
                            dark:text-blue-400
                            flex items-center justify-center
                        "
                    >

                        <x-icon
                            name="message-circle"
                            class="w-5 h-5"
                        />

                    </div>

                </div>


                <div class="mt-4 flex items-center justify-between">

                    <span
                        class="
                            text-xs
                            {{ $unreadMessagesCount > 0
                                ? 'text-red-500 font-semibold'
                                : 'text-muted-foreground'
                            }}
                        "
                    >
                        @if($unreadMessagesCount > 0)
                            {{ $unreadMessagesCount }} non lu(s)
                        @else
                            Aucun nouveau message
                        @endif
                    </span>

                    <x-icon
                        name="arrow-up-right"
                        class="
                            w-4 h-4
                            text-muted-foreground
                            group-hover:text-foreground
                            transition
                        "
                    />

                </div>

            </a>

        @endcan


        {{-- FAVORIS --}}
        @can('member.bookmarks')

            <a
                href="{{ $bookmarksUrl }}"
                class="
                    group
                    relative
                    overflow-hidden
                    rounded-2xl
                    border border-border
                    bg-card
                    p-5
                    shadow-sm
                    hover:shadow-md
                    hover:-translate-y-0.5
                    transition-all duration-200
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-muted-foreground">
                            Articles sauvegardés
                        </p>

                        <p class="mt-2 text-3xl font-bold tracking-tight">
                            {{ $bookmarksCount }}
                        </p>

                    </div>

                    <div
                        class="
                            h-11 w-11
                            rounded-xl
                            bg-violet-500/10
                            text-violet-600
                            dark:text-violet-400
                            flex items-center justify-center
                        "
                    >

                        <x-icon
                            name="bookmark"
                            class="w-5 h-5"
                        />

                    </div>

                </div>


                <div class="mt-4 flex items-center justify-between">

                    <span class="text-xs text-muted-foreground">
                        Ma bibliothèque
                    </span>

                    <x-icon
                        name="arrow-up-right"
                        class="
                            w-4 h-4
                            text-muted-foreground
                            group-hover:text-foreground
                            transition
                        "
                    />

                </div>

            </a>

        @endcan


        {{-- STATUT --}}
        @can('member.dashboard')

            <a
                href="{{ $profileUrl }}"
                class="
                    group
                    relative
                    overflow-hidden
                    rounded-2xl
                    border border-border
                    bg-card
                    p-5
                    shadow-sm
                    hover:shadow-md
                    hover:-translate-y-0.5
                    transition-all duration-200
                "
            >

                <div class="flex items-start justify-between">

                    <div>

                        <p class="text-sm text-muted-foreground">
                            Statut du compte
                        </p>

                        <p class="mt-2 text-2xl font-bold tracking-tight">
                            {{ $user->statusLabel() }}
                        </p>

                    </div>

                    <div
                        class="
                            h-11 w-11
                            rounded-xl
                            bg-emerald-500/10
                            text-emerald-600
                            dark:text-emerald-400
                            flex items-center justify-center
                        "
                    >

                        <x-icon
                            name="shield-check"
                            class="w-5 h-5"
                        />

                    </div>

                </div>


                <div class="mt-4 flex items-center justify-between">

                    <span class="text-xs text-muted-foreground">
                        Compte membre
                    </span>

                    <x-icon
                        name="arrow-up-right"
                        class="
                            w-4 h-4
                            text-muted-foreground
                            group-hover:text-foreground
                            transition
                        "
                    />

                </div>

            </a>

        @endcan

    </div>


    {{-- ==========================================================
        SECTION PRINCIPALE
    =========================================================== --}}

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


        {{-- ACTIVITÉ --}}
        <div
            class="
                xl:col-span-2
                rounded-2xl
                border border-border
                bg-card
                overflow-hidden
            "
        >

            <div
                class="
                    px-5 py-4
                    border-b border-border
                    flex items-center justify-between
                "
            >

                <div>

                    <h3 class="font-semibold">
                        Votre espace
                    </h3>

                    <p class="text-sm text-muted-foreground mt-0.5">
                        Retrouvez rapidement vos principales fonctionnalités.
                    </p>

                </div>

            </div>


            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">


                @can('member.profile')

                    <a
                        href="{{ $profileUrl }}"
                        class="
                            group
                            flex items-center gap-4
                            rounded-xl
                            border border-border
                            p-4
                            hover:bg-muted/50
                            transition
                        "
                    >

                        <div
                            class="
                                h-10 w-10
                                rounded-xl
                                bg-primary/10
                                text-primary
                                flex items-center justify-center
                                shrink-0
                            "
                        >

                            <x-icon
                                name="user"
                                class="w-5 h-5"
                            />

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="font-medium">
                                Mon profil
                            </div>

                            <div class="text-xs text-muted-foreground mt-0.5">
                                Gérer mes informations
                            </div>

                        </div>

                        <x-icon
                            name="chevron-right"
                            class="
                                w-4 h-4
                                text-muted-foreground
                                group-hover:translate-x-0.5
                                transition
                            "
                        />

                    </a>

                @endcan


                @can('member.messages')

                    <a
                        href="{{ $chatUrl }}"
                        class="
                            group
                            flex items-center gap-4
                            rounded-xl
                            border border-border
                            p-4
                            hover:bg-muted/50
                            transition
                        "
                    >

                        <div
                            class="
                                h-10 w-10
                                rounded-xl
                                bg-blue-500/10
                                text-blue-600
                                dark:text-blue-400
                                flex items-center justify-center
                                shrink-0
                            "
                        >

                            <x-icon
                                name="message-circle"
                                class="w-5 h-5"
                            />

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="font-medium">
                                Mes messages
                            </div>

                            <div class="text-xs text-muted-foreground mt-0.5">
                                Échanger avec Generation PUSH
                            </div>

                        </div>

                        <x-icon
                            name="chevron-right"
                            class="
                                w-4 h-4
                                text-muted-foreground
                                group-hover:translate-x-0.5
                                transition
                            "
                        />

                    </a>

                @endcan


                @can('member.bookmarks')

                    <a
                        href="{{ $bookmarksUrl }}"
                        class="
                            group
                            flex items-center gap-4
                            rounded-xl
                            border border-border
                            p-4
                            hover:bg-muted/50
                            transition
                        "
                    >

                        <div
                            class="
                                h-10 w-10
                                rounded-xl
                                bg-violet-500/10
                                text-violet-600
                                dark:text-violet-400
                                flex items-center justify-center
                                shrink-0
                            "
                        >

                            <x-icon
                                name="bookmark"
                                class="w-5 h-5"
                            />

                        </div>

                        <div class="min-w-0 flex-1">

                            <div class="font-medium">
                                Articles sauvegardés
                            </div>

                            <div class="text-xs text-muted-foreground mt-0.5">
                                Retrouver mes contenus favoris
                            </div>

                        </div>

                        <x-icon
                            name="chevron-right"
                            class="
                                w-4 h-4
                                text-muted-foreground
                                group-hover:translate-x-0.5
                                transition
                            "
                        />

                    </a>

                @endcan


                <a
                    href="{{ route('front.home') }}"
                    class="
                        group
                        flex items-center gap-4
                        rounded-xl
                        border border-border
                        p-4
                        hover:bg-muted/50
                        transition
                    "
                >

                    <div
                        class="
                            h-10 w-10
                            rounded-xl
                            bg-emerald-500/10
                            text-emerald-600
                            dark:text-emerald-400
                            flex items-center justify-center
                            shrink-0
                        "
                    >

                        <x-icon
                            name="globe"
                            class="w-5 h-5"
                        />

                    </div>

                    <div class="min-w-0 flex-1">

                        <div class="font-medium">
                            Site Generation PUSH
                        </div>

                        <div class="text-xs text-muted-foreground mt-0.5">
                            Découvrir les actualités et programmes
                        </div>

                    </div>

                    <x-icon
                        name="external-link"
                        class="
                            w-4 h-4
                            text-muted-foreground
                            group-hover:translate-x-0.5
                            transition
                        "
                    />

                </a>

            </div>

        </div>


        {{-- PROFIL --}}
        <div
            class="
                rounded-2xl
                border border-border
                bg-card
                overflow-hidden
            "
        >

            <div
                class="
                    px-5 py-4
                    border-b border-border
                "
            >

                <h3 class="font-semibold">
                    Mon compte
                </h3>

                <p class="text-sm text-muted-foreground mt-0.5">
                    Informations principales
                </p>

            </div>


            <div class="p-5">


                <div class="flex items-center gap-4">

                    @if($user->profile_photo)

                        <img
                            src="{{ asset('storage/' . $user->profile_photo) }}"
                            alt="{{ $user->name }}"
                            class="
                                h-14 w-14
                                rounded-2xl
                                object-cover
                            "
                        >

                    @else

                        <div
                            class="
                                h-14 w-14
                                rounded-2xl
                                bg-primary/10
                                text-primary
                                flex items-center justify-center
                                font-bold
                                text-xl
                            "
                        >
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>

                    @endif


                    <div class="min-w-0">

                        <div class="font-semibold truncate">
                            {{ $user->name }}
                        </div>

                        <div class="text-sm text-muted-foreground truncate">
                            {{ $user->email }}
                        </div>

                    </div>

                </div>


                <div class="mt-5 space-y-3">


                    <div class="flex items-center justify-between text-sm">

                        <span class="text-muted-foreground">
                            Statut
                        </span>

                        <span class="font-medium">
                            {{ $user->statusLabel() }}
                        </span>

                    </div>


                    <div class="flex items-center justify-between text-sm">

                        <span class="text-muted-foreground">
                            Profil
                        </span>

                        <span class="font-medium">
                            {{ $profileCompletion }}%
                        </span>

                    </div>


                    <div class="flex items-center justify-between text-sm">

                        <span class="text-muted-foreground">
                            Messages
                        </span>

                        <span class="font-medium">
                            {{ $messagesCount }}
                        </span>

                    </div>


                    <div class="flex items-center justify-between text-sm">

                        <span class="text-muted-foreground">
                            Favoris
                        </span>

                        <span class="font-medium">
                            {{ $bookmarksCount }}
                        </span>

                    </div>

                </div>


                <a
                    href="{{ $profileUrl }}"
                    class="
                        mt-5
                        w-full
                        inline-flex
                        items-center
                        justify-center
                        gap-2
                        rounded-xl
                        border border-border
                        px-4 py-2.5
                        text-sm font-semibold
                        hover:bg-muted
                        transition
                    "
                >

                    Gérer mon profil

                    <x-icon
                        name="arrow-right"
                        class="w-4 h-4"
                    />

                </a>

            </div>

        </div>

    </div>

</x-layouts.member>