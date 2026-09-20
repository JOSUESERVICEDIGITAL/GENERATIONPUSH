<x-guest-layout :title="'Confirme ton mot de passe — Generation PUSH'">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">
            Zone sécurisée
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Confirme ton mot de passe avant de continuer.
        </p>
    </div>


    {{-- ============================================================
        ERREUR DE CONFIRMATION
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
                        Vérification impossible
                    </p>

                    <p class="text-sm mt-1">
                        {{ $errors->first() }}
                    </p>
                </div>

            </div>
        </div>
    @endif


    <form
        method="POST"
        action="{{ route('password.confirm') }}"
        class="space-y-5"
        id="confirmPasswordForm"
    >
        @csrf


        {{-- ========================================================
            MOT DE PASSE
        ========================================================= --}}
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
                    autofocus
                />

                {{-- AFFICHER / MASQUER --}}
                <button
                    type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center
                           px-4 text-gray-400 hover:text-gray-700 transition"
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


        {{-- ========================================================
            BOUTON
        ========================================================= --}}
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
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2zm3-9V7a3 3 0 016 0v3"
                    />
                </svg>

                Confirmer

            </span>
        </x-primary-button>

    </form>


    {{-- =============================================================
        SCRIPT AFFICHER / MASQUER
    ============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const password =
                document.getElementById('password');

            const toggle =
                document.getElementById('togglePassword');

            const eyeOpen =
                document.getElementById('eyeOpen');

            const eyeClosed =
                document.getElementById('eyeClosed');


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
