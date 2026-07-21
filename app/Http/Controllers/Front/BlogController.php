<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostBookmark;
use App\Models\PostLike;
use App\Models\PostRating;
use App\Models\PostReadSession;
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
            ->withCount(['likes', 'bookmarks'])
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

        $user = auth()->user();
        $liked = $post->isLikedBy($user);
        $bookmarked = $post->isBookmarkedBy($user);
        $myRating = $post->ratingBy($user);
        $likesCount = $post->likes()->count();
        $averageRating = $post->averageRating();

        return view('front.blog.show', compact('post', 'related', 'liked', 'bookmarked', 'myRating', 'likesCount', 'averageRating'));
    }

    public function toggleLike(Post $post)
    {
        abort_unless(auth()->check(), 403);

        $existing = PostLike::where('post_id', $post->id)->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            PostLike::create(['post_id' => $post->id, 'user_id' => auth()->id()]);
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'count' => $post->likes()->count(),
        ]);
    }

    public function toggleBookmark(Post $post)
    {
        abort_unless(auth()->check(), 403);

        $existing = PostBookmark::where('post_id', $post->id)->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->delete();
            $bookmarked = false;
        } else {
            PostBookmark::create(['post_id' => $post->id, 'user_id' => auth()->id()]);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }

    public function rate(Request $request, Post $post)
    {
        abort_unless(auth()->check(), 403);

        $request->validate(['rating' => ['required', 'integer', 'min:1', 'max:5']]);

        PostRating::updateOrCreate(
            ['post_id' => $post->id, 'user_id' => auth()->id()],
            ['rating' => $request->input('rating')]
        );

        return response()->json([
            'average' => $post->averageRating(),
            'my_rating' => (int) $request->input('rating'),
        ]);
    }

    public function trackReadTime(Request $request, Post $post)
    {
        $request->validate(['seconds' => ['required', 'integer', 'min:1', 'max:7200']]);

        PostReadSession::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'duration_seconds' => $request->input('seconds'),
        ]);

        return response()->json(['ok' => true]);
    }

    public function bookmarked()
    {
        abort_unless(auth()->check(), 403);

        $posts = auth()->user()->load(['bookmarkedPosts' => fn ($q) => $q->with('category')])->bookmarkedPosts;

        return view('front.blog.bookmarked', compact('posts'));
    }
}
