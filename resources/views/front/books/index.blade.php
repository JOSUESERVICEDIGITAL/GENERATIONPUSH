@extends('layouts.front')

@section('title', 'Le Livre | Generation PUSH')

@section('content')

<style>
    /* ============================================================
       GENERATION PUSH — BOOK EXPERIENCE
       ============================================================ */

    :root {
        --push-orange: #E8631A;
        --push-black: #1A1A1A;
        --push-dark: #090909;
        --push-soft: #f7f7f5;
    }

    .book-page {
        overflow: hidden;
        background: #fff;
    }

    /* ========================= HERO ========================= */

    .book-hero {
        position: relative;
        min-height: 92vh;
        display: flex;
        align-items: center;
        padding: 150px 0 90px;
        color: #fff;
        background:
            radial-gradient(
                circle at 76% 45%,
                rgba(232, 99, 26, .24),
                transparent 27%
            ),
            radial-gradient(
                circle at 15% 30%,
                rgba(232, 99, 26, .08),
                transparent 30%
            ),
            linear-gradient(
                135deg,
                #050505 0%,
                #101010 45%,
                #080808 100%
            );
    }

    .book-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: .08;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(255,255,255,.07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,.07) 1px, transparent 1px);
        background-size: 70px 70px;
        mask-image: linear-gradient(to bottom, black, transparent 90%);
    }

    .book-hero::after {
        content: "";
        position: absolute;
        width: 520px;
        height: 520px;
        right: -220px;
        top: -220px;
        border-radius: 50%;
        border: 1px solid rgba(232,99,26,.15);
        box-shadow:
            0 0 0 80px rgba(232,99,26,.025),
            0 0 0 160px rgba(232,99,26,.018);
    }

    .book-container {
        width: min(1240px, calc(100% - 40px));
        margin: 0 auto;
        display: grid;
        grid-template-columns: 1fr 1fr;
        align-items: center;
        gap: 70px;
        position: relative;
        z-index: 2;
    }

    /* ========================= TEXT ========================= */

    .book-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 8px 14px;
        border: 1px solid rgba(232,99,26,.35);
        background: rgba(232,99,26,.08);
        border-radius: 999px;
        color: #ff9a61;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .16em;
        text-transform: uppercase;
        backdrop-filter: blur(12px);
    }

    .book-eyebrow-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: var(--push-orange);
        box-shadow: 0 0 15px var(--push-orange);
    }

    .book-title {
        margin-top: 24px;
        font-size: clamp(48px, 6vw, 88px);
        line-height: .95;
        letter-spacing: -.055em;
        font-weight: 900;
        max-width: 700px;
    }

    .book-title span {
        color: var(--push-orange);
    }

    .book-subtitle {
        margin-top: 22px;
        max-width: 620px;
        color: rgba(255,255,255,.68);
        font-size: 19px;
        line-height: 1.7;
    }

    .book-author {
        margin-top: 18px;
        color: rgba(255,255,255,.85);
        font-weight: 600;
    }

    .book-author strong {
        color: #fff;
    }

    .book-price-zone {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        margin-top: 30px;
    }

    .book-current-price {
        font-size: 35px;
        font-weight: 900;
        color: #fff;
        line-height: 1;
    }

    .book-old-price {
        color: rgba(255,255,255,.38);
        font-size: 18px;
        text-decoration: line-through;
    }

    .book-promo {
        padding: 5px 9px;
        background: rgba(232,99,26,.14);
        color: #ff9a61;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .book-actions {
        margin-top: 30px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .take-book {
        position: relative;
        overflow: hidden;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        min-height: 57px;
        padding: 0 28px;
        border-radius: 14px;
        background: var(--push-orange);
        color: #fff;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .05em;
        text-decoration: none;
        box-shadow: 0 15px 45px rgba(232,99,26,.30);
        transition: .35s ease;
    }

    .take-book::before {
        content: "";
        position: absolute;
        top: 0;
        left: -120%;
        width: 70%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255,255,255,.25),
            transparent
        );
        transform: skewX(-20deg);
        transition: .7s;
    }

    .take-book:hover {
        color: #fff;
        transform: translateY(-4px);
        box-shadow: 0 22px 55px rgba(232,99,26,.42);
    }

    .take-book:hover::before {
        left: 140%;
    }

    .book-format-label {
        color: rgba(255,255,255,.48);
        font-size: 13px;
    }

    /* ============================================================
       REALISTIC 3D BOOK
       ============================================================ */

    .book-stage {
        position: relative;
        height: 620px;
        display: flex;
        justify-content: center;
        align-items: center;
        perspective: 1400px;
    }

    .stage-light {
        position: absolute;
        width: 430px;
        height: 430px;
        border-radius: 50%;
        background: radial-gradient(
            circle,
            rgba(232,99,26,.30) 0%,
            rgba(232,99,26,.10) 38%,
            transparent 70%
        );
        filter: blur(20px);
        animation: stagePulse 5s ease-in-out infinite;
    }

    @keyframes stagePulse {
        0%, 100% {
            opacity: .7;
            transform: scale(.95);
        }

        50% {
            opacity: 1;
            transform: scale(1.08);
        }
    }

    .floating-book {
        position: relative;
        z-index: 5;
        width: 300px;
        height: 440px;
        transform-style: preserve-3d;
        transform:
            rotateY(-25deg)
            rotateX(4deg)
            rotateZ(-2deg);
        animation: floatBook 5s ease-in-out infinite;
        transition: transform .6s cubic-bezier(.2,.8,.2,1);
    }

    .book-stage:hover .floating-book {
        transform:
            rotateY(-10deg)
            rotateX(1deg)
            rotateZ(0deg)
            translateY(-12px);
    }

    @keyframes floatBook {
        0%, 100% {
            translate: 0 0;
        }

        50% {
            translate: 0 -16px;
        }
    }

    .book-front {
        position: absolute;
        inset: 0;
        overflow: hidden;
        border-radius: 4px 12px 12px 4px;
        background: #222;
        transform: translateZ(24px);
        box-shadow:
            -15px 15px 35px rgba(0,0,0,.35),
            35px 45px 80px rgba(0,0,0,.55);
    }

    .book-front img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
    }

    .book-front::after {
        content: "";
        position: absolute;
        inset: 0;
        background:
            linear-gradient(
                90deg,
                rgba(0,0,0,.24),
                transparent 11%,
                transparent 70%,
                rgba(255,255,255,.09)
            );
        pointer-events: none;
    }

    .book-pages {
        position: absolute;
        width: 47px;
        height: 426px;
        right: -22px;
        top: 7px;
        transform:
            rotateY(90deg)
            translateZ(1px);
        background:
            repeating-linear-gradient(
                90deg,
                #e8e5dc 0px,
                #fff 1px,
                #dedbd2 2px,
                #f6f4ee 3px
            );
        border-radius: 2px;
    }

    .book-spine {
        position: absolute;
        width: 48px;
        height: 440px;
        left: -24px;
        top: 0;
        transform: rotateY(-90deg);
        background: linear-gradient(
            90deg,
            #111,
            #292929,
            #0c0c0c
        );
    }

    .book-bottom {
        position: absolute;
        width: 296px;
        height: 48px;
        left: 2px;
        bottom: -24px;
        transform: rotateX(90deg);
        background:
            repeating-linear-gradient(
                0deg,
                #ddd9d0 0px,
                #faf8f2 1px,
                #d5d1c8 2px
            );
    }

    /* ========================= PODIUM ========================= */

    .podium {
        position: absolute;
        bottom: 30px;
        width: 470px;
        height: 105px;
        z-index: 2;
    }

    .podium-top {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 65px;
        border-radius: 50%;
        background:
            radial-gradient(
                ellipse at center,
                #444 0%,
                #242424 43%,
                #0c0c0c 75%
            );
        border: 1px solid rgba(255,255,255,.08);
        box-shadow:
            inset 0 8px 20px rgba(255,255,255,.04),
            0 -8px 45px rgba(232,99,26,.18);
    }

    .podium-body {
        position: absolute;
        top: 31px;
        left: 0;
        width: 100%;
        height: 70px;
        background: linear-gradient(
            90deg,
            #070707,
            #1b1b1b 25%,
            #272727 50%,
            #111 75%,
            #050505
        );
        border-radius: 0 0 48% 48% / 0 0 30% 30%;
        box-shadow: 0 35px 70px rgba(0,0,0,.6);
    }

    .podium-body::after {
        content: "";
        position: absolute;
        bottom: -2px;
        left: 5%;
        width: 90%;
        height: 6px;
        border-radius: 50%;
        background: var(--push-orange);
        filter: blur(7px);
        opacity: .65;
    }

    .book-shadow {
        position: absolute;
        bottom: 98px;
        width: 280px;
        height: 50px;
        background: rgba(0,0,0,.75);
        border-radius: 50%;
        filter: blur(18px);
        transform: rotate(-4deg);
        z-index: 3;
    }

    .bestseller-float {
        position: absolute;
        right: 20px;
        top: 70px;
        z-index: 8;
        width: 105px;
        height: 105px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        text-align: center;
        background: var(--push-orange);
        color: #fff;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .08em;
        transform: rotate(9deg);
        box-shadow:
            0 15px 45px rgba(232,99,26,.4),
            inset 0 0 0 5px rgba(255,255,255,.1);
    }

    .bestseller-float strong {
        font-size: 16px;
        line-height: 1;
    }

    /* ========================= GENERAL SECTIONS ========================= */

    .book-section {
        padding: 110px 20px;
    }

    .section-inner {
        width: min(1180px, 100%);
        margin: auto;
    }

    .section-label {
        color: var(--push-orange);
        font-size: 12px;
        font-weight: 900;
        letter-spacing: .17em;
        text-transform: uppercase;
    }

    .section-title {
        margin-top: 12px;
        color: var(--push-black);
        font-size: clamp(34px, 4vw, 56px);
        line-height: 1.05;
        letter-spacing: -.04em;
        font-weight: 900;
    }

    .section-description {
        color: #626262;
        font-size: 18px;
        line-height: 1.9;
    }

    /* ========================= WHY ========================= */

    .why-section {
        background: #fff;
    }

    .why-grid {
        display: grid;
        grid-template-columns: .8fr 1.2fr;
        gap: 100px;
        align-items: start;
    }

    .why-number {
        display: inline-flex;
        width: 54px;
        height: 54px;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #fff2ea;
        color: var(--push-orange);
        font-weight: 900;
    }

    .quote-line {
        margin-top: 30px;
        width: 65px;
        height: 5px;
        border-radius: 999px;
        background: var(--push-orange);
    }

    /* ========================= DISCOVER ========================= */

    .discover-section {
        background: #f7f7f5;
    }

    .discover-header {
        max-width: 750px;
        margin-bottom: 55px;
    }

    .discover-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .discover-card {
        position: relative;
        min-height: 210px;
        padding: 30px;
        border-radius: 24px;
        background: #fff;
        border: 1px solid #ededeb;
        transition: .35s ease;
        overflow: hidden;
    }

    .discover-card::before {
        content: "";
        position: absolute;
        width: 100px;
        height: 100px;
        right: -45px;
        top: -45px;
        border-radius: 50%;
        background: rgba(232,99,26,.07);
        transition: .35s;
    }

    .discover-card:hover {
        transform: translateY(-8px);
        border-color: rgba(232,99,26,.3);
        box-shadow: 0 25px 50px rgba(0,0,0,.07);
    }

    .discover-card:hover::before {
        transform: scale(1.5);
    }

    .discover-icon {
        width: 45px;
        height: 45px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: #fff1e8;
        color: var(--push-orange);
        margin-bottom: 25px;
    }

    .discover-card p {
        position: relative;
        z-index: 2;
        color: #444;
        font-size: 16px;
        line-height: 1.7;
        font-weight: 600;
    }

    /* ========================= DETAILS ========================= */

    .details-section {
        color: #fff;
        background:
            radial-gradient(
                circle at 10% 50%,
                rgba(232,99,26,.10),
                transparent 25%
            ),
            #0b0b0b;
    }

    .details-section .section-title {
        color: #fff;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 1px;
        margin-top: 55px;
        background: rgba(255,255,255,.09);
        border: 1px solid rgba(255,255,255,.09);
        border-radius: 25px;
        overflow: hidden;
    }

    .detail-item {
        padding: 30px 20px;
        text-align: center;
        background: #101010;
    }

    .detail-icon {
        width: 42px;
        height: 42px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        background: rgba(232,99,26,.12);
        color: var(--push-orange);
    }

    .detail-value {
        color: #fff;
        font-size: 15px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .detail-label {
        margin-top: 5px;
        color: rgba(255,255,255,.4);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .1em;
    }

    /* ========================= OTHER BOOKS ========================= */

    .other-books {
        background: #fff;
    }


    /* ============================================================
   AUTRES LIVRES — SLIDER
   ============================================================ */

.books-slider-wrapper {
    position: relative;
    margin-top: 55px;
}

.books-swiper {
    overflow: hidden;
    padding: 15px 5px 55px;
}

.books-swiper .swiper-slide {
    height: auto;
}

.books-swiper .other-book-card {
    height: 100%;
}

/* Flèches placées aux extrémités de la section */

.books-slider-arrow {
    position: absolute;
    top: 42%;
    z-index: 20;

    width: 58px;
    height: 58px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid rgba(0, 0, 0, .08);
    border-radius: 50%;

    background: #fff;
    color: #181818;

    box-shadow:
        0 12px 35px rgba(0, 0, 0, .12);

    cursor: pointer;

    transition:
        transform .3s ease,
        background .3s ease,
        color .3s ease,
        box-shadow .3s ease;
}

.books-slider-arrow:hover {
    background: var(--push-orange);
    color: #fff;

    transform: translateY(-50%) scale(1.08);

    box-shadow:
        0 15px 40px rgba(232, 99, 26, .30);
}

.books-slider-prev {
    left: -85px;
    transform: translateY(-50%);
}

.books-slider-next {
    right: -85px;
    transform: translateY(-50%);
}

.books-slider-prev:hover,
.books-slider-next:hover {
    transform: translateY(-50%) scale(1.08);
}


/* Pagination */

.books-swiper-pagination {
    position: relative !important;
    bottom: auto !important;
    margin-top: 12px;
}

.books-swiper-pagination .swiper-pagination-bullet {
    width: 8px;
    height: 8px;

    background: #c9c9c9;
    opacity: 1;

    transition: .3s ease;
}

.books-swiper-pagination .swiper-pagination-bullet-active {
    width: 28px;

    border-radius: 999px;

    background: var(--push-orange);
}


/* Bouton désactivé */

.books-slider-arrow.swiper-button-disabled {
    opacity: .25;
    cursor: not-allowed;
}


/* Grand écran */

@media (max-width: 1380px) {

    .books-slider-prev {
        left: -30px;
    }

    .books-slider-next {
        right: -30px;
    }
}


/* Tablette */

@media (max-width: 1050px) {

    .books-slider-prev {
        left: 5px;
    }

    .books-slider-next {
        right: 5px;
    }

    .books-slider-arrow {
        width: 50px;
        height: 50px;
    }
}


/* Mobile */

@media (max-width: 700px) {

    .books-slider-arrow {
        width: 44px;
        height: 44px;

        top: 40%;
    }

    .books-slider-prev {
        left: 3px;
    }

    .books-slider-next {
        right: 3px;
    }

    .books-swiper {
        padding-left: 12px;
        padding-right: 12px;
    }
}
   

    .other-book-card {
        position: relative;
        padding: 18px;
        border: 1px solid #eee;
        border-radius: 24px;
        background: #fff;
        transition: .35s ease;
    }

    .other-book-card:hover {
        transform: translateY(-10px);
        border-color: rgba(232,99,26,.25);
        box-shadow: 0 25px 60px rgba(0,0,0,.08);
    }

    .other-cover {
        position: relative;
        aspect-ratio: 3 / 4;
        overflow: hidden;
        border-radius: 17px;
        background: #f2f2f2;
    }

    .other-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: .5s;
    }

    .other-book-card:hover .other-cover img {
        transform: scale(1.04);
    }

    .other-book-info {
        padding: 20px 5px 5px;
    }

    .other-book-title {
        color: #171717;
        font-size: 18px;
        font-weight: 900;
        line-height: 1.25;
    }

    .other-book-author {
        margin-top: 7px;
        color: #8a8a8a;
        font-size: 13px;
    }

    .other-book-footer {
        margin-top: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .other-book-price {
        color: var(--push-orange);
        font-size: 17px;
        font-weight: 900;
    }

    .other-take {
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 13px;
        color: #fff;
        background: #181818;
        transition: .25s;
    }

    .other-take:hover {
        color: #fff;
        background: var(--push-orange);
        transform: translateX(3px);
    }

    /* ========================= EMPTY ========================= */

    .books-empty {
        min-height: 70vh;
        padding: 160px 20px 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #0b0b0b;
        color: #fff;
    }

    /* ========================= RESPONSIVE ========================= */

    @media (max-width: 1050px) {
        .book-container {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .book-copy {
            max-width: 760px;
            margin: auto;
        }

        .book-title,
        .book-subtitle {
            margin-left: auto;
            margin-right: auto;
        }

        .book-price-zone,
        .book-actions {
            justify-content: center;
        }

        .book-stage {
            height: 600px;
        }

        .why-grid {
            grid-template-columns: 1fr;
            gap: 45px;
        }

        .discover-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .details-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        
    }

    @media (max-width: 700px) {
        .book-hero {
            padding-top: 125px;
        }

        .book-container {
            width: min(100% - 28px, 1240px);
            gap: 25px;
        }

        .book-title {
            font-size: 45px;
        }

        .book-subtitle {
            font-size: 16px;
        }

        .book-stage {
            height: 500px;
            transform: scale(.82);
            margin: -35px 0;
        }

        .discover-grid,
        .details-grid,
        
        .book-section {
            padding: 75px 20px;
        }

        .take-book {
            width: 100%;
        }

        .book-format-label {
            width: 100%;
        }
    }
</style>


<div class="book-page">

    @if ($featuredBook)

        @php
            /*
             * On transforme "discover_content" en plusieurs cartes.
             * On accepte les retours à la ligne, points multiples ou puces.
             */
            $discoverItems = collect(
                preg_split(
                    '/(?:\r\n|\r|\n|•|\s{3,})/',
                    (string) $featuredBook->discover_content
                )
            )
                ->map(fn ($item) => trim($item, " \t\n\r\0\x0B.-"))
                ->filter()
                ->values();

            $hasPromotion =
                !is_null($featuredBook->promotional_price)
                && (float) $featuredBook->promotional_price
                    < (float) $featuredBook->price;
        @endphp


        {{-- ======================================================
             HERO + LIVRE 3D
        ======================================================= --}}

        <section class="book-hero">

            <div class="book-container">

                {{-- TEXTE --}}

                <div
                    class="book-copy"
                    data-aos="fade-right"
                    data-aos-duration="900"
                >

                    <div class="book-eyebrow">

                        <span class="book-eyebrow-dot"></span>

                        @if ($featuredBook->is_bestseller)
                            Best-seller Generation PUSH
                        @else
                            Le livre Generation PUSH
                        @endif

                    </div>


                    <h1 class="book-title">
                        {{ $featuredBook->title }}
                    </h1>


                    @if ($featuredBook->subtitle)

                        <p class="book-subtitle">
                            {{ $featuredBook->subtitle }}
                        </p>

                    @endif


                    @if ($featuredBook->author)

                        <p class="book-author">
                            Un livre de
                            <strong>{{ $featuredBook->author }}</strong>
                        </p>

                    @endif


                    {{-- PRIX --}}

                    <div class="book-price-zone">

                        <div class="book-current-price">

                            {{ number_format(
                                $featuredBook->currentPrice(),
                                0,
                                ',',
                                ' '
                            ) }}

                            <small style="font-size:14px;">
                                FCFA
                            </small>

                        </div>


                        @if ($hasPromotion)

                            <div class="book-old-price">

                                {{ number_format(
                                    (float) $featuredBook->price,
                                    0,
                                    ',',
                                    ' '
                                ) }}
                                FCFA

                            </div>

                            <span class="book-promo">
                                Offre spéciale
                            </span>

                        @endif

                    </div>


                    {{-- CTA --}}

                    <div class="book-actions">

                        @if (
                            $featuredBook->product
                            && $featuredBook->product->status === 'published'
                        )

                            <a
                                href="{{ route(
                                    'front.shop.order.create',
                                    $featuredBook->product
                                ) }}"
                                class="take-book"
                            >

                                <span>
                                    Prendre ce livre
                                </span>

                                <svg
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M5 12h14"/>
                                    <path d="m13 6 6 6-6 6"/>
                                </svg>

                            </a>

                        @else

                            <span
                                class="take-book"
                                style="
                                    opacity:.45;
                                    cursor:not-allowed;
                                "
                            >
                                Bientôt disponible
                            </span>

                        @endif


                        <span class="book-format-label">
                            {{ $featuredBook->formatLabel() }}
                        </span>

                    </div>

                </div>


                {{-- ==================================================
                     LIVRE 3D + PODIUM
                =================================================== --}}

                <div
                    class="book-stage"
                    data-aos="zoom-in"
                    data-aos-duration="1100"
                >

                    <div class="stage-light"></div>


                    @if ($featuredBook->is_bestseller)

                        <div class="bestseller-float">
                            <span>★</span>
                            <strong>BEST</strong>
                            <span>SELLER</span>
                        </div>

                    @endif


                    <div class="book-shadow"></div>


                    <div class="floating-book">

                        <div class="book-front">

                            @if ($featuredBook->coverUrl())

                                <img
                                    src="{{ $featuredBook->coverUrl() }}"
                                    alt="Couverture de {{ $featuredBook->title }}"
                                >

                            @else

                                <div
                                    style="
                                        width:100%;
                                        height:100%;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                        text-align:center;
                                        padding:30px;
                                        background:
                                            linear-gradient(
                                                135deg,
                                                #171717,
                                                #E8631A
                                            );
                                        color:white;
                                        font-weight:900;
                                        font-size:30px;
                                    "
                                >
                                    {{ $featuredBook->title }}
                                </div>

                            @endif

                        </div>

                        <div class="book-pages"></div>

                        <div class="book-spine"></div>

                        <div class="book-bottom"></div>

                    </div>


                    <div class="podium">

                        <div class="podium-top"></div>

                        <div class="podium-body"></div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ======================================================
             POURQUOI CE LIVRE
        ======================================================= --}}

        @if (
            $featuredBook->value_description
            || $featuredBook->description
        )

            <section class="book-section why-section">

                <div class="section-inner why-grid">

                    <div data-aos="fade-up">

                        <div class="why-number">
                            01
                        </div>

                        <div class="section-label" style="margin-top:25px;">
                            Une invitation à passer à l'action
                        </div>

                        <h2 class="section-title">
                            Pourquoi ce livre ?
                        </h2>

                        <div class="quote-line"></div>

                    </div>


                    <div
                        class="section-description"
                        data-aos="fade-up"
                        data-aos-delay="120"
                    >

                        @if ($featuredBook->value_description)

                            {!! nl2br(
                                e($featuredBook->value_description)
                            ) !!}

                        @else

                            {!! nl2br(
                                e($featuredBook->description)
                            ) !!}

                        @endif

                    </div>

                </div>

            </section>

        @endif


        {{-- ======================================================
             DÉCOUVRIR
        ======================================================= --}}

        @if ($discoverItems->isNotEmpty())

            <section class="book-section discover-section">

                <div class="section-inner">

                    <div
                        class="discover-header"
                        data-aos="fade-up"
                    >

                        <div class="section-label">
                            À l'intérieur
                        </div>

                        <h2 class="section-title">
                            Ce que vous allez découvrir
                        </h2>

                    </div>


                    <div class="discover-grid">

                        @foreach ($discoverItems as $item)

                            <article
                                class="discover-card"
                                data-aos="fade-up"
                                data-aos-delay="{{ min($loop->index * 80, 320) }}"
                            >

                                <div class="discover-icon">

                                    <svg
                                        width="22"
                                        height="22"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2.3"
                                    >
                                        <path d="M20 6 9 17l-5-5"/>
                                    </svg>

                                </div>

                                <p>
                                    {{ $item }}
                                </p>

                            </article>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif


        {{-- ======================================================
             INFORMATIONS DU LIVRE
        ======================================================= --}}

        <section class="book-section details-section">

            <div class="section-inner">

                <div data-aos="fade-up">

                    <div class="section-label">
                        Fiche du livre
                    </div>

                    <h2 class="section-title">
                        Quelques détails.
                    </h2>

                </div>


                <div
                    class="details-grid"
                    data-aos="fade-up"
                    data-aos-delay="100"
                >

                    {{-- PAGES --}}

                    <div class="detail-item">

                        <div class="detail-icon">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                            </svg>
                        </div>

                        <div class="detail-value">
                            {{ $featuredBook->pages ?: '—' }}
                        </div>

                        <div class="detail-label">
                            Pages
                        </div>

                    </div>


                    {{-- FORMAT --}}

                    <div class="detail-item">

                        <div class="detail-icon">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect x="3" y="4" width="18" height="16" rx="2"/>
                                <path d="M7 8h10M7 12h7"/>
                            </svg>
                        </div>

                        <div class="detail-value">
                            {{ $featuredBook->formatLabel() }}
                        </div>

                        <div class="detail-label">
                            Format
                        </div>

                    </div>


                    {{-- ÉDITEUR --}}

                    <div class="detail-item">

                        <div class="detail-icon">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M3 21h18"/>
                                <path d="M6 18V8"/>
                                <path d="M10 18V8"/>
                                <path d="M14 18V8"/>
                                <path d="M18 18V8"/>
                                <path d="m4 8 8-5 8 5"/>
                            </svg>
                        </div>

                        <div class="detail-value">
                            {{ $featuredBook->publisher ?: '—' }}
                        </div>

                        <div class="detail-label">
                            Éditeur
                        </div>

                    </div>


                    {{-- DATE --}}

                    <div class="detail-item">

                        <div class="detail-icon">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <rect x="3" y="5" width="18" height="16" rx="2"/>
                                <path d="M16 3v4M8 3v4M3 11h18"/>
                            </svg>
                        </div>

                        <div class="detail-value">

                            {{ $featuredBook->publication_date
                                ? $featuredBook->publication_date->format('Y')
                                : '—'
                            }}

                        </div>

                        <div class="detail-label">
                            Publication
                        </div>

                    </div>


                    {{-- ISBN --}}

                    <div class="detail-item">

                        <div class="detail-icon">
                            <svg
                                width="20"
                                height="20"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M3 5v14"/>
                                <path d="M7 5v14"/>
                                <path d="M11 5v14"/>
                                <path d="M15 5v14"/>
                                <path d="M19 5v14"/>
                                <path d="M21 5v14"/>
                            </svg>
                        </div>

                        <div class="detail-value">
                            {{ $featuredBook->isbn ?: '—' }}
                        </div>

                        <div class="detail-label">
                            ISBN
                        </div>

                    </div>

                </div>

            </div>

        </section>


        {{-- ======================================================
             AUTRES LIVRES
        ======================================================= --}}
{{-- ======================================================
     AUTRES LIVRES — CARROUSEL
======================================================= --}}

@if ($otherBooks->isNotEmpty())

    <section class="book-section other-books">

        <div class="section-inner">

            {{-- TITRE --}}

            <div data-aos="fade-up">

                <div class="section-label">
                    Aller plus loin
                </div>

                <h2 class="section-title">
                    Découvrez les autres livres
                </h2>

                <p
                    style="
                        max-width:650px;
                        margin-top:16px;
                        color:#777;
                        line-height:1.8;
                    "
                >
                    Continuez votre parcours avec les autres ouvrages
                    disponibles dans l'univers Generation PUSH.
                </p>

            </div>


            {{-- SLIDER --}}

            <div
                class="books-slider-wrapper"
                data-aos="fade-up"
                data-aos-delay="100"
            >

                {{-- FLÈCHE GAUCHE --}}

                <button
                    type="button"
                    class="books-slider-arrow books-slider-prev"
                    aria-label="Livre précédent"
                >
                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                </button>


                {{-- SWIPER --}}

                <div class="swiper books-swiper">

                    <div class="swiper-wrapper">

                        @foreach ($otherBooks as $book)

                            <div class="swiper-slide">

                                <article class="other-book-card">

                                    {{-- COUVERTURE --}}

                                    <div class="other-cover">

                                        @if ($book->coverUrl())

                                            <img
                                                src="{{ $book->coverUrl() }}"
                                                alt="Couverture de {{ $book->title }}"
                                                loading="lazy"
                                            >

                                        @else

                                            <div
                                                style="
                                                    width:100%;
                                                    height:100%;
                                                    display:flex;
                                                    align-items:center;
                                                    justify-content:center;
                                                    padding:25px;
                                                    text-align:center;
                                                    color:white;
                                                    font-weight:900;
                                                    background:
                                                        linear-gradient(
                                                            135deg,
                                                            #171717,
                                                            #E8631A
                                                        );
                                                "
                                            >
                                                {{ $book->title }}
                                            </div>

                                        @endif


                                        {{-- BEST-SELLER --}}

                                        @if ($book->is_bestseller)

                                            <span
                                                class="badge badge-warning"
                                                style="
                                                    position:absolute;
                                                    top:12px;
                                                    left:12px;
                                                    z-index:3;
                                                    font-weight:800;
                                                "
                                            >
                                                Best-seller
                                            </span>

                                        @endif

                                    </div>


                                    {{-- INFORMATIONS --}}

                                    <div class="other-book-info">

                                        <h3 class="other-book-title">
                                            {{ $book->title }}
                                        </h3>


                                        @if ($book->author)

                                            <p class="other-book-author">
                                                {{ $book->author }}
                                            </p>

                                        @endif


                                        <div class="other-book-footer">

                                            <div>

                                                <span class="other-book-price">

                                                    {{ number_format(
                                                        $book->currentPrice(),
                                                        0,
                                                        ',',
                                                        ' '
                                                    ) }}

                                                    FCFA

                                                </span>


                                                @if (
                                                    !is_null($book->promotional_price)
                                                    && (float) $book->promotional_price
                                                        < (float) $book->price
                                                )

                                                    <div
                                                        style="
                                                            margin-top:3px;
                                                            color:#aaa;
                                                            font-size:12px;
                                                            text-decoration:line-through;
                                                        "
                                                    >

                                                        {{ number_format(
                                                            (float) $book->price,
                                                            0,
                                                            ',',
                                                            ' '
                                                        ) }}

                                                        FCFA

                                                    </div>

                                                @endif

                                            </div>


                                            {{-- COMMANDE --}}

                                            @if (
                                                $book->product
                                                && $book->product->status === 'published'
                                            )

                                                <a
                                                    href="{{ route(
                                                        'front.shop.order.create',
                                                        $book->product
                                                    ) }}"
                                                    class="other-take"
                                                    title="Prendre ce livre"
                                                    aria-label="Prendre {{ $book->title }}"
                                                >

                                                    <svg
                                                        width="18"
                                                        height="18"
                                                        viewBox="0 0 24 24"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    >
                                                        <path d="M5 12h14"/>
                                                        <path d="m13 6 6 6-6 6"/>
                                                    </svg>

                                                </a>

                                            @endif

                                        </div>

                                    </div>

                                </article>

                            </div>

                        @endforeach

                    </div>


                    {{-- POINTS DE NAVIGATION --}}

                    <div class="swiper-pagination books-swiper-pagination"></div>

                </div>


                {{-- FLÈCHE DROITE --}}

                <button
                    type="button"
                    class="books-slider-arrow books-slider-next"
                    aria-label="Livre suivant"
                >

                    <svg
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                </button>

            </div>

        </div>

    </section>

