<x-layouts.public :title="$product->title . ' — Generation PUSH'">

    <style>
        /* =========================================================
           GENERATION PUSH — PRODUCT SHOW
        ========================================================= */

        .gp-product-page {
            --orange: #E8631A;
            --orange-soft: rgba(232, 99, 26, .10);
            --black: #111111;
            --dark: #080808;
            --muted: #777;
            --line: #e9e9e5;
            --soft: #f7f7f4;

            background: #fff;
            color: var(--black);
            overflow: hidden;
        }

        .gp-product-container {
            width: min(1180px, calc(100% - 48px));
            margin-inline: auto;
        }

        /* =========================================================
           TOP
        ========================================================= */

        .gp-product-top {
            position: relative;
            padding: 135px 0 105px;
            background:
                radial-gradient(
                    circle at 95% 0,
                    rgba(232, 99, 26, .08),
                    transparent 25%
                ),
                #fff;
        }

        .gp-product-breadcrumb {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 9px;

            margin-bottom: 37px;

            color: #999;

            font-size: 10px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .gp-product-breadcrumb a {
            color: #777;
            text-decoration: none;
            transition: color .2s ease;
        }

        .gp-product-breadcrumb a:hover {
            color: var(--orange);
        }

        .gp-product-breadcrumb svg {
            width: 12px;
            height: 12px;
        }

        .gp-product-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.03fr) minmax(0, .97fr);
            gap: 78px;
            align-items: start;
        }

        /* =========================================================
           MEDIA
        ========================================================= */

        .gp-product-visual-column {
            position: relative;
        }

        .gp-product-visual {
            position: relative;
            overflow: hidden;

            aspect-ratio: 1 / .93;

            border-radius: 32px;
            background:
                radial-gradient(
                    circle at 70% 20%,
                    rgba(232,99,26,.13),
                    transparent 30%
                ),
                #f2f2ef;

            box-shadow:
                0 30px 70px rgba(0,0,0,.09);
        }

        .gp-product-main-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;

            transition: transform .8s cubic-bezier(.2,.8,.2,1);
        }

        .gp-product-visual:hover .gp-product-main-image {
            transform: scale(1.025);
        }

        .gp-product-placeholder {
            width: 100%;
            height: 100%;

            display: grid;
            place-items: center;
        }

        .gp-product-placeholder-inner {
            width: 105px;
            height: 105px;

            display: grid;
            place-items: center;

            border-radius: 30px;

            color: var(--orange);
            background: #fff;

            box-shadow: 0 20px 50px rgba(0,0,0,.09);
        }

        .gp-product-placeholder-inner svg {
            width: 43px;
            height: 43px;
        }

        .gp-product-visual-badge {
            position: absolute;
            z-index: 4;
            top: 21px;
            left: 21px;

            display: inline-flex;
            align-items: center;
            gap: 7px;

            min-height: 34px;
            padding: 0 14px;

            border-radius: 100px;

            color: #222;
            background: rgba(255,255,255,.92);
            backdrop-filter: blur(12px);

            font-size: 9px;
            font-weight: 900;
            letter-spacing: .1em;
            text-transform: uppercase;

            box-shadow: 0 7px 25px rgba(0,0,0,.08);
        }

        .gp-product-free-badge {
            position: absolute;
            z-index: 4;
            top: 21px;
            right: 21px;

            min-height: 34px;
            padding: 0 14px;

            display: inline-flex;
            align-items: center;

            border-radius: 100px;

            color: #fff;
            background: var(--orange);

            font-size: 9px;
            font-weight: 900;
            letter-spacing: .09em;
            text-transform: uppercase;
        }

        /* video play */

        .gp-product-video-play {
            position: absolute;
            z-index: 4;
            top: 50%;
            left: 50%;

            width: 78px;
            height: 78px;

            display: grid;
            place-items: center;

            border: 1px solid rgba(255,255,255,.55);
            border-radius: 50%;

            color: #fff;
            background: rgba(0,0,0,.52);
            backdrop-filter: blur(10px);

            transform: translate(-50%, -50%);
        }

        .gp-product-video-play svg {
            width: 27px;
            height: 27px;
            margin-left: 4px;
        }

        /* =========================================================
           SOCIAL ACTIONS
        ========================================================= */

        .gp-product-social {
            display: flex;
            align-items: center;
            gap: 9px;

            margin-top: 19px;
        }

        .gp-social-button {
            height: 45px;

            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;

            padding: 0 15px;

            border: 1px solid var(--line);
            border-radius: 13px;

            color: #666;
            background: #fff;

            font-size: 10px;
            font-weight: 800;

            cursor: pointer;

            transition:
                color .25s ease,
                border-color .25s ease,
                background .25s ease,
                transform .25s ease;
        }

        .gp-social-button:hover {
            color: var(--orange);
            border-color: rgba(232,99,26,.25);
            background: var(--orange-soft);
            transform: translateY(-2px);
        }

        .gp-social-button svg {
            width: 16px;
            height: 16px;
        }

        /* =========================================================
           CONTENT
        ========================================================= */

        .gp-product-content {
            padding-top: 5px;
        }

        .gp-product-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 10px;

            margin-bottom: 17px;

            color: var(--orange);

            font-size: 10px;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .gp-product-eyebrow::before {
            content: "";
            width: 28px;
            height: 2px;
            background: currentColor;
        }

        .gp-product-title {
            margin: 0;

            color: #151515;

            font-size: clamp(39px, 4.5vw, 65px);
            line-height: .98;
            font-weight: 950;
            letter-spacing: -.055em;
        }

        .gp-product-description {
            margin: 25px 0 0;

            color: #727272;

            font-size: 14px;
            line-height: 1.85;
            white-space: pre-line;
        }

        /* price */

        .gp-product-price-block {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 25px;

            margin-top: 34px;
            padding: 25px 0;

            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
        }

        .gp-product-price-label {
            margin-bottom: 7px;

            color: #aaa;

            font-size: 9px;
            font-weight: 850;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .gp-product-price {
            color: #171717;

            font-size: 30px;
            line-height: 1;
            font-weight: 950;
            letter-spacing: -.035em;
        }

        .gp-product-stock {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 9px 12px;

            border-radius: 100px;

            font-size: 9px;
            font-weight: 850;
        }

        .gp-product-stock.is-available {
            color: #177544;
            background: #eaf8f0;
        }

        .gp-product-stock.is-empty {
            color: #b83232;
            background: #fceded;
        }

        .gp-product-stock-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* =========================================================
           STATS
        ========================================================= */

        .gp-product-stats {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 23px;

            margin-top: 25px;
        }

        .gp-product-stat {
            display: flex;
            align-items: center;
            gap: 8px;

            color: #888;

            font-size: 10px;
            font-weight: 750;
        }

        .gp-product-stat svg {
            width: 15px;
            height: 15px;
            color: var(--orange);
        }

        .gp-product-stat strong {
            color: #333;
            font-weight: 900;
        }

        /* =========================================================
           CTA ORDER
        ========================================================= */

        .gp-product-order {
            margin-top: 32px;
        }

        .gp-product-primary-action {
            width: 100%;
            min-height: 60px;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;

            padding: 0 23px;

            border: 0;
            border-radius: 16px;

            color: #fff !important;
            background: var(--orange);

            text-decoration: none !important;

            font-size: 12px;
            font-weight: 900;
            letter-spacing: .02em;

            box-shadow:
                0 16px 35px rgba(232,99,26,.23);

            transition:
                transform .3s ease,
                box-shadow .3s ease,
                background .3s ease;
        }

        .gp-product-primary-action:hover {
            color: #fff;
            background: #d85813;

            transform: translateY(-3px);

            box-shadow:
                0 22px 45px rgba(232,99,26,.30);
        }

        .gp-product-action-main {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .gp-product-action-main svg {
            width: 19px;
            height: 19px;
        }

        .gp-product-action-price {
            padding-left: 18px;
            border-left: 1px solid rgba(255,255,255,.25);
        }

        .gp-product-login-note {
            display: flex;
            align-items: flex-start;
            gap: 8px;

            margin-top: 13px;

            color: #999;

            font-size: 10px;
            line-height: 1.6;
        }

        .gp-product-login-note svg {
            width: 14px;
            height: 14px;
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* =========================================================
           BENEFITS
        ========================================================= */

        .gp-product-benefits {
            padding: 80px 0;
            background: #0b0b0b;
        }

        .gp-benefits-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1px;

            overflow: hidden;

            border: 1px solid rgba(255,255,255,.09);
            border-radius: 25px;

            background: rgba(255,255,255,.09);
        }

        .gp-benefit {
            position: relative;
            padding: 35px;

            background: #0e0e0e;
        }

        .gp-benefit-number {
            position: absolute;
            top: 24px;
            right: 25px;

            color: rgba(255,255,255,.08);

            font-size: 35px;
            line-height: 1;
            font-weight: 950;
        }

        .gp-benefit-icon {
            width: 46px;
            height: 46px;

            display: grid;
            place-items: center;

            margin-bottom: 28px;

            border-radius: 14px;

            color: var(--orange);
            background: rgba(232,99,26,.10);
        }

        .gp-benefit-icon svg {
            width: 20px;
            height: 20px;
        }

        .gp-benefit h3 {
            margin: 0;

            color: #fff;

            font-size: 16px;
            font-weight: 850;
        }

        .gp-benefit p {
            margin: 10px 0 0;

            color: rgba(255,255,255,.47);

            font-size: 11px;
            line-height: 1.75;
        }

        /* =========================================================
           RELATED
        ========================================================= */

        .gp-related {
            padding: 105px 0 120px;
            background: #fff;
        }

        .gp-related-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 30px;

            margin-bottom: 47px;
        }

        .gp-related-eyebrow {
            margin-bottom: 13px;

            color: var(--orange);

            font-size: 10px;
            font-weight: 900;
            letter-spacing: .15em;
            text-transform: uppercase;
        }

        .gp-related-title {
            margin: 0;

            color: #151515;

            font-size: clamp(36px, 4vw, 55px);
            line-height: 1;
            font-weight: 950;
            letter-spacing: -.05em;
        }

        .gp-related-title span {
            color: var(--orange);
        }

        .gp-related-back {
            display: inline-flex;
            align-items: center;
            gap: 9px;

            color: #555 !important;

            text-decoration: none !important;

            font-size: 10px;
            font-weight: 850;

            transition: color .2s ease;
        }

        .gp-related-back:hover {
            color: var(--orange) !important;
        }

        .gp-related-back svg {
            width: 15px;
            height: 15px;
        }

        .gp-related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .gp-related-card {
            overflow: hidden;

            border: 1px solid var(--line);
            border-radius: 24px;

            color: inherit !important;
            background: #fff;

            text-decoration: none !important;

            transition:
                transform .35s ease,
                box-shadow .35s ease,
                border-color .35s ease;
        }

        .gp-related-card:hover {
            transform: translateY(-7px);
            border-color: rgba(232,99,26,.20);
            box-shadow: 0 25px 55px rgba(0,0,0,.09);
        }

        .gp-related-image {
            position: relative;
            overflow: hidden;

            aspect-ratio: 16 / 10;

            background: #f2f2ef;
        }

        .gp-related-image img {
            width: 100%;
            height: 100%;

            display: block;
            object-fit: cover;

            transition: transform .6s ease;
        }

        .gp-related-card:hover .gp-related-image img {
            transform: scale(1.05);
        }

        .gp-related-placeholder {
            width: 100%;
            height: 100%;

            display: grid;
            place-items: center;

            color: #bbb;
        }

        .gp-related-placeholder svg {
            width: 35px;
            height: 35px;
        }

        .gp-related-body {
            padding: 22px;
        }

        .gp-related-type {
            color: var(--orange);

            font-size: 8px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .gp-related-name {
            margin: 8px 0 0;

            color: #181818;

            font-size: 17px;
            line-height: 1.3;
            font-weight: 900;
        }

        .gp-related-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;

            margin-top: 19px;
        }

        .gp-related-price {
            color: #222;
            font-size: 14px;
            font-weight: 900;
        }

        .gp-related-arrow {
            width: 35px;
            height: 35px;

            display: grid;
            place-items: center;

            border-radius: 50%;

            color: #fff;
            background: #171717;

            transition: background .25s ease;
        }

        .gp-related-card:hover .gp-related-arrow {
            background: var(--orange);
        }

        .gp-related-arrow svg {
            width: 14px;
            height: 14px;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 1000px) {
            .gp-product-layout {
                gap: 45px;
            }
        }

        @media (max-width: 850px) {
            .gp-product-top {
                padding-top: 120px;
            }

            .gp-product-layout {
                grid-template-columns: 1fr;
            }

            .gp-product-visual-column {
                max-width: 680px;
            }

            .gp-benefits-grid {
                grid-template-columns: 1fr;
            }

            .gp-related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 620px) {
            .gp-product-container {
                width: min(100% - 32px, 1180px);
            }

            .gp-product-top {
                padding: 105px 0 75px;
            }

            .gp-product-breadcrumb {
                margin-bottom: 27px;
            }

            .gp-product-visual {
                border-radius: 24px;
            }

            .gp-product-title {
                font-size: 42px;
            }

            .gp-product-price-block {
                align-items: flex-start;
                flex-direction: column;
            }

            .gp-product-social {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
            }

            .gp-social-button {
                padding: 0 7px;
            }

            .gp-social-button span:last-child {
                display: none;
            }

            .gp-related {
                padding: 75px 0 90px;
            }

            .gp-related-head {
                display: block;
            }

            .gp-related-back {
                margin-top: 20px;
            }

            .gp-related-grid {
                grid-template-columns: 1fr;
            }

            .gp-product-primary-action {
                min-height: 58px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .gp-product-page *,
            .gp-product-page *::before,
            .gp-product-page *::after {
                transition-duration: .01ms !important;
            }
        }
    </style>


    <main class="gp-product-page">

        {{-- =========================================================
             PRODUCT HERO
        ========================================================== --}}

        <section class="gp-product-top">

            <div class="gp-product-container">

                {{-- BREADCRUMB --}}

                <nav
                    class="gp-product-breadcrumb"
                    aria-label="Fil d'Ariane"
                >
                    <a href="{{ route('front.shop.index') }}">
                        Boutique
                    </a>

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <span>
                        {{ $product->typeLabel() }}
                    </span>

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path d="m9 18 6-6-6-6"/>
                    </svg>

                    <span>
                        {{ \Illuminate\Support\Str::limit($product->title, 35) }}
                    </span>
                </nav>


                <div class="gp-product-layout">

                    {{-- =================================================
                         VISUEL
                    ================================================== --}}

                    <div
                        class="gp-product-visual-column"
                        data-aos="fade-right"
                    >

                        <div class="gp-product-visual">

                            @if ($product->imageUrl())

                                <img
                                    src="{{ $product->imageUrl() }}"
                                    alt="{{ $product->title }}"
                                    class="gp-product-main-image"
                                >

                            @else

                                <div class="gp-product-placeholder">

                                    <div class="gp-product-placeholder-inner">

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
                                            >
                                                <rect x="6" y="3" width="12" height="18" rx="2"/>
                                                <path d="M9 7h6"/>
                                                <path d="M9 11h6"/>
                                                <circle cx="12" cy="16" r="1"/>
                                            </svg>

                                        @else

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                            </svg>

                                        @endif

                                    </div>

                                </div>

                            @endif


                            <span class="gp-product-visual-badge">
                                {{ $product->typeLabel() }}
                            </span>


                            @if($product->is_free)
                                <span class="gp-product-free-badge">
                                    Gratuit
                                </span>
                            @endif


                            @if($product->type === 'video')

                                <div class="gp-product-video-play">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <path d="M8 5.14v13.72a1 1 0 0 0 1.5.86l10-6.86a1 1 0 0 0 0-1.72l-10-6.86A1 1 0 0 0 8 5.14Z"/>
                                    </svg>

                                </div>

                            @endif

                        </div>


                        {{-- =============================================
                             SOCIAL
                        ============================================== --}}

                        <div class="gp-product-social">

                            {{-- LIKE --}}

                            <button
                                type="button"
                                class="gp-social-button"
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
                                    <path d="M20.8 8.6c0 5.5-8.8 10.4-8.8 10.4S3.2 14.1 3.2 8.6A4.6 4.6 0 0 1 12 6.1a4.6 4.6 0 0 1 8.8 2.5Z"/>
                                </svg>

                                <strong>
                                    {{ $product->likes_count ?? 0 }}
                                </strong>

                                <span>J'aime</span>
                            </button>


                            {{-- SHARE --}}

                            <button
                                type="button"
                                id="share-product"
                                class="gp-social-button"
                                title="Partager"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <circle cx="18" cy="5" r="3"/>
                                    <circle cx="6" cy="12" r="3"/>
                                    <circle cx="18" cy="19" r="3"/>
                                    <path d="m8.6 10.5 6.8-4"/>
                                    <path d="m8.6 13.5 6.8 4"/>
                                </svg>

                                <strong>
                                    {{ $product->shares_count ?? 0 }}
                                </strong>

                                <span>Partager</span>
                            </button>


                            {{-- SAVE --}}

                            <button
                                type="button"
                                class="gp-social-button"
                                title="Sauvegarder"
                            >
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3-6 3V4Z"/>
                                </svg>

                                <strong>
                                    {{ $product->saves_count ?? 0 }}
                                </strong>

                                <span>Sauvegarder</span>
                            </button>

                        </div>

                    </div>



                    {{-- =================================================
                         PRODUCT INFO
                    ================================================== --}}

                    <div
                        class="gp-product-content"
                        data-aos="fade-left"
                    >

                        <div class="gp-product-eyebrow">
                            {{ $product->typeLabel() }}
                        </div>


                        <h1 class="gp-product-title">
                            {{ $product->title }}
                        </h1>


                        @if($product->description)

                            <div class="gp-product-description">
                                {{ $product->description }}
                            </div>

                        @endif


                        {{-- =============================================
                             PRICE
                        ============================================== --}}

                        <div class="gp-product-price-block">

                            <div>

                                <div class="gp-product-price-label">
                                    {{ $product->is_free ? 'Accès' : 'Prix' }}
                                </div>

                                <div class="gp-product-price">
                                    {{ $product->priceLabel() }}
                                </div>

                            </div>


                            @if($product->type === 'usb_key')

                                @if(($product->stock ?? 0) > 0)

                                    <div class="gp-product-stock is-available">

                                        <span class="gp-product-stock-dot"></span>

                                        En stock · {{ $product->stock }}

                                    </div>

                                @else

                                    <div class="gp-product-stock is-empty">

                                        <span class="gp-product-stock-dot"></span>

                                        Rupture de stock

                                    </div>

                                @endif

                            @endif

                        </div>


                        {{-- =============================================
                             MINI STATS
                        ============================================== --}}

                        <div class="gp-product-stats">

                            <div class="gp-product-stat">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M20.8 8.6c0 5.5-8.8 10.4-8.8 10.4S3.2 14.1 3.2 8.6A4.6 4.6 0 0 1 12 6.1a4.6 4.6 0 0 1 8.8 2.5Z"/>
                                </svg>

                                <strong>
                                    {{ $product->likes_count ?? 0 }}
                                </strong>

                                J'aime

                            </div>


                            <div class="gp-product-stat">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18l-6-3-6 3V4Z"/>
                                </svg>

                                <strong>
                                    {{ $product->saves_count ?? 0 }}
                                </strong>

                                sauvegarde{{ ($product->saves_count ?? 0) > 1 ? 's' : '' }}

                            </div>


                            <div class="gp-product-stat">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M6 7h12l1 14H5L6 7Z"/>
                                    <path d="M9 7a3 3 0 0 1 6 0"/>
                                </svg>

                                <strong>
                                    {{ $product->orders_count ?? 0 }}
                                </strong>

                                commande{{ ($product->orders_count ?? 0) > 1 ? 's' : '' }}

                            </div>

                        </div>


                        {{-- =============================================
                             ORDER / DOWNLOAD
                        ============================================== --}}

                        <div class="gp-product-order">

                            {{-- FREE FILE --}}

                            @if ($product->is_free && $product->fileUrl())

                                <a
                                    href="{{ $product->fileUrl() }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="gp-product-primary-action"
                                >

                                    <span class="gp-product-action-main">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >
                                            <path d="M12 3v12"/>
                                            <path d="m7 10 5 5 5-5"/>
                                            <path d="M5 21h14"/>
                                        </svg>

                                        Télécharger gratuitement

                                    </span>

                                    <span>→</span>

                                </a>


                            {{-- FREE VIDEO --}}

                            @elseif ($product->is_free && $product->video_url)

                                <a
                                    href="{{ $product->video_url }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="gp-product-primary-action"
                                >

                                    <span class="gp-product-action-main">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="m10 8 6 4-6 4Z"/>
                                        </svg>

                                        Regarder gratuitement

                                    </span>

                                    <span>→</span>

                                </a>


                            {{-- PAID --}}

                            @else

                                @if(Route::has('front.shop.order.create'))

                                    <a
                                        href="{{ auth()->check()
                                            ? route('front.shop.order.create', $product->slug)
                                            : route('login', [
                                                'redirect' => route(
                                                    'front.shop.order.create',
                                                    $product->slug
                                                )
                                            ])
                                        }}"
                                        class="gp-product-primary-action"
                                    >

                                        <span class="gp-product-action-main">

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            >
                                                <path d="M6 7h12l1 14H5L6 7Z"/>
                                                <path d="M9 7a3 3 0 0 1 6 0"/>
                                            </svg>

                                            Commander maintenant

                                        </span>

                                        <span class="gp-product-action-price">
                                            {{ $product->priceLabel() }}
                                        </span>

                                    </a>

                                @endif


                                @guest

                                    <div class="gp-product-login-note">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <circle cx="12" cy="12" r="9"/>
                                            <path d="M12 11v5"/>
                                            <path d="M12 8h.01"/>
                                        </svg>

                                        <span>
                                            Connecte-toi pour confirmer ta commande.
                                            Tes coordonnées seront récupérées automatiquement.
                                        </span>

                                    </div>

                                @endguest

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </section>



        {{-- =========================================================
             BENEFITS
        ========================================================== --}}

        @unless($product->is_free)

            <section class="gp-product-benefits">

                <div class="gp-product-container">

                    <div class="gp-benefits-grid">

                        {{-- 01 --}}

                        <article
                            class="gp-benefit"
                            data-aos="fade-up"
                        >

                            <span class="gp-benefit-number">
                                01
                            </span>

                            <div class="gp-benefit-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M20 21a8 8 0 0 0-16 0"/>
                                    <circle cx="12" cy="7" r="4"/>
                                    <path d="m16 11 2 2 4-4"/>
                                </svg>

                            </div>

                            <h3>
                                Commande simple
                            </h3>

                            <p>
                                Connecte-toi et tes informations sont
                                automatiquement récupérées pour faciliter
                                ta demande.
                            </p>

                        </article>


                        {{-- 02 --}}

                        <article
                            class="gp-benefit"
                            data-aos="fade-up"
                            data-aos-delay="100"
                        >

                            <span class="gp-benefit-number">
                                02
                            </span>

                            <div class="gp-benefit-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4Z"/>
                                </svg>

                            </div>

                            <h3>
                                Contact direct
                            </h3>

                            <p>
                                Après ta demande, l'équipe Generation PUSH
                                peut te contacter directement pour
                                finaliser la commande.
                            </p>

                        </article>


                        {{-- 03 --}}

                        <article
                            class="gp-benefit"
                            data-aos="fade-up"
                            data-aos-delay="200"
                        >

                            <span class="gp-benefit-number">
                                03
                            </span>

                            <div class="gp-benefit-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                                    <path d="m9 12 2 2 4-4"/>
                                </svg>

                            </div>

                            <h3>
                                Suivi Generation PUSH
                            </h3>

                            <p>
                                Chaque commande est enregistrée et suivie
                                par notre équipe jusqu'à sa finalisation.
                            </p>

                        </article>

                    </div>

                </div>

            </section>

        @endunless



        {{-- =========================================================
             RELATED PRODUCTS
        ========================================================== --}}

        @if($related->isNotEmpty())

            <section class="gp-related">

                <div class="gp-product-container">

                    <div class="gp-related-head">

                        <div>

                            <div class="gp-related-eyebrow">
                                À découvrir aussi
                            </div>

                            <h2 class="gp-related-title">
                                Continue ton
                                <span>parcours.</span>
                            </h2>

                        </div>


                        <a
                            href="{{ route('front.shop.index') }}"
                            class="gp-related-back"
                        >
                            Voir toute la boutique

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M5 12h14"/>
                                <path d="m13 6 6 6-6 6"/>
                            </svg>
                        </a>

                    </div>


                    <div class="gp-related-grid">

                        @foreach($related as $i => $r)

                            <a
                                href="{{ route('front.shop.show', $r->slug) }}"
                                class="gp-related-card"
                                data-aos="fade-up"
                                data-aos-delay="{{ ($i % 3) * 90 }}"
                            >

                                <div class="gp-related-image">

                                    @if($r->imageUrl())

                                        <img
                                            src="{{ $r->imageUrl() }}"
                                            alt="{{ $r->title }}"
                                            loading="lazy"
                                        >

                                    @else

                                        <div class="gp-related-placeholder">

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                                            </svg>

                                        </div>

                                    @endif

                                </div>


                                <div class="gp-related-body">

                                    <div class="gp-related-type">
                                        {{ $r->typeLabel() }}
                                    </div>

                                    <h3 class="gp-related-name">
                                        {{ $r->title }}
                                    </h3>

                                    <div class="gp-related-footer">

                                        <span class="gp-related-price">
                                            {{ $r->priceLabel() }}
                                        </span>

                                        <span class="gp-related-arrow">

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                            >
                                                <path d="M5 12h14"/>
                                                <path d="m13 6 6 6-6 6"/>
                                            </svg>

                                        </span>

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>

                </div>

            </section>

        @endif

    </main>



    {{-- =============================================================
         SHARE
    ============================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const shareButton = document.getElementById('share-product');

            if (!shareButton) {
                return;
            }

            shareButton.addEventListener('click', async function () {

                const shareData = {
                    title: @json($product->title),
                    text: @json('Découvre cette ressource sur Generation PUSH'),
                    url: window.location.href
                };

                /*
                |--------------------------------------------------------------------------
                | Native Share API
                |--------------------------------------------------------------------------
                */

                if (navigator.share) {

                    try {
                        await navigator.share(shareData);
                    } catch (error) {
                        // L'utilisateur a fermé la fenêtre.
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Clipboard fallback
                |--------------------------------------------------------------------------
                */

                try {

                    await navigator.clipboard.writeText(
                        window.location.href
                    );

                    const original = shareButton.innerHTML;

                    shareButton.innerHTML = `
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>

                        <strong>Copié</strong>
                        <span>Lien copié</span>
                    `;

                    setTimeout(function () {
                        shareButton.innerHTML = original;
                    }, 1800);

                } catch (error) {
                    // Presse-papier non disponible.
                }

            });

        });
    </script>

</x-layouts.public>