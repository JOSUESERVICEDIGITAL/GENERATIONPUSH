<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutPageController extends Controller
{
    /**
     * Afficher le formulaire d'édition de la page À propos.
     */
    public function edit()
    {
        $page = Page::firstOrCreate(
            ['key' => 'about'],
            [
                'title' => 'À propos de nous',
                'subtitle' => "Découvre la mission, l'histoire et les visages qui donnent vie à Generation PUSH",
                'status' => 'active',
            ]
        );

        return view('admin.pages.about.edit', compact('page'));
    }


    /**
     * Mettre à jour toute la page À propos.
     */
    public function update(Request $request)
    {
        $page = Page::firstOrCreate(
            ['key' => 'about'],
            [
                'title' => 'À propos de nous',
                'status' => 'active',
            ]
        );

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Bannière
            |--------------------------------------------------------------------------
            */

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'banner_video_enabled' => [
                'nullable',
                'boolean',
            ],

            'banner_video' => [
                'nullable',
                'file',
                'mimes:mp4,webm',
                'max:51200',
            ],

            'banner_poster' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],


            /*
            |--------------------------------------------------------------------------
            | Notre histoire
            |--------------------------------------------------------------------------
            */

            'story_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'story_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'story_text' => [
                'nullable',
                'string',
            ],

            'story_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'story_card_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'story_card_text' => [
                'nullable',
                'string',
                'max:500',
            ],


            /*
            |--------------------------------------------------------------------------
            | Fondatrice
            |--------------------------------------------------------------------------
            */

            'founder_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'founder_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'founder_text' => [
                'nullable',
                'string',
            ],

            'founder_button_text' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Valeurs
            |--------------------------------------------------------------------------
            */

            'values_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'values_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'values_intro' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'value_1_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'value_1_short' => [
                'nullable',
                'string',
                'max:255',
            ],

            'value_1_text' => [
                'nullable',
                'string',
            ],

            'value_2_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'value_2_short' => [
                'nullable',
                'string',
                'max:255',
            ],

            'value_2_text' => [
                'nullable',
                'string',
            ],

            'value_3_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'value_3_short' => [
                'nullable',
                'string',
                'max:255',
            ],

            'value_3_text' => [
                'nullable',
                'string',
            ],


            /*
            |--------------------------------------------------------------------------
            | Équipe
            |--------------------------------------------------------------------------
            */

            'team_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'team_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'team_intro' => [
                'nullable',
                'string',
                'max:1000',
            ],


            /*
            |--------------------------------------------------------------------------
            | CTA final
            |--------------------------------------------------------------------------
            */

            'cta_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'cta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'cta_highlight' => [
                'nullable',
                'string',
                'max:255',
            ],

            'cta_text' => [
                'nullable',
                'string',
                'max:1500',
            ],

            'cta_button_text' => [
                'nullable',
                'string',
                'max:255',
            ],

            'cta_button_url' => [
                'nullable',
                'string',
                'max:2048',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Checkbox vidéo
        |--------------------------------------------------------------------------
        |
        | Une checkbox non cochée n'est normalement pas envoyée.
        | On force donc toujours true/false.
        |
        */

        $validated['banner_video_enabled'] =
            $request->boolean('banner_video_enabled');


        /*
        |--------------------------------------------------------------------------
        | Upload / remplacement de la vidéo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner_video')) {

            $newVideo = $request
                ->file('banner_video')
                ->store('pages/about/banner/videos', 'public');

            if ($page->banner_video) {
                Storage::disk('public')->delete($page->banner_video);
            }

            $validated['banner_video'] = $newVideo;
        }


        /*
        |--------------------------------------------------------------------------
        | Upload / remplacement du poster
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner_poster')) {

            $newPoster = $request
                ->file('banner_poster')
                ->store('pages/about/banner/posters', 'public');

            if ($page->banner_poster) {
                Storage::disk('public')->delete($page->banner_poster);
            }

            $validated['banner_poster'] = $newPoster;
        }


        /*
        |--------------------------------------------------------------------------
        | Upload / remplacement image Notre histoire
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('story_image')) {

            $newStoryImage = $request
                ->file('story_image')
                ->store('pages/about/story', 'public');

            if ($page->story_image) {
                Storage::disk('public')->delete($page->story_image);
            }

            $validated['story_image'] = $newStoryImage;
        }


        /*
        |--------------------------------------------------------------------------
        | Mise à jour
        |--------------------------------------------------------------------------
        */

        $page->update($validated);


        return redirect()
            ->route('admin.pages.about.edit')
            ->with(
                'success',
                'La page À propos a été mise à jour avec succès.'
            );
    }


    /**
     * Supprimer la vidéo de bannière.
     */
    public function destroyBannerVideo()
    {
        $page = Page::where('key', 'about')->firstOrFail();

        if ($page->banner_video) {
            Storage::disk('public')->delete($page->banner_video);
        }

        $page->update([
            'banner_video' => null,
            'banner_video_enabled' => false,
        ]);

        return redirect()
            ->route('admin.pages.about.edit')
            ->with(
                'success',
                'La vidéo de la bannière a été supprimée.'
            );
    }


    /**
     * Supprimer le poster de la bannière.
     */
    public function destroyBannerPoster()
    {
        $page = Page::where('key', 'about')->firstOrFail();

        if ($page->banner_poster) {
            Storage::disk('public')->delete($page->banner_poster);
        }

        $page->update([
            'banner_poster' => null,
        ]);

        return redirect()
            ->route('admin.pages.about.edit')
            ->with(
                'success',
                'Le poster de la bannière a été supprimé.'
            );
    }


    /**
     * Supprimer l'image de la section Notre histoire.
     */
    public function destroyStoryImage()
    {
        $page = Page::where('key', 'about')->firstOrFail();

        if ($page->story_image) {
            Storage::disk('public')->delete($page->story_image);
        }

        $page->update([
            'story_image' => null,
        ]);

        return redirect()
            ->route('admin.pages.about.edit')
            ->with(
                'success',
                "L'image de la section Notre histoire a été supprimée."
            );
    }
}