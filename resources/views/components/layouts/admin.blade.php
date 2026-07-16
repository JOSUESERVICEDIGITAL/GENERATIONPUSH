<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    x-data="{ dark: localStorage.getItem('theme') === 'dark' }"
    x-init="$watch('dark', v => { localStorage.setItem('theme', v ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', v) }); document.documentElement.classList.toggle('dark', dark)"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} — Generation PUSH</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-background text-foreground" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">

    <x-sidebar />

    <div class="transition-all duration-300" :class="sidebarOpen ? 'md:ms-64' : 'md:ms-20'">
        <x-topbar :title="$title ?? null" />

        <main class="pt-16 min-h-screen">
            <div class="p-4 md:p-6 space-y-6">
                @if(isset($header))
                    <div>{{ $header }}</div>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>

    <x-flash-toasts />

</body>
</html>
