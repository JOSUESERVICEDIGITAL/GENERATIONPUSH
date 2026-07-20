<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\SiteSetting;

class ContactController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::current();

        return view('front.contact', compact('settings'));
    }

    public function store(StoreContactMessageRequest $request)
    {
        ContactMessage::create($request->validated());

        return redirect()
            ->route('front.contact')
            ->with('success', 'Ton message a bien été envoyé, merci ! Nous te répondrons rapidement.');
    }
}
