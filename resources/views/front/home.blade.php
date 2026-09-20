<x-layouts.public :title="$settings->meta_title ?? null" :meta-description="$settings->meta_description ?? null">

<!-- ============================================================
     HERO
     ============================================================ -->
<section
    id="generation-push-hero"
    class="relative h-screen min-h-[600px] w-full overflow-hidden bg-[#1A1A1A]"
>

    {{-- ========================================================
         VIDÉO / IMAGE DE FOND
         ======================================================== --}}
    @if ($settings->heroDirectVideoUrl())

        <video
            autoplay
            muted
            loop
            playsinline
            @if ($settings->heroPosterUrl())
                poster="{{ $settings->heroPosterUrl() }}"
            @endif
            class="absolute inset-0 w-full h-full object-cover"
        >
            <source
                src="{{ $settings->heroDirectVideoUrl() }}"
                type="video/mp4"
            >
        </video>

    @elseif ($settings->heroEmbedUrl())

        <iframe
            src="{{ $settings->heroEmbedUrl() }}"
            class="absolute inset-0 w-full h-full object-cover pointer-events-none"
            style="
                width: 100vw;
                height: 56.25vw;
                min-height: 100%;
                min-width: 177.77vh;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
            "
            frameborder="0"
            allow="autoplay; fullscreen"
        ></iframe>

    @elseif ($settings->heroPosterUrl())

        <img
            src="{{ $settings->heroPosterUrl() }}"
            class="absolute inset-0 w-full h-full object-cover"
            alt=""
        >

    @else

        <div
            class="absolute inset-0 bg-gradient-to-br from-[#1A1A1A] via-[#2A1810] to-accent/30"
        ></div>

    @endif


    {{-- ========================================================
         OVERLAY
         ======================================================== --}}
    <div
        class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/60"
    ></div>


    {{-- ========================================================
         CONTENU HERO
         ======================================================== --}}
    <div class="absolute inset-0 flex items-center justify-center px-4">

        <div class="max-w-4xl text-center">

            {{-- =================================================
                 SOUS-TITRE
                 ================================================= --}}
            @if ($settings->hero_subtitle)

                <p
                    class="inline-block px-4 py-1.5 rounded-full
                           bg-accent/20 border border-accent/40
                           text-accent-foreground
                           text-xs sm:text-sm
                           font-semibold tracking-wide uppercase
                           mb-6 backdrop-blur-sm"
                >
                    {{ $settings->hero_subtitle }}
                </p>

            @endif


            {{-- =================================================
                 TITRE ANIMÉ
                 ================================================= --}}
            <h1
                id="generation-push-hero-title"
                class="text-3xl sm:text-5xl md:text-6xl lg:text-6xl
                       font-extrabold leading-tight tracking-tight"
                style="color: #FFFFFF;"
            ></h1>


            {{-- =================================================
                 DESCRIPTION
                 ================================================= --}}
            @if ($settings->hero_description)

                <p
                    class="mt-6 text-base sm:text-lg md:text-xl
                           text-gray-200 max-w-2xl mx-auto"
                >
                    {{ $settings->hero_description }}
                </p>

            @endif


            {{-- =================================================
                 BOUTONS
                 ================================================= --}}
            <div
                class="mt-10 flex flex-col sm:flex-row
                       items-center justify-center gap-4"
            >

                <a
                    href="{{ $settings->hero_cta_url ?? route('register') }}"
                    class="w-full sm:w-auto
                           px-8 py-3.5
                           rounded-lg
                           bg-accent
                           text-white
                           font-semibold
                           hover:opacity-90
                           hover:scale-105
                           transition-all duration-200
                           shadow-lg shadow-accent/30"
                >
                    {{ $settings->hero_cta_label ?? 'Rejoindre la communauté' }}
                </a>


                <a
                    href="#a-propos"
                    class="w-full sm:w-auto
                           px-8 py-3.5
                           rounded-lg
                           border border-white/30
                           text-white
                           font-semibold
                           hover:bg-white/10
                           transition-all duration-200
                           backdrop-blur-sm"
                >
                    Découvrir
                </a>

            </div>

        </div>

    </div>


    {{-- ========================================================
         INDICATEUR DE SCROLL
         ======================================================== --}}
    <a
        href="#stats"
        class="absolute bottom-8 start-1/2
               -translate-x-1/2
               text-white/80
               hover:text-white
               transition-colors duration-200
               animate-bounce"
    >
        <x-icon name="chevron-down" class="w-8 h-8" />
    </a>

