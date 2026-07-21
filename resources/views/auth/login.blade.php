<x-guest-layout :title="'Connexion — Generation PUSH'">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">Content de te revoir 👋</h1>
        <p class="text-gray-500 text-sm mt-1">Connecte-toi pour accéder à ton espace.</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 text-sm font-medium text-green-600">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center gap-2">
                <x-checkbox name="remember" />
                <span class="text-sm text-gray-600">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-accent hover:underline" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <x-primary-button class="w-full">
            Se connecter
        </x-primary-button>

        <p class="text-center text-sm text-gray-500">
            Pas encore de compte ?
            <a href="{{ route('register') }}" class="text-accent font-semibold hover:underline">Rejoindre la communauté</a>
        </p>
    </form>
</x-guest-layout>
