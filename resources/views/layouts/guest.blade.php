<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Generation PUSH') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />
    <link href="https://fonts.bunny.net/css?family=lora:600,700" rel="stylesheet" />
    <link
        href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|lora:600,700"
        rel="stylesheet"
    />

        {{-- ============================================================
        IZI TOAST
        ============================================================ --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/css/iziToast.min.css"
    >
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ============================================================
        CORRECTION GLOBALE DES CHAMPS MOT DE PASSE
        ============================================================ --}}
    <style>
        /*
         * Tous les conteneurs de champs password ayant
         * un bouton d'affichage doivent être positionnés en relative.
         */
        .password-field {
            position: relative;
        }

        /*
         * Le bouton œil reste toujours à l'intérieur
         * du champ, à droite et centré verticalement.
         */
        .password-field .password-toggle {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            width: 3rem;

            padding: 0;
            margin: 0;

            border: 0;
            background: transparent;

            cursor: pointer;
            z-index: 10;
        }

        /*
         * On réserve l'espace nécessaire à droite
         * pour que le texte ne passe pas sous l'œil.
         */
        .password-field input[type="password"],
        .password-field input[type="text"] {
            padding-right: 3rem !important;
        }

        /*
         * RTL : l'œil passe naturellement à gauche.
         */
        [dir="rtl"] .password-field .password-toggle {
            right: auto;
            left: 0;
        }

        [dir="rtl"] .password-field input[type="password"],
        [dir="rtl"] .password-field input[type="text"] {
            padding-right: 1rem !important;
            padding-left: 3rem !important;
        }
    </style>
</head>

<body class="font-sans antialiased text-[#1A1A1A]">

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

        <!-- =========================================================
             PANNEAU BRANDING
             ========================================================= -->
        <div class="hidden lg:flex flex-col justify-between relative overflow-hidden bg-[#1A1A1A] p-12 text-white">

            <div class="absolute inset-0 bg-gradient-to-br from-accent/30 via-transparent to-transparent"></div>

            <div class="absolute -bottom-24 -start-24 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>

            <a href="{{ route('front.home') }}" class="relative flex items-center gap-3">
                <div class="w-11 h-11 rounded-lg bg-accent flex items-center justify-center text-white font-bold">
                    GP
                </div>

                <span class="font-bold text-lg">
                    Generation PUSH
                </span>
            </a>

            <div class="relative max-w-md">

                <p
                    class="text-3xl font-extrabold leading-tight"
                    style="font-family: 'Lora', serif;"
                >
                    "Rejoindre Generation PUSH a changé ma trajectoire de leader."
                </p>

                <p class="text-gray-400 text-sm mt-4">
                    — Un membre de la communauté
                </p>

                <div class="grid grid-cols-3 gap-6 mt-10 pt-8 border-t border-white/10">

                    <div>
                        <p class="text-2xl font-extrabold text-accent">
                            2500+
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Membres
                        </p>
                    </div>

                    <div>
                        <p class="text-2xl font-extrabold text-accent">
                            40+
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Formations
                        </p>
                    </div>

                    <div>
                        <p class="text-2xl font-extrabold text-accent">
                            98%
                        </p>

                        <p class="text-xs text-gray-400 mt-1">
                            Satisfaction
                        </p>
                    </div>

                </div>
            </div>

            <p class="relative text-xs text-gray-500">
                &copy; {{ now()->year }} Generation PUSH. Tous droits réservés.
            </p>

        </div>


        <!-- =========================================================
             PANNEAU FORMULAIRE
             ========================================================= -->
        <div class="flex flex-col items-center justify-center px-4 sm:px-6 py-12 bg-white">

            <div class="w-full max-w-sm">

                <!-- Logo mobile -->
                <a
                    href="{{ route('front.home') }}"
                    class="flex lg:hidden items-center gap-3 justify-center mb-8"
                >
                    <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center text-white font-bold">
                        GP
                    </div>

                    <span class="font-bold">
                        Generation PUSH
                    </span>
                </a>

                <!-- =================================================
                     CONTENU DE LA PAGE
                     ================================================= -->
                {{ $slot }}

                <p class="text-center text-xs text-gray-400 mt-8">
                    <a
                        href="{{ route('front.home') }}"
                        class="hover:text-accent transition-colors duration-200"
                    >
                        ← Retour au site
                    </a>
                </p>

            </div>

        </div>

    </div>


    <!-- =============================================================
         TOASTS
         ============================================================= -->
   {{-- =============================================================
     TOASTS GENERATION PUSH
     ============================================================= --}}
<div
    id="gp-toast-container"
    class="fixed top-5 right-5 z-[99999] w-[calc(100%-2rem)] max-w-md space-y-3"
></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        function showToast(type, title, message) {

            const container = document.getElementById('gp-toast-container');

            if (!container) return;

            const config = {
                success: {
                    icon: '✓',
                    iconClass: 'bg-green-100 text-green-600',
                    borderClass: 'border-green-200',
                },

                error: {
                    icon: '!',
                    iconClass: 'bg-red-100 text-red-600',
                    borderClass: 'border-red-200',
                },

                warning: {
                    icon: '!',
                    iconClass: 'bg-orange-100 text-orange-600',
                    borderClass: 'border-orange-200',
                },

                info: {
                    icon: 'i',
                    iconClass: 'bg-blue-100 text-blue-600',
                    borderClass: 'border-blue-200',
                },
            };

            const current = config[type] ?? config.info;

            const toast = document.createElement('div');

            toast.className = `
                relative
                flex
                items-start
                gap-3
                rounded-2xl
                border
                ${current.borderClass}
                bg-white
                p-4
                shadow-[0_15px_40px_rgba(0,0,0,0.15)]
                opacity-0
                translate-x-8
                transition-all
                duration-300
            `;

            toast.innerHTML = `
                <div class="
                    flex
                    h-10
                    w-10
                    shrink-0
                    items-center
                    justify-center
                    rounded-full
                    ${current.iconClass}
                    font-bold
                    text-lg
                ">
                    ${current.icon}
                </div>

                <div class="min-w-0 flex-1 pr-5">
                    <div class="text-sm font-bold text-[#1A1A1A]">
                        ${title}
                    </div>

                    <div class="mt-1 text-sm leading-relaxed text-gray-600">
                        ${message}
                    </div>
                </div>

                <button
                    type="button"
                    class="
                        absolute
                        right-3
                        top-3
                        flex
                        h-7
                        w-7
                        items-center
                        justify-center
                        rounded-full
                        text-gray-400
                        transition
                        hover:bg-gray-100
                        hover:text-gray-700
                    "
                    aria-label="Fermer"
                >
                    ×
                </button>
            `;

            container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-x-8');
            });

            const closeButton = toast.querySelector('button');

            closeButton.addEventListener('click', () => {
                closeToast(toast);
            });

            setTimeout(() => {
                closeToast(toast);
            }, 5000);
        }

        function closeToast(toast) {

            if (!toast || !toast.parentNode) return;

            toast.classList.add(
                'opacity-0',
                'translate-x-8'
            );

            setTimeout(() => {
                toast.remove();
            }, 300);
        }

        /*
        |--------------------------------------------------------------------------
        | PROFIL MIS À JOUR
        |--------------------------------------------------------------------------
        */

        @if(session('status') === 'profile-updated')
            showToast(
                'success',
                'Profil mis à jour',
                'Votre profil Generation PUSH a été mis à jour avec succès.'
            );
        @endif


        /*
        |--------------------------------------------------------------------------
        | PROFIL À COMPLÉTER
        |--------------------------------------------------------------------------
        */

        @if(session('profile_required'))
            showToast(
                'info',
                'Profil à compléter',
                @json(session('profile_required'))
            );
        @endif


        /*
        |--------------------------------------------------------------------------
        | SUCCÈS
        |--------------------------------------------------------------------------
        */

        @if(session('success'))
            showToast(
                'success',
                'Succès',
                @json(session('success'))
            );
        @endif


        /*
        |--------------------------------------------------------------------------
        | ERREUR
        |--------------------------------------------------------------------------
        */

        @if(session('error'))
            showToast(
                'error',
                'Erreur',
                @json(session('error'))
            );
        @endif


        /*
        |--------------------------------------------------------------------------
        | ATTENTION
        |--------------------------------------------------------------------------
        */

        @if(session('warning'))
            showToast(
                'warning',
                'Attention',
                @json(session('warning'))
            );
        @endif

    });
