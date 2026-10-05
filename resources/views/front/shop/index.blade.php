<x-layouts.public title="Boutique — Generation PUSH">



    @php

        $productCount = method_exists($products, 'total')

            ? $products->total()

            : $products->count();



        $filters = [

            ''        => 'Tous',

            'book'    => 'Livres',

            'video'   => 'Vidéos',

            'usb_key' => 'Clés USB',

        ];

    @endphp





    <style>

        /* =========================================================

           GENERATION PUSH — SHOP

        ========================================================= */



        .gp-shop {

            --shop-orange: #E8631A;

            --shop-orange-light: #ff8a45;

            --shop-black: #111111;

            --shop-dark: #080808;

            --shop-soft: #f7f7f4;

            --shop-gray: #737373;

            --shop-line: #e9e9e5;



            overflow: hidden;

            background: #fff;

            color: var(--shop-black);

        }



        .gp-shop *,

        .gp-shop *::before,

        .gp-shop *::after {

            box-sizing: border-box;

        }



        .gp-shop-container {

            width: min(1180px, calc(100% - 48px));

            margin-inline: auto;

        }





        /* =========================================================

           HERO

        ========================================================= */



        .gp-shop-hero {

            position: relative;

            min-height: 590px;

            display: flex;

            align-items: center;

            overflow: hidden;

            padding: 150px 0 90px;

            background:

                radial-gradient(

                    circle at 82% 35%,

                    rgba(232, 99, 26, .18),

                    transparent 28%

                ),

                radial-gradient(

                    circle at 12% 90%,

                    rgba(232, 99, 26, .08),

                    transparent 24%

                ),

                #080808;

        }



        .gp-shop-hero-video { position:absolute; z-index:0; inset:0; width:100%; height:100%; object-fit:cover; }
        .gp-shop-hero-video-overlay { position:absolute; z-index:1; inset:0; background:linear-gradient(90deg,rgba(8,8,8,.94) 0%,rgba(8,8,8,.80) 42%,rgba(8,8,8,.55) 100%); pointer-events:none; }

        .gp-shop-hero::before {

            content: "";

            position: absolute;

            inset: 0;

            opacity: .18;

            pointer-events: none;

            background-image:

                linear-gradient(rgba(255,255,255,.045) 1px, transparent 1px),

                linear-gradient(90deg, rgba(255,255,255,.045) 1px, transparent 1px);

            background-size: 65px 65px;

            mask-image: linear-gradient(to bottom, #000, transparent);

        }



        .gp-shop-hero-word {

            position: absolute;

            z-index: 0;

            right: -45px;

            bottom: -72px;



            color: rgba(255,255,255,.025);



            font-size: clamp(180px, 25vw, 370px);

            line-height: .75;

            font-weight: 1000;

            letter-spacing: -.09em;



            user-select: none;

            pointer-events: none;

        }



        .gp-shop-hero-glow {

            position: absolute;

            width: 480px;

            height: 480px;

            right: -160px;

            top: -180px;



            border-radius: 50%;

            background: rgba(232,99,26,.14);

            filter: blur(100px);



            pointer-events: none;

        }



        .gp-shop-hero-inner {

            position: relative;

            z-index: 2;



            display: grid;

            grid-template-columns: minmax(0, 1fr) 320px;

            align-items: end;

            gap: 80px;

        }



        .gp-shop-eyebrow {

            display: inline-flex;

            align-items: center;

            gap: 11px;



            margin-bottom: 24px;



            color: var(--shop-orange-light);



            font-size: 11px;

            line-height: 1;

            font-weight: 900;

            letter-spacing: .18em;

            text-transform: uppercase;

        }



        .gp-shop-eyebrow::before {

            content: "";

            width: 31px;

            height: 2px;

            border-radius: 20px;

            background: currentColor;

        }



        .gp-shop-hero-title {

            max-width: 850px;

            margin: 0;



            color: #fff;



            font-size: clamp(52px, 6.2vw, 91px);

            line-height: .93;

            font-weight: 950;

            letter-spacing: -.065em;

        }



        .gp-shop-hero-title span {

            color: var(--shop-orange);

        }



        .gp-shop-hero-text {

            max-width: 650px;

            margin: 28px 0 0;



            color: rgba(255,255,255,.62);



            font-size: 15px;

            line-height: 1.85;

        }





        /* hero meta */



        .gp-shop-hero-meta {

            position: relative;



            padding: 27px;



            border: 1px solid rgba(255,255,255,.10);

            border-radius: 24px;



            background: rgba(255,255,255,.045);

            backdrop-filter: blur(15px);

        }



        .gp-shop-hero-meta::before {

            content: "";

            position: absolute;

            top: -1px;

            left: 28px;



            width: 55px;

            height: 2px;



            background: var(--shop-orange);

        }



        .gp-shop-meta-number {

            color: #fff;



            font-size: 46px;

            line-height: 1;

            font-weight: 950;

            letter-spacing: -.05em;

        }



        .gp-shop-meta-label {

            margin-top: 8px;



            color: rgba(255,255,255,.50);



            font-size: 10px;

            font-weight: 800;

            letter-spacing: .12em;

            text-transform: uppercase;

        }



        .gp-shop-meta-line {

            height: 1px;

            margin: 23px 0;

            background: rgba(255,255,255,.10);

        }



        .gp-shop-meta-types {

            display: flex;

            flex-wrap: wrap;

            gap: 8px;

        }



        .gp-shop-meta-type {

            padding: 8px 11px;



            border: 1px solid rgba(255,255,255,.09);

            border-radius: 100px;



            color: rgba(255,255,255,.68);

            background: rgba(255,255,255,.04);



            font-size: 9px;

            font-weight: 800;

            letter-spacing: .08em;

            text-transform: uppercase;

        }





        /* =========================================================

           CATALOGUE

        ========================================================= */



        .gp-shop-catalogue {

            position: relative;

            padding: 105px 0 120px;



            background:

                radial-gradient(

                    circle at 100% 0,

                    rgba(232,99,26,.06),

                    transparent 22%

                ),

                #fff;

        }



        .gp-shop-section-head {

            display: flex;

            justify-content: space-between;

            align-items: flex-end;

            gap: 40px;



            margin-bottom: 52px;

        }



        .gp-shop-section-label {

            display: inline-flex;

            align-items: center;

            gap: 10px;



            margin-bottom: 14px;



            color: var(--shop-orange);



            font-size: 10px;

            line-height: 1;

            font-weight: 900;

            letter-spacing: .16em;

            text-transform: uppercase;

        }



        .gp-shop-section-label::before {

            content: "";

            width: 27px;

            height: 2px;

            background: currentColor;

        }



        .gp-shop-section-title {

            margin: 0;



            color: #151515;



            font-size: clamp(38px, 4vw, 58px);

            line-height: 1;

            font-weight: 950;

            letter-spacing: -.055em;

        }



        .gp-shop-section-title span {

            color: var(--shop-orange);

        }



        .gp-shop-section-description {

            max-width: 420px;

            margin: 0;



            color: #777;



            font-size: 13px;

            line-height: 1.8;

        }





        /* =========================================================

           TOOLBAR

        ========================================================= */



        .gp-shop-toolbar {

            display: grid;

            grid-template-columns: minmax(280px, 1fr) auto;

            align-items: center;

            gap: 20px;



            margin-bottom: 48px;

            padding: 14px;



            border: 1px solid var(--shop-line);

            border-radius: 23px;



            background: #fafaf8;

        }



        .gp-shop-search {

            position: relative;

        }



        .gp-shop-search svg {

            position: absolute;

            top: 50%;

            left: 19px;



            width: 18px;

            height: 18px;



            color: #aaa;



            transform: translateY(-50%);

            pointer-events: none;

        }



        .gp-shop-search input {

            width: 100%;

            height: 54px;



            padding: 0 20px 0 51px;



            border: 1px solid transparent;

            border-radius: 15px;

            outline: none;



            color: #222;

            background: #fff;



            font-size: 13px;



            box-shadow: 0 4px 15px rgba(0,0,0,.025);



            transition:

                border-color .25s ease,

                box-shadow .25s ease;

        }



        .gp-shop-search input::placeholder {

            color: #aaa;

        }



        .gp-shop-search input:focus {

            border-color: rgba(232,99,26,.35);

            box-shadow: 0 0 0 4px rgba(232,99,26,.07);

        }





        /* filters */



        .gp-shop-filters {

            display: flex;

            align-items: center;

            flex-wrap: wrap;

            gap: 7px;

        }



        .gp-shop-filter {

            display: inline-flex;

            align-items: center;

            justify-content: center;



            min-height: 43px;

            padding: 0 16px;



            border-radius: 12px;



            color: #777;

            background: transparent;



            text-decoration: none !important;



            font-size: 10px;

            font-weight: 850;

            letter-spacing: .03em;



            transition: .25s ease;

        }



        .gp-shop-filter:hover {

            color: #171717;

            background: #eee;

        }



        .gp-shop-filter.is-active {

            color: #fff;

            background: #171717;



            box-shadow: 0 8px 20px rgba(0,0,0,.13);

        }





        /* =========================================================

           GRID

        ========================================================= */



        .gp-shop-grid {

            display: grid;

            grid-template-columns: repeat(3, minmax(0, 1fr));

            gap: 27px;

        }





        /* =========================================================

           PRODUCT CARD

        ========================================================= */



        .gp-product {

            position: relative;

            overflow: hidden;



            display: flex;

            flex-direction: column;



            border: 1px solid #ebebe7;

            border-radius: 27px;



            background: #fff;



            box-shadow: 0 10px 35px rgba(0,0,0,.045);



            transition:

                transform .4s cubic-bezier(.2,.8,.2,1),

                box-shadow .4s ease,

                border-color .4s ease;

        }



        .gp-product:hover {

            transform: translateY(-8px);



            border-color: rgba(232,99,26,.22);



            box-shadow:

                0 30px 70px rgba(0,0,0,.10);

        }





        /* media */



        .gp-product-media-link {

            display: block;

            text-decoration: none;

        }



        .gp-product-media {

            position: relative;

            overflow: hidden;



            aspect-ratio: 1 / .82;



            background:

                linear-gradient(

                    145deg,

                    #f4f4f1,

                    #ecece8

                );

        }



        .gp-product-media::after {

            content: "";

            position: absolute;

            inset: 0;



            background:

                linear-gradient(

                    to top,

                    rgba(0,0,0,.18),

                    transparent 45%

                );



            opacity: 0;

            transition: opacity .4s ease;

            pointer-events: none;

        }



        .gp-product:hover .gp-product-media::after {

            opacity: 1;

        }



        .gp-product-image {

            display: block;



            width: 100%;

            height: 100%;



            object-fit: cover;



            transition:

                transform .75s cubic-bezier(.2,.8,.2,1);

        }



        .gp-product:hover .gp-product-image {

            transform: scale(1.055);

        }





        /* placeholder */



        .gp-product-placeholder {

            width: 100%;

            height: 100%;



            display: grid;

            place-items: center;



            background:

                radial-gradient(

                    circle at 65% 25%,

                    rgba(232,99,26,.15),

                    transparent 28%

                ),

                #f3f3f0;

        }



        .gp-product-placeholder-icon {

            width: 74px;

            height: 74px;



            display: grid;

            place-items: center;



            border-radius: 21px;



            color: var(--shop-orange);

            background: #fff;



            box-shadow: 0 14px 35px rgba(0,0,0,.08);

        }



        .gp-product-placeholder-icon svg {

            width: 31px;

            height: 31px;

        }





        /* badges */



        .gp-product-badges {

            position: absolute;

            z-index: 5;

            top: 17px;

            left: 17px;

            right: 17px;



            display: flex;

            justify-content: space-between;

            align-items: flex-start;

            gap: 10px;



            pointer-events: none;

        }



        .gp-product-badge {

            display: inline-flex;

            align-items: center;

            gap: 6px;



            min-height: 29px;

            padding: 0 11px;



            border-radius: 100px;



            font-size: 8px;

            font-weight: 900;

            letter-spacing: .09em;

            text-transform: uppercase;



            box-shadow: 0 7px 20px rgba(0,0,0,.08);

        }



        .gp-product-badge-type {

            color: #222;

            background: rgba(255,255,255,.94);

            backdrop-filter: blur(10px);

        }



        .gp-product-badge-free {

            color: #fff;

            background: var(--shop-orange);

        }





        /* play video */



        .gp-product-play {

            position: absolute;

            z-index: 4;

            top: 50%;

            left: 50%;



            width: 62px;

            height: 62px;



            display: grid;

            place-items: center;



            border: 1px solid rgba(255,255,255,.65);

            border-radius: 50%;



            color: #fff;

            background: rgba(0,0,0,.45);

            backdrop-filter: blur(8px);



            transform: translate(-50%, -50%);



            transition:

                transform .3s ease,

                background .3s ease;

        }



        .gp-product:hover .gp-product-play {

            background: var(--shop-orange);



            transform:

                translate(-50%, -50%)

                scale(1.08);

        }



        .gp-product-play svg {

            width: 21px;

            height: 21px;

            margin-left: 3px;

        }





        /* =========================================================

           PRODUCT BODY

        ========================================================= */



        .gp-product-body {

            flex: 1;



            display: flex;

            flex-direction: column;



            padding: 25px 25px 21px;

        }



        .gp-product-type {

            margin-bottom: 10px;



            color: var(--shop-orange);



            font-size: 9px;

            font-weight: 900;

            letter-spacing: .13em;

            text-transform: uppercase;

        }



        .gp-product-title {

            margin: 0;



            color: #171717;



            font-size: 21px;

            line-height: 1.2;

            font-weight: 900;

            letter-spacing: -.025em;



            transition: color .25s ease;

        }



        .gp-product-title-link {

            text-decoration: none !important;

        }



        .gp-product-title-link:hover .gp-product-title {

            color: var(--shop-orange);

        }



        .gp-product-description {

            display: -webkit-box;

            overflow: hidden;



            margin: 12px 0 0;



            color: #818181;



            font-size: 12px;

            line-height: 1.7;



            -webkit-line-clamp: 2;

            -webkit-box-orient: vertical;

        }





        /* price/action */



        .gp-product-purchase {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;



            margin-top: auto;

            padding-top: 25px;

        }



        .gp-product-price-label {

            margin-bottom: 4px;



            color: #aaa;



            font-size: 8px;

            font-weight: 800;

            letter-spacing: .10em;

            text-transform: uppercase;

        }



        .gp-product-price {

            color: #171717;



            font-size: 20px;

            line-height: 1;

            font-weight: 950;

            letter-spacing: -.025em;

        }



        .gp-product-arrow {

            width: 44px;

            height: 44px;



            display: grid;

            place-items: center;



            flex-shrink: 0;



            border-radius: 50%;



            color: #fff;

            background: #171717;



            transition:

                transform .3s ease,

                background .3s ease;

        }



        .gp-product:hover .gp-product-arrow {

            background: var(--shop-orange);

            transform: rotate(-35deg);

        }



        .gp-product-arrow svg {

            width: 17px;

            height: 17px;

        }





        /* =========================================================

           PRODUCT STATS

        ========================================================= */



        .gp-product-stats {

            display: flex;

            align-items: center;

            gap: 18px;



            margin-top: 21px;

            padding-top: 17px;



            border-top: 1px solid #eeeeeb;

        }



        .gp-product-stat {

            display: inline-flex;

            align-items: center;

            gap: 6px;



            color: #aaa;



            font-size: 9px;

            font-weight: 750;

        }



        .gp-product-stat svg {

            width: 14px;

            height: 14px;

        }



        .gp-product-stat:last-child {

            margin-left: auto;

        }





        /* =========================================================

           EMPTY

        ========================================================= */



        .gp-shop-empty {

            position: relative;



            padding: 100px 30px;



            border: 1px dashed #ddd;

            border-radius: 30px;



            text-align: center;



            background: #fafaf8;

        }



        .gp-shop-empty-icon {

            width: 74px;

            height: 74px;



            display: grid;

            place-items: center;



            margin: 0 auto 23px;



            border-radius: 22px;



            color: var(--shop-orange);

            background: rgba(232,99,26,.09);

        }



        .gp-shop-empty-icon svg {

            width: 31px;

            height: 31px;

        }



        .gp-shop-empty h3 {

            margin: 0;



            color: #171717;



            font-size: 24px;

            font-weight: 900;

        }



        .gp-shop-empty p {

            max-width: 440px;

            margin: 11px auto 0;



            color: #888;



            font-size: 13px;

            line-height: 1.7;

        }



        .gp-shop-reset {

            display: inline-flex;

            align-items: center;

            justify-content: center;



            margin-top: 25px;

            padding: 13px 20px;



            border-radius: 12px;



            color: #fff !important;

            background: #171717;



            text-decoration: none !important;



            font-size: 11px;

            font-weight: 850;



            transition: .3s ease;

        }



        .gp-shop-reset:hover {

            background: var(--shop-orange);

            transform: translateY(-2px);

        }





        /* =========================================================

           PAGINATION

        ========================================================= */



        .gp-shop-pagination {

            margin-top: 55px;

        }





        /* =========================================================

           FOOT CTA

        ========================================================= */



        .gp-shop-bottom {

            position: relative;

            overflow: hidden;



            padding: 100px 24px;



            text-align: center;



            background: #090909;

        }



        .gp-shop-bottom::before {

            content: "";

            position: absolute;

            top: 50%;

            left: 50%;



            width: 620px;

            height: 620px;



            border-radius: 50%;



            background:

                radial-gradient(

                    circle,

                    rgba(232,99,26,.18),

                    transparent 65%

                );



            transform: translate(-50%, -50%);

        }



        .gp-shop-bottom::after {

            content: "PUSH";



            position: absolute;

            left: 50%;

            bottom: -90px;



            color: rgba(255,255,255,.025);



            font-size: clamp(180px, 26vw, 360px);

            line-height: .8;

            font-weight: 1000;

            letter-spacing: -.09em;



            transform: translateX(-50%);

        }



        .gp-shop-bottom-inner {

            position: relative;

            z-index: 2;



            max-width: 760px;

            margin-inline: auto;

        }



        .gp-shop-bottom-label {

            color: var(--shop-orange);



            font-size: 10px;

            font-weight: 900;

            letter-spacing: .18em;

            text-transform: uppercase;

        }



        .gp-shop-bottom-title {

            margin: 18px 0 0;



            color: #fff;



            font-size: clamp(38px, 5vw, 65px);

            line-height: .98;

            font-weight: 950;

            letter-spacing: -.055em;

        }



        .gp-shop-bottom-title span {

            color: var(--shop-orange);

        }



        .gp-shop-bottom-text {

            max-width: 570px;

            margin: 22px auto 0;



            color: rgba(255,255,255,.58);



            font-size: 13px;

            line-height: 1.8;

        }





        /* =========================================================

           RESPONSIVE

        ========================================================= */



        @media (max-width: 1050px) {



            .gp-shop-hero-inner {

                grid-template-columns: 1fr 270px;

                gap: 45px;

            }



            .gp-shop-grid {

                grid-template-columns: repeat(2, minmax(0, 1fr));

            }

        }





        @media (max-width: 850px) {



            .gp-shop-hero {

                min-height: auto;

                padding: 135px 0 80px;

            }



            .gp-shop-hero-inner {

                grid-template-columns: 1fr;

                align-items: start;

            }



            .gp-shop-hero-meta {

                width: min(100%, 390px);

            }



            .gp-shop-section-head {

                display: block;

            }



            .gp-shop-section-description {

                margin-top: 20px;

            }



            .gp-shop-toolbar {

                grid-template-columns: 1fr;

            }

        }





        @media (max-width: 650px) {



            .gp-shop-container {

                width: min(100% - 32px, 1180px);

            }



            .gp-shop-hero {

                padding: 120px 0 65px;

            }



            .gp-shop-hero-title {

                font-size: clamp(47px, 14vw, 67px);

            }



            .gp-shop-hero-text {

                font-size: 13px;

            }



            .gp-shop-hero-meta {

                width: 100%;

            }



            .gp-shop-catalogue {

                padding: 75px 0 85px;

            }



            .gp-shop-section-head {

                margin-bottom: 35px;

            }



            .gp-shop-section-title {

                font-size: 40px;

            }



            .gp-shop-toolbar {

                padding: 10px;

                border-radius: 19px;

            }



            .gp-shop-filters {

                display: grid;

                grid-template-columns: repeat(2, 1fr);

                width: 100%;

            }



            .gp-shop-filter {

                width: 100%;

            }



            .gp-shop-grid {

                grid-template-columns: 1fr;

            }



            .gp-product-media {

                aspect-ratio: 1 / .82;

            }



            .gp-shop-bottom {

                padding: 80px 20px;

            }

        }





        @media (prefers-reduced-motion: reduce) {



            .gp-shop *,

            .gp-shop *::before,

            .gp-shop *::after {

                transition-duration: .01ms !important;

                animation-duration: .01ms !important;

            }

        }

    </style>





    <main class="gp-shop">



        {{-- =========================================================

             HERO

        ========================================================== --}}



        <section class="gp-shop-hero">
            @if($page->hasActiveBannerVideo())
                <video class="gp-shop-hero-video" autoplay muted loop playsinline @if($page->bannerPosterUrl()) poster="{{ $page->bannerPosterUrl() }}" @endif>
                    <source src="{{ $page->bannerVideoUrl() }}">
                </video>
                <div class="gp-shop-hero-video-overlay"></div>
            @endif



            <div class="gp-shop-hero-glow"></div>

            <div class="gp-shop-hero-word">PUSH</div>



            <div class="gp-shop-container gp-shop-hero-inner">



                <div data-aos="fade-up">



                    <div class="gp-shop-eyebrow">{{ $page->shop_hero_eyebrow }}</div>
                    <h1 class="gp-shop-hero-title">{{ $page->shop_hero_title }} <span>{{ $page->shop_hero_highlight }}</span></h1>
                    <p class="gp-shop-hero-text">{{ $page->shop_hero_text }}</p>



                </div>





                <aside

                    class="gp-shop-hero-meta"

                    data-aos="fade-left"

                    data-aos-delay="150"

                >



                    <div class="gp-shop-meta-number">

                        {{ number_format($productCount) }}

                    </div>



                    <div class="gp-shop-meta-label">

                        Ressource{{ $productCount > 1 ? 's' : '' }} disponible{{ $productCount > 1 ? 's' : '' }}

                    </div>



                    <div class="gp-shop-meta-line"></div>



                    <div class="gp-shop-meta-types">



                        <span class="gp-shop-meta-type">

                            Livres

                        </span>



                        <span class="gp-shop-meta-type">

                            Vidéos

                        </span>



                        <span class="gp-shop-meta-type">

                            Clés USB

                        </span>



                    </div>



                </aside>



            </div>



        </section>







        {{-- =========================================================

             CATALOGUE

        ========================================================== --}}



        <section class="gp-shop-catalogue">



            <div class="gp-shop-container">



                {{-- HEADER --}}



                <div class="gp-shop-section-head">



                    <div data-aos="fade-up">



                        <div class="gp-shop-section-label">{{ $page->shop_catalogue_eyebrow }}</div>
                        <h2 class="gp-shop-section-title">{{ $page->shop_catalogue_title }} <span>{{ $page->shop_catalogue_highlight }}</span></h2>



                    </div>





                    <p

                        class="gp-shop-section-description"

                        data-aos="fade-up"

                        data-aos-delay="100"

                    >
                        {{ $page->shop_catalogue_text }}
                    </p>



                </div>







                {{-- =====================================================

                     RECHERCHE + FILTRES

                ====================================================== --}}



                <div

                    class="gp-shop-toolbar"

                    data-aos="fade-up"

                >



                    {{-- RECHERCHE --}}



                    <form

                        method="GET"

                        action="{{ route('front.shop.index') }}"

                        class="gp-shop-search"

                    >



                        @if($type)

                            <input

                                type="hidden"

                                name="type"

                                value="{{ $type }}"

                            >

                        @endif



                        <svg

                            viewBox="0 0 24 24"

                            fill="none"

                            stroke="currentColor"

                            stroke-width="2"

                            stroke-linecap="round"

                            stroke-linejoin="round"

                        >

                            <circle cx="11" cy="11" r="7"></circle>

                            <path d="m20 20-3.5-3.5"></path>

                        </svg>



                        <input

                            type="search"

                            name="search"

                            value="{{ $search }}"

                            placeholder="Rechercher un livre, une vidéo, une ressource..."

                            aria-label="Rechercher dans la boutique"

                        >



                    </form>







                    {{-- FILTRES --}}



                    <nav

                        class="gp-shop-filters"

                        aria-label="Catégories de la boutique"

                    >



                        @foreach ($filters as $value => $label)



                            @php

                                $isActive =

                                    ($type === $value)

                                    || (!$type && $value === '');

                            @endphp



                            <a

                                href="{{ route('front.shop.index', array_filter([

                                    'type' => $value ?: null,

                                    'search' => $search ?: null,

                                ])) }}"

                                class="gp-shop-filter {{ $isActive ? 'is-active' : '' }}"

                            >

                                {{ $label }}

                            </a>



                        @endforeach



                    </nav>



                </div>







                {{-- =====================================================

                     PRODUITS

                ====================================================== --}}



                @if ($products->isEmpty())



                    <div

                        class="gp-shop-empty"

                        data-aos="fade-up"

                    >



                        <div class="gp-shop-empty-icon">



                            <svg

                                viewBox="0 0 24 24"

                                fill="none"

                                stroke="currentColor"

                                stroke-width="1.8"

                                stroke-linecap="round"

                                stroke-linejoin="round"

                            >

                                <circle cx="11" cy="11" r="7"></circle>

                                <path d="m20 20-3.5-3.5"></path>

                            </svg>



                        </div>



                        <h3>

                            Aucune ressource trouvée

                        </h3>



                        <p>

                            Aucun produit ne correspond actuellement

                            à ta recherche. Essaie un autre mot-clé

                            ou affiche l'ensemble de la boutique.

                        </p>



                        <a

                            href="{{ route('front.shop.index') }}"

                            class="gp-shop-reset"

                        >

                            Voir toutes les ressources

                        </a>



                    </div>



                @else



                    <div class="gp-shop-grid">



                        @foreach ($products as $i => $product)



                            <article

                                class="gp-product"

                                data-aos="fade-up"

                                data-aos-delay="{{ ($i % 3) * 80 }}"

                            >



                                {{-- =========================================

                                     MEDIA

                                ========================================== --}}



                                <a

                                    href="{{ route('front.shop.show', $product->slug) }}"

                                    class="gp-product-media-link"

                                    aria-label="Voir {{ $product->title }}"

                                >



                                    <div class="gp-product-media">



                                        @if ($product->imageUrl())



                                            <img

                                                src="{{ $product->imageUrl() }}"

                                                alt="{{ $product->title }}"

                                                class="gp-product-image"

                                                loading="lazy"

                                            >



                                        @else



                                            <div class="gp-product-placeholder">



                                                <div class="gp-product-placeholder-icon">



                                                    @if ($product->type === 'video')



                                                        <svg

                                                            viewBox="0 0 24 24"

                                                            fill="currentColor"

                                                        >

                                                            <path d="M8 5.14v13.72a1 1 0 0 0 1.5.86l10-6.86a1 1 0 0 0 0-1.72l-10-6.86A1 1 0 0 0 8 5.14Z"/>

                                                        </svg>



                                                    @elseif ($product->type === 'usb_key')



                                                        <svg

                                                            viewBox="0 0 24 24"

                                                            fill="none"

                                                            stroke="currentColor"

                                                            stroke-width="1.8"

                                                            stroke-linecap="round"

                                                            stroke-linejoin="round"

                                                        >

                                                            <rect x="6" y="3" width="12" height="18" rx="2"></rect>

                                                            <path d="M9 7h6"></path>

                                                            <path d="M9 11h6"></path>

                                                            <circle cx="12" cy="16" r="1"></circle>

                                                        </svg>



                                                    @else



                                                        <svg

                                                            viewBox="0 0 24 24"

                                                            fill="none"

                                                            stroke="currentColor"

                                                            stroke-width="1.8"

                                                            stroke-linecap="round"

                                                            stroke-linejoin="round"

                                                        >

                                                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>

                                                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"></path>

                                                        </svg>



                                                    @endif



                                                </div>



                                            </div>



                                        @endif







                                        {{-- BADGES --}}



                                        <div class="gp-product-badges">



                                            <span class="gp-product-badge gp-product-badge-type">

                                                {{ $product->typeLabel() }}

                                            </span>



                                            @if ($product->is_free)



                                                <span class="gp-product-badge gp-product-badge-free">

                                                    Gratuit

                                                </span>



                                            @endif



                                        </div>







                                        {{-- PLAY POUR VIDÉOS --}}



                                        @if ($product->type === 'video')



                                            <span class="gp-product-play">



                                                <svg

                                                    viewBox="0 0 24 24"

                                                    fill="currentColor"

                                                >

                                                    <path d="M8 5.14v13.72a1 1 0 0 0 1.5.86l10-6.86a1 1 0 0 0 0-1.72l-10-6.86A1 1 0 0 0 8 5.14Z"/>

                                                </svg>



                                            </span>



                                        @endif



                                    </div>



                                </a>







                                {{-- =========================================

                                     BODY

                                ========================================== --}}



                                <div class="gp-product-body">



                                    <div class="gp-product-type">

                                        {{ $product->typeLabel() }}

                                    </div>





                                    <a

                                        href="{{ route('front.shop.show', $product->slug) }}"

                                        class="gp-product-title-link"

                                    >



                                        <h3 class="gp-product-title">

                                            {{ $product->title }}

                                        </h3>



                                    </a>





                                    @if ($product->description)



                                        <p class="gp-product-description">

                                            {{ $product->description }}

                                        </p>



                                    @endif







                                    {{-- PRIX + ACTION --}}



                                    <div class="gp-product-purchase">



                                        <div>



                                            <div class="gp-product-price-label">

                                                {{ $product->is_free ? 'Accès' : 'Prix' }}

                                            </div>



                                            <div class="gp-product-price">

                                                {{ $product->priceLabel() }}

                                            </div>



                                        </div>





                                        <a

                                            href="{{ route('front.shop.show', $product->slug) }}"

                                            class="gp-product-arrow"

                                            aria-label="Découvrir {{ $product->title }}"

                                        >



                                            <svg

                                                viewBox="0 0 24 24"

                                                fill="none"

                                                stroke="currentColor"

                                                stroke-width="2"

                                                stroke-linecap="round"

                                                stroke-linejoin="round"

                                            >

                                                <path d="M5 12h14"></path>

                                                <path d="m13 6 6 6-6 6"></path>

                                            </svg>



                                        </a>



                                    </div>







                                    {{-- =========================================

                                         STATS

                                    ========================================== --}}



                                    <div class="gp-product-stats">



                                        {{-- LIKES --}}



                                        <span

                                            class="gp-product-stat"

                                            title="J'aime"

                                        >



                                            <svg

                                                viewBox="0 0 24 24"

                                                fill="none"

                                                stroke="currentColor"

                                                stroke-width="2"

                                                stroke-linecap="round"

                                                stroke-linejoin="round"

                                            >

                                                <path d="M20.8 8.6c0 5.5-8.8 10.4-8.8 10.4S3.2 14.1 3.2 8.6A4.6 4.6 0 0 1 12 6.1a4.6 4.6 0 0 1 8.8 2.5Z"></path>

                                            </svg>



                                            {{ $product->likes_count ?? 0 }}



                                        </span>







                                        {{-- SAVES --}}



                                        <span

                                            class="gp-product-stat"

                                            title="Enregistrements"

                                        >



                                            <svg

                                                viewBox="0 0 24 24"

                                                fill="none"

                                                stroke="currentColor"

                                                stroke-width="2"

                                                stroke-linecap="round"

                                                stroke-linejoin="round"

                                            >

                                                <path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3-6 3V4Z"></path>

                                            </svg>



                                            {{ $product->saves_count ?? 0 }}



                                        </span>







                                        {{-- COMMANDES --}}



                                        <span

                                            class="gp-product-stat"

                                            title="Commandes"

                                        >



                                            <svg

                                                viewBox="0 0 24 24"

                                                fill="none"

                                                stroke="currentColor"

                                                stroke-width="2"

                                                stroke-linecap="round"

                                                stroke-linejoin="round"

                                            >

                                                <path d="M6 7h12l1 14H5L6 7Z"></path>

                                                <path d="M9 7a3 3 0 0 1 6 0"></path>

                                            </svg>



                                            {{ $product->orders_count ?? 0 }}



                                        </span>



                                    </div>



                                </div>



                            </article>



                        @endforeach



                    </div>







                    {{-- ================================================

                         PAGINATION

                    ================================================= --}}



                    @if(method_exists($products, 'hasPages') && $products->hasPages())



                        <div class="gp-shop-pagination">

                            {{ $products->withQueryString()->links() }}

                        </div>



                    @endif



                @endif



            </div>



        </section>







        {{-- =========================================================

             CTA FINAL

        ========================================================== --}}



        <section class="gp-shop-bottom">



            <div

                class="gp-shop-bottom-inner"

                data-aos="fade-up"

            >



                <div class="gp-shop-bottom-label">{{ $page->shop_cta_eyebrow }}</div>
                <h2 class="gp-shop-bottom-title">{{ $page->shop_cta_title }} <span>{{ $page->shop_cta_highlight }}</span></h2>
                <p class="gp-shop-bottom-text">{{ $page->shop_cta_text }}</p>

            </div>

        </section>
    </main>


</x-layouts.public>
