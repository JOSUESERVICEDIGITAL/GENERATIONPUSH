<section class="w-full">

    {{-- ============================================================
        TOAST SUCCÈS — MODALE SOUS LE HEADER
    ============================================================ --}}
    <div
        x-data="{ show: {{ session('status') === 'profile-updated' ? 'true' : 'false' }} }"
        x-show="show"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
        x-init="if (show) setTimeout(() => show = false, 5000)"
        class="fixed right-2 top-6 z-[99999] w-[calc(100%-2rem)] max-w-xl"
        role="alert"
        aria-live="polite"
    >
        <div class="relative overflow-hidden rounded-2xl border border-green-200 bg-white shadow-2xl">

            {{-- Barre supérieure --}}
            <div class="h-1.5 w-full bg-green-500"></div>

            <div class="flex items-start gap-4 px-6 py-5">

                {{-- Icône --}}
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2.5">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M5 13l4 4L19 7"/>
                    </svg>
                </div>

                {{-- Message --}}
                <div class="min-w-0 flex-1 pt-0.5">
                    <h3 class="text-lg font-bold text-gray-900">
                        Succès
                    </h3>

                    <p class="mt-1 text-sm text-gray-600">
                        Profil enregistré avec succès.
                    </p>
                </div>

                {{-- Bouton fermer --}}
                <button
                    type="button"
                    @click="show = false"
                    class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg text-gray-400 transition hover:bg-gray-100 hover:text-gray-700"
                    aria-label="Fermer"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>

            </div>
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>


    {{-- ============================================================
        HEADER
    ============================================================ --}}
    <div class="mb-8 flex items-start gap-4">

        <div
            class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-white shadow-sm"
            style="background-color: #E8631A;"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 class="h-6 w-6"
                 fill="none"
                 viewBox="0 0 24 24"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"/>
            </svg>
        </div>

        <div>
            <h2 class="text-xl font-bold text-gray-900">
                Informations du profil
            </h2>

            <p class="mt-1 text-sm leading-6 text-gray-500">
                Complète tes informations personnelles pour profiter pleinement
                de ton espace Generation PUSH.
            </p>
        </div>

    </div>


    {{-- ============================================================
        VERIFICATION EMAIL
    ============================================================ --}}
    <form id="send-verification"
          method="post"
          action="{{ route('verification.send') }}">
        @csrf
    </form>


    {{-- ============================================================
        FORMULAIRE
    ============================================================ --}}
    <form method="post"
          action="{{ route('profile.update') }}"
          enctype="multipart/form-data"
          class="space-y-8">

        @csrf
        @method('patch')


        {{-- ========================================================
            PHOTO
        ======================================================== --}}
        <div
            class="overflow-hidden rounded-2xl border border-gray-200"
            style="background-color: #fffaf7;"
        >

            <div
                class="border-b border-gray-200 px-6 py-5"
                style="background-color: #E8631A;"
            >

                <h3 class="text-base font-bold text-white">
                    Photo de profil
                </h3>

                <p class="mt-1 text-sm text-white/80">
                    Ajoute une photo pour personnaliser ton espace membre.
                </p>

            </div>


            <div class="p-6">

                <div class="flex flex-col gap-6 md:flex-row md:items-center">

                    {{-- Avatar --}}
                    <div class="relative mx-auto shrink-0 md:mx-0">

                        <div
                            id="profile-photo-preview"
                            class="h-36 w-36 overflow-hidden rounded-full border-4 border-white bg-gray-100 shadow-lg ring-4"
                            style="--tw-ring-color: rgba(232, 99, 26, .18);"
                        >

                            @if($user->profile_photo)

                                <img
                                    src="{{ Storage::url($user->profile_photo) }}"
                                    alt="Photo de {{ $user->name }}"
                                    class="h-full w-full object-cover"
                                >

                            @else

                                <div
                                    class="flex h-full w-full items-center justify-center text-5xl font-bold text-white"
                                    style="background-color: #E8631A;"
                                >
                                    {{ strtoupper(substr($user->name ?? 'M', 0, 1)) }}
                                </div>

                            @endif

                        </div>


                        {{-- Bouton caméra --}}
                        <label
                            for="profile_photo"
                            class="absolute bottom-1 right-1 flex h-11 w-11 cursor-pointer items-center justify-center rounded-full border-4 border-white text-white shadow-lg transition hover:scale-105"
                            style="background-color: #E8631A;"
                            title="Changer la photo"
                        >

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M6.827 6.5H5.25A2.25 2.25 0 003 8.75v8.5A2.25 2.25 0 005.25 19.5h13.5A2.25 2.25 0 0021 17.25v-8.5a2.25 2.25 0 00-2.25-2.25h-1.577a1.5 1.5 0 01-1.06-.44l-.94-.94a1.5 1.5 0 00-1.06-.44h-2.226a1.5 1.5 0 00-1.06.44l-.94.94a1.5 1.5 0 01-1.06.44z"/>

                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M15.75 13a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>

                            </svg>

                        </label>

                    </div>


                    {{-- Zone upload --}}
                    <div class="flex-1">

                        <label
                            for="profile_photo"
                            class="group flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed px-6 py-8 text-center transition"
                            style="border-color: rgba(232,99,26,.35); background-color: #ffffff;"
                            onmouseover="this.style.backgroundColor='#fff7f2';this.style.borderColor='#E8631A'"
                            onmouseout="this.style.backgroundColor='#ffffff';this.style.borderColor='rgba(232,99,26,.35)'"
                        >

                            <div
                                class="mb-3 flex h-12 w-12 items-center justify-center rounded-xl text-white"
                                style="background-color: #E8631A;"
                            >

                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-6 w-6"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor"
                                     stroke-width="1.8">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          d="M3 16.5l4.5-4.5a2.121 2.121 0 013 0L15 16.5m-3-3l1.5-1.5a2.121 2.121 0 013 0L21 16.5M15 8h.008M5.25 4.5h13.5A1.75 1.75 0 0120.5 6.25v11.5a1.75 1.75 0 01-1.75 1.75H5.25a1.75 1.75 0 01-1.75-1.75V6.25A1.75 1.75 0 015.25 4.5z"/>

                                </svg>

                            </div>

                            <span class="text-sm font-bold text-gray-900">
                                Choisir une nouvelle photo
                            </span>

                            <span class="mt-1 text-xs text-gray-500">
                                JPG, JPEG, PNG ou WEBP — 5 Mo maximum
                            </span>

                        </label>

                        <input
                            id="profile_photo"
                            name="profile_photo"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="hidden"
                        >

                        <p
                            id="profile-photo-name"
                            class="mt-2 hidden text-center text-xs font-semibold"
                            style="color: #E8631A;"
                        ></p>

                        <x-input-error
                            class="mt-2"
                            :messages="$errors->get('profile_photo')"
                        />

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================
            IDENTITE
        ======================================================== --}}
        <div>

            <div class="mb-5 flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-white"
                    style="background-color: #E8631A;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a8.25 8.25 0 0115 0"/>
                    </svg>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900">
                        Identité
                    </h3>

                    <p class="text-sm text-gray-500">
                        Les informations principales de ton compte.
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Nom --}}
                <div class="md:col-span-2">

                    <label
                        for="name"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Nom complet
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Ex. Josué Ouedraogo"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-[#E8631A] focus:ring-[#E8631A]"
                    >

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('name')"
                    />

                </div>


                {{-- Email --}}
                <div>

                    <label
                        for="email"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Adresse email
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="username"
                        placeholder="exemple@email.com"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-[#E8631A] focus:ring-[#E8631A]"
                    >

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('email')"
                    />

                    @if (
                        $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
                        && ! $user->hasVerifiedEmail()
                    )

                        <div class="mt-3 rounded-xl border border-orange-200 bg-orange-50 p-3">

                            <p class="text-sm text-gray-700">
                                Ton adresse email n'est pas encore vérifiée.
                            </p>

                            <button
                                form="send-verification"
                                type="submit"
                                class="mt-1 text-sm font-bold underline underline-offset-2 cursor-pointer"
                                style="color: #E8631A;"
                            >
                                Renvoyer l'email de vérification
                            </button>

                            @if (session('status') === 'verification-link-sent')

                                <p class="mt-2 text-sm font-semibold text-green-600">
                                    Un nouveau lien de vérification a été envoyé.
                                </p>

                            @endif

                        </div>

                    @endif

                </div>


                {{-- Téléphone --}}
                <div>

                    <label
                        for="phone"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Numéro de téléphone
                    </label>

                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone', $user->phone) }}"
                        required
                        autocomplete="tel"
                        placeholder="+226 XX XX XX XX"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-[#E8631A] focus:ring-[#E8631A]"
                    >

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('phone')"
                    />

                </div>

            </div>

        </div>


        {{-- ========================================================
            LOCALISATION
        ======================================================== --}}
        <div class="border-t border-gray-100 pt-8">

            <div class="mb-5 flex items-center gap-3">

                <div
                    class="flex h-10 w-10 items-center justify-center rounded-xl text-white"
                    style="background-color: #E8631A;"
                >
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="2">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M12 21s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z"/>

                        <circle cx="12"
                                cy="11"
                                r="2.5"/>

                    </svg>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900">
                        Localisation
                    </h3>

                    <p class="text-sm text-gray-500">
                        Utilisée notamment pour les commandes et livraisons.
                    </p>
                </div>

            </div>


            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                {{-- Pays --}}
                <div>

                    <label
                        for="country"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Pays
                    </label>

                    <input
                        id="country"
                        name="country"
                        type="text"
                        value="{{ old('country', $user->country) }}"
                        required
                        autocomplete="country-name"
                        placeholder="Ex. Burkina Faso"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-[#E8631A] focus:ring-[#E8631A]"
                    >

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('country')"
                    />

                </div>


                {{-- Ville --}}
                <div>

                    <label
                        for="city"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Ville
                    </label>

                    <input
                        id="city"
                        name="city"
                        type="text"
                        value="{{ old('city', $user->city) }}"
                        required
                        autocomplete="address-level2"
                        placeholder="Ex. Ouagadougou"
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-[#E8631A] focus:ring-[#E8631A]"
                    >

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('city')"
                    />

                </div>


                {{-- Adresse --}}
                <div class="md:col-span-2">

                    <label
                        for="address"
                        class="block text-sm font-semibold text-gray-700"
                    >
                        Adresse complète
                    </label>

                    <textarea
                        id="address"
                        name="address"
                        rows="3"
                        required
                        autocomplete="street-address"
                        placeholder="Quartier, secteur, rue, numéro de porte..."
                        class="mt-1.5 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-[#E8631A] focus:ring-[#E8631A]"
                    >{{ old('address', $user->address) }}</textarea>

                    <x-input-error
                        class="mt-2"
                        :messages="$errors->get('address')"
                    />

                </div>

            </div>

        </div>
<br>

        {{-- ========================================================
            NOTE GENERATION PUSH
        ======================================================== --}}
        <div
            class="rounded-2xl p-5"
            style="background-color: #E8631A;"
        >

            <div class="flex gap-4">

                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/15 text-white">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-5 w-5"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.8">

                        <circle cx="12"
                                cy="12"
                                r="9"/>

                        <path stroke-linecap="round"
                              d="M12 10v6"/>

                        <path stroke-linecap="round"
                              d="M12 7.25h.01"/>

                    </svg>

                </div>
                <div>

                    <h4 class="font-bold text-white">
                        Ton profil Generation PUSH
                    </h4>

                    <p class="mt-1 text-sm leading-6 text-white/85">
                        Ces informations pourront être réutilisées automatiquement
                        lorsque tu participeras à une activité, effectueras une
                        commande ou demanderas une livraison.
                    </p>

                </div>

            </div>

        </div>


        {{-- ========================================================
            ACTION
        ======================================================== --}}
<div class="flex flex-col gap-6 border-t border-gray-200 pt-6 sm:flex-row sm:items-center">

    <button
        type="submit"
        class="inline-flex w-full items-center justify-center gap-3 rounded-xl px-10 py-4 text-sm font-bold text-white shadow-md transition hover:-translate-y-0.5 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2"
        style="background-color: #E8631A; --tw-ring-color: #E8631A;"
    >
        <svg xmlns="http://www.w3.org/2000/svg"
             class="h-5 w-5 shrink-0"
             fill="none"
             viewBox="0 0 24 24"
             stroke="currentColor"
             stroke-width="2">

            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  d="M5 13l4 4L19 7"/>

        </svg>

        <span class="whitespace-nowrap">
            Enregistrer mon profil
        </span>
    </button>


</div>

    </form>


    {{-- ============================================================
        APERCU PHOTO
    ============================================================ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const input = document.getElementById('profile_photo');
            const preview = document.getElementById('profile-photo-preview');
            const fileName = document.getElementById('profile-photo-name');

            if (!input || !preview) {
                return;
            }

            input.addEventListener('change', function (event) {

                const file = event.target.files[0];

                if (!file) {
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    return;
                }

                if (fileName) {
                    fileName.textContent = 'Photo sélectionnée : ' + file.name;
                    fileName.classList.remove('hidden');
                }

                const reader = new FileReader();

                reader.onload = function (e) {

                    preview.innerHTML = `
                        <img
                            src="${e.target.result}"
                            alt="Aperçu de la photo"
                            class="h-full w-full object-cover"
                        >
                    `;

                };

                reader.readAsDataURL(file);

            });

        });
    </script>

</section>
