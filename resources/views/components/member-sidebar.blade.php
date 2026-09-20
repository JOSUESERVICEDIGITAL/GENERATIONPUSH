@php
    $user = auth()->user();

    $dashboardUrl = route('member.dashboard');

    $profileUrl = route('front.my-space');

    $chatUrl = \Illuminate\Support\Facades\Route::has('front.chat.index')
        ? route('front.chat.index')
        : $dashboardUrl;

    $bookmarksUrl = \Illuminate\Support\Facades\Route::has('front.blog.bookmarked')
        ? route('front.blog.bookmarked')
        : $dashboardUrl;

    $notificationsUrl = \Illuminate\Support\Facades\Route::has('member.notifications')
        ? route('member.notifications')
        : $dashboardUrl;

    $formationsUrl = \Illuminate\Support\Facades\Route::has('member.formations')
        ? route('member.formations')
        : $dashboardUrl;

    $eventsUrl = \Illuminate\Support\Facades\Route::has('member.events')
        ? route('member.events')
        : $dashboardUrl;

    $reservationsUrl = \Illuminate\Support\Facades\Route::has('member.reservations')
        ? route('member.reservations')
        : $dashboardUrl;

    $ordersUrl = \Illuminate\Support\Facades\Route::has('member.orders')
        ? route('member.orders')
        : $dashboardUrl;

    $paymentsUrl = \Illuminate\Support\Facades\Route::has('member.payments')
        ? route('member.payments')
        : $dashboardUrl;

    $settingsUrl = \Illuminate\Support\Facades\Route::has('member.settings')
        ? route('member.settings')
        : $profileUrl;

    $unreadMessages = $user
        ->chatMessages()
        ->where('is_from_admin', true)
        ->whereNull('read_by_member_at')
        ->count();
@endphp


<aside
    x-cloak
    class="
        fixed inset-y-0 start-0 z-50
        flex flex-col
        bg-card border-e border-border
        shadow-xl
        transition-all duration-300
        w-64
    "
    :class="[
        sidebarOpen ? 'md:w-64' : 'md:w-20',
        mobileSidebarOpen
            ? 'translate-x-0'
            : '-translate-x-full md:translate-x-0'
    ]"
