@php
    /*
    |--------------------------------------------------------------------------
    | TYPE DE CANDIDATURE
    |--------------------------------------------------------------------------
    */

    $type = $type ?? request()->route('type');

    $isVolunteer = $type === 'volunteer';
    $isPartner = $type === 'partner';


    /*
    |--------------------------------------------------------------------------
    | INFORMATIONS DE LA PERSONNE
    |--------------------------------------------------------------------------
    */

    $personName = request('name')
        ?? request('contact_name')
        ?? request('organization')
        ?? 'cher membre';


    /*
    |--------------------------------------------------------------------------
    | CONTENU PERSONNALISÉ
    |--------------------------------------------------------------------------
    */

    if ($isVolunteer) {

        $title = 'Merci ' . $personName . ' !';

        $subtitle = 'Votre candidature pour rejoindre Generation PUSH en tant que bénévole a bien été reçue.';

        $message = 'Nous vous remercions sincèrement pour votre intérêt, votre disponibilité et votre volonté de contribuer aux actions de Generation PUSH.';

        $nextStep = 'Notre équipe va prendre le temps d’examiner votre candidature. Nous vous contacterons dans les prochaines 24 heures afin d’échanger avec vous et de vous présenter les prochaines étapes.';

        $icon = 'fa-hands-helping';

        $label = 'Candidature bénévole';

    } elseif ($isPartner) {

        $title = 'Merci ' . $personName . ' !';

        $subtitle = 'Votre demande de partenariat avec Generation PUSH a bien été reçue.';

        $message = 'Nous vous remercions pour l’intérêt que vous portez à Generation PUSH et pour votre volonté de construire avec nous des initiatives porteuses d’impact.';

        $nextStep = 'Notre équipe va étudier votre demande avec attention et vous contactera dans les prochaines 24 heures afin d’échanger sur votre organisation, votre projet et les possibilités de collaboration.';

        $icon = 'fa-handshake';

        $label = 'Demande de partenariat';

    } else {

        $title = 'Merci ' . $personName . ' !';

        $subtitle = 'Votre demande a bien été reçue par Generation PUSH.';

        $message = 'Nous vous remercions pour votre intérêt envers Generation PUSH.';

        $nextStep = 'Notre équipe va examiner votre demande et vous contactera dans les prochaines 24 heures.';

        $icon = 'fa-check-circle';

        $label = 'Demande reçue';
    }
@endphp


<x-layouts.public :title="$title . ' — Generation PUSH'">

    {{-- ============================================================
         CONFIRMATION
    ============================================================= --}}

    <section class="relative min-h-[75vh] overflow-hidden bg-gray-50 py-16 md:py-24">

        {{-- Décorations --}}
        <div class="pointer-events-none absolute inset-0 overflow-hidden">
            <div class="absolute -right-32 -top-32 h-80 w-80 rounded-full bg-primary/10 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-32 h-80 w-80 rounded-full bg-primary/10 blur-3xl"></div>
        </div>


        <div class="relative z-10 mx-auto w-full max-w-3xl px-6">

            <div class="overflow-hidden rounded-3xl border border-gray-100 bg-white shadow-xl">

                {{-- ====================================================
                     EN-TÊTE
                ===================================================== --}}

                <div class="px-6 pb-8 pt-12 text-center md:px-12">

                    {{-- Icône --}}
                    <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-primary/10">
                        <i class="fas {{ $icon }} text-3xl text-primary"></i>
                    </div>


                    {{-- Badge --}}
                    <div class="mb-5 inline-flex items-center gap-2 rounded-full bg-primary/10 px-4 py-2 text-sm font-semibold text-primary">
                        <i class="fas fa-check-circle"></i>
                        {{ $label }}
                    </div>


                    {{-- Titre --}}
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 md:text-4xl">
                        {{ $title }}
                    </h1>


                    {{-- Sous-titre --}}
                    <p class="mx-auto mt-5 max-w-2xl text-lg leading-8 text-gray-600">
                        {{ $subtitle }}
                    </p>

                </div>


                {{-- ====================================================
                     MESSAGE
                ===================================================== --}}

                <div class="border-t border-gray-100 px-6 py-8 md:px-12">

                    <p class="text-base leading-8 text-gray-700">
                        {{ $message }}
                    </p>


                    {{-- Prochaine étape --}}
                    <div class="mt-8 rounded-2xl bg-gray-50 p-6">

                        <div class="flex gap-4">

                            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10">
                                <i class="fas fa-clock text-primary"></i>
                            </div>

                            <div>

                                <h2 class="font-semibold text-gray-900">
                                    Et maintenant ?
                                </h2>

                                <p class="mt-2 leading-7 text-gray-600">
                                    {{ $nextStep }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- ====================================================
                         CONTACT SOUS 24H
                    ===================================================== --}}

                    <div class="mt-6 flex items-center justify-center gap-3 rounded-xl border border-primary/20 bg-primary/5 px-5 py-4 text-center">

                        <i class="fas fa-phone-alt text-primary"></i>

                        <span class="text-sm font-medium text-gray-700">
                            Notre équipe vous contactera dans les
                            <strong class="text-primary">24 heures</strong>.
                        </span>

                    </div>

                </div>


                {{-- ====================================================
                     BAS DE PAGE
                ===================================================== --}}

                <div class="border-t border-gray-100 bg-gray-50 px-6 py-7 md:px-12">

                    <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">

                        <p class="text-sm text-gray-500">
                            Merci de votre confiance envers
                            <strong class="text-gray-700">
                                Generation PUSH
                            </strong>.
                        </p>


                        <a href="{{ route('front.home') }}"
                           class="inline-flex items-center gap-2 rounded-xl bg-primary px-5 py-3 text-sm font-semibold text-white transition hover:opacity-90">

                            <i class="fas fa-home"></i>

                            Retour à l'accueil

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </section>

</x-layouts.public>
