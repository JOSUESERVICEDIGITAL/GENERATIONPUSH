@extends('layouts.admin')

@section('title', 'Modifier la Boutique')

@section('content')

<style>
    .gp-shop-admin {
        --gp-orange: #E8631A;
        --gp-black: #1A1A1A;
        --gp-soft: #f7f7f5;
        --gp-line: #e9e9e6;

        max-width: 1180px;
        margin: 0 auto;
        padding-bottom: 60px;
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .gp-shop-admin-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 28px;
    }

    .gp-shop-admin-eyebrow {
        margin-bottom: 7px;

        color: var(--gp-orange);

        font-size: 10px;
        line-height: 1;
        font-weight: 900;
        letter-spacing: .15em;
        text-transform: uppercase;
    }

    .gp-shop-admin-head h1 {
        margin: 0;

        color: var(--gp-black);

        font-size: 30px;
        line-height: 1.1;
        font-weight: 900;
        letter-spacing: -.035em;
    }

    .gp-shop-admin-head p {
        max-width: 650px;
        margin: 9px 0 0;

        color: #7d7d7d;

        font-size: 12px;
        line-height: 1.7;
    }

    .gp-shop-view {
        flex-shrink: 0;

        min-height: 43px;
        padding: 0 16px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;

        border: 1px solid #e5e5e5;
        border-radius: 12px;

        color: #333 !important;
        background: #fff;

        text-decoration: none !important;

        font-size: 11px;
        font-weight: 800;

        transition: .25s ease;
    }

    .gp-shop-view:hover {
        color: var(--gp-orange) !important;
        border-color: rgba(232, 99, 26, .30);
        transform: translateY(-2px);
    }

    .gp-shop-view svg {
        width: 15px;
        height: 15px;
    }


    /* =========================================================
       ALERTS
    ========================================================= */

    .gp-shop-alert {
        margin-bottom: 22px;
        padding: 15px 17px;

        border-radius: 13px;

        font-size: 12px;
        line-height: 1.6;
    }

    .gp-shop-alert-success {
        color: #17633a;
        background: #eaf8f0;
        border: 1px solid #d0eddb;
    }

    .gp-shop-alert-error {
        color: #9b3030;
        background: #fff0f0;
        border: 1px solid #f3cccc;
    }

    .gp-shop-alert-error ul {
        margin: 8px 0 0 18px;
        padding: 0;
    }


    /* =========================================================
       LAYOUT
    ========================================================= */

    .gp-shop-admin-layout {
        display: grid;
        grid-template-columns: 225px minmax(0, 1fr);
        gap: 25px;
        align-items: start;
    }


    /* =========================================================
       SIDEBAR TABS
    ========================================================= */

    .gp-shop-tabs {
        position: sticky;
        top: 90px;

        padding: 10px;

        border: 1px solid var(--gp-line);
        border-radius: 18px;

        background: #fff;

        box-shadow: 0 8px 30px rgba(0, 0, 0, .025);
    }

    .gp-shop-tab {
        width: 100%;
        min-height: 48px;

        display: flex;
        align-items: center;
        gap: 11px;

        padding: 8px 11px;

        border: 0;
        border-radius: 11px;

        color: #6f6f6f;
        background: transparent;

        text-align: left;

        font-size: 11px;
        font-weight: 800;

        cursor: pointer;

        transition: .2s ease;
    }

    .gp-shop-tab + .gp-shop-tab {
        margin-top: 4px;
    }

    .gp-shop-tab:hover {
        color: #222;
        background: #f5f5f3;
    }

    .gp-shop-tab.active {
        color: #fff;
        background: var(--gp-black);

        box-shadow: 0 8px 18px rgba(0, 0, 0, .12);
    }

    .gp-shop-tab-number {
        width: 26px;
        height: 26px;

        display: grid;
        place-items: center;

        flex-shrink: 0;

        border-radius: 8px;

        color: #888;
        background: #f2f2f0;

        font-size: 9px;
        font-weight: 900;
    }

    .gp-shop-tab.active .gp-shop-tab-number {
        color: #fff;
        background: rgba(255, 255, 255, .12);
    }


    /* =========================================================
       PANELS
    ========================================================= */

    .gp-shop-panel {
        display: none;
    }

    .gp-shop-panel.active {
        display: block;
    }


    /* =========================================================
       CARD
    ========================================================= */

    .gp-shop-card {
        padding: 28px;

        border: 1px solid var(--gp-line);
        border-radius: 20px;

        background: #fff;

        box-shadow: 0 8px 30px rgba(0, 0, 0, .025);
    }

    .gp-shop-card + .gp-shop-card {
        margin-top: 20px;
    }

    .gp-shop-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;

        margin-bottom: 25px;
        padding-bottom: 20px;

        border-bottom: 1px solid #eeeeeb;
    }

    .gp-shop-card-head h2 {
        margin: 0;

        color: #181818;

        font-size: 17px;
        line-height: 1.2;
        font-weight: 900;
    }

    .gp-shop-card-head p {
        margin: 6px 0 0;

        color: #909090;

        font-size: 11px;
        line-height: 1.6;
    }

    .gp-shop-card-badge {
        flex-shrink: 0;

        padding: 7px 10px;

        border-radius: 100px;

        color: var(--gp-orange);
        background: rgba(232, 99, 26, .08);

        font-size: 8px;
        font-weight: 900;
        letter-spacing: .09em;
        text-transform: uppercase;
    }


    /* =========================================================
       FIELDS
    ========================================================= */

    .gp-shop-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .gp-shop-field-full {
        grid-column: 1 / -1;
    }

    .gp-shop-label {
        display: block;

        margin-bottom: 8px;

        color: #353535;

        font-size: 10px;
        font-weight: 850;
    }

    .gp-shop-label span {
        color: #aaa;
        font-weight: 600;
    }

    .gp-shop-input,
    .gp-shop-select,
    .gp-shop-textarea {
        width: 100%;

        border: 1px solid #dededb;
        border-radius: 11px;
        outline: none;

        color: #252525;
        background: #fff;

        font-size: 12px;

        transition:
            border-color .2s ease,
            box-shadow .2s ease;
    }

    .gp-shop-input,
    .gp-shop-select {
        height: 46px;
        padding: 0 13px;
    }

    .gp-shop-textarea {
        min-height: 125px;
        padding: 13px;

        resize: vertical;

        line-height: 1.7;
    }

    .gp-shop-input:focus,
    .gp-shop-select:focus,
    .gp-shop-textarea:focus {
        border-color: var(--gp-orange);
        box-shadow: 0 0 0 3px rgba(232, 99, 26, .08);
    }

    .gp-shop-help {
        margin-top: 6px;

        color: #aaa;

        font-size: 9px;
        line-height: 1.5;
    }


    /* =========================================================
       PREVIEW TITLE
    ========================================================= */

    .gp-shop-title-preview {
        margin-top: 20px;
        padding: 20px;

        overflow: hidden;

        border-radius: 15px;

        background: #111;
    }

    .gp-shop-title-preview small {
        display: block;
        margin-bottom: 8px;

        color: rgba(255, 255, 255, .45);

        font-size: 8px;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
    }

    .gp-shop-title-preview strong {
        color: #fff;

        font-size: 21px;
        line-height: 1.25;
        font-weight: 900;
    }

    .gp-shop-title-preview strong span {
        color: var(--gp-orange);
    }


    /* =========================================================
       VIDEO SWITCH
    ========================================================= */

    .gp-shop-switch {
        display: flex;
        align-items: center;
        gap: 12px;

        padding: 15px;

        border: 1px solid #e8e8e5;
        border-radius: 13px;

        background: #fafaf8;

        cursor: pointer;
    }

    .gp-shop-switch input {
        width: 18px;
        height: 18px;

        flex-shrink: 0;

        accent-color: var(--gp-orange);
    }

    .gp-shop-switch strong {
        display: block;

        color: #292929;

        font-size: 11px;
        font-weight: 850;
    }

    .gp-shop-switch small {
        display: block;

        margin-top: 3px;

        color: #999;

        font-size: 9px;
        line-height: 1.4;
    }


    /* =========================================================
       UPLOAD
    ========================================================= */

    .gp-shop-upload {
        padding: 17px;

        border: 1px dashed #d8d8d5;
        border-radius: 13px;

        background: #fafaf8;
    }

    .gp-shop-upload input[type="file"] {
        display: block;
        width: 100%;

        color: #666;

        font-size: 10px;
    }

    .gp-shop-upload input[type="file"]::file-selector-button {
        margin-right: 10px;
        padding: 8px 12px;

        border: 0;
        border-radius: 8px;

        color: #fff;
        background: #222;

        font-size: 9px;
        font-weight: 800;

        cursor: pointer;
    }


    /* =========================================================
       MEDIA PREVIEW
    ========================================================= */

    .gp-shop-media-preview {
        position: relative;
        overflow: hidden;

        margin-top: 13px;

        border-radius: 15px;

        background: #111;
    }

    .gp-shop-media-preview video,
    .gp-shop-media-preview img {
        display: block;

        width: 100%;
        max-height: 330px;

        object-fit: cover;
    }

    .gp-shop-current-label {
        position: absolute;
        z-index: 3;
        top: 11px;
        left: 11px;

        padding: 6px 9px;

        border-radius: 100px;

        color: #fff;
        background: rgba(0, 0, 0, .65);

        font-size: 8px;
        font-weight: 800;
        letter-spacing: .05em;
        text-transform: uppercase;
    }


    /* =========================================================
       DELETE
    ========================================================= */

    .gp-shop-delete {
        min-height: 37px;
        padding: 0 13px;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;

        border: 1px solid #f0cccc;
        border-radius: 9px;

        color: #ad3939;
        background: #fff;

        font-size: 9px;
        font-weight: 800;

        cursor: pointer;

        transition: .2s ease;
    }

    .gp-shop-delete:hover {
        color: #fff;
        background: #c64343;
        border-color: #c64343;
    }


    /* =========================================================
       INFO BOX
    ========================================================= */

    .gp-shop-info {
        display: flex;
        align-items: flex-start;
        gap: 11px;

        margin-bottom: 22px;
        padding: 14px;

        border: 1px solid rgba(232, 99, 26, .15);
        border-radius: 12px;

        color: #76513d;
        background: rgba(232, 99, 26, .055);

        font-size: 10px;
        line-height: 1.6;
    }

    .gp-shop-info svg {
        width: 17px;
        height: 17px;

        flex-shrink: 0;

        color: var(--gp-orange);
    }


    /* =========================================================
       SAVE BAR
    ========================================================= */

    .gp-shop-save-bar {
        position: sticky;
        z-index: 20;
        bottom: 18px;

        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;

        margin-top: 23px;
        padding: 14px 17px;

        border: 1px solid #e3e3e0;
        border-radius: 16px;

        background: rgba(255, 255, 255, .94);
        backdrop-filter: blur(15px);

        box-shadow: 0 16px 45px rgba(0, 0, 0, .10);
    }

    .gp-shop-save-bar p {
        margin: 0;

        color: #818181;

        font-size: 10px;
        line-height: 1.5;
    }

    .gp-shop-save {
        min-height: 44px;
        padding: 0 20px;

        flex-shrink: 0;

        border: 0;
        border-radius: 11px;

        color: #fff;
        background: var(--gp-orange);

        font-size: 10px;
        font-weight: 900;

        cursor: pointer;

        box-shadow: 0 10px 24px rgba(232, 99, 26, .22);

        transition: .25s ease;
    }

    .gp-shop-save:hover {
        background: #d85813;
        transform: translateY(-2px);
    }


    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 900px) {

        .gp-shop-admin-layout {
            grid-template-columns: 1fr;
        }

        .gp-shop-tabs {
            position: static;

            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 5px;
        }

        .gp-shop-tab + .gp-shop-tab {
            margin-top: 0;
        }
    }

    @media (max-width: 650px) {

        .gp-shop-admin-head {
            flex-direction: column;
        }

        .gp-shop-fields {
            grid-template-columns: 1fr;
        }

        .gp-shop-field-full {
            grid-column: auto;
        }

        .gp-shop-save-bar {
            align-items: stretch;
            flex-direction: column;
        }

        .gp-shop-save {
            width: 100%;
        }
    }
</style>


<div class="gp-shop-admin">

    {{-- =========================================================
         HEADER
    ========================================================== --}}

    <div class="gp-shop-admin-head">

        <div>

            <div class="gp-shop-admin-eyebrow">
                Pages / Boutique
            </div>

            <h1>
                Modifier la Boutique
            </h1>

            <p>
                Personnalise les textes et le Hero de la Boutique.
                Les produits, prix, stocks et fichiers restent gérés
                depuis le module Produits.
            </p>

        </div>


        <a
            href="{{ route('front.shop.index') }}"
            target="_blank"
            rel="noopener"
            class="gp-shop-view"
        >
            Voir la Boutique

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <path d="M15 3h6v6"/>
                <path d="m10 14 11-11"/>
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
            </svg>
        </a>

    </div>


    {{-- =========================================================
         SUCCESS
    ========================================================== --}}

    @if(session('success'))

        <div class="gp-shop-alert gp-shop-alert-success">
            {{ session('success') }}
        </div>

    @endif


    {{-- =========================================================
         ERRORS
    ========================================================== --}}

    @if($errors->any())

        <div class="gp-shop-alert gp-shop-alert-error">

            <strong>
                Certains champs doivent être corrigés.
            </strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

    @endif


    {{-- =========================================================
         MAIN FORM
    ========================================================== --}}

    <form
        method="POST"
        action="{{ route('admin.pages.shop.update') }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <div class="gp-shop-admin-layout">

            {{-- =====================================================
                 TABS
            ====================================================== --}}

            <aside class="gp-shop-tabs">

                <button
                    type="button"
                    class="gp-shop-tab active"
                    data-tab="general"
                >
                    <span class="gp-shop-tab-number">
                        01
                    </span>

                    Général
                </button>


                <button
                    type="button"
                    class="gp-shop-tab"
                    data-tab="hero"
                >
                    <span class="gp-shop-tab-number">
                        02
                    </span>

                    Hero
                </button>


                <button
                    type="button"
                    class="gp-shop-tab"
                    data-tab="catalogue"
                >
                    <span class="gp-shop-tab-number">
                        03
                    </span>

                    Catalogue
                </button>


                <button
                    type="button"
                    class="gp-shop-tab"
                    data-tab="cta"
                >
                    <span class="gp-shop-tab-number">
                        04
                    </span>

                    CTA final
                </button>

            </aside>



            {{-- =====================================================
                 CONTENT
            ====================================================== --}}

            <div>

                {{-- =================================================
                     GENERAL
                ================================================== --}}

                <section
                    class="gp-shop-panel active"
                    data-panel="general"
                >

                    <div class="gp-shop-card">

                        <div class="gp-shop-card-head">

                            <div>
                                <h2>
                                    Informations générales
                                </h2>

                                <p>
                                    Paramètres principaux de la page Boutique.
                                </p>
                            </div>

                            <span class="gp-shop-card-badge">
                                Boutique
                            </span>

                        </div>


                        <div class="gp-shop-fields">

                            {{-- TITLE --}}

                            <div>

                                <label
                                    for="title"
                                    class="gp-shop-label"
                                >
                                    Nom de la page
                                </label>

                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    class="gp-shop-input"
                                    value="{{ old('title', $page->title) }}"
                                    required
                                >

                            </div>


                            {{-- STATUS --}}

                            <div>

                                <label
                                    for="status"
                                    class="gp-shop-label"
                                >
                                    Statut
                                </label>

                                <select
                                    id="status"
                                    name="status"
                                    class="gp-shop-select"
                                >

                                    <option
                                        value="active"
                                        @selected(
                                            old('status', $page->status) === 'active'
                                        )
                                    >
                                        Active
                                    </option>

                                    <option
                                        value="inactive"
                                        @selected(
                                            old('status', $page->status) === 'inactive'
                                        )
                                    >
                                        Inactive
                                    </option>

                                </select>

                            </div>


                            {{-- SUBTITLE --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="subtitle"
                                    class="gp-shop-label"
                                >
                                    Sous-titre général
                                </label>

                                <textarea
                                    id="subtitle"
                                    name="subtitle"
                                    class="gp-shop-textarea"
                                    rows="4"
                                >{{ old('subtitle', $page->subtitle) }}</textarea>

                                <div class="gp-shop-help">
                                    Information générale de la page.
                                    Le Hero possède ses propres textes ci-dessous.
                                </div>

                            </div>

                        </div>

                    </div>

                </section>



                {{-- =================================================
                     HERO
                ================================================== --}}

                <section
                    class="gp-shop-panel"
                    data-panel="hero"
                >

                    {{-- TEXTS --}}

                    <div class="gp-shop-card">

                        <div class="gp-shop-card-head">

                            <div>
                                <h2>
                                    Hero de la Boutique
                                </h2>

                                <p>
                                    Personnalise le grand message affiché
                                    en haut de la page.
                                </p>
                            </div>

                            <span class="gp-shop-card-badge">
                                Hero
                            </span>

                        </div>


                        <div class="gp-shop-fields">

                            {{-- EYEBROW --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="shop_hero_eyebrow"
                                    class="gp-shop-label"
                                >
                                    Petit titre
                                </label>

                                <input
                                    type="text"
                                    id="shop_hero_eyebrow"
                                    name="shop_hero_eyebrow"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_hero_eyebrow',
                                        $page->shop_hero_eyebrow
                                    ) }}"
                                    placeholder="Boutique Generation PUSH"
                                >

                            </div>


                            {{-- TITLE --}}

                            <div>

                                <label
                                    for="shop_hero_title"
                                    class="gp-shop-label"
                                >
                                    Titre principal
                                </label>

                                <input
                                    type="text"
                                    id="shop_hero_title"
                                    name="shop_hero_title"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_hero_title',
                                        $page->shop_hero_title
                                    ) }}"
                                    placeholder="Des ressources pour"
                                >

                            </div>


                            {{-- HIGHLIGHT --}}

                            <div>

                                <label
                                    for="shop_hero_highlight"
                                    class="gp-shop-label"
                                >
                                    Partie orange
                                </label>

                                <input
                                    type="text"
                                    id="shop_hero_highlight"
                                    name="shop_hero_highlight"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_hero_highlight',
                                        $page->shop_hero_highlight
                                    ) }}"
                                    placeholder="passer à l'action."
                                >

                            </div>


                            {{-- DESCRIPTION --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="shop_hero_text"
                                    class="gp-shop-label"
                                >
                                    Description
                                </label>

                                <textarea
                                    id="shop_hero_text"
                                    name="shop_hero_text"
                                    class="gp-shop-textarea"
                                    rows="5"
                                >{{ old(
                                    'shop_hero_text',
                                    $page->shop_hero_text
                                ) }}</textarea>

                            </div>

                        </div>


                        {{-- TITLE PREVIEW --}}

                        <div class="gp-shop-title-preview">

                            <small>
                                Aperçu du titre
                            </small>

                            <strong>
                                <span id="preview-hero-title">
                                    {{ old(
                                        'shop_hero_title',
                                        $page->shop_hero_title
                                    ) }}
                                </span>

                                <span id="preview-hero-highlight">
                                    {{ old(
                                        'shop_hero_highlight',
                                        $page->shop_hero_highlight
                                    ) }}
                                </span>
                            </strong>

                        </div>

                    </div>



                    {{-- VIDEO --}}

                    <div class="gp-shop-card">

                        <div class="gp-shop-card-head">

                            <div>
                                <h2>
                                    Vidéo de fond
                                </h2>

                                <p>
                                    Ajoute une vidéo derrière le Hero.
                                    Sans vidéo, le design noir/orange reste affiché.
                                </p>
                            </div>

                            <span class="gp-shop-card-badge">
                                Facultatif
                            </span>

                        </div>


                        <div class="gp-shop-fields">

                            {{-- ENABLE --}}

                            <div class="gp-shop-field-full">

                                <label class="gp-shop-switch">

                                    <input
                                        type="checkbox"
                                        name="banner_video_enabled"
                                        value="1"
                                        @checked(
                                            old(
                                                'banner_video_enabled',
                                                $page->banner_video_enabled
                                            )
                                        )
                                    >

                                    <span>

                                        <strong>
                                            Activer la vidéo de fond
                                        </strong>

                                        <small>
                                            Si activée et qu'une vidéo existe,
                                            elle remplacera le fond graphique du Hero.
                                        </small>

                                    </span>

                                </label>

                            </div>


                            {{-- VIDEO UPLOAD --}}

                            <div>

                                <label
                                    for="banner_video"
                                    class="gp-shop-label"
                                >
                                    Vidéo
                                    <span>MP4 / WebM</span>
                                </label>

                                <div class="gp-shop-upload">

                                    <input
                                        type="file"
                                        id="banner_video"
                                        name="banner_video"
                                        accept="video/mp4,video/webm"
                                    >

                                    <div class="gp-shop-help">
                                        Taille maximale : 50 Mo.
                                        Une vidéo courte et optimisée est recommandée.
                                    </div>

                                </div>

                            </div>


                            {{-- POSTER --}}

                            <div>

                                <label
                                    for="banner_poster"
                                    class="gp-shop-label"
                                >
                                    Image poster
                                    <span>JPG / PNG / WebP</span>
                                </label>

                                <div class="gp-shop-upload">

                                    <input
                                        type="file"
                                        id="banner_poster"
                                        name="banner_poster"
                                        accept="image/jpeg,image/png,image/webp"
                                    >

                                    <div class="gp-shop-help">
                                        Image affichée pendant le chargement
                                        de la vidéo.
                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- CURRENT VIDEO --}}

                        @if($page->banner_video)

                            <div class="gp-shop-media-preview">

                                <span class="gp-shop-current-label">
                                    Vidéo actuelle
                                </span>

                                <video
                                    id="gp-current-video"
                                    controls
                                    muted
                                    playsinline
                                    @if($page->banner_poster)
                                        poster="{{ $page->bannerPosterUrl() }}"
                                    @endif
                                >
                                    <source
                                        src="{{ $page->bannerVideoUrl() }}"
                                    >
                                </video>

                            </div>

                        @endif



                        {{-- NEW VIDEO PREVIEW --}}

                        <div
                            id="gp-video-preview-wrapper"
                            class="gp-shop-media-preview"
                            style="display:none;"
                        >

                            <span class="gp-shop-current-label">
                                Nouvelle vidéo
                            </span>

                            <video
                                id="gp-video-preview"
                                controls
                                muted
                                playsinline
                            ></video>

                        </div>



                        {{-- CURRENT POSTER --}}

                        @if($page->banner_poster)

                            <div class="gp-shop-media-preview">

                                <span class="gp-shop-current-label">
                                    Poster actuel
                                </span>

                                <img
                                    src="{{ $page->bannerPosterUrl() }}"
                                    alt="Poster actuel de la Boutique"
                                >

                            </div>

                        @endif



                        {{-- NEW POSTER PREVIEW --}}

                        <div
                            id="gp-poster-preview-wrapper"
                            class="gp-shop-media-preview"
                            style="display:none;"
                        >

                            <span class="gp-shop-current-label">
                                Nouveau poster
                            </span>

                            <img
                                id="gp-poster-preview"
                                src=""
                                alt="Aperçu du nouveau poster"
                            >

                        </div>

                    </div>

                </section>



                {{-- =================================================
                     CATALOGUE
                ================================================== --}}

                <section
                    class="gp-shop-panel"
                    data-panel="catalogue"
                >

                    <div class="gp-shop-card">

                        <div class="gp-shop-card-head">

                            <div>

                                <h2>
                                    Introduction du catalogue
                                </h2>

                                <p>
                                    Modifie les textes situés juste avant
                                    la recherche, les filtres et les produits.
                                </p>

                            </div>

                            <span class="gp-shop-card-badge">
                                Catalogue
                            </span>

                        </div>


                        <div class="gp-shop-info">

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
                                Cette partie modifie uniquement la présentation
                                de la Boutique. Les produits restent administrés
                                depuis ton module Produits.
                            </span>

                        </div>


                        <div class="gp-shop-fields">

                            {{-- EYEBROW --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="shop_catalogue_eyebrow"
                                    class="gp-shop-label"
                                >
                                    Petit titre
                                </label>

                                <input
                                    type="text"
                                    id="shop_catalogue_eyebrow"
                                    name="shop_catalogue_eyebrow"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_catalogue_eyebrow',
                                        $page->shop_catalogue_eyebrow
                                    ) }}"
                                    placeholder="Ressources"
                                >

                            </div>


                            {{-- TITLE --}}

                            <div>

                                <label
                                    for="shop_catalogue_title"
                                    class="gp-shop-label"
                                >
                                    Titre
                                </label>

                                <input
                                    type="text"
                                    id="shop_catalogue_title"
                                    name="shop_catalogue_title"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_catalogue_title',
                                        $page->shop_catalogue_title
                                    ) }}"
                                    placeholder="Continue à"
                                >

                            </div>


                            {{-- HIGHLIGHT --}}

                            <div>

                                <label
                                    for="shop_catalogue_highlight"
                                    class="gp-shop-label"
                                >
                                    Partie orange
                                </label>

                                <input
                                    type="text"
                                    id="shop_catalogue_highlight"
                                    name="shop_catalogue_highlight"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_catalogue_highlight',
                                        $page->shop_catalogue_highlight
                                    ) }}"
                                    placeholder="grandir."
                                >

                            </div>


                            {{-- TEXT --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="shop_catalogue_text"
                                    class="gp-shop-label"
                                >
                                    Description
                                </label>

                                <textarea
                                    id="shop_catalogue_text"
                                    name="shop_catalogue_text"
                                    class="gp-shop-textarea"
                                    rows="5"
                                >{{ old(
                                    'shop_catalogue_text',
                                    $page->shop_catalogue_text
                                ) }}</textarea>

                            </div>

                        </div>


                        <div class="gp-shop-title-preview">

                            <small>
                                Aperçu
                            </small>

                            <strong>
                                <span id="preview-catalogue-title">
                                    {{ old(
                                        'shop_catalogue_title',
                                        $page->shop_catalogue_title
                                    ) }}
                                </span>

                                <span id="preview-catalogue-highlight">
                                    {{ old(
                                        'shop_catalogue_highlight',
                                        $page->shop_catalogue_highlight
                                    ) }}
                                </span>
                            </strong>

                        </div>

                    </div>

                </section>



                {{-- =================================================
                     CTA FINAL
                ================================================== --}}

                <section
                    class="gp-shop-panel"
                    data-panel="cta"
                >

                    <div class="gp-shop-card">

                        <div class="gp-shop-card-head">

                            <div>

                                <h2>
                                    CTA final
                                </h2>

                                <p>
                                    Personnalise le message noir/orange
                                    affiché à la fin de la Boutique.
                                </p>

                            </div>

                            <span class="gp-shop-card-badge">
                                Bas de page
                            </span>

                        </div>


                        <div class="gp-shop-fields">

                            {{-- EYEBROW --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="shop_cta_eyebrow"
                                    class="gp-shop-label"
                                >
                                    Petit titre
                                </label>

                                <input
                                    type="text"
                                    id="shop_cta_eyebrow"
                                    name="shop_cta_eyebrow"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_cta_eyebrow',
                                        $page->shop_cta_eyebrow
                                    ) }}"
                                    placeholder="Generation PUSH"
                                >

                            </div>


                            {{-- TITLE --}}

                            <div>

                                <label
                                    for="shop_cta_title"
                                    class="gp-shop-label"
                                >
                                    Titre
                                </label>

                                <input
                                    type="text"
                                    id="shop_cta_title"
                                    name="shop_cta_title"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_cta_title',
                                        $page->shop_cta_title
                                    ) }}"
                                    placeholder="Apprendre ne suffit pas."
                                >

                            </div>


                            {{-- HIGHLIGHT --}}

                            <div>

                                <label
                                    for="shop_cta_highlight"
                                    class="gp-shop-label"
                                >
                                    Partie orange
                                </label>

                                <input
                                    type="text"
                                    id="shop_cta_highlight"
                                    name="shop_cta_highlight"
                                    class="gp-shop-input"
                                    value="{{ old(
                                        'shop_cta_highlight',
                                        $page->shop_cta_highlight
                                    ) }}"
                                    placeholder="Il faut agir."
                                >

                            </div>


                            {{-- TEXT --}}

                            <div class="gp-shop-field-full">

                                <label
                                    for="shop_cta_text"
                                    class="gp-shop-label"
                                >
                                    Description
                                </label>

                                <textarea
                                    id="shop_cta_text"
                                    name="shop_cta_text"
                                    class="gp-shop-textarea"
                                    rows="5"
                                >{{ old(
                                    'shop_cta_text',
                                    $page->shop_cta_text
                                ) }}</textarea>

                            </div>

                        </div>


                        <div class="gp-shop-title-preview">

                            <small>
                                Aperçu
                            </small>

                            <strong>
                                <span id="preview-cta-title">
                                    {{ old(
                                        'shop_cta_title',
                                        $page->shop_cta_title
                                    ) }}
                                </span>

                                <span id="preview-cta-highlight">
                                    {{ old(
                                        'shop_cta_highlight',
                                        $page->shop_cta_highlight
                                    ) }}
                                </span>
                            </strong>

                        </div>

                    </div>

                </section>



                {{-- =================================================
                     SAVE
                ================================================== --}}

                <div class="gp-shop-save-bar">

                    <p>
                        Enregistre les modifications avant de quitter cette page.
                    </p>

                    <button
                        type="submit"
                        class="gp-shop-save"
                    >
                        Enregistrer les modifications
                    </button>

                </div>

            </div>

        </div>

    </form>



    {{-- =========================================================
         DELETE CURRENT VIDEO
    ========================================================== --}}

    @if($page->banner_video)

        <form
            method="POST"
            action="{{ route('admin.pages.shop.banner-video.destroy') }}"
            style="margin-top: 18px;"
            onsubmit="return confirm('Supprimer définitivement la vidéo actuelle ?')"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="gp-shop-delete"
            >
                Supprimer la vidéo actuelle
            </button>

        </form>

    @endif



    {{-- =========================================================
         DELETE CURRENT POSTER
    ========================================================== --}}

    @if($page->banner_poster)

        <form
            method="POST"
            action="{{ route('admin.pages.shop.banner-poster.destroy') }}"
            style="margin-top: 8px;"
            onsubmit="return confirm('Supprimer définitivement le poster actuel ?')"
        >

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="gp-shop-delete"
            >
                Supprimer le poster actuel
            </button>

        </form>

    @endif

</div>



{{-- =============================================================
     JAVASCRIPT
============================================================== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
        |--------------------------------------------------------------------------
        | TABS
        |--------------------------------------------------------------------------
        */

        const tabs = document.querySelectorAll('.gp-shop-tab');
        const panels = document.querySelectorAll('.gp-shop-panel');

        tabs.forEach(function (tab) {

            tab.addEventListener('click', function () {

                const target = this.dataset.tab;

                tabs.forEach(function (item) {
                    item.classList.remove('active');
                });

                panels.forEach(function (panel) {
                    panel.classList.remove('active');
                });

                this.classList.add('active');

                const targetPanel = document.querySelector(
                    '[data-panel="' + target + '"]'
                );

                if (targetPanel) {
                    targetPanel.classList.add('active');
                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | VIDEO PREVIEW
        |--------------------------------------------------------------------------
        */

        const videoInput =
            document.getElementById('banner_video');

        const videoPreviewWrapper =
            document.getElementById('gp-video-preview-wrapper');

        const videoPreview =
            document.getElementById('gp-video-preview');

        if (
            videoInput &&
            videoPreviewWrapper &&
            videoPreview
        ) {

            videoInput.addEventListener('change', function () {

                const file = this.files[0];

                if (!file) {
                    videoPreviewWrapper.style.display = 'none';
                    return;
                }

                const url = URL.createObjectURL(file);

                videoPreview.src = url;

                videoPreviewWrapper.style.display = 'block';

                videoPreview.load();

            });

        }


        /*
        |--------------------------------------------------------------------------
        | POSTER PREVIEW
        |--------------------------------------------------------------------------
        */

        const posterInput =
            document.getElementById('banner_poster');

        const posterPreviewWrapper =
            document.getElementById('gp-poster-preview-wrapper');

        const posterPreview =
            document.getElementById('gp-poster-preview');

        if (
            posterInput &&
            posterPreviewWrapper &&
            posterPreview
        ) {

            posterInput.addEventListener('change', function () {

                const file = this.files[0];

                if (!file) {
                    posterPreviewWrapper.style.display = 'none';
                    return;
                }

                const url = URL.createObjectURL(file);

                posterPreview.src = url;

                posterPreviewWrapper.style.display = 'block';

            });

        }


        /*
        |--------------------------------------------------------------------------
        | LIVE TITLE PREVIEW
        |--------------------------------------------------------------------------
        */

        function bindPreview(inputId, previewId) {

            const input =
                document.getElementById(inputId);

            const preview =
                document.getElementById(previewId);

            if (!input || !preview) {
                return;
            }

            input.addEventListener('input', function () {
                preview.textContent = this.value;
            });

        }


        bindPreview(
            'shop_hero_title',
            'preview-hero-title'
        );

        bindPreview(
            'shop_hero_highlight',
            'preview-hero-highlight'
        );

        bindPreview(
            'shop_catalogue_title',
            'preview-catalogue-title'
        );

        bindPreview(
            'shop_catalogue_highlight',
            'preview-catalogue-highlight'
        );

        bindPreview(
            'shop_cta_title',
            'preview-cta-title'
        );

        bindPreview(
            'shop_cta_highlight',
            'preview-cta-highlight'
        );

    });
</script>

@endsection