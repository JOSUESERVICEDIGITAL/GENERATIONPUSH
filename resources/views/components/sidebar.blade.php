@php
    $navItems = [
        [
            'label' => __('nav.dashboard'),
            'href' => route('admin.dashboard'),
            'icon' => 'layout-dashboard',
            'match' => 'admin/dashboard*',
        ],
        [
            'label' => __('nav.users'),
            'icon' => 'users',
            'match' => 'admin/users*,admin/members*,admin/leaders*',
            'children' => [
                ['label' => __('nav.all_users'), 'href' => route('admin.users.index'), 'match' => 'admin/users*'],
                ['label' => __('nav.members'), 'href' => route('admin.members.index'), 'match' => 'admin/members*'],
                ['label' => __('nav.leaders'), 'href' => route('admin.leaders.index'), 'match' => 'admin/leaders*'],
            ],
        ],
        [
            'label' => __('nav.programs'),
            'icon' => 'book-open',
            'match' => 'admin/programs*',
            'children' => [
                ['label' => __('nav.formations'), 'href' => route('admin.programs.formations.index'), 'match' => 'admin/programs/formations*'],
                ['label' => __('nav.courses'), 'href' => route('admin.programs.courses.index'), 'match' => 'admin/programs/courses*'],
                ['label' => __('nav.library'), 'href' => route('admin.programs.library.index'), 'match' => 'admin/programs/library*'],
            ],
        ],
        [
            'label' => __('nav.events'),
            'icon' => 'calendar',
            'match' => 'admin/events*',
            'children' => [
                ['label' => __('nav.conferences'), 'href' => route('admin.events.conferences.index'), 'match' => 'admin/events/conferences*'],
                ['label' => __('nav.masterclass'), 'href' => route('admin.events.masterclass.index'), 'match' => 'admin/events/masterclass*'],
                ['label' => __('nav.coaching'), 'href' => route('admin.events.coaching.index'), 'match' => 'admin/events/coaching*'],
                ['label' => __('nav.reservations'), 'href' => route('admin.events.reservations.index'), 'match' => 'admin/events/reservations*'],
                ['label' => __('nav.tickets'), 'href' => route('admin.events.tickets.index'), 'match' => 'admin/events/tickets*'],
            ],
        ],
        [
            'label' => __('nav.payments'),
            'icon' => 'dollar-sign',
            'match' => 'admin/payments*',
            'children' => [
                ['label' => __('nav.transactions'), 'href' => route('admin.payments.transactions.index'), 'match' => 'admin/payments/transactions*'],
                ['label' => __('nav.subscriptions'), 'href' => route('admin.payments.subscriptions.index'), 'match' => 'admin/payments/subscriptions*'],
                ['label' => __('nav.invoices'), 'href' => route('admin.payments.invoices.index'), 'match' => 'admin/payments/invoices*'],
            ],
        ],
        [
            'label' => __('nav.shop'),
            'icon' => 'shopping-bag',
            'match' => 'admin/shop*',
            'children' => [
                ['label' => __('nav.products'), 'href' => route('admin.shop.products.index'), 'match' => 'admin/shop/products*'],
                ['label' => __('nav.orders'), 'href' => route('admin.shop.orders.index'), 'match' => 'admin/shop/orders*'],
            ],
        ],
        [
            'label' => __('nav.content'),
            'icon' => 'file-text',
            'match' => 'admin/content*',
            'children' => [
                ['label' => __('nav.blog'), 'href' => route('admin.content.blog.index'), 'match' => 'admin/content/blog*'],
                ['label' => __('nav.articles'), 'href' => route('admin.content.articles.index'), 'match' => 'admin/content/articles*'],
                ['label' => __('nav.categories'), 'href' => route('admin.content.categories.index'), 'match' => 'admin/content/categories*'],
            ],
        ],
        [
            'label' => __('nav.communications'),
            'icon' => 'mail',
            'match' => 'admin/communications*',
            'children' => [
                ['label' => __('nav.newsletter'), 'href' => route('admin.communications.newsletter.index'), 'match' => 'admin/communications/newsletter*'],
                ['label' => __('nav.sms'), 'href' => route('admin.communications.sms.index'), 'match' => 'admin/communications/sms*'],
                ['label' => __('nav.notifications'), 'href' => route('admin.communications.notifications.index'), 'match' => 'admin/communications/notifications*'],
                ['label' => 'Contact', 'href' => route('admin.communications.messages.index'), 'match' => 'admin/communications/messages*'],
                ['label' => 'Chat interne', 'href' => route('admin.communications.chat.index'), 'match' => 'admin/communications/chat*'],
                ['label' => 'Abonnés', 'href' => route('admin.communications.subscribers.index'), 'match' => 'admin/communications/subscribers*'],
            ],
        ],
        [
            'label' => __('nav.media'),
            'icon' => 'image',
            'match' => 'admin/media*',
            'children' => [
                ['label' => __('nav.gallery'), 'href' => route('admin.media.gallery.index'), 'match' => 'admin/media/gallery*'],
                ['label' => __('nav.videos'), 'href' => route('admin.media.videos.index'), 'match' => 'admin/media/videos*'],
            ],
        ],
        [
            'label' => __('nav.partners'),
            'icon' => 'users-round',
            'match' => 'admin/partners*',
            'children' => [
                ['label' => __('nav.sponsors'), 'href' => route('admin.partners.sponsors.index'), 'match' => 'admin/partners/sponsors*'],
                ['label' => __('nav.testimonials'), 'href' => route('admin.partners.testimonials.index'), 'match' => 'admin/partners/testimonials*'],
                ['label' => __('nav.team'), 'href' => route('admin.partners.team.index'), 'match' => 'admin/partners/team*'],
            ],
        ],
        [
            'label' => __('nav.pages'),
            'icon' => 'globe',
            'match' => 'admin/pages*',
            'children' => [
                ['label' => __('nav.page_home'), 'href' => route('admin.pages.home.edit'), 'match' => 'admin/pages/home*'],
                ['label' => __('nav.page_navigation'), 'href' => route('admin.pages.navigation.index'), 'match' => 'admin/pages/navigation*'],
                ['label' => __('nav.page_footer'), 'href' => route('admin.pages.footer.index'), 'match' => 'admin/pages/footer*'],
                ['label' => 'Pages personnalisées', 'href' => route('admin.pages.custom.index'), 'match' => 'admin/pages/custom*'],
                ['label' => 'Fondatrice', 'href' => route('admin.pages.founder.edit'), 'match' => 'admin/pages/founder*'],
            ],
        ],
    ];

    $isActive = function ($pattern) {
        foreach (explode(',', $pattern) as $p) {
            if (request()->is(trim($p))) return true;
        }
        return false;
    };
@endphp

<!-- Bouton menu mobile -->
<button
    @click="mobileSidebarOpen = !mobileSidebarOpen"
    class="fixed top-4 start-4 z-50 md:hidden p-2 rounded-lg bg-card hover:bg-secondary border border-border"
>
    <svg x-show="!mobileSidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
    <svg x-show="mobileSidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
</button>

<!-- Overlay mobile -->
<div
    x-show="mobileSidebarOpen"
    x-transition.opacity
    @click="mobileSidebarOpen = false"
    class="fixed inset-0 bg-black/50 z-40 md:hidden"
    style="display: none;"
></div>

<aside
    class="fixed top-0 start-0 h-screen bg-sidebar border-e border-sidebar-border transition-all duration-300 ease-in-out z-40 flex flex-col overflow-hidden"
    :class="[sidebarOpen ? 'w-64' : 'w-20', mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full rtl:translate-x-full md:translate-x-0']"
>
    <!-- Logo -->
    <div class="flex items-center gap-3 px-4 py-6 border-b border-sidebar-border" :class="!sidebarOpen && 'justify-center'">
        <div class="w-10 h-10 shrink-0 rounded-lg bg-accent flex items-center justify-center text-white font-bold text-lg">
            GP
        </div>
        <div x-show="sidebarOpen" x-cloak class="flex-1">
            <h1 class="font-bold text-lg leading-tight text-foreground">Generation</h1>
            <p class="text-xs text-muted-foreground">PUSH</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto scrollbar-none px-2 py-4 space-y-1">
        @foreach ($navItems as $item)
            @php $active = $isActive($item['match']); $children = $item['children'] ?? null; @endphp
            <div x-data="{ expanded: {{ $active && !empty($children) ? 'true' : 'false' }} }">
                <a
                    href="{{ $children ? '#' : $item['href'] }}"
                    @if($children) @click.prevent="expanded = !expanded" @endif
                    class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-secondary text-foreground {{ $active ? 'bg-accent text-accent-foreground font-semibold hover:bg-accent' : '' }}"
                    :class="!sidebarOpen && 'justify-center px-3'"
                >
                    <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                    <span x-show="sidebarOpen" x-cloak class="flex-1 text-sm font-medium truncate">{{ $item['label'] }}</span>
                    @if($children)
                        <svg x-show="sidebarOpen" x-cloak class="w-4 h-4 transition-transform duration-200" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    @endif
                </a>

                @if($children)
                    <div
                        x-show="sidebarOpen && expanded"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        class="mt-1 ms-4 ps-4 border-s border-sidebar-border space-y-1"
                    >
                        @foreach ($children as $child)
                            <a
                                href="{{ $child['href'] }}"
                                class="block px-4 py-2 rounded-lg text-sm transition-all duration-200 hover:bg-secondary text-foreground {{ $isActive($child['match']) ? 'bg-accent text-accent-foreground font-semibold' : '' }}"
                            >
                                {{ $child['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </nav>

    <!-- Actions du bas -->
    <div class="border-t border-sidebar-border p-4 space-y-2">
        <a
            href="{{ route('admin.settings.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-secondary text-foreground"
            :class="!sidebarOpen && 'justify-center px-3'"
        >
            <x-icon name="settings" class="w-5 h-5 shrink-0" />
            <span x-show="sidebarOpen" x-cloak class="text-sm font-medium">{{ __('nav.settings') }}</span>
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="w-full flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-destructive/10 text-destructive"
                :class="!sidebarOpen && 'justify-center px-3'"
            >
                <x-icon name="log-out" class="w-5 h-5 shrink-0" />
                <span x-show="sidebarOpen" x-cloak class="text-sm font-medium">{{ __('nav.logout') }}</span>
            </button>
        </form>
    </div>
</aside>
