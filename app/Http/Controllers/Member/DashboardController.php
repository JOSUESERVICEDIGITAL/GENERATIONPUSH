<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | MESSAGES
        |--------------------------------------------------------------------------
        */

        $messagesCount = $user->chatMessages()->count();

        $unreadMessagesCount = $user->chatMessages()
            ->where('is_from_admin', true)
            ->whereNull('read_by_member_at')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | FAVORIS
        |--------------------------------------------------------------------------
        */

        $bookmarksCount = $user->bookmarkedPosts()->count();


        /*
        |--------------------------------------------------------------------------
        | PROFIL
        |--------------------------------------------------------------------------
        */

        $profileComplete = $user->hasCompleteProfile();

        $profileCompletion = 0;

        $profileFields = [
            $user->name,
            $user->email,
            $user->phone,
            $user->country,
            $user->city,
            $user->address,
            $user->profile_photo,
            $user->profile_completed_at,
        ];

        $completedFields = collect($profileFields)
            ->filter(fn ($value) => filled($value))
            ->count();

        $totalProfileFields = count($profileFields);

        if ($totalProfileFields > 0) {
            $profileCompletion = (int) round(
                ($completedFields / $totalProfileFields) * 100
            );
        }


        return view('member.dashboard', [
            'user' => $user,

            'messagesCount' => $messagesCount,

            'unreadMessagesCount' => $unreadMessagesCount,

            'bookmarksCount' => $bookmarksCount,

            'profileComplete' => $profileComplete,

            'profileCompletion' => $profileCompletion,
        ]);
    }
}