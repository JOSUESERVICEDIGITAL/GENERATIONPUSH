<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    @php
        $navUser = Auth::user();

        $navInitials = Str::of($navUser->name ?? 'Admin')
            ->explode(' ')
            ->filter()
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->take(2)
            ->join('');
    @endphp

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <div class="flex">

                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <div class="w-9 h-9 rounded-lg bg-[#E8631A] flex items-center justify-center text-white font-bold">
                            GP
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link
                        :href="route('admin.dashboard')"
                        :active="request()->routeIs('admin.dashboard')"
                    >
                        {{ __('nav.dashboard') }}
                    </x-nav-link>
                </div>

            </div>


            <!-- =====================================================
                 SETTINGS DROPDOWN DESKTOP
                 ===================================================== -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">

                <x-dropdown align="right" width="56">

                    <x-slot name="trigger">

                        <button
                            class="
                                flex
                                items-center
                                gap-3
                                px-2
                                py-1.5
                                rounded-xl
                                text-sm
                                font-medium
                                text-gray-600
                                hover:text-gray-900
                                hover:bg-gray-50
                                focus:outline-none
                                transition
                                duration-150
                            "
                        >

                            <!-- Photo de profil -->
                            <div class="w-9 h-9 rounded-full overflow-hidden shrink-0 bg-[#E8631A] flex items-center justify-center text-white font-bold text-xs">

                                @if ($navUser?->profile_photo)
                                    <img
                                        src="{{ Storage::url($navUser->profile_photo) }}"
                                        alt="{{ $navUser->name }}"
                                        class="w-full h-full object-cover"
                                    >
                                @else
                                    {{ $navInitials }}
                                @endif

                            </div>


                            <!-- Nom -->
                            <div class="hidden md:block text-start">
                                <div class="font-semibold text-gray-800 leading-tight">
                                    {{ $navUser->name }}
                                </div>

                                <div class="text-xs text-gray-400 font-normal">
                                    {{ $navUser->email }}
                                </div>
                            </div>


                            <!-- Chevron -->
                            <div class="ms-1">

                                <svg
                                    class="fill-current h-4 w-4 text-gray-400"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd"
                                    />
                                </svg>

                            </div>

                        </button>

                    </x-slot>


                    <x-slot name="content">

                        <!-- En-tête du profil -->
                        <div class="px-4 py-4 border-b border-gray-100">

                            <div class="flex items-center gap-3">

                                <div class="w-11 h-11 rounded-full overflow-hidden shrink-0 bg-[#E8631A] flex items-center justify-center text-white font-bold">

                                    @if ($navUser?->profile_photo)
                                        <img
                                            src="{{ Storage::url($navUser->profile_photo) }}"
                                            alt="{{ $navUser->name }}"
                                            class="w-full h-full object-cover"
                                        >
                                    @else
                                        {{ $navInitials }}
                                    @endif

                                </div>

                                <div class="min-w-0">

                                    <div class="font-semibold text-gray-800 truncate">
                                        {{ $navUser->name }}
                                    </div>

                                    <div class="text-sm text-gray-500 truncate">
                                        {{ $navUser->email }}
                                    </div>

                                </div>

                            </div>

                        </div>


                        <!-- Navigation -->
                        <div class="p-1">

                            <x-dropdown-link :href="route('admin.dashboard')">
                                {{ __('nav.dashboard') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('nav.my_profile') }}
                            </x-dropdown-link>

                        </div>


                        <!-- Authentication -->
                        <div class="border-t border-gray-100 p-1">

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link
                                    :href="route('logout')"
                                    onclick="
                                        event.preventDefault();
                                        this.closest('form').submit();
                                    "
                                >
                                    {{ __('nav.logout') }}
                                </x-dropdown-link>

                            </form>

                        </div>

                    </x-slot>

                </x-dropdown>

            </div>


            <!-- =====================================================
                 HAMBURGER MOBILE
                 ===================================================== -->
            <div class="-me-2 flex items-center sm:hidden">

                <button
                    @click="open = ! open"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        p-2
                        rounded-md
                        text-gray-400
                        hover:text-gray-500
                        hover:bg-gray-100
                        focus:outline-none
                        focus:bg-gray-100
                        focus:text-gray-500
                        transition
                        duration-150
                    "
                >

                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <path
                            :class="{
                                'hidden': open,
                                'inline-flex': !open
                            }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{
                                'hidden': !open,
                                'inline-flex': open
                            }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>

        </div>
    </div>


    <!-- =============================================================
         RESPONSIVE NAVIGATION MENU
         ============================================================= -->
    <div
        :class="{
            'block': open,
            'hidden': !open
        }"
        class="hidden sm:hidden"
    >

        <div class="pt-2 pb-3 space-y-1">

            <x-responsive-nav-link
                :href="route('admin.dashboard')"
                :active="request()->routeIs('admin.dashboard')"
            >
                {{ __('nav.dashboard') }}
            </x-responsive-nav-link>

        </div>


        <!-- =========================================================
             RESPONSIVE PROFILE
             ========================================================= -->
        <div class="pt-4 pb-1 border-t border-gray-200">

            <div class="px-4">

                <div class="flex items-center gap-3">

                    <!-- Photo mobile -->
                    <div class="w-12 h-12 rounded-full overflow-hidden shrink-0 bg-[#E8631A] flex items-center justify-center text-white font-bold">

                        @if ($navUser?->profile_photo)

                            <img
                                src="{{ Storage::url($navUser->profile_photo) }}"
                                alt="{{ $navUser->name }}"
                                class="w-full h-full object-cover"
                            >

                        @else

                            {{ $navInitials }}

                        @endif

                    </div>


                    <div class="min-w-0">

                        <div class="font-semibold text-base text-gray-800 truncate">
                            {{ $navUser->name }}
                        </div>

                        <div class="font-medium text-sm text-gray-500 truncate">
                            {{ $navUser->email }}
                        </div>

                    </div>

                </div>

            </div>


            <div class="mt-3 space-y-1">

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('nav.my_profile') }}
                </x-responsive-nav-link>


                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link
                        :href="route('logout')"
                        onclick="
                            event.preventDefault();
                            this.closest('form').submit();
                        "
                    >
                        {{ __('nav.logout') }}
                    </x-responsive-nav-link>

                </form>

            </div>

        </div>

    </div>

</nav>
