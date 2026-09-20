<?php

namespace App\Providers;

use App\Models\CoachingSession;
use App\Models\Conference;
use App\Models\Masterclass;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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

         Gate::define('member.dashboard', function ($user) {
        return $user
            && $user->role === 'Member'
            && $user->status === 'active';
    });

    Gate::define('member.profile', function ($user) {
        return $user
            && $user->role === 'Member'
            && $user->status === 'active';
    });

    Gate::define('member.messages', function ($user) {
        return $user
            && $user->role === 'Member'
            && $user->status === 'active'
            && $user->chat_enabled;
    });

    Gate::define('member.bookmarks', function ($user) {
        return $user
            && $user->role === 'Member'
            && $user->status === 'active';
    });
    Gate::define('member.dashboard', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.profile', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.messages', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active'
        && $user->chat_enabled;
});

Gate::define('member.notifications', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.bookmarks', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.formations', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.events', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.reservations', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.orders', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.payments', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});

Gate::define('member.settings', function ($user) {
    return $user
        && $user->role === 'Member'
        && $user->status === 'active';
});
    }
    
}
