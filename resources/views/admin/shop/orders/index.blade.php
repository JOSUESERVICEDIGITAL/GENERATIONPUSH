<x-layouts.admin title="Commandes">

    @php
        $statusMeta = [
            'pending' => [
                'label' => 'En attente',
                'class' => 'bg-amber-50 text-amber-700 border-amber-100',
                'dot' => 'bg-amber-500',
            ],
            'paid' => [
                'label' => 'Payée',
                'class' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                'dot' => 'bg-emerald-500',
            ],
            'cancelled' => [
                'label' => 'Annulée',
                'class' => 'bg-red-50 text-red-700 border-red-100',
                'dot' => 'bg-red-500',
            ],
            'refunded' => [
                'label' => 'Remboursée',
                'class' => 'bg-violet-50 text-violet-700 border-violet-100',
                'dot' => 'bg-violet-500',
            ],
        ];

        $deliveryMeta = [
            'not_required' => [
                'label' => 'Pas de livraison',
                'class' => 'text-slate-500 bg-slate-50',
            ],
            'pending' => [
                'label' => 'En attente',
                'class' => 'text-amber-700 bg-amber-50',
            ],
            'processing' => [
                'label' => 'En préparation',
                'class' => 'text-blue-700 bg-blue-50',
            ],
            'shipped' => [
                'label' => 'Expédiée',
                'class' => 'text-violet-700 bg-violet-50',
            ],
            'delivered' => [
                'label' => 'Livrée',
                'class' => 'text-emerald-700 bg-emerald-50',
            ],
        ];
    @endphp

    <style>
        :root {
            --brand: #E8631A;
            --brand-soft: rgba(232, 99, 26, .07);
            --brand-soft-2: rgba(232, 99, 26, .12);
            --brand-ring: rgba(232, 99, 26, .18);
        }

        [x-cloak] {
            display: none !important;
        }

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

        .stat-card {
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            border-color: rgba(232, 99, 26, .18);
            box-shadow: 0 18px 40px -24px rgba(15, 23, 42, .22);
        }

        .order-row {
            transition: background-color .16s ease;
        }
        .order-row:hover {
            background-color: rgba(248, 250, 252, .92);
        }

        /* Menu dropdown */
        .dd-item {
            transition: background-color .15s ease, color .15s ease;
        }
        .dd-item:hover {
            background-color: var(--brand-soft);
            color: var(--brand);
        }
        .dd-item-danger:hover {
            background-color: rgba(239, 68, 68, .08);
            color: #dc2626;
        }

        .dd-enter {
            animation: ddIn .18s cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes ddIn {
            from { opacity: 0; transform: translateY(-6px) scale(.98); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Le menu doit passer au-dessus de tout */
        .dd-container { position: relative; }
        .dd-menu {
            z-index: 40;
            min-width: 232px;
        }

        /* Si c'est la dernière ligne, on ouvre vers le haut */
        tr:last-child .dd-menu.up { bottom: 100%; top: auto; margin-bottom: 8px; margin-top: 0; }
    </style>

    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,

            selected: [],
            allIds: @js($orders->pluck('id')->values()),
            products: @js($products),

            createItems: [{ product_id: '', quantity: 1 }],
            editItems: [],

            editing: {
                id: null,
                user_id: '',
                status: 'pending'
            },

            openMenu: null,

            productPrice(id) {
                const product = this.products.find(p => Number(p.id) === Number(id));
                if (!product) return 0;
                return product.is_free ? 0 : parseFloat(product.price || 0);
            },

            createTotal() {
                return this.createItems.reduce((sum, item) => {
                    return sum + this.productPrice(item.product_id) * Number(item.quantity || 0);
                }, 0);
            },

            editTotal() {
                return this.editItems.reduce((sum, item) => {
                    return sum + this.productPrice(item.product_id) * Number(item.quantity || 0);
                }, 0);
            },

            money(value) {
                return (Number(value) || 0).toLocaleString('fr-FR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' $';
            },

            toggleMenu(id) {
                this.openMenu = this.openMenu === id ? null : id;
            },

            openEdit(order) {
                this.editing = {
                    id: order.id,
                    user_id: order.user_id,
                    status: order.status
                };

                this.editItems = order.items.map(item => ({
                    product_id: item.product_id,
                    quantity: item.quantity
                }));

                if (!this.editItems.length) {
                    this.editItems = [{ product_id: '', quantity: 1 }];
                }

                this.openMenu = null;
                this.editOpen = true;
            },

            toggleAll(event) {
                this.selected = event.target.checked ? [...this.allIds] : [];
            },

            toggleSelected(id) {
                id = Number(id);
                if (this.selected.includes(id)) {
                    this.selected = this.selected.filter(item => item !== id);
                } else {
                    this.selected.push(id);
                }
            },

            isSelected(id) {
                return this.selected.includes(Number(id));
            }
        }"
        @click.outside="openMenu = null"
        @keydown.escape.window="openMenu = null"
        class="space-y-7"
    >

        {{-- ============================================================
             HEADER
        ============================================================= --}}

        <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">

            <div>
                <div class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                    <span>Admin</span>
                    <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                    <span>Boutique</span>
                    <x-icon name="chevron-right" class="h-3.5 w-3.5" />
                    <span class="brand-text">Commandes</span>
                </div>

                <div class="mt-3 flex items-center gap-3">
                    <h1 class="text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                        Commandes
                    </h1>

                    <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-500">
                        {{ $orders->total() }}
                    </span>
                </div>

                <p class="mt-1.5 text-sm text-slate-500">
                    Gérez les commandes, paiements, livraisons et QR Codes depuis un seul espace.
                </p>
            </div>

            <button
                type="button"
                @click="createOpen = true"
                class="brand-btn inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-bold"
            >
                <x-icon name="plus" class="h-4 w-4" />
                Nouvelle commande
            </button>

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
             STATISTIQUES
        ============================================================= --}}

        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4">

            <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">
                        Commandes
                    </span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <x-icon name="shopping-bag" class="h-4 w-4" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-black text-slate-900">{{ $stats['total'] }}</p>
                <p class="mt-1 text-xs text-slate-500">commandes enregistrées</p>
            </div>

            <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">
                        En attente
                    </span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                        <x-icon name="clock" class="h-4 w-4" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-black text-amber-600">{{ $stats['pending'] }}</p>
                <p class="mt-1 text-xs text-slate-500">commandes à traiter</p>
            </div>

            <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">
                        Payées
                    </span>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <x-icon name="check-circle" class="h-4 w-4" />
                    </span>
                </div>
                <p class="mt-4 text-3xl font-black text-emerald-600">{{ $stats['paid'] }}</p>
                <p class="mt-1 text-xs text-slate-500">paiements confirmés</p>
            </div>

            <div class="stat-card rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">
                        Chiffre d'affaires
                    </span>
                    <span class="brand-bg-soft brand-text flex h-9 w-9 items-center justify-center rounded-xl">
                        <x-icon name="dollar-sign" class="h-4 w-4" />
                    </span>
                </div>
                <p class="brand-text mt-4 text-3xl font-black">
                    {{ number_format($stats['revenue'], 2) }} $
                </p>
                <p class="mt-1 text-xs text-slate-500">commandes payées</p>
            </div>

        </div>


        {{-- ============================================================
             TABLEAU
        ============================================================= --}}

        <div class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_12px_40px_-24px_rgba(15,23,42,.18)]">

            {{-- TOOLBAR --}}

            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                    <div>
                        <h2 class="text-base font-black text-slate-900">
                            Toutes les commandes
                        </h2>
                        <p class="mt-1 text-xs text-slate-500">
                            Utilisez le menu d'actions de chaque ligne pour gérer une commande.
                        </p>
                    </div>

                    <form method="GET" class="flex flex-col gap-2 sm:flex-row">

                        <div class="relative">
                            <x-icon
                                name="search"
                                class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Rechercher une commande..."
                                class="w-full rounded-xl border border-slate-200 bg-white py-2.5 pl-9 pr-4 text-sm text-slate-800 placeholder:text-slate-400 sm:w-64"
                            >
                        </div>

                        <select
                            name="status"
                            onchange="this.form.submit()"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm font-medium text-slate-700"
                        >
                            <option value="">Tous les statuts</option>
                            <option value="pending" @selected($status === 'pending')>En attente</option>
                            <option value="paid" @selected($status === 'paid')>Payée</option>
                            <option value="cancelled" @selected($status === 'cancelled')>Annulée</option>
                            <option value="refunded" @selected($status === 'refunded')>Remboursée</option>
                        </select>

                        @if ($search || $status)
                            <a
                                href="{{ route('admin.shop.orders.index') }}"
                                class="inline-flex items-center justify-center gap-1.5 rounded-xl border border-slate-200 px-3.5 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-50 hover:text-slate-800"
                            >
                                <x-icon name="x" class="h-3.5 w-3.5" />
                                Effacer
                            </a>
                        @endif

                    </form>

                </div>
            </div>


            {{-- BULK ACTIONS --}}

            <div
                x-show="selected.length > 0"
                x-cloak
                x-transition
                class="border-b border-orange-100 bg-orange-50/60 px-5 py-3.5 sm:px-7"
            >
                <div class="flex flex-wrap items-center justify-between gap-3">

                    <div class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <span class="flex h-7 min-w-7 items-center justify-center rounded-lg bg-white px-2 text-xs font-black brand-text shadow-sm">
                            <span x-text="selected.length"></span>
                        </span>
                        <span>commande(s) sélectionnée(s)</span>
                    </div>

                    <form
                        x-ref="bulkForm"
                        method="POST"
                        action="{{ route('admin.shop.orders.bulk-destroy') }}"
                    >
                        @csrf
                        @method('DELETE')

                        <template x-for="id in selected" :key="id">
                            <input type="hidden" name="ids[]" :value="id">
                        </template>

                        <button
                            type="button"
                            @click="if (confirm('Supprimer définitivement les commandes sélectionnées ?')) { $refs.bulkForm.submit() }"
                            class="inline-flex items-center gap-2 rounded-xl border border-red-200 bg-white px-3.5 py-2 text-sm font-bold text-red-600 transition hover:bg-red-50"
                        >
                            <x-icon name="trash" class="h-4 w-4" />
                            Supprimer
                        </button>
                    </form>

                </div>
            </div>


            {{-- TABLE --}}

            <div class="overflow-x-auto">
                <table class="w-full min-w-[1080px] text-sm">

                    <thead class="border-b border-slate-200 bg-slate-50/80">
                        <tr>

                            <th class="w-12 px-5 py-3.5 text-left">
                                <input
                                    type="checkbox"
                                    class="rounded border-slate-300 text-[#E8631A] focus:ring-[#E8631A]"
                                    @change="toggleAll($event)"
                                    :checked="selected.length === allIds.length && allIds.length > 0"
                                >
                            </th>

                            <th class="px-3 py-3.5 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Commande
                            </th>

                            <th class="px-3 py-3.5 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Client
                            </th>

                            <th class="px-3 py-3.5 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Articles
                            </th>

                            <th class="px-3 py-3.5 text-right text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Total
                            </th>

                            <th class="px-3 py-3.5 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Statut
                            </th>

                            <th class="px-3 py-3.5 text-left text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Livraison
                            </th>

                            <th class="px-3 py-3.5 text-center text-[10px] font-black uppercase tracking-wider text-slate-400">
                                QR
                            </th>

                            <th class="px-3 py-3.5 text-right text-[10px] font-black uppercase tracking-wider text-slate-400">
                                Date
                            </th>

                            <th class="w-14 px-5 py-3.5"></th>

                        </tr>
                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse ($orders as $order)

                            @php
                                $qrAvailable =
                                    $order->qr_code_path &&
                                    \Illuminate\Support\Facades\Storage::disk('private')->exists($order->qr_code_path);

                                $customerName = $order->customer_name ?? $order->user?->name ?? 'Client';
                                $customerEmail = $order->customer_email ?? $order->user?->email;
                                $initial = strtoupper(mb_substr(trim($customerName), 0, 1));

                                $statusInfo = $statusMeta[$order->status] ?? [
                                    'label' => $order->statusLabel(),
                                    'class' => 'bg-slate-50 text-slate-600 border-slate-100',
                                    'dot' => 'bg-slate-400',
                                ];

                                $deliveryInfo = $deliveryMeta[$order->delivery_status] ?? [
                                    'label' => $order->deliveryStatusLabel(),
                                    'class' => 'text-slate-600 bg-slate-50',
                                ];

                                $orderData = [
                                    'id' => $order->id,
                                    'user_id' => $order->user_id,
                                    'status' => $order->status,
                                    'items' => $order->items->map(fn ($item) => [
                                        'product_id' => $item->product_id,
                                        'title' => $item->title,
                                        'quantity' => (int) $item->quantity,
                                    ])->values()->all(),
                                ];
                            @endphp

                            <tr class="order-row">

                                {{-- CHECKBOX --}}
                                <td class="px-5 py-4">
                                    <input
                                        type="checkbox"
                                        value="{{ $order->id }}"
                                        class="rounded border-slate-300 text-[#E8631A] focus:ring-[#E8631A]"
                                        :checked="isSelected({{ $order->id }})"
                                        @change="toggleSelected({{ $order->id }})"
                                    >
                                </td>


                                {{-- COMMANDE --}}
                                <td class="px-3 py-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-500">
                                            <x-icon name="shopping-bag" class="h-4 w-4" />
                                        </div>
                                        <div>
                                            <div class="font-mono text-xs font-black text-slate-800">
                                                {{ $order->order_number }}
                                            </div>
                                            <div class="mt-0.5 text-[11px] text-slate-400">
                                                #{{ $order->id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>


                                {{-- CLIENT --}}
                                <td class="px-3 py-4">
                                    <div class="flex min-w-[190px] items-center gap-3">
                                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full brand-bg-soft brand-text text-xs font-black">
                                            {{ $initial ?: '?' }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-bold text-slate-800">
                                                {{ $customerName }}
                                            </p>
                                            @if ($customerEmail)
                                                <p class="mt-0.5 max-w-[190px] truncate text-xs text-slate-400">
                                                    {{ $customerEmail }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>


                                {{-- ARTICLES --}}
                                <td class="px-3 py-4">
                                    <div class="max-w-[230px]">
                                        <p class="truncate font-medium text-slate-700">
                                            {{ $order->items->first()?->title ?? 'Aucun article' }}
                                        </p>

                                        @if ($order->items->count() > 1)
                                            <p class="mt-0.5 text-xs font-semibold brand-text">
                                                +{{ $order->items->count() - 1 }}
                                                {{ $order->items->count() - 1 > 1 ? 'autres articles' : 'autre article' }}
                                            </p>
                                        @else
                                            <p class="mt-0.5 text-xs text-slate-400">
                                                {{ $order->items->sum('quantity') }}
                                                {{ $order->items->sum('quantity') > 1 ? 'unités' : 'unité' }}
                                            </p>
                                        @endif
                                    </div>
                                </td>


                                {{-- TOTAL --}}
                                <td class="px-3 py-4 text-right">
                                    <span class="whitespace-nowrap font-black text-slate-900">
                                        {{ number_format($order->total, 2) }} $
                                    </span>
                                </td>


                                {{-- STATUT --}}
                                <td class="px-3 py-4">
                                    <span class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-[11px] font-bold {{ $statusInfo['class'] }}">
                                        <span class="h-1.5 w-1.5 rounded-full {{ $statusInfo['dot'] }}"></span>
                                        {{ $statusInfo['label'] }}
                                    </span>
                                </td>


                                {{-- LIVRAISON --}}
                                <td class="px-3 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold {{ $deliveryInfo['class'] }}">
                                        {{ $deliveryInfo['label'] }}
                                    </span>
                                </td>


                                {{-- QR --}}
                                <td class="px-3 py-4 text-center">
                                    @if ($qrAvailable)
                                        <a
                                            href="{{ route('front.shop.order.qr.download', $order) }}"
                                            title="Télécharger le QR Code"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg brand-bg-soft brand-text transition hover:scale-105"
                                        >
                                            <x-icon name="qr-code" class="h-4 w-4" />
                                        </a>
                                    @else
                                        <span
                                            title="QR Code indisponible"
                                            class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-slate-50 text-slate-300"
                                        >
                                            <x-icon name="qr-code" class="h-4 w-4" />
                                        </span>
                                    @endif
                                </td>


                                {{-- DATE --}}
                                <td class="whitespace-nowrap px-3 py-4 text-right">
                                    <p class="text-xs font-semibold text-slate-600">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </p>
                                    <p class="mt-0.5 text-[11px] text-slate-400">
                                        {{ $order->created_at->format('H:i') }}
                                    </p>
                                </td>


                                {{-- ACTIONS (3 points) --}}
                                <td class="px-5 py-4 text-right">
                                    <div class="dd-container" @click.stop>

                                        <button
                                            type="button"
                                            @click="toggleMenu({{ $order->id }})"
                                            class="flex h-9 w-9 items-center justify-center rounded-xl text-slate-400 transition-colors duration-200 hover:bg-slate-100 hover:text-slate-700"
                                            aria-label="Actions"
                                        >
                                            <x-icon name="more-vertical" class="h-4 w-4" />
                                        </button>

                                        <div
                                            x-cloak
                                            x-show="openMenu === {{ $order->id }}"
                                            @click.outside="openMenu = null"
                                            class="dd-menu dd-enter absolute right-0 top-full mt-2 overflow-hidden rounded-2xl border border-slate-200 bg-white text-left shadow-xl shadow-slate-900/10"
                                        >

                                            <div class="border-b border-slate-100 px-3 py-2">
                                                <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">
                                                    Actions
                                                </p>
                                            </div>

                                            <div class="p-1.5">

                                                {{-- Voir la fiche --}}
                                                <a
                                                    href="{{ route('admin.shop.orders.show', $order) }}"
                                                    class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700"
                                                >
                                                    <x-icon name="eye" class="h-4 w-4" />
                                                    Voir la fiche
                                                </a>

                                                {{-- Modifier --}}
                                                @if (! in_array($order->status, ['cancelled', 'refunded'], true))
                                                    <button
                                                        type="button"
                                                        @click="openEdit(@js($orderData))"
                                                        class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700"
                                                    >
                                                        <x-icon name="pencil" class="h-4 w-4" />
                                                        Modifier
                                                    </button>
                                                @endif

                                                {{-- Télécharger QR --}}
                                                @if ($qrAvailable)
                                                    <a
                                                        href="{{ route('front.shop.order.qr.download', $order) }}"
                                                        class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700"
                                                    >
                                                        <x-icon name="download" class="h-4 w-4" />
                                                        Télécharger le QR
                                                    </a>
                                                @endif

                                                {{-- Marquer contacté --}}
                                                @if ($order->status === 'pending' && ! $order->contacted_at)
                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.shop.orders.contacted', $order) }}"
                                                    >
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700"
                                                        >
                                                            <x-icon name="phone" class="h-4 w-4" />
                                                            Marquer contacté
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
                                                            class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-semibold text-emerald-600"
                                                        >
                                                            <x-icon name="check-circle" class="h-4 w-4" />
                                                            Confirmer la commande
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Annuler / Rembourser (si payée) --}}
                                                @if ($order->status === 'paid')
                                                    <div class="my-1.5 border-t border-slate-100"></div>

                                                    <form
                                                        method="POST"
                                                        action="{{ route('admin.shop.orders.cancel', $order) }}"
                                                        onsubmit="return confirm('Annuler cette commande ? L\'inventaire sera restitué.');"
                                                    >
                                                        @csrf
                                                        <button
                                                            type="submit"
                                                            class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700"
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
                                                            class="dd-item flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700"
                                                        >
                                                            <x-icon name="rotate-ccw" class="h-4 w-4" />
                                                            Rembourser
                                                        </button>
                                                    </form>
                                                @endif

                                                {{-- Supprimer --}}
                                                <div class="my-1.5 border-t border-slate-100"></div>

                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.shop.orders.destroy', $order) }}"
                                                    onsubmit="return confirm('Supprimer définitivement cette commande ?');"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="dd-item dd-item-danger flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-red-600"
                                                    >
                                                        <x-icon name="trash" class="h-4 w-4" />
                                                        Supprimer
                                                    </button>
                                                </form>

                                            </div>

                                        </div>

                                    </div>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="10" class="px-5 py-20 text-center">
                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl brand-bg-soft brand-text">
                                        <x-icon name="shopping-bag" class="h-6 w-6" />
                                    </div>
                                    <p class="mt-4 font-bold text-slate-800">Aucune commande trouvée</p>
                                    <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                                        Modifiez vos filtres ou créez votre première commande.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>
            </div>


            {{-- PAGINATION --}}

            @if ($orders->hasPages())
                <div class="border-t border-slate-100 px-5 py-5 sm:px-7">
                    {{ $orders->links() }}
                </div>
            @endif

        </div>


        {{-- ============================================================
             MODAL CRÉATION
        ============================================================= --}}

        <x-modal open="createOpen" title="Créer une commande">

            <form
                method="POST"
                action="{{ route('admin.shop.orders.store') }}"
                class="space-y-5"
            >
                @csrf

                <div class="flex items-start gap-3 rounded-2xl brand-bg-soft p-4">
                    <x-icon name="info" class="mt-0.5 h-4 w-4 shrink-0 brand-text" />
                    <p class="text-xs leading-5 text-slate-600">
                        Le numéro de commande sera généré automatiquement.
                    </p>
                </div>

                <div>
                    <x-input-label for="create_user_id" value="Client" />
                    <select
                        id="create_user_id"
                        name="user_id"
                        required
                        class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm"
                    >
                        <option value="">— Choisir un client —</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} — {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <x-input-label value="Articles" />
                        <button
                            type="button"
                            @click="createItems.push({ product_id: '', quantity: 1 })"
                            class="inline-flex items-center gap-1 text-xs font-bold brand-text"
                        >
                            <x-icon name="plus" class="h-3.5 w-3.5" />
                            Ajouter
                        </button>
                    </div>

                    <template x-for="(item, index) in createItems" :key="index">
                        <div class="flex gap-2">
                            <select
                                :name="'items[' + index + '][product_id]'"
                                x-model="item.product_id"
                                required
                                class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm"
                            >
                                <option value="">— Produit —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->title }}
                                        — {{ $product->is_free ? 'Gratuit' : number_format($product->price, 2) . ' $' }}
                                    </option>
                                @endforeach
                            </select>

                            <input
                                type="number"
                                min="1"
                                :name="'items[' + index + '][quantity]'"
                                x-model="item.quantity"
                                class="w-20 rounded-xl border border-slate-200 px-3 py-2.5 text-sm"
                            >

                            <button
                                type="button"
                                x-show="createItems.length > 1"
                                @click="createItems.splice(index, 1)"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-400 hover:bg-red-50 hover:text-red-600"
                            >
                                <x-icon name="x" class="h-4 w-4" />
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3.5">
                    <span class="text-sm font-medium text-slate-500">Total estimé</span>
                    <span class="text-lg font-black brand-text" x-text="money(createTotal())"></span>
                </div>

                <div>
                    <x-input-label for="create_status" value="Statut initial" />
                    <select
                        id="create_status"
                        name="status"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm"
                    >
                        <option value="pending">En attente</option>
                        <option value="paid">Payée</option>
                        <option value="cancelled">Annulée</option>
                        <option value="refunded">Remboursée</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        @click="createOpen = false"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="brand-btn inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold"
                    >
                        <x-icon name="check" class="h-4 w-4" />
                        Créer la commande
                    </button>
                </div>
            </form>

        </x-modal>


        {{-- ============================================================
             MODAL ÉDITION
        ============================================================= --}}

        <x-modal open="editOpen" title="Modifier la commande">

            <form
                method="POST"
                :action="`{{ url('admin/shop/orders') }}/${editing.id}`"
                class="space-y-5"
            >
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="edit_user_id" value="Client" />
                    <select
                        id="edit_user_id"
                        name="user_id"
                        x-model="editing.user_id"
                        required
                        class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm"
                    >
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}">
                                {{ $u->name }} — {{ $u->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <x-input-label value="Articles" />
                        <button
                            type="button"
                            @click="editItems.push({ product_id: '', quantity: 1 })"
                            class="inline-flex items-center gap-1 text-xs font-bold brand-text"
                        >
                            <x-icon name="plus" class="h-3.5 w-3.5" />
                            Ajouter
                        </button>
                    </div>

                    <template x-for="(item, index) in editItems" :key="index">
                        <div class="flex gap-2">
                            <select
                                :name="'items[' + index + '][product_id]'"
                                x-model="item.product_id"
                                required
                                class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-sm"
                            >
                                <option value="">— Produit —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->title }}
                                        — {{ $product->is_free ? 'Gratuit' : number_format($product->price, 2) . ' $' }}
                                    </option>
                                @endforeach
                            </select>

                            <input
                                type="number"
                                min="1"
                                :name="'items[' + index + '][quantity]'"
                                x-model="item.quantity"
                                class="w-20 rounded-xl border border-slate-200 px-3 py-2.5 text-sm"
                            >

                            <button
                                type="button"
                                x-show="editItems.length > 1"
                                @click="editItems.splice(index, 1)"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-slate-400 hover:bg-red-50 hover:text-red-600"
                            >
                                <x-icon name="x" class="h-4 w-4" />
                            </button>
                        </div>
                    </template>
                </div>

                <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3.5">
                    <span class="text-sm font-medium text-slate-500">Total estimé</span>
                    <span class="text-lg font-black brand-text" x-text="money(editTotal())"></span>
                </div>

                <div>
                    <x-input-label for="edit_status" value="Statut" />
                    <select
                        id="edit_status"
                        name="status"
                        x-model="editing.status"
                        class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm"
                    >
                        <option value="pending">En attente</option>
                        <option value="paid">Payée</option>
                        <option value="cancelled">Annulée</option>
                        <option value="refunded">Remboursée</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        @click="editOpen = false"
                        class="rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-bold text-slate-700"
                    >
                        Annuler
                    </button>

                    <button
                        type="submit"
                        class="brand-btn inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-bold"
                    >
                        <x-icon name="check" class="h-4 w-4" />
                        Enregistrer
                    </button>
                </div>
            </form>

        </x-modal>

    </div>

</x-layouts.admin>
