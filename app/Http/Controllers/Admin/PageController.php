<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    /**
     * Liste des pages administrables.
     */
    public function index()
    {
        $pages = Page::query()
            ->orderBy('title')
            ->get();

        return view('admin.pages.index', compact('pages'));
    }


    /**
     * Formulaire d'édition.
     */
    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }


    /**
     * Mise à jour.
     */
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
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

            'banner_video_enabled' => [
                'nullable',
                'boolean',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Vidéo
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner_video')) {

            if ($page->banner_video) {
                Storage::disk('public')
                    ->delete($page->banner_video);
            }

            $validated['banner_video'] =
                $request->file('banner_video')
                    ->store('pages/banners/videos', 'public');
        }


        /*
        |--------------------------------------------------------------------------
        | Poster
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('banner_poster')) {

            if ($page->banner_poster) {
                Storage::disk('public')
                    ->delete($page->banner_poster);
            }

            $validated['banner_poster'] =
                $request->file('banner_poster')
                    ->store('pages/banners/posters', 'public');
        }


        $validated['banner_video_enabled'] =
            $request->boolean('banner_video_enabled');


        $page->update($validated);


        return back()->with(
            'success',
            'La page a été mise à jour avec succès.'
        );
    }


    /**
     * Supprimer la vidéo du banner.
     */
    public function destroyBannerVideo(Page $page)
    {
        if ($page->banner_video) {
            Storage::disk('public')
                ->delete($page->banner_video);
        }

        $page->update([
            'banner_video' => null,
            'banner_video_enabled' => false,
        ]);

        return back()->with(
            'success',
            'La vidéo de bannière a été supprimée.'
        );
    }


    /**
     * Supprimer le poster.
     */
    public function destroyBannerPoster(Page $page)
    {
        if ($page->banner_poster) {
            Storage::disk('public')
                ->delete($page->banner_poster);
        }

        $page->update([
            'banner_poster' => null,
        ]);

        return back()->with(
            'success',
            'Le poster a été supprimé.'
        );
    }
}