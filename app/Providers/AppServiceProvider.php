<?php

namespace App\Providers;

use App\Models\CoachingSession;
use App\Models\Conference;
use App\Models\Masterclass;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::morphMap([
            'conference' => Conference::class,
            'masterclass' => Masterclass::class,
            'coaching_session' => CoachingSession::class,
        ]);
    }
}
