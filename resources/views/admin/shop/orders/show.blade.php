<x-layouts.admin :title="'Commande ' . $order->order_number">

    @php
        $statusMeta = [
            'pending'   => ['label' => 'En attente',  'class' => 'bg-amber-50 text-amber-700 border-amber-100',       'dot' => 'bg-amber-500'],
            'paid'      => ['label' => 'Payée',       'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'dot' => 'bg-emerald-500'],
            'cancelled' => ['label' => 'Annulée',     'class' => 'bg-red-50 text-red-700 border-red-100',             'dot' => 'bg-red-500'],
            'refunded'  => ['label' => 'Remboursée',  'class' => 'bg-violet-50 text-violet-700 border-violet-100',    'dot' => 'bg-violet-500'],
        ];

        $deliveryMeta = [
            'not_required' => ['label' => 'Pas de livraison', 'class' => 'text-slate-500 bg-slate-50 border-slate-200'],
            'pending'      => ['label' => 'En attente',       'class' => 'text-amber-700 bg-amber-50 border-amber-100'],
            'processing'   => ['label' => 'En préparation',   'class' => 'text-blue-700 bg-blue-50 border-blue-100'],
            'shipped'      => ['label' => 'Expédiée',         'class' => 'text-violet-700 bg-violet-50 border-violet-100'],
            'delivered'    => ['label' => 'Livrée',           'class' => 'text-emerald-700 bg-emerald-50 border-emerald-100'],
        ];

        $statusInfo = $statusMeta[$order->status] ?? [
            'label' => $order->statusLabel(),
            'class' => 'bg-slate-50 text-slate-600 border-slate-100',
            'dot' => 'bg-slate-400',
        ];

        $deliveryInfo = $deliveryMeta[$order->delivery_status] ?? [
            'label' => $order->deliveryStatusLabel(),
            'class' => 'text-slate-600 bg-slate-50 border-slate-200',
        ];

        $qrAvailable = $order->qr_code_path
            && \Illuminate\Support\Facades\Storage::disk('private')->exists($order->qr_code_path);

        $customerName = $order->customer_name ?? $order->user?->name ?? 'Client';
        $customerEmail = $order->customer_email ?? $order->user?->email;
        $initial = strtoupper(mb_substr(trim($customerName), 0, 1));
    @endphp

    <style>
        :root {
            --brand: #E8631A;
            --brand-soft: rgba(232, 99, 26, .07);
            --brand-soft-2: rgba(232, 99, 26, .12);
            --brand-ring: rgba(232, 99, 26, .18);
        }

        [x-cloak] { display: none !important; }

        .brand-text { color: var(--brand); }
        .brand-bg { background-color: var(--brand); }
        .brand-bg-soft { background-color: var(--brand-soft); }

        .brand-btn {
            background: var(--brand);
            color: white;
            box-shadow: 0 12px 28px -14px rgba(232, 99, 26, .65);
            transition: transform .18s ease, box-shadow .18s ease;
        }
        .brand-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 34px -15px rgba(232, 99, 26, .7);
        }
        .brand-btn:active { transform: translateY(0); }

        input:focus, select:focus, textarea:focus {
            outline: none !important;
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 4px var(--brand-ring) !important;
        }

        .card-soft {
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .card-soft:hover {
            border-color: rgba(232, 99, 26, .22);
            box-shadow: 0 20px 45px -28px rgba(15, 23, 42, .22);
        }

        .action-btn {
            transition: background-color .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease;
        }
        .action-btn:hover {
            transform: translateY(-1px);
            border-color: rgba(232, 99, 26, .3);
            box-shadow: 0 12px 26px -20px rgba(15, 23, 42, .25);
        }
        .action-btn:active { transform: translateY(0); }

        .qr-frame {
            background:
                radial-gradient(circle at top right, rgba(232, 99, 26, .08), transparent 50%),
                #f8fafc;
        }

        .fade-in { animation: fadeIn .45s ease both; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .pop-in { animation: popIn .35s cubic-bezier(.22,1,.36,1) both; }
        @keyframes popIn {
            from { opacity: 0; transform: scale(.94); }
            to   { opacity: 1; transform: scale(1); }
        }
    </style>

    <div
        x-data="{ qrOpen: false }"
        @keydown.escape.window="qrOpen = false"
        class="space-y-7 fade-in"
    >

        {{-- ============================================================
             HEADER
        ============================================================= --}}

        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">

            <div class="min-w-0">

                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <span>Admin</span>
                    <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                    <a href="{{ route('admin.shop.orders.index') }}" class="transition hover:text-slate-700">
                        Commandes
                    </a>
                    <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                    <span class="brand-text font-mono">{{ $order->order_number }}</span>
                </div>

                <div class="mt-3 flex flex-wrap items-center gap-3">

                    <h1 class="font-mono text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                        {{ $order->order_number }}
                    </h1>

                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $statusInfo['class'] }}">
                        <span class="h-1.5 w-1.5 rounded-full {{ $statusInfo['dot'] }}"></span>
                        {{ $statusInfo['label'] }}
                    </span>

                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $deliveryInfo['class'] }}">
                        {{ $deliveryInfo['label'] }}
                    </span>

                    @if ($qrAvailable)
                        <span class="inline-flex items-center gap-1 rounded-full bg-orange-50 px-2.5 py-1 text-[11px] font-bold text-orange-700">
                            <x-icon name="qr-code" class="h-3 w-3" />
                            QR disponible
                        </span>
                    @endif

                </div>

                <p class="mt-2 text-sm text-slate-500">
                    Créée le
                    <span class="font-semibold text-slate-700">
                        {{ $order->created_at->translatedFormat('d F Y à H:i') }}
                    </span>
                    · #{{ $order->id }}
                </p>

            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a
                    href="{{ route('admin.shop.orders.index') }}"
                    class="action-btn inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700"
                >
                    <x-icon name="arrow-left" class="h-4 w-4" />
                    Retour
                </a>
            </div>

        </div>


        {{-- ============================================================
             ALERTES
        ============================================================= --}}

        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">
                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0" />
                <div>
                    <p class="font-bold">Opération effectuée</p>
                    <p class="mt-0.5 text-emerald-600">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">
                <x-icon name="alert-circle" class="mt-0.5 h-5 w-5 shrink-0" />
                <div>
                    <p class="font-bold">Une erreur est survenue</p>
                    <p class="mt-0.5 text-red-600">{{ session('error') }}</p>
                </div>
            </div>
        @endif


        {{-- ============================================================
             CONTENU
        ============================================================= --}}

        <div class="grid gap-6 lg:grid-cols-3">


            {{-- ======================================================
                 COLONNE PRINCIPALE
            ======================================================= --}}
            <div class="lg:col-span-2 space-y-6">


                {{-- CLIENT --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-7 card-soft">

                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                                Client
                            </p>
                            <h2 class="mt-1 text-lg font-black text-slate-900">
                                Informations client
                            </h2>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl brand-bg-soft brand-text">
                            <x-icon name="user" class="h-5 w-5" />
                        </div>
                    </div>


                    <div class="flex items-start gap-4">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-full brand-bg-soft brand-text text-lg font-black">
                            {{ $initial ?: '?' }}
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-base font-black text-slate-900">
                                {{ $customerName }}
                            </p>

                            <div class="mt-2 space-y-1.5">
                                @if ($customerEmail)
                                    <p class="flex items-center gap-2 text-sm text-slate-500">
                                        <x-icon name="mail" class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                                        <span class="truncate">{{ $customerEmail }}</span>
                                    </p>
                                @endif

                                @if ($order->customer_phone)
                                    <p class="flex items-center gap-2 text-sm text-slate-500">
                                        <x-icon name="phone" class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                                        <span>{{ $order->customer_phone }}</span>
                                    </p>
                                @endif

                                @if ($order->customer_city || $order->customer_country)
                                    <p class="flex items-center gap-2 text-sm text-slate-500">
                                        <x-icon name="map-pin" class="h-3.5 w-3.5 shrink-0 text-slate-400" />
                                        <span>
                                            {{ $order->customer_city }}@if($order->customer_city && $order->customer_country), @endif{{ $order->customer_country }}
                                        </span>
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if ($order->user)
                            <a
                                href="{{ route('profile.edit') }}"
                                class="hidden sm:inline-flex items-center gap-1.5 rounded-xl border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-600 transition hover:border-[#E8631A]/30 hover:text-[#E8631A]"
                            >
                                <x-icon name="external-link" class="h-3.5 w-3.5" />
                                Voir le profil
                            </a>
                        @endif
                    </div>


                    @if ($order->customer_address)
                        <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Adresse de livraison
                            </p>
                            <p class="mt-1.5 text-sm font-medium leading-relaxed text-slate-700">
                                {{ $order->customer_address }}
                            </p>
                        </div>
                    @endif

                </section>


                {{-- ARTICLES --}}
                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white card-soft">

                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5 sm:px-7">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                                Commande
                            </p>
                            <h2 class="mt-1 text-lg font-black text-slate-900">
                                Articles commandés
                            </h2>
                        </div>

                        <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-bold text-slate-500">
                            {{ $order->items->count() }} {{ $order->items->count() > 1 ? 'articles' : 'article' }}
                        </span>
                    </div>


                    <div class="divide-y divide-slate-100">
                        @foreach ($order->items as $item)
                            <div class="p-6 sm:p-7">

                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                    <div class="flex min-w-0 gap-4">
                                        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-100">
                                            @if ($item->product?->imageUrl())
                                                <img
                                                    src="{{ $item->product->imageUrl() }}"
                                                    alt="{{ $item->title }}"
                                                    class="h-full w-full object-cover"
                                                >
                                            @else
                                                <x-icon name="package" class="h-6 w-6 text-slate-400" />
                                            @endif
                                        </div>

                                        <div class="min-w-0">
                                            <h3 class="truncate text-base font-bold text-slate-900">
                                                {{ $item->title }}
                                            </h3>

                                            <div class="mt-2 flex flex-wrap items-center gap-2">
                                                <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    {{ $item->fulfillmentTypeLabel() }}
                                                </span>

                                                <span class="text-xs text-slate-400">
                                                    × {{ $item->quantity }}
                                                </span>

                                                @if ($item->isDigital())
                                                    @if ($item->digital_access_granted_at)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-600">
                                                            <x-icon name="check-circle" class="h-3 w-3" />
                                                            Accès accordé
                                                        </span>
                                                    @elseif ($item->digital_file_path)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-600">
                                                            <x-icon name="lock" class="h-3 w-3" />
                                                            Fichier prêt
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                            <x-icon name="upload" class="h-3 w-3" />
                                                            Aucun fichier
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="shrink-0 text-left sm:text-right">
                                        <p class="text-base font-black text-slate-900">
                                            {{ number_format($item->subtotal(), 2) }} $
                                        </p>

                                        @if ($item->quantity > 1)
                                            <p class="mt-0.5 text-xs text-slate-400">
                                                {{ number_format($item->price, 2) }} $ / unité
                                            </p>
                                        @endif
                                    </div>

                                </div>


                                {{-- CONTENU NUMÉRIQUE --}}
                                @if ($item->isDigital())
                                    <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50/60 p-4">

                                        <div class="flex items-center gap-2 mb-3">
                                            <x-icon name="download" class="h-4 w-4 brand-text" />
                                            <p class="text-xs font-black uppercase tracking-wider text-slate-600">
                                                Contenu numérique
                                            </p>
                                        </div>

                                        <div class="grid gap-3 sm:grid-cols-2">

                                            {{-- Upload --}}
                                            <form
                                                method="POST"
                                                action="{{ route('admin.shop.orders.items.digital-file.upload', [$order, $item]) }}"
                                                enctype="multipart/form-data"
                                                class="flex flex-col gap-2"
                                            >
                                                @csrf

                                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    {{ $item->digital_file_path ? 'Remplacer le fichier' : 'Ajouter un fichier' }}
                                                </label>

                                                <div class="flex gap-2">
                                                    <input
                                                        type="file"
                                                        name="digital_file"
                                                        required
                                                        class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs file:mr-2 file:rounded-lg file:border-0 file:bg-slate-100 file:px-2.5 file:py-1 file:text-xs file:font-bold file:text-slate-700"
                                                    >

                                                    <button
                                                        type="submit"
                                                        class="brand-btn inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-xs font-bold"
                                                    >
                                                        <x-icon name="upload" class="h-3.5 w-3.5" />
                                                        Envoyer
                                                    </button>
                                                </div>
                                            </form>


                                            {{-- Accès --}}
                                            <div class="flex flex-col gap-2">
                                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                                    Accès membre
                                                </span>

                                                @if ($item->digital_access_granted_at)
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.shop.orders.items.digital-access.revoke', [$order, $item]) }}"
                                                        onsubmit="return confirm('Révoquer l\'accès à ce contenu ?');"
                                                    >
                                                        @csrf
                                                        @method('DELETE')

                                                        <button
                                                            type="submit"
                                                            class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-xl border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-600"
                                                        >
                                                            <x-icon name="lock" class="h-3.5 w-3.5" />
                                                            Révoquer l'accès
                                                        </button>
                                                    </form>
                                                @else
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.shop.orders.items.digital-access.grant', [$order, $item]) }}"
                                                    >
                                                        @csrf

                                                        <button
                                                            type="submit"
                                                            @disabled(! $item->digital_file_path)
                                                            class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-700 disabled:cursor-not-allowed disabled:opacity-50"
                                                        >
                                                            <x-icon name="unlock" class="h-3.5 w-3.5" />
                                                            Accorder l'accès
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                @endif

                            </div>
                        @endforeach
                    </div>


                    {{-- TOTAL --}}
                    <div class="border-t border-slate-200 bg-slate-50 px-6 py-6 sm:px-7">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-bold text-slate-600">Total</span>
                            <span class="text-2xl font-black brand-text">
                                {{ number_format($order->total, 2) }} $
                            </span>
                        </div>
                    </div>

                </section>


                {{-- NOTES --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-7 card-soft">

                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                                Notes
                            </p>
                            <h2 class="mt-1 text-lg font-black text-slate-900">
                                Notes internes
                            </h2>
                        </div>

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                            <x-icon name="file-text" class="h-5 w-5" />
                        </div>
                    </div>

                    @if ($order->notes)
                        <div class="mb-4 rounded-2xl bg-slate-50 p-4">
                            <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Note du client
                            </p>
                            <p class="mt-1.5 text-sm leading-relaxed text-slate-700">
                                {{ $order->notes }}
                            </p>
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('admin.shop.orders.notes.update', $order) }}"
                        class="space-y-3"
                    >
                        @csrf
                        @method('PUT')

                        <textarea
                            name="admin_notes"
                            rows="4"
                            placeholder="Ajouter une note administrative…"
                            class="w-full resize-none rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400"
                        >{{ old('admin_notes', $order->admin_notes) }}</textarea>

                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="brand-btn inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold"
                            >
                                <x-icon name="check" class="h-4 w-4" />
                                Enregistrer la note
                            </button>
                        </div>
                    </form>

                </section>

            </div>


            {{-- ======================================================
                 COLONNE LATÉRALE
            ======================================================= --}}
            <div class="space-y-6 lg:sticky lg:top-24 lg:self-start">


                {{-- ACTIONS --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 card-soft">

                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                                Cycle de vie
                            </p>
                            <h2 class="mt-1 text-base font-black text-slate-900">
                                Actions
                            </h2>
                        </div>

                        <div class="brand-bg-soft brand-text flex h-10 w-10 items-center justify-center rounded-xl">
                            <x-icon name="zap" class="h-5 w-5" />
                        </div>
                    </div>


                    <div class="space-y-2.5">

                        {{-- Contacter --}}
                        @if ($order->status === 'pending' && ! $order->contacted_at)
                            <form
                                method="POST"
                                action="{{ route('admin.shop.orders.contacted', $order) }}"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700"
                                >
                                    <x-icon name="phone" class="h-4 w-4" />
                                    Marquer comme contacté
                                </button>
                            </form>
                        @endif

                        {{-- Retirer contact --}}
                        @if ($order->contacted_at && $order->status === 'pending')
                            <form
                                method="POST"
                                action="{{ route('admin.shop.orders.contacted.destroy', $order) }}"
                                onsubmit="return confirm('Retirer le contact avec le client ?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700"
                                >
                                    <x-icon name="phone-off" class="h-4 w-4" />
                                    Retirer le contact
                                </button>
                            </form>
                        @endif

                        {{-- Confirmer --}}
                        @if ($order->status === 'pending' && $order->contacted_at)
                            <form
                                method="POST"
                                action="{{ route('admin.shop.orders.confirm', $order) }}"
                                onsubmit="return confirm('Confirmer cette commande ? L\'inventaire sera mis à jour.');"
                            >
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-bold text-white shadow-sm transition hover:bg-emerald-700"
                                >
                                    <x-icon name="check-circle" class="h-4 w-4" />
                                    Confirmer la commande
                                </button>
                            </form>
                        @endif

                        {{-- Modifier --}}
                        @if (! in_array($order->status, ['cancelled', 'refunded'], true))
                            <a
                                href="{{ route('admin.shop.orders.index') }}"
                                class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-700"
                            >
                                <x-icon name="pencil" class="h-4 w-4" />
                                Modifier la commande
                            </a>
                        @endif

                        {{-- Annuler / Rembourser --}}
                        @if ($order->status === 'paid')
                            <div class="my-1 border-t border-slate-100"></div>

                            <div class="grid grid-cols-2 gap-2.5">
                                <form
                                    method="POST"
                                    action="{{ route('admin.shop.orders.cancel', $order) }}"
                                    onsubmit="return confirm('Annuler cette commande ? L\'inventaire sera restitué.');"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-3 text-sm font-bold text-slate-700"
                                    >
                                        <x-icon name="x-circle" class="h-4 w-4" />
                                        Annuler
                                    </button>
                                </form>

                                <form
                                    method="POST"
                                    action="{{ route('admin.shop.orders.refund', $order) }}"
                                    onsubmit="return confirm('Rembourser cette commande ? L\'inventaire sera restitué.');"
                                >
                                    @csrf
                                    <button
                                        type="submit"
                                        class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-3 text-sm font-bold text-slate-700"
                                    >
                                        <x-icon name="rotate-ccw" class="h-4 w-4" />
                                        Rembourser
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="my-1 border-t border-slate-100"></div>

                        {{-- Supprimer --}}
                        <form
                            method="POST"
                            action="{{ route('admin.shop.orders.destroy', $order) }}"
                            onsubmit="return confirm('Supprimer définitivement cette commande ?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="action-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-bold text-red-600 hover:bg-red-100"
                            >
                                <x-icon name="trash" class="h-4 w-4" />
                                Supprimer la commande
                            </button>
                        </form>

                    </div>

                </section>


                {{-- LIVRAISON --}}
                @if ($order->delivery_status !== 'not_required')
                    <section class="rounded-3xl border border-slate-200 bg-white p-6 card-soft">

                        <div class="mb-5 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                                    Logistique
                                </p>
                                <h2 class="mt-1 text-base font-black text-slate-900">
                                    Livraison
                                </h2>
                            </div>

                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                                <x-icon name="truck" class="h-5 w-5" />
                            </div>
                        </div>

                        @if ($order->status === 'paid')
                            <form
                                method="POST"
                                action="{{ route('admin.shop.orders.delivery-status.update', $order) }}"
                                class="space-y-3"
                            >
                                @csrf
                                @method('PUT')

                                <select
                                    name="delivery_status"
                                    class="block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-800"
                                >
                                    <option value="pending"    @selected($order->delivery_status === 'pending')>En attente</option>
                                    <option value="processing" @selected($order->delivery_status === 'processing')>En préparation</option>
                                    <option value="shipped"    @selected($order->delivery_status === 'shipped')>Expédiée</option>
                                    <option value="delivered"  @selected($order->delivery_status === 'delivered')>Livrée</option>
                                </select>

                                <button
                                    type="submit"
                                    class="brand-btn inline-flex w-full items-center justify-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-bold"
                                >
                                    <x-icon name="check" class="h-4 w-4" />
                                    Mettre à jour
                                </button>
                            </form>
                        @else
                            <p class="rounded-2xl bg-slate-50 px-4 py-3 text-xs text-slate-500">
                                La commande doit être confirmée pour gérer la livraison.
                            </p>
                        @endif

                    </section>
                @endif


                {{-- QR CODE --}}
                <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white card-soft">

                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                                Identité numérique
                            </p>
                            <h2 class="mt-1 text-base font-black text-slate-900">
                                QR Code
                            </h2>
                        </div>

                        <div class="brand-bg-soft brand-text flex h-10 w-10 items-center justify-center rounded-xl">
                            <x-icon name="qr-code" class="h-5 w-5" />
                        </div>
                    </div>


                    <div class="p-6">

                        @if ($qrAvailable)

                            {{-- QR cliquable --}}
                            <button
                                type="button"
                                @click="qrOpen = true"
                                class="group block w-full focus:outline-none"
                                aria-label="Agrandir le QR Code"
                            >
                                <div class="qr-frame mx-auto w-fit rounded-2xl border-2 border-slate-200 p-4 shadow-sm transition group-hover:scale-[1.02] group-hover:border-[#E8631A] group-hover:shadow-xl">
                                    <img
                                        src="{{ route('front.shop.order.qr', $order) }}"
                                        alt="QR Code {{ $order->order_number }}"
                                        class="h-auto w-full max-w-[220px]"
                                    >
                                </div>

                                <p class="mt-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-400 transition group-hover:text-[#E8631A]">
                                    <x-icon name="zoom-in" class="h-3.5 w-3.5" />
                                    Cliquer pour agrandir
                                </p>
                            </button>


                            {{-- Actions --}}
                            <div class="mt-5 grid grid-cols-2 gap-2.5">
                                <a
                                    href="{{ route('front.shop.order.qr', $order) }}"
                                    target="_blank"
                                    class="action-btn inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-2.5 text-xs font-bold text-slate-700"
                                >
                                    <x-icon name="eye" class="h-3.5 w-3.5" />
                                    Afficher
                                </a>

                                <a
                                    href="{{ route('front.shop.order.qr.download', $order) }}"
                                    class="brand-btn inline-flex items-center justify-center gap-2 rounded-2xl px-3 py-2.5 text-xs font-bold"
                                >
                                    <x-icon name="download" class="h-3.5 w-3.5" />
                                    Télécharger
                                </a>
                            </div>

                        @else

                            <div class="rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-5 py-10 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-white text-slate-300 shadow-sm">
                                    <x-icon name="qr-code" class="h-6 w-6" />
                                </div>
                                <p class="mt-3 text-sm font-bold text-slate-700">
                                    QR Code indisponible
                                </p>
                                <p class="mx-auto mt-1 max-w-xs text-xs leading-5 text-slate-400">
                                    Aucun fichier QR n'est actuellement associé à cette commande.
                                </p>
                            </div>

                        @endif

                    </div>

                </section>


                {{-- META --}}
                <section class="rounded-3xl border border-slate-200 bg-white p-6 card-soft">

                    <div class="mb-5">
                        <p class="text-[10px] font-black uppercase tracking-[.15em] text-slate-400">
                            Métadonnées
                        </p>
                        <h2 class="mt-1 text-base font-black text-slate-900">
                            Détails techniques
                        </h2>
                    </div>

                    <div class="space-y-3 text-sm">

                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-slate-500">N° commande</span>
                            <span class="font-mono font-bold text-slate-800">{{ $order->order_number }}</span>
                        </div>

                        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                            <span class="text-slate-500">Créée le</span>
                            <span class="font-semibold text-slate-800">
                                {{ $order->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        @if ($order->confirmed_at)
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <span class="text-slate-500">Confirmée le</span>
                                <span class="font-semibold text-emerald-600">
                                    {{ $order->confirmed_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif

                        @if ($order->contacted_at)
                            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                                <span class="text-slate-500">Contact</span>
                                <span class="font-semibold text-emerald-600">
                                    {{ $order->contacted_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                        @endif

                        <div class="flex items-center justify-between">
                            <span class="text-slate-500">Dernière mise à jour</span>
                            <span class="font-semibold text-slate-800">
                                {{ $order->updated_at->diffForHumans() }}
                            </span>
                        </div>

                    </div>

                </section>

            </div>

        </div>


        {{-- ============================================================
             MODAL QR CODE
        ============================================================= --}}

        @if ($qrAvailable)
            <div
                x-cloak
                x-show="qrOpen"
                x-transition.opacity
                @click.self="qrOpen = false"
                class="fixed inset-0 z-[80] flex items-center justify-center bg-slate-900/60 p-4 backdrop-blur-sm"
                role="dialog"
                aria-modal="true"
            >
                <div
                    x-show="qrOpen"
                    x-transition
                    @click.stop
                    class="pop-in relative w-full max-w-md rounded-3xl bg-white p-6 shadow-2xl sm:p-8"
                >

                    <button
                        type="button"
                        @click="qrOpen = false"
                        class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition hover:bg-slate-200 hover:text-slate-700"
                        aria-label="Fermer"
                    >
                        <x-icon name="x" class="h-4 w-4" />
                    </button>

                    <div class="text-center">
                        <p class="text-xs font-black uppercase tracking-wider brand-text">
                            QR Code
                        </p>
                        <h3 class="mt-1 text-lg font-black text-slate-900">
                            {{ $order->order_number }}
                        </h3>
                    </div>

                    <div class="qr-frame mt-6 flex justify-center rounded-2xl border-2 border-slate-200 p-4">
                        <img
                            src="{{ route('front.shop.order.qr', $order) }}"
                            alt="QR Code {{ $order->order_number }}"
                            class="h-auto w-full max-w-[300px]"
                        >
                    </div>

                    <div class="mt-6 rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-black uppercase tracking-wider text-slate-500">
                            Sauvegarde
                        </p>
                        <ul class="mt-2 space-y-2 text-sm leading-6 text-slate-600">
                            <li class="flex gap-2">
                                <x-icon name="smartphone" class="mt-1 h-4 w-4 shrink-0 brand-text" />
                                <span><strong class="text-slate-700">Mobile :</strong> appui long sur l'image → « Enregistrer ».</span>
                            </li>
                            <li class="flex gap-2">
                                <x-icon name="monitor" class="mt-1 h-4 w-4 shrink-0 brand-text" />
                                <span><strong class="text-slate-700">Ordinateur :</strong> clic droit → « Enregistrer sous ».</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ route('front.shop.order.qr.download', $order) }}"
                            class="brand-btn inline-flex flex-1 items-center justify-center gap-2 rounded-2xl px-5 py-3 text-sm font-bold"
                        >
                            <x-icon name="download" class="h-4 w-4" />
                            Télécharger
                        </a>

                        <button
                            type="button"
                            @click="qrOpen = false"
                            class="inline-flex flex-1 items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:border-[#E8631A]/30 hover:text-[#E8631A]"
                        >
                            Fermer
                        </button>
                    </div>

                </div>
            </div>
        @endif

    </div>

</x-layouts.admin>