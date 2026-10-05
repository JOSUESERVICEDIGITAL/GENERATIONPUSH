<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >


    <title>
        @yield('title', 'Generation PUSH')
    </title>

    <meta
        name="description"
        content="@yield(
            'meta_description',
            'Generation PUSH - Leadership, jeunesse, impact et transformation.'
        )"
    >

    <meta
        name="theme-color"
        content="#E8631A"
    >


    {{-- Tailwind + Alpine + composants historiques --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- Google Fonts --}}
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Montserrat:wght@500;600;700;800;900&display=swap"
        rel="stylesheet"
    >


    {{-- Bootstrap --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >


    {{-- AOS --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"
    >


    {{-- Swiper --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
    >


    {{-- iziToast --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css"
    >


    {{-- CSS Front Generation PUSH --}}
    <link
        rel="stylesheet"
        href="{{ asset('front/css/style.css') }}"
    >


    @stack('styles')

</head>


<body class="font-sans antialiased bg-white text-[#1A1A1A]">


    {{-- ============================================================
         PAGE LOADER
    ============================================================ --}}

    <div id="gp-page-loader">

        <div class="gp-loader-content">

            <div class="gp-loader-circle"></div>

            <span>
                GENERATION <strong>PUSH</strong>
            </span>

        </div>

    </div>


    {{-- ============================================================
         SCROLL PROGRESS
    ============================================================ --}}

    <div id="gp-scroll-progress"></div>


    {{-- ============================================================
         NAVBAR PUBLIQUE UNIQUE
    ============================================================ --}}

    <x-front.navbar />


    {{-- ============================================================
         CONTENU DES PAGES
    ============================================================ --}}

    <main id="gp-main">

        @yield('content')

    </main>


    {{-- ============================================================
         FOOTER PUBLIC UNIQUE
    ============================================================ --}}

    <x-front.footer />


    {{-- ============================================================
         BACK TO TOP
    ============================================================ --}}

    <button
        id="gp-back-to-top"
        type="button"
        aria-label="Retour en haut"
    >

        <i class="bi bi-arrow-up"></i>

    </button>


    {{-- Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


    {{-- AOS --}}
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>


    {{-- Swiper --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>


    {{-- iziToast --}}
    <script src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"></script>


    {{-- JS Front Generation PUSH --}}
    <script src="{{ asset('front/js/script.js') }}"></script>


    {{-- Flash component --}}
    <x-flash-toasts />


    {{-- Scripts spécifiques aux pages --}}
    @stack('scripts')


</body>
</html>
