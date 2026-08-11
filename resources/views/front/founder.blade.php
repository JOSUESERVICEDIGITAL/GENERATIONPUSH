<x-layouts.public :title="($founder->name ?? 'Notre fondatrice') . ' — Generation PUSH'">

    <link href="https://fonts.bunny.net/css?family=lora:600,700&display=swap" rel="stylesheet" />

    <main>
        <!-- Hero -->
        <header class="relative pt-32 pb-40 md:pt-40 md:pb-56 bg-[#1A1A1A] overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-accent/25 via-transparent to-transparent"></div>
            <div class="absolute -top-24 -end-24 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>

            <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data x-reveal>
                <p class="inline-block px-4 py-1.5 rounded-full bg-accent/20 border border-accent/40 text-white text-xs sm:text-sm font-semibold tracking-wide uppercase mb-6 backdrop-blur-sm">
                    Fondatrice
                </p>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold text-white leading-tight font-serif">
                    {{ $founder->name ?? 'Notre fondatrice' }}
                </h1>
                @if ($founder->role_title)
                    <p class="mt-4 text-lg text-gray-300">{{ $founder->role_title }}</p>
                @endif

                @if ($founder->show_social && ! empty($founder->socials()))
                    <div class="flex items-center justify-center gap-3 mt-8">
                        @foreach ($founder->socials() as $network => $url)
                            <a href="{{ $url }}" target="_blank" rel="noopener"
                               class="w-10 h-10 rounded-full bg-white/10 hover:bg-accent flex items-center justify-center transition-colors duration-200 text-white"
                               aria-label="Suivez-nous sur {{ ucfirst($network) }}">
                                <x-icon :name="$network" class="w-4 h-4" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </header>

        <!-- Portrait circulaire avec diaporama -->
        <div class="relative -mt-32 md:-mt-40 z-10">
            <div
                x-data="carousel(@js($photoUrls))"
                class="relative w-64 h-64 sm:w-80 sm:h-80 mx-auto rounded-full overflow-hidden shadow-2xl ring-8 ring-white bg-gradient-to-br from-accent/20 to-accent/5"
                role="img"
                aria-label="Portrait de {{ $founder->name ?? 'la fondatrice' }}"
            >
                <template x-for="(photo, i) in photos" :key="i">
                    <img
                        x-show="index === i"
                        :src="photo"
                        x-transition:enter="transition ease-out duration-700"
                        x-transition:enter-start="opacity-0 scale-105"
                        x-transition:enter-end="opacity-100 scale-100"
                        class="absolute inset-0 w-full h-full object-cover object-center"
                        :alt="'Portrait de {{ $founder->name }} – photo ' + (i+1)"
                        loading="lazy"
                    />
                </template>
                <div x-show="photos.length === 0" class="absolute inset-0 flex items-center justify-center">
                    <div class="w-20 h-20 rounded-2xl bg-accent flex items-center justify-center text-white font-bold text-2xl">GP</div>
                </div>

                <!-- Indicateurs -->
                <div x-show="photos.length > 1" class="absolute bottom-4 inset-x-0 flex items-center justify-center gap-1.5" role="tablist" aria-label="Diaporama des photos">
                    <template x-for="(photo, i) in photos" :key="i">
                        <span
                            class="h-1.5 rounded-full transition-all duration-300 cursor-pointer"
                            :class="index === i ? 'w-5 bg-white' : 'w-1.5 bg-white/60'"
                            role="tab"
                            :aria-selected="index === i"
                            :aria-label="'Photo ' + (i+1)"
                            @click="index = i"
                        ></span>
                    </template>
                </div>
            </div>
        </div>

        <!-- Contenu : cartes -->
        <article class="pt-16 pb-20 md:pb-28 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                @if ($founder->show_bio && $founder->bio)
                    <section class="bg-[#F8F9FA] rounded-2xl p-8" x-data x-reveal>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                <x-icon name="file-text" class="w-5 h-5 text-accent" />
                            </div>
                            <h2 class="font-bold text-xl text-[#1A1A1A]">Biographie</h2>
                        </div>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $founder->bio }}</p>
                    </section>
                @endif

                @if ($founder->show_why_founded && $founder->why_founded)
                    <section class="bg-[#F8F9FA] rounded-2xl p-8" x-data x-reveal>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                                <x-icon name="zap" class="w-5 h-5 text-accent" />
                            </div>
                            <h2 class="font-bold text-xl text-[#1A1A1A]">Pourquoi Generation PUSH</h2>
                        </div>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $founder->why_founded }}</p>
                    </section>
                @endif

                @if ($founder->show_mission && $founder->mission)
                    <section class="bg-[#1A1A1A] rounded-2xl p-8 relative overflow-hidden" x-data x-reveal>
                        <div class="absolute -bottom-10 -end-10 w-40 h-40 rounded-full bg-accent/10 blur-2xl"></div>
                        <div class="relative flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-accent/20 flex items-center justify-center shrink-0">
                                <x-icon name="award" class="w-5 h-5 text-accent" />
                            </div>
                            <h2 class="font-bold text-xl text-white">Sa mission</h2>
                        </div>
                        <p class="relative text-gray-300 leading-relaxed whitespace-pre-line">{{ $founder->mission }}</p>
                    </section>
                @endif

                <div class="text-center pt-6" x-data x-reveal>
                    <a href="{{ route('register') }}" class="inline-block px-8 py-3.5 rounded-lg bg-accent text-white font-semibold hover:opacity-90 hover:scale-105 transition-all duration-200 shadow-lg shadow-accent/30">
                        Rejoindre la communauté
                    </a>
                </div>
            </div>
        </article>
    </main>

    <!-- Définition du composant Alpine pour le carrousel (à placer dans un fichier JS global) -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', (photos) => ({
                photos: photos,
                index: 0,
                init() {
                    if (this.photos.length > 1) {
                        setInterval(() => {
                            this.index = (this.index + 1) % this.photos.length;
                        }, 3000);
                    }
                }
            }));
        });
    </script>
</x-layouts.public>
