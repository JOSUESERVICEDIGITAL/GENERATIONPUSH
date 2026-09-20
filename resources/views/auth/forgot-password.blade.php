<x-guest-layout :title="'Mot de passe oublié — Generation PUSH'">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">
            Mot de passe oublié ?
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Pas de souci, on t'envoie un lien de réinitialisation par email.
        </p>
    </div>


    {{-- ============================================================
        MESSAGE DE SUCCÈS
    ============================================================= --}}
    @if (session('status'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-green-700">
            <div class="flex items-start gap-3">

                <svg
                    class="w-5 h-5 mt-0.5 shrink-0"
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

                <div>
                    <p class="font-semibold">
                        Email envoyé
                    </p>

                    <p class="text-sm mt-1">
                        {{ session('status') }}
                    </p>
                </div>

            </div>
        </div>
    @endif


    {{-- ============================================================
        ERREUR GÉNÉRALE
    ============================================================= --}}
    @if ($errors->any())
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-red-700">
            <div class="flex items-start gap-3">

                <svg
                    class="w-5 h-5 mt-0.5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>

                <div>
                    <p class="font-semibold">
                        Impossible d'envoyer le lien
                    </p>

                    <ul class="text-sm mt-1 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    @endif


    {{-- ============================================================
        FORMULAIRE
    ============================================================= --}}
    <form
        method="POST"
        action="{{ route('password.email') }}"
        class="space-y-5"
    >
        @csrf


        {{-- EMAIL --}}
        <div>

            <x-input-label
                for="email"
                value="Email"
            />

            <div class="relative mt-1">

                {{-- Icône email --}}
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                    <svg
                        class="w-5 h-5 text-gray-400"
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
                </div>

                <x-text-input
                    id="email"
                    class="block w-full pl-11"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="exemple@email.com"
                />

            </div>

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- BOUTON --}}
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

                Envoyer le lien de réinitialisation

            </span>
        </x-primary-button>


        {{-- RETOUR CONNEXION --}}
        <p class="text-center text-sm text-gray-500">

            <a
                href="{{ route('login') }}"
                class="inline-flex items-center gap-1.5 text-accent font-semibold hover:underline"
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
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />
                </svg>

                Retour à la connexion
            </a>

        </p>

    </form>

</x-guest-layout>
