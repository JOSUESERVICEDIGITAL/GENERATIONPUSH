<x-layouts.public title="Mes articles sauvegardés — Generation PUSH">

    <x-front.page-banner title="Mes articles sauvegardés" subtitle="Retrouve ici les articles que tu as mis de côté" />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if ($posts->isEmpty())
                <p class="text-center text-gray-500 py-12">Tu n'as encore sauvegardé aucun article. <a href="{{ route('front.blog.index') }}" class="text-accent font-semibold hover:underline">Parcourir le blog →</a></p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $i => $post)
                        <a href="{{ route('front.blog.show', $post->slug) }}" class="group" x-data x-reveal.delay.{{ ($i % 3) * 100 }}>
                            <div class="aspect-video rounded-2xl bg-gray-100 overflow-hidden mb-4">
                                @if ($post->coverImageUrl())
                                    <img src="{{ $post->coverImageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center"><x-icon name="file-text" class="w-8 h-8 text-gray-300" /></div>
                                @endif
                            </div>
                            @if ($post->category)
                                <span class="text-xs font-semibold" style="color: {{ $post->category->color }}">{{ $post->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-lg text-[#1A1A1A] mt-1 group-hover:text-accent transition-colors duration-200 line-clamp-2">{{ $post->title }}</h3>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

</x-layouts.public>
