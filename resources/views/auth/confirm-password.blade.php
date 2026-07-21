<x-guest-layout :title="'Confirme ton mot de passe — Generation PUSH'">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-[#1A1A1A]">Zone sécurisée</h1>
        <p class="text-gray-500 text-sm mt-1">Confirme ton mot de passe avant de continuer.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Mot de passe" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Confirmer
        </x-primary-button>
    </form>
</x-guest-layout>
