/* ================================================================
   GENERATION PUSH — FRONT OFFICE
   Global JavaScript

   Bootstrap 5
   Swiper
   AOS
   iziToast
================================================================ */

'use strict';


/* ================================================================
   GENERATION PUSH APP
================================================================ */

const GenerationPush = {

    /* ------------------------------------------------------------
       INITIALISATION
    ------------------------------------------------------------ */



    init() {

        this.initPageLoader();

        this.initHeader();

        this.initScrollProgress();

        this.initSmoothScroll();

        this.initAOS();

        this.initTooltips();

        this.initHeroGreeting();

        this.initHeroTyping();

        this.initCounters();

        this.initPushEventsSlider();

        this.initEventsSlider();

        this.initTestimonialsSlider();

        this.initSponsorsSlider();

        this.initBlogSlider();

        this.initBackToTop();

        this.initForms();

        this.initCustomReveal();

        this.initMobileMenu();

        this.initParallax();

        this.initAgendaSlider();

    },


    /* ============================================================
       01. PAGE LOADER
    ============================================================ */

    initPageLoader() {

        const loader =
            document.querySelector('#gp-page-loader');

        if (!loader) {
            return;
        }

        window.addEventListener('load', () => {

            setTimeout(() => {

                loader.classList.add('gp-loaded');

                setTimeout(() => {

                    if (loader.parentNode) {
                        loader.remove();
                    }

                }, 700);

            }, 250);

        });

    },


    /* ============================================================
       02. HEADER
    ============================================================ */

    initHeader() {

        const header =
            document.querySelector('#gp-header');

        if (!header) {
            return;
        }


        let lastScrollY =
            window.scrollY;


        const updateHeader = () => {

            const currentScroll =
                window.scrollY;


            /*
             * Navbar blanche après scroll
             */
            if (currentScroll > 40) {

                header.classList.add(
                    'gp-header-scrolled'
                );

            } else {

                header.classList.remove(
                    'gp-header-scrolled'
                );

            }


            /*
             * Petit effet de navigation fluide.
             * On ne masque pas totalement la navbar.
             */
            if (
                currentScroll > lastScrollY &&
                currentScroll > 250
            ) {

                header.style.transform =
                    'translateY(-10px)';

            } else {

                header.style.transform =
                    'translateY(0)';

            }


            lastScrollY =
                currentScroll;

        };


        window.addEventListener(
            'scroll',
            updateHeader,
            {
                passive: true
            }
        );


        updateHeader();

    },


    /* ============================================================
       03. PROGRESSION DE LA PAGE
    ============================================================ */

    initScrollProgress() {

        const progress =
            document.querySelector(
                '#gp-scroll-progress'
            );

        if (!progress) {
            return;
        }


        const updateProgress = () => {

            const scrollTop =
                window.scrollY;

            const documentHeight =
                document.documentElement.scrollHeight -
                window.innerHeight;


            if (documentHeight <= 0) {
                return;
            }


            const percentage =
                (
                    scrollTop /
                    documentHeight
                ) * 100;


            progress.style.width =
                `${percentage}%`;

        };


        window.addEventListener(
            'scroll',
            updateProgress,
            {
                passive: true
            }
        );


        updateProgress();

    },


    /* ============================================================
       04. SMOOTH SCROLL
    ============================================================ */

    initSmoothScroll() {

        const links =
            document.querySelectorAll(
                'a[href^="#"]'
            );


        links.forEach(link => {

            link.addEventListener(
                'click',
                event => {

                    const href =
                        link.getAttribute('href');


                    if (
                        !href ||
                        href === '#'
                    ) {
                        return;
                    }


                    const target =
                        document.querySelector(href);


                    if (!target) {
                        return;
                    }


                    event.preventDefault();


                    const header =
                        document.querySelector(
                            '#gp-header'
                        );


                    const headerHeight =
                        header
                            ? header.offsetHeight
                            : 0;


                    const targetPosition =
                        target.getBoundingClientRect().top +
                        window.scrollY -
                        headerHeight;


                    window.scrollTo({

                        top: targetPosition,

                        behavior: 'smooth'

                    });

                }
            );

        });

    },


    /* ============================================================
       05. AOS
    ============================================================ */

    initAOS() {

        if (typeof AOS === 'undefined') {
            return;
        }


        AOS.init({

            duration: 850,

            easing:
                'cubic-bezier(0.22, 1, 0.36, 1)',

            once: true,

            offset: 70,

            delay: 0,

            anchorPlacement:
                'top-bottom',

            disable: false

        });

    },


    /* ============================================================
       06. BOOTSTRAP TOOLTIPS
    ============================================================ */

    initTooltips() {

        if (
            typeof bootstrap ===
            'undefined'
        ) {
            return;
        }


        const tooltipElements =
            document.querySelectorAll(
                '[data-bs-toggle="tooltip"]'
            );


        tooltipElements.forEach(
            element => {

                new bootstrap.Tooltip(
                    element
                );

            }
        );

    },


    /* ============================================================
       07. HERO — BONJOUR / BONSOIR
    ============================================================ */

    initHeroGreeting() {

        const greeting =
            document.querySelector(
                '#gpGreeting'
            );


        if (!greeting) {
            return;
        }


        const hour =
            new Date().getHours();


        let message =
            'Bienvenue';


        if (
            hour >= 5 &&
            hour < 12
        ) {

            message =
                'Bonjour';

        } else if (
            hour >= 12 &&
            hour < 18
        ) {

            message =
                'Bon après-midi';

        } else {

            message =
                'Bonsoir';

        }


        greeting.textContent =
            message;

    },






    /* ============================================================
       08. HERO — MACHINE À ÉCRIRE

       Cycle :
       blanc
       écriture
       pause
       effacement
       orange
       écriture
       pause
       effacement
       blanc
       ...
    ============================================================ */

    initHeroTyping() {

        const element =
            document.querySelector(
                '[data-gp-typing]'
            );


        if (!element) {
            return;
        }


        const text =
            element.dataset.gpText ||
            'PUSH YOU';


        const colors = [

            '#FFFFFF',

            '#E8631A'

        ];


        let colorIndex = 0;

        let characterIndex = 0;

        let deleting = false;


        /*
         * VITESSES
         */

        const typingSpeed = 110;

        const deletingSpeed = 65;

        const pauseCompleted = 1700;

        const pauseEmpty = 450;


        /*
         * Rendu
         */

        const render = () => {

            element.textContent =
                text.substring(
                    0,
                    characterIndex
                );


            element.style.color =
                colors[colorIndex];

        };


        /*
         * Machine
         */

        const animate = () => {


            /* -------------------------
               ÉCRITURE
            ------------------------- */

            if (!deleting) {

                characterIndex++;

                render();


                if (
                    characterIndex >=
                    text.length
                ) {

                    characterIndex =
                        text.length;

                    deleting = true;


                    setTimeout(
                        animate,
                        pauseCompleted
                    );


                    return;
                }


                setTimeout(
                    animate,
                    typingSpeed
                );


                return;
            }



            /* -------------------------
               EFFACEMENT
            ------------------------- */

            characterIndex--;

            render();


            if (
                characterIndex <= 0
            ) {

                characterIndex = 0;

                deleting = false;


                /*
                 * changement couleur
                 */

                colorIndex =
                    (
                        colorIndex + 1
                    )
                    % colors.length;


                setTimeout(
                    animate,
                    pauseEmpty
                );


                return;
            }


            setTimeout(
                animate,
                deletingSpeed
            );

        };


        /*
         * Petit délai au chargement
         */

        setTimeout(
            animate,
            600
        );

    },


    /* ============================================================
       09. COMPTEURS
    ============================================================ */

    initCounters() {

        const counters =
            document.querySelectorAll(
                '[data-gp-counter]'
            );


        if (!counters.length) {
            return;
        }


        const animateCounter =
            counter => {


                if (
                    counter.dataset.gpAnimated ===
                    'true'
                ) {
                    return;
                }


                counter.dataset.gpAnimated =
                    'true';


                const target =
                    parseFloat(
                        counter.dataset.gpCounter
                    ) || 0;


                const suffix =
                    counter.dataset.gpSuffix ||
                    '';


                const duration =
                    1600;


                const startTime =
                    performance.now();


                const update =
                    currentTime => {


                        const elapsed =
                            currentTime -
                            startTime;


                        const progress =
                            Math.min(
                                elapsed /
                                duration,
                                1
                            );


                        /*
                         * easing
                         */

                        const eased =
                            1 -
                            Math.pow(
                                1 - progress,
                                4
                            );


                        const current =
                            Math.round(
                                target *
                                eased
                            );


                        counter.textContent =
                            `${current}${suffix}`;


                        if (
                            progress < 1
                        ) {

                            requestAnimationFrame(
                                update
                            );

                        }

                    };


                requestAnimationFrame(
                    update
                );

            };


        /*
         * Déclenchement uniquement
         * quand les statistiques
         * deviennent visibles.
         */

        if (
            'IntersectionObserver'
            in window
        ) {

            const observer =
                new IntersectionObserver(

                    entries => {

                        entries.forEach(
                            entry => {

                                if (
                                    entry.isIntersecting
                                ) {

                                    animateCounter(
                                        entry.target
                                    );

                                    observer.unobserve(
                                        entry.target
                                    );

                                }

                            }
                        );

                    },

                    {
                        threshold: 0.45
                    }

                );


            counters.forEach(
                counter => {

                    observer.observe(
                        counter
                    );

                }
            );


        } else {

            counters.forEach(
                animateCounter
            );

        }

    },


    /* ============================================================
       10. FORMATIONS SLIDER

       AUTO
       MANUEL
       SOURIS
       SWIPE
       PAUSE AU SURVOL
    ============================================================ */



    /* ============================================================
       11. EVENTS SLIDER

       AUTO + MANUEL
    ============================================================ */

    initPushEventsSlider() {

        const slider =
            document.querySelector(
                '.gp-push-events-swiper'
            );


        if (
            !slider ||
            typeof Swiper ===
            'undefined'
        ) {
            return;
        }


        const realSlides =
            slider.querySelectorAll(
                '.swiper-slide'
            ).length;


        /*
         * Active la boucle infinie à partir
         * de deux événements.
         */
        const infiniteLoop =
            realSlides >= 2;


        const swiper =
            new Swiper(
                slider,
                {

                    slidesPerView: 2,

                    spaceBetween: 18,

                    loop: infiniteLoop,

                    loopAdditionalSlides: 4,

                    speed: 850,

                    autoplay: infiniteLoop
                        ? {

                            delay: 3000,

                            disableOnInteraction:
                                false,

                            pauseOnMouseEnter:
                                true

                        }
                        : false,

                    navigation: {

                        nextEl:
                            '.gp-push-event-next',

                        prevEl:
                            '.gp-push-event-prev'

                    },

                    pagination: {

                        el:
                            '.gp-push-event-pagination',

                        clickable: true,

                        dynamicBullets: true

                    },

                    grabCursor: true,

                    simulateTouch: true,

                    allowTouchMove: true,

                    resistance: true,

                    resistanceRatio: .7,

                    watchSlidesProgress: true,

                    observer: true,

                    observeParents: true,

                    breakpoints: {

                        0: {

                            slidesPerView: 1.08,

                            spaceBetween: 12

                        },

                        576: {

                            slidesPerView: 1.25,

                            spaceBetween: 15

                        },

                        768: {

                            slidesPerView: 2,

                            spaceBetween: 16

                        },

                        992: {

                            slidesPerView: 2,

                            spaceBetween: 18

                        }

                    }

                }
            );


        swiper.on(
            'touchEnd',
            () => {

                if (
                    swiper.autoplay &&
                    infiniteLoop
                ) {

                    swiper.autoplay.start();

                }

            }
        );


        swiper.on(
            'navigationNext',
            () => {

                if (
                    swiper.autoplay &&
                    infiniteLoop
                ) {

                    swiper.autoplay.start();

                }

            }
        );


        swiper.on(
            'navigationPrev',
            () => {

                if (
                    swiper.autoplay &&
                    infiniteLoop
                ) {

                    swiper.autoplay.start();

                }

            }
        );

    },


    initEventsSlider() {

        const slider =
            document.querySelector(
                '.gp-events-swiper'
            );


        if (
            !slider ||
            typeof Swiper ===
            'undefined'
        ) {
            return;
        }


        new Swiper(
            slider,
            {

                slidesPerView: 1.05,

                spaceBetween: 18,

                speed: 900,

                grabCursor: true,

                watchOverflow: true,

                autoplay: {

                    delay: 5200,

                    disableOnInteraction:
                        false,

                    pauseOnMouseEnter:
                        true

                },

                navigation: {

                    nextEl:
                        '.gp-events-next',

                    prevEl:
                        '.gp-events-prev'

                },

                pagination: {

                    el:
                        '.gp-events-pagination',

                    clickable: true

                },

                breakpoints: {

                    768: {

                        slidesPerView: 1.35,

                        spaceBetween: 25

                    },

                    992: {

                        slidesPerView: 1.7,

                        spaceBetween: 28

                    },

                    1200: {

                        slidesPerView: 2,

                        spaceBetween: 30

                    }

                }

            }
        );

    },


    /* ============================================================
       12. TESTIMONIALS SLIDER

       Carte centrale mise en avant
       Auto + manuel
    ============================================================ */

    initTestimonialsSlider() {

        const slider =
            document.querySelector(
                '.gp-testimonials-swiper'
            );


        if (
            !slider ||
            typeof Swiper ===
            'undefined'
        ) {
            return;
        }


        const swiper =
            new Swiper(
                slider,
                {

                    slidesPerView: 1.08,

                    spaceBetween: 18,

                    speed: 900,

                    grabCursor: true,

                    centeredSlides: false,

                    watchOverflow: true,

                    autoplay: {

                        delay: 5000,

                        disableOnInteraction:
                            false,

                        pauseOnMouseEnter:
                            true

                    },

                    navigation: {

                        nextEl:
                            '.gp-testimonials-next',

                        prevEl:
                            '.gp-testimonials-prev'

                    },

                    pagination: {

                        el:
                            '.gp-testimonials-pagination',

                        clickable: true

                    },

                    breakpoints: {

                        576: {

                            slidesPerView: 1.3,

                            spaceBetween: 20

                        },

                        768: {

                            slidesPerView: 2,

                            spaceBetween: 25

                        },

                        1200: {

                            slidesPerView: 2.6,

                            spaceBetween: 30

                        }

                    }

                }
            );


        /*
         * Petite classe permettant
         * éventuellement d'accentuer
         * la slide active via CSS.
         */

        const updateActive =
            () => {

                swiper.slides.forEach(
                    slide => {

                        slide.classList.remove(
                            'gp-testimonial-active'
                        );

                    }
                );


                if (
                    swiper.slides[
                    swiper.activeIndex
                    ]
                ) {

                    swiper.slides[
                        swiper.activeIndex
                    ].classList.add(
                        'gp-testimonial-active'
                    );

                }

            };


        swiper.on(
            'slideChange',
            updateActive
        );


        updateActive();

    },


    /* ============================================================
       13. SPONSORS

       DÉFILEMENT CONTINU
       INFINI
       SOURIS / TOUCH
    ============================================================ */

    initSponsorsSlider() {

        const slider =
            document.querySelector(
                '.gp-sponsors-swiper'
            );


        if (
            !slider ||
            typeof Swiper ===
            'undefined'
        ) {
            return;
        }


        const slides =
            slider.querySelectorAll(
                '.swiper-slide'
            );


        /*
         * S'il n'y a qu'un sponsor,
         * inutile de faire un loop.
         */

        const canLoop =
            slides.length > 3;


        new Swiper(
            slider,
            {

                slidesPerView: 2.2,

                spaceBetween: 20,

                speed: 5000,

                loop: canLoop,

                allowTouchMove: true,

                grabCursor: true,

                freeMode: {

                    enabled: true,

                    momentum: false

                },

                autoplay: canLoop
                    ? {

                        delay: 0,

                        disableOnInteraction:
                            false,

                        pauseOnMouseEnter:
                            true

                    }
                    : false,

                breakpoints: {

                    576: {

                        slidesPerView: 3,

                        spaceBetween: 30

                    },

                    768: {

                        slidesPerView: 4,

                        spaceBetween: 35

                    },

                    992: {

                        slidesPerView: 5,

                        spaceBetween: 45

                    },

                    1200: {

                        slidesPerView: 6,

                        spaceBetween: 55

                    }

                }

            }
        );

    },


    /* ============================================================
       14. BLOG

       MANUEL principalement.
       Pas d'autoplay pour laisser
       le visiteur lire tranquillement.
    ============================================================ */

    initBlogSlider() {

        const slider =
            document.querySelector(
                '.gp-blog-swiper'
            );


        if (
            !slider ||
            typeof Swiper ===
            'undefined'
        ) {
            return;
        }


        new Swiper(
            slider,
            {

                slidesPerView: 1.08,

                spaceBetween: 18,

                speed: 800,

                grabCursor: true,

                watchOverflow: true,

                keyboard: {
                    enabled: true
                },

                navigation: {

                    nextEl:
                        '.gp-blog-next',

                    prevEl:
                        '.gp-blog-prev'

                },

                pagination: {

                    el:
                        '.gp-blog-pagination',

                    clickable: true

                },

                breakpoints: {

                    576: {

                        slidesPerView: 1.4,

                        spaceBetween: 20

                    },

                    768: {

                        slidesPerView: 2,

                        spaceBetween: 25

                    },

                    1200: {

                        slidesPerView: 3,

                        spaceBetween: 30

                    }

                }

            }
        );

    },


    /* ============================================================
       AGENDA GENERATION PUSH
    ============================================================ */

    initAgendaSlider() {

        const slider =
            document.querySelector('.gp-agenda-swiper');

        if (
            !slider ||
            typeof Swiper === 'undefined'
        ) {
            return;
        }


        const wrapper =
            slider.querySelector('.swiper-wrapper');


        let slides =
            Array.from(
                wrapper.querySelectorAll(
                    ':scope > .swiper-slide'
                )
            );


        const realTotal = slides.length;


        /*
        |--------------------------------------------------------------------------
        | AUCUN ÉVÉNEMENT
        |--------------------------------------------------------------------------
        */

        if (realTotal === 0) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | EXACTEMENT 2 ÉVÉNEMENTS
        |--------------------------------------------------------------------------
        |
        | Pour obtenir :
        |
        | A B
        | B A
        | A B
        | B A
        |
        | avec 2 cartes visibles, on crée une copie technique.
        |
        | Le visiteur ne voit pas qu'elles sont dupliquées.
        |--------------------------------------------------------------------------
        */

        if (realTotal === 2) {

            slides.forEach((slide) => {

                const clone =
                    slide.cloneNode(true);

                clone.classList.add(
                    'gp-agenda-technical-clone'
                );

                wrapper.appendChild(clone);

            });

        }


        /*
        |--------------------------------------------------------------------------
        | ON RECALCULE
        |--------------------------------------------------------------------------
        */

        slides =
            Array.from(
                wrapper.querySelectorAll(
                    ':scope > .swiper-slide'
                )
            );


        const totalSlides =
            slides.length;


        const canSlide =
            totalSlides > 1;


        /*
        |--------------------------------------------------------------------------
        | SWIPER
        |--------------------------------------------------------------------------
        */

        const agendaSwiper =
            new Swiper(
                slider,
                {

                    speed: 900,

                    loop: canSlide,

                    loopAdditionalSlides: 4,

                    watchSlidesProgress: true,

                    observer: true,

                    observeParents: true,


                    /*
                    |--------------------------------------------------------------------------
                    | AUTOPLAY — 3 SECONDES
                    |--------------------------------------------------------------------------
                    */

                    autoplay: canSlide
                        ? {

                            delay: 3000,

                            disableOnInteraction: false,

                            pauseOnMouseEnter: true,

                            waitForTransition: true

                        }
                        : false,


                    /*
                    |--------------------------------------------------------------------------
                    | NAVIGATION
                    |--------------------------------------------------------------------------
                    */

                    navigation: {

                        prevEl:
                            '.gp-agenda-prev',

                        nextEl:
                            '.gp-agenda-next'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | PAGINATION
                    |--------------------------------------------------------------------------
                    */

                    pagination: {

                        el:
                            '.gp-agenda-pagination',

                        clickable: true,

                        dynamicBullets: true

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | MANUEL SOURIS / TACTILE
                    |--------------------------------------------------------------------------
                    */

                    grabCursor: canSlide,

                    simulateTouch: true,

                    allowTouchMove: canSlide,

                    touchRatio: 1,

                    resistance: true,

                    resistanceRatio: .75,


                    /*
                    |--------------------------------------------------------------------------
                    | MOBILE FIRST
                    |--------------------------------------------------------------------------
                    */

                    slidesPerView: 1.08,

                    slidesPerGroup: 1,

                    spaceBetween: 14,


                    /*
                    |--------------------------------------------------------------------------
                    | RESPONSIVE
                    |--------------------------------------------------------------------------
                    */

                    breakpoints: {

                        576: {

                            slidesPerView: 1.25,

                            spaceBetween: 16

                        },


                        768: {

                            slidesPerView: 1.6,

                            spaceBetween: 18

                        },


                        992: {

                            slidesPerView: 2,

                            slidesPerGroup: 1,

                            spaceBetween: 22

                        },


                        1400: {

                            slidesPerView: 2,

                            slidesPerGroup: 1,

                            spaceBetween: 26

                        }

                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | CONTINUER APRÈS NAVIGATION MANUELLE
        |--------------------------------------------------------------------------
        */

        const restartAutoplay = () => {

            if (
                agendaSwiper.autoplay &&
                canSlide
            ) {

                agendaSwiper.autoplay.start();

            }

        };


        agendaSwiper.on(
            'touchEnd',
            restartAutoplay
        );


        agendaSwiper.on(
            'navigationNext',
            restartAutoplay
        );


        agendaSwiper.on(
            'navigationPrev',
            restartAutoplay
        );

    },


    /* ============================================================
       15. BACK TO TOP
    ============================================================ */

    initBackToTop() {

        const button =
            document.querySelector(
                '#gp-back-to-top'
            );


        if (!button) {
            return;
        }


        const updateButton = () => {

            if (
                window.scrollY > 700
            ) {

                button.classList.add(
                    'gp-show'
                );

            } else {

                button.classList.remove(
                    'gp-show'
                );

            }

        };


        window.addEventListener(
            'scroll',
            updateButton,
            {
                passive: true
            }
        );


        button.addEventListener(
            'click',
            () => {

                window.scrollTo({

                    top: 0,

                    behavior: 'smooth'

                });

            }
        );


        updateButton();

    },


    /* ============================================================
       16. FORMULAIRES
    ============================================================ */

    initForms() {

        const forms =
            document.querySelectorAll(
                '[data-gp-form]'
            );


        forms.forEach(
            form => {

                form.addEventListener(
                    'submit',
                    () => {

                        const button =
                            form.querySelector(
                                'button[type="submit"]'
                            );


                        if (!button) {
                            return;
                        }


                        const loadingText =
                            button.dataset.loadingText;


                        if (
                            loadingText
                        ) {

                            /*
                             * On mémorise le contenu
                             * avant changement.
                             */

                            button.dataset.originalHtml =
                                button.innerHTML;


                            button.innerHTML = `

                                <span
                                    class="spinner-border
                                           spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>

                                ${loadingText}

                            `;

                        }


                        button.disabled =
                            true;

                    }
                );

            }
        );

    },


    /* ============================================================
       17. REVEAL PERSONNALISÉ

       Pour les éléments qui utilisent :
       .gp-reveal
       .gp-reveal-left
       .gp-reveal-right
    ============================================================ */

    initCustomReveal() {

        const elements =
            document.querySelectorAll(
                '.gp-reveal, ' +
                '.gp-reveal-left, ' +
                '.gp-reveal-right'
            );


        if (!elements.length) {
            return;
        }


        if (
            !(
                'IntersectionObserver'
                in window
            )
        ) {

            elements.forEach(
                element => {

                    element.classList.add(
                        'gp-visible'
                    );

                }
            );


            return;
        }


        const observer =
            new IntersectionObserver(

                entries => {

                    entries.forEach(
                        entry => {

                            if (
                                entry.isIntersecting
                            ) {

                                entry.target
                                    .classList
                                    .add(
                                        'gp-visible'
                                    );


                                observer.unobserve(
                                    entry.target
                                );

                            }

                        }
                    );

                },

                {

                    threshold: 0.15,

                    rootMargin:
                        '0px 0px -40px 0px'

                }

            );


        elements.forEach(
            element => {

                observer.observe(
                    element
                );

            }
        );

    },


    /* ============================================================
       18. MENU MOBILE
    ============================================================ */

    initMobileMenu() {

        const mobileMenu =
            document.querySelector(
                '#gpMobileMenu'
            );


        if (
            !mobileMenu ||
            typeof bootstrap ===
            'undefined'
        ) {
            return;
        }


        const links =
            mobileMenu.querySelectorAll(
                'a'
            );


        links.forEach(
            link => {

                link.addEventListener(
                    'click',
                    () => {

                        const instance =
                            bootstrap
                                .Offcanvas
                                .getInstance(
                                    mobileMenu
                                );


                        if (instance) {

                            instance.hide();

                        }

                    }
                );

            }
        );

    },


    /* ============================================================
       19. PARALLAX LÉGER

       Très léger pour garder
       le site fluide.
    ============================================================ */

    initParallax() {

        /*
         * Pas de parallax sur téléphone.
         */

        if (
            window.innerWidth < 992
        ) {
            return;
        }


        /*
         * Respecte les préférences
         * d'accessibilité.
         */

        if (
            window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches
        ) {
            return;
        }


        const heroMedia =
            document.querySelector(
                '.gp-home-hero-media'
            );


        if (!heroMedia) {
            return;
        }


        let ticking = false;


        const updateParallax =
            () => {

                const scrollY =
                    window.scrollY;


                /*
                 * On arrête le calcul
                 * une fois le hero dépassé.
                 */

                if (
                    scrollY <=
                    window.innerHeight * 1.2
                ) {

                    const translate =
                        scrollY * 0.12;


                    heroMedia.style.transform =
                        `translate3d(
                            0,
                            ${translate}px,
                            0
                        )`;

                }


                ticking = false;

            };


        window.addEventListener(
            'scroll',
            () => {

                if (!ticking) {

                    requestAnimationFrame(
                        updateParallax
                    );


                    ticking = true;

                }

            },
            {
                passive: true
            }
        );

    }


};



/* ================================================================
   20. DOM READY
================================================================ */

document.addEventListener(
    'DOMContentLoaded',
    () => {

        GenerationPush.init();

    }
);


/* ================================================================
   21. IZI TOAST GLOBAL

   Permet aussi depuis Blade de faire :

   window.gpToast.success('Message');
================================================================ */

window.gpToast = {

    success(message) {

        if (
            typeof iziToast ===
            'undefined'
        ) {

            console.log(message);

            return;
        }


        iziToast.success({

            title: 'Succès',

            message: message,

            position: 'topRight',

            timeout: 4500,

            progressBar: true,

            close: true

        });

    },


    error(message) {

        if (
            typeof iziToast ===
            'undefined'
        ) {

            console.error(message);

            return;
        }


        iziToast.error({

            title: 'Erreur',

            message: message,

            position: 'topRight',

            timeout: 5500,

            progressBar: true,

            close: true

        });

    },


    warning(message) {

        if (
            typeof iziToast ===
            'undefined'
        ) {

            console.warn(message);

            return;
        }


        iziToast.warning({

            title: 'Attention',

            message: message,

            position: 'topRight',

            timeout: 5000,

            progressBar: true,

            close: true

        });

    },


    info(message) {

        if (
            typeof iziToast ===
            'undefined'
        ) {

            console.log(message);

            return;
        }


        iziToast.info({

            title: 'Information',

            message: message,

            position: 'topRight',

            timeout: 4500,

            progressBar: true,

            close: true

        });

    }


};
