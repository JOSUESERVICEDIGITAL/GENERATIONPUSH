@php
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | URLS MEMBRE
    |--------------------------------------------------------------------------
    */

    $dashboardUrl = route('member.dashboard');

    $profileUrl = \Illuminate\Support\Facades\Route::has('member.profile')
        ? route('member.profile')
        : $dashboardUrl;

    $messagesUrl = \Illuminate\Support\Facades\Route::has('member.messages')
        ? route('member.messages')
        : $dashboardUrl;

    $notificationsUrl = \Illuminate\Support\Facades\Route::has('member.notifications')
        ? route('member.notifications')
        : $dashboardUrl;

    $bookmarksUrl = \Illuminate\Support\Facades\Route::has('member.bookmarks')
        ? route('member.bookmarks')
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
        : $dashboardUrl;

    /*
    |--------------------------------------------------------------------------
    | MESSAGES NON LUS
    |--------------------------------------------------------------------------
    */


    $unreadMessages = \App\Models\ChatMessage::query()
        ->where('author_id', '!=', $user->id)
        ->whereDoesntHave('reads', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->count();

@endphp


<style>
    /* ============================================================
       MEMBER SIDEBAR
    ============================================================ */

    [x-cloak] {
        display: none !important;
    }

    /* ============================================================
       SIDEBAR SHELL
    ============================================================ */

    .member-sidebar {
        --sb-brand: #E8631A;
        --sb-brand-soft: rgba(232, 99, 26, .10);
        --sb-brand-glow: rgba(232, 99, 26, .38);

        width: 16rem;
        height: 100vh;
        height: 100dvh;

        transition:
            width .32s cubic-bezier(.22, 1, .36, 1),
            transform .32s cubic-bezier(.22, 1, .36, 1),
            box-shadow .25s ease;

        will-change: width, transform;

        /*
         * Important :
         * le scroll ne doit PAS être porté par l'aside.
         * C'est uniquement .sb-nav qui scrolle.
         */
        overflow: visible;
    }

    @media (min-width: 768px) {
        .member-sidebar.is-collapsed {
            width: 76px;
        }
    }

    /* ============================================================
       BRAND
    ============================================================ */

    .sb-brand-text {
        color: var(--sb-brand);
    }

    .sb-brand-bg {
        background-color: var(--sb-brand);
    }

    .sb-brand-bg-soft {
        background-color: var(--sb-brand-soft);
    }

    /* ============================================================
       TOGGLE
    ============================================================ */

    .sb-toggle {
        transition:
            transform .22s ease,
            background-color .18s ease,
            color .18s ease,
            border-color .18s ease,
            box-shadow .22s ease;
    }

    .sb-toggle:hover {
        transform: scale(1.08);
        border-color: rgba(232, 99, 26, .45);
        color: var(--sb-brand);
        box-shadow: 0 10px 22px -10px var(--sb-brand-glow);
    }

    .sb-toggle:active {
        transform: scale(.95);
    }

    /* ============================================================
       NAV ITEM
    ============================================================ */

    .sb-item {
        position: relative;

        transition:
            background-color .18s ease,
            color .18s ease,
            box-shadow .22s ease,
            padding .22s ease,
            gap .22s ease;
    }

    .sb-item:not(.is-active):hover {
        background-color: var(--sb-brand-soft);
        color: var(--sb-brand);
    }

    .sb-item.is-active {
        background-color: var(--sb-brand);
        color: #fff;
        box-shadow: 0 10px 22px -12px var(--sb-brand-glow);
    }

    /* ============================================================
       COLLAPSED
    ============================================================ */

    .member-sidebar.is-collapsed .sb-item {
        justify-content: center;
        gap: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    .member-sidebar.is-collapsed .sb-group + .sb-group {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid rgba(226, 232, 240, .85);
    }

    /* ============================================================
       LOGO
    ============================================================ */

    .sb-logo-text {
        max-width: 180px;
        overflow: hidden;

        transition:
            opacity .2s ease,
            max-width .3s cubic-bezier(.22, 1, .36, 1),
            transform .25s ease;
    }

    .member-sidebar.is-collapsed .sb-logo-text {
        opacity: 0;
        max-width: 0;
        transform: translateX(-8px);
    }

    .member-sidebar.is-collapsed .sb-logo-link {
        gap: 0 !important;
        justify-content: center;
    }

    /* ============================================================
       BADGE
    ============================================================ */

    .sb-badge {
        animation: sbBadgePulse 2.4s ease-in-out infinite;
    }

    @keyframes sbBadgePulse {
        0%, 100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, .55);
        }

        50% {
            box-shadow: 0 0 0 5px rgba(239, 68, 68, 0);
        }
    }

    /* ============================================================
       NAVIGATION — SCROLL
    ============================================================ */

    .sb-nav-wrapper {
        /*
         * Ces propriétés sont importantes dans une chaîne flex.
         */
        flex: 1 1 auto;
        min-height: 0;
        height: 0;
        position: relative;
    }

    .sb-nav {
        width: 100%;
        height: 100%;

        min-height: 0;

        overflow-y: auto;
        overflow-x: hidden;

        overscroll-behavior: contain;
        -webkit-overflow-scrolling: touch;

        scrollbar-gutter: stable;

        /*
         * Évite certains blocages de scroll liés aux flex containers.
         */
        touch-action: pan-y;
    }

    .sb-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sb-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sb-nav::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 999px;
    }

    .sb-nav::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Firefox */
    .sb-nav {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e1 transparent;
    }

    /* ============================================================
       TOOLTIP
    ============================================================ */

    .sb-tooltip-portal {
        position: fixed;
        z-index: 99999;

        pointer-events: none;

        transform: translateY(-50%);

        white-space: nowrap;

        border-radius: .65rem;

        background: #0f172a;
        color: white;

        padding: .45rem .7rem;

        font-size: .72rem;
        line-height: 1.2;
        font-weight: 700;

        box-shadow:
            0 10px 25px rgba(15, 23, 42, .18),
            0 2px 6px rgba(15, 23, 42, .12);
    }

    .sb-tooltip-portal::before {
        content: '';

        position: absolute;

        left: -4px;
        top: 50%;

        width: 8px;
        height: 8px;

        background: #0f172a;

        border-radius: 2px;

        transform: translateY(-50%) rotate(45deg);
    }

    /* ============================================================
       FOOTER
    ============================================================ */

    .sb-footer {
        flex: 0 0 auto;

        padding-bottom:
            max(.75rem, env(safe-area-inset-bottom));
    }

    /* ============================================================
       REDUCED MOTION
    ============================================================ */

    @media (prefers-reduced-motion: reduce) {

        .member-sidebar,
        .sb-item,
        .sb-toggle,
        .sb-logo-text,
        .sb-badge {
            transition: none !important;
            animation: none !important;
        }
    }
