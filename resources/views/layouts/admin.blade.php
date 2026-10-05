<!DOCTYPE html>

<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"

    x-data="{
        dark: localStorage.getItem('theme') === 'dark'
    }"

    x-init="
        $watch('dark', value => {
            localStorage.setItem(
                'theme',
                value ? 'dark' : 'light'
            );

            document.documentElement.classList.toggle(
                'dark',
                value
            );
        });

        document.documentElement.classList.toggle(
            'dark',
            dark
        );
    "
>

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >


    {{-- ============================================================
         TITRE
    ============================================================ --}}

    <title>
        @yield('title', 'Dashboard') — Generation PUSH
    </title>


    {{-- ============================================================
         FAVICON
    ============================================================ --}}

    <link
        rel="icon"
        type="image/png"
        href="{{ asset('favicon_io/favicon-32x32.png') }}"
    >


    {{-- ============================================================
         POLICES
    ============================================================ --}}

    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700"
        rel="stylesheet"
    >


    {{-- ============================================================
         VITE — TAILWIND + ALPINE
    ============================================================ --}}

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])


    {{-- ============================================================
         STYLES PROPRES AUX PAGES
    ============================================================ --}}

    @stack('styles')

</head>


<body
    class="font-sans antialiased bg-background text-foreground"

    x-data="{
        sidebarOpen: true,
        mobileSidebarOpen: false
    }"
>


    {{-- ================================================================
         SIDEBAR
    ================================================================ --}}

    <x-sidebar />



    {{-- ================================================================
         APPLICATION
    ================================================================ --}}

    <div
        class="transition-all duration-300"
        :class="
            sidebarOpen
                ? 'md:ms-64'
                : 'md:ms-20'
        "
    >


        {{-- ============================================================
             TOPBAR
        ============================================================ --}}

        <x-topbar
            :title="trim($__env->yieldContent('title')) ?: null"
        />



        {{-- ============================================================
             CONTENU PRINCIPAL
        ============================================================ --}}

        <main class="pt-16 min-h-screen">

            <div class="p-4 md:p-6 space-y-6">

                @yield('content')

            </div>

        </main>


    </div>



    {{-- ================================================================
         NOTIFICATIONS FLASH
    ================================================================ --}}

    <x-flash-toasts />



    {{-- ================================================================
         SCRIPTS PROPRES AUX PAGES
    ================================================================ --}}

    @stack('scripts')


</body>

</html>
