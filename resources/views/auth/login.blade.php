<x-guest-layout :title="'Connexion — Generation PUSH'">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">
            Content de te revoir
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Connecte-toi pour accéder à ton espace.
        </p>
    </div>

    {{-- ============================================================
        ALERTE COMPTE EN ATTENTE DE VALIDATION
    ============================================================= --}}
    @if (session('pending'))
        <div class="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-800">
            <div class="flex items-start gap-3">
                <div class="mt-0.5 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z"/>
                    </svg>
                </div>

                <div>
                    <p class="font-semibold">
                        Compte en attente de validation
                    </p>

                    <p class="text-sm mt-1">
                        Votre demande a bien été enregistrée.
                        Votre compte doit encore être validé par un administrateur
                        de Generation PUSH.
                    </p>

                    <p class="text-sm mt-2 font-medium">
                        Merci de patienter. Nous vous contacterons dès que votre
                        compte sera validé.
                    </p>
                </div>
            </div>
        </div>
    @endif


    {{-- ============================================================
        MESSAGE SESSION
    ============================================================= --}}
    @if (session('status'))
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200
                    px-4 py-3 text-sm font-medium text-green-700">
            {{ session('status') }}
        </div>
    @endif


    {{-- ============================================================
        ERREUR GENERALE
    ============================================================= --}}
    @if ($errors->has('login'))
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200
                    px-4 py-3 text-sm text-red-700">
            {{ $errors->first('login') }}
        </div>
    @endif


    <form method="POST"
          action="{{ route('login') }}"
          class="space-y-5">

        @csrf

        {{-- EMAIL --}}
        <div>
            <x-input-label
                for="email"
                value="Email"
            />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>


        {{-- MOT DE PASSE --}}
        <div>
            <x-input-label
                for="password"
                value="Mot de passe"
            />

            <div class="relative mt-1">

                <x-text-input
                    id="password"
                    class="block w-full pr-12"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

                {{-- BOUTON AFFICHER / MASQUER --}}
                <button
                    type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center
                           px-4 text-gray-400 hover:text-gray-700
                           transition"
                    aria-label="Afficher le mot de passe"
                >

                    {{-- Œil fermé --}}
                    <svg
                        id="eyeClosed"
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3l18 18M10.58 10.58a2 2 0 102.83 2.83M9.88 4.24A10.94 10.94 0 0112 4c5 0 9.27 3.11 10.5 7.5a10.9 10.9 0 01-4.12 5.52M6.23 6.23A10.9 10.9 0 003.5 11.5C4.73 15.89 9 19 14 19c1.1 0 2.17-.17 3.18-.49"
                        />
                    </svg>

                    {{-- Œil ouvert --}}
                    <svg
                        id="eyeOpen"
                        class="w-5 h-5 hidden"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M2.25 12s3.75-7 9.75-7 9.75 7 9.75 7-3.75 7-9.75 7-9.75-7-9.75-7z"
                        />

                        <circle
                            cx="12"
                            cy="12"
                            r="3"
                            stroke-width="2"
                        />
                    </svg>

                </button>
            </div>

            <x-input-error
                :messages="$errors->get('password')"
                class="mt-2"
            />
        </div>


        {{-- REMEMBER + MOT DE PASSE OUBLIE --}}
        <div class="flex items-center justify-between">

            <label class="flex items-center gap-2">
                <x-checkbox name="remember" />

                <span class="text-sm text-gray-600">
                    Se souvenir de moi
                </span>
            </label>

            @if (Route::has('password.request'))
                <a
                    class="text-sm text-accent hover:underline"
                    href="{{ route('password.request') }}"
                >
                    Mot de passe oublié ?
                </a>
            @endif

        </div>


        {{-- CONNEXION --}}
        <x-primary-button class="w-full">
            Se connecter
        </x-primary-button>


        {{-- INSCRIPTION --}}
        <p class="text-center text-sm text-gray-500">

            Pas encore de compte ?

            <a
                href="{{ route('register') }}"
                class="text-accent font-semibold hover:underline"
            >
                Rejoindre la communauté
            </a>

        </p>

    </form>


    {{-- ============================================================
        SCRIPT AFFICHER / MASQUER MOT DE PASSE
    ============================================================= --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const password = document.getElementById('password');
            const toggle = document.getElementById('togglePassword');
            const eyeOpen = document.getElementById('eyeOpen');
            const eyeClosed = document.getElementById('eyeClosed');

            if (!password || !toggle) {
                return;
            }

            toggle.addEventListener('click', function () {

                if (password.type === 'password') {

                    password.type = 'text';

                    eyeClosed.classList.add('hidden');
                    eyeOpen.classList.remove('hidden');

                    toggle.setAttribute(
                        'aria-label',
                        'Masquer le mot de passe'
                    );

                } else {

                    password.type = 'password';

                    eyeOpen.classList.add('hidden');
                    eyeClosed.classList.remove('hidden');

                    toggle.setAttribute(
                        'aria-label',
                        'Afficher le mot de passe'
                    );
                }
            });

        });
    </script>

</x-guest-layout>
