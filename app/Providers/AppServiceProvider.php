<?php

namespace App\Providers;

use App\Models\CoachingSession;
use App\Models\Conference;
use App\Models\Masterclass;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
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

        // Railway (et la plupart des PaaS) terminent le HTTPS au niveau du proxy
        // et transmettent les requêtes en HTTP en interne. Sans ça, Laravel génère
        // tous ses liens (CSS, JS, routes) en http:// même si le site est servi en
        // https://, ce que les navigateurs bloquent silencieusement (contenu mixte).
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
}
