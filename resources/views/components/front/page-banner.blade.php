{{-- @props(['title', 'subtitle' => null])

<section class="relative pt-32 pb-16 md:pt-40 md:pb-20 bg-[#1A1A1A] overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-accent/20 via-transparent to-transparent"></div>
    <div class="absolute -top-24 -end-24 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center" x-data x-reveal>
        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-white">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-4 text-gray-300 max-w-2xl mx-auto">{{ $subtitle }}</p>
        @endif
    </div>
</section> --}}








@props([
    'title',
    'subtitle' => null,
    'video' => null,
    'poster' => null,
])

<section class="relative pt-32 pb-16 md:pt-40 md:pb-20 bg-[#1A1A1A] overflow-hidden">

    {{-- =========================================================
        FOND VIDÉO
        Affiché uniquement lorsqu'une vidéo est fournie
    ========================================================== --}}
    @if ($video)

        <video
            class="absolute inset-0 w-full h-full object-cover"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            @if ($poster)
                poster="{{ $poster }}"
            @endif
            aria-hidden="true"
        >
            <source src="{{ $video }}">
        </video>

        {{-- Overlay sombre pour rendre le texte lisible --}}
        <div class="absolute inset-0 bg-black/60"></div>

        {{-- Légère identité orange Generation PUSH --}}
        <div
            class="absolute inset-0
                   bg-gradient-to-br
                   from-accent/25
                   via-transparent
                   to-black/20"
        ></div>

    @else

        {{-- =====================================================
            FOND ORIGINAL
            On conserve exactement ton ancien design
        ====================================================== --}}
        <div
            class="absolute inset-0
                   bg-gradient-to-br
                   from-accent/20
                   via-transparent
                   to-transparent"
        ></div>

        <div
            class="absolute -top-24 -end-24
                   w-96 h-96
                   rounded-full
                   bg-accent/10
                   blur-3xl"
        ></div>

    @endif


    {{-- =========================================================
        CONTENU
    ========================================================== --}}
    <div
        class="relative z-10
               max-w-7xl mx-auto
               px-4 sm:px-6 lg:px-8
               text-center"
        x-data
        x-reveal
    >

        <h1
            class="text-3xl sm:text-4xl md:text-5xl
                   font-extrabold text-white"
        >
            {{ $title }}
        </h1>

        @if ($subtitle)
            <p class="mt-4 text-gray-300 max-w-2xl mx-auto">
                {{ $subtitle }}
            </p>
        @endif

    </div>

</section>