<x-layouts.member :title="'Tableau de bord'">

    @php
        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR
        |--------------------------------------------------------------------------
        */

        $userName = filled($user?->name)
            ? $user->name
            : 'Membre';

        $initials = collect(
            preg_split('/\s+/', trim($userName))
        )
            ->filter()
            ->map(fn ($part) => mb_strtoupper(mb_substr($part, 0, 1)))
            ->take(2)
            ->implode('');

        $profilePhoto = $user?->profile_photo;

        $profilePercentage = min(
            100,
            max(0, (int) ($profileCompletion ?? 0))
        );


        /*
        |--------------------------------------------------------------------------
        | STATUTS COMMANDES
        |--------------------------------------------------------------------------
        */

        $orderStatusLabels = [
            'pending'    => 'En attente',
            'paid'       => 'Payée',
            'processing' => 'En traitement',
            'completed'  => 'Terminée',
            'cancelled'  => 'Annulée',
            'failed'     => 'Échouée',
            'refunded'   => 'Remboursée',
        ];


        /*
        |--------------------------------------------------------------------------
        | STATUTS RÉSERVATIONS
        |--------------------------------------------------------------------------
        */

        $reservationStatusLabels = [
            'pending'   => 'En attente',
            'confirmed' => 'Confirmée',
            'cancelled' => 'Annulée',
            'completed' => 'Terminée',
        ];


        /*
        |--------------------------------------------------------------------------
        | STATUTS TRANSACTIONS
        |--------------------------------------------------------------------------
        */

        $transactionStatusLabels = [
            'pending'   => 'En attente',
            'completed' => 'Effectué',
            'failed'    => 'Échoué',
            'cancelled' => 'Annulé',
            'refunded'  => 'Remboursé',
        ];


        /*
        |--------------------------------------------------------------------------
        | VALEURS PAR DÉFAUT
        |--------------------------------------------------------------------------
        */

        $unreadMessages = (int) ($unreadMessages ?? 0);
        $totalMessages = (int) ($totalMessages ?? 0);

        $favoritesCount = (int) ($favoritesCount ?? 0);

        $reservationsCount = (int) ($reservationsCount ?? 0);
        $confirmedReservationsCount = (int) ($confirmedReservationsCount ?? 0);
        $pendingReservationsCount = (int) ($pendingReservationsCount ?? 0);

        $eventsCount = (int) ($eventsCount ?? 0);

        $ordersCount = (int) ($ordersCount ?? 0);
        $paidOrdersCount = (int) ($paidOrdersCount ?? 0);
        $pendingOrdersCount = (int) ($pendingOrdersCount ?? 0);

        $paymentsCount = (int) ($paymentsCount ?? 0);
        $completedPaymentsCount = (int) ($completedPaymentsCount ?? 0);
        $pendingPaymentsCount = (int) ($pendingPaymentsCount ?? 0);

        $totalPaid = (float) ($totalPaid ?? 0);

        $recentOrders = $recentOrders ?? collect();
        $recentReservations = $recentReservations ?? collect();
        $recentTransactions = $recentTransactions ?? collect();


        /*
        |--------------------------------------------------------------------------
        | ROUTES
        |--------------------------------------------------------------------------
        */

        $homeUrl = Route::has('front.home')
            ? route('front.home')
            : url('/');

        $profileUrl = Route::has('member.profile')
            ? route('member.profile')
            : '#';

        $messagesUrl = Route::has('member.messages')
            ? route('member.messages')
            : '#';

        $bookmarksUrl = Route::has('member.bookmarks')
            ? route('member.bookmarks')
            : '#';

        $eventsUrl = Route::has('member.events')
            ? route('member.events')
            : '#';

        $reservationsUrl = Route::has('member.reservations')
            ? route('member.reservations')
            : '#';

        $ordersUrl = Route::has('member.orders')
            ? route('member.orders')
            : '#';

        $paymentsUrl = Route::has('member.payments')
            ? route('member.payments')
            : '#';

        $settingsUrl = Route::has('member.settings')
            ? route('member.settings')
            : '#';
    @endphp


    {{-- ======================================================================
        EN-TÊTE
    ======================================================================= --}}

    <section class="mb-8">

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div class="min-w-0">

                <p class="mb-1 text-sm font-medium text-gray-500">
                    Espace membre
                </p>

                <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                    Bonjour {{ $userName }} 
                </h1>

                <p class="mt-2 max-w-2xl text-sm leading-6 text-gray-500">
                    Bienvenue dans votre espace personnel Generation PUSH.
                    Retrouvez ici vos activités, vos réservations, vos commandes
                    et vos paiements.
                </p>

            </div>


            {{-- RETOUR SITE --}}

            <a
                href="{{ $homeUrl }}"
                class="inline-flex w-fit shrink-0 items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#E8631A] hover:text-[#E8631A] hover:shadow-md"
            >

                <svg
                    class="h-4 w-4"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M3 12l9-9 9 9"/>
                    <path d="M5 10v10h14V10"/>
                    <path d="M9 20v-6h6v6"/>
                </svg>

                Voir le site

            </a>

        </div>

    </section>



    {{-- ======================================================================
        PROFIL + MESSAGES
    ======================================================================= --}}

    <section class="mb-8 grid gap-6 lg:grid-cols-3">


        {{-- ==================================================================
            PROFIL
        =================================================================== --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm lg:col-span-2">

            <div class="flex flex-col gap-6 sm:flex-row sm:items-center">


                {{-- AVATAR --}}

                <div class="shrink-0">

                    @if($profilePhoto)

                        <img
                            src="{{ asset('storage/' . ltrim($profilePhoto, '/')) }}"
                            alt="{{ $userName }}"
                            class="h-20 w-20 rounded-2xl object-cover ring-4 ring-orange-50"
                        >

                    @else

                        <div
                            class="flex h-20 w-20 items-center justify-center rounded-2xl bg-[#E8631A] text-xl font-bold text-white ring-4 ring-orange-50"
                        >
                            {{ $initials ?: 'M' }}
                        </div>

                    @endif

                </div>


                {{-- INFORMATIONS --}}

                <div class="min-w-0 flex-1">

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                        <div class="min-w-0">

                            <h2 class="truncate text-lg font-bold text-gray-900">
                                {{ $userName }}
                            </h2>

                            @if(filled($user?->email))

                                <p class="mt-1 truncate text-sm text-gray-500">
                                    {{ $user->email }}
                                </p>

                            @endif

                        </div>


                        <span
                            class="inline-flex w-fit shrink-0 items-center gap-1.5 rounded-full bg-green-50 px-3 py-1.5 text-xs font-semibold text-green-700"
                        >

                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>

                            Membre

                        </span>

                    </div>


                    {{-- PROGRESSION PROFIL --}}

                    <div class="mt-5">

                        <div class="mb-2 flex items-center justify-between gap-3 text-xs">

                            <span class="font-medium text-gray-500">
                                Profil complété
                            </span>

                            <span class="font-bold text-gray-900">
                                {{ $profilePercentage }}%
                            </span>

                        </div>


                        <div class="h-2 overflow-hidden rounded-full bg-gray-100">

                            <div
                                class="h-full rounded-full bg-[#E8631A] transition-all duration-500"
                                style="width: {{ $profilePercentage }}%"
                            ></div>

                        </div>

                    </div>

                </div>


                {{-- PROFIL --}}

                <a
                    href="{{ $profileUrl }}"
                    class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white transition duration-200 hover:-translate-y-0.5 hover:bg-gray-800"
                >

                    <svg
                        class="h-4 w-4"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M12 20h9"/>
                        <path d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4Z"/>
                    </svg>

                    Mon profil

                </a>

            </div>

        </div>



        {{-- ==================================================================
            MESSAGES
        =================================================================== --}}

        <a
            href="{{ $messagesUrl }}"
            class="group rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-[#E8631A]/30 hover:shadow-md"
        >

            <div class="flex items-start justify-between gap-4">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-[#E8631A]">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M21 11.5a8.4 8.4 0 01-9 8.5 8.8 8.8 0 01-4-.9L3 21l1.9-4.6A8.3 8.3 0 013 11.5 8.5 8.5 0 0112 3a8.5 8.5 0 019 8.5Z"/>
                    </svg>

                </div>


                @if($unreadMessages > 0)

                    <span class="shrink-0 rounded-full bg-red-50 px-2.5 py-1 text-xs font-bold text-red-600">
                        {{ $unreadMessages }}
                        nouveau{{ $unreadMessages > 1 ? 'x' : '' }}
                    </span>

                @endif

            </div>


            <div class="mt-5">

                <p class="text-sm font-medium text-gray-500">
                    Mes messages
                </p>

                <p class="mt-1 text-3xl font-bold text-gray-900">
                    {{ $totalMessages }}
                </p>

                <p class="mt-2 text-xs text-gray-500">

                    {{ $unreadMessages }}

                    message{{ $unreadMessages > 1 ? 's' : '' }}

                    non lu{{ $unreadMessages > 1 ? 's' : '' }}

                </p>

            </div>

        </a>

    </section>



    {{-- ======================================================================
        STATISTIQUES PRINCIPALES
    ======================================================================= --}}

    <section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">


        {{-- FAVORIS --}}

        <a
            href="{{ $bookmarksUrl }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md"
        >

            <div class="flex items-center justify-between gap-4">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M20.8 8.6c0 5.5-8.8 10.4-8.8 10.4S3.2 14.1 3.2 8.6A4.6 4.6 0 017.8 4c1.7 0 3.3.9 4.2 2.2A5 5 0 0116.2 4a4.6 4.6 0 014.6 4.6Z"/>
                    </svg>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    Favoris
                </span>

            </div>

            <p class="mt-5 text-2xl font-bold text-gray-900">
                {{ $favoritesCount }}
            </p>

            <p class="mt-1 text-sm text-gray-500">

                article{{ $favoritesCount > 1 ? 's' : '' }}

                enregistré{{ $favoritesCount > 1 ? 's' : '' }}

            </p>

        </a>



        {{-- RÉSERVATIONS --}}

        <a
            href="{{ $reservationsUrl }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md"
        >

            <div class="flex items-center justify-between gap-4">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M8 2v4"/>
                        <path d="M16 2v4"/>
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M3 10h18"/>
                        <path d="M8 14h.01"/>
                        <path d="M12 14h.01"/>
                        <path d="M16 14h.01"/>
                    </svg>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    Réservations
                </span>

            </div>

            <p class="mt-5 text-2xl font-bold text-gray-900">
                {{ $reservationsCount }}
            </p>

            <p class="mt-1 text-sm text-gray-500">

                {{ $confirmedReservationsCount }}

                confirmée{{ $confirmedReservationsCount > 1 ? 's' : '' }}

            </p>

        </a>



        {{-- ÉVÉNEMENTS --}}

        <a
            href="{{ $eventsUrl }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md"
        >

            <div class="flex items-center justify-between gap-4">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M8 2v4"/>
                        <path d="M16 2v4"/>
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M3 10h18"/>
                        <path d="M8 14h.01"/>
                        <path d="M12 14h.01"/>
                        <path d="M16 14h.01"/>
                    </svg>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    Événements
                </span>

            </div>

            <p class="mt-5 text-2xl font-bold text-gray-900">
                {{ $eventsCount }}
            </p>

            <p class="mt-1 text-sm text-gray-500">
                Masterclass réservées
            </p>

        </a>



        {{-- COMMANDES --}}

        <a
            href="{{ $ordersUrl }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md"
        >

            <div class="flex items-center justify-between gap-4">

                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M6 2l-3 6v14h18V8l-3-6H6Z"/>
                        <path d="M3 8h18"/>
                        <path d="M8 12a4 4 0 008 0"/>
                    </svg>

                </div>

                <span class="text-xs font-medium text-gray-400">
                    Commandes
                </span>

            </div>

            <p class="mt-5 text-2xl font-bold text-gray-900">
                {{ $ordersCount }}
            </p>

            <p class="mt-1 text-sm text-gray-500">

                {{ $paidOrdersCount }}

                payée{{ $paidOrdersCount > 1 ? 's' : '' }}

            </p>

        </a>

    </section>



    {{-- ======================================================================
        RÉSERVATIONS + PAIEMENTS
    ======================================================================= --}}

    <section class="mb-8 grid gap-6 xl:grid-cols-2">


        {{-- ==================================================================
            RÉSERVATIONS RÉCENTES
        =================================================================== --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-5">

                <div class="min-w-0">

                    <h2 class="font-bold text-gray-900">
                        Mes réservations
                    </h2>

                    <p class="mt-1 text-xs text-gray-500">
                        Vos dernières inscriptions
                    </p>

                </div>

                <a
                    href="{{ $reservationsUrl }}"
                    class="shrink-0 text-xs font-semibold text-[#E8631A] hover:underline"
                >
                    Tout voir
                </a>

            </div>


            @forelse($recentReservations as $reservation)

                @php
                    $reservable = $reservation->reservable;
                    $reservationStatus = $reservation->status ?? 'pending';

                    $statusClass = match ($reservationStatus) {
                        'confirmed' => 'bg-green-50 text-green-700',
                        'cancelled' => 'bg-red-50 text-red-700',
                        'completed' => 'bg-blue-50 text-blue-700',
                        default => 'bg-amber-50 text-amber-700',
                    };
                @endphp


                <div class="border-b border-gray-100 px-6 py-4 last:border-0">

                    <div class="flex items-start gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-[#E8631A]">

                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <path d="M16 2v4"/>
                                <path d="M8 2v4"/>
                                <path d="M3 10h18"/>
                            </svg>

                        </div>


                        <div class="min-w-0 flex-1">

                            <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">

                                <div class="min-w-0">

                                    <p class="truncate text-sm font-semibold text-gray-900">
                                        {{ $reservable?->title ?? $reservable?->name ?? 'Réservation' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $reservation->created_at?->format('d/m/Y à H:i') ?? 'Date inconnue' }}
                                    </p>

                                </div>


                                <span
                                    class="inline-flex w-fit shrink-0 rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $statusClass }}"
                                >
                                    {{ $reservationStatusLabels[$reservationStatus] ?? ucfirst($reservationStatus) }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">

                        <svg
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <path d="M16 2v4"/>
                            <path d="M8 2v4"/>
                            <path d="M3 10h18"/>
                        </svg>

                    </div>

                    <p class="mt-4 text-sm font-semibold text-gray-900">
                        Aucune réservation
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Vous n'avez encore aucune réservation.
                    </p>

                </div>

            @endforelse

        </div>



        {{-- ==================================================================
            PAIEMENTS
        =================================================================== --}}

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-100 px-6 py-5">

                <div class="flex items-start justify-between gap-4">

                    <div>

                        <h2 class="font-bold text-gray-900">
                            Mes paiements
                        </h2>

                        <p class="mt-1 text-xs text-gray-500">
                            Historique de vos transactions
                        </p>

                    </div>

                    <a
                        href="{{ $paymentsUrl }}"
                        class="shrink-0 text-xs font-semibold text-[#E8631A] hover:underline"
                    >
                        Tout voir
                    </a>

                </div>


                <div class="mt-5 rounded-xl bg-gray-50 p-4">

                    <p class="text-xs font-medium text-gray-500">
                        Total payé
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">

                        {{ number_format($totalPaid, 0, ',', ' ') }}

                        <span class="text-sm font-semibold text-gray-500">
                            FCFA
                        </span>

                    </p>

                </div>

            </div>


            @forelse($recentTransactions as $transaction)

                @php
                    $transactionStatus = $transaction->status ?? 'pending';

                    $transactionClass = match ($transactionStatus) {
                        'completed' => 'bg-green-50 text-green-700',
                        'failed', 'cancelled' => 'bg-red-50 text-red-700',
                        'refunded' => 'bg-blue-50 text-blue-700',
                        default => 'bg-amber-50 text-amber-700',
                    };
                @endphp


                <div class="border-b border-gray-100 px-6 py-4 last:border-0">

                    <div class="flex items-center gap-4">

                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gray-50 text-gray-500">

                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path d="M3 10h18"/>
                                <path d="M7 15h4"/>
                            </svg>

                        </div>


                        <div class="min-w-0 flex-1">

                            <p class="truncate text-sm font-semibold text-gray-900">
                                {{ $transaction->description ?? 'Transaction' }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">

                                {{ $transaction->date?->format('d/m/Y')
                                    ?? $transaction->created_at?->format('d/m/Y')
                                    ?? 'Date inconnue'
                                }}

                            </p>

                        </div>


                        <div class="shrink-0 text-right">

                            <p class="text-sm font-bold text-gray-900">

                                {{ number_format((float) ($transaction->amount ?? 0), 0, ',', ' ') }}

                                <span class="text-[10px] font-semibold text-gray-500">
                                    FCFA
                                </span>

                            </p>

                            <span
                                class="mt-1 inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold {{ $transactionClass }}"
                            >
                                {{ $transactionStatusLabels[$transactionStatus] ?? ucfirst($transactionStatus) }}
                            </span>

                        </div>

                    </div>

                </div>

            @empty

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">

                        <svg
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <rect x="3" y="4" width="18" height="16" rx="2"/>
                            <path d="M3 10h18"/>
                        </svg>

                    </div>

                    <p class="mt-4 text-sm font-semibold text-gray-900">
                        Aucun paiement
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        Vos transactions apparaîtront ici.
                    </p>

                </div>

            @endforelse

        </div>

    </section>



    {{-- ======================================================================
        COMMANDES RÉCENTES
    ======================================================================= --}}

    <section class="mb-8 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

        <div class="flex items-center justify-between gap-4 border-b border-gray-100 px-6 py-5">

            <div>

                <h2 class="font-bold text-gray-900">
                    Mes commandes
                </h2>

                <p class="mt-1 text-xs text-gray-500">
                    Vos dernières commandes
                </p>

            </div>

            <a
                href="{{ $ordersUrl }}"
                class="shrink-0 text-xs font-semibold text-[#E8631A] hover:underline"
            >
                Toutes les commandes
            </a>

        </div>


        @forelse($recentOrders as $order)

            @php
                $orderStatus = $order->status ?? 'pending';

                $orderClass = match ($orderStatus) {
                    'paid', 'completed' => 'bg-green-50 text-green-700',
                    'cancelled', 'failed' => 'bg-red-50 text-red-700',
                    'processing' => 'bg-blue-50 text-blue-700',
                    'refunded' => 'bg-purple-50 text-purple-700',
                    default => 'bg-amber-50 text-amber-700',
                };

                $itemsCount = $order->items?->count() ?? 0;

                $orderTotal = $order->total
                    ?? $order->total_amount
                    ?? 0;
            @endphp


            <div class="border-b border-gray-100 px-6 py-4 last:border-0">

                <div class="flex flex-col gap-4 md:flex-row md:items-center">


                    {{-- COMMANDE --}}

                    <div class="flex min-w-0 flex-1 items-center gap-4">

                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-gray-900 text-white">

                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M6 2l-3 6v14h18V8l-3-6H6Z"/>
                                <path d="M3 8h18"/>
                            </svg>

                        </div>


                        <div class="min-w-0">

                            <p class="truncate text-sm font-bold text-gray-900">
                                Commande #{{ $order->id }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500">
                                {{ $order->created_at?->format('d/m/Y à H:i') ?? 'Date inconnue' }}
                            </p>

                        </div>

                    </div>


                    {{-- ARTICLES --}}

                    <div class="text-sm text-gray-500 md:w-40">

                        <span class="font-semibold text-gray-700">
                            {{ $itemsCount }}
                        </span>

                        article{{ $itemsCount > 1 ? 's' : '' }}

                    </div>


                    {{-- TOTAL --}}

                    <div class="text-left md:w-36 md:text-right">

                        <p class="text-sm font-bold text-gray-900">

                            {{ number_format((float) $orderTotal, 0, ',', ' ') }}

                            <span class="text-[10px] font-semibold text-gray-500">
                                FCFA
                            </span>

                        </p>

                    </div>


                    {{-- STATUT --}}

                    <div class="md:w-32 md:text-right">

                        <span
                            class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-semibold {{ $orderClass }}"
                        >
                            {{ $orderStatusLabels[$orderStatus] ?? ucfirst($orderStatus) }}
                        </span>

                    </div>

                </div>

            </div>

        @empty

            <div class="px-6 py-14 text-center">

                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-50 text-gray-400">

                    <svg
                        class="h-6 w-6"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                    >
                        <path d="M6 2l-3 6v14h18V8l-3-6H6Z"/>
                        <path d="M3 8h18"/>
                    </svg>

                </div>

                <p class="mt-4 text-sm font-semibold text-gray-900">
                    Aucune commande
                </p>

                <p class="mt-1 text-xs text-gray-500">
                    Vous n'avez encore effectué aucune commande.
                </p>

            </div>

        @endforelse

    </section>



    {{-- ======================================================================
        RÉSUMÉ RAPIDE
    ======================================================================= --}}

    <section class="grid gap-6 lg:grid-cols-3">


        {{-- ==================================================================
            RÉSERVATIONS
        =================================================================== --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M8 2v4"/>
                        <path d="M16 2v4"/>
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <path d="M3 10h18"/>
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-semibold text-gray-900">
                        Réservations
                    </p>

                    <p class="text-xs text-gray-500">
                        État de vos inscriptions
                    </p>

                </div>

            </div>


            <div class="mt-5 grid grid-cols-2 gap-3">

                <div class="rounded-xl bg-green-50 p-3">

                    <p class="text-xl font-bold text-green-700">
                        {{ $confirmedReservationsCount }}
                    </p>

                    <p class="mt-1 text-xs text-green-700/70">
                        Confirmées
                    </p>

                </div>


                <div class="rounded-xl bg-amber-50 p-3">

                    <p class="text-xl font-bold text-amber-700">
                        {{ $pendingReservationsCount }}
                    </p>

                    <p class="mt-1 text-xs text-amber-700/70">
                        En attente
                    </p>

                </div>

            </div>

        </div>



        {{-- ==================================================================
            PAIEMENTS
        =================================================================== --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <path d="M3 10h18"/>
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-semibold text-gray-900">
                        Paiements
                    </p>

                    <p class="text-xs text-gray-500">
                        État de vos transactions
                    </p>

                </div>

            </div>


            <div class="mt-5 grid grid-cols-2 gap-3">

                <div class="rounded-xl bg-green-50 p-3">

                    <p class="text-xl font-bold text-green-700">
                        {{ $completedPaymentsCount }}
                    </p>

                    <p class="mt-1 text-xs text-green-700/70">
                        Effectués
                    </p>

                </div>


                <div class="rounded-xl bg-amber-50 p-3">

                    <p class="text-xl font-bold text-amber-700">
                        {{ $pendingPaymentsCount }}
                    </p>

                    <p class="mt-1 text-xs text-amber-700/70">
                        En attente
                    </p>

                </div>

            </div>

        </div>



        {{-- ==================================================================
            ACCÈS RAPIDES
        =================================================================== --}}

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-[#E8631A]">

                    <svg
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8Z"/>
                    </svg>

                </div>

                <div>

                    <p class="text-sm font-semibold text-gray-900">
                        Accès rapides
                    </p>

                    <p class="text-xs text-gray-500">
                        Les espaces les plus utilisés
                    </p>

                </div>

            </div>


            <div class="mt-5 grid grid-cols-2 gap-2">

                <a
                    href="{{ $messagesUrl }}"
                    class="rounded-lg border border-gray-100 px-3 py-2 text-center text-xs font-semibold text-gray-700 transition hover:border-orange-200 hover:bg-orange-50/50 hover:text-[#E8631A]"
                >
                    Messages
                </a>


                <a
                    href="{{ $eventsUrl }}"
                    class="rounded-lg border border-gray-100 px-3 py-2 text-center text-xs font-semibold text-gray-700 transition hover:border-orange-200 hover:bg-orange-50/50 hover:text-[#E8631A]"
                >
                    Événements
                </a>


                <a
                    href="{{ $ordersUrl }}"
                    class="rounded-lg border border-gray-100 px-3 py-2 text-center text-xs font-semibold text-gray-700 transition hover:border-orange-200 hover:bg-orange-50/50 hover:text-[#E8631A]"
                >
                    Commandes
                </a>


                <a
                    href="{{ $settingsUrl }}"
                    class="rounded-lg border border-gray-100 px-3 py-2 text-center text-xs font-semibold text-gray-700 transition hover:border-orange-200 hover:bg-orange-50/50 hover:text-[#E8631A]"
                >
                    Paramètres
                </a>

            </div>

        </div>

    </section>

</x-layouts.member>
