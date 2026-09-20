<x-layouts.public :title="'Commander — ' . $product->title . ' — Generation PUSH'">

    {{-- Couleur principale injectée --}}
    <style>
        :root {
            --brand: #E8631A;
            --brand-soft: rgba(232, 99, 26, 0.08);
            --brand-ring: rgba(232, 99, 26, 0.25);
        }

        .brand-text { color: var(--brand); }
        .brand-bg { background-color: var(--brand); }
        .brand-bg-soft { background-color: var(--brand-soft); }
        .brand-border { border-color: var(--brand); }

        .brand-btn {
            background-color: var(--brand);
            box-shadow: 0 10px 30px -10px rgba(232, 99, 26, 0.55);
            transition: transform .25s ease, box-shadow .25s ease, opacity .25s ease;
        }
        .brand-btn:hover {
            transform: translateY(-1px) scale(1.01);
            box-shadow: 0 16px 40px -12px rgba(232, 99, 26, 0.65);
        }
        .brand-btn:active { transform: translateY(0) scale(.99); }

        /* Focus doux partout */
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: var(--brand) !important;
            box-shadow: 0 0 0 4px var(--brand-ring) !important;
        }

        /* Radio cards */
        .radio-card {
            transition: border-color .25s ease, background-color .25s ease, box-shadow .25s ease, transform .2s ease;
        }
        .radio-card:hover {
            border-color: rgba(232, 99, 26, .45);
            transform: translateY(-2px);
        }
        .peer:checked + .radio-card {
            border-color: var(--brand);
            background-color: var(--brand-soft);
            box-shadow: 0 10px 30px -14px rgba(232, 99, 26, .5);
        }
        .peer:checked + .radio-card .radio-dot {
            border-color: var(--brand);
            background-color: var(--brand);
        }
        .peer:checked + .radio-card .radio-dot-inner { opacity: 1; }
        .peer:checked + .radio-card .radio-icon {
            background-color: rgba(232, 99, 26, .12);
            color: var(--brand);
        }
    </style>

    {{-- Espace supérieur pour ne pas coller au header --}}
    <section class="bg-slate-50 pt-28 md:pt-32 lg:pt-36 pb-20 md:pb-24">

        <div class="max-w-6xl mx-auto px-5 sm:px-8 lg:px-10">

            {{-- Retour --}}
            <div class="mb-8 md:mb-10">
                <a
                    href="{{ route('front.shop.show', $product->slug) }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 hover:text-[#E8631A] transition-colors duration-200"
                >
                    <x-icon name="arrow-left" class="w-4 h-4" />
                    Retour au produit
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-10">

                {{-- =========================================================
                    FORMULAIRE
                ========================================================== --}}
                <div class="lg:col-span-2">

                    <div class="bg-white rounded-3xl shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)] border border-slate-100 overflow-hidden">

                        {{-- En-tête --}}
                        <div class="px-6 py-8 sm:px-10 sm:py-10 border-b border-slate-100">
                            <div class="flex items-start gap-5">

                                <div class="w-14 h-14 rounded-2xl brand-bg-soft brand-text flex items-center justify-center shrink-0">
                                    <x-icon name="shopping-bag" class="w-6 h-6" />
                                </div>

                                <div class="min-w-0">
                                    <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 leading-tight">
                                        Finaliser votre commande
                                    </h1>

                                    <p class="mt-2 text-slate-500 text-sm sm:text-base">
                                        Vérifiez vos informations et choisissez la version souhaitée.
                                    </p>
                                </div>

                            </div>
                        </div>

                        {{-- Messages --}}
                        @if (session('error'))
                            <div class="mx-6 sm:mx-10 mt-8 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-sm text-red-700">
                                {{ session('error') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mx-6 sm:mx-10 mt-8 rounded-2xl bg-red-50 border border-red-200 px-5 py-5 text-sm text-red-700">
                                <p class="font-semibold mb-2">
                                    Veuillez corriger les erreurs suivantes :
                                </p>
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form
                            method="POST"
                            action="{{ route('front.shop.order.store', $product->slug) }}"
                            class="px-6 py-8 sm:px-10 sm:py-10 space-y-10"
                        >
                            @csrf

                            {{-- =================================================
                                INFORMATIONS CLIENT
                            ================================================== --}}
                            <div>
                                <div class="flex items-center gap-4 mb-7">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                        <x-icon name="user" class="w-5 h-5 text-slate-500" />
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-900">
                                            Vos informations
                                        </h2>
                                        <p class="text-sm text-slate-500">
                                            Ces informations proviennent de votre profil.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                    {{-- Nom --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Nom complet
                                        </label>
                                        <div class="relative">
                                            <input
                                                type="text"
                                                value="{{ $user->name }}"
                                                readonly
                                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 pl-4 pr-11 py-3.5 text-slate-700 cursor-not-allowed transition-all duration-200"
                                            >
                                            <x-icon name="lock" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                        </div>
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Adresse e-mail
                                        </label>
                                        <div class="relative">
                                            <input
                                                type="email"
                                                value="{{ $user->email }}"
                                                readonly
                                                class="w-full rounded-2xl border border-slate-200 bg-slate-50 pl-4 pr-11 py-3.5 text-slate-700 cursor-not-allowed transition-all duration-200"
                                            >
                                            <x-icon name="lock" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
                                        </div>
                                    </div>

                                    {{-- Téléphone --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Téléphone
                                        </label>
                                        <input
                                            type="text"
                                            value="{{ $user->phone }}"
                                            readonly
                                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-700 cursor-not-allowed transition-all duration-200"
                                        >
                                    </div>

                                    {{-- Pays --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Pays
                                        </label>
                                        <input
                                            type="text"
                                            value="{{ $user->country }}"
                                            readonly
                                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-700 cursor-not-allowed transition-all duration-200"
                                        >
                                    </div>

                                    {{-- Ville --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Ville
                                        </label>
                                        <input
                                            type="text"
                                            value="{{ $user->city }}"
                                            readonly
                                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-700 cursor-not-allowed transition-all duration-200"
                                        >
                                    </div>

                                    {{-- Adresse --}}
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                                            Adresse de livraison
                                        </label>
                                        <input
                                            type="text"
                                            value="{{ $user->address }}"
                                            readonly
                                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3.5 text-slate-700 cursor-not-allowed transition-all duration-200"
                                        >
                                    </div>

                                </div>

                                <div class="mt-5">
                                    <a
                                        href="{{ route('profile.edit') }}"
                                        class="inline-flex items-center gap-2 text-sm font-semibold brand-text hover:opacity-80 transition-opacity duration-200"
                                    >
                                        <x-icon name="pencil" class="w-4 h-4" />
                                        Modifier mes informations
                                    </a>
                                </div>
                            </div>

                            {{-- =================================================
                                VERSION
                            ================================================== --}}
                            <div class="pt-10 border-t border-slate-100">

                                <div class="flex items-center gap-4 mb-7">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center shrink-0">
                                        <x-icon name="layers" class="w-5 h-5 text-slate-500" />
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-bold text-slate-900">
                                            Choisissez votre version
                                        </h2>
                                        <p class="text-sm text-slate-500">
                                            Sélectionnez le format que vous souhaitez recevoir.
                                        </p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    @if (in_array('physical', $fulfillmentOptions, true))
                                        <label class="relative cursor-pointer block">
                                            <input
                                                type="radio"
                                                name="fulfillment_type"
                                                value="physical"
                                                class="peer sr-only"
                                                {{ old('fulfillment_type', 'physical') === 'physical' ? 'checked' : '' }}
                                            >

                                            <div class="radio-card rounded-2xl border-2 border-slate-200 p-5 sm:p-6 bg-white">
                                                <div class="flex items-start gap-4">

                                                    <div class="radio-icon w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 transition-all duration-200">
                                                        <x-icon name="package" class="w-5 h-5" />
                                                    </div>

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <h3 class="font-bold text-slate-900">
                                                                Version physique
                                                            </h3>

                                                            <div class="radio-dot w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all duration-200">
                                                                <div class="radio-dot-inner w-2 h-2 rounded-full bg-white opacity-0 transition-opacity duration-200"></div>
                                                            </div>
                                                        </div>

                                                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                                                            Vous recevrez le produit à l'adresse indiquée dans votre profil.
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                        </label>
                                    @endif

                                    @if (in_array('digital', $fulfillmentOptions, true))
                                        <label class="relative cursor-pointer block">
                                            <input
                                                type="radio"
                                                name="fulfillment_type"
                                                value="digital"
                                                class="peer sr-only"
                                                {{ old('fulfillment_type', !in_array('physical', $fulfillmentOptions, true) ? 'digital' : null) === 'digital' ? 'checked' : '' }}
                                            >

                                            <div class="radio-card rounded-2xl border-2 border-slate-200 p-5 sm:p-6 bg-white">
                                                <div class="flex items-start gap-4">

                                                    <div class="radio-icon w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0 transition-all duration-200">
                                                        <x-icon name="download" class="w-5 h-5" />
                                                    </div>

                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between gap-3">
                                                            <h3 class="font-bold text-slate-900">
                                                                Version numérique
                                                            </h3>

                                                            <div class="radio-dot w-5 h-5 rounded-full border-2 border-slate-300 flex items-center justify-center shrink-0 transition-all duration-200">
                                                                <div class="radio-dot-inner w-2 h-2 rounded-full bg-white opacity-0 transition-opacity duration-200"></div>
                                                            </div>
                                                        </div>

                                                        <p class="mt-2 text-sm text-slate-500 leading-relaxed">
                                                            Vous recevrez votre contenu au format numérique.
                                                        </p>
                                                    </div>

                                                </div>
                                            </div>
                                        </label>
                                    @endif

                                </div>

                                @error('fulfillment_type')
                                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- =================================================
                                QUANTITÉ
                            ================================================== --}}
                            <div class="pt-10 border-t border-slate-100">

                                <label for="quantity" class="block text-sm font-semibold text-slate-700 mb-3">
                                    Quantité
                                </label>

                                <div class="flex items-center gap-4 flex-wrap">
                                    <input
                                        type="number"
                                        name="quantity"
                                        id="quantity"
                                        min="1"
                                        max="99"
                                        value="{{ old('quantity', 1) }}"
                                        class="w-32 rounded-2xl border border-slate-200 px-4 py-3.5 text-slate-900 transition-all duration-200"
                                    >

                                    @if ($product->stock !== null)
                                        <span class="text-sm text-slate-500">
                                            {{ $product->stock }} disponible(s)
                                        </span>
                                    @endif
                                </div>

                                @error('quantity')
                                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- =================================================
                                NOTES
                            ================================================== --}}
                            <div class="pt-10 border-t border-slate-100">

                                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-3">
                                    Note ou précision
                                    <span class="font-normal text-slate-400">(facultatif)</span>
                                </label>

                                <textarea
                                    name="notes"
                                    id="notes"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="Une précision concernant votre commande..."
                                    class="w-full rounded-2xl border border-slate-200 px-4 py-3.5 text-slate-900 placeholder-slate-400 focus:border-[#E8631A] focus:ring-0 resize-none transition-all duration-200"
                                >{{ old('notes') }}</textarea>

                                @error('notes')
                                    <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- =================================================
                                BOUTON
                            ================================================== --}}
                            <div class="pt-10 border-t border-slate-100">

                                <button
                                    type="submit"
                                    class="brand-btn w-full inline-flex items-center justify-center gap-3
                                           px-6 py-4 sm:py-5 rounded-2xl
                                           text-white font-bold text-base sm:text-lg
                                           focus:outline-none focus:ring-4"
                                >
                                    <x-icon name="shopping-bag" class="w-5 h-5" />
                                    <span>Confirmer ma commande</span>
                                    <span class="opacity-90 font-semibold">— {{ $product->priceLabel() }}</span>
                                </button>

                                <p class="mt-4 text-center text-xs text-slate-500 leading-relaxed">
                                    Après votre demande, notre équipe vous contactera pour finaliser votre commande.
                                </p>

                            </div>

                        </form>

                    </div>

                </div>

                {{-- =========================================================
                    RÉCAPITULATIF
                ========================================================== --}}
                <div class="lg:col-span-1">

                    <div class="lg:sticky lg:top-28">

                        <div class="bg-white rounded-3xl shadow-[0_10px_40px_-20px_rgba(15,23,42,0.15)] border border-slate-100 overflow-hidden">

                            {{-- Image --}}
                            <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
                                @if ($product->imageUrl())
                                    <img
                                        src="{{ $product->imageUrl() }}"
                                        alt="{{ $product->title }}"
                                        class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                                    >
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <x-icon name="image" class="w-12 h-12" />
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 sm:p-7">

                                <span class="inline-flex px-3 py-1 rounded-full brand-bg-soft brand-text text-xs font-bold">
                                    {{ $product->typeLabel() }}
                                </span>

                                <h2 class="mt-4 text-xl font-bold text-slate-900 leading-snug">
                                    {{ $product->title }}
                                </h2>

                                <div class="mt-6 pt-6 border-t border-slate-100 space-y-3">

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-500">Prix unitaire</span>
                                        <span class="font-bold text-slate-900">{{ $product->priceLabel() }}</span>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-500">Quantité</span>
                                        <span class="font-semibold text-slate-900">× 1</span>
                                    </div>

                                </div>

                                <div class="mt-6 pt-6 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-900">Total</span>
                                        <span class="text-2xl font-extrabold brand-text">
                                            {{ $product->priceLabel() }}
                                        </span>
                                    </div>
                                </div>

                            </div>

                        </div>

                        {{-- Informations --}}
                        <div class="mt-6 rounded-3xl brand-bg-soft border border-[#E8631A]/15 p-6">

                            <div class="flex gap-4">

                                <div class="shrink-0">
                                    <x-icon name="shield-check" class="w-5 h-5 brand-text" />
                                </div>

                                <div>
                                    <h3 class="font-bold text-slate-900 text-sm">
                                        Vos informations sont sécurisées
                                    </h3>
                                    <p class="mt-2 text-xs leading-relaxed text-slate-600">
                                        Les informations de votre profil seront utilisées automatiquement
                                        pour traiter votre commande et, lorsque nécessaire, organiser la livraison.
                                    </p>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </section>

</x-layouts.public>
