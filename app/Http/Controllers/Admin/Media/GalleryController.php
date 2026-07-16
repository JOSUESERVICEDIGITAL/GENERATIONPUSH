<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Gallery\StoreGalleryImageRequest;
use App\Http\Requests\Admin\Gallery\UpdateGalleryImageRequest;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $album = $request->query('album');

        $images = GalleryImage::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($album, fn ($query) => $query->where('album', $album))
            ->latest()
            ->paginate(16)
            ->withQueryString();

        $stats = [
            'total' => GalleryImage::count(),
            'published' => GalleryImage::where('status', 'published')->count(),
        ];

        $albums = GalleryImage::whereNotNull('album')->distinct()->pluck('album');

        return view('admin.media.gallery.index', compact('images', 'stats', 'search', 'album', 'albums'));
    }

    public function store(StoreGalleryImageRequest $request)
    {
        $data = $request->validated();
        $data['image_path'] = $request->file('image')->store('gallery', 'public');

        GalleryImage::create($data);

        return redirect()
            ->route('admin.media.gallery.index')
            ->with('success', 'Image ajoutée avec succès.');
    }

    public function update(UpdateGalleryImageRequest $request, GalleryImage $image)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $image->update($data);

        return redirect()
            ->route('admin.media.gallery.index')
            ->with('success', 'Image mise à jour avec succès.');
    }

    public function destroy(GalleryImage $image)
    {
        if ($image->image_path) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()
            ->route('admin.media.gallery.index')
            ->with('success', 'Image supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $images = GalleryImage::whereIn('id', $ids)->get();

        foreach ($images as $image) {
            if ($image->image_path) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        GalleryImage::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.media.gallery.index')
            ->with('success', count($ids) . ' image(s) supprimée(s).');
    }
}