</script>

    <!-- =============================================================
         CORRECTION AUTOMATIQUE DES CHAMPS PASSWORD
         ============================================================= -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
             * Recherche tous les champs password présents
             * dans la page actuelle.
             */
            const passwordInputs = document.querySelectorAll(
                'input[type="password"]'
            );

            passwordInputs.forEach(function (input) {

                /*
                 * On recherche un bouton œil déjà présent
                 * dans le même bloc.
                 */
                let container = input.parentElement;

                if (!container) {
                    return;
                }

                /*
                 * Si le bouton existe déjà mais qu'il est
                 * mal positionné, on réorganise son conteneur.
                 */
                let toggle = container.querySelector(
                    'button[onclick*="togglePassword"], ' +
                    'button[aria-label*="Afficher"], ' +
                    'button[aria-label*="Masquer"], ' +
                    '.password-toggle'
                );

                if (!toggle) {
                    return;
                }

                /*
                 * Le conteneur devient la référence
                 * pour le positionnement absolu.
                 */
                container.classList.add('password-field');

                /*
                 * Le bouton reçoit notre classe globale.
                 */
                toggle.classList.add('password-toggle');

                /*
                 * L'input doit laisser de la place à droite
                 * pour l'icône.
                 */
                input.style.paddingRight = '3rem';

                /*
                 * On s'assure que le bouton ne soit pas
                 * positionné en dehors du champ.
                 */
                toggle.style.position = 'absolute';
                toggle.style.top = '0';
                toggle.style.right = '0';
                toggle.style.bottom = '0';
                toggle.style.display = 'flex';
                toggle.style.alignItems = 'center';
                toggle.style.justifyContent = 'center';
                toggle.style.width = '3rem';
                toggle.style.zIndex = '10';
            });


            /*
             * Fonction globale disponible également pour
             * les boutons utilisant onclick="togglePassword(...)"
             */
            window.togglePassword = function (inputId, button) {

                const input = document.getElementById(inputId);

                if (!input) {
                    return;
                }

                if (input.type === 'password') {

                    input.type = 'text';

                    if (button) {
                        button.setAttribute(
                            'aria-label',
                            'Masquer le mot de passe'
                        );
                    }

                } else {

                    input.type = 'password';

                    if (button) {
                        button.setAttribute(
                            'aria-label',
                            'Afficher le mot de passe'
                        );
                    }
                }
            };

        });
    </script>
    {{-- =============================================================
         IZI TOAST
         ============================================================= --}}
    <script
        src="https://cdn.jsdelivr.net/npm/izitoast@1.4.0/dist/js/iziToast.min.js"
    ></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            @if(session('status') === 'profile-updated')
                iziToast.success({
                    title: 'Profil mis à jour',
                    message: 'Votre profil Generation PUSH a été mis à jour avec succès.',
                    position: 'topRight',
                    timeout: 5000,
                    close: true,
                    progressBar: true,
                    transitionIn: 'fadeInDown',
                    transitionOut: 'fadeOutUp',
                });
            @endif

            @if(session('profile_required'))
                iziToast.info({
                    title: 'Profil à compléter',
                    message: @json(session('profile_required')),
                    position: 'topRight',
                    timeout: 6000,
                    close: true,
                    progressBar: true,
                });
            @endif

            @if(session('success'))
                iziToast.success({
                    title: 'Succès',
                    message: @json(session('success')),
                    position: 'topRight',
                    timeout: 5000,
                    close: true,
                    progressBar: true,
                });
            @endif

            @if(session('error'))
                iziToast.error({
                    title: 'Erreur',
                    message: @json(session('error')),
                    position: 'topRight',
                    timeout: 6000,
                    close: true,
                    progressBar: true,
                });
            @endif

            @if(session('warning'))
                iziToast.warning({
                    title: 'Attention',
                    message: @json(session('warning')),
                    position: 'topRight',
                    timeout: 6000,
                    close: true,
                    progressBar: true,
                });
            @endif

        });
    </script>
</body>

</html>
