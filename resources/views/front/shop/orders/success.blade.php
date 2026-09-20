<x-layouts.public title="Commande enregistrée — Generation PUSH">

    <style>
        :root {
            --brand: #E8631A;
            --brand-soft: rgba(232, 99, 26, 0.08);
            --brand-ring: rgba(232, 99, 26, 0.25);
        }

        .brand-text { color: var(--brand); }
        .brand-bg { background-color: var(--brand); }
        .brand-bg-soft { background-color: var(--brand-soft); }

        .brand-btn {
            background-color: var(--brand);
            box-shadow: 0 10px 30px -10px rgba(232, 99, 26, .55);
            transition: transform .25s ease, box-shadow .25s ease, opacity .25s ease;
        }
        .brand-btn:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 16px 40px -12px rgba(232, 99, 26, .65);
        }
        .brand-btn:active { transform: translateY(0) scale(.99); }

        .soft-card {
            transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
        }
        .soft-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px -22px rgba(15, 23, 42, .18);
            border-color: rgba(232, 99, 26, .25);
        }

        .qr-frame {
            transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
            cursor: zoom-in;
        }
        .qr-frame:hover {
            transform: scale(1.02);
            border-color: var(--brand);
            box-shadow: 0 20px 50px -22px rgba(232, 99, 26, .55);
        }

        .fade-in { animation: fadeIn .5s ease both; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .pop-in { animation: popIn .35s cubic-bezier(.22,1,.36,1) both; }
        @keyframes popIn {
            from { opacity: 0; transform: scale(.94); }
            to   { opacity: 1; transform: scale(1); }
        }

        [x-cloak] { display: none !important; }
    </style>

    <section class="relative overflow-hidden bg-slate-50 pt-28 md:pt-32 lg:pt-36 pb-20 md:pb-24">

        {{-- Décor de fond --}}
        <div class="pointer-events-none absolute -top-32 -right-24 h-80 w-80 rounded-full bg-[#E8631A]/10 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-32 -left-24 h-80 w-80 rounded-full bg-[#E8631A]/5 blur-3xl"></div>

        <div class="relative mx-auto max-w-6xl px-5 sm:px-8 lg:px-10">

            {{-- ==========================================================
                HERO SUCCÈS
            =========================================================== --}}
            <div class="mx-auto max-w-3xl text-center fade-in">

                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-[#E8631A]/10">
                    <div class="flex h-14 w-14 items-center justify-center rounded-full brand-bg text-white shadow-lg shadow-[#E8631A]/30">
                        <x-icon name="check" class="h-7 w-7" />
                    </div>
                </div>

                <p class="mt-6 text-xs sm:text-sm font-bold uppercase tracking-[0.2em] brand-text">
                    Demande enregistrée
                </p>

                <h1 class="mt-3 text-3xl sm:text-4xl font-black tracking-tight text-slate-900">
                    Merci pour ta commande !
                </h1>

                <p class="mx-auto mt-4 max-w-2xl text-base sm:text-lg leading-7 text-slate-600">
                    Ta demande a bien été enregistrée. Notre équipe Generation PUSH
                    va vérifier les informations et te contacter pour finaliser la commande.
                </p>

                <div class="mt-7 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm">
                    <x-icon name="shopping-bag" class="h-4 w-4 brand-text" />
                    Commande
                    <span class="text-slate-900">#{{ $order->order_number }}</span>
                </div>

            </div>


            {{-- ==========================================================
                CONTENU PRINCIPAL
            =========================================================== --}}
            <div class="mt-12 md:mt-14 grid gap-6 lg:gap-8 lg:grid-cols-3">

                {{-- ======================================================
                    COLONNE PRINCIPALE
                ======================================================= --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Produits --}}
                    <div class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)]">

                        <div class="border-b border-slate-100 px-6 py-6 sm:px-8">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-lg font-bold text-slate-900">
                                        Détails de la commande
                                    </h2>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $order->items->count() }}
                                        {{ $order->items->count() > 1 ? 'articles' : 'article' }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center rounded-full bg-amber-50 px-3.5 py-1.5 text-xs font-bold text-amber-700 border border-amber-100">
                                    {{ $order->statusLabel() }}
                                </span>
                            </div>
                        </div>


                        <div class="divide-y divide-slate-100">
                            @foreach ($order->items as $item)
                                <div class="p-6 sm:p-7">
                                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                                        <div class="flex min-w-0 gap-4">
                                            <div class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-2xl bg-slate-100">
                                                @if ($item->product?->imageUrl())
                                                    <img
                                                        src="{{ $item->product->imageUrl() }}"
                                                        alt="{{ $item->title }}"
                                                        class="h-full w-full object-cover"
                                                    >
                                                @else
                                                    <x-icon name="shopping-bag" class="h-7 w-7 text-slate-400" />
                                                @endif
                                            </div>

                                            <div class="min-w-0">
                                                <h3 class="truncate font-bold text-slate-900">
                                                    {{ $item->title }}
                                                </h3>

                                                <div class="mt-2 flex flex-wrap items-center gap-2">
                                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                                                        {{ $item->fulfillmentTypeLabel() }}
                                                    </span>
                                                    <span class="text-xs text-slate-500">
                                                        × {{ $item->quantity }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="shrink-0 text-left sm:text-right">
                                            <p class="font-bold text-slate-900">
                                                {{ number_format((float) $item->subtotal(), 2, ',', ' ') }} $
                                            </p>
                                            @if ($item->quantity > 1)
                                                <p class="mt-1 text-xs text-slate-500">
                                                    {{ number_format((float) $item->price, 2, ',', ' ') }} $ / unité
                                                </p>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>


                        {{-- Total --}}
                        <div class="border-t border-slate-200 bg-slate-50/70 px-6 py-6 sm:px-8">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-base font-semibold text-slate-600">Total</span>
                                <span class="text-2xl sm:text-3xl font-black text-slate-900">
                                    {{ number_format((float) $order->total, 2, ',', ' ') }} $
                                </span>
                            </div>
                        </div>

                    </div>


                    {{-- ==================================================
                        INFORMATIONS CLIENT
                    =================================================== --}}
                    <div class="rounded-3xl border border-slate-100 bg-white shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)]">

                        <div class="border-b border-slate-100 px-6 py-6 sm:px-8">
                            <h2 class="text-lg font-bold text-slate-900">
                                Informations de la commande
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">
                                Informations enregistrées au moment de la commande.
                            </p>
                        </div>


                        <div class="grid gap-6 p-6 sm:grid-cols-2 sm:p-8">

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Nom</p>
                                <p class="mt-1.5 font-semibold text-slate-800">{{ $order->customer_name }}</p>
                            </div>

                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Email</p>
                                <p class="mt-1.5 break-all font-semibold text-slate-800">{{ $order->customer_email }}</p>
                            </div>

                            @if ($order->customer_phone)
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Téléphone</p>
                                    <p class="mt-1.5 font-semibold text-slate-800">{{ $order->customer_phone }}</p>
                                </div>
                            @endif

                            @if ($order->customer_country)
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pays</p>
                                    <p class="mt-1.5 font-semibold text-slate-800">{{ $order->customer_country }}</p>
                                </div>
                            @endif

                            @if ($order->customer_city)
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Ville</p>
                                    <p class="mt-1.5 font-semibold text-slate-800">{{ $order->customer_city }}</p>
                                </div>
                            @endif

                            @if ($order->customer_address)
                                <div class="sm:col-span-2">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">
                                        Adresse de livraison
                                    </p>
                                    <div class="mt-2.5 flex gap-3 rounded-2xl bg-slate-50 p-4">
                                        <x-icon name="map-pin" class="mt-0.5 h-5 w-5 shrink-0 brand-text" />
                                        <p class="text-sm font-medium leading-6 text-slate-700">
                                            {{ $order->customer_address }}
                                        </p>
                                    </div>
                                </div>
                            @endif

                            @if ($order->notes)
                                <div class="sm:col-span-2">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Note</p>
                                    <p class="mt-2.5 rounded-2xl bg-slate-50 p-4 text-sm leading-6 text-slate-600">
                                        {{ $order->notes }}
                                    </p>
                                </div>
                            @endif

                        </div>

                    </div>

                </div>


                {{-- ======================================================
                    SIDEBAR
                ======================================================= --}}
                <div class="space-y-6">

                    {{-- ==================================================
                        QR CODE (cliquable + modal)
                    =================================================== --}}
                    <div
                        x-data="{ open: false }"
                        class="overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)] soft-card"
                    >

                        <div class="border-b border-slate-100 px-6 py-6">
                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl brand-bg-soft">
                                    <x-icon name="qr-code" class="h-5 w-5 brand-text" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">QR Code</p>
                                    <h2 class="mt-0.5 font-bold text-slate-900">Ton récapitulatif</h2>
                                </div>
                            </div>
                        </div>


                        <div class="p-6">

                            {{-- QR cliquable --}}
                            <button
                                type="button"
                                @click="open = true"
                                class="group block w-full focus:outline-none"
                                aria-label="Agrandir le QR Code"
                            >
                                <div class="qr-frame mx-auto w-fit rounded-2xl border-2 border-slate-200 bg-white p-4 shadow-sm">
                                    <img
                                        src="{{ route('front.shop.order.qr', $order) }}"
                                        alt="QR Code de la commande {{ $order->order_number }}"
                                        class="h-auto w-full max-w-[240px] pointer-events-none select-none"
                                    >
                                </div>

                                <p class="mt-3 flex items-center justify-center gap-1.5 text-xs font-semibold text-slate-400 transition-colors duration-200 group-hover:text-[#E8631A]">
                                    <x-icon name="zoom-in" class="h-3.5 w-3.5" />
                                    Cliquer pour agrandir
                                </p>
                            </button>


                            {{-- Info --}}
                            <div class="mt-5 rounded-2xl bg-slate-50 p-4">
                                <div class="flex gap-3">
                                    <x-icon name="info" class="mt-0.5 h-5 w-5 shrink-0 brand-text" />
                                    <p class="text-sm leading-6 text-slate-600">
                                        Ce QR Code contient le récapitulatif de ta commande.
                                        Il peut être scanné avec un lecteur QR compatible,
                                        <strong class="font-bold text-slate-700">même sans connexion Internet</strong>.
                                    </p>
                                </div>
                            </div>


                            {{-- Télécharger direct --}}
                            <a
                                href="{{ route('front.shop.order.qr.download', $order) }}"
                                class="brand-btn mt-4 inline-flex w-full items-center justify-center gap-2 rounded-2xl px-5 py-3.5 text-sm font-bold text-white"
                            >
                                <x-icon name="download" class="h-5 w-5" />
                                Télécharger mon QR Code
                            </a>

                            <p class="mt-3 text-center text-xs leading-5 text-slate-400">
                                Conserve ce QR Code avec tes informations de commande.
                            </p>

                        </div>


                        {{-- ==========================================
                            MODAL QR
                        ========================================== --}}
                        <div
                            x-cloak
                            x-show="open"
                            x-transition.opacity
                            @keydown.escape.window="open = false"
                            @click.self="open = false"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
                            role="dialog"
                            aria-modal="true"
                        >
                            <div
                                x-show="open"
                                x-transition
                                class="pop-in relative w-full max-w-md rounded-3xl bg-white p-6 sm:p-8 shadow-2xl"
                                @click.stop
                            >

                                {{-- Fermer --}}
                                <button
                                    type="button"
                                    @click="open = false"
                                    class="absolute right-4 top-4 flex h-9 w-9 items-center justify-center rounded-full bg-slate-100 text-slate-500 transition-colors duration-200 hover:bg-slate-200 hover:text-slate-700"
                                    aria-label="Fermer"
                                >
                                    <x-icon name="x" class="h-4 w-4" />
                                </button>

                                <div class="text-center">
                                    <p class="text-xs font-bold uppercase tracking-wider brand-text">
                                        QR Code
                                    </p>
                                    <h3 class="mt-1 text-lg font-bold text-slate-900">
                                        Récapitulatif de la commande
                                    </h3>
                                    <p class="mt-1 text-sm text-slate-500">
                                        #{{ $order->order_number }}
                                    </p>
                                </div>

                                <div class="mt-6 flex justify-center">
                                    <div class="rounded-2xl border-2 border-slate-200 bg-white p-4 shadow-sm">
                                        <img
                                            src="{{ route('front.shop.order.qr', $order) }}"
                                            alt="QR Code de la commande {{ $order->order_number }}"
                                            class="h-auto w-full max-w-[280px]"
                                        >
                                    </div>
                                </div>

                                <div class="mt-6 rounded-2xl bg-slate-50 p-4 text-left">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                                        Comment sauvegarder ?
                                    </p>
                                    <ul class="mt-2 space-y-2 text-sm leading-6 text-slate-600">
                                        <li class="flex gap-2">
                                            <x-icon name="smartphone" class="mt-1 h-4 w-4 shrink-0 brand-text" />
                                            <span><strong class="text-slate-700">Mobile :</strong> appui long sur l'image → « Enregistrer l'image ».</span>
                                        </li>
                                        <li class="flex gap-2">
                                            <x-icon name="monitor" class="mt-1 h-4 w-4 shrink-0 brand-text" />
                                            <span><strong class="text-slate-700">Ordinateur :</strong> clic droit sur l'image → « Enregistrer sous ».</span>
                                        </li>
                                        <li class="flex gap-2">
                                            <x-icon name="download" class="mt-1 h-4 w-4 shrink-0 brand-text" />
                                            <span>Ou utilise le bouton ci-dessous.</span>
                                        </li>
                                    </ul>
                                </div>

                                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                                    <a
                                        href="{{ route('front.shop.order.qr.download', $order) }}"
                                        class="brand-btn inline-flex flex-1 items-center justify-center gap-2 rounded-2xl px-5 py-3.5 text-sm font-bold text-white"
                                    >
                                        <x-icon name="download" class="h-5 w-5" />
                                        Télécharger
                                    </a>

                                    <button
                                        type="button"
                                        @click="open = false"
                                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-bold text-slate-700 transition-all duration-200 hover:border-[#E8631A]/30 hover:text-[#E8631A]"
                                    >
                                        Fermer
                                    </button>
                                </div>

                            </div>
                        </div>

                    </div>


                    {{-- ==================================================
                        STATUT
                    =================================================== --}}
                    <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)] soft-card">

                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl brand-bg-soft">
                                <x-icon name="clock" class="h-5 w-5 brand-text" />
                            </div>
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Statut</p>
                                <p class="mt-0.5 font-bold text-slate-900">{{ $order->statusLabel() }}</p>
                            </div>
                        </div>

                        <div class="mt-5 rounded-2xl bg-amber-50 p-4 border border-amber-100">
                            <p class="text-sm leading-6 text-amber-800">
                                Ta demande est actuellement en cours de traitement.
                                Notre équipe te contactera pour confirmer les modalités
                                de paiement, de livraison ou d'accès.
                            </p>
                        </div>

                    </div>


                    {{-- ==================================================
                        LIVRAISON
                    =================================================== --}}
                    @if ($order->delivery_status !== 'not_required')
                        <div class="rounded-3xl border border-slate-100 bg-white p-6 shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)] soft-card">

                            <div class="flex items-center gap-3">
                                <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100">
                                    <x-icon name="package" class="h-5 w-5 text-slate-700" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Livraison</p>
                                    <p class="mt-0.5 font-bold text-slate-900">{{ $order->deliveryStatusLabel() }}</p>
                                </div>
                            </div>

                            <p class="mt-4 text-sm leading-6 text-slate-500">
                                Les informations de livraison sont celles enregistrées
                                au moment de la commande.
                            </p>

                        </div>
                    @endif


                    {{-- ==================================================
                        MESSAGE GENERATION PUSH
                    =================================================== --}}
                    <div class="relative overflow-hidden rounded-3xl brand-bg p-6 text-white shadow-xl shadow-[#E8631A]/25">

                        <div class="pointer-events-none absolute -top-10 -right-10 h-40 w-40 rounded-full bg-white/10 blur-2xl"></div>

                        <div class="relative">
                            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/15">
                                <x-icon name="message-circle" class="h-5 w-5" />
                            </div>

                            <h3 class="mt-5 text-lg font-black">
                                Une équipe à ton écoute
                            </h3>

                            <p class="mt-2 text-sm leading-6 text-white/90">
                                Une personne de l'équipe Generation PUSH reviendra vers toi
                                pour finaliser ta commande et répondre à tes éventuelles questions.
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- ==========================================================
                ACTIONS
            =========================================================== --}}
            <div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">

                <a
                    href="{{ route('front.shop.index') }}"
                    class="brand-btn inline-flex items-center justify-center gap-2 rounded-2xl px-7 py-3.5 text-sm font-bold text-white"
                >
                    <x-icon name="shopping-bag" class="h-5 w-5" />
                    Retour à la boutique
                </a>

                @auth
                    <a
                        href="{{ route('profile.edit') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-200 bg-white px-7 py-3.5 text-sm font-bold text-slate-700 shadow-sm transition-all duration-200 hover:border-[#E8631A]/30 hover:text-[#E8631A] hover:-translate-y-0.5"
                    >
                        <x-icon name="user" class="h-5 w-5" />
                        Mon profil
                    </a>
                @endauth

            </div>

        </div>

    </section>

</x-layouts.public>