@endif


    @else

        {{-- ======================================================
             AUCUN LIVRE PUBLIÉ
        ======================================================= --}}

        <section class="books-empty">

            <div>

                <div class="book-eyebrow">
                    <span class="book-eyebrow-dot"></span>
                    Generation PUSH
                </div>

                <h1
                    style="
                        font-size:clamp(40px,6vw,75px);
                        font-weight:900;
                        margin-top:25px;
                    "
                >
                    Le livre arrive bientôt.
                </h1>

                <p
                    style="
                        color:rgba(255,255,255,.55);
                        margin-top:15px;
                    "
                >
                    Revenez prochainement pour découvrir nos publications.
                </p>

            </div>

        </section>

    @endif

</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const slider = document.querySelector('.books-swiper');

        if (!slider || typeof Swiper === 'undefined') {
            return;
        }

        const numberOfBooks =
            slider.querySelectorAll('.swiper-slide').length;

        const swiper = new Swiper('.books-swiper', {

            /* -----------------------------------------
             * DÉFILEMENT
             * ----------------------------------------- */

            slidesPerView: 1,
            spaceBetween: 20,

            speed: 850,

            grabCursor: true,

            watchOverflow: true,

            /* Boucle seulement si suffisamment de livres */
            loop: numberOfBooks > 4,


            /* -----------------------------------------
             * AUTOPLAY
             * ----------------------------------------- */

            autoplay: numberOfBooks > 1
                ? {
                    delay: 3200,

                    /*
                     * Si l'utilisateur clique sur une flèche,
                     * l'autoplay continue ensuite.
                     */
                    disableOnInteraction: false,

                    /*
                     * Pause lorsque la souris est sur les livres.
                     */
                    pauseOnMouseEnter: true,
                }
                : false,


            /* -----------------------------------------
             * FLÈCHES
             * ----------------------------------------- */

            navigation: {
                nextEl: '.books-slider-next',
                prevEl: '.books-slider-prev',
            },


            /* -----------------------------------------
             * PAGINATION
             * ----------------------------------------- */

            pagination: {
                el: '.books-swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },


            /* -----------------------------------------
             * RESPONSIVE
             * ----------------------------------------- */

            breakpoints: {

                640: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },

                900: {
                    slidesPerView: 3,
                    spaceBetween: 24,
                },

                1200: {
                    slidesPerView: 4,
                    spaceBetween: 28,
                },

            },

        });

    });
</script>
@endsection