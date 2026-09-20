<x-layouts.public :title="$product->title . ' — Generation PUSH'">

    {{-- ============================================================
         PRODUIT
    ============================================================= --}}
    <section class="pt-28 pb-16 md:pt-32 md:pb-20 bg-white">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                {{-- =================================================
                     IMAGE
                ================================================= --}}
                <div x-data x-reveal="'left'">

                    <div class="aspect-square rounded-3xl bg-gray-100 overflow-hidden shadow-sm">

                        @if ($product->imageUrl())

                            <img
                                src="{{ $product->imageUrl() }}"
                                alt="{{ $product->title }}"
                                class="w-full h-full object-cover"
                            >

                        @else

                            <div class="w-full h-full flex items-center justify-center">

                                <x-icon
                                    :name="match($product->type) {
                                        'video' => 'video',
                                        'usb_key' => 'hard-drive',
                                        default => 'book'
                                    }"
                                    class="w-16 h-16 text-gray-300"
                                />

                            </div>

                        @endif

                    </div>


                    {{-- =================================================
                         ACTIONS SOCIALES
                    ================================================= --}}
                   {{-- ============================================================
     ACTIONS : J'AIME / PARTAGE / SAUVEGARDE
============================================================= --}}
<div class="mt-6 grid grid-cols-3 gap-3">

    {{-- J'AIME --}}
    <button
        type="button"
        class="group flex flex-col items-center justify-center
               min-h-[90px] rounded-2xl
               border border-red-100
               bg-red-50
               text-red-500
               hover:bg-red-500
               hover:text-white
               hover:border-red-500
               transition-all duration-300"
    >

        {{-- Bootstrap Icons : heart --}}
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="25"
            height="25"
            fill="currentColor"
            viewBox="0 0 16 16"
            class="mb-2 transition-transform duration-300 group-hover:scale-110"
        >
            <path
                d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357
                3.452-2.368 5.365-4.542 6.286-6.357.955-1.885.837-3.362.314-4.385C13.486.878
                10.4.28 8.717 2.01z
                M8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12
                3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15"
            />
        </svg>

        <span class="text-lg font-extrabold leading-none">
            {{ $product->likes_count ?? 0 }}
        </span>

        <span class="text-[11px] font-semibold uppercase tracking-wide mt-1">
            J'aime
        </span>

    </button>


    {{-- PARTAGES --}}
    <button
        type="button"
        id="share-product"
        class="group flex flex-col items-center justify-center
               min-h-[90px] rounded-2xl
               border border-blue-100
               bg-blue-50
               text-blue-500
               hover:bg-blue-500
               hover:text-white
               hover:border-blue-500
               transition-all duration-300"
    >

        {{-- Bootstrap Icons : share --}}
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="25"
            height="25"
            fill="currentColor"
            viewBox="0 0 16 16"
            class="mb-2 transition-transform duration-300 group-hover:scale-110"
        >
            <path
                d="M13.5 1a1.5 1.5 0 1 0 0 3
                1.5 1.5 0 0 0 0-3M11 2.5a2.5 2.5 0 1 1
                .8 1.85l-6.2 3.1a2.5 2.5 0 0 1
                0 1.1l6.2 3.1a2.5 2.5 0 1 1-.45.89l-6.1-3.05a2.5
                2.5 0 1 1 0-2.98l6.1-3.05A2.5 2.5 0 0 1
                11 2.5"
            />
        </svg>

        <span class="text-lg font-extrabold leading-none">
            {{ $product->shares_count ?? 0 }}
        </span>

        <span class="text-[11px] font-semibold uppercase tracking-wide mt-1">
            Partages
        </span>

    </button>


    {{-- SAUVEGARDES --}}
    <button
        type="button"
        class="group flex flex-col items-center justify-center
               min-h-[90px] rounded-2xl
               border border-amber-100
               bg-amber-50
               text-amber-500
               hover:bg-amber-500
               hover:text-white
               hover:border-amber-500
               transition-all duration-300"
    >

        {{-- Bootstrap Icons : bookmark --}}
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="25"
            height="25"
            fill="currentColor"
            viewBox="0 0 16 16"
            class="mb-2 transition-transform duration-300 group-hover:scale-110"
        >
            <path
                d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1
                2 2v13.5a.5.5 0 0 1-.777.416L8
                12.101l-5.223 3.815A.5.5 0 0 1
                2 15.5z"
            />
        </svg>

        <span class="text-lg font-extrabold leading-none">
            {{ $product->saves_count ?? 0 }}
        </span>

        <span class="text-[11px] font-semibold uppercase tracking-wide mt-1">
            Sauvegardes
        </span>

    </button>

</div>

                </div>


                {{-- =================================================
                     INFORMATIONS PRODUIT
                ================================================= --}}
                <div x-data x-reveal="'right'">

                    {{-- TYPE --}}
                    <p class="text-xs font-semibold text-accent uppercase tracking-wide mb-2">
                        {{ $product->typeLabel() }}
                    </p>


                    {{-- TITRE --}}
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-[#1A1A1A] leading-tight">
                        {{ $product->title }}
                    </h1>


                    {{-- PRIX --}}
                    <p class="text-2xl font-bold text-accent mt-4">
                        {{ $product->priceLabel() }}
                    </p>


                    {{-- STOCK USB --}}
                    @if ($product->type === 'usb_key')

                        <p class="text-sm text-gray-500 mt-2">

                            @if (($product->stock ?? 0) > 0)

                                <span class="text-green-600 font-medium">
                                    En stock
                                </span>

                                <span>
                                    — {{ $product->stock }} disponibles
                                </span>

                            @else

                                <span class="text-red-600 font-medium">
                                    Rupture de stock
                                </span>

                            @endif

                        </p>

                    @endif


                    {{-- DESCRIPTION --}}
                    <p class="text-gray-600 leading-relaxed mt-6 whitespace-pre-line">
                        {{ $product->description }}
                    </p>


                    {{-- =================================================
                         PETITES STATISTIQUES
                    ================================================= --}}
                   {{-- ============================================================
     STATISTIQUES DU PRODUIT
============================================================= --}}
<div class="mt-8 grid grid-cols-2 sm:grid-cols-4 gap-4">

    {{-- J'AIME --}}
    <div
        class="relative overflow-hidden rounded-2xl
               border border-red-100
               bg-gradient-to-br from-red-50 to-white
               p-5 shadow-sm
               hover:shadow-md hover:-translate-y-1
               transition-all duration-300"
    >

        <div
            class="w-11 h-11 rounded-xl
                   bg-red-500 text-white
                   flex items-center justify-center
                   shadow-sm mb-4"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                fill="currentColor"
                viewBox="0 0 16 16"
            >
                <path
                    d="m8 2.748-.717-.737C5.6.281
                    2.514.878 1.4 3.053c-.523
                    1.023-.641 2.5.314 4.385.92
                    1.815 2.834 3.989 6.286
                    6.357 3.452-2.368 5.365-4.542
                    6.286-6.357.955-1.885.837-3.362
                    .314-4.385C13.486.878 10.4.28
                    8.717 2.01z"
                />
            </svg>
        </div>

        <p class="text-2xl font-extrabold text-[#1A1A1A]">
            {{ $product->likes_count ?? 0 }}
        </p>

        <p class="text-sm font-medium text-gray-500 mt-1">
            J'aime
        </p>

    </div>


    {{-- PARTAGES --}}
    <div
        class="relative overflow-hidden rounded-2xl
               border border-blue-100
               bg-gradient-to-br from-blue-50 to-white
               p-5 shadow-sm
               hover:shadow-md hover:-translate-y-1
               transition-all duration-300"
    >

        <div
            class="w-11 h-11 rounded-xl
                   bg-blue-500 text-white
                   flex items-center justify-center
                   shadow-sm mb-4"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                fill="currentColor"
                viewBox="0 0 16 16"
            >
                <path
                    d="M13.5 1a1.5 1.5 0 1 0
                    0 3 1.5 1.5 0 0 0 0-3M11
                    2.5a2.5 2.5 0 1 1 .8 1.85l-6.2
                    3.1a2.5 2.5 0 0 1 0 1.1l6.2
                    3.1a2.5 2.5 0 1 1-.45.89l-6.1
                    -3.05a2.5 2.5 0 1 1 0-2.98l6.1
                    -3.05A2.5 2.5 0 0 1 11 2.5"
                />
            </svg>
        </div>

        <p class="text-2xl font-extrabold text-[#1A1A1A]">
            {{ $product->shares_count ?? 0 }}
        </p>

        <p class="text-sm font-medium text-gray-500 mt-1">
            Partages
        </p>

    </div>


    {{-- SAUVEGARDES --}}
    <div
        class="relative overflow-hidden rounded-2xl
               border border-amber-100
               bg-gradient-to-br from-amber-50 to-white
               p-5 shadow-sm
               hover:shadow-md hover:-translate-y-1
               transition-all duration-300"
    >

        <div
            class="w-11 h-11 rounded-xl
                   bg-amber-500 text-white
                   flex items-center justify-center
                   shadow-sm mb-4"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                fill="currentColor"
                viewBox="0 0 16 16"
            >
                <path
                    d="M2 2a2 2 0 0 1 2-2h8a2 2
                    0 0 1 2 2v13.5a.5.5 0 0
                    1-.777.416L8 12.101l-5.223
                    3.815A.5.5 0 0 1 2 15.5z"
                />
            </svg>
        </div>

        <p class="text-2xl font-extrabold text-[#1A1A1A]">
            {{ $product->saves_count ?? 0 }}
        </p>

        <p class="text-sm font-medium text-gray-500 mt-1">
            Sauvegardes
        </p>

    </div>


    {{-- COMMANDES --}}
    <div
        class="relative overflow-hidden rounded-2xl
               border border-accent/20
               bg-gradient-to-br from-accent/10 to-white
               p-5 shadow-sm
               hover:shadow-md hover:-translate-y-1
               transition-all duration-300"
    >

        <div
            class="w-11 h-11 rounded-xl
                   bg-accent text-white
                   flex items-center justify-center
                   shadow-sm mb-4"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                width="22"
                height="22"
                fill="currentColor"
                viewBox="0 0 16 16"
            >
                <path
                    d="M0 1.5A.5.5 0 0 1
                    .5 1h1a.5.5 0 0 1
                    .485.379L2.89 5H14.5a.5.5
                    0 0 1 .491.592l-1.5 8A.5.5
                    0 0 1 13 14H4a.5.5 0 0
                    1-.491-.408L1.01 1.5zM3.102
                    6l1.313 7h8.17l1.313-7z"
                />
                <path
                    d="M5 15a1 1 0 1 1-2 0
                    1 1 0 0 1 2 0m8 0a1 1
                    0 1 1-2 0 1 1 0 0 1 2 0"
                />
            </svg>
        </div>

        <p class="text-2xl font-extrabold text-[#1A1A1A]">
            {{ $product->orders_count ?? 0 }}
        </p>

        <p class="text-sm font-medium text-gray-500 mt-1">
            Commandes
        </p>

    </div>

</div>

                    {{-- =================================================
                         COMMANDE
                    ================================================= --}}
                    <div class="mt-8">

                        @if ($product->is_free && $product->fileUrl())

                            <a
                                href="{{ $product->fileUrl() }}"
                                target="_blank"
                                class="inline-flex items-center gap-2
                                       px-8 py-3.5 rounded-xl
                                       bg-accent text-white
                                       font-semibold
                                       hover:opacity-90
                                       hover:scale-[1.02]
                                       transition-all duration-200
                                       shadow-lg shadow-accent/20"
                            >

                                <x-icon
                                    name="download"
                                    class="w-5 h-5"
                                />

                                Télécharger gratuitement

                            </a>


                        @elseif ($product->is_free && $product->video_url)

                            <a
                                href="{{ $product->video_url }}"
                                target="_blank"
                                class="inline-flex items-center gap-2
                                       px-8 py-3.5 rounded-xl
                                       bg-accent text-white
                                       font-semibold
                                       hover:opacity-90
                                       hover:scale-[1.02]
                                       transition-all duration-200
                                       shadow-lg shadow-accent/20"
                            >

                                <x-icon
                                    name="play-circle"
                                    class="w-5 h-5"
                                />

                                Regarder gratuitement

                            </a>


                        @else

                            {{-- =========================================
                                 PRODUIT PAYANT
                            ========================================= --}}

                            @if (Route::has('front.shop.order.create'))

                                <a
                                    href="{{ auth()->check()
                                        ? route('front.shop.order.create', $product->slug)
                                        : route('login') }}"
                                    class="inline-flex items-center justify-center gap-2
                                           w-full sm:w-auto
                                           px-8 py-3.5 rounded-xl
                                           bg-accent text-white
                                           font-semibold
                                           hover:opacity-90
                                           hover:scale-[1.02]
                                           transition-all duration-200
                                           shadow-lg shadow-accent/20"
                                >

                                    <x-icon
                                        name="shopping-bag"
                                        class="w-5 h-5"
                                    />

                                    Commander

                                    <span class="opacity-80">
                                        — {{ $product->priceLabel() }}
                                    </span>

                                </a>

                            @else

                                <a
                                    href="{{ auth()->check()
                                        ? route('front.shop.order.create', $product->slug)
                                        : route('login', ['redirect' => route('front.shop.order.create', $product->slug)]) }}"
                                    class="inline-flex items-center justify-center gap-2
                                           w-full sm:w-auto
                                           px-8 py-3.5 rounded-xl
                                           bg-accent text-white
                                           font-semibold
                                           hover:opacity-90
                                           hover:scale-[1.02]
                                           transition-all duration-200
                                           shadow-lg shadow-accent/20"
                                >

                                    <x-icon
                                        name="shopping-bag"
                                        class="w-5 h-5"
                                    />

                                    Commander

                                    <span class="opacity-80">
                                        — {{ $product->priceLabel() }}
                                    </span>

                                </a>

                            @endif


                            <p class="text-xs text-gray-400 mt-3">
                                Connecte-toi pour confirmer ta commande.
                                Tes coordonnées seront automatiquement récupérées.
                            </p>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </section>


    {{-- ============================================================
         INFORMATIONS / AVANTAGES
    ============================================================= --}}
    @unless ($product->is_free)

        <section class="py-12 bg-[#F8F9FA] border-y border-gray-100">

            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                    <div class="flex items-start gap-4 bg-white rounded-2xl p-6 border border-gray-100">

                        <div class="w-11 h-11 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                            <x-icon name="user-check" class="w-5 h-5" />
                        </div>

                        <div>
                            <h3 class="font-bold text-[#1A1A1A]">
                                Commande simple
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Tes coordonnées sont récupérées automatiquement après connexion.
                            </p>
                        </div>

                    </div>


                    <div class="flex items-start gap-4 bg-white rounded-2xl p-6 border border-gray-100">

                        <div class="w-11 h-11 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                            <x-icon name="message-circle" class="w-5 h-5" />
                        </div>

                        <div>
                            <h3 class="font-bold text-[#1A1A1A]">
                                Contact direct
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Après ta demande, notre équipe te contacte pour finaliser.
                            </p>
                        </div>

                    </div>


                    <div class="flex items-start gap-4 bg-white rounded-2xl p-6 border border-gray-100">

                        <div class="w-11 h-11 rounded-xl bg-accent/10 text-accent flex items-center justify-center shrink-0">
                            <x-icon name="shield-check" class="w-5 h-5" />
                        </div>

                        <div>
                            <h3 class="font-bold text-[#1A1A1A]">
                                Suivi par Generation PUSH
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Chaque commande est reçue et suivie par notre équipe.
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    @endunless


    {{-- ============================================================
         PRODUITS SIMILAIRES
    ============================================================= --}}
    @if ($related->isNotEmpty())

        <section class="py-16 md:py-20 bg-white">

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <h2
                    class="text-2xl font-bold text-[#1A1A1A] mb-10 text-center"
                    x-data
                    x-reveal
                >
                    Produits similaires
                </h2>


                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    @foreach ($related as $i => $r)

                        <a
                            href="{{ route('front.shop.show', $r->slug) }}"
                            class="group border border-gray-100
                                   bg-white rounded-2xl overflow-hidden
                                   hover:shadow-xl
                                   transition-shadow duration-300"
                            x-data
                            x-reveal.delay.{{ $i * 100 }}
                        >

                            <div class="aspect-video bg-gray-100 overflow-hidden">

                                @if ($r->imageUrl())

                                    <img
                                        src="{{ $r->imageUrl() }}"
                                        alt="{{ $r->title }}"
                                        class="w-full h-full object-cover
                                               group-hover:scale-105
                                               transition-transform duration-500"
                                        loading="lazy"
                                    >

                                @endif

                            </div>


                            <div class="p-5">

                                <p class="text-xs uppercase tracking-wide text-accent font-semibold">
                                    {{ $r->typeLabel() }}
                                </p>

                                <h3
                                    class="font-bold text-[#1A1A1A]
                                           group-hover:text-accent
                                           transition-colors duration-200
                                           line-clamp-2 mt-1"
                                >
                                    {{ $r->title }}
                                </h3>

                                <p class="font-bold text-accent mt-2">
                                    {{ $r->priceLabel() }}
                                </p>

                            </div>

                        </a>

                    @endforeach

                </div>

            </div>

        </section>

    @endif


    {{-- ============================================================
         PARTAGE
    ============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const shareButton =
                document.getElementById('share-product');

            if (!shareButton) {
                return;
            }


            shareButton.addEventListener('click', async function () {

                const shareData = {
                    title: @json($product->title),
                    text: @json('Découvre ce produit sur Generation PUSH'),
                    url: window.location.href
                };


                /*
                |--------------------------------------------------------------------------
                | PARTAGE NATIF
                |--------------------------------------------------------------------------
                */

                if (navigator.share) {

                    try {

                        await navigator.share(shareData);

                        /*
                         * Le compteur réel de partage devra être
                         * incrémenté côté serveur dans une prochaine étape.
                         */

                    } catch (error) {

                        /*
                         * L'utilisateur a simplement fermé
                         * la fenêtre de partage.
                         */

                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | FALLBACK — COPIE DU LIEN
                |--------------------------------------------------------------------------
                */

                try {

                    await navigator.clipboard.writeText(
                        window.location.href
                    );


                    const original =
                        shareButton.innerHTML;


                    shareButton.innerHTML = `
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>

                        <span class="font-semibold">
                            Lien copié
                        </span>
                    `;


                    setTimeout(function () {

                        shareButton.innerHTML = original;

                    }, 1800);

                } catch (error) {

                    /*
                     * Rien à faire si le navigateur
                     * bloque le presse-papier.
                     */

                }

            });

        });
    </script>

</x-layouts.public>
