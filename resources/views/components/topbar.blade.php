@props(['title' => null])

@php

    /*
    |--------------------------------------------------------------------------
    | LANGUES
    |--------------------------------------------------------------------------
    */

    $locales = [
        'fr' => [
            'label' => 'Français',
            'short' => 'FR',
            'flag' => '🇫🇷',
        ],

        'en' => [
            'label' => 'English',
            'short' => 'EN',
            'flag' => '🇬🇧',
        ],

        'ar' => [
            'label' => 'العربية',
            'short' => 'AR',
            'flag' => '🇸🇦',
        ],
    ];

    $currentLocale =
        $locales[app()->getLocale()]
        ?? $locales['fr'];


    /*
    |--------------------------------------------------------------------------
    | AGENDA — NOUVEAU SYSTÈME ÉVÉNEMENTIEL
    |--------------------------------------------------------------------------
    |
    | Tous les événements viennent maintenant de App\Models\Event.
    |
    | On n'utilise plus :
    | - Conference
    | - Masterclass
    | - CoachingSession
    |
    */

    $upcomingEvents = \App\Models\Event::query()
        ->with('category')
        ->where('status', 'published')
        ->where('starts_at', '>=', now())
        ->orderBy('starts_at')
        ->take(5)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | NOTIFICATIONS
    |--------------------------------------------------------------------------
    |
    | On garde seulement les utilisateurs et les messages de contact.
    |
    | On retire volontairement :
    | - Order
    | - Transaction
    | - ChatMessage
    |
    | afin d'éviter les erreurs liées aux anciens modèles/colonnes.
    |
    */

    $recentActivity = collect();


    /*
    |--------------------------------------------------------------------------
    | UTILISATEURS RÉCENTS
    |--------------------------------------------------------------------------
    */

    if (
        class_exists(\App\Models\User::class)
        && \Illuminate\Support\Facades\Route::has('admin.users.index')
    ) {

        $recentUsers = \App\Models\User::query()
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($user) {

                return [
                    'icon' => 'users',

                    'color' =>
                        'text-accent bg-accent/10',

                    'text' =>
                        'Nouvel utilisateur : ' . $user->name,

                    'date' =>
                        $user->created_at,

                    'url' =>
                        route('admin.users.index'),
                ];

            });

        $recentActivity =
            $recentActivity->concat($recentUsers);
    }


    /*
    |--------------------------------------------------------------------------
    | MESSAGES DE CONTACT
    |--------------------------------------------------------------------------
    */

    if (
        class_exists(\App\Models\ContactMessage::class)
        && \Illuminate\Support\Facades\Route::has(
            'admin.communications.messages.index'
        )
    ) {

        $recentMessages = \App\Models\ContactMessage::query()
            ->where('status', 'new')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($message) {

                return [
                    'icon' => 'mail',

                    'color' =>
                        'text-blue-600 bg-blue-500/10',

                    'text' =>
                        'Message de ' . $message->name,

                    'date' =>
                        $message->created_at,

                    'url' =>
                        route(
                            'admin.communications.messages.index'
                        ),
                ];

            });

        $recentActivity =
            $recentActivity->concat($recentMessages);
    }


    /*
    |--------------------------------------------------------------------------
    | TRI DES NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    $recentActivity = $recentActivity
        ->sortByDesc('date')
        ->take(6);


    /*
    |--------------------------------------------------------------------------
    | NOMBRE DE NOTIFICATIONS NON LUES
    |--------------------------------------------------------------------------
    */

    $unreadCount = 0;

    if (
        class_exists(\App\Models\ContactMessage::class)
    ) {

        $unreadCount =
            \App\Models\ContactMessage::query()
                ->where('status', 'new')
                ->count();
    }


    /*
    |--------------------------------------------------------------------------
    | CRÉATION RAPIDE
    |--------------------------------------------------------------------------
    |
    | Conférence / Masterclass / Coaching ne sont plus des modules
    | indépendants.
    |
    | "Événement" ouvre le nouveau formulaire Event.
    | La catégorie sera choisie dans ce formulaire.
    |
    */

    $quickCreateLinks = [];


    /*
    | Utilisateur
    */

    if (
        \Illuminate\Support\Facades\Route::has(
            'admin.users.create'
        )
    ) {

        $quickCreateLinks[] = [
            'label' => 'Utilisateur',
            'url' => route('admin.users.create'),
            'icon' => 'users',
        ];
    }


    /*
    | Formation
    */

    if (
        \Illuminate\Support\Facades\Route::has(
            'admin.programs.formations.index'
        )
    ) {

        $quickCreateLinks[] = [
            'label' => 'Formation',
            'url' => route(
                'admin.programs.formations.index'
            ),
            'icon' => 'book-open',
        ];
    }


    /*
    | NOUVEAU MODULE ÉVÉNEMENT
    */

    if (
        \Illuminate\Support\Facades\Route::has(
            'admin.events.create'
        )
    ) {

        $quickCreateLinks[] = [
            'label' => 'Événement',
            'url' => route('admin.events.create'),
            'icon' => 'calendar',
        ];
    }


    /*
    | Produit boutique
    */

    if (
        \Illuminate\Support\Facades\Route::has(
            'admin.shop.products.index'
        )
    ) {

        $quickCreateLinks[] = [
            'label' => 'Produit boutique',
            'url' => route(
                'admin.shop.products.index'
            ),
            'icon' => 'shopping-bag',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | UTILISATEUR CONNECTÉ
    |--------------------------------------------------------------------------
    */

    $headerUser = auth()->user();

    $headerInitials = \Illuminate\Support\Str::of(
        $headerUser?->name ?? 'Admin'
    )
        ->explode(' ')
        ->filter()
        ->map(
            fn ($word) =>
                \Illuminate\Support\Str::substr(
                    $word,
                    0,
                    1
                )
        )
        ->take(2)
        ->join('');

@endphp


<header
    x-data="{ mobileSearchOpen: false }"
    class="fixed top-0 end-0 start-0 h-16 bg-card border-b border-border z-30 transition-all duration-300"
    :class="sidebarOpen ? 'md:ms-64' : 'md:ms-20'"
>

    <div
        class="flex items-center justify-between h-full px-3 sm:px-4 md:px-6 gap-2 sm:gap-4"
    >


        {{-- ============================================================
             TOGGLE SIDEBAR
        ============================================================ --}}

        <button
            type="button"
            @click="sidebarOpen = !sidebarOpen"
            :title="'{{ __('nav.toggle_sidebar') }}'"
            class="hidden md:flex p-2 rounded-lg hover:bg-secondary text-foreground shrink-0 cursor-pointer"
        >

            <x-icon
                name="menu"
                class="w-5 h-5"
            />

        </button>


        <div class="w-9 md:hidden shrink-0"></div>



        {{-- ============================================================
             TITRE MOBILE
        ============================================================ --}}

        @if($title)

            <h1
                class="font-semibold text-foreground truncate sm:hidden"
                x-show="!mobileSearchOpen"
            >

                {{ $title }}

            </h1>

        @endif



        {{-- ============================================================
             RECHERCHE
        ============================================================ --}}

        <form
            action="{{ route('admin.dashboard') }}"
            method="GET"
            class="flex-1 items-center min-w-0 hidden sm:flex"
            :class="mobileSearchOpen && '!flex'"
        >

            <div class="relative w-full max-w-sm">

                <x-icon
                    name="search"
                    class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                />

                <input
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    x-ref="searchInput"
                    placeholder="{{ __('nav.search_placeholder') }}"
                    class="w-full ps-10 pe-4 py-2 rounded-lg border border-border bg-secondary text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200 cursor-text"
                >

            </div>

        </form>



        {{-- ============================================================
             RECHERCHE MOBILE
        ============================================================ --}}

        <button
            type="button"
            @click="
                mobileSearchOpen = !mobileSearchOpen;

                if (mobileSearchOpen) {
                    $nextTick(() => $refs.searchInput.focus())
                }
            "
            class="sm:hidden p-2 rounded-lg hover:bg-secondary text-foreground shrink-0 ms-auto cursor-pointer"
            x-show="!mobileSearchOpen"
        >

            <x-icon
                name="search"
                class="w-5 h-5"
            />

        </button>



        {{-- ============================================================
             ACTIONS
        ============================================================ --}}

        <div
            class="flex items-center gap-1 sm:gap-2 md:gap-3"
        >


            {{-- ========================================================
                 CRÉATION RAPIDE
            ======================================================== --}}

            @if(count($quickCreateLinks) > 0)

                <div
                    class="relative hidden lg:block"
                    x-data="{ createMenuOpen: false }"
                    @click.outside="createMenuOpen = false"
                >

                    <button
                        type="button"
                        @click="createMenuOpen = !createMenuOpen"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200 shrink-0 cursor-pointer"
                    >

                        <x-icon
                            name="plus"
                            class="w-4 h-4"
                        />

                        <span>
                            {{ __('nav.create') }}
                        </span>

                    </button>


                    <div
                        x-show="createMenuOpen"
                        x-cloak

                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-2"
                        x-transition:enter-end="opacity-100 translate-y-0"

                        class="absolute end-0 mt-2 w-56 bg-card border border-border rounded-lg shadow-lg z-50 p-1"
                    >

                        @foreach($quickCreateLinks as $link)

                            <a
                                href="{{ $link['url'] }}"
                                class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-foreground hover:bg-secondary transition-all duration-200 cursor-pointer"
                            >

                                <x-icon
                                    :name="$link['icon']"
                                    class="w-4 h-4 text-muted-foreground"
                                />

                                <span>
                                    {{ $link['label'] }}
                                </span>

                            </a>

                        @endforeach

                    </div>

                </div>

            @endif



            {{-- ========================================================
                 AGENDA — NOUVEAU MODULE EVENT
            ======================================================== --}}

            <div
                class="relative"
                x-data="{ agendaOpen: false }"
                @click.outside="agendaOpen = false"
            >

                <button
                    type="button"
                    @click="agendaOpen = !agendaOpen"
                    class="hidden md:flex p-2 rounded-lg hover:bg-secondary text-foreground cursor-pointer relative"
                    title="Prochains événements"
                >

                    <x-icon
                        name="calendar"
                        class="w-5 h-5"
                    />


                    @if($upcomingEvents->isNotEmpty())

                        <span
                            class="absolute -top-1 -end-1 min-w-[18px] h-[18px] px-1 rounded-full bg-accent text-white text-[10px] font-bold flex items-center justify-center"
                        >
                            {{ $upcomingEvents->count() }}
                        </span>

                    @endif

                </button>


                <div
                    x-show="agendaOpen"
                    x-cloak

                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"

                    class="absolute end-0 mt-2 w-80 max-w-[90vw] bg-card border border-border rounded-lg shadow-lg z-50"
                >

                    {{-- HEADER --}}

                    <div
                        class="p-4 border-b border-border flex items-center justify-between gap-3"
                    >

                        <div>

                            <p
                                class="font-semibold text-foreground text-sm"
                            >
                                Prochains événements
                            </p>

                            <p
                                class="text-xs text-muted-foreground mt-0.5"
                            >
                                Agenda Generation PUSH
                            </p>

                        </div>


                        <a
                            href="{{ route('admin.events.index') }}"
                            class="text-xs font-medium text-accent hover:underline"
                        >
                            Voir tout
                        </a>

                    </div>


                    {{-- LISTE DES ÉVÉNEMENTS --}}

                    <div
                        class="max-h-72 overflow-y-auto divide-y divide-border"
                    >

                        @forelse($upcomingEvents as $event)

                            <a
                                href="{{ route('admin.events.show', $event) }}"
                                class="p-4 flex items-center justify-between gap-3 hover:bg-secondary/50 transition-colors duration-200 cursor-pointer"
                            >

                                <div class="min-w-0 flex-1">

                                    <p
                                        class="text-sm font-medium text-foreground truncate"
                                    >
                                        {{ $event->title }}
                                    </p>


                                    <p
                                        class="text-xs text-muted-foreground mt-0.5"
                                    >

                                        {{ $event->category?->name ?? 'Événement' }}

                                        —

                                        {{ $event->starts_at->translatedFormat('d M Y') }}

                                        à

                                        {{ $event->starts_at->format('H:i') }}

                                    </p>


                                    @if($event->city || $event->country)

                                        <p
                                            class="text-xs text-muted-foreground mt-1 truncate"
                                        >

                                            {{ collect([
                                                $event->city,
                                                $event->country
                                            ])->filter()->implode(', ') }}

                                        </p>

                                    @endif

                                </div>


                                <x-icon
                                    name="chevron-right"
                                    class="w-4 h-4 text-muted-foreground shrink-0"
                                />

                            </a>

                        @empty

                            <div
                                class="p-5 text-center"
                            >

                                <x-icon
                                    name="calendar"
                                    class="w-7 h-7 text-muted-foreground mx-auto mb-2"
                                />

                                <p
                                    class="text-sm text-muted-foreground"
                                >
                                    Aucun événement à venir
                                </p>


                                <a
                                    href="{{ route('admin.events.create') }}"
                                    class="inline-flex items-center gap-1 mt-3 text-xs font-medium text-accent hover:underline"
                                >

                                    <x-icon
                                        name="plus"
                                        class="w-3.5 h-3.5"
                                    />

                                    Créer un événement

                                </a>

                            </div>

                        @endforelse

                    </div>


                    {{-- FOOTER AGENDA --}}

                    <div
                        class="p-3 border-t border-border text-center"
                    >

                        <a
                            href="{{ route('admin.events.index') }}"
                            class="text-xs font-medium text-accent hover:underline cursor-pointer"
                        >
                            Gérer tous les événements
                        </a>

                    </div>

                </div>

            </div>



            {{-- ========================================================
                 COMMUNICATIONS
            ======================================================== --}}

            @if(
                \Illuminate\Support\Facades\Route::has(
                    'admin.communications.newsletter.index'
                )
            )

                <a
                    href="{{ route('admin.communications.newsletter.index') }}"
                    class="hidden sm:flex p-2 rounded-lg hover:bg-secondary text-foreground relative cursor-pointer"
                    title="Communications"
                >

                    <x-icon
                        name="mail"
                        class="w-5 h-5"
                    />

                </a>

            @endif



            {{-- ========================================================
                 NOTIFICATIONS
            ======================================================== --}}

            <div
                class="relative"
                x-data="{ notifOpen: false }"
                @click.outside="notifOpen = false"
            >

                <button
                    type="button"
                    @click="notifOpen = !notifOpen"
                    class="p-2 rounded-lg hover:bg-secondary text-foreground relative cursor-pointer"
                    title="Notifications"
                >

                    <x-icon
                        name="bell"
                        class="w-5 h-5"
                    />


                    @if($unreadCount > 0)

                        <span
                            class="absolute -top-1 -end-1 min-w-[18px] h-[18px] px-1 rounded-full bg-destructive text-white text-[10px] font-bold flex items-center justify-center"
                        >

                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}

                        </span>

                    @endif

                </button>


                <div
                    x-show="notifOpen"
                    x-cloak

                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"

                    class="absolute end-0 mt-2 w-80 max-w-[90vw] bg-card border border-border rounded-lg shadow-lg z-50"
                >

                    <div
                        class="p-4 border-b border-border"
                    >

                        <p
                            class="font-semibold text-foreground text-sm"
                        >
                            {{ __('nav.notifications') }}
                        </p>

                    </div>


                    <div
                        class="max-h-72 overflow-y-auto divide-y divide-border"
                    >

                        @forelse($recentActivity as $activity)

                            <a
                                href="{{ $activity['url'] }}"
                                class="p-4 flex gap-3 hover:bg-secondary/50 transition-colors duration-200 cursor-pointer"
                            >

                                <div
                                    class="w-8 h-8 rounded-full {{ $activity['color'] }} flex items-center justify-center shrink-0"
                                >

                                    <x-icon
                                        :name="$activity['icon']"
                                        class="w-4 h-4"
                                    />

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="text-sm text-foreground truncate"
                                    >
                                        {{ $activity['text'] }}
                                    </p>

                                    <p
                                        class="text-xs text-muted-foreground mt-0.5"
                                    >
                                        {{ $activity['date']->diffForHumans() }}
                                    </p>

                                </div>

                            </a>

                        @empty

                            <p
                                class="p-4 text-sm text-muted-foreground"
                            >
                                Aucune nouvelle notification
                            </p>

                        @endforelse

                    </div>


                    @if(
                        \Illuminate\Support\Facades\Route::has(
                            'admin.communications.notifications.index'
                        )
                    )

                        <div
                            class="p-3 border-t border-border text-center"
                        >

                            <a
                                href="{{ route('admin.communications.notifications.index') }}"
                                class="text-xs font-medium text-accent hover:underline cursor-pointer"
                            >
                                Voir toutes les notifications
                            </a>

                        </div>

                    @endif

                </div>

            </div>



            {{-- ========================================================
                 LANGUES
            ======================================================== --}}

            <div
                class="relative"
                x-data="{ langOpen: false }"
                @click.outside="langOpen = false"
            >

                <button
                    type="button"
                    @click="langOpen = !langOpen"
                    :title="'{{ __('nav.language') }}'"
                    class="flex items-center gap-1.5 p-2 rounded-lg hover:bg-secondary text-foreground cursor-pointer"
                >

                    <x-icon
                        name="globe"
                        class="w-5 h-5"
                    />

                    <span
                        class="hidden sm:inline text-xs font-semibold"
                    >
                        {{ $currentLocale['short'] }}
                    </span>

                </button>


                <div
                    x-show="langOpen"
                    x-cloak

                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"

                    class="absolute end-0 mt-2 w-44 bg-card border border-border rounded-lg shadow-lg z-50 p-1"
                >

                    @foreach($locales as $code => $locale)

                        <a
                            href="{{ route('locale.switch', $code) }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-secondary transition-all duration-200 cursor-pointer
                                {{
                                    app()->getLocale() === $code
                                    ? 'bg-accent/10 text-accent font-semibold'
                                    : 'text-foreground'
                                }}"
                        >

                            <span>
                                {{ $locale['flag'] }}
                            </span>

                            <span>
                                {{ $locale['label'] }}
                            </span>


                            @if(app()->getLocale() === $code)

                                <x-icon
                                    name="check"
                                    class="w-3.5 h-3.5 ms-auto"
                                />

                            @endif

                        </a>

                    @endforeach

                </div>

            </div>



            {{-- ========================================================
                 DARK MODE
            ======================================================== --}}

            <button
                type="button"
                @click="dark = !dark"
                :title="'{{ __('nav.toggle_theme') }}'"
                class="p-2 rounded-lg hover:bg-secondary text-foreground cursor-pointer"
            >

                <x-icon
                    x-show="!dark"
                    name="moon"
                    class="w-5 h-5"
                />

                <x-icon
                    x-show="dark"
                    x-cloak
                    name="sun"
                    class="w-5 h-5"
                />

            </button>



            {{-- ========================================================
                 PROFIL
            ======================================================== --}}

            <div
                class="relative"
                x-data="{ profileOpen: false }"
                @click.outside="profileOpen = false"
            >

                <button
                    type="button"
                    @click="profileOpen = !profileOpen"
                    class="flex items-center gap-2 ps-1 sm:ps-2 pe-1 sm:pe-3 py-1 rounded-lg hover:bg-secondary transition-all duration-200 text-foreground cursor-pointer"
                >

                    <div
                        class="w-8 h-8 rounded-full overflow-hidden shrink-0 bg-accent flex items-center justify-center text-white font-semibold text-sm"
                    >

                        @if($headerUser?->profile_photo)

                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($headerUser->profile_photo) }}"
                                alt="{{ $headerUser->name }}"
                                class="w-full h-full object-cover"
                            >

                        @else

                            {{ $headerInitials }}

                        @endif

                    </div>


                    <x-icon
                        name="chevron-down"
                        class="hidden sm:block w-4 h-4 transition-transform duration-200"
                        x-bind:class="profileOpen ? 'rotate-180' : ''"
                    />

                </button>


                <div
                    x-show="profileOpen"
                    x-cloak

                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"

                    class="absolute end-0 mt-2 w-56 bg-card border border-border rounded-lg shadow-lg z-50"
                >

                    {{-- UTILISATEUR --}}

                    <div
                        class="p-4 border-b border-border flex items-center gap-3"
                    >

                        <div
                            class="w-10 h-10 rounded-full overflow-hidden shrink-0 bg-accent flex items-center justify-center text-white font-semibold"
                        >

                            @if($headerUser?->profile_photo)

                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($headerUser->profile_photo) }}"
                                    alt="{{ $headerUser->name }}"
                                    class="w-full h-full object-cover"
                                >

                            @else

                                {{ $headerInitials }}

                            @endif

                        </div>


                        <div class="min-w-0">

                            <p
                                class="font-semibold text-foreground truncate"
                            >
                                {{ $headerUser?->name ?? 'Admin' }}
                            </p>

                            <p
                                class="text-sm text-muted-foreground truncate"
                            >
                                {{
                                    $headerUser?->email
                                    ?? 'admin@generationpush.com'
                                }}
                            </p>

                        </div>

                    </div>


                    {{-- MENU PROFIL --}}

                    <nav class="p-2 space-y-1">

                        @if(
                            \Illuminate\Support\Facades\Route::has(
                                'profile.edit'
                            )
                        )

                            <a
                                href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 rounded-lg hover:bg-secondary text-foreground transition-all duration-200 text-sm cursor-pointer"
                            >
                                {{ __('nav.my_profile') }}
                            </a>

                        @endif


                        @if(
                            \Illuminate\Support\Facades\Route::has(
                                'admin.settings.index'
                            )
                        )

                            <a
                                href="{{ route('admin.settings.index') }}"
                                class="block px-4 py-2 rounded-lg hover:bg-secondary text-foreground transition-all duration-200 text-sm cursor-pointer"
                            >
                                {{ __('nav.settings') }}
                            </a>

                        @endif


                        @if(
                            \Illuminate\Support\Facades\Route::has(
                                'logout'
                            )
                        )

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="w-full text-start px-4 py-2 rounded-lg hover:bg-destructive/10 text-destructive transition-all duration-200 text-sm cursor-pointer"
                                >
                                    {{ __('nav.logout') }}
                                </button>

                            </form>

                        @endif

                    </nav>

                </div>

            </div>

        </div>

    </div>

</header>
