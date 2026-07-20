<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Formation;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\User;

class AboutController extends Controller
{
    public function __invoke()
    {
        $settings = SiteSetting::current();
        $team = TeamMember::where('status', 'active')->orderBy('order')->get();

        $stats = [
            'members' => User::count(),
            'formations' => Formation::count(),
            'events' => Conference::count(),
        ];

        return view('front.about', compact('settings', 'team', 'stats'));
    }
}
