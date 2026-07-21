<x-layouts.public title="Blog — Generation PUSH">

    <x-front.page-banner title="Le blog" subtitle="Conseils, retours d'expérience et actualités pour les leaders africains" />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Filtres -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-14" x-data x-reveal>
                <form method="GET" class="relative w-full sm:max-w-xs">
                    <x-icon name="search" class="absolute start-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un article..." class="w-full ps-11 pe-4 py-2.5 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                </form>

                @if ($categories->isNotEmpty())
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('front.blog.index') }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors duration-200 {{ !$categorySlug ? 'bg-accent text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">Toutes</a>
                        @foreach ($categories as $cat)
                            <a href="{{ route('front.blog.index', ['category' => $cat->slug]) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors duration-200" style="{{ $categorySlug === $cat->slug ? "background-color:{$cat->color};color:white" : 'background-color:#F3F4F6;color:#4B5563' }}">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($posts->isEmpty())
                <p class="text-center text-gray-500 py-12">Aucun article trouvé.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $i => $post)
                        <a href="{{ route('front.blog.show', $post->slug) }}" class="group" x-data x-reveal.delay.{{ ($i % 3) * 100 }}>
                            <div class="aspect-video rounded-2xl bg-gray-100 overflow-hidden mb-4">
                                @if ($post->coverImageUrl())
                                    <img src="{{ $post->coverImageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-icon name="file-text" class="w-8 h-8 text-gray-300" />
                                    </div>
                                @endif
                            </div>
                            @if ($post->category)
                                <span class="text-xs font-semibold" style="color: {{ $post->category->color }}">{{ $post->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-lg text-[#1A1A1A] mt-1 group-hover:text-accent transition-colors duration-200 line-clamp-2">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-500 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                            <div class="flex items-center justify-between mt-3">
                                <p class="text-xs text-gray-400">{{ $post->published_at?->translatedFormat('d F Y') }} · {{ $post->estimatedReadingTime() }} min</p>
                                <div class="flex items-center gap-3 text-xs text-gray-400">
                                    <span class="flex items-center gap-1"><x-icon name="eye" class="w-3.5 h-3.5" /> {{ $post->views }}</span>
                                    <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> {{ $post->likes_count }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center gap-1 mt-3 text-sm font-semibold text-accent group-hover:gap-2 transition-all duration-200">
                                Lire la suite <x-icon name="chevron-right" class="w-3.5 h-3.5" />
                            </span>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>

</x-layouts.public>
