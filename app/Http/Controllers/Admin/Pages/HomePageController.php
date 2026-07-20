<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteSettings\UpdateSiteSettingRequest;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class HomePageController extends Controller
{
    public function edit()
    {
        $settings = SiteSetting::current();

        return view('admin.pages.home.edit', compact('settings'));
    }

    public function update(UpdateSiteSettingRequest $request)
    {
        $settings = SiteSetting::current();
        $data = $request->validated();
        $data['hero_cta_url'] = $data['hero_cta_url'] ?? null;

        if ($request->hasFile('hero_video')) {
            if ($settings->hero_video_path) {
                Storage::disk('public')->delete($settings->hero_video_path);
            }
            $data['hero_video_path'] = $request->file('hero_video')->store('site/hero', 'public');
        }

        if ($request->hasFile('hero_poster')) {
            if ($settings->hero_poster_path) {
                Storage::disk('public')->delete($settings->hero_poster_path);
            }
            $data['hero_poster_path'] = $request->file('hero_poster')->store('site/hero', 'public');
        }

        if ($request->hasFile('about_image')) {
            if ($settings->about_image_path) {
                Storage::disk('public')->delete($settings->about_image_path);
            }
            $data['about_image_path'] = $request->file('about_image')->store('site/about', 'public');
        }

        $settings->update($data);

        return redirect()
            ->route('admin.pages.home.edit')
            ->with('success', 'Page d\'accueil mise à jour avec succès.');
    }
}
