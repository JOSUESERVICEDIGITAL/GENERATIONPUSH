<x-layouts.public title="À propos — Generation PUSH">

    <x-front.page-banner title="À propos de nous" subtitle="Découvre la mission, l'histoire et l'équipe derrière Generation PUSH" />

    <!-- Histoire -->
    <section class="py-20 md:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div x-data x-reveal="'left'">
                @if ($settings->aboutImageUrl())
                    <img src="{{ $settings->aboutImageUrl() }}" class="rounded-2xl shadow-xl w-full aspect-[4/3] object-cover">
                @else
                    <div class="rounded-2xl shadow-xl w-full aspect-[4/3] bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                        <div class="w-20 h-20 rounded-2xl bg-accent flex items-center justify-center text-white font-bold text-3xl">GP</div>
                    </div>
                @endif
            </div>
            <div x-data x-reveal="'right'">
                <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Notre histoire</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A] mb-6">{{ $settings->about_title ?? 'Une communauté panafricaine de leadership' }}</h2>
                <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $settings->about_text ?? "Generation PUSH accompagne des milliers de jeunes leaders à travers l'Afrique de l'Ouest." }}</p>

                <div class="grid grid-cols-3 gap-6 mt-10 pt-8 border-t border-gray-100">
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold text-accent">{{ number_format($stats['members']) }}+</p>
                        <p class="text-xs text-gray-500 mt-1">Membres</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold text-accent">{{ number_format($stats['formations']) }}+</p>
                        <p class="text-xs text-gray-500 mt-1">Formations</p>
                    </div>
                    <div>
                        <p class="text-2xl sm:text-3xl font-extrabold text-accent">{{ number_format($stats['events']) }}+</p>
                        <p class="text-xs text-gray-500 mt-1">Événements</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Valeurs -->
    <section class="py-20 md:py-28 bg-[#F8F9FA]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Nos valeurs</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Ce qui nous anime</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ([
                    ['icon' => 'zap', 'title' => 'Excellence', 'text' => 'Nous visons l\'excellence dans chaque formation et chaque événement que nous organisons.'],
                    ['icon' => 'users-round', 'title' => 'Communauté', 'text' => 'Un réseau solidaire de leaders qui s\'entraident et grandissent ensemble.'],
                    ['icon' => 'award', 'title' => 'Impact', 'text' => 'Chaque action est pensée pour avoir un impact durable sur le continent africain.'],
                ] as $i => $value)
                    <div class="bg-white rounded-2xl p-8 text-center shadow-sm hover:shadow-lg transition-shadow duration-300" x-data x-reveal.delay.{{ $i * 100 }}>
                        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-5">
                            <x-icon :name="$value['icon']" class="w-6 h-6 text-accent" />
                        </div>
                        <h3 class="font-bold text-lg text-[#1A1A1A] mb-2">{{ $value['title'] }}</h3>
                        <p class="text-sm text-gray-600">{{ $value['text'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Équipe -->
    @if ($team->isNotEmpty())
        <section class="py-20 md:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                    <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">L'équipe</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Les personnes derrière Generation PUSH</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    @foreach ($team as $i => $member)
                        <div class="text-center" x-data x-reveal.delay.{{ $i * 80 }}>
                            <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-accent/10 mx-auto mb-4 overflow-hidden flex items-center justify-center">
                                @if ($member->photoUrl())
                                    <img src="{{ $member->photoUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-2xl font-bold text-accent">{{ Str::substr($member->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <h3 class="font-semibold text-[#1A1A1A] text-sm">{{ $member->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $member->role }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- CTA -->
    <section class="py-20 bg-[#1A1A1A]">
        <div class="max-w-3xl mx-auto px-4 text-center" x-data x-reveal="'zoom'">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Prêt à rejoindre l'aventure ?</h2>
            <p class="text-gray-400 mb-8">Deviens membre de Generation PUSH dès aujourd'hui.</p>
            <a href="{{ route('register') }}" class="inline-block px-8 py-3.5 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200">
                Rejoindre la communauté
            </a>
        </div>
    </section>

</x-layouts.public>
