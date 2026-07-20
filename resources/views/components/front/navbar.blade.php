@php
    $navItems = \App\Models\MenuItem::navbar()->active()->with(['children' => fn($q) => $q->active()->orderBy('order')])->orderBy('order')->get();
@endphp

<nav
    x-data="{ scrolled: false, mobileOpen: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 40)"
    :class="scrolled || mobileOpen ? 'bg-white/95 backdrop-blur-md shadow-sm text-foreground' : 'bg-transparent text-white'"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 py-3">
            <a href="{{ route('front.home') }}" class="flex items-center gap-2 shrink-0">
                <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center text-white font-bold">GP</div>
                <span class="font-bold leading-tight">Generation<br class="hidden sm:block"><span class="font-normal text-sm"> PUSH</span></span>
            </a>

            <!-- Liens desktop -->
            <div class="hidden lg:flex items-center gap-1">
                @foreach ($navItems as $item)
                    @if ($item->children->isNotEmpty())
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button class="flex items-center gap-1 px-4 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition-colors duration-200">
                                {{ $item->label }}
                                <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                            </button>
                            <div x-show="open" x-transition x-cloak class="absolute top-full start-0 mt-1 w-56 bg-white text-foreground rounded-lg shadow-lg border border-border p-2">
                                @foreach ($item->children as $child)
                                    <a href="{{ $child->url }}" @if($child->open_in_new_tab) target="_blank" @endif class="block px-3 py-2 rounded-lg text-sm hover:bg-secondary transition-colors duration-200">{{ $child->label }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item->url }}" @if($item->open_in_new_tab) target="_blank" @endif class="px-4 py-2 rounded-lg text-sm font-medium hover:bg-white/10 transition-colors duration-200">
                            {{ $item->label }}
                        </a>
                    @endif
                @endforeach
            </div>

            <!-- Zone connexion / utilisateur connecté -->
            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/10 transition-colors duration-200 cursor-pointer">
                            <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-sm shrink-0">
                                {{ Str::of(auth()->user()->name)->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->join('') }}
                            </div>
                            <span class="text-sm font-medium">{{ Str::before(auth()->user()->name, ' ') }}</span>
                            <x-icon name="chevron-down" class="w-3.5 h-3.5" />
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute end-0 top-full mt-2 w-52 bg-white text-foreground rounded-lg shadow-lg border border-border p-2">
                            @if (auth()->user()->role === 'Admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-secondary transition-colors duration-200">
                                    <x-icon name="layout-dashboard" class="w-4 h-4 text-muted-foreground" /> Tableau de bord
                                </a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-secondary transition-colors duration-200">
                                <x-icon name="users" class="w-4 h-4 text-muted-foreground" /> Mon profil
                            </a>
                            <a href="{{ route('front.chat.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-secondary transition-colors duration-200">
                                <x-icon name="send" class="w-4 h-4 text-muted-foreground" /> Mes messages
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-destructive/10 text-destructive transition-colors duration-200 text-start cursor-pointer">
                                    <x-icon name="log-out" class="w-4 h-4" /> Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium hover:opacity-80 transition-opacity duration-200">Connexion</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-accent text-white text-sm font-medium hover:opacity-90 transition-all duration-200">
                        Rejoindre
                    </a>
                @endauth
            </div>

            <!-- Bouton mobile -->
            <button @click="mobileOpen = !mobileOpen" class="lg:hidden p-2 rounded-lg cursor-pointer">
                <x-icon :name="'menu'" class="w-6 h-6" x-show="!mobileOpen" />
                <x-icon :name="'x'" class="w-6 h-6" x-show="mobileOpen" x-cloak />
            </button>
        </div>
    </div>

    <!-- Menu mobile -->
    <div x-show="mobileOpen" x-cloak x-transition class="lg:hidden bg-white text-foreground border-t border-border">
        <div class="px-4 py-4 space-y-1">
            @foreach ($navItems as $item)
                <a href="{{ $item->url }}" class="block px-3 py-2.5 rounded-lg text-sm font-medium hover:bg-secondary transition-colors duration-200">{{ $item->label }}</a>
                @foreach ($item->children as $child)
                    <a href="{{ $child->url }}" class="block px-6 py-2 rounded-lg text-sm text-muted-foreground hover:bg-secondary transition-colors duration-200">{{ $child->label }}</a>
                @endforeach
            @endforeach

            <div class="pt-3 mt-3 border-t border-border flex flex-col gap-2">
                @auth
                    @if (auth()->user()->role === 'Admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-center border border-border">Tableau de bord</a>
                    @endif
                    <a href="{{ route('profile.edit') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-center border border-border">Mon profil</a>
                    <a href="{{ route('front.chat.index') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-center border border-border">Mes messages</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-3 py-2.5 rounded-lg text-sm font-medium text-center bg-destructive text-white cursor-pointer">Déconnexion</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-center border border-border">Connexion</a>
                    <a href="{{ route('register') }}" class="px-3 py-2.5 rounded-lg text-sm font-medium text-center bg-accent text-white">Rejoindre</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
