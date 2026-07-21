@php
    $readingMinutes = $post->estimatedReadingTime();
@endphp

<x-layouts.public :title="$post->title . ' — Generation PUSH'" :meta-description="$post->excerpt">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=lora:400,500,600,700&family=merriweather:400,700" rel="stylesheet" />

    <section class="relative pt-32 pb-16 md:pt-40 md:pb-20 bg-[#1A1A1A] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-accent/20 via-transparent to-transparent"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data x-reveal>
            @if ($post->category)
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-4" style="background-color: {{ $post->category->color }}30; color: {{ $post->category->color }}">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight" style="font-family: 'Lora', serif;">{{ $post->title }}</h1>
            <p class="mt-4 text-sm text-gray-400">
                {{ $post->author->name ?? 'Generation PUSH' }} · {{ $post->published_at?->translatedFormat('d F Y') }} · {{ $readingMinutes }} min de lecture
            </p>
        </div>
    </section>

    <div
        x-data="{
            liked: {{ $liked ? 'true' : 'false' }},
            likesCount: {{ $likesCount }},
            bookmarked: {{ $bookmarked ? 'true' : 'false' }},
            myRating: {{ $myRating ?? 0 }},
            averageRating: {{ $averageRating }},
            hoverRating: 0,
            isAuthenticated: {{ auth()->check() ? 'true' : 'false' }},
            startTime: Date.now(),

            toggleLike() {
                if (!this.isAuthenticated) { window.location.href = '{{ route('login') }}'; return; }
                fetch('{{ route('front.blog.like', $post->slug) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                }).then(r => r.json()).then(data => { this.liked = data.liked; this.likesCount = data.count; });
            },
            toggleBookmark() {
                if (!this.isAuthenticated) { window.location.href = '{{ route('login') }}'; return; }
                fetch('{{ route('front.blog.bookmark', $post->slug) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' },
                }).then(r => r.json()).then(data => {
                    this.bookmarked = data.bookmarked;
                    window.notify && window.notify('success', data.bookmarked ? 'Article sauvegardé.' : 'Retiré des sauvegardes.');
                });
            },
            rate(value) {
                if (!this.isAuthenticated) { window.location.href = '{{ route('login') }}'; return; }
                this.myRating = value;
                fetch('{{ route('front.blog.rate', $post->slug) }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ rating: value }),
                }).then(r => r.json()).then(data => { this.averageRating = data.average; });
            },
            sendReadTime() {
                const seconds = Math.round((Date.now() - this.startTime) / 1000);
                if (seconds < 3) return;
                const data = new FormData();
                data.append('_token', document.querySelector('meta[name=csrf-token]').content);
                data.append('seconds', Math.min(seconds, 7200));
                navigator.sendBeacon('{{ route('front.blog.read-time', $post->slug) }}', data);
            },
        }"
        x-init="
            document.addEventListener('visibilitychange', () => { if (document.visibilityState === 'hidden') sendReadTime(); });
            window.addEventListener('pagehide', () => sendReadTime());
        "
    >
        <!-- Barre d'engagement flottante (desktop) -->
        <div class="hidden lg:flex flex-col gap-3 fixed left-6 top-1/2 -translate-y-1/2 z-20">
            <button @click="toggleLike()" class="w-12 h-12 rounded-full bg-white shadow-md border border-gray-100 flex flex-col items-center justify-center hover:scale-110 transition-transform duration-200 cursor-pointer">
                <svg :class="liked ? 'text-red-500' : 'text-gray-400'" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                </svg>
                <span class="text-[10px] font-semibold text-gray-500 mt-0.5" x-text="likesCount"></span>
            </button>
            <button @click="toggleBookmark()" class="w-12 h-12 rounded-full bg-white shadow-md border border-gray-100 flex items-center justify-center hover:scale-110 transition-transform duration-200 cursor-pointer">
                <svg :class="bookmarked ? 'text-accent' : 'text-gray-400'" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
                    <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                </svg>
            </button>
            <div class="w-12 rounded-full bg-white shadow-md border border-gray-100 flex flex-col items-center justify-center py-2">
                <span class="text-[10px] font-bold text-gray-500" x-text="averageRating"></span>
                <x-icon name="star" class="w-3.5 h-3.5 text-yellow-500 mt-0.5" style="fill: currentColor" />
            </div>
        </div>

        <section class="py-16 md:py-20 bg-white">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                @if ($post->coverImageUrl())
                    <img src="{{ $post->coverImageUrl() }}" class="w-full aspect-video object-cover rounded-2xl mb-10 -mt-20 relative shadow-xl" x-data x-reveal="'zoom'">
                @endif

                <!-- Barre d'engagement mobile -->
                <div class="flex lg:hidden items-center justify-center gap-6 mb-10 pb-6 border-b border-gray-100">
                    <button @click="toggleLike()" class="flex items-center gap-1.5 cursor-pointer">
                        <svg :class="liked ? 'text-red-500' : 'text-gray-400'" :fill="liked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                        </svg>
                        <span class="text-sm text-gray-600" x-text="likesCount"></span>
                    </button>
                    <button @click="toggleBookmark()" class="cursor-pointer">
                        <svg :class="bookmarked ? 'text-accent' : 'text-gray-400'" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="w-5 h-5">
                            <path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </button>
                    <div class="flex items-center gap-1 text-sm text-gray-500">
                        <x-icon name="star" class="w-4 h-4 text-yellow-500" style="fill: currentColor" />
                        <span x-text="averageRating"></span>
                    </div>
                    <div class="flex items-center gap-1 text-sm text-gray-500">
                        <x-icon name="eye" class="w-4 h-4" /> {{ $post->views }}
                    </div>
                </div>

                <!-- Contenu de l'article : typographie dédiée -->
                <article
                    class="max-w-none text-[#2A2A2A]"
                    style="font-family: 'Merriweather', Georgia, serif; font-size: 1.125rem; line-height: 1.9;"
                    x-data x-reveal
                >
                    {!! nl2br(e($post->content)) !!}
                </article>

                <!-- Note (étoiles) -->
                <div class="mt-14 pt-10 border-t border-gray-100 text-center">
                    <p class="text-sm text-gray-500 mb-3">Cet article t'a été utile ?</p>
                    <div class="flex items-center justify-center gap-1">
                        <template x-for="i in [1,2,3,4,5]" :key="i">
                            <button @click="rate(i)" @mouseenter="hoverRating = i" @mouseleave="hoverRating = 0" class="cursor-pointer p-0.5">
                                <x-icon name="star" class="w-7 h-7 transition-colors duration-150" x-bind:class="(hoverRating || myRating) >= i ? 'text-yellow-500' : 'text-gray-200'" x-bind:style="(hoverRating || myRating) >= i ? 'fill: currentColor' : ''" />
                            </button>
                        </template>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Note moyenne : <span x-text="averageRating"></span> / 5</p>
                </div>

                <div class="mt-10 flex items-center justify-between flex-wrap gap-4">
                    <a href="{{ route('front.blog.index') }}" class="inline-flex items-center gap-2 text-accent font-semibold hover:gap-3 transition-all duration-200">
                        <x-icon name="chevron-right" class="w-4 h-4 rotate-180" /> Retour au blog
                    </a>
                    <p class="text-xs text-gray-400 flex items-center gap-1"><x-icon name="eye" class="w-3.5 h-3.5" /> {{ $post->views }} vues</p>
                </div>
            </div>
        </section>
    </div>

    @if ($related->isNotEmpty())
        <section class="py-16 md:py-20 bg-[#F8F9FA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-[#1A1A1A] mb-10 text-center" x-data x-reveal>Articles similaires</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($related as $i => $r)
                        <a href="{{ route('front.blog.show', $r->slug) }}" class="group" x-data x-reveal.delay.{{ $i * 100 }}>
                            <div class="aspect-video rounded-2xl bg-white overflow-hidden mb-4">
                                @if ($r->coverImageUrl())
                                    <img src="{{ $r->coverImageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><x-icon name="file-text" class="w-8 h-8 text-gray-300" /></div>
                                @endif
                            </div>
                            <h3 class="font-bold text-[#1A1A1A] group-hover:text-accent transition-colors duration-200 line-clamp-2">{{ $r->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts.public>
