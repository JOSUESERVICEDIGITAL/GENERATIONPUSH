<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\CustomPage;

class CustomPageController extends Controller
{
    public function show(string $slug)
    {
        $page = CustomPage::where('slug', $slug)->where('status', 'published')->firstOrFail();

        return view('front.custom-page', compact('page'));
    }
}
