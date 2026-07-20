<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Masterclass;

class EventController extends Controller
{
    public function index()
    {
        $conferences = Conference::all()->map(fn ($e) => [
            'title' => $e->title,
            'location' => $e->location,
            'country' => $e->country,
            'date' => $e->date,
            'type' => 'Conférence',
        ]);

        $masterclasses = Masterclass::all()->map(fn ($e) => [
            'title' => $e->title,
            'location' => $e->location,
            'country' => $e->country,
            'date' => $e->date,
            'type' => 'Masterclass',
        ]);

        $all = $conferences->concat($masterclasses)->sortBy('date');

        $upcoming = $all->filter(fn ($e) => $e['date'] && $e['date']->isFuture())->values();
        $past = $all->filter(fn ($e) => ! $e['date'] || $e['date']->isPast())->sortByDesc('date')->values();

        return view('front.events', compact('upcoming', 'past'));
    }
}
