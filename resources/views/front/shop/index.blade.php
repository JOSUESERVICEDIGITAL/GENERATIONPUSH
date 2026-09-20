<x-layouts.public title="Boutique — Generation PUSH">

    <x-front.page-banner
        title="Boutique"
        subtitle="Livres, masterclass vidéo et clés USB pour continuer à apprendre"
    />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- ==========================================================
                FILTRES
            =========================================================== --}}
            <div
                class="flex flex-col lg:flex-row items-center justify-between gap-5 mb-14"
                x-data
                x-reveal
            >

                {{-- Recherche --}}
                <form method="GET" class="relative w-full lg:max-w-md">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher un produit..."
                        class="w-full ps-12 pe-5 py-3 rounded-2xl border border-gray-200 bg-white
                               text-sm text-gray-700 placeholder-gray-400
                               focus:outline-none focus:ring-2 focus:ring-accent/20
                               focus:border-accent transition"
                    >

                    {{-- Icône recherche --}}
                    <svg
                        class="absolute start-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-3.5-3.5"></path>
                    </svg>
                </form>

                {{-- Catégories --}}
                <div class="flex flex-wrap items-center justify-center gap-2">

                    @foreach ([
                        '' => 'Tous',
                        'book' => 'Livres',
                        'video' => 'Vidéos',
                        'usb_key' => 'Clés USB'
                    ] as $value => $label)

                        <a
                            href="{{ route('front.shop.index', array_filter([
                                'type' => $value ?: null,
                                'search' => $search
                            ])) }}"
                            class="
                                px-5 py-2.5 rounded-full text-xs font-bold
                                transition-all duration-200
                                {{ $type === $value || (!$type && !$value)
                                    ? 'bg-accent text-white shadow-md shadow-accent/20'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900'
                                }}
                            "
                        >
                            {{ $label }}
                        </a>

                    @endforeach

                </div>
            </div>


            {{-- ==========================================================
                PRODUITS
            =========================================================== --}}
            @if ($products->isEmpty())

                <div
                    class="py-20 text-center"
                    x-data
                    x-reveal
                >
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-gray-100 flex items-center justify-center mb-5">

                        <svg
                            class="w-9 h-9 text-gray-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="m20 20-3.5-3.5"></path>
                        </svg>

                    </div>

                    <h3 class="text-lg font-bold text-gray-800">
                        Aucun produit trouvé
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">
                        Essayez une autre recherche ou sélectionnez une autre catégorie.
                    </p>
                </div>

            @else

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-7">

                    @foreach ($products as $i => $product)

                        <article
                            class="
                                group bg-white rounded-3xl overflow-hidden
                                border border-gray-100
                                shadow-sm hover:shadow-xl
                                transition-all duration-300
                                hover:-translate-y-1
                            "
                            x-data
                            x-reveal.delay.{{ ($i % 3) * 100 }}
                        >

                            {{-- ==================================================
                                IMAGE
                            =================================================== --}}
                            <a
                                href="{{ route('front.shop.show', $product->slug) }}"
                                class="block"
                            >

                                <div class="aspect-[4/3] bg-gray-100 relative overflow-hidden">

                                    @if ($product->imageUrl())

                                        <img
                                            src="{{ $product->imageUrl() }}"
                                            alt="{{ $product->title }}"
                                            class="
                                                w-full h-full object-cover
                                                group-hover:scale-105
                                                transition-transform duration-700
                                            "
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="w-full h-full flex items-center justify-center bg-gray-50">

                                            @if ($product->type === 'video')

                                                {{-- VIDEO --}}
                                                <div class="w-16 h-16 rounded-2xl bg-blue-100 text-blue-600 flex items-center justify-center">

                                                    <svg
                                                        class="w-8 h-8"
                                                        viewBox="0 0 24 24"
                                                        fill="currentColor"
                                                    >
                                                        <path d="M8 5.14v13.72a1 1 0 0 0 1.5.86l10-6.86a1 1 0 0 0 0-1.72l-10-6.86A1 1 0 0 0 8 5.14Z"/>
                                                    </svg>

                                                </div>

                                            @elseif ($product->type === 'usb_key')

                                                {{-- USB --}}
                                                <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center">

                                                    <svg
                                                        class="w-8 h-8"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.8"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <rect x="6" y="3" width="12" height="18" rx="2"></rect>
                                                        <path d="M9 7h6"></path>
                                                        <path d="M9 11h6"></path>
                                                        <circle cx="12" cy="16" r="1"></circle>
                                                    </svg>

                                                </div>

                                            @else

                                                {{-- LIVRE --}}
                                                <div class="w-16 h-16 rounded-2xl bg-accent/10 text-accent flex items-center justify-center">

                                                    <svg
                                                        class="w-8 h-8"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="1.8"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path>
                                                    </svg>

                                                </div>

                                            @endif

                                        </div>

                                    @endif


                                    {{-- Badge gratuit --}}
                                    @if ($product->is_free)

                                        <span
                                            class="
                                                absolute top-4 start-4
                                                inline-flex items-center gap-1.5
                                                px-3 py-1.5 rounded-full
                                                bg-green-500 text-white
                                                text-[11px] font-extrabold
                                                shadow-lg
                                            "
                                        >
                                            <svg
                                                class="w-3.5 h-3.5"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M20 6 9 17l-5-5"></path>
                                            </svg>

                                            Gratuit
                                        </span>

                                    @endif


                                    {{-- Type --}}
                                    <span
                                        class="
                                            absolute top-4 end-4
                                            px-3 py-1.5 rounded-full
                                            bg-white/95 backdrop-blur
                                            text-[10px] font-bold
                                            uppercase tracking-wide
                                            text-gray-600
                                            shadow-sm
                                        "
                                    >
                                        {{ $product->typeLabel() }}
                                    </span>

                                </div>

                            </a>


                            {{-- ==================================================
                                INFORMATIONS
                            =================================================== --}}
                            <div class="p-5">

                                <a
                                    href="{{ route('front.shop.show', $product->slug) }}"
                                    class="block"
                                >

                                    <h3
                                        class="
                                            font-extrabold text-[17px] leading-snug
                                            text-[#1A1A1A]
                                            group-hover:text-accent
                                            transition-colors duration-200
                                            line-clamp-2
                                        "
                                    >
                                        {{ $product->title }}
                                    </h3>

                                    @if ($product->description)

                                        <p class="mt-2 text-sm text-gray-500 leading-relaxed line-clamp-2">
                                            {{ $product->description }}
                                        </p>

                                    @endif

                                    <div class="flex items-center justify-between gap-4 mt-5">

                                        <p class="font-extrabold text-lg text-accent">
                                            {{ $product->priceLabel() }}
                                        </p>

                                        <span
                                            class="
                                                inline-flex items-center justify-center
                                                w-9 h-9 rounded-full
                                                bg-gray-100 text-gray-600
                                                group-hover:bg-accent
                                                group-hover:text-white
                                                transition-all duration-300
                                            "
                                        >

                                            <svg
                                                class="w-4 h-4"
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M5 12h14"></path>
                                                <path d="m13 6 6 6-6 6"></path>
                                            </svg>

                                        </span>

                                    </div>

                                </a>


                                {{-- ==================================================
                                    MINI STATS
                                =================================================== --}}
                                <div class="grid grid-cols-3 gap-2 mt-5 pt-4 border-t border-gray-100">

                                    {{-- Likes --}}
                                    <div class="flex items-center justify-center gap-1.5 text-xs text-gray-500">

                                        <svg
                                            class="w-4 h-4 text-red-500"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M20.8 8.6c0 5.5-8.8 10.4-8.8 10.4S3.2 14.1 3.2 8.6A4.6 4.6 0 0 1 12 6.1a4.6 4.6 0 0 1 8.8 2.5Z"></path>
                                        </svg>

                                        <span class="font-semibold">
                                            {{ $product->likes_count ?? 0 }}
                                        </span>

                                    </div>


                                    {{-- Saves --}}
                                    <div class="flex items-center justify-center gap-1.5 text-xs text-gray-500">

                                        <svg
                                            class="w-4 h-4 text-amber-500"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3-6 3V4Z"></path>
                                        </svg>

                                        <span class="font-semibold">
                                            {{ $product->saves_count ?? 0 }}
                                        </span>

                                    </div>


                                    {{-- Commandes --}}
                                    <div class="flex items-center justify-center gap-1.5 text-xs text-gray-500">

                                        <svg
                                            class="w-4 h-4 text-accent"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M6 7h12l1 14H5L6 7Z"></path>
                                            <path d="M9 7a3 3 0 0 1 6 0"></path>
                                        </svg>

                                        <span class="font-semibold">
                                            {{ $product->orders_count ?? 0 }}
                                        </span>

                                    </div>

                                </div>

                            </div>

                        </article>

                    @endforeach

                </div>


                {{-- ==========================================================
                    PAGINATION
                =========================================================== --}}
                <div class="mt-12">
                    {{ $products->links() }}
                </div>

            @endif

        </div>
    </section>

</x-layouts.public>
