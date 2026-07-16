<?php

namespace App\Http\Controllers\Admin\Media;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Videos\StoreVideoRequest;
use App\Http\Requests\Admin\Videos\UpdateVideoRequest;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');

        $videos = Video::query()
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Video::count(),
            'published' => Video::where('status', 'published')->count(),
            'views' => (int) Video::sum('views'),
        ];

        return view('admin.media.videos.index', compact('videos', 'stats', 'search', 'status'));
    }

    public function store(StoreVideoRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('videos', 'public');
        }
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        Video::create($data);

        return redirect()
            ->route('admin.media.videos.index')
            ->with('success', 'Vidéo ajoutée avec succès.');
    }

    public function update(UpdateVideoRequest $request, Video $video)
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($video->file_path) {
                Storage::disk('public')->delete($video->file_path);
            }
            $data['file_path'] = $request->file('file')->store('videos', 'public');
        }
        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('videos/thumbnails', 'public');
        }

        $video->update($data);

        return redirect()
            ->route('admin.media.videos.index')
            ->with('success', 'Vidéo mise à jour avec succès.');
    }

    public function destroy(Video $video)
    {
        if ($video->file_path) {
            Storage::disk('public')->delete($video->file_path);
        }
        if ($video->thumbnail_path) {
            Storage::disk('public')->delete($video->thumbnail_path);
        }

        $video->delete();

        return redirect()
            ->route('admin.media.videos.index')
            ->with('success', 'Vidéo supprimée.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $videos = Video::whereIn('id', $ids)->get();

        foreach ($videos as $video) {
            if ($video->file_path) {
                Storage::disk('public')->delete($video->file_path);
            }
            if ($video->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }
        }

        Video::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.media.videos.index')
            ->with('success', count($ids) . ' vidéo(s) supprimée(s).');
    }
}
