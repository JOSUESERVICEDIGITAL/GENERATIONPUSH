<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreEngagementApplicationRequest;
use App\Models\EngagementApplication;
use App\Models\EngagementPage;

class EngagementController extends Controller
{
    public function partner()
    {
        $page = EngagementPage::forType('partner');
        abort_unless($page->is_visible, 404);

        return view('front.engagement', ['page' => $page, 'type' => 'partner']);
    }

    public function volunteer()
    {
        $page = EngagementPage::forType('volunteer');
        abort_unless($page->is_visible, 404);

        return view('front.engagement', ['page' => $page, 'type' => 'volunteer']);
    }

    public function store(StoreEngagementApplicationRequest $request)
    {
        EngagementApplication::create($request->validated());

        return redirect()
            ->back()
            ->with('success', "Merci pour ta candidature ! On te recontacte très vite.");
    }
}