</section>


<!-- ============================================================
     ANIMATION DU TITRE HERO
     ============================================================ -->

<script>
document.addEventListener('DOMContentLoaded', function () {

    const heroSection = document.getElementById(
        'generation-push-hero'
    );

    const heroTitle = document.getElementById(
        'generation-push-hero-title'
    );


    /*
    |--------------------------------------------------------------------------
    | SÉCURITÉ
    |--------------------------------------------------------------------------
    */

    if (!heroSection || !heroTitle) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | TEXTES
    |--------------------------------------------------------------------------
    |
    | Le premier texte vient directement du back-office.
    |
    */

    const configuredText = @json(
        $settings->hero_title ?? 'Formons les leaders africains de demain'
    );


    /*
     * Texte automatique.
     */
    const defaultText =
        'Nous formons les leaders africains de demain';


    /*
    |--------------------------------------------------------------------------
    | COULEURS
    |--------------------------------------------------------------------------
    */

    const whiteColor = '#FFFFFF';

    const orangeColor = '#E8631A';


    /*
    |--------------------------------------------------------------------------
    | VITESSES
    |--------------------------------------------------------------------------
    */

    /*
     * Vitesse d'écriture.
     */
    const typingSpeed = 70;


    /*
     * Vitesse de suppression.
     */
    const deletingSpeed = 45;


    /*
     * Pause lorsque le texte est entièrement écrit.
     */
    const pauseAfterTyping = 1200;


    /*
     * Petite pause entre deux textes.
     */
    const pauseBetweenTexts = 400;


    /*
     |--------------------------------------------------------------------------
    | ÉTAT DE L'ANIMATION
    |--------------------------------------------------------------------------
    */

    let currentColor = whiteColor;

    let currentTimer = null;

    let animationRunning = false;

    let heroIsVisible = false;


    /*
    |--------------------------------------------------------------------------
    | ARRÊTER L'ANIMATION
    |--------------------------------------------------------------------------
    */

    function stopAnimation() {

        if (currentTimer !== null) {

            clearTimeout(currentTimer);

            currentTimer = null;
        }

        animationRunning = false;
    }


    /*
    |--------------------------------------------------------------------------
    | ÉCRIRE UN TEXTE
    |--------------------------------------------------------------------------
    */

    function typeText(text, callback) {

        let index = 0;


        /*
         * On vide complètement le titre.
         */
        heroTitle.textContent = '';


        /*
         * Applique la couleur actuelle.
         */
        heroTitle.style.color = currentColor;


        function write() {

            /*
             * Si le HERO n'est plus visible,
             * on arrête proprement.
             */
            if (!heroIsVisible) {

                stopAnimation();

                return;
            }


            /*
             * Tant qu'il reste des caractères.
             */
            if (index < text.length) {

                heroTitle.textContent += text.charAt(index);

                index++;


                currentTimer = setTimeout(
                    write,
                    typingSpeed
                );

            } else {

                /*
                 * Texte entièrement écrit.
                 *
                 * On attend avant de supprimer.
                 */
                currentTimer = setTimeout(
                    callback,
                    pauseAfterTyping
                );
            }
        }


        write();
    }


    /*
    |--------------------------------------------------------------------------
    | SUPPRIMER UN TEXTE
    |--------------------------------------------------------------------------
    */

    function deleteText(callback) {

        function erase() {

            /*
             * Si le HERO n'est plus visible,
             * on arrête.
             */
            if (!heroIsVisible) {

                stopAnimation();

                return;
            }


            const currentText =
                heroTitle.textContent;


            /*
             * Tant qu'il reste des caractères.
             */
            if (currentText.length > 0) {

                heroTitle.textContent =
                    currentText.substring(
                        0,
                        currentText.length - 1
                    );


                currentTimer = setTimeout(
                    erase,
                    deletingSpeed
                );

            } else {

                /*
                 * Petite pause avant le texte suivant.
                 */
                currentTimer = setTimeout(
                    callback,
                    pauseBetweenTexts
                );
            }
        }


        erase();
    }


    /*
    |--------------------------------------------------------------------------
    | TEXTE DU BACK-OFFICE
    |--------------------------------------------------------------------------
    */

    function playConfiguredText(callback) {

        typeText(
            configuredText,
            function () {

                deleteText(
                    callback
                );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TEXTE PAR DÉFAUT
    |--------------------------------------------------------------------------
    */

    function playDefaultText(callback) {

        typeText(
            defaultText,
            function () {

                deleteText(
                    callback
                );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SÉQUENCE BLANCHE
    |--------------------------------------------------------------------------
    |
    | 1. Texte back-office
    | 2. Texte par défaut
    | 3. Passage à l'orange
    |
    */

    function playWhiteSequence() {

        if (!heroIsVisible) {
            return;
        }


        currentColor = whiteColor;

        heroTitle.style.color = whiteColor;


        /*
         * TEXTE BACK-OFFICE
         */
        playConfiguredText(

            function () {

                if (!heroIsVisible) {
                    return;
                }


                /*
                 * TEXTE PAR DÉFAUT
                 */
                playDefaultText(

                    function () {

                        if (!heroIsVisible) {
                            return;
                        }


                        /*
                         * Passage à la séquence orange.
                         */
                        playOrangeSequence();

                    }
                );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SÉQUENCE ORANGE
    |--------------------------------------------------------------------------
    |
    | 1. Texte back-office
    | 2. Texte par défaut
    | 3. Retour au blanc
    |
    */

    function playOrangeSequence() {

        if (!heroIsVisible) {
            return;
        }


        currentColor = orangeColor;

        heroTitle.style.color = orangeColor;


        /*
         * TEXTE BACK-OFFICE EN ORANGE
         */
        playConfiguredText(

            function () {

                if (!heroIsVisible) {
                    return;
                }


                /*
                 * TEXTE PAR DÉFAUT EN ORANGE
                 */
                playDefaultText(

                    function () {

                        if (!heroIsVisible) {
                            return;
                        }


                        /*
                         * Retour à la première
                         * séquence blanche.
                         */
                        playWhiteSequence();

                    }
                );

            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | REDÉMARRER DEPUIS LE DÉBUT
    |--------------------------------------------------------------------------
    */

    function restartAnimation() {

        /*
         * Annule absolument tous les anciens timers.
         */
        stopAnimation();


        /*
         * Nettoyage complet du titre.
         */
        heroTitle.textContent = '';


        /*
         * Première couleur :
         * BLANC
         */
        currentColor = whiteColor;

        heroTitle.style.color = whiteColor;


        /*
         * L'animation peut démarrer.
         */
        animationRunning = true;


        /*
         * Première séquence :
         * BLANC
         */
        playWhiteSequence();
    }


    /*
    |--------------------------------------------------------------------------
    | INTERSECTION OBSERVER
    |--------------------------------------------------------------------------
    |
    | Le scroll ne contrôle PAS l'animation.
    |
    | Il sert uniquement à détecter :
    |
    | HERO visible
    |       ↓
    | démarrage / reprise depuis le début
    |
    | HERO invisible
    |       ↓
    | arrêt
    |
    */

    const observer =
        new IntersectionObserver(

            function (entries) {

                entries.forEach(function (entry) {

                    /*
                     * Le HERO vient d'entrer dans
                     * la zone visible.
                     */
                    if (entry.isIntersecting) {

                        /*
                         * On évite de redémarrer
                         * à chaque petit mouvement
                         * de scroll.
                         */
                        if (!heroIsVisible) {

                            heroIsVisible = true;

                            restartAnimation();
                        }

                    }

                    /*
                     * Le HERO vient de sortir
                     * de la zone visible.
                     */
                    else {

                        if (heroIsVisible) {

                            heroIsVisible = false;

                            stopAnimation();

                            /*
                             * On nettoie le titre.
                             */
                            heroTitle.textContent = '';

                            /*
                             * On prépare la prochaine
                             * apparition en blanc.
                             */
                            currentColor = whiteColor;

                            heroTitle.style.color =
                                whiteColor;
                        }
                    }

                });

            },

            {
                /*
                 * Le HERO est considéré visible
                 * dès qu'environ 35% est dans l'écran.
                 */
                threshold: 0.35
            }
        );


    /*
    |--------------------------------------------------------------------------
    | DÉMARRAGE DE LA SURVEILLANCE
    |--------------------------------------------------------------------------
    */

    observer.observe(heroSection);


    /*
    |--------------------------------------------------------------------------
    | NETTOYAGE SI LA PAGE EST QUITTÉE
    |--------------------------------------------------------------------------
    */

    window.addEventListener(
        'pagehide',
        function () {

            stopAnimation();

            observer.disconnect();

        }
    );

});
</script>





    <!-- ============ STATS ============ -->
    <section id="stats" class="bg-white py-14 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div x-data x-reveal.delay.0>
                <p class="text-3xl sm:text-4xl font-extrabold text-accent">{{ number_format($stats['members']) }}+</p>
                <p class="text-sm text-gray-500 mt-1">Membres actifs</p>
            </div>
            <div x-data x-reveal.delay.100>
                <p class="text-3xl sm:text-4xl font-extrabold text-accent">{{ number_format($stats['formations']) }}+</p>
                <p class="text-sm text-gray-500 mt-1">Formations</p>
            </div>
            <div x-data x-reveal.delay.200>
                <p class="text-3xl sm:text-4xl font-extrabold text-accent">{{ number_format($stats['events']) }}+</p>
                <p class="text-sm text-gray-500 mt-1">Événements</p>
            </div>
            <div x-data x-reveal.delay.300>
                <p class="text-3xl sm:text-4xl font-extrabold text-accent">{{ $stats['satisfaction'] }}%</p>
                <p class="text-sm text-gray-500 mt-1">Satisfaction</p>
            </div>
        </div>
    </section>

    <!-- ============ À PROPOS ============ -->
    <section id="a-propos" class="py-20 md:py-28 bg-white overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div x-data x-reveal="'left'">
                @if ($settings->aboutImageUrl())
                    <img src="{{ $settings->aboutImageUrl() }}" class="rounded-2xl shadow-xl w-full aspect-[4/3] object-cover">
                @else
                    <div class="rounded-2xl shadow-xl w-full aspect-[4/3] bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                        <div class="w-20 h-20 rounded-2xl bg-accent flex items-center justify-center text-white font-bold text-3xl">GP</div>
                    </div>
                @endif
            </div>
            <div x-data x-reveal="'right'">
                <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">À propos de nous</p>
                <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A] mb-6">{{ $settings->about_title ?? 'Une communauté panafricaine de leadership' }}</h2>
                <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $settings->about_text ?? "Generation PUSH accompagne des milliers de jeunes leaders à travers l'Afrique de l'Ouest à travers des formations, des conférences et un réseau de mentors engagés." }}</p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 mt-8 text-accent font-semibold hover:gap-3 transition-all duration-200">
                    En savoir plus <x-icon name="chevron-right" class="w-4 h-4" />
                </a>
            </div>
        </div>
    </section>

    <!-- ============ FORMATIONS ============ -->
    @if ($formations->isNotEmpty())
        <section class="py-20 md:py-28 bg-[#F8F9FA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                    <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Programmes</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Nos formations en cours</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($formations as $i => $formation)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden group" x-data x-reveal.delay.{{ $i * 100 }}>
                            <div class="aspect-video bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center group-hover:scale-105 transition-transform duration-500">
                                <x-icon name="book-open" class="w-10 h-10 text-accent/60" />
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-[#1A1A1A] mb-2 line-clamp-2">{{ $formation->name }}</h3>
                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-4">
                                    <span class="flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $formation->duration ?? '—' }}</span>
                                    <span class="flex items-center gap-1"><x-icon name="users" class="w-3.5 h-3.5" /> {{ $formation->participants }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-accent">{{ number_format($formation->price, 0) }} $</span>
                                    <a href="{{ route('register') }}" class="text-sm font-semibold text-accent hover:underline">S'inscrire →</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ============ ÉVÉNEMENTS ============ -->
    @if ($events->isNotEmpty())
        <section class="py-20 md:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                    <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Agenda</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Prochains événements</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($events as $i => $event)
                        <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 group" x-data x-reveal.delay.{{ $i * 100 }}>
                            <div class="aspect-video bg-gradient-to-br from-[#1A1A1A] to-accent/40 flex items-center justify-center relative overflow-hidden">
                                <x-icon name="zap" class="w-10 h-10 text-white/70 group-hover:scale-110 transition-transform duration-500" />
                                <span class="absolute top-3 start-3 px-2.5 py-1 rounded-lg bg-white text-[#1A1A1A] text-xs font-bold">
                                    {{ $event->date?->format('d M') }}
                                </span>
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-[#1A1A1A] mb-2 line-clamp-2">{{ $event->title }}</h3>
                                <p class="text-sm text-gray-500 flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $event->location }}, {{ $event->country }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ============ TÉMOIGNAGES ============ -->
    @if ($testimonials->isNotEmpty())
        <section class="py-20 md:py-28 bg-[#F8F9FA]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                    <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Témoignages</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Ce que dit notre communauté</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($testimonials->take(3) as $i => $testimonial)
                        <div class="bg-white rounded-2xl p-6 shadow-sm hover:shadow-lg transition-shadow duration-300" x-data x-reveal.delay.{{ $i * 100 }}>
                            <x-icon name="quote" class="w-8 h-8 text-accent/30 mb-4" />
                            <p class="text-gray-600 text-sm leading-relaxed mb-6">{{ $testimonial->content }}</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center overflow-hidden shrink-0">
                                    @if ($testimonial->photoUrl())
                                        <img src="{{ $testimonial->photoUrl() }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-accent font-semibold text-sm">{{ Str::substr($testimonial->author_name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-[#1A1A1A]">{{ $testimonial->author_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $testimonial->author_role }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

  <!-- ============ SPONSORS ============ -->
@if ($sponsors->isNotEmpty())
    <section class="py-16 bg-white border-y border-gray-100">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

           <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                    <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Sponsors</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Ils nous soutiennent</h2>
                </div>


            {{-- Logos --}}
            <div
                class="flex flex-wrap items-center justify-center gap-x-12 gap-y-10 sm:gap-x-16 sm:gap-y-12"
                x-data
                x-reveal
            >

                @foreach ($sponsors as $sponsor)

                    <div
                        class="flex items-center justify-center
                               min-w-[120px] min-h-[60px]
                               transition-transform duration-300
                               hover:scale-105"
                    >

                        @if ($sponsor->logoUrl())

                            <img
                                src="{{ $sponsor->logoUrl() }}"
                                alt="{{ $sponsor->name }}"
                                class="max-h-14 max-w-[180px] w-auto object-contain"
                                loading="lazy"
                            >

                        @else

                            <span
                                class="font-bold text-gray-500 text-lg"
                            >
                                {{ $sponsor->name }}
                            </span>

                        @endif

                    </div>

                @endforeach

            </div>

        </div>

    </section>
@endif
    <!-- ============ BLOG ============ -->
    @if ($posts->isNotEmpty())
        <section class="py-20 md:py-28 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-2xl mx-auto mb-14" x-data x-reveal>
                    <p class="text-accent font-semibold text-sm uppercase tracking-wide mb-3">Blog</p>
                    <h2 class="text-3xl sm:text-4xl font-bold text-[#1A1A1A]">Derniers articles</h2>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($posts as $i => $post)
                        <a href="#" class="group" x-data x-reveal.delay.{{ $i * 100 }}>
                            <div class="aspect-video rounded-2xl bg-gray-100 overflow-hidden mb-4">
                                @if ($post->coverImageUrl())
                                    <img src="{{ $post->coverImageUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-icon name="file-text" class="w-8 h-8 text-gray-300" />
                                    </div>
                                @endif
                            </div>
                            @if ($post->category)
                                <span class="text-xs font-semibold" style="color: {{ $post->category->color }}">{{ $post->category->name }}</span>
                            @endif
                            <h3 class="font-bold text-[#1A1A1A] mt-1 group-hover:text-accent transition-colors duration-200 line-clamp-2">{{ $post->title }}</h3>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- ============ NOUS REJOINDRE AUTREMENT ============ -->
<section class="py-20 md:py-28 bg-[#F8F9FA]">
   <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 text-center">
    <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-5">
        <x-icon name="award" class="w-6 h-6 text-accent" />
    </div>

    <h3 class="font-bold text-lg text-[#1A1A1A] mb-2">Devenir partenaire</h3>

    <p class="text-sm text-gray-600 mb-6">
        Entreprise, institution ou organisation : associe ta marque à une communauté de plus de 2500 jeunes leaders.
    </p>

    {{-- <a href="{{ route('front.engagement.create', ['type' => 'partner']) }}"
       class="inline-block px-6 py-3 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200">
        Devenir partenaire
    </a> --}}
    <a href="{{ route('front.partner') }}"
   class="inline-block px-6 py-3 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200">
    Devenir partenaire
</a>
</div>

<div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-xl transition-shadow duration-300 text-center"
     x-data
     x-reveal.delay.100>
    <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center mx-auto mb-5">
        <x-icon name="users-round" class="w-6 h-6 text-accent" />
    </div>

    <h3 class="font-bold text-lg text-[#1A1A1A] mb-2">Devenir bénévole</h3>

    <p class="text-sm text-gray-600 mb-6">
        Donne de ton temps et de tes compétences pour accompagner la prochaine génération de leaders.
    </p>

    {{-- <a href="{{ route('front.engagement.create', ['type' => 'volunteer']) }}"
       class="inline-block px-6 py-3 rounded-lg border-2 border-accent text-accent font-semibold hover:bg-accent hover:text-white transition-all duration-200">
        Devenir bénévole
    </a> --}}
    <a href="{{ route('front.volunteer') }}"
   class="inline-block px-6 py-3 rounded-lg border-2 border-accent text-accent font-semibold hover:bg-accent hover:text-white transition-all duration-200">
    Devenir bénévole
</a>
</div>
</section>

    <!-- ============ NEWSLETTER ============ -->
    <section class="py-20 bg-[#1A1A1A]">
        <div class="max-w-3xl mx-auto px-4 text-center" x-data x-reveal="'zoom'">
            <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">{{ $settings->newsletter_title ?? 'Reste informé' }}</h2>
            <p class="text-gray-400 mb-8">{{ $settings->newsletter_text ?? 'Reçois nos actualités, formations et événements directement par email.' }}</p>

            @if (session('success'))
                <p class="text-green-400 text-sm mb-4">{{ session('success') }}</p>
            @endif

            <form method="POST" action="{{ route('front.newsletter.store') }}" class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                @csrf
                <input type="email" name="email" required placeholder="ton@email.com" class="flex-1 px-4 py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder:text-gray-500 focus:outline-none focus:ring-2 focus:ring-accent">
                <button type="submit" class="px-6 py-3 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200 cursor-pointer">S'abonner</button>
            </form>
            @error('email')
                <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
            @enderror
        </div>
    </section>

</x-layouts.public>


