<x-guest-layout :title="'Réinitialiser le mot de passe — Generation PUSH'">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">
            Nouveau mot de passe
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Choisis un nouveau mot de passe sécurisé.
        </p>
    </div>


    {{-- ============================================================
        ERREURS
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
                        Vérifie les informations saisies
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


    <form
        method="POST"
        action="{{ route('password.store') }}"
        class="space-y-5"
        id="resetPasswordForm"
    >

        @csrf

        <input
            type="hidden"
            name="token"
            value="{{ $request->route('token') }}"
        />


        {{-- ========================================================
            EMAIL
        ========================================================= --}}
        <div>

            <x-input-label
                for="email"
                value="Email"
            />

            <div class="relative mt-1">

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
                    :value="old('email', $request->email)"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="exemple@email.com"
                />

            </div>

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>


        {{-- ========================================================
            NOUVEAU MOT DE PASSE
        ========================================================= --}}
        <div>

            <x-input-label
                for="password"
                value="Nouveau mot de passe"
            />

            <div class="relative mt-1">

                <x-text-input
                    id="password"
                    class="block w-full pr-12"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                />

                {{-- AFFICHER / MASQUER --}}
                <button
                    type="button"
                    id="togglePassword"
                    class="absolute inset-y-0 right-0 flex items-center px-4
                           text-gray-400 hover:text-gray-700 transition"
                    aria-label="Afficher le mot de passe"
                >

                    {{-- Œil fermé --}}
                    <svg
                        id="passwordEyeClosed"
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
                        id="passwordEyeOpen"
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
            CONFIRMATION
        ========================================================= --}}
        <div>

            <x-input-label
                for="password_confirmation"
                value="Confirmer le mot de passe"
            />

            <div class="relative mt-1">

                <x-text-input
                    id="password_confirmation"
                    class="block w-full pr-12"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />

                {{-- AFFICHER / MASQUER --}}
                <button
                    type="button"
                    id="togglePasswordConfirmation"
                    class="absolute inset-y-0 right-0 flex items-center px-4
                           text-gray-400 hover:text-gray-700 transition"
                    aria-label="Afficher la confirmation du mot de passe"
                >

                    {{-- Œil fermé --}}
                    <svg
                        id="confirmationEyeClosed"
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
                        id="confirmationEyeOpen"
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


            {{-- MESSAGE TEMPS RÉEL --}}
            <div
                id="passwordMatchMessage"
                class="hidden mt-2 text-sm"
            ></div>


            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- ========================================================
            BOUTON
        ========================================================= --}}
        <x-primary-button
            id="resetButton"
            class="w-full"
        >
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

                Réinitialiser le mot de passe

            </span>
        </x-primary-button>

    </form>


    {{-- =============================================================
        JAVASCRIPT
    ============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const form =
                document.getElementById('resetPasswordForm');

            const password =
                document.getElementById('password');

            const passwordConfirmation =
                document.getElementById('password_confirmation');

            const resetButton =
                document.getElementById('resetButton');

            const matchMessage =
                document.getElementById('passwordMatchMessage');


            /*
            |--------------------------------------------------------------------------
            | AFFICHER / MASQUER
            |--------------------------------------------------------------------------
            */

            function setupPasswordToggle(
                input,
                button,
                eyeOpen,
                eyeClosed,
                showLabel,
                hideLabel
            ) {

                button.addEventListener('click', function () {

                    if (input.type === 'password') {

                        input.type = 'text';

                        eyeClosed.classList.add('hidden');
                        eyeOpen.classList.remove('hidden');

                        button.setAttribute(
                            'aria-label',
                            hideLabel
                        );

                    } else {

                        input.type = 'password';

                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');

                        button.setAttribute(
                            'aria-label',
                            showLabel
                        );

                    }

                });

            }


            setupPasswordToggle(
                password,
                document.getElementById('togglePassword'),
                document.getElementById('passwordEyeOpen'),
                document.getElementById('passwordEyeClosed'),
                'Afficher le mot de passe',
                'Masquer le mot de passe'
            );


            setupPasswordToggle(
                passwordConfirmation,
                document.getElementById('togglePasswordConfirmation'),
                document.getElementById('confirmationEyeOpen'),
                document.getElementById('confirmationEyeClosed'),
                'Afficher la confirmation du mot de passe',
                'Masquer la confirmation du mot de passe'
            );


            /*
            |--------------------------------------------------------------------------
            | VÉRIFICATION EN TEMPS RÉEL
            |--------------------------------------------------------------------------
            */

            function checkPasswords() {

                const firstPassword =
                    password.value;

                const secondPassword =
                    passwordConfirmation.value;


                /*
                | Rien de saisi
                */

                if (!firstPassword && !secondPassword) {

                    matchMessage.classList.add('hidden');

                    matchMessage.textContent = '';

                    resetButton.disabled = false;

                    return;
                }


                /*
                | Confirmation encore vide
                */

                if (!secondPassword) {

                    matchMessage.classList.add('hidden');

                    matchMessage.textContent = '';

                    resetButton.disabled = false;

                    return;
                }


                /*
                | CORRESPONDANCE
                */

                if (
                    firstPassword === secondPassword &&
                    firstPassword.length > 0
                ) {

                    matchMessage.classList.remove('hidden');

                    matchMessage.classList.remove(
                        'text-red-600'
                    );

                    matchMessage.classList.add(
                        'text-green-600'
                    );

                    matchMessage.innerHTML = `
                        <span class="inline-flex items-center gap-1.5">
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
                                    d="M5 13l4 4L19 7"
                                />
                            </svg>

                            Les mots de passe correspondent.
                        </span>
                    `;

                    resetButton.disabled = false;

                    return;
                }


                /*
                | PAS DE CORRESPONDANCE
                */

                matchMessage.classList.remove('hidden');

                matchMessage.classList.remove(
                    'text-green-600'
                );

                matchMessage.classList.add(
                    'text-red-600'
                );

                matchMessage.innerHTML = `
                    <span class="inline-flex items-center gap-1.5">
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
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>

                        Les mots de passe ne correspondent pas.
                    </span>
                `;

                resetButton.disabled = true;
            }


            /*
            |--------------------------------------------------------------------------
            | ÉCOUTE EN TEMPS RÉEL
            |--------------------------------------------------------------------------
            */

            password.addEventListener(
                'input',
                checkPasswords
            );

            passwordConfirmation.addEventListener(
                'input',
                checkPasswords
            );


            /*
            |--------------------------------------------------------------------------
            | VÉRIFICATION AVANT ENVOI
            |--------------------------------------------------------------------------
            */

            form.addEventListener('submit', function (event) {

                if (
                    password.value !==
                    passwordConfirmation.value
                ) {

                    event.preventDefault();

                    checkPasswords();

                    passwordConfirmation.focus();
                }

            });

        });
    </script>

</x-guest-layout>
