<x-layouts.public :title="$product->title . ' — Generation PUSH'">

    <section class="pt-28 pb-16 md:pt-32 md:pb-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <div x-data x-reveal="'left'">
                    <div class="aspect-square rounded-2xl bg-gray-100 overflow-hidden">
                        @if ($product->imageUrl())
                            <img src="{{ $product->imageUrl() }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <x-icon :name="match($product->type) { 'video' => 'video', 'usb_key' => 'hard-drive', default => 'book' }" class="w-16 h-16 text-gray-300" />
                            </div>
                        @endif
                    </div>
                </div>

                <div x-data x-reveal="'right'">
                    <p class="text-xs font-semibold text-accent uppercase tracking-wide mb-2">{{ $product->typeLabel() }}</p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-[#1A1A1A]">{{ $product->title }}</h1>
                    <p class="text-2xl font-bold text-accent mt-4">{{ $product->priceLabel() }}</p>

                    @if ($product->type === 'usb_key')
                        <p class="text-sm text-gray-500 mt-2">
                            @if (($product->stock ?? 0) > 0)
                                <span class="text-green-600 font-medium">En stock</span> ({{ $product->stock }} disponibles)
                            @else
                                <span class="text-red-600 font-medium">Rupture de stock</span>
                            @endif
                        </p>
                    @endif

                    <p class="text-gray-600 leading-relaxed mt-6 whitespace-pre-line">{{ $product->description }}</p>

                    <div class="mt-8">
                        @if ($product->is_free && $product->fileUrl())
                            <a href="{{ $product->fileUrl() }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200">
                                <x-icon name="download" class="w-4 h-4" /> Télécharger gratuitement
                            </a>
                        @elseif ($product->is_free && $product->video_url)
                            <a href="{{ $product->video_url }}" target="_blank" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200">
                                <x-icon name="play-circle" class="w-4 h-4" /> Regarder gratuitement
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200">
                                <x-icon name="shopping-bag" class="w-4 h-4" /> Acheter — {{ $product->priceLabel() }}
                            </a>
                            <p class="text-xs text-gray-400 mt-3">Crée un compte ou connecte-toi pour finaliser ton achat.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="py-16 md:py-20 bg-[#F8F9FA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-[#1A1A1A] mb-10 text-center" x-data x-reveal>Produits similaires</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($related as $i => $r)
                        <a href="{{ route('front.shop.show', $r->slug) }}" class="group border border-gray-100 bg-white rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300" x-data x-reveal.delay.{{ $i * 100 }}>
                            <div class="aspect-video bg-gray-100 overflow-hidden">
                                @if ($r->imageUrl())
                                    <img src="{{ $r->imageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif
                            </div>
                            <div class="p-5">
                                <h3 class="font-bold text-[#1A1A1A] group-hover:text-accent transition-colors duration-200 line-clamp-2">{{ $r->title }}</h3>
                                <p class="font-bold text-accent mt-2">{{ $r->priceLabel() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

</x-layouts.public>
