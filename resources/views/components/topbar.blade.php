@props(['title' => null])

@php
    $locales = [
        'fr' => ['label' => 'Français', 'short' => 'FR', 'flag' => '🇫🇷'],
        'en' => ['label' => 'English', 'short' => 'EN', 'flag' => '🇬🇧'],
        'ar' => ['label' => 'العربية', 'short' => 'AR', 'flag' => '🇸🇦'],
    ];
    $currentLocale = $locales[app()->getLocale()] ?? $locales['fr'];

    // Agenda : 5 prochains événements toutes catégories confondues (utilisé par l'icône calendrier)
    $today = \Illuminate\Support\Carbon::today();
    $upcomingEvents = collect()
        ->concat(\App\Models\Conference::where('date', '>=', $today)->orderBy('date')->take(5)->get()->map(fn ($e) => ['title' => $e->title, 'date' => $e->date, 'url' => route('admin.events.conferences.index'), 'type' => 'Conférence']))
        ->concat(\App\Models\Masterclass::where('date', '>=', $today)->orderBy('date')->take(5)->get()->map(fn ($e) => ['title' => $e->title, 'date' => $e->date, 'url' => route('admin.events.masterclass.index'), 'type' => 'Masterclass']))
        ->concat(\App\Models\CoachingSession::where('date', '>=', $today)->orderBy('date')->take(5)->get()->map(fn ($e) => ['title' => $e->title, 'date' => $e->date, 'url' => route('admin.events.coaching.index'), 'type' => 'Coaching']))
        ->sortBy('date')
        ->take(5);

    // Notifications : flux réel des derniers événements notables (pas de démo statique)
    $recentActivity = collect()
        ->concat(\App\Models\User::latest()->take(3)->get()->map(fn ($u) => [
            'icon' => 'users', 'color' => 'text-accent bg-accent/10',
            'text' => "Nouvel utilisateur : {$u->name}",
            'date' => $u->created_at, 'url' => route('admin.users.index'),
        ]))
        ->concat(\App\Models\ContactMessage::where('status', 'new')->latest()->take(3)->get()->map(fn ($m) => [
            'icon' => 'mail', 'color' => 'text-blue-600 bg-blue-500/10',
            'text' => "Message de {$m->name}",
            'date' => $m->created_at, 'url' => route('admin.communications.messages.index'),
        ]))
        ->concat(\App\Models\Order::latest()->take(3)->get()->map(fn ($o) => [
            'icon' => 'shopping-bag', 'color' => 'text-green-600 bg-green-500/10',
            'text' => "Nouvelle commande {$o->order_number}",
            'date' => $o->created_at, 'url' => route('admin.shop.orders.index'),
        ]))
        ->concat(\App\Models\Transaction::where('status', 'completed')->latest()->take(3)->get()->map(fn ($t) => [
            'icon' => 'dollar-sign', 'color' => 'text-green-600 bg-green-500/10',
            'text' => 'Paiement reçu : ' . number_format($t->amount, 2) . ' $',
            'date' => $t->created_at, 'url' => route('admin.payments.transactions.index'),
        ]))
        ->concat(\App\Models\ChatMessage::where('is_from_admin', false)->whereNull('read_by_admin_at')->with('user')->latest()->take(3)->get()->map(fn ($c) => [
            'icon' => 'send', 'color' => 'text-purple-600 bg-purple-500/10',
            'text' => 'Chat : ' . ($c->user->name ?? 'Membre') . ' — ' . \Illuminate\Support\Str::limit($c->content, 40),
            'date' => $c->created_at, 'url' => route('admin.communications.chat.show', $c->user_id),
        ]))
        ->sortByDesc('date')
        ->take(6);

    $unreadCount = \App\Models\ContactMessage::where('status', 'new')->count()
        + \App\Models\ChatMessage::where('is_from_admin', false)->whereNull('read_by_admin_at')->count();

    $quickCreateLinks = [
        ['label' => 'Utilisateur', 'url' => route('admin.users.create'), 'icon' => 'users'],
        ['label' => 'Formation', 'url' => route('admin.programs.formations.index'), 'icon' => 'book-open'],
        ['label' => 'Conférence', 'url' => route('admin.events.conferences.index'), 'icon' => 'calendar'],
        ['label' => 'Produit boutique', 'url' => route('admin.shop.products.index'), 'icon' => 'shopping-bag'],
    ];
@endphp

<header
    x-data="{ mobileSearchOpen: false }"
    class="fixed top-0 end-0 start-0 h-16 bg-card border-b border-border z-30 transition-all duration-300"
    :class="sidebarOpen ? 'md:ms-64' : 'md:ms-20'"
