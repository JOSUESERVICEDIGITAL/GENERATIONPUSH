<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Founder\UpdateFounderProfileRequest;
use App\Models\FounderPhoto;
use App\Models\FounderProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FounderController extends Controller
{
    public function edit()
    {
        $founder = FounderProfile::current();

        return view('admin.pages.founder.edit', compact('founder'));
    }

    public function update(UpdateFounderProfileRequest $request)
    {
        $founder = FounderProfile::current();
        $data = $request->validated();

        foreach (['show_bio', 'show_why_founded', 'show_mission', 'show_social', 'show_gallery', 'is_page_enabled'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        if ($request->hasFile('main_photo')) {
            if ($founder->main_photo_path) {
                Storage::disk('public')->delete($founder->main_photo_path);
            }
            $data['main_photo_path'] = $request->file('main_photo')->store('founder', 'public');
        }

        $founder->update($data);

        return redirect()
            ->route('admin.pages.founder.edit')
            ->with('success', 'Page fondatrice mise à jour avec succès.');
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'max:6144'],
        ]);

        $founder = FounderProfile::current();
        $order = FounderPhoto::where('founder_profile_id', $founder->id)->max('order') ?? 0;

        foreach ($request->file('photos') as $file) {
            $order++;

            FounderPhoto::create([
                'founder_profile_id' => $founder->id,
                'image_path' => $file->store('founder/gallery', 'public'),
                'order' => $order,
            ]);
        }

        $count = count($request->file('photos'));

        return redirect()
            ->route('admin.pages.founder.edit')
            ->with('success', $count > 1 ? "{$count} photos ajoutées à la galerie." : 'Photo ajoutée à la galerie.');
    }

    public function destroyPhoto(FounderPhoto $photo)
    {
        if ($photo->image_path) {
            Storage::disk('public')->delete($photo->image_path);
        }

        $photo->delete();

        return redirect()
            ->route('admin.pages.founder.edit')
            ->with('success', 'Photo supprimée.');
    }
}
