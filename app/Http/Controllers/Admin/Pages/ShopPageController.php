<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopPageController extends Controller
{
    public function edit()
    {
        $page = Page::firstOrCreate(
            ['key' => 'shop'],
            [
                'title' => 'Boutique',
                'subtitle' => 'Des ressources pour apprendre, grandir et passer à l’action.',
                'status' => 'active',
            ]
        );

        return view('admin.pages.shop.edit', compact('page'));
    }



    public function update(Request $request)
    {
        $page = Page::firstOrCreate(
            ['key' => 'shop'],
            [
                'title' => 'Boutique',
                'status' => 'active',
            ]
        );

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Général
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


            /*
            |--------------------------------------------------------------------------
            | Hero
            |--------------------------------------------------------------------------
            */

            'shop_hero_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_hero_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_hero_highlight' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_hero_text' => [
                'nullable',
                'string',
                'max:2000',
            ],


            /*
            |--------------------------------------------------------------------------
            | Vidéo Hero
            |--------------------------------------------------------------------------
            */

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
            | Catalogue
            |--------------------------------------------------------------------------
            */

            'shop_catalogue_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_catalogue_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_catalogue_highlight' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_catalogue_text' => [
                'nullable',
                'string',
                'max:2000',
            ],


            /*
            |--------------------------------------------------------------------------
            | CTA final
            |--------------------------------------------------------------------------
            */

            'shop_cta_eyebrow' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_cta_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_cta_highlight' => [
                'nullable',
                'string',
                'max:255',
            ],

            'shop_cta_text' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        $validated['banner_video_enabled'] =
            $request->boolean('banner_video_enabled');


        /*
        |--------------------------------------------------------------------------
        | Vidéo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner_video')) {

            $newVideo = $request
                ->file('banner_video')
                ->store(
                    'pages/shop/banner/videos',
                    'public'
                );

            if ($page->banner_video) {
                Storage::disk('public')
                    ->delete($page->banner_video);
            }

            $validated['banner_video'] = $newVideo;
        }


        /*
        |--------------------------------------------------------------------------
        | Poster
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner_poster')) {

            $newPoster = $request
                ->file('banner_poster')
                ->store(
                    'pages/shop/banner/posters',
                    'public'
                );

            if ($page->banner_poster) {
                Storage::disk('public')
                    ->delete($page->banner_poster);
            }

            $validated['banner_poster'] = $newPoster;
        }


        $page->update($validated);


        return redirect()
            ->route('admin.pages.shop.edit')
            ->with(
                'success',
                'La page Boutique a été mise à jour avec succès.'
            );
    }


    public function destroyBannerVideo()
    {
        $page = Page::where('key', 'shop')
            ->firstOrFail();

        if ($page->banner_video) {

            Storage::disk('public')
                ->delete($page->banner_video);
        }

        $page->update([
            'banner_video' => null,
            'banner_video_enabled' => false,
        ]);

        return redirect()
            ->route('admin.pages.shop.edit')
            ->with(
                'success',
                'La vidéo de la Boutique a été supprimée.'
            );
    }


    public function destroyBannerPoster()
    {
        $page = Page::where('key', 'shop')
            ->firstOrFail();

        if ($page->banner_poster) {

            Storage::disk('public')
                ->delete($page->banner_poster);
        }

        $page->update([
            'banner_poster' => null,
        ]);

        return redirect()
            ->route('admin.pages.shop.edit')
            ->with(
                'success',
                'Le poster de la Boutique a été supprimé.'
            );
    }
}