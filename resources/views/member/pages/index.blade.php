<x-layouts.member :title="$title">

    @php
        $brandColor = '#E8631A';

        /*
        |--------------------------------------------------------------------------
        | MÉTA DE STATUTS
        |--------------------------------------------------------------------------
        */

        $orderStatusMeta = [
            'pending'    => ['label' => 'En attente',  'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
            'paid'       => ['label' => 'Payée',       'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
            'processing' => ['label' => 'En traitement','class' => 'bg-blue-50 text-blue-700 border-blue-100'],
            'completed'  => ['label' => 'Terminée',    'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
            'cancelled'  => ['label' => 'Annulée',     'class' => 'bg-red-50 text-red-700 border-red-100'],
            'failed'     => ['label' => 'Échouée',     'class' => 'bg-red-50 text-red-700 border-red-100'],
            'refunded'   => ['label' => 'Remboursée',  'class' => 'bg-violet-50 text-violet-700 border-violet-100'],
        ];

        $reservationStatusMeta = [
            'pending'   => ['label' => 'En attente',  'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
            'confirmed' => ['label' => 'Confirmée',   'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
            'cancelled' => ['label' => 'Annulée',     'class' => 'bg-red-50 text-red-700 border-red-100'],
            'completed' => ['label' => 'Terminée',    'class' => 'bg-blue-50 text-blue-700 border-blue-100'],
        ];

        $transactionStatusMeta = [
            'pending'   => ['label' => 'En attente', 'class' => 'bg-amber-50 text-amber-700 border-amber-100'],
            'completed' => ['label' => 'Effectué',   'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100'],
            'failed'    => ['label' => 'Échoué',     'class' => 'bg-red-50 text-red-700 border-red-100'],
            'cancelled' => ['label' => 'Annulé',     'class' => 'bg-red-50 text-red-700 border-red-100'],
            'refunded'  => ['label' => 'Remboursé',  'class' => 'bg-violet-50 text-violet-700 border-violet-100'],
        ];
    @endphp

    <style>
        :root {
            --brand: #E8631A;
            --brand-soft: rgba(232, 99, 26, .08);
            --brand-ring: rgba(232, 99, 26, .18);
        }

        .brand-text { color: var(--brand); }
        .brand-bg { background-color: var(--brand); }
        .brand-bg-soft { background-color: var(--brand-soft); }

        .brand-btn {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 12px 28px -14px rgba(232, 99, 26, .55);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .brand-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 34px -15px rgba(232, 99, 26, .65);
        }
        .brand-btn:active { transform: translateY(0); }

        .ghost-btn {
            transition: background-color .18s ease, border-color .18s ease, color .18s ease, transform .18s ease;
        }
        .ghost-btn:hover {
            border-color: rgba(232, 99, 26, .35);
            color: var(--brand);
            transform: translateY(-1px);
        }

        .card-soft {
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            border-color: rgba(232, 99, 26, .22);
            box-shadow: 0 20px 45px -28px rgba(15, 23, 42, .22);
        }

        .row-hover { transition: background-color .16s ease; }
        .row-hover:hover { background-color: rgba(248, 250, 252, .8); }

        .fade-in { animation: fadeIn .45s ease both; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .status-dot {
            animation: pulseDot 2s ease-in-out infinite;
        }
        @keyframes pulseDot {
            0%, 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, .55); }
            50%      { box-shadow: 0 0 0 5px rgba(34, 197, 94, 0); }
        }

        @media (prefers-reduced-motion: reduce) {
            .fade-in, .status-dot, .brand-btn, .ghost-btn, .card-soft { animation: none; transition: none; }
        }
    </style>


    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="flex flex-col gap-2 fade-in">

        <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400">
            <span>Espace membre</span>
            <x-icon name="chevron-right" class="h-3.5 w-3.5" />
            <span class="brand-text">{{ $title }}</span>
        </nav>

        <div class="mt-2 flex items-center gap-3">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl brand-bg-soft brand-text">
                <x-icon :name="$icon" class="h-5 w-5" />
            </div>

            <div>
                <h1 class="text-2xl font-black tracking-tight text-slate-900 md:text-3xl">
                    {{ $title }}
                </h1>
            </div>

        </div>

        @if(!empty($description))
            <p class="mt-1 max-w-3xl text-sm leading-6 text-slate-500 md:text-base">
                {{ $description }}
            </p>
        @endif

    </div>


    {{-- ==========================================================
        CONTENU
    =========================================================== --}}

    <div class="mt-8 fade-in">


        {{-- ======================================================
            MON PROFIL
        ======================================================= --}}

        @if($section === 'profile')

            @php
                $initials = collect(preg_split('/\s+/', trim($user->name ?? 'M')))
                    ->filter()
                    ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
                    ->take(2)
                    ->implode('');

                $profileFields = [
                    'name' => $user->name ?? null,
                    'email' => $user->email ?? null,
                    'phone' => $user->phone ?? null,
                    'country' => $user->country ?? null,
                    'city' => $user->city ?? null,
                    'address' => $user->address ?? null,
                    'bio' => $user->bio ?? null,
                    'profile_photo' => $user->profile_photo ?? null,
                ];

                $filled = collect($profileFields)->filter(fn ($v) => ! empty($v))->count();
                $total = count($profileFields);
                $completion = $total > 0 ? (int) round(($filled / $total) * 100) : 0;
            @endphp

            <div class="grid gap-6 lg:grid-cols-3">

                {{-- Carte profil --}}
                <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)] lg:col-span-1">

                    <div class="flex flex-col items-center text-center">

                        @if($user->profile_photo)
                            <img
                                src="{{ asset('storage/' . $user->profile_photo) }}"
                                alt="{{ $user->name }}"
                                class="h-24 w-24 rounded-full object-cover ring-4 ring-[#E8631A]/10"
                            >
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-full brand-bg text-3xl font-black text-white ring-4 ring-[#E8631A]/10">
                                {{ $initials ?: 'M' }}
                            </div>
                        @endif

                        <h2 class="mt-4 text-xl font-black text-slate-900">
                            {{ $user->name }}
                        </h2>

                        <p class="mt-1 break-all text-sm text-slate-500">
                            {{ $user->email }}
                        </p>

                        <div class="mt-4">
                            <span class="inline-flex items-center gap-2 rounded-full brand-bg-soft px-3 py-1 text-xs font-bold brand-text">
                                <span class="status-dot h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                {{ ucfirst($user->status ?? 'Membre') }}
                            </span>
                        </div>


                        {{-- Progression --}}
                        <div class="mt-6 w-full text-left">

                            <div class="mb-2 flex items-center justify-between gap-3 text-xs">
                                <span class="flex items-center gap-1.5 font-bold uppercase tracking-wider text-slate-500">
                                    <x-icon name="sparkles" class="h-3.5 w-3.5 brand-text" />
                                    Profil complété
                                </span>

                                <span class="inline-flex items-center rounded-full brand-bg-soft px-2.5 py-0.5 text-[11px] font-black brand-text">
                                    {{ $completion }}%
                                </span>
                            </div>

                            <div class="h-2.5 overflow-hidden rounded-full bg-slate-100 ring-1 ring-inset ring-slate-200/60">
                                <div
                                    class="h-full rounded-full brand-bg shadow-[0_0_12px_-2px_rgba(232,99,26,.55)] transition-all duration-700 ease-out"
                                    style="width: {{ $completion }}%"
                                ></div>
                            </div>

                            @if($completion < 100)
                                <p class="mt-2 flex items-center gap-1.5 text-[11px] text-slate-400">
                                    <x-icon name="info" class="h-3 w-3" />
                                    Complétez votre profil pour tout débloquer.
                                </p>
                            @else
                                <p class="mt-2 flex items-center gap-1.5 text-[11px] font-semibold text-emerald-600">
                                    <x-icon name="check-circle" class="h-3 w-3" />
                                    Profil complet — Bravo !
                                </p>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- Informations --}}
                <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)] sm:p-7 lg:col-span-2">

                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-black text-slate-900">
                                Informations personnelles
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Les informations associées à votre compte.
                            </p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl brand-bg-soft brand-text">
                            <x-icon name="user" class="h-5 w-5" />
                        </div>
                    </div>


                    <div class="mt-6 grid gap-5 sm:grid-cols-2">

                        @php
                            $fields = [
                                ['label' => 'Nom', 'value' => $user->name],
                                ['label' => 'Adresse e-mail', 'value' => $user->email, 'break' => true],
                                ['label' => 'Téléphone', 'value' => $user->phone ?? '—'],
                                ['label' => 'Pays', 'value' => $user->country ?? '—'],
                                ['label' => 'Ville', 'value' => $user->city ?? '—'],
                                ['label' => 'Adresse', 'value' => $user->address ?? '—'],
                                ['label' => 'Statut', 'value' => ucfirst($user->status ?? 'Actif')],
                                ['label' => 'Membre depuis', 'value' => optional($user->created_at)->translatedFormat('d F Y')],
                            ];
                        @endphp

                        @foreach($fields as $f)
                            <div class="rounded-2xl border border-slate-100 bg-slate-50/40 p-4">
                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                    {{ $f['label'] }}
                                </p>
                                <p class="mt-1.5 font-semibold text-slate-800 {{ !empty($f['break']) ? 'break-all' : '' }}">
                                    {{ $f['value'] ?: '—' }}
                                </p>
                            </div>
                        @endforeach

                    </div>

                </div>

            </div>


        {{-- ======================================================
            MES MESSAGES
        ======================================================= --}}

        @elseif($section === 'messages')

            @php
                $totalMsg = $messages->count();
                $unreadMsg = $messages->where('is_from_admin', true)->whereNull('read_by_member_at')->count();
            @endphp

            <div class="space-y-6">

                {{-- Stats --}}
                <div class="grid gap-4 sm:grid-cols-2">

                    <div class="card-soft card-hover rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl brand-bg-soft brand-text">
                                <x-icon name="message-circle" class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Total des messages
                                </p>
                                <p class="mt-1 text-2xl font-black text-slate-900">
                                    {{ $totalMsg }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="card-soft card-hover rounded-2xl border border-slate-200 bg-white p-5">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                                <x-icon name="mail" class="h-5 w-5" />
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                    Non lus
                                </p>
                                <p class="mt-1 text-2xl font-black {{ $unreadMsg > 0 ? 'text-red-600' : 'text-slate-900' }}">
                                    {{ $unreadMsg }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>


                {{-- Liste --}}
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-black text-slate-900">
                            Vos messages
                        </h2>
                    </div>

                    @forelse($messages as $message)
                        @php
                            $isUnread = $message->is_from_admin && ! $message->read_by_member_at;
                            $authorName = $message->author->name ?? 'Équipe Generation PUSH';
                            $initial = strtoupper(mb_substr($authorName, 0, 1));
                        @endphp

                        <div class="row-hover border-b border-slate-100 p-5 last:border-b-0">
                            <div class="flex gap-4">

                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full brand-bg-soft brand-text text-xs font-black">
                                    {{ $initial }}
                                </div>

                                <div class="min-w-0 flex-1">

                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="font-bold text-slate-900">
                                            {{ $authorName }}
                                        </p>
                                        <span class="text-xs text-slate-500">
                                            {{ optional($message->created_at)->translatedFormat('d M Y à H:i') }}
                                        </span>
                                    </div>

                                    <p class="mt-2 text-sm leading-6 text-slate-600">
                                        {{ $message->message ?? $message->content ?? '' }}
                                    </p>

                                    @if($isUnread)
                                        <span class="mt-3 inline-flex items-center gap-1.5 rounded-full brand-bg-soft px-2.5 py-1 text-xs font-bold brand-text">
                                            <span class="h-1.5 w-1.5 rounded-full brand-bg"></span>
                                            Nouveau
                                        </span>
                                    @endif

                                </div>

                            </div>
                        </div>
                    @empty

                        <div class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                                <x-icon name="message-circle" class="h-6 w-6" />
                            </div>
                            <h3 class="mt-4 font-bold text-slate-900">
                                Aucun message
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Vous n'avez encore reçu aucun message.
                            </p>
                        </div>

                    @endforelse

                </div>

            </div>


        {{-- ======================================================
            NOTIFICATIONS
        ======================================================= --}}

        @elseif($section === 'notifications')

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-base font-black text-slate-900">
                        Notifications
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Les informations importantes concernant votre compte.
                    </p>
                </div>

                @php $notifications = $notifications ?? collect(); @endphp

                @forelse($notifications as $notification)
                    <div class="row-hover border-b border-slate-100 p-5 last:border-b-0">
                        <div class="flex items-start gap-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl brand-bg-soft brand-text">
                                <x-icon name="{{ $notification->icon ?? 'bell' }}" class="h-5 w-5" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-slate-900">{{ $notification->title ?? 'Notification' }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $notification->message ?? '' }}</p>
                                <p class="mt-2 text-xs text-slate-400">
                                    {{ optional($notification->created_at)->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty

                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                            <x-icon name="bell" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">
                            Aucune notification
                        </h3>
                        <p class="mx-auto mt-1 max-w-md text-sm text-slate-500">
                            Vous êtes à jour. Les nouvelles notifications apparaîtront automatiquement ici.
                        </p>
                    </div>

                @endforelse

            </div>


        {{-- ======================================================
            FAVORIS
        ======================================================= --}}

        @elseif($section === 'bookmarks')

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-base font-black text-slate-900">
                                Mes articles favoris
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $bookmarks->count() }} article(s) enregistré(s)
                            </p>
                        </div>
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                            <x-icon name="heart" class="h-5 w-5" />
                        </div>
                    </div>
                </div>

                @forelse($bookmarks as $post)

                    <div class="row-hover border-b border-slate-100 p-5 last:border-b-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <h3 class="font-bold text-slate-900">
                                    {{ $post->title }}
                                </h3>
                                @if(!empty($post->excerpt))
                                    <p class="mt-1 text-sm leading-6 text-slate-500">
                                        {{ \Illuminate\Support\Str::limit($post->excerpt, 180) }}
                                    </p>
                                @endif

                                <p class="mt-2 text-xs text-slate-400">
                                    Ajouté {{ optional($post->created_at)->diffForHumans() }}
                                </p>
                            </div>

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                                <x-icon name="heart" class="h-4 w-4 fill-current" />
                            </div>
                        </div>
                    </div>

                @empty

                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-pink-50 text-pink-500">
                            <x-icon name="heart" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">
                            Aucun favori
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Les articles ajoutés à vos favoris apparaîtront ici.
                        </p>
                    </div>

                @endforelse

            </div>


        {{-- ======================================================
            FORMATIONS
        ======================================================= --}}

        @elseif($section === 'formations')

            @php $formations = $formations ?? collect(); @endphp

            @if($formations->isEmpty())

                <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                        <x-icon name="graduation-cap" class="h-7 w-7" />
                    </div>
                    <h3 class="mt-5 font-bold text-slate-900">Aucune formation</h3>
                    <p class="mx-auto mt-2 max-w-md text-sm text-slate-500">
                        Vous n'êtes inscrit à aucune formation pour le moment.
                    </p>
                </div>

            @else

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

                    @foreach($formations as $formation)

                        @php
                            $progress = (int) ($formation->pivot->progress ?? $formation->progress ?? 0);
                        @endphp

                        <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                            <div class="flex items-start justify-between">
                                <div class="flex h-12 w-12 items-center justify-center rounded-xl brand-bg-soft brand-text">
                                    <x-icon name="graduation-cap" class="h-6 w-6" />
                                </div>

                                <span class="inline-flex items-center rounded-full brand-bg-soft px-2.5 py-1 text-[11px] font-black brand-text">
                                    {{ $progress }}%
                                </span>
                            </div>

                            <h2 class="mt-5 font-bold text-slate-900 line-clamp-2">
                                {{ $formation->title }}
                            </h2>

                            @if(!empty($formation->description))
                                <p class="mt-2 text-sm leading-6 text-slate-500 line-clamp-3">
                                    {{ \Illuminate\Support\Str::limit($formation->description, 140) }}
                                </p>
                            @endif

                            <div class="mt-5 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full brand-bg transition-all duration-700"
                                    style="width: {{ $progress }}%"
                                ></div>
                            </div>

                        </div>

                    @endforeach

                </div>

            @endif


        {{-- ======================================================
            ÉVÉNEMENTS
        ======================================================= --}}

        @elseif($section === 'events')

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-base font-black text-slate-900">
                        Mes événements
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $events->count() }} événement(s) trouvé(s)
                    </p>
                </div>

                @forelse($events as $reservation)

                    @php
                        $event = $reservation->reservable;
                        $evStatus = $reservation->status ?? 'pending';
                        $evMeta = $reservationStatusMeta[$evStatus] ?? ['label' => ucfirst($evStatus), 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                    @endphp

                    <div class="row-hover border-b border-slate-100 p-5 last:border-b-0">
                        <div class="flex items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl brand-bg-soft brand-text">
                                <x-icon name="calendar-days" class="h-5 w-5" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-slate-900">
                                    {{ $event->title ?? 'Événement' }}
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ optional($event->start_at ?? $event->date)->translatedFormat('d F Y à H:i') ?? 'Date à venir' }}
                                </p>
                            </div>

                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $evMeta['class'] }}">
                                {{ $evMeta['label'] }}
                            </span>

                        </div>
                    </div>

                @empty

                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                            <x-icon name="calendar-days" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">Aucun événement</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Vos événements réservés apparaîtront ici.
                        </p>
                    </div>

                @endforelse

            </div>


        {{-- ======================================================
            RÉSERVATIONS
        ======================================================= --}}

        @elseif($section === 'reservations')

            <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-base font-black text-slate-900">
                        Mes réservations
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        {{ $reservations->count() }} réservation(s)
                    </p>
                </div>

                @forelse($reservations as $reservation)

                    @php
                        $rStatus = $reservation->status ?? 'pending';
                        $rMeta = $reservationStatusMeta[$rStatus] ?? ['label' => ucfirst($rStatus), 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                    @endphp

                    <div class="row-hover border-b border-slate-100 p-5 last:border-b-0">
                        <div class="flex items-start gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl brand-bg-soft brand-text">
                                <x-icon name="ticket" class="h-5 w-5" />
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="font-bold text-slate-900">
                                    {{ $reservation->reservable->title ?? 'Réservation' }}
                                </h3>
                                <p class="mt-1 text-sm text-slate-500">
                                    Réservée le {{ optional($reservation->created_at)->translatedFormat('d F Y à H:i') }}
                                </p>
                            </div>

                            <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $rMeta['class'] }}">
                                {{ $rMeta['label'] }}
                            </span>

                        </div>
                    </div>

                @empty

                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                            <x-icon name="ticket" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">Aucune réservation</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Vos réservations apparaîtront ici.
                        </p>
                    </div>

                @endforelse

            </div>


        {{-- ======================================================
            COMMANDES
        ======================================================= --}}

        @elseif($section === 'orders')

            <div class="space-y-4">

                @forelse($orders as $order)

                    @php
                        $oStatus = $order->status ?? 'pending';
                        $oMeta = $orderStatusMeta[$oStatus] ?? ['label' => ucfirst($oStatus), 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                    @endphp

                    <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                            <div class="flex items-center gap-4">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl brand-bg text-white">
                                    <x-icon name="shopping-bag" class="h-5 w-5" />
                                </div>

                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                        Commande
                                    </p>
                                    <h2 class="mt-0.5 font-mono text-base font-black text-slate-900">
                                        {{ $order->order_number ?? '#' . $order->id }}
                                    </h2>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ optional($order->created_at)->translatedFormat('d F Y à H:i') }}
                                    </p>
                                </div>
                            </div>

                            <div class="text-left sm:text-right">
                                <p class="text-xl font-black text-slate-900">
                                    {{ number_format((float) $order->total, 0, ',', ' ') }}
                                    <span class="text-xs font-bold text-slate-400">FCFA</span>
                                </p>
                                <span class="mt-1 inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $oMeta['class'] }}">
                                    {{ $oMeta['label'] }}
                                </span>
                            </div>

                        </div>


                        @if($order->items->count())

                            <div class="mt-5 border-t border-slate-100 pt-5">

                                <p class="mb-3 text-xs font-black uppercase tracking-wider text-slate-400">
                                    Articles
                                </p>

                                <div class="space-y-2">

                                    @foreach($order->items as $item)
                                        <div class="flex items-center justify-between gap-4 rounded-xl bg-slate-50/60 px-3.5 py-2.5 text-sm">
                                            <span class="text-slate-600">
                                                {{ $item->title ?? $item->product_name ?? 'Article' }}
                                                @if(isset($item->quantity))
                                                    <span class="ml-1 font-semibold brand-text">× {{ $item->quantity }}</span>
                                                @endif
                                            </span>

                                            @if(isset($item->total))
                                                <span class="font-bold text-slate-800">
                                                    {{ number_format((float) $item->total, 0, ',', ' ') }} FCFA
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach

                                </div>

                            </div>

                        @endif

                    </div>

                @empty

                    <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                            <x-icon name="shopping-bag" class="h-6 w-6" />
                        </div>
                        <h3 class="mt-4 font-bold text-slate-900">Aucune commande</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Votre historique de commandes apparaîtra ici.
                        </p>
                    </div>

                @endforelse

            </div>


        {{-- ======================================================
            PAIEMENTS
        ======================================================= --}}

        @elseif($section === 'payments')

            <div class="space-y-6">

                {{-- Total --}}
                <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">
                    <div class="flex items-center gap-4">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl brand-bg-soft brand-text">
                            <x-icon name="credit-card" class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="text-xs font-black uppercase tracking-wider text-slate-400">
                                Total des paiements effectués
                            </p>
                            <p class="mt-1 text-2xl font-black brand-text">
                                {{ number_format((float) $completedAmount, 0, ',', ' ') }}
                                <span class="text-sm font-bold text-slate-500">FCFA</span>
                            </p>
                        </div>
                    </div>
                </div>


                {{-- Transactions --}}
                <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                    <div class="border-b border-slate-100 px-6 py-5">
                        <h2 class="text-base font-black text-slate-900">
                            Historique des transactions
                        </h2>
                    </div>

                    @forelse($transactions as $transaction)

                        @php
                            $tStatus = $transaction->status ?? 'pending';
                            $tMeta = $transactionStatusMeta[$tStatus] ?? ['label' => ucfirst($tStatus), 'class' => 'bg-slate-50 text-slate-600 border-slate-100'];
                        @endphp

                        <div class="row-hover border-b border-slate-100 p-5 last:border-b-0">
                            <div class="flex items-center justify-between gap-4">

                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                        <x-icon name="receipt" class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $transaction->description ?? 'Transaction' }}
                                        </p>
                                        <p class="mt-0.5 text-xs text-slate-500">
                                            {{ optional($transaction->date ?? $transaction->created_at)->translatedFormat('d M Y à H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="font-black text-slate-900">
                                        {{ number_format((float) ($transaction->amount ?? 0), 0, ',', ' ') }}
                                        <span class="text-[10px] font-bold text-slate-400">FCFA</span>
                                    </p>
                                    <span class="mt-1 inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $tMeta['class'] }}">
                                        {{ $tMeta['label'] }}
                                    </span>
                                </div>

                            </div>
                        </div>

                    @empty

                        <div class="px-6 py-16 text-center">
                            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                                <x-icon name="credit-card" class="h-6 w-6" />
                            </div>
                            <h3 class="mt-4 font-bold text-slate-900">Aucun paiement</h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Votre historique de paiements apparaîtra ici.
                            </p>
                        </div>

                    @endforelse

                </div>

            </div>


        {{-- ======================================================
            PARAMÈTRES
        ======================================================= --}}

        @elseif($section === 'settings')

            @php $authUser = auth()->user(); @endphp

            <div class="grid gap-6 lg:grid-cols-2">

                {{-- Compte --}}
                <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl brand-bg-soft brand-text">
                            <x-icon name="user" class="h-5 w-5" />
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900">Compte</h2>
                            <p class="text-xs text-slate-500">
                                Informations de votre compte membre.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-slate-50/60 p-4">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Adresse e-mail
                            </p>
                            <p class="mt-1 break-all font-semibold text-slate-800">
                                {{ $authUser->email }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50/60 p-4">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Statut du compte
                            </p>
                            <p class="mt-1 font-semibold text-emerald-600">
                                {{ ucfirst($authUser->status ?? 'Actif') }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-50/60 p-4">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Membre depuis
                            </p>
                            <p class="mt-1 font-semibold text-slate-800">
                                {{ optional($authUser->created_at)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>

                </div>


                {{-- Sécurité --}}
                <div class="card-soft card-hover rounded-3xl border border-slate-200 bg-white p-6 shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl brand-bg-soft brand-text">
                            <x-icon name="shield-check" class="h-5 w-5" />
                        </div>
                        <div>
                            <h2 class="font-black text-slate-900">Sécurité</h2>
                            <p class="text-xs text-slate-500">
                                Gérez la sécurité de votre compte.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 rounded-2xl border border-slate-200 p-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">Mot de passe</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    Modifiez régulièrement votre mot de passe pour sécuriser votre compte.
                                </p>
                            </div>
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                <x-icon name="lock" class="h-4 w-4" />
                            </div>
                        </div>
                    </div>

                </div>

            </div>


        {{-- ======================================================
            SECTION INCONNUE
        ======================================================= --}}

        @else

            <div class="rounded-3xl border border-slate-200 bg-white p-10 text-center shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                    <x-icon :name="$icon" class="h-7 w-7" />
                </div>

                <h2 class="mt-5 text-xl font-black text-slate-900">
                    {{ $title }}
                </h2>

                <p class="mx-auto mt-2 max-w-xl text-sm text-slate-500">
                    Cette rubrique de votre espace membre est en cours de préparation.
                </p>

            </div>

        @endif

    </div>

</x-layouts.member>
