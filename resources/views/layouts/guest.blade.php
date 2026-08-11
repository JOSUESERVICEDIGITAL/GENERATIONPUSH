<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Generation PUSH') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=lora:600,700" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|lora:600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-[#1A1A1A]">
    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        <!-- Panneau branding (gauche, masqué sur mobile) -->
        <div class="hidden lg:flex flex-col justify-between relative overflow-hidden bg-[#1A1A1A] p-12 text-white">
            <div class="absolute inset-0 bg-gradient-to-br from-accent/30 via-transparent to-transparent"></div>
            <div class="absolute -bottom-24 -start-24 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>

            <a href="{{ route('front.home') }}" class="relative flex items-center gap-3">
                <div class="w-11 h-11 rounded-lg bg-accent flex items-center justify-center text-white font-bold">GP
                </div>
                <span class="font-bold text-lg">Generation PUSH</span>
            </a>

            <div class="relative max-w-md">
                <p class="text-3xl font-extrabold leading-tight" style="font-family: 'Lora', serif;">
                    "Rejoindre Generation PUSH a changé ma trajectoire de leader."
                </p>
                <p class="text-gray-400 text-sm mt-4">— Un membre de la communauté</p>

                <div class="grid grid-cols-3 gap-6 mt-10 pt-8 border-t border-white/10">
                    <div>
                        <p class="text-2xl font-extrabold text-accent">2500+</p>
                        <p class="text-xs text-gray-400 mt-1">Membres</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-accent">40+</p>
                        <p class="text-xs text-gray-400 mt-1">Formations</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-accent">98%</p>
                        <p class="text-xs text-gray-400 mt-1">Satisfaction</p>
                    </div>
                </div>
            </div>

            <p class="relative text-xs text-gray-500">&copy; {{ now()->year }} Generation PUSH. Tous droits réservés.
            </p>
        </div>

        <!-- Panneau formulaire (droite) -->
        <div class="flex flex-col items-center justify-center px-4 sm:px-6 py-12 bg-white">
            <div class="w-full max-w-sm">
                <a href="{{ route('front.home') }}" class="flex lg:hidden items-center gap-3 justify-center mb-8">
                    <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center text-white font-bold">GP
                    </div>
                    <span class="font-bold">Generation PUSH</span>
                </a>

                {{ $slot }}

                <p class="text-center text-xs text-gray-400 mt-8">
                    <a href="{{ route('front.home') }}" class="hover:text-accent transition-colors duration-200">←
                        Retour au site</a>
                </p>
            </div>
        </div>
    </div>

    <x-flash-toasts />
</body>

</html>
