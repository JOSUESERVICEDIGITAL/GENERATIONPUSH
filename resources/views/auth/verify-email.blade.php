<x-guest-layout :title="'Vérifie ton email — Generation PUSH'">

    {{-- ============================================================
        EN-TÊTE
    ============================================================= --}}
    <div class="mb-8">

        {{-- Icône --}}
        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center mb-5">
            <x-icon
                name="mail"
                class="w-6 h-6 text-accent"
            />
        </div>

        <h1 class="text-2xl font-bold text-[#1A1A1A]">
            Vérifie ton adresse email
        </h1>

        <p class="text-gray-500 text-sm mt-2 leading-relaxed">
            Merci de t'être inscrit ! Avant de commencer, confirme ton
            adresse email en cliquant sur le lien que nous venons de
            t'envoyer.
        </p>

    </div>


    {{-- ============================================================
        SUCCÈS : NOUVEAU LIEN ENVOYÉ
    ============================================================= --}}
    @if (session('status') == 'verification-link-sent')

        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">

            <div class="flex items-start gap-3">

                <div class="shrink-0 mt-0.5">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                </div>

                <div>

                    <p class="font-semibold">
                        Email envoyé
                    </p>

                    <p class="text-sm mt-1 leading-relaxed">
                        Un nouveau lien de vérification a été envoyé
                        à l'adresse email que tu as fournie lors de
                        ton inscription.
                    </p>

                </div>

            </div>

        </div>

    @endif


    {{-- ============================================================
        INFORMATION
    ============================================================= --}}
    <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-4">

        <div class="flex items-start gap-3">

            <svg
                class="w-5 h-5 text-gray-400 mt-0.5 shrink-0"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"
                />
            </svg>

            <div>

                <p class="text-sm font-medium text-gray-700">
                    Tu n'as pas reçu l'email ?
                </p>

                <p class="text-sm text-gray-500 mt-1 leading-relaxed">
                    Vérifie également ton dossier spam ou courrier
                    indésirable. Si tu ne trouves toujours rien,
                    demande simplement un nouvel envoi.
                </p>

            </div>

        </div>

    </div>


    {{-- ============================================================
        ACTIONS
    ============================================================= --}}
    <div class="space-y-3">

        {{-- RENVOYER --}}
        <form
            method="POST"
            action="{{ route('verification.send') }}"
        >
            @csrf

            <x-primary-button class="w-full">

                <span class="inline-flex items-center justify-center gap-2">

                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 8l9 6 9-6M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z"
                        />
                    </svg>

                    Renvoyer l'email de vérification

                </span>

            </x-primary-button>

        </form>


        {{-- DÉCONNEXION --}}
        <form
            method="POST"
            action="{{ route('logout') }}"
        >
            @csrf

            <button
                type="submit"
                class="w-full inline-flex items-center justify-center gap-2
                       py-2.5 text-sm text-gray-500
                       hover:text-accent transition-colors duration-200
                       cursor-pointer"
            >

                <svg
                    class="w-4 h-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"
                    />
                </svg>

                Déconnexion

            </button>

        </form>

    </div>

</x-guest-layout>