>

    {{-- ==========================================================
        MOBILE OVERLAY
    =========================================================== --}}

    <div
        x-show="mobileSidebarOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black/50 md:hidden -z-10"
        @click="mobileSidebarOpen = false"
    ></div>


    {{-- ==========================================================
        LOGO
    =========================================================== --}}

    <div
        class="
            h-16 shrink-0
            flex items-center
            border-b border-border
            px-4
        "
    >

        <a
            href="{{ $dashboardUrl }}"
            class="flex items-center gap-3 min-w-0"
        >

            <div
                class="
                    h-10 w-10 shrink-0
                    rounded-xl
                    bg-primary
                    text-primary-foreground
                    flex items-center justify-center
                    font-extrabold
                    shadow-sm
                "
            >
                GP
            </div>

            <div
                x-show="sidebarOpen"
                x-transition
                class="min-w-0"
            >

                <div class="font-bold leading-tight truncate">
                    Generation
                </div>

                <div class="text-xs text-muted-foreground">
                    PUSH · Membre
                </div>

            </div>

        </a>

    </div>


    {{-- ==========================================================
        NAVIGATION
    =========================================================== --}}

    <nav
        class="
            flex-1
            overflow-y-auto
            px-3 py-5
            space-y-1
        "
    >

        {{-- ======================================================
            PRINCIPAL
        ======================================================= --}}

        <div
            x-show="sidebarOpen"
            class="
                px-3 mb-2
                text-[11px]
                font-semibold
                uppercase
                tracking-wider
                text-muted-foreground
            "
        >
            Principal
        </div>


        {{-- TABLEAU DE BORD --}}
        @can('member.dashboard')

            <a
                href="{{ $dashboardUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.dashboard')
                        ? 'bg-primary text-primary-foreground shadow-sm'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="layout-dashboard"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Tableau de bord
                </span>

            </a>

        @endcan


        {{-- MON PROFIL --}}
        @can('member.profile')

            <a
                href="{{ $profileUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('front.my-space')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="user"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mon profil
                </span>

            </a>

        @endcan


        {{-- ======================================================
            COMMUNICATION
        ======================================================= --}}

        <div
            x-show="sidebarOpen"
            class="
                px-3
                mt-6 mb-2
                text-[11px]
                font-semibold
                uppercase
                tracking-wider
                text-muted-foreground
            "
        >
            Communication
        </div>


        {{-- MESSAGES --}}
        @can('member.messages')

            <a
                href="{{ $chatUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    text-muted-foreground
                    hover:bg-muted
                    hover:text-foreground
                "
            >

                <div class="relative shrink-0">

                    <x-icon
                        name="message-circle"
                        class="w-5 h-5"
                    />

                    @if($unreadMessages > 0)

                        <span
                            class="
                                absolute
                                -top-1
                                -end-1
                                h-4
                                min-w-4
                                px-1
                                rounded-full
                                bg-red-500
                                text-white
                                text-[9px]
                                font-bold
                                flex items-center justify-center
                            "
                        >
                            {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                        </span>

                    @endif

                </div>

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate flex-1"
                >
                    Mes messages
                </span>

            </a>

        @endcan


        {{-- NOTIFICATIONS --}}
        @can('member.notifications')

            <a
                href="{{ $notificationsUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.notifications')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="bell"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes notifications
                </span>

            </a>

        @endcan


        {{-- FAVORIS --}}
        @can('member.bookmarks')

            <a
                href="{{ $bookmarksUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('front.blog.bookmarked')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="bookmark"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes favoris
                </span>

            </a>

        @endcan


        {{-- ======================================================
            ACTIVITÉS
        ======================================================= --}}

        <div
            x-show="sidebarOpen"
            class="
                px-3
                mt-6 mb-2
                text-[11px]
                font-semibold
                uppercase
                tracking-wider
                text-muted-foreground
            "
        >
            Activités
        </div>


        {{-- FORMATIONS --}}
        @can('member.formations')

            <a
                href="{{ $formationsUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.formations*')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="graduation-cap"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes formations
                </span>

            </a>

        @endcan


        {{-- ÉVÉNEMENTS --}}
        @can('member.events')

            <a
                href="{{ $eventsUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.events*')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="calendar-days"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes événements
                </span>

            </a>

        @endcan


        {{-- RÉSERVATIONS --}}
        @can('member.reservations')

            <a
                href="{{ $reservationsUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.reservations*')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="ticket"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes réservations
                </span>

            </a>

        @endcan


        {{-- ======================================================
            FINANCES
        ======================================================= --}}

        <div
            x-show="sidebarOpen"
            class="
                px-3
                mt-6 mb-2
                text-[11px]
                font-semibold
                uppercase
                tracking-wider
                text-muted-foreground
            "
        >
            Finances
        </div>


        {{-- COMMANDES --}}
        @can('member.orders')

            <a
                href="{{ $ordersUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.orders*')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="shopping-bag"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes commandes
                </span>

            </a>

        @endcan


        {{-- PAIEMENTS --}}
        @can('member.payments')

            <a
                href="{{ $paymentsUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.payments*')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="credit-card"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Mes paiements
                </span>

            </a>

        @endcan


        {{-- ======================================================
            COMPTE
        ======================================================= --}}

        <div
            x-show="sidebarOpen"
            class="
                px-3
                mt-6 mb-2
                text-[11px]
                font-semibold
                uppercase
                tracking-wider
                text-muted-foreground
            "
        >
            Compte
        </div>


        {{-- PARAMÈTRES --}}
        @can('member.settings')

            <a
                href="{{ $settingsUrl }}"
                class="
                    group
                    flex items-center gap-3
                    px-3 py-2.5
                    rounded-xl
                    transition
                    {{ request()->routeIs('member.settings')
                        ? 'bg-muted text-foreground font-semibold'
                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                    }}
                "
            >

                <x-icon
                    name="settings"
                    class="w-5 h-5 shrink-0"
                />

                <span
                    x-show="sidebarOpen"
                    x-transition
                    class="truncate"
                >
                    Paramètres
                </span>

            </a>

        @endcan


        {{-- SITE PUBLIC --}}
        <a
            href="{{ route('front.home') }}"
            class="
                group
                flex items-center gap-3
                px-3 py-2.5
                rounded-xl
                text-muted-foreground
                hover:bg-muted
                hover:text-foreground
                transition
            "
        >

            <x-icon
                name="globe"
                class="w-5 h-5 shrink-0"
            />

            <span
                x-show="sidebarOpen"
                x-transition
                class="truncate"
            >
                Voir le site
            </span>

        </a>

    </nav>


    {{-- ==========================================================
        USER FOOTER
    =========================================================== --}}

    <div
        class="
            shrink-0
            border-t border-border
            p-3
        "
    >

        <div class="flex items-center gap-3">

            @if($user?->profile_photo)

                <img
                    src="{{ asset('storage/' . $user->profile_photo) }}"
                    alt="{{ $user->name }}"
                    class="
                        h-9 w-9
                        rounded-full
                        object-cover
                        shrink-0
                    "
                >

            @else

                <div
                    class="
                        h-9 w-9
                        rounded-full
                        bg-primary/10
                        text-primary
                        flex items-center justify-center
                        font-bold
                        shrink-0
                    "
                >
                    {{ strtoupper(substr($user?->name ?? 'M', 0, 1)) }}
                </div>

            @endif


            <div
                x-show="sidebarOpen"
                x-transition
                class="min-w-0 flex-1"
            >

                <div class="font-semibold text-sm truncate">
                    {{ $user?->name }}
                </div>

                <div class="text-xs text-muted-foreground truncate">
                    Membre
                </div>

            </div>

        </div>


        <form
            method="POST"
            action="{{ route('logout') }}"
            class="mt-2"
            x-show="sidebarOpen"
            x-transition
        >

            @csrf

            <button
                type="submit"
                class="
                    w-full
                    flex items-center gap-3
                    px-3 py-2
                    rounded-lg
                    text-sm
                    text-muted-foreground
                    hover:bg-red-500/10
                    hover:text-red-500
                    transition
                "
            >

                <x-icon
                    name="log-out"
                    class="w-4 h-4"
                />

                Déconnexion

            </button>

        </form>

    </div>

</aside>