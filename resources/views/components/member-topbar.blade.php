@php
    $user = auth()->user();

    $unreadMessages = $user
        ->chatMessages()
        ->where('is_from_admin', true)
        ->whereNull('read_by_member_at')
        ->count();

    $profileUrl = route('front.my-space');

    $chatUrl = \Illuminate\Support\Facades\Route::has('front.chat.index')
        ? route('front.chat.index')
        : route('member.dashboard');

    $bookmarksUrl = \Illuminate\Support\Facades\Route::has('front.blog.bookmarked')
        ? route('front.blog.bookmarked')
        : route('member.dashboard');
@endphp

<header
    class="
        fixed top-0 start-0 end-0 z-40
        h-16
        bg-card/95
        backdrop-blur
        border-b border-border
        transition-all duration-300
    "
    :class="sidebarOpen ? 'md:ms-64' : 'md:ms-20'"
>

    <div class="h-full px-4 md:px-6 flex items-center gap-3">


        {{-- MOBILE MENU --}}
        <button
            type="button"
            class="
                md:hidden
                h-10 w-10
                rounded-xl
                border border-border
                flex items-center justify-center
                hover:bg-muted
                transition
            "
            @click="mobileSidebarOpen = true"
        >
            <x-icon
                name="menu"
                class="w-5 h-5"
            />
        </button>


        {{-- DESKTOP SIDEBAR TOGGLE --}}
        <button
            type="button"
            class="
                hidden md:flex
                h-10 w-10
                rounded-xl
                items-center justify-center
                hover:bg-muted
                transition
            "
            @click="sidebarOpen = !sidebarOpen"
        >

            <x-icon
                name="panel-left"
                class="w-5 h-5"
            />

        </button>


        {{-- TITLE --}}
        <div class="min-w-0 flex-1">

            <h1 class="font-semibold text-base md:text-lg truncate">
                {{ $title ?? 'Mon espace membre' }}
            </h1>

        </div>


        {{-- QUICK ACTIONS --}}
        <div class="flex items-center gap-1">


            {{-- SITE --}}
            <a
                href="{{ route('front.home') }}"
                class="
                    hidden sm:flex
                    h-10 w-10
                    items-center justify-center
                    rounded-xl
                    text-muted-foreground
                    hover:bg-muted
                    hover:text-foreground
                    transition
                "
                title="Voir le site"
            >

                <x-icon
                    name="globe"
                    class="w-5 h-5"
                />

            </a>


            {{-- FAVORIS --}}
            @can('member.bookmarks')

                <a
                    href="{{ $bookmarksUrl }}"
                    class="
                        hidden sm:flex
                        h-10 w-10
                        items-center justify-center
                        rounded-xl
                        text-muted-foreground
                        hover:bg-muted
                        hover:text-foreground
                        transition
                    "
                    title="Mes favoris"
                >

                    <x-icon
                        name="bookmark"
                        class="w-5 h-5"
                    />

                </a>

            @endcan


            {{-- MESSAGES --}}
            @can('member.messages')

                <a
                    href="{{ $chatUrl }}"
                    class="
                        relative
                        h-10 w-10
                        flex items-center justify-center
                        rounded-xl
                        text-muted-foreground
                        hover:bg-muted
                        hover:text-foreground
                        transition
                    "
                    title="Mes messages"
                >

                    <x-icon
                        name="message-circle"
                        class="w-5 h-5"
                    />

                    @if($unreadMessages > 0)

                        <span
                            class="
                                absolute
                                -top-0.5 -end-0.5
                                h-5 min-w-5
                                px-1
                                rounded-full
                                bg-red-500
                                text-white
                                text-[10px]
                                font-bold
                                flex items-center justify-center
                                ring-2 ring-card
                            "
                        >
                            {{ $unreadMessages > 99 ? '99+' : $unreadMessages }}
                        </span>

                    @endif

                </a>

            @endcan


            {{-- DARK MODE --}}
            <button
                type="button"
                @click="dark = !dark"
                class="
                    hidden sm:flex
                    h-10 w-10
                    items-center justify-center
                    rounded-xl
                    text-muted-foreground
                    hover:bg-muted
                    hover:text-foreground
                    transition
                "
                title="Changer le thème"
            >

                <x-icon
                    x-show="!dark"
                    name="moon"
                    class="w-5 h-5"
                />

                <x-icon
                    x-show="dark"
                    name="sun"
                    class="w-5 h-5"
                />

            </button>


            {{-- USER --}}
            <div
                x-data="{ open: false }"
                class="relative"
            >

                <button
                    type="button"
                    @click="open = !open"
                    @click.outside="open = false"
                    class="
                        flex items-center gap-2
                        h-10
                        px-2
                        rounded-xl
                        hover:bg-muted
                        transition
                    "
                >

                    @if($user?->profile_photo)

                        <img
                            src="{{ asset('storage/' . $user->profile_photo) }}"
                            alt="{{ $user->name }}"
                            class="
                                h-8 w-8
                                rounded-full
                                object-cover
                            "
                        >

                    @else

                        <div
                            class="
                                h-8 w-8
                                rounded-full
                                bg-primary/10
                                text-primary
                                flex items-center justify-center
                                font-bold
                                text-sm
                            "
                        >
                            {{ strtoupper(substr($user?->name ?? 'M', 0, 1)) }}
                        </div>

                    @endif

                    <span
                        class="
                            hidden lg:block
                            max-w-32
                            truncate
                            text-sm font-medium
                        "
                    >
                        {{ $user?->name }}
                    </span>

                    <x-icon
                        name="chevron-down"
                        class="hidden lg:block w-4 h-4 text-muted-foreground"
                    />

                </button>


                {{-- DROPDOWN --}}
                <div
                    x-cloak
                    x-show="open"
                    x-transition.origin.top.right
                    class="
                        absolute end-0 top-12
                        w-64
                        rounded-2xl
                        border border-border
                        bg-card
                        shadow-2xl
                        overflow-hidden
                    "
                >

                    <div class="p-4 border-b border-border">

                        <div class="font-semibold truncate">
                            {{ $user?->name }}
                        </div>

                        <div class="text-xs text-muted-foreground truncate">
                            {{ $user?->email }}
                        </div>

                        <div class="mt-2">

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    gap-1.5
                                    rounded-full
                                    px-2.5 py-1
                                    text-xs font-medium
                                    bg-emerald-500/10
                                    text-emerald-600
                                "
                            >

                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                                {{ $user->statusLabel() }}

                            </span>

                        </div>

                    </div>


                    <div class="p-2">

                        <a
                            href="{{ $profileUrl }}"
                            class="
                                flex items-center gap-3
                                px-3 py-2.5
                                rounded-xl
                                hover:bg-muted
                                transition
                            "
                        >

                            <x-icon
                                name="user"
                                class="w-4 h-4"
                            />

                            <span class="text-sm">
                                Mon profil
                            </span>

                        </a>


                        <a
                            href="{{ route('member.dashboard') }}"
                            class="
                                flex items-center gap-3
                                px-3 py-2.5
                                rounded-xl
                                hover:bg-muted
                                transition
                            "
                        >

                            <x-icon
                                name="layout-dashboard"
                                class="w-4 h-4"
                            />

                            <span class="text-sm">
                                Mon tableau de bord
                            </span>

                        </a>

                    </div>


                    <div class="border-t border-border p-2">

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="
                                    w-full
                                    flex items-center gap-3
                                    px-3 py-2.5
                                    rounded-xl
                                    text-red-500
                                    hover:bg-red-500/10
                                    transition
                                    text-sm
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

                </div>

            </div>

        </div>

    </div>

</header>