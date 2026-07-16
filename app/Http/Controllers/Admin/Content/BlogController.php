<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Posts\StorePostRequest;
use App\Http\Requests\Admin\Posts\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $categoryId = $request->query('category_id');

        $posts = Post::query()
            ->with('category')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft' => Post::where('status', 'draft')->count(),
            'views' => (int) Post::sum('views'),
        ];

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.content.blog.index', compact('posts', 'stats', 'search', 'status', 'categoryId', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.content.blog.create', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        if ($request->hasFile('cover_image')) {
            $data['cover_image_path'] = $request->file('cover_image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()
            ->route('admin.content.blog.index')
            ->with('success', 'Article créé avec succès.');
    }

    public function edit(Post $blog)
    {
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return view('admin.content.blog.edit', ['post' => $blog, 'categories' => $categories]);
    }

    public function update(UpdatePostRequest $request, Post $blog)
    {
        $data = $request->validated();

        if ($request->hasFile('cover_image')) {
            if ($blog->cover_image_path) {
                Storage::disk('public')->delete($blog->cover_image_path);
            }
            $data['cover_image_path'] = $request->file('cover_image')->store('posts', 'public');
        }

        $blog->update($data);

        return redirect()
            ->route('admin.content.blog.index')
            ->with('success', 'Article mis à jour avec succès.');
    }

    public function destroy(Post $blog)
    {
        if ($blog->cover_image_path) {
            Storage::disk('public')->delete($blog->cover_image_path);
        }

        $blog->delete();

        return redirect()
            ->route('admin.content.blog.index')
            ->with('success', 'Article supprimé.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = (array) $request->input('ids', []);
        $posts = Post::whereIn('id', $ids)->get();

        foreach ($posts as $post) {
            if ($post->cover_image_path) {
                Storage::disk('public')->delete($post->cover_image_path);
            }
        }

        Post::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.content.blog.index')
            ->with('success', count($ids) . ' article(s) supprimé(s).');
    }
}
