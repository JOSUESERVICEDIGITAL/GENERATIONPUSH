<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));
        $categorySlug = $request->query('category');

        $posts = Post::query()
            ->where('status', 'published')
            ->with('category')
            ->when($search !== '', fn ($query) => $query->where('title', 'like', "%{$search}%"))
            ->when($categorySlug, function ($query) use ($categorySlug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $categorySlug));
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::has('posts')->orderBy('name')->get();

        return view('front.blog.index', compact('posts', 'categories', 'search', 'categorySlug'));
    }

    public function show(Post $post)
    {
        abort_if($post->status !== 'published', 404);

        $post->increment('views');

        $related = Post::where('status', 'published')
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('front.blog.show', compact('post', 'related'));
    }
}