>
    <div class="flex items-center justify-between h-full px-3 sm:px-4 md:px-6 gap-2 sm:gap-4">

        <!-- Toggle desktop sidebar -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            :title="'{{ __('nav.toggle_sidebar') }}'"
            class="hidden md:flex p-2 rounded-lg hover:bg-secondary text-foreground shrink-0 cursor-pointer"
        >
            <x-icon name="menu" class="w-5 h-5" />
        </button>

        <div class="w-9 md:hidden shrink-0"></div>

        @if($title)
            <h1 class="font-semibold text-foreground truncate sm:hidden" x-show="!mobileSearchOpen">{{ $title }}</h1>
        @endif

        <!-- Recherche -->
        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex-1 items-center min-w-0 hidden sm:flex" :class="mobileSearchOpen && '!flex'">
            <div class="relative w-full max-w-sm">
                <x-icon name="search" class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none" />
                <input
                    type="text"
                    name="q"
                    x-ref="searchInput"
                    placeholder="{{ __('nav.search_placeholder') }}"
                    class="w-full ps-10 pe-4 py-2 rounded-lg border border-border bg-secondary text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200 cursor-text"
                >
            </div>
        </form>

        <button
            type="button"
            @click="mobileSearchOpen = !mobileSearchOpen; if (mobileSearchOpen) $nextTick(() => $refs.searchInput.focus())"
            class="sm:hidden p-2 rounded-lg hover:bg-secondary text-foreground shrink-0 ms-auto cursor-pointer"
            x-show="!mobileSearchOpen"
        >
            <x-icon name="search" class="w-5 h-5" />
        </button>

        <!-- Actions & profil -->
        <div class="flex items-center gap-1 sm:gap-2 md:gap-3">

            <!-- Créer (quick-create) -->
            <div class="relative hidden lg:block" x-data="{ createMenuOpen: false }" @click.outside="createMenuOpen = false">
                <button
                    type="button"
                    @click="createMenuOpen = !createMenuOpen"
                    class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200 shrink-0 cursor-pointer"
                >
                    <x-icon name="plus" class="w-4 h-4" />
                    <span>{{ __('nav.create') }}</span>
                </button>

                <div
                    x-show="createMenuOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute end-0 mt-2 w-56 bg-card border border-border rounded-lg shadow-lg z-50 p-1"
                >
                    @foreach ($quickCreateLinks as $link)
                        <a href="{{ $link['url'] }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-foreground hover:bg-secondary transition-all duration-200 cursor-pointer">
                            <x-icon :name="$link['icon']" class="w-4 h-4 text-muted-foreground" />
                            <span>{{ $link['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Agenda (prochains événements) -->
            <div class="relative" x-data="{ agendaOpen: false }" @click.outside="agendaOpen = false">
                <button
                    type="button"
                    @click="agendaOpen = !agendaOpen"
                    class="hidden md:flex p-2 rounded-lg hover:bg-secondary text-foreground cursor-pointer relative"
                >
                    <x-icon name="calendar" class="w-5 h-5" />
                    @if ($upcomingEvents->isNotEmpty())
                        <span class="absolute -top-1 -end-1 min-w-[18px] h-[18px] px-1 rounded-full bg-accent text-white text-[10px] font-bold flex items-center justify-center">{{ $upcomingEvents->count() }}</span>
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
                    <div class="p-4 border-b border-border">
                        <p class="font-semibold text-foreground text-sm">Prochains événements</p>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-border">
                        @forelse ($upcomingEvents as $event)
                            <a href="{{ $event['url'] }}" class="p-4 flex items-center justify-between gap-3 hover:bg-secondary/50 transition-colors duration-200 cursor-pointer">
                                <div class="min-w-0">
                                    <p class="text-sm text-foreground truncate">{{ $event['title'] }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">{{ $event['type'] }} — {{ $event['date']?->translatedFormat('d M Y') }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="p-4 text-sm text-muted-foreground">Aucun événement à venir</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Messages (vers Communications) -->
            <a
                href="{{ route('admin.communications.newsletter.index') }}"
                class="hidden sm:flex p-2 rounded-lg hover:bg-secondary text-foreground relative cursor-pointer"
                title="Communications"
            >
                <x-icon name="mail" class="w-5 h-5" />
            </a>

            <!-- Notifications -->
            <div class="relative" x-data="{ notifOpen: false }" @click.outside="notifOpen = false">
                <button type="button" @click="notifOpen = !notifOpen" class="p-2 rounded-lg hover:bg-secondary text-foreground relative cursor-pointer">
                    <x-icon name="bell" class="w-5 h-5" />
                    @if ($unreadCount > 0)
                        <span class="absolute -top-1 -end-1 min-w-[18px] h-[18px] px-1 rounded-full bg-destructive text-white text-[10px] font-bold flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
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
                    <div class="p-4 border-b border-border flex items-center justify-between">
                        <p class="font-semibold text-foreground text-sm">{{ __('nav.notifications') }}</p>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-border">
                        @forelse ($recentActivity as $activity)
                            <a href="{{ $activity['url'] }}" class="p-4 flex gap-3 hover:bg-secondary/50 transition-colors duration-200 cursor-pointer">
                                <div class="w-8 h-8 rounded-full {{ $activity['color'] }} flex items-center justify-center shrink-0">
                                    <x-icon :name="$activity['icon']" class="w-4 h-4" />
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm text-foreground truncate">{{ $activity['text'] }}</p>
                                    <p class="text-xs text-muted-foreground mt-0.5">{{ $activity['date']->diffForHumans() }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="p-4 text-sm text-muted-foreground">{{ __('nav.no_new_notifications') }}</p>
                        @endforelse
                    </div>
                    <div class="p-3 border-t border-border text-center">
                        <a href="{{ route('admin.communications.notifications.index') }}" class="text-xs font-medium text-accent hover:underline cursor-pointer">{{ __('nav.view_all_notifications') }}</a>
                    </div>
                </div>
            </div>

            <!-- Sélecteur de langue -->
            <div class="relative" x-data="{ langOpen: false }" @click.outside="langOpen = false">
                <button type="button" @click="langOpen = !langOpen" :title="'{{ __('nav.language') }}'" class="flex items-center gap-1.5 p-2 rounded-lg hover:bg-secondary text-foreground cursor-pointer">
                    <x-icon name="globe" class="w-5 h-5" />
                    <span class="hidden sm:inline text-xs font-semibold">{{ $currentLocale['short'] }}</span>
                </button>

                <div
                    x-show="langOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute end-0 mt-2 w-44 bg-card border border-border rounded-lg shadow-lg z-50 p-1"
                >
                    @foreach ($locales as $code => $locale)
                        <a
                            href="{{ route('locale.switch', $code) }}"
                            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm hover:bg-secondary transition-all duration-200 cursor-pointer {{ app()->getLocale() === $code ? 'bg-accent/10 text-accent font-semibold' : 'text-foreground' }}"
                        >
                            <span>{{ $locale['flag'] }}</span>
                            <span>{{ $locale['label'] }}</span>
                            @if(app()->getLocale() === $code)
                                <x-icon name="check" class="w-3.5 h-3.5 ms-auto" />
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Dark mode -->
            <button type="button" @click="dark = !dark" :title="'{{ __('nav.toggle_theme') }}'" class="p-2 rounded-lg hover:bg-secondary text-foreground cursor-pointer">
                <x-icon x-show="!dark" name="moon" class="w-5 h-5" />
                <x-icon x-show="dark" x-cloak name="sun" class="w-5 h-5" />
            </button>

            <!-- Profil -->
            <div class="relative" x-data="{ profileOpen: false }" @click.outside="profileOpen = false">
                <button type="button" @click="profileOpen = !profileOpen" class="flex items-center gap-2 ps-1 sm:ps-2 pe-1 sm:pe-3 py-1 rounded-lg hover:bg-secondary transition-all duration-200 text-foreground cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-sm shrink-0">
                        {{ Str::of(auth()->user()->name ?? 'Admin')->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->join('') }}
                    </div>
                    <x-icon name="chevron-down" class="hidden sm:block w-4 h-4 transition-transform duration-200" x-bind:class="profileOpen ? 'rotate-180' : ''" />
                </button>

                <div
                    x-show="profileOpen"
                    x-cloak
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute end-0 mt-2 w-48 bg-card border border-border rounded-lg shadow-lg z-50"
                >
                    <div class="p-4 border-b border-border">
                        <p class="font-semibold text-foreground">{{ auth()->user()->name ?? 'Admin' }}</p>
                        <p class="text-sm text-muted-foreground truncate">{{ auth()->user()->email ?? 'admin@generationpush.com' }}</p>
                    </div>
                    <nav class="p-2 space-y-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg hover:bg-secondary text-foreground transition-all duration-200 text-sm cursor-pointer">{{ __('nav.my_profile') }}</a>
                        <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 rounded-lg hover:bg-secondary text-foreground transition-all duration-200 text-sm cursor-pointer">{{ __('nav.settings') }}</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-start px-4 py-2 rounded-lg hover:bg-destructive/10 text-destructive transition-all duration-200 text-sm cursor-pointer">{{ __('nav.logout') }}</button>
                        </form>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