</style>


{{-- ============================================================
     SIDEBAR
============================================================ --}}

<div
    x-data="{
        tooltip: {
            show: false,
            text: '',
            top: 0,
            left: 0
        },

        showTooltip(el, text) {

            if (sidebarOpen) {
                this.hideTooltip();
                return;
            }

            const rect = el.getBoundingClientRect();

            this.tooltip.text = text;
            this.tooltip.top = rect.top + (rect.height / 2);
            this.tooltip.left = rect.right + 12;
            this.tooltip.show = true;
        },

        hideTooltip() {
            this.tooltip.show = false;
        }
    }"
    @mouseleave="hideTooltip()"
>


    {{-- ========================================================
         ASIDE
    ========================================================= --}}

    <aside
        x-cloak

        :class="[
            !sidebarOpen ? 'is-collapsed' : '',
            mobileSidebarOpen
                ? 'translate-x-0'
                : '-translate-x-full md:translate-x-0'
        ]"

        class="
            member-sidebar

            fixed inset-y-0 start-0 z-50

            flex flex-col

            bg-white
            border-e border-slate-200
            shadow-xl
        "
    >


        {{-- ====================================================
             MOBILE OVERLAY
        ===================================================== --}}

        <div
            x-show="mobileSidebarOpen"
            x-transition.opacity

            @click="mobileSidebarOpen = false"

            class="
                fixed
                inset-0
                -z-10
                bg-black/50
                backdrop-blur-sm
                md:hidden
            "
        ></div>


        {{-- ====================================================
             TOGGLE DESKTOP
        ===================================================== --}}

        <button
            type="button"

            @click="
                hideTooltip();
                sidebarOpen = !sidebarOpen
            "

            :aria-label="
                sidebarOpen
                    ? 'Réduire le menu'
                    : 'Développer le menu'
            "

            class="
                sb-toggle

                absolute
                -end-3
                top-20
                z-[60]

                hidden
                h-7
                w-7

                items-center
                justify-center

                rounded-full

                border
                border-slate-200

                bg-white

                text-slate-500

                shadow-md

                md:flex
            "
        >

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2.5"
                stroke-linecap="round"
                stroke-linejoin="round"

                class="h-3.5 w-3.5 transition-transform duration-300"

                :class="sidebarOpen ? '' : 'rotate-180'"
            >
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>

        </button>


        {{-- ====================================================
             LOGO
        ===================================================== --}}

        <div
            class="
                flex
                h-16
                shrink-0
                items-center
                border-b
                border-slate-200
                px-4
            "
        >

            <a
                href="{{ $dashboardUrl }}"
                class="sb-logo-link flex min-w-0 items-center gap-3"
            >

                <div
                    class="
                        sb-brand-bg

                        flex
                        h-10
                        w-10
                        shrink-0

                        items-center
                        justify-center

                        rounded-xl

                        text-sm
                        font-extrabold
                        text-white

                        shadow-sm
                    "
                >
                    GP
                </div>


                <div class="sb-logo-text min-w-0">

                    <div
                        class="
                            truncate
                            font-bold
                            leading-tight
                            text-slate-900
                        "
                    >
                        Generation
                    </div>

                    <div
                        class="
                            truncate
                            text-xs
                            text-slate-500
                        "
                    >
                        PUSH · Membre
                    </div>

                </div>

            </a>

        </div>


        {{-- ====================================================
             ZONE NAVIGATION
        ===================================================== --}}

        <div class="sb-nav-wrapper">


            {{-- =================================================
                 NAVIGATION SCROLLABLE
            ================================================== --}}

            <nav
                class="
                    sb-nav

                    space-y-0.5

                    px-3
                    py-4
                "
            >


                {{-- =================================================
                     PRINCIPAL
                ================================================== --}}

                <div class="sb-group">

                    <div
                        x-show="sidebarOpen"

                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"

                        class="
                            mb-2
                            px-3

                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-slate-400
                        "
                    >
                        Principal
                    </div>


                    {{-- TABLEAU DE BORD --}}
                    @can('member.dashboard')

                        @php
                            $active = request()->routeIs('member.dashboard');
                        @endphp

                        <a
                            href="{{ $dashboardUrl }}"

                            aria-label="Tableau de bord"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Tableau de bord'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="layout-dashboard"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Tableau de bord
                            </span>

                        </a>

                    @endcan


                    {{-- PROFIL --}}
                    @can('member.profile')

                        @php
                            $active = request()->routeIs('member.profile');
                        @endphp

                        <a
                            href="{{ $profileUrl }}"

                            aria-label="Mon profil"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mon profil'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="user"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mon profil
                            </span>

                        </a>

                    @endcan

                </div>


                {{-- =================================================
                     COMMUNICATION
                ================================================== --}}

                <div class="sb-group">

                    <div
                        x-show="sidebarOpen"

                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"

                        class="
                            mt-6
                            mb-2
                            px-3

                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-slate-400
                        "
                    >
                        Communication
                    </div>


                    {{-- MESSAGES --}}
                    @can('member.messages')

                        @php
                            $active = request()->routeIs('member.messages*');
                        @endphp

                        <a
                            href="{{ $messagesUrl }}"

                            aria-label="Mes messages"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes messages'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="message-circle"
                                    class="h-5 w-5"
                                />


                                @if($unreadMessages > 0)

                                    <span
                                        class="
                                            sb-badge

                                            absolute
                                            -top-1
                                            -end-1

                                            flex
                                            h-4
                                            min-w-4

                                            items-center
                                            justify-center

                                            rounded-full

                                            bg-red-500

                                            px-1

                                            text-[9px]
                                            font-bold
                                            text-white
                                        "
                                    >
                                        {{ $unreadMessages > 9
                                            ? '9+'
                                            : $unreadMessages }}
                                    </span>

                                @endif

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="
                                    flex-1
                                    truncate
                                "
                            >
                                Mes messages
                            </span>

                        </a>

                    @endcan


                    {{-- NOTIFICATIONS --}}
                    @can('member.notifications')

                        @php
                            $active = request()->routeIs('member.notifications*');
                        @endphp

                        <a
                            href="{{ $notificationsUrl }}"

                            aria-label="Mes notifications"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes notifications'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="bell"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes notifications
                            </span>

                        </a>

                    @endcan


                    {{-- FAVORIS --}}
                    @can('member.bookmarks')

                        @php
                            $active = request()->routeIs('member.bookmarks*');
                        @endphp

                        <a
                            href="{{ $bookmarksUrl }}"

                            aria-label="Mes favoris"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes favoris'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="bookmark"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes favoris
                            </span>

                        </a>

                    @endcan

                </div>


                {{-- =================================================
                     ACTIVITÉS
                ================================================== --}}

                <div class="sb-group">

                    <div
                        x-show="sidebarOpen"

                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"

                        class="
                            mt-6
                            mb-2
                            px-3

                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-slate-400
                        "
                    >
                        Activités
                    </div>


                    {{-- FORMATIONS --}}
                    @can('member.formations')

                        @php
                            $active = request()->routeIs('member.formations*');
                        @endphp

                        <a
                            href="{{ $formationsUrl }}"

                            aria-label="Mes formations"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes formations'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="graduation-cap"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes formations
                            </span>

                        </a>

                    @endcan


                    {{-- ÉVÉNEMENTS --}}
                    @can('member.events')

                        @php
                            $active = request()->routeIs('member.events*');
                        @endphp

                        <a
                            href="{{ $eventsUrl }}"

                            aria-label="Mes événements"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes événements'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="calendar-days"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes événements
                            </span>

                        </a>

                    @endcan


                    {{-- RÉSERVATIONS --}}
                    @can('member.reservations')

                        @php
                            $active = request()->routeIs('member.reservations*');
                        @endphp

                        <a
                            href="{{ $reservationsUrl }}"

                            aria-label="Mes réservations"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes réservations'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="ticket"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes réservations
                            </span>

                        </a>

                    @endcan

                </div>


                {{-- =================================================
                     FINANCES
                ================================================== --}}

                <div class="sb-group">

                    <div
                        x-show="sidebarOpen"

                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"

                        class="
                            mt-6
                            mb-2
                            px-3

                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-slate-400
                        "
                    >
                        Finances
                    </div>


                    {{-- COMMANDES --}}
                    @can('member.orders')

                        @php
                            $active = request()->routeIs('member.orders*');
                        @endphp

                        <a
                            href="{{ $ordersUrl }}"

                            aria-label="Mes commandes"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes commandes'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="shopping-bag"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes commandes
                            </span>

                        </a>

                    @endcan


                    {{-- PAIEMENTS --}}
                    @can('member.payments')

                        @php
                            $active = request()->routeIs('member.payments*');
                        @endphp

                        <a
                            href="{{ $paymentsUrl }}"

                            aria-label="Mes paiements"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Mes paiements'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="credit-card"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Mes paiements
                            </span>

                        </a>

                    @endcan

                </div>


                {{-- =================================================
                     COMPTE
                ================================================== --}}

                <div class="sb-group">

                    <div
                        x-show="sidebarOpen"

                        x-transition:enter="transition ease-out duration-150"
                        x-transition:enter-start="opacity-0 -translate-x-1"
                        x-transition:enter-end="opacity-100 translate-x-0"

                        class="
                            mt-6
                            mb-2
                            px-3

                            text-[10px]
                            font-bold
                            uppercase
                            tracking-wider
                            text-slate-400
                        "
                    >
                        Compte
                    </div>


                    {{-- PARAMÈTRES --}}
                    @can('member.settings')

                        @php
                            $active = request()->routeIs('member.settings*');
                        @endphp

                        <a
                            href="{{ $settingsUrl }}"

                            aria-label="Paramètres"

                            @mouseenter="
                                showTooltip(
                                    $el,
                                    'Paramètres'
                                )
                            "

                            @mouseleave="hideTooltip()"

                            class="
                                sb-item

                                flex
                                items-center
                                gap-3

                                rounded-xl

                                px-3
                                py-2.5

                                text-sm
                                font-medium

                                {{ $active
                                    ? 'is-active'
                                    : 'text-slate-600' }}
                            "
                        >

                            <div class="relative shrink-0">

                                <x-icon
                                    name="settings"
                                    class="h-5 w-5"
                                />

                            </div>


                            <span
                                x-show="sidebarOpen"

                                x-transition:enter="transition ease-out duration-200 delay-75"
                                x-transition:enter-start="opacity-0 -translate-x-2"
                                x-transition:enter-end="opacity-100 translate-x-0"

                                class="truncate"
                            >
                                Paramètres
                            </span>

                        </a>

                    @endcan


                    {{-- VOIR LE SITE --}}
                    <a
                        href="{{ route('front.home') }}"

                        aria-label="Voir le site"

                        @mouseenter="
                            showTooltip(
                                $el,
                                'Voir le site'
                            )
                        "

                        @mouseleave="hideTooltip()"

                        class="
                            sb-item

                            flex
                            items-center
                            gap-3

                            rounded-xl

                            px-3
                            py-2.5

                            text-sm
                            font-medium

                            text-slate-600
                        "
                    >

                        <div class="relative shrink-0">

                            <x-icon
                                name="globe"
                                class="h-5 w-5"
                            />

                        </div>


                        <span
                            x-show="sidebarOpen"

                            x-transition:enter="transition ease-out duration-200 delay-75"
                            x-transition:enter-start="opacity-0 -translate-x-2"
                            x-transition:enter-end="opacity-100 translate-x-0"

                            class="truncate"
                        >
                            Voir le site
                        </span>

                    </a>

                </div>


                {{-- Espace supplémentaire pour faciliter le scroll --}}
                <div class="h-4"></div>

            </nav>


            {{-- =================================================
                 INDICATEUR DE FIN DE SCROLL
            ================================================== --}}

            <div
                class="
                    pointer-events-none

                    absolute
                    inset-x-0
                    bottom-0

                    h-12

                    bg-gradient-to-t
                    from-white
                    to-transparent
                "
            ></div>

        </div>


        {{-- ====================================================
             FOOTER UTILISATEUR
        ===================================================== --}}

        <div
            class="
                sb-footer

                shrink-0

                border-t
                border-slate-200

                px-3
                pt-3

                bg-white
            "
        >

            <div class="flex items-center gap-3">


                {{-- AVATAR --}}
                @if($user?->profile_photo)

                    <img
                        src="{{ asset('storage/' . $user->profile_photo) }}"
                        alt="{{ $user->name }}"

                        class="
                            h-9
                            w-9
                            shrink-0

                            rounded-full

                            object-cover

                            ring-2
                            ring-transparent

                            transition
                        "
                    >

                @else

                    <div
                        class="
                            sb-brand-bg-soft
                            sb-brand-text

                            flex
                            h-9
                            w-9
                            shrink-0

                            items-center
                            justify-center

                            rounded-full

                            font-bold
                        "
                    >
                        {{ strtoupper(mb_substr($user?->name ?? 'M', 0, 1)) }}
                    </div>

                @endif


                {{-- NOM --}}
                <div
                    x-show="sidebarOpen"

                    x-transition:enter="transition ease-out duration-200 delay-75"
                    x-transition:enter-start="opacity-0 -translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"

                    class="
                        min-w-0
                        flex-1
                    "
                >

                    <div
                        class="
                            truncate
                            text-sm
                            font-semibold
                            text-slate-900
                        "
                    >
                        {{ $user?->name }}
                    </div>

                    <div
                        class="
                            truncate
                            text-xs
                            text-slate-500
                        "
                    >
                        Membre
                    </div>

                </div>

            </div>


            {{-- DÉCONNEXION --}}
            <form
                method="POST"
                action="{{ route('logout') }}"

                x-show="sidebarOpen"

                x-transition:enter="transition ease-out duration-200 delay-75"
                x-transition:enter-start="opacity-0 -translate-x-2"
                x-transition:enter-end="opacity-100 translate-x-0"

                class="mt-2"
            >

                @csrf

                <button
                    type="submit"

                    class="
                        flex
                        w-full
                        items-center
                        gap-3

                        rounded-lg

                        px-3
                        py-2

                        text-sm
                        text-slate-500

                        transition

                        hover:bg-red-500/10
                        hover:text-red-500
                    "
                >

                    <x-icon
                        name="log-out"
                        class="h-4 w-4"
                    />

                    Déconnexion

                </button>

            </form>

        </div>

    </aside>


    {{-- ============================================================
         TOOLTIP PORTAL
         Hors du <aside> pour ne jamais être coupé par son layout.
    ============================================================= --}}

    <div
        x-cloak

        x-show="tooltip.show"

        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"

        :style="`
            top: ${tooltip.top}px;
            left: ${tooltip.left}px;
        `"

        class="sb-tooltip-portal"
    >
        <span x-text="tooltip.text"></span>
    </div>

</div>
