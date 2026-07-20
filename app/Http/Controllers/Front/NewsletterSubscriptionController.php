<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;

class NewsletterSubscriptionController extends Controller
{
    public function store(StoreNewsletterSubscriberRequest $request)
    {
        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->validated('email')],
            ['status' => 'subscribed']
        );

        return redirect()
            ->back()
            ->with('success', 'Merci ! Tu es maintenant inscrit à la newsletter.');
    }
}
