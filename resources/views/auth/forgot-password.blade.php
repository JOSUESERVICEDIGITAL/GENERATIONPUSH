<x-guest-layout :title="'Mot de passe oublié — Generation PUSH'">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">Mot de passe oublié ?</h1>
        <p class="text-gray-500 text-sm mt-1">Pas de souci, on t'envoie un lien de réinitialisation par email.</p>
    </div>

    <div class="mb-4 text-sm text-gray-500">
        @if (session('status'))
            <span class="text-green-600 font-medium">{{ session('status') }}</span>
        @endif
    </div>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Envoyer le lien de réinitialisation
        </x-primary-button>

        <p class="text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline">← Retour à la connexion</a>
        </p>
    </form>
</x-guest-layout>
