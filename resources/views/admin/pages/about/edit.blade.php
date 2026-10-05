@extends('layouts.admin')

@section('title', 'Modifier la page À propos')

@section('content')

    @php
        $isAboutPage = $page->key === 'about';
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- =========================================================
        HEADER
        ========================================================== --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-8">

            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-orange-50 text-[#E8631A]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 5a2 2 0 0 1 2-2h8l6 6v10a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2z" />
                            <path d="M14 3v6h6" />
                        </svg>
                    </span>

                    <span class="text-xs font-bold uppercase tracking-[0.18em] text-[#E8631A]">
                        Pages
                    </span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-gray-900">
                    Page À propos
                </h1>

                <p class="mt-2 text-sm text-gray-500 max-w-2xl">
                    Gérez le contenu, les textes, les images et la bannière vidéo
                    de la page À propos de Generation PUSH.
                </p>
            </div>

            <a href="{{ url('/a-propos') }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl
                      border border-gray-200 bg-white text-sm font-bold text-gray-700
                      hover:border-[#E8631A] hover:text-[#E8631A] transition">

                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h6v6" />
                    <path d="M10 14 21 3" />
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" />
                </svg>

                Voir la page
            </a>
        </div>


        {{-- =========================================================
        MESSAGES
        ========================================================== --}}

        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif


        @if($errors->any())
            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4">
                <p class="font-bold text-red-700 mb-2">
                    Certains champs doivent être corrigés.
                </p>

                <ul class="list-disc pl-5 text-sm text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- =========================================================
        NAVIGATION INTERNE
        ========================================================== --}}

        <div class="mb-7 overflow-x-auto">
            <div class="inline-flex min-w-max gap-2 p-1.5 rounded-2xl bg-gray-100">

                <a href="#banner" class="gp-admin-tab">Bannière</a>
                <a href="#story" class="gp-admin-tab">Notre histoire</a>
                <a href="#founder" class="gp-admin-tab">Fondatrice</a>
                <a href="#values" class="gp-admin-tab">Valeurs</a>
                <a href="#team" class="gp-admin-tab">Équipe</a>
                <a href="#cta" class="gp-admin-tab">CTA final</a>

            </div>
        </div>


        {{-- =========================================================
        FORMULAIRE
        ========================================================== --}}
        

        <form action="{{ route('admin.pages.about.update') }}" method="POST" enctype="multipart/form-data"
            class="space-y-8">

            @csrf
            @method('PUT')


            {{-- =====================================================
            BANNER
            ====================================================== --}}

            <section id="banner" class="gp-admin-card scroll-mt-28">

                <div class="gp-admin-section-header">

                    <div>
                        <span class="gp-admin-number">01</span>

                        <h2 class="gp-admin-title">
                            Bannière
                        </h2>

                        <p class="gp-admin-description">
                            Gérez le titre principal et la vidéo de fond de la page.
                        </p>
                    </div>

                    <span class="gp-admin-badge">
                        Hero
                    </span>

                </div>


                <div class="p-6 lg:p-8 space-y-7">

                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Titre de la bannière
                            </label>

                            <input type="text" name="title" value="{{ old('title', $page->title) }}" class="gp-input"
                                placeholder="À propos de nous" required>

                            @error('title')
                                <p class="gp-error">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label class="gp-label">
                                Statut de la page
                            </label>

                            <select name="status" class="gp-input">
                                <option value="active" @selected(old('status', $page->status) === 'active')>
                                    Active
                                </option>

                                <option value="inactive" @selected(old('status', $page->status) === 'inactive')>
                                    Inactive
                                </option>
                            </select>
                        </div>

                    </div>


                    <div>
                        <label class="gp-label">
                            Sous-titre
                        </label>

                        <textarea name="subtitle" rows="3" class="gp-textarea"
                            placeholder="Découvre la mission, l'histoire...">{{ old('subtitle', $page->subtitle) }}</textarea>

                        @error('subtitle')
                            <p class="gp-error">{{ $message }}</p>
                        @enderror
                    </div>


                    {{-- VIDÉO --}}

                    <div class="border-t border-gray-100 pt-7">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">

                            <div>
                                <h3 class="font-black text-gray-900">
                                    Vidéo de bannière
                                </h3>

                                <p class="text-xs text-gray-500 mt-1">
                                    Si elle est activée, elle remplacera le fond
                                    graphique classique de la bannière.
                                </p>
                            </div>


                            <label class="inline-flex items-center gap-3 cursor-pointer">

                                <input type="hidden" name="banner_video_enabled" value="0">

                                <input type="checkbox" name="banner_video_enabled" value="1" class="sr-only peer" @checked(
                                    old(
                                        'banner_video_enabled',
                                        $page->banner_video_enabled
                                    )
                                )>

                                <span class="relative w-12 h-7 bg-gray-200 rounded-full
                                             peer-checked:bg-[#E8631A]
                                             after:content-['']
                                             after:absolute after:top-1 after:left-1
                                             after:w-5 after:h-5 after:bg-white
                                             after:rounded-full after:transition
                                             peer-checked:after:translate-x-5">
                                </span>

                                <span class="text-sm font-bold text-gray-700">
                                    Activer la vidéo
                                </span>

                            </label>

                        </div>


                        <div class="grid lg:grid-cols-2 gap-6">

                            {{-- UPLOAD VIDEO --}}

                            <div>

                                <label class="gp-upload-box">

                                    <input id="aboutBannerVideoInput" type="file" name="banner_video"
                                        accept="video/mp4,video/webm" class="hidden">

                                    <span class="gp-upload-icon">
                                        <svg width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M12 3v12" />
                                            <path d="m7 8 5-5 5 5" />
                                            <path d="M5 21h14" />
                                        </svg>
                                    </span>

                                    <strong>
                                        Choisir une vidéo
                                    </strong>

                                    <span>
                                        MP4 ou WebM · maximum 50 Mo
                                    </span>

                                </label>

                                <p id="aboutVideoFilename" class="hidden mt-3 text-xs font-semibold text-[#E8631A]"></p>

                            </div>


                            {{-- APERÇU --}}

                            <div>

                                <label class="gp-label">
                                    Aperçu
                                </label>

                                <div class="relative overflow-hidden rounded-2xl bg-[#111] aspect-video">

                                    @if($page->bannerVideoUrl())

                                        <video id="aboutBannerVideoPreview" src="{{ $page->bannerVideoUrl() }}"
                                            poster="{{ $page->bannerPosterUrl() }}" controls muted
                                            class="w-full h-full object-cover"></video>

                                    @else

                                        <video id="aboutBannerVideoPreview" controls muted
                                            class="hidden w-full h-full object-cover"></video>

                                        <div id="aboutVideoEmpty"
                                            class="absolute inset-0 flex flex-col items-center justify-center text-center p-6">
                                            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#E8631A"
                                                stroke-width="1.5">
                                                <polygon points="5 3 19 12 5 21 5 3" />
                                            </svg>

                                            <p class="mt-3 text-sm font-bold text-white">
                                                Aucune vidéo
                                            </p>

                                            <p class="text-xs text-gray-500 mt-1">
                                                Le banner classique sera utilisé.
                                            </p>
                                        </div>

                                    @endif

                                </div>

                            </div>

                        </div>


                        @if($page->banner_video)

                            <div class="mt-4">

                                <button type="submit" form="deleteAboutBannerVideo"
                                    class="text-xs font-bold text-red-600 hover:text-red-700">
                                    Supprimer la vidéo actuelle
                                </button>

                            </div>

                        @endif

                    </div>


                    {{-- POSTER --}}

                    <div class="border-t border-gray-100 pt-7">

                        <h3 class="font-black text-gray-900">
                            Image d'attente de la vidéo
                        </h3>

                        <p class="text-xs text-gray-500 mt-1 mb-5">
                            Cette image apparaît pendant le chargement de la vidéo.
                        </p>


                        <div class="grid lg:grid-cols-2 gap-6">

                            <label class="gp-upload-box">

                                <input id="aboutPosterInput" type="file" name="banner_poster"
                                    accept="image/jpeg,image/png,image/webp" class="hidden">

                                <span class="gp-upload-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect width="18" height="18" x="3" y="3" rx="2" />
                                        <circle cx="9" cy="9" r="2" />
                                        <path d="m21 15-5-5L5 21" />
                                    </svg>
                                </span>

                                <strong>
                                    Choisir un poster
                                </strong>

                                <span>
                                    JPG, PNG ou WebP
                                </span>

                            </label>


                            <div class="gp-image-preview">

                                @if($page->bannerPosterUrl())

                                    <img id="aboutPosterPreview" src="{{ $page->bannerPosterUrl() }}" alt="Poster actuel">

                                @else

                                    <img id="aboutPosterPreview" src="" alt="" class="hidden">

                                    <div id="aboutPosterEmpty" class="gp-empty-media">
                                        Aucun poster
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =====================================================
            HISTOIRE
            ====================================================== --}}

            <section id="story" class="gp-admin-card scroll-mt-28">

                <div class="gp-admin-section-header">

                    <div>
                        <span class="gp-admin-number">02</span>

                        <h2 class="gp-admin-title">
                            Notre histoire
                        </h2>

                        <p class="gp-admin-description">
                            Gérez le premier grand bloc de présentation.
                        </p>
                    </div>

                </div>


                <div class="p-6 lg:p-8 space-y-6">

                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Petit titre
                            </label>

                            <input type="text" name="story_eyebrow" value="{{ old('story_eyebrow', $page->story_eyebrow) }}"
                                class="gp-input" placeholder="Notre histoire">
                        </div>


                        <div>
                            <label class="gp-label">
                                Titre principal
                            </label>

                            <input type="text" name="story_title" value="{{ old('story_title', $page->story_title) }}"
                                class="gp-input" placeholder="Une communauté panafricaine...">
                        </div>

                    </div>


                    <div>
                        <label class="gp-label">
                            Texte
                        </label>

                        <textarea name="story_text" rows="7" class="gp-textarea"
                            placeholder="Racontez l'histoire de Generation PUSH...">{{ old('story_text', $page->story_text) }}</textarea>
                    </div>


                    {{-- IMAGE HISTOIRE --}}

                    <div>

                        <label class="gp-label">
                            Image principale
                        </label>

                        <div class="grid lg:grid-cols-2 gap-6">

                            <label class="gp-upload-box">

                                <input id="storyImageInput" type="file" name="story_image"
                                    accept="image/jpeg,image/png,image/webp" class="hidden">

                                <span class="gp-upload-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2">
                                        <rect width="18" height="18" x="3" y="3" rx="2" />
                                        <circle cx="9" cy="9" r="2" />
                                        <path d="m21 15-5-5L5 21" />
                                    </svg>
                                </span>

                                <strong>
                                    Modifier l'image
                                </strong>

                                <span>
                                    JPG, PNG ou WebP
                                </span>

                            </label>


                            <div class="gp-image-preview">

                                @if($page->storyImageUrl())

                                    <img id="storyImagePreview" src="{{ $page->storyImageUrl() }}" alt="Image Notre histoire">

                                @else

                                    <img id="storyImagePreview" src="" alt="" class="hidden">

                                    <div id="storyImageEmpty" class="gp-empty-media">
                                        Aucune image
                                    </div>

                                @endif

                            </div>

                        </div>

                    </div>


                    {{-- CARTE FLOTTANTE --}}

                    <div class="rounded-2xl border border-orange-100 bg-orange-50/50 p-5 lg:p-6">

                        <div class="mb-5">
                            <h3 class="font-black text-gray-900">
                                Petite carte flottante
                            </h3>

                            <p class="text-xs text-gray-500 mt-1">
                                La petite carte affichée par-dessus l'image.
                            </p>
                        </div>


                        <div class="grid lg:grid-cols-2 gap-6">

                            <div>
                                <label class="gp-label">
                                    Titre
                                </label>

                                <input type="text" name="story_card_title"
                                    value="{{ old('story_card_title', $page->story_card_title) }}" class="gp-input"
                                    placeholder="Passer à l'action">
                            </div>


                            <div>
                                <label class="gp-label">
                                    Description
                                </label>

                                <input type="text" name="story_card_text"
                                    value="{{ old('story_card_text', $page->story_card_text) }}" class="gp-input"
                                    placeholder="Plus qu'une communauté, un mouvement.">
                            </div>

                        </div>

                    </div>

                </div>

            </section>


            {{-- =====================================================
            FONDATRICE
            ====================================================== --}}

            <section id="founder" class="gp-admin-card scroll-mt-28">

                <div class="gp-admin-section-header">

                    <div>
                        <span class="gp-admin-number">03</span>

                        <h2 class="gp-admin-title">
                            Section Fondatrice
                        </h2>

                        <p class="gp-admin-description">
                            Présentez la vision derrière Generation PUSH.
                        </p>
                    </div>

                </div>


                <div class="p-6 lg:p-8 space-y-6">

                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Petit titre
                            </label>

                            <input type="text" name="founder_eyebrow"
                                value="{{ old('founder_eyebrow', $page->founder_eyebrow) }}" class="gp-input"
                                placeholder="La vision derrière le mouvement">
                        </div>


                        <div>
                            <label class="gp-label">
                                Titre
                            </label>

                            <input type="text" name="founder_title" value="{{ old('founder_title', $page->founder_title) }}"
                                class="gp-input" placeholder="À l'origine de Generation PUSH.">
                        </div>

                    </div>


                    <div>
                        <label class="gp-label">
                            Description
                        </label>

                        <textarea name="founder_text" rows="5"
                            class="gp-textarea">{{ old('founder_text', $page->founder_text) }}</textarea>
                    </div>


                    <div class="max-w-xl">
                        <label class="gp-label">
                            Texte du bouton
                        </label>

                        <input type="text" name="founder_button_text"
                            value="{{ old('founder_button_text', $page->founder_button_text) }}" class="gp-input"
                            placeholder="Découvrir son histoire">

                        <p class="gp-help">
                            Le bouton continuera à ouvrir la page de la fondatrice.
                        </p>
                    </div>

                </div>

            </section>


            {{-- =====================================================
            VALEURS
            ====================================================== --}}

            <section id="values" class="gp-admin-card scroll-mt-28">

                <div class="gp-admin-section-header">

                    <div>
                        <span class="gp-admin-number">04</span>

                        <h2 class="gp-admin-title">
                            Nos valeurs
                        </h2>

                        <p class="gp-admin-description">
                            Modifiez la présentation et les trois cartes 3D.
                        </p>
                    </div>

                </div>


                <div class="p-6 lg:p-8 space-y-8">

                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Petit titre
                            </label>

                            <input type="text" name="values_eyebrow"
                                value="{{ old('values_eyebrow', $page->values_eyebrow) }}" class="gp-input"
                                placeholder="Nos valeurs">
                        </div>


                        <div>
                            <label class="gp-label">
                                Titre
                            </label>

                            <input type="text" name="values_title" value="{{ old('values_title', $page->values_title) }}"
                                class="gp-input" placeholder="Ce qui nous fait avancer.">
                        </div>

                    </div>


                    <div>
                        <label class="gp-label">
                            Introduction
                        </label>

                        <textarea name="values_intro" rows="3"
                            class="gp-textarea">{{ old('values_intro', $page->values_intro) }}</textarea>
                    </div>


                    <div class="grid xl:grid-cols-3 gap-5">

                        @for($i = 1; $i <= 3; $i++)

                            @php
                                $defaultTitles = [
                                    1 => 'Excellence',
                                    2 => 'Communauté',
                                    3 => 'Impact',
                                ];

                                $defaultShorts = [
                                    1 => 'Viser plus haut.',
                                    2 => 'Avancer ensemble.',
                                    3 => 'Transformer durablement.',
                                ];

                                $titleField = "value_{$i}_title";
                                $shortField = "value_{$i}_short";
                                $textField = "value_{$i}_text";
                            @endphp

                            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

                                <div class="flex items-center justify-between mb-5">

                                    <span class="text-3xl font-black text-gray-200">
                                        0{{ $i }}
                                    </span>

                                    <span class="text-[10px] uppercase tracking-widest font-black text-[#E8631A]">
                                        Valeur
                                    </span>

                                </div>


                                <div class="space-y-4">

                                    <div>
                                        <label class="gp-label">
                                            Nom
                                        </label>

                                        <input type="text" name="{{ $titleField }}"
                                            value="{{ old($titleField, $page->{$titleField}) }}" class="gp-input"
                                            placeholder="{{ $defaultTitles[$i] }}">
                                    </div>


                                    <div>
                                        <label class="gp-label">
                                            Phrase courte
                                        </label>

                                        <input type="text" name="{{ $shortField }}"
                                            value="{{ old($shortField, $page->{$shortField}) }}" class="gp-input"
                                            placeholder="{{ $defaultShorts[$i] }}">
                                    </div>


                                    <div>
                                        <label class="gp-label">
                                            Description
                                        </label>

                                        <textarea name="{{ $textField }}" rows="6"
                                            class="gp-textarea">{{ old($textField, $page->{$textField}) }}</textarea>
                                    </div>

                                </div>

                            </div>

                        @endfor

                    </div>

                </div>

            </section>


            {{-- =====================================================
            ÉQUIPE
            ====================================================== --}}

            <section id="team" class="gp-admin-card scroll-mt-28">

                <div class="gp-admin-section-header">

                    <div>
                        <span class="gp-admin-number">05</span>

                        <h2 class="gp-admin-title">
                            Présentation de l'équipe
                        </h2>

                        <p class="gp-admin-description">
                            Ces textes apparaissent au-dessus du slider de l'équipe.
                        </p>
                    </div>

                </div>


                <div class="p-6 lg:p-8 space-y-6">

                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Petit titre
                            </label>

                            <input type="text" name="team_eyebrow" value="{{ old('team_eyebrow', $page->team_eyebrow) }}"
                                class="gp-input" placeholder="L'équipe">
                        </div>


                        <div>
                            <label class="gp-label">
                                Titre
                            </label>

                            <input type="text" name="team_title" value="{{ old('team_title', $page->team_title) }}"
                                class="gp-input" placeholder="Les visages derrière Generation PUSH.">
                        </div>

                    </div>


                    <div>
                        <label class="gp-label">
                            Introduction
                        </label>

                        <textarea name="team_intro" rows="4"
                            class="gp-textarea">{{ old('team_intro', $page->team_intro) }}</textarea>
                    </div>


                    <div class="rounded-2xl border border-blue-100 bg-blue-50 p-5">

                        <p class="text-sm font-bold text-blue-900">
                            Les membres de l'équipe ne sont pas modifiés ici.
                        </p>

                        <p class="text-xs text-blue-700 mt-1 leading-5">
                            Les photos, noms, fonctions, biographies, emails et LinkedIn
                            restent gérés par ton module TeamMember.
                        </p>

                    </div>

                </div>

            </section>


            {{-- =====================================================
            CTA
            ====================================================== --}}

            <section id="cta" class="gp-admin-card scroll-mt-28">

                <div class="gp-admin-section-header">

                    <div>
                        <span class="gp-admin-number">06</span>

                        <h2 class="gp-admin-title">
                            Appel à l'action final
                        </h2>

                        <p class="gp-admin-description">
                            Gérez le grand bloc situé en bas de la page.
                        </p>
                    </div>

                </div>


                <div class="p-6 lg:p-8 space-y-6">

                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Petit titre
                            </label>

                            <input type="text" name="cta_eyebrow" value="{{ old('cta_eyebrow', $page->cta_eyebrow) }}"
                                class="gp-input" placeholder="Generation PUSH">
                        </div>


                        <div>
                            <label class="gp-label">
                                Première partie du titre
                            </label>

                            <input type="text" name="cta_title" value="{{ old('cta_title', $page->cta_title) }}"
                                class="gp-input" placeholder="Ne regarde pas le changement.">
                        </div>

                    </div>


                    <div>
                        <label class="gp-label">
                            Partie du titre mise en orange
                        </label>

                        <input type="text" name="cta_highlight" value="{{ old('cta_highlight', $page->cta_highlight) }}"
                            class="gp-input" placeholder="Deviens-en acteur.">
                    </div>


                    <div>
                        <label class="gp-label">
                            Description
                        </label>

                        <textarea name="cta_text" rows="4"
                            class="gp-textarea">{{ old('cta_text', $page->cta_text) }}</textarea>
                    </div>


                    <div class="grid lg:grid-cols-2 gap-6">

                        <div>
                            <label class="gp-label">
                                Texte du bouton
                            </label>

                            <input type="text" name="cta_button_text"
                                value="{{ old('cta_button_text', $page->cta_button_text) }}" class="gp-input"
                                placeholder="Rejoindre la communauté">
                        </div>


                        <div>
                            <label class="gp-label">
                                URL du bouton
                            </label>

                            <input type="text" name="cta_button_url"
                                value="{{ old('cta_button_url', $page->cta_button_url) }}" class="gp-input"
                                placeholder="/register">
                        </div>

                    </div>

                </div>

            </section>


            {{-- =====================================================
            ENREGISTRER
            ====================================================== --}}

            <div class="sticky bottom-5 z-30">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between
                            gap-4 rounded-2xl border border-gray-200 bg-white/95
                            backdrop-blur-xl shadow-xl p-4">

                    <div class="hidden sm:block">

                        <p class="text-sm font-black text-gray-900">
                            Page À propos
                        </p>

                        <p class="text-xs text-gray-500">
                            Pensez à enregistrer vos modifications.
                        </p>

                    </div>


                    <button type="submit" class="inline-flex items-center justify-center gap-2
                               px-6 py-3.5 rounded-xl bg-[#E8631A]
                               text-white text-sm font-black
                               hover:bg-[#cf5414] transition
                               shadow-lg shadow-orange-500/20">

                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z" />
                            <path d="M17 21v-8H7v8" />
                            <path d="M7 3v5h8" />
                        </svg>

                        Enregistrer les modifications

                    </button>

                </div>

            </div>

        </form>


        {{-- =========================================================
        FORMULAIRES DE SUPPRESSION SÉPARÉS
        Important : pas de form imbriqué.
        ========================================================== --}}

        @if($page->banner_video)

            <form id="deleteAboutBannerVideo" action="{{ route('admin.pages.about.banner-video.destroy') }}" method="POST"
                class="hidden">
                @csrf
                @method('DELETE')
            </form>

        @endif

    </div>


    {{-- =============================================================
    STYLES ADMIN
    ============================================================== --}}

    <style>
        html {
            scroll-behavior: smooth;
        }

        .gp-admin-card {
            overflow: hidden;

            border: 1px solid #e5e7eb;
            border-radius: 22px;

            background: #fff;

            box-shadow: 0 8px 35px rgba(0, 0, 0, .035);
        }

        .gp-admin-section-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            gap: 20px;

            padding: 24px 28px;

            border-bottom: 1px solid #f0f0f0;

            background:
                linear-gradient(135deg,
                    #fff,
                    #fafafa);
        }

        .gp-admin-number {
            display: block;

            margin-bottom: 6px;

            color: #E8631A;

            font-size: 10px;
            font-weight: 900;

            letter-spacing: .15em;
        }

        .gp-admin-title {
            margin: 0;

            color: #111827;

            font-size: 19px;
            font-weight: 900;
        }

        .gp-admin-description {
            margin-top: 5px;

            color: #6b7280;

            font-size: 12px;
        }

        .gp-admin-badge {
            display: inline-flex;
            align-items: center;

            padding: 7px 11px;

            border-radius: 999px;

            color: #E8631A;

            background: rgba(232, 99, 26, .09);

            font-size: 9px;
            font-weight: 900;

            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .gp-label {
            display: block;

            margin-bottom: 8px;

            color: #374151;

            font-size: 12px;
            font-weight: 800;
        }

        .gp-input,
        .gp-textarea {
            width: 100%;

            border: 1px solid #dfe2e6;
            border-radius: 12px;

            color: #111827;
            background: #fff;

            font-size: 13px;

            outline: none;

            transition: .2s ease;
        }

        .gp-input {
            min-height: 45px;

            padding: 0 14px;
        }

        .gp-textarea {
            padding: 13px 14px;

            resize: vertical;
        }

        .gp-input:focus,
        .gp-textarea:focus {
            border-color: #E8631A;

            box-shadow:
                0 0 0 3px rgba(232, 99, 26, .10);
        }

        .gp-help {
            margin-top: 7px;

            color: #9ca3af;

            font-size: 10px;
        }

        .gp-error {
            margin-top: 6px;

            color: #dc2626;

            font-size: 11px;
            font-weight: 700;
        }

        .gp-upload-box {
            min-height: 160px;

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            gap: 7px;

            padding: 24px;

            border: 1.5px dashed #d1d5db;
            border-radius: 18px;

            text-align: center;

            cursor: pointer;

            background: #fafafa;

            transition: .25s ease;
        }

        .gp-upload-box:hover {
            border-color: #E8631A;

            background: rgba(232, 99, 26, .035);
        }

        .gp-upload-icon {
            width: 45px;
            height: 45px;

            display: grid;
            place-items: center;

            margin-bottom: 3px;

            border-radius: 13px;

            color: #E8631A;

            background: rgba(232, 99, 26, .10);
        }

        .gp-upload-box strong {
            color: #1f2937;

            font-size: 12px;
            font-weight: 900;
        }

        .gp-upload-box>span:last-child {
            color: #9ca3af;

            font-size: 10px;
        }

        .gp-image-preview {
            position: relative;

            overflow: hidden;

            min-height: 160px;

            border: 1px solid #e5e7eb;
            border-radius: 18px;

            background: #f3f4f6;
        }

        .gp-image-preview img {
            width: 100%;
            height: 100%;

            min-height: 160px;

            display: block;

            object-fit: cover;
        }

        .gp-empty-media {
            position: absolute;

            inset: 0;

            display: grid;
            place-items: center;

            color: #9ca3af;

            font-size: 11px;
            font-weight: 700;
        }

        .gp-admin-tab {
            display: inline-flex;
            align-items: center;

            padding: 9px 13px;

            border-radius: 11px;

            color: #6b7280;

            text-decoration: none;

            font-size: 11px;
            font-weight: 800;

            transition: .2s ease;
        }

        .gp-admin-tab:hover {
            color: #E8631A;

            background: #fff;
        }
    </style>


    {{-- =============================================================
    PREVIEWS JS
    ============================================================== --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
            |--------------------------------------------------------------------------
            | Vidéo
            |--------------------------------------------------------------------------
            */

            const videoInput =
                document.getElementById('aboutBannerVideoInput');

            const videoPreview =
                document.getElementById('aboutBannerVideoPreview');

            const videoEmpty =
                document.getElementById('aboutVideoEmpty');

            const videoFilename =
                document.getElementById('aboutVideoFilename');


            if (videoInput && videoPreview) {

                videoInput.addEventListener('change', function () {

                    const file = this.files?.[0];

                    if (!file) {
                        return;
                    }

                    const url = URL.createObjectURL(file);

                    videoPreview.src = url;

                    videoPreview.classList.remove('hidden');

                    if (videoEmpty) {
                        videoEmpty.classList.add('hidden');
                    }

                    if (videoFilename) {

                        videoFilename.textContent =
                            'Vidéo sélectionnée : ' + file.name;

                        videoFilename.classList.remove('hidden');
                    }

                    videoPreview.load();

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Poster
            |--------------------------------------------------------------------------
            */

            const posterInput =
                document.getElementById('aboutPosterInput');

            const posterPreview =
                document.getElementById('aboutPosterPreview');

            const posterEmpty =
                document.getElementById('aboutPosterEmpty');


            if (posterInput && posterPreview) {

                posterInput.addEventListener('change', function () {

                    const file = this.files?.[0];

                    if (!file) {
                        return;
                    }

                    posterPreview.src =
                        URL.createObjectURL(file);

                    posterPreview.classList.remove('hidden');

                    if (posterEmpty) {
                        posterEmpty.classList.add('hidden');
                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | Image Notre histoire
            |--------------------------------------------------------------------------
            */

            const storyInput =
                document.getElementById('storyImageInput');

            const storyPreview =
                document.getElementById('storyImagePreview');

            const storyEmpty =
                document.getElementById('storyImageEmpty');


            if (storyInput && storyPreview) {

                storyInput.addEventListener('change', function () {

                    const file = this.files?.[0];

                    if (!file) {
                        return;
                    }

                    storyPreview.src =
                        URL.createObjectURL(file);

                    storyPreview.classList.remove('hidden');

                    if (storyEmpty) {
                        storyEmpty.classList.add('hidden');
                    }

                });

            }

        });
    </script>

@endsection