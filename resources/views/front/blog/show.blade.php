<x-layouts.public :title="$post->title . ' — Generation PUSH'" :meta-description="$post->excerpt">

    <section class="relative pt-32 pb-16 md:pt-40 md:pb-20 bg-[#1A1A1A] overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-accent/20 via-transparent to-transparent"></div>
        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data x-reveal>
            @if ($post->category)
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold mb-4" style="background-color: {{ $post->category->color }}30; color: {{ $post->category->color }}">{{ $post->category->name }}</span>
            @endif
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-white leading-tight">{{ $post->title }}</h1>
            <p class="mt-4 text-sm text-gray-400">
                {{ $post->author->name ?? 'Generation PUSH' }} · {{ $post->published_at?->translatedFormat('d F Y') }} · {{ $post->views }} vues
            </p>
        </div>
    </section>

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($post->coverImageUrl())
                <img src="{{ $post->coverImageUrl() }}" class="w-full aspect-video object-cover rounded-2xl mb-10 -mt-20 relative shadow-xl" x-data x-reveal="'zoom'">
            @endif

            <div class="prose prose-gray max-w-none" x-data x-reveal>
                {!! nl2br(e($post->content)) !!}
            </div>

            <div class="mt-12 pt-8 border-t border-gray-100">
                <a href="{{ route('front.blog.index') }}" class="inline-flex items-center gap-2 text-accent font-semibold hover:gap-3 transition-all duration-200">
                    <x-icon name="chevron-right" class="w-4 h-4 rotate-180" /> Retour au blog
                </a>
            </div>
        </div>
    </section>

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
