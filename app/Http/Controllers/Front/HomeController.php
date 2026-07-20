<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Formation;
use App\Models\Post;
use App\Models\Sponsor;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\SiteSetting;

class HomeController extends Controller
{
    public function __invoke()
    {
        $settings = SiteSetting::current();

        $stats = [
            'members' => User::count(),
            'formations' => Formation::count(),
            'events' => Conference::count(),
            'satisfaction' => Testimonial::avg('rating') ? round(Testimonial::avg('rating') / 5 * 100) : 98,
        ];

        $formations = Formation::where('status', 'active')->latest()->take(3)->get();
        $events = Conference::where('date', '>=', now())->orderBy('date')->take(3)->get();
        $testimonials = Testimonial::where('status', 'published')->where('featured', true)->take(6)->get();
        $sponsors = Sponsor::where('status', 'active')->orderBy('order')->get();
        $posts = Post::where('status', 'published')->with('category')->latest('published_at')->take(3)->get();

        return view('front.home', compact('settings', 'stats', 'formations', 'events', 'testimonials', 'sponsors', 'posts'));
    }
}
