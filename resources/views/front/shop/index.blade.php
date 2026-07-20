<x-layouts.public title="Boutique — Generation PUSH">

    <x-front.page-banner title="Boutique" subtitle="Livres, masterclass vidéo et clés USB pour continuer à apprendre" />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Filtres -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-14" x-data x-reveal>
                <form method="GET" class="relative w-full sm:max-w-xs">
                    <x-icon name="search" class="absolute start-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un produit..." class="w-full ps-11 pe-4 py-2.5 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                </form>
                <div class="flex flex-wrap items-center gap-2">
                    @foreach (['' => 'Tous', 'book' => 'Livres', 'video' => 'Vidéos', 'usb_key' => 'Clés USB'] as $value => $label)
                        <a href="{{ route('front.shop.index', array_filter(['type' => $value ?: null, 'search' => $search])) }}" class="px-4 py-1.5 rounded-full text-xs font-semibold transition-colors duration-200 {{ $type === $value || (!$type && !$value) ? 'bg-accent text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            @if ($products->isEmpty())
                <p class="text-center text-gray-500 py-12">Aucun produit trouvé.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($products as $i => $product)
                        <a href="{{ route('front.shop.show', $product->slug) }}" class="group border border-gray-100 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300" x-data x-reveal.delay.{{ ($i % 3) * 100 }}>
                            <div class="aspect-video bg-gray-100 relative overflow-hidden">
                                @if ($product->imageUrl())
                                    <img src="{{ $product->imageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-icon :name="match($product->type) { 'video' => 'video', 'usb_key' => 'hard-drive', default => 'book' }" class="w-10 h-10 text-gray-300" />
                                    </div>
                                @endif
                                @if ($product->is_free)
                                    <span class="absolute top-3 start-3 px-2.5 py-1 rounded-lg bg-green-500 text-white text-xs font-bold">Gratuit</span>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-gray-400 mb-1">{{ $product->typeLabel() }}</p>
                                <h3 class="font-bold text-[#1A1A1A] group-hover:text-accent transition-colors duration-200 line-clamp-2">{{ $product->title }}</h3>
                                <p class="font-bold text-accent mt-3">{{ $product->priceLabel() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-12">{{ $products->links() }}</div>
            @endif
        </div>
    </section>

</x-layouts.public>
