<x-layouts.public title="À propos — Generation PUSH">

    {{-- =========================================================
    BANNIÈRE
    ========================================================== --}}

    <x-front.page-banner :title="$page->title" :subtitle="$page->subtitle" :video="$page->hasActiveBannerVideo() ? $page->bannerVideoUrl() : null" :poster="$page->bannerPosterUrl()" />


    @php
        /*
        |--------------------------------------------------------------------------
        | Valeurs
        |--------------------------------------------------------------------------
        */

        $values = [
            [
                'number' => '01',
                'icon' => 'zap',
                'title' => $page->value_1_title,
                'short' => $page->value_1_short,
                'text' => $page->value_1_text,
            ],
            [
                'number' => '02',
                'icon' => 'users-round',
                'title' => $page->value_2_title,
                'short' => $page->value_2_short,
                'text' => $page->value_2_text,
            ],
            [
                'number' => '03',
                'icon' => 'award',
                'title' => $page->value_3_title,
                'short' => $page->value_3_short,
                'text' => $page->value_3_text,
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Équipe
        |--------------------------------------------------------------------------
        */

        $teamSlides = $team->chunk(3);
        $teamCount = $team->count();
    @endphp

    <style>
        /* =========================================================
           GENERATION PUSH — ABOUT PAGE
        ========================================================= */

        .gp-about {
            --gp-orange: #E8631A;
            --gp-orange-light: #ff8c4b;
            --gp-black: #151515;
            --gp-dark: #080808;
            --gp-text: #6f6f6f;
            --gp-soft: #f7f7f5;
            --gp-line: #e9e9e6;

            overflow: hidden;

            color: var(--gp-black);
            background: #fff;
        }

        .gp-about *,
        .gp-about *::before,
        .gp-about *::after {
            box-sizing: border-box;
        }

        .gp-container {
            width: min(1180px, calc(100% - 48px));
            margin-inline: auto;
        }

        .gp-section {
            position: relative;

            padding: 110px 0;
        }


        /* =========================================================
           TYPOGRAPHIE
        ========================================================= */

        .gp-eyebrow {
            display: inline-flex;
            align-items: center;

            gap: 10px;

            margin-bottom: 17px;

            color: var(--gp-orange);

            font-size: 11px;
            line-height: 1;

            font-weight: 900;

            text-transform: uppercase;
            letter-spacing: .16em;
        }

        .gp-eyebrow::before {
            content: "";

            width: 28px;
            height: 2px;

            border-radius: 100px;

            background: currentColor;
        }

        .gp-title {
            margin: 0;

            color: var(--gp-black);

            font-size: clamp(39px, 4.4vw, 64px);
            line-height: .99;

            font-weight: 950;

            letter-spacing: -.055em;
        }

        .gp-title span {
            color: var(--gp-orange);
        }

        .gp-lead {
            max-width: 630px;

            margin: 23px 0 0;

            color: var(--gp-text);

            font-size: 15px;
            line-height: 1.9;
        }


        /* =========================================================
           01 — HISTOIRE
        ========================================================= */

        .gp-story {
            background:
                radial-gradient(circle at 5% 40%,
                    rgba(232, 99, 26, .07),
                    transparent 23%),
                #fff;
        }

        .gp-story-grid {
            display: grid;

            grid-template-columns:
                minmax(0, .95fr) minmax(0, 1.05fr);

            align-items: center;

            gap: clamp(65px, 8vw, 110px);
        }


        /* VISUEL */

        .gp-story-visual {
            position: relative;

            min-height: 580px;
        }

        .gp-story-frame {
            position: absolute;

            z-index: 2;

            inset: 0 38px 38px 0;

            overflow: hidden;

            border-radius: 34px;

            background:
                linear-gradient(145deg,
                    #181818,
                    #74300e);

            box-shadow:
                0 35px 80px rgba(0, 0, 0, .14);
        }

        .gp-story-frame img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: cover;

            transition: transform .8s ease;
        }

        .gp-story-visual:hover .gp-story-frame img {
            transform: scale(1.04);
        }

        .gp-story-placeholder {
            width: 100%;
            height: 100%;

            display: grid;
            place-items: center;

            background:
                radial-gradient(circle at 70% 25%,
                    rgba(232, 99, 26, .7),
                    transparent 35%),
                linear-gradient(145deg,
                    #181818,
                    #79320e);
        }

        .gp-story-placeholder span {
            color: #fff;

            font-size: 85px;

            font-weight: 950;

            letter-spacing: -.08em;
        }


        /* CARRÉ ORANGE */

        .gp-story-decoration {
            position: absolute;

            z-index: 1;

            right: 0;
            bottom: 0;

            width: 165px;
            height: 165px;

            border-radius: 30px;

            background:
                linear-gradient(135deg,
                    var(--gp-orange),
                    var(--gp-orange-light));
        }

        .gp-story-decoration::after {
            content: "";

            position: absolute;

            inset: 20px;

            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 20px;
        }


        /* PETITE CARTE */

        .gp-story-floating {
            position: absolute;

            z-index: 5;

            left: -30px;
            bottom: 70px;

            width: 215px;

            padding: 21px;

            border: 1px solid rgba(255, 255, 255, .7);
            border-radius: 20px;

            background: rgba(255, 255, 255, .94);

            box-shadow:
                0 20px 50px rgba(0, 0, 0, .14);

            backdrop-filter: blur(15px);

            animation:
                gpAboutFloating 4s ease-in-out infinite;
        }

        @keyframes gpAboutFloating {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-9px);
            }
        }

        .gp-story-floating-icon {
            width: 43px;
            height: 43px;

            display: grid;
            place-items: center;

            margin-bottom: 14px;

            border-radius: 13px;

            color: var(--gp-orange);

            background: rgba(232, 99, 26, .1);
        }

        .gp-story-floating strong {
            display: block;

            color: #171717;

            font-size: 14px;
            font-weight: 900;
        }

        .gp-story-floating small {
            display: block;

            margin-top: 5px;

            color: #888;

            font-size: 10px;
            line-height: 1.55;
        }


        /* DESCRIPTION */

        .gp-story-description {
            margin: 27px 0 0;

            color: var(--gp-text);

            font-size: 15px;
            line-height: 1.9;

            white-space: pre-line;
        }


        /* STATS */

        .gp-stats {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 12px;

            margin-top: 42px;
        }

        .gp-stat {
            position: relative;

            overflow: hidden;

            min-height: 110px;

            padding: 20px 17px;

            border: 1px solid var(--gp-line);
            border-radius: 17px;

            background: #fafaf8;

            transition:
                transform .3s ease,
                box-shadow .3s ease,
                border-color .3s ease;
        }

        .gp-stat:hover {
            transform: translateY(-5px);

            border-color: rgba(232, 99, 26, .25);

            box-shadow:
                0 18px 40px rgba(0, 0, 0, .06);
        }

        .gp-stat-number {
            color: var(--gp-orange);

            font-size: 27px;
            line-height: 1;

            font-weight: 950;
        }

        .gp-stat-label {
            margin-top: 9px;

            color: #777;

            font-size: 10px;
            line-height: 1.4;

            font-weight: 750;
        }


        /* =========================================================
           02 — FONDATRICE
        ========================================================= */

        .gp-founder {
            position: relative;

            overflow: hidden;

            background: #090909;
        }

        .gp-founder::before {
            content: "";

            position: absolute;

            inset: 0;

            background:
                radial-gradient(circle at 8% 50%,
                    rgba(232, 99, 26, .18),
                    transparent 26%),
                radial-gradient(circle at 90% 10%,
                    rgba(232, 99, 26, .09),
                    transparent 23%);
        }

        .gp-founder::after {
            content: "PUSH";

            position: absolute;

            right: -50px;
            bottom: -105px;

            color: rgba(255, 255, 255, .025);

            font-size: clamp(180px, 23vw, 340px);

            line-height: .8;

            font-weight: 1000;

            letter-spacing: -.09em;
        }

        .gp-founder-grid {
            position: relative;

            z-index: 2;

            min-height: 370px;

            display: grid;

            grid-template-columns:
                90px minmax(0, 1fr) auto;

            align-items: center;

            gap: 40px;
        }

        .gp-founder-index {
            display: flex;
            flex-direction: column;
            align-items: center;

            gap: 15px;
        }

        .gp-founder-index strong {
            color: rgba(255, 255, 255, .14);

            font-size: 45px;
            line-height: 1;

            font-weight: 950;
        }

        .gp-founder-index span {
            width: 1px;
            height: 65px;

            background:
                linear-gradient(to bottom,
                    var(--gp-orange),
                    transparent);
        }

        .gp-founder-copy {
            max-width: 700px;
        }

        .gp-founder .gp-eyebrow {
            color: var(--gp-orange-light);
        }

        .gp-founder-title {
            margin: 0;

            color: #fff;

            font-size: clamp(37px, 4.3vw, 59px);
            line-height: 1;

            font-weight: 950;

            letter-spacing: -.055em;
        }

        .gp-founder-title span {
            color: var(--gp-orange);
        }

        .gp-founder-description {
            max-width: 610px;

            margin: 20px 0 0;

            color: rgba(255, 255, 255, .66);

            font-size: 14px;
            line-height: 1.85;
        }

        .gp-founder-button {
            display: inline-flex;
            align-items: center;

            gap: 10px;

            padding: 16px 20px;

            border: 1px solid rgba(255, 255, 255, .13);
            border-radius: 14px;

            color: #fff !important;

            background: rgba(255, 255, 255, .06);

            text-decoration: none;

            font-size: 12px;
            font-weight: 850;

            transition: .3s ease;
        }

        .gp-founder-button:hover {
            color: #fff;

            border-color: var(--gp-orange);

            background: var(--gp-orange);

            transform: translateY(-3px);
        }


        /* =========================================================
           03 — VALEURS 3D
        ========================================================= */

        .gp-values {
            background:
                radial-gradient(circle at 95% 5%,
                    rgba(232, 99, 26, .08),
                    transparent 23%),
                var(--gp-soft);
        }

        .gp-values-header {
            max-width: 720px;

            margin: 0 auto 60px;

            text-align: center;
        }

        .gp-values-header .gp-eyebrow {
            justify-content: center;
        }

        .gp-values-header .gp-lead {
            margin-inline: auto;
        }

        .gp-values-grid {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 24px;
        }


        /* PERSPECTIVE */

        .gp-value-scene {
            min-height: 385px;

            perspective: 1200px;

            cursor: pointer;
        }

        .gp-value-card {
            position: relative;

            width: 100%;
            height: 100%;
            min-height: 385px;

            transform-style: preserve-3d;

            transition:
                transform .75s cubic-bezier(.2, .75, .2, 1);
        }

        .gp-value-scene:hover .gp-value-card,
        .gp-value-scene.is-flipped .gp-value-card {
            transform: rotateY(180deg);
        }


        /* FACES */

        .gp-value-face {
            position: absolute;

            inset: 0;

            overflow: hidden;

            display: flex;
            flex-direction: column;

            padding: 31px;

            border: 1px solid #e7e7e4;
            border-radius: 27px;

            background: #fff;

            box-shadow:
                0 18px 45px rgba(0, 0, 0, .055);

            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .gp-value-front {
            transform: rotateY(0deg);
        }

        .gp-value-back {
            color: #fff;

            border-color: transparent;

            background:
                radial-gradient(circle at 80% 15%,
                    rgba(232, 99, 26, .42),
                    transparent 35%),
                #111;

            transform: rotateY(180deg);
        }


        /* FACE AVANT */

        .gp-value-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .gp-value-icon {
            width: 58px;
            height: 58px;

            display: grid;
            place-items: center;

            border-radius: 17px;

            color: var(--gp-orange);

            background: rgba(232, 99, 26, .10);
        }

        .gp-value-number {
            color: #ededeb;

            font-size: 48px;
            line-height: 1;

            font-weight: 950;
        }

        .gp-value-front-copy {
            margin-top: auto;
        }

        .gp-value-short {
            margin-bottom: 8px;

            color: var(--gp-orange);

            font-size: 10px;

            font-weight: 900;

            text-transform: uppercase;

            letter-spacing: .12em;
        }

        .gp-value-title {
            margin: 0;

            color: #171717;

            font-size: 26px;
            line-height: 1.1;

            font-weight: 950;
        }

        .gp-value-hint {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-top: 25px;
            padding-top: 18px;

            border-top: 1px solid #ececea;

            color: #999;

            font-size: 10px;

            font-weight: 750;

            text-transform: uppercase;

            letter-spacing: .08em;
        }

        .gp-value-hint span:last-child {
            width: 34px;
            height: 34px;

            display: grid;
            place-items: center;

            border-radius: 50%;

            color: var(--gp-orange);

            background: rgba(232, 99, 26, .08);
        }


        /* FACE ARRIÈRE */

        .gp-value-back-number {
            color: rgba(255, 255, 255, .12);

            font-size: 52px;
            line-height: 1;

            font-weight: 950;
        }

        .gp-value-back-content {
            margin-top: auto;
        }

        .gp-value-back .gp-value-title {
            color: #fff;
        }

        .gp-value-back-text {
            margin: 17px 0 0;

            color: rgba(255, 255, 255, .70);

            font-size: 14px;
            line-height: 1.8;
        }

        .gp-value-back-line {
            width: 50px;
            height: 3px;

            margin-top: 24px;

            border-radius: 100px;

            background: var(--gp-orange);
        }


        /* =========================================================
           04 — ÉQUIPE
        ========================================================= */

        .gp-team {
            position: relative;

            overflow: hidden;

            background: #fff;
        }

        .gp-team-header {
            max-width: 720px;

            margin-bottom: 55px;
        }

        .gp-team-header .gp-lead {
            max-width: 600px;
        }


        /* =========================================================
           CAROUSEL
        ========================================================= */

        .gp-team-carousel-wrap {
            position: relative;

            width: 100%;

            padding: 0 70px;
        }

        .gp-team-carousel {
            position: relative;

            width: 100%;
        }

        .gp-team-carousel .carousel-inner {
            overflow: hidden;

            border-radius: 27px;
        }

        .gp-team-carousel .carousel-item {
            padding: 3px;
        }

        .gp-team-row {
            display: grid;

            grid-template-columns:
                repeat(3, minmax(0, 1fr));

            gap: 24px;
        }


        /* =========================================================
           CARTE MEMBRE
        ========================================================= */

        .gp-member-card {
            position: relative;

            overflow: hidden;

            min-height: 470px;

            display: flex;
            flex-direction: column;

            border-radius: 27px;

            background: #111;

            box-shadow:
                0 18px 50px rgba(0, 0, 0, .10);
        }


        /* PHOTO */

        .gp-member-photo {
            position: relative;

            height: 285px;

            overflow: hidden;

            background:
                linear-gradient(145deg,
                    #1b1b1b,
                    #6c2a0d);
        }

        .gp-member-photo img {
            display: block;

            width: 100%;
            height: 100%;

            object-fit: cover;
            object-position: center top;

            transition: transform .6s ease;
        }

        .gp-member-card:hover .gp-member-photo img {
            transform: scale(1.055);
        }

        .gp-member-placeholder {
            width: 100%;
            height: 100%;

            display: grid;
            place-items: center;

            color: rgba(255, 255, 255, .9);

            font-size: 72px;

            font-weight: 950;

            background:
                radial-gradient(circle at 50% 25%,
                    rgba(232, 99, 26, .45),
                    transparent 30%),
                linear-gradient(145deg,
                    #161616,
                    #65280c);
        }


        /* BODY */

        .gp-member-body {
            flex: 1;

            display: flex;
            flex-direction: column;

            padding: 25px;

            color: #fff;

            background:
                linear-gradient(145deg,
                    #111,
                    #181818);
        }

        .gp-member-role {
            margin-bottom: 7px;

            color: var(--gp-orange-light);

            font-size: 9px;

            font-weight: 900;

            text-transform: uppercase;

            letter-spacing: .11em;
        }

        .gp-member-name {
            margin: 0;

            color: #fff;

            font-size: 21px;
            line-height: 1.2;

            font-weight: 900;
        }

        .gp-member-bio {
            display: -webkit-box;

            overflow: hidden;

            margin: 13px 0 0;

            color: rgba(255, 255, 255, .60);

            font-size: 12px;
            line-height: 1.65;

            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }


        /* SOCIAL */

        .gp-member-socials {
            display: flex;
            align-items: center;

            gap: 9px;

            margin-top: auto;
            padding-top: 20px;
        }

        .gp-social {
            width: 37px;
            height: 37px;

            display: grid;
            place-items: center;

            border: 1px solid rgba(255, 255, 255, .10);
            border-radius: 50%;

            color: #fff !important;

            background: rgba(255, 255, 255, .055);

            text-decoration: none;

            transition:
                background .3s ease,
                border-color .3s ease,
                transform .3s ease;
        }

        .gp-social:hover {
            color: #fff;

            border-color: var(--gp-orange);

            background: var(--gp-orange);

            transform: translateY(-3px);
        }


        /* =========================================================
           FLÈCHES CAROUSEL
        ========================================================= */

        .gp-team-control {
            position: absolute;

            z-index: 20;

            top: 50%;

            width: 52px;
            height: 52px;

            display: grid;
            place-items: center;

            padding: 0;

            border: 1px solid #e6e6e3;
            border-radius: 50%;

            color: #171717;

            background: #fff;

            box-shadow:
                0 14px 35px rgba(0, 0, 0, .10);

            transform: translateY(-50%);

            cursor: pointer;

            transition: .3s ease;
        }

        .gp-team-control:hover {
            color: #fff;

            border-color: var(--gp-orange);

            background: var(--gp-orange);

            transform:
                translateY(-50%) scale(1.07);
        }

        .gp-team-prev {
            left: 0;
        }

        .gp-team-next {
            right: 0;
        }


        /* INDICATEURS */

        .gp-team-indicators {
            position: static;

            display: flex;

            justify-content: center;

            gap: 7px;

            margin: 30px 0 0;

            padding: 0;
        }

        .gp-team-indicators [data-bs-target] {
            width: 8px;
            height: 8px;

            margin: 0;

            border: 0;
            border-radius: 100px;

            background: #d2d2cf;

            opacity: 1;

            transition: .3s ease;
        }

        .gp-team-indicators .active {
            width: 30px;

            background: var(--gp-orange);
        }


        /* =========================================================
           05 — CTA
        ========================================================= */

        .gp-cta {
            position: relative;

            overflow: hidden;

            padding: 115px 24px;

            text-align: center;

            background: #070707;
        }

        .gp-cta::before {
            content: "";

            position: absolute;

            top: 50%;
            left: 50%;

            width: 650px;
            height: 650px;

            transform: translate(-50%, -50%);

            border-radius: 50%;

            background:
                radial-gradient(circle,
                    rgba(232, 99, 26, .20),
                    transparent 65%);
        }

        .gp-cta::after {
            content: "PUSH";

            position: absolute;

            left: 50%;
            bottom: -85px;

            transform: translateX(-50%);

            color: rgba(255, 255, 255, .025);

            font-size: clamp(170px, 25vw, 350px);

            line-height: .8;

            font-weight: 1000;

            letter-spacing: -.09em;
        }

        .gp-cta-inner {
            position: relative;

            z-index: 2;

            max-width: 820px;

            margin-inline: auto;
        }

        .gp-cta .gp-eyebrow {
            justify-content: center;
        }

        .gp-cta-title {
            margin: 0;

            color: #fff;

            font-size: clamp(42px, 5.2vw, 72px);
            line-height: .98;

            font-weight: 950;

            letter-spacing: -.06em;
        }

        .gp-cta-title span {
            display: block;

            color: var(--gp-orange);
        }

        .gp-cta-text {
            max-width: 600px;

            margin: 23px auto 33px;

            color: rgba(255, 255, 255, .62);

            font-size: 14px;
            line-height: 1.8;
        }

        .gp-cta-button {
            display: inline-flex;
            align-items: center;

            gap: 11px;

            padding: 17px 26px;

            border-radius: 14px;

            color: #fff !important;

            background: var(--gp-orange);

            text-decoration: none;

            font-size: 12px;
            font-weight: 900;

            box-shadow:
                0 18px 45px rgba(232, 99, 26, .27);

            transition: .3s ease;
        }

        .gp-cta-button:hover {
            color: #fff;

            transform: translateY(-4px);

            box-shadow:
                0 24px 55px rgba(232, 99, 26, .40);
        }


        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1050px) {

            .gp-section {
                padding: 90px 0;
            }

            .gp-story-grid {
                grid-template-columns: 1fr;

                gap: 65px;
            }

            .gp-story-visual {
                width: min(650px, 100%);

                margin-inline: auto;
            }

            .gp-founder-grid {
                grid-template-columns:
                    70px minmax(0, 1fr);

                padding: 75px 0;

                gap: 28px;
            }

            .gp-founder-action {
                grid-column: 2;
            }

            .gp-values-grid {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }
        }


        /* =========================================================
           TABLETTE — 2 CARTES ÉQUIPE
        ========================================================= */

        @media (max-width: 991.98px) {

            .gp-team-row {
                grid-template-columns:
                    repeat(2, minmax(0, 1fr));
            }

            /*
             * Le troisième membre d'un groupe passe sur
             * la ligne suivante si Bootstrap affiche le chunk.
             * On le centre.
             */

            .gp-team-row>*:last-child:nth-child(odd) {
                grid-column: 1 / -1;

                width: calc(50% - 12px);

                justify-self: center;
            }
        }


        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 700px) {

            .gp-container {
                width: min(100% - 32px, 1180px);
            }

            .gp-section {
                padding: 72px 0;
            }

            .gp-title {
                font-size: clamp(36px, 11vw, 49px);
            }


            /* histoire */

            .gp-story-visual {
                min-height: 430px;
            }

            .gp-story-frame {
                inset: 0 15px 22px 0;

                border-radius: 25px;
            }

            .gp-story-decoration {
                width: 110px;
                height: 110px;

                border-radius: 21px;
            }

            .gp-story-floating {
                left: 10px;
                bottom: 38px;

                width: 180px;

                padding: 16px;
            }

            .gp-stats {
                grid-template-columns: 1fr;
            }


            /* fondatrice */

            .gp-founder-grid {
                display: flex;
                flex-direction: column;
                align-items: flex-start;

                padding: 70px 0;
            }

            .gp-founder-index {
                flex-direction: row;
            }

            .gp-founder-index span {
                width: 55px;
                height: 1px;

                background:
                    linear-gradient(to right,
                        var(--gp-orange),
                        transparent);
            }

            .gp-founder-title {
                font-size: clamp(38px, 11vw, 51px);
            }

            .gp-founder-action {
                width: 100%;
            }

            .gp-founder-button {
                width: 100%;

                justify-content: center;
            }


            /* valeurs */

            .gp-values-grid {
                grid-template-columns: 1fr;
            }

            .gp-value-scene,
            .gp-value-card {
                min-height: 350px;
            }


            /* équipe */

            .gp-team-carousel-wrap {
                padding: 0 46px;
            }

            .gp-team-row {
                grid-template-columns: 1fr;
            }

            .gp-team-row>*:last-child:nth-child(odd) {
                grid-column: auto;

                width: 100%;
            }

            /*
             * Sur mobile, on masque les cartes 2 et 3
             * d'un même slide Bootstrap.
             * Le JS plus bas reconstruit le carousel en
             * une carte par slide.
             */

            .gp-member-card {
                min-height: 455px;
            }

            .gp-member-photo {
                height: 280px;
            }

            .gp-team-control {
                width: 42px;
                height: 42px;
            }


            /* CTA */

            .gp-cta {
                padding: 85px 20px;
            }

            .gp-cta-title {
                font-size: clamp(40px, 12vw, 58px);
            }

            .gp-cta-button {
                width: 100%;

                justify-content: center;
            }
        }


        @media (prefers-reduced-motion: reduce) {

            .gp-story-floating {
                animation: none;
            }

            .gp-about *,
            .gp-about *::before,
            .gp-about *::after {
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
            }
        }
    </style>


    <main class="gp-about">


        {{-- =====================================================
        HISTOIRE
        ====================================================== --}}

        <section class="gp-section gp-story">

            <div class="gp-container gp-story-grid">

                <div class="gp-story-visual" data-aos="fade-right">

                    <div class="gp-story-decoration"></div>


                    <div class="gp-story-frame">

                        @if ($page->storyImageUrl())

                            <img src="{{ $page->storyImageUrl() }}" alt="{{ $page->story_title ?: 'Generation PUSH' }}">

                        @elseif ($settings->aboutImageUrl())

                            <img src="{{ $settings->aboutImageUrl() }}" alt="{{ $page->story_title ?: 'Generation PUSH' }}">

                        @else

                            <div class="gp-story-placeholder">
                                <span>GP</span>
                            </div>

                        @endif

                    </div>


                    <div class="gp-story-floating">

                        <div class="gp-story-floating-icon">
                            <x-icon name="zap" class="w-5 h-5" />
                        </div>

                        <strong>
                            {{ $page->story_card_title }}
                        </strong>

                        <small>
                            {{ $page->story_card_text }}
                        </small>

                    </div>

                </div>


                <div data-aos="fade-left">

                    <div class="gp-eyebrow">
                        {{ $page->story_eyebrow }}
                    </div>

                    <h2 class="gp-title">
                        {{ $page->story_title }}
                    </h2>

                    <div class="gp-story-description">
                        {!! nl2br(e($page->story_text)) !!}
                    </div>


                    <div class="gp-stats">

                        <div class="gp-stat">

                            <div class="gp-stat-number">
                                {{ number_format($stats['members'] ?? 0) }}+
                            </div>

                            <div class="gp-stat-label">
                                Membres engagés
                            </div>

                        </div>


                        <div class="gp-stat">

                            <div class="gp-stat-number">
                                {{ number_format($stats['events'] ?? 0) }}+
                            </div>

                            <div class="gp-stat-label">
                                Événements réalisés
                            </div>

                        </div>


                        <div class="gp-stat">

                            <div class="gp-stat-number">
                                {{ number_format($teamCount) }}+
                            </div>

                            <div class="gp-stat-label">
                                Membres de l'équipe
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- =====================================================
        FONDATRICE
        ====================================================== --}}

        <section class="gp-founder">

            <div class="gp-container gp-founder-grid">

                <div class="gp-founder-index">

                    <strong>
                        01
                    </strong>

                    <span></span>

                </div>


                <div class="gp-founder-copy">

                    <div class="gp-eyebrow">
                        {{ $page->founder_eyebrow }}
                    </div>

                    <h2 class="gp-founder-title">
                        {{ $page->founder_title }}
                    </h2>

                    <div class="gp-founder-description">
                        {!! nl2br(e($page->founder_text)) !!}
                    </div>

                </div>


                <div class="gp-founder-action">

                    <a href="{{ route('front.founder') }}" class="gp-founder-button">
                        {{ $page->founder_button_text }}

                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="m13 6 6 6-6 6" />
                        </svg>

                    </a>

                </div>

            </div>

        </section>


        {{-- =====================================================
        VALEURS 3D
        ====================================================== --}}

        <section class="gp-section gp-values">

            <div class="gp-container">

                <header class="gp-values-header" data-aos="fade-up">

                    <div class="gp-eyebrow">
                        {{ $page->values_eyebrow }}
                    </div>

                    <h2 class="gp-title">
                        {{ $page->values_title }}
                    </h2>

                    <p class="gp-lead">
                        {{ $page->values_intro }}
                    </p>

                </header>


                <div class="gp-values-grid">

                    @foreach ($values as $value)

                        <div class="gp-value-scene" tabindex="0" data-aos="fade-up">

                            <article class="gp-value-card">


                                {{-- FACE AVANT --}}

                                <div class="gp-value-face gp-value-front">

                                    <div class="gp-value-top">

                                        <div class="gp-value-icon">

                                            <x-icon :name="$value['icon']" class="w-6 h-6" />

                                        </div>

                                        <span class="gp-value-number">
                                            {{ $value['number'] }}
                                        </span>

                                    </div>


                                    <div class="gp-value-front-copy">

                                        <div class="gp-value-short">
                                            {{ $value['short'] }}
                                        </div>

                                        <h3 class="gp-value-title">
                                            {{ $value['title'] }}
                                        </h3>


                                        <div class="gp-value-hint">

                                            <span>
                                                Découvrir la valeur
                                            </span>

                                            <span>
                                                ↗
                                            </span>

                                        </div>

                                    </div>

                                </div>


                                {{-- FACE ARRIÈRE --}}

                                <div class="gp-value-face gp-value-back">

                                    <span class="gp-value-back-number">
                                        {{ $value['number'] }}
                                    </span>


                                    <div class="gp-value-back-content">

                                        <h3 class="gp-value-title">
                                            {{ $value['title'] }}
                                        </h3>

                                        <p class="gp-value-back-text">
                                            {{ $value['text'] }}
                                        </p>

                                        <div class="gp-value-back-line"></div>

                                    </div>

                                </div>


                            </article>

                        </div>

                    @endforeach

                </div>

            </div>

        </section>


        {{-- =====================================================
        ÉQUIPE
        ====================================================== --}}

        @if ($team->isNotEmpty())

            <section class="gp-section gp-team">

                <div class="gp-container">

                    <header class="gp-team-header" data-aos="fade-up">

                        <div class="gp-eyebrow">
                            {{ $page->team_eyebrow }}
                        </div>

                        <h2 class="gp-title">
                            {{ $page->team_title }}
                        </h2>

                        <p class="gp-lead">
                            {{ $page->team_intro }}
                        </p>

                    </header>


                    <div class="gp-team-carousel-wrap" data-aos="fade-up">

                        <div id="gpTeamCarousel" class="carousel slide gp-team-carousel" data-bs-ride="carousel"
                            data-bs-interval="4200" data-bs-pause="hover" data-bs-touch="true" data-bs-wrap="true">

                            <div class="carousel-inner">


                                @foreach ($teamSlides as $slideIndex => $members)

                                    <div
                                        class="carousel-item
                                                                                        {{ $slideIndex === 0 ? 'active' : '' }}">

                                        <div class="gp-team-row">


                                            @foreach ($members as $member)

                                                <article class="gp-member-card">


                                                    {{-- PHOTO --}}

                                                    <div class="gp-member-photo">

                                                        @if ($member->photoUrl())

                                                            <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}"
                                                                loading="lazy">

                                                        @else

                                                                                        <div class="gp-member-placeholder">

                                                                                            {{ Str::upper(
                                                                Str::substr(
                                                                    $member->name,
                                                                    0,
                                                                    1
                                                                )
                                                            ) }}

                                                                                        </div>

                                                        @endif

                                                    </div>


                                                    {{-- INFOS --}}

                                                    <div class="gp-member-body">

                                                        @if ($member->role)

                                                            <div class="gp-member-role">
                                                                {{ $member->role }}
                                                            </div>

                                                        @endif


                                                        <h3 class="gp-member-name">
                                                            {{ $member->name }}
                                                        </h3>


                                                        @if ($member->bio)

                                                            <p class="gp-member-bio">
                                                                {{ $member->bio }}
                                                            </p>

                                                        @else

                                                            <p class="gp-member-bio">
                                                                Membre de l'équipe Generation PUSH,
                                                                engagé au service de la vision
                                                                et de la communauté.
                                                            </p>

                                                        @endif


                                                        @if ($member->email || $member->linkedin_url)

                                                            <div class="gp-member-socials">


                                                                {{-- EMAIL --}}

                                                                @if ($member->email)

                                                                    <a href="mailto:{{ $member->email }}" class="gp-social"
                                                                        title="Envoyer un email à {{ $member->name }}" aria-label="Email">

                                                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                                                            stroke="currentColor" stroke-width="2">
                                                                            <rect width="20" height="16" x="2" y="4" rx="2" />

                                                                            <path d="m22 7-10 6L2 7" />
                                                                        </svg>

                                                                    </a>

                                                                @endif


                                                                {{-- LINKEDIN --}}

                                                                @if ($member->linkedin_url)

                                                                    <a href="{{ $member->linkedin_url }}" target="_blank"
                                                                        rel="noopener noreferrer" class="gp-social"
                                                                        title="LinkedIn de {{ $member->name }}" aria-label="LinkedIn">

                                                                        <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor">
                                                                            <path
                                                                                d="M6.5 8.5H3.2V19h3.3V8.5ZM4.85 3C3.78 3 3 3.72 3 4.67c0 .93.76 1.67 1.81 1.67h.02c1.09 0 1.86-.74 1.86-1.67C6.67 3.72 5.92 3 4.85 3ZM19.5 13c0-3.2-1.7-4.69-3.98-4.69-1.83 0-2.65 1.01-3.11 1.72V8.5H9.1c.04 1.01 0 10.5 0 10.5h3.31v-5.86c0-.31.02-.63.12-.85.24-.63.79-1.28 1.71-1.28 1.21 0 1.69.92 1.69 2.27V19h3.31l.26-6Z" />
                                                                        </svg>

                                                                    </a>

                                                                @endif

                                                            </div>

                                                        @endif

                                                    </div>

                                                </article>

                                            @endforeach


                                        </div>

                                    </div>

                                @endforeach

                            </div>


                            {{-- INDICATEURS --}}

                            @if ($teamSlides->count() > 1)

                                <div class="carousel-indicators gp-team-indicators">

                                    @foreach ($teamSlides as $slideIndex => $members)

                                        <button type="button" data-bs-target="#gpTeamCarousel" data-bs-slide-to="{{ $slideIndex }}"
                                            class="{{ $slideIndex === 0 ? 'active' : '' }}"
                                            aria-current="{{ $slideIndex === 0 ? 'true' : 'false' }}"
                                            aria-label="Groupe {{ $slideIndex + 1 }}"></button>

                                    @endforeach

                                </div>

                            @endif

                        </div>


                        {{-- FLÈCHE GAUCHE --}}

                        @if ($teamCount > 1)

                            <button class="gp-team-control gp-team-prev" type="button" data-bs-target="#gpTeamCarousel"
                                data-bs-slide="prev" aria-label="Membres précédents">

                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="m15 18-6-6 6-6" />
                                </svg>

                            </button>


                            {{-- FLÈCHE DROITE --}}

                            <button class="gp-team-control gp-team-next" type="button" data-bs-target="#gpTeamCarousel"
                                data-bs-slide="next" aria-label="Membres suivants">

                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="m9 18 6-6-6-6" />
                                </svg>

                            </button>

                        @endif

                    </div>

                </div>

            </section>

        @endif


        {{-- =====================================================
        CTA
        ====================================================== --}}

        <section class="gp-cta">

            <div class="gp-cta-inner" data-aos="zoom-in">

                <div class="gp-eyebrow">
                    {{ $page->cta_eyebrow }}
                </div>

                <h2 class="gp-cta-title">
                    {{ $page->cta_title }}

                    @if($page->cta_highlight)
                        <span>{{ $page->cta_highlight }}</span>
                    @endif
                </h2>

                <p class="gp-cta-text">
                    {{ $page->cta_text }}
                </p>

                @if($page->cta_button_text)
                    <a href="{{ $page->cta_button_url ?: '#' }}" class="gp-cta-button">
                        {{ $page->cta_button_text }}

                        <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 12h14" />
                            <path d="m13 6 6 6-6 6" />
                        </svg>
                    </a>
                @endif


                <a href="{{ route('register') }}" class="gp-cta-button">
                    Rejoindre la communauté

                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14" />
                        <path d="m13 6 6 6-6 6" />
                    </svg>

                </a>

            </div>

        </section>

    </main>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | CARTES VALEURS
            |--------------------------------------------------------------------------
            | Desktop : hover.
            | Mobile / tablette : clic.
            */

            document
                .querySelectorAll('.gp-value-scene')
                .forEach(function (scene) {

                    scene.addEventListener('click', function () {

                        this.classList.toggle('is-flipped');

                    });

                    scene.addEventListener('keydown', function (event) {

                        if (
                            event.key === 'Enter' ||
                            event.key === ' '
                        ) {

                            event.preventDefault();

                            this.classList.toggle('is-flipped');

                        }

                    });

                });


            /*
            |--------------------------------------------------------------------------
            | BOOTSTRAP CAROUSEL
            |--------------------------------------------------------------------------
            */

            const carouselElement =
                document.getElementById('gpTeamCarousel');

            if (
                carouselElement &&
                typeof bootstrap !== 'undefined' &&
                bootstrap.Carousel
            ) {

                bootstrap.Carousel.getOrCreateInstance(
                    carouselElement,
                    {
                        interval: 4200,
                        ride: 'carousel',
                        pause: 'hover',
                        touch: true,
                        wrap: true,
                    }
                );

            }

        });
    </script>

</x-layouts.public>