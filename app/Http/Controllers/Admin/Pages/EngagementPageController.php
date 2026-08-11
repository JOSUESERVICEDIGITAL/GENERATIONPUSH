<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Engagement\UpdateEngagementPageRequest;
use App\Models\EngagementPage;
use Illuminate\Support\Facades\Storage;

class EngagementPageController extends Controller
{
    public function edit(string $type)
    {
        abort_unless(in_array($type, ['partner', 'volunteer']), 404);

        $page = EngagementPage::forType($type);

        return view('admin.pages.engagement.edit', compact('page', 'type'));
    }

    public function update(UpdateEngagementPageRequest $request, string $type)
    {
        abort_unless(in_array($type, ['partner', 'volunteer']), 404);

        $page = EngagementPage::forType($type);
        $data = $request->validated();
        $data['is_visible'] = $request->boolean('is_visible');

        if ($request->hasFile('image')) {
            if ($page->image_path) {
                Storage::disk('public')->delete($page->image_path);
            }
            $data['image_path'] = $request->file('image')->store('engagement', 'public');
        }

        $page->update($data);

        return redirect()
            ->route('admin.pages.engagement.edit', $type)
            ->with('success', 'Page mise à jour avec succès.');
    }
}
