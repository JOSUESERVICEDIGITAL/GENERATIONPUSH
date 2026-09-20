<x-guest-layout :title="'Rejoindre — Generation PUSH'">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">
            Rejoins la communauté
        </h1>

        <p class="text-gray-500 text-sm mt-1">
            Crée ton compte en quelques secondes.
        </p>
    </div>


    <form
        method="POST"
        action="{{ route('register') }}"
        class="space-y-5"
        id="registerForm"
    >
        @csrf


        {{-- NOM --}}
        <div>
            <x-input-label
                for="name"
                value="Nom complet"
            />

            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error
                :messages="$errors->get('name')"
                class="mt-2"
            />
        </div>


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
                autocomplete="username"
            />

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />
        </div>


        {{-- =========================================================
            MOT DE PASSE
        ========================================================== --}}
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
                    autocomplete="new-password"
                />

                {{-- BOUTON AFFICHER / MASQUER --}}
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


        {{-- =========================================================
            CONFIRMATION MOT DE PASSE
        ========================================================== --}}
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

                {{-- BOUTON AFFICHER / MASQUER --}}
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


            {{-- MESSAGE DE CORRESPONDANCE --}}
            <div
                id="passwordMatchMessage"
                class="hidden mt-2 text-sm"
            ></div>


            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />

        </div>


        {{-- =========================================================
            BOUTON
        ========================================================== --}}
        <x-primary-button
            id="registerButton"
            class="w-full"
        >
            Créer mon compte
        </x-primary-button>


        {{-- =========================================================
            CONNEXION
        ========================================================== --}}
        <p class="text-center text-sm text-gray-500">

            Déjà membre ?

            <a
                href="{{ route('login') }}"
                class="text-accent font-semibold hover:underline"
            >
                Se connecter
            </a>

        </p>

    </form>


    {{-- =============================================================
        JAVASCRIPT
    ============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | ÉLÉMENTS
            |--------------------------------------------------------------------------
            */

            const form = document.getElementById('registerForm');

            const password = document.getElementById('password');
            const passwordConfirmation =
                document.getElementById('password_confirmation');

            const registerButton =
                document.getElementById('registerButton');

            const matchMessage =
                document.getElementById('passwordMatchMessage');


            /*
            |--------------------------------------------------------------------------
            | AFFICHER / MASQUER MOT DE PASSE
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


            /*
            |--------------------------------------------------------------------------
            | MOT DE PASSE PRINCIPAL
            |--------------------------------------------------------------------------
            */

            setupPasswordToggle(
                password,
                document.getElementById('togglePassword'),
                document.getElementById('passwordEyeOpen'),
                document.getElementById('passwordEyeClosed'),
                'Afficher le mot de passe',
                'Masquer le mot de passe'
            );


            /*
            |--------------------------------------------------------------------------
            | CONFIRMATION
            |--------------------------------------------------------------------------
            */

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

                const firstPassword = password.value;
                const secondPassword = passwordConfirmation.value;


                /*
                | Tant que rien n'est saisi
                */

                if (!firstPassword && !secondPassword) {

                    matchMessage.classList.add('hidden');
                    matchMessage.textContent = '';

                    registerButton.disabled = false;

                    return;
                }


                /*
                | Confirmation vide
                */

                if (!secondPassword) {

                    matchMessage.classList.add('hidden');
                    matchMessage.textContent = '';

                    registerButton.disabled = false;

                    return;
                }


                /*
                | MOTS DE PASSE IDENTIQUES
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

                    registerButton.disabled = false;

                    return;
                }


                /*
                | MOTS DE PASSE DIFFÉRENTS
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

                registerButton.disabled = true;
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
            | DOUBLE SÉCURITÉ AVANT ENVOI
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
