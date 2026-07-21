<x-guest-layout :title="'Vérifie ton email — Generation PUSH'">
    <div class="mb-8">
        <div class="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center mb-5">
            <x-icon name="mail" class="w-6 h-6 text-accent" />
        </div>
        <h1 class="text-2xl font-bold text-[#1A1A1A]">Vérifie ton adresse email</h1>
        <p class="text-gray-500 text-sm mt-2 leading-relaxed">
            Merci de t'être inscrit ! Avant de commencer, peux-tu confirmer ton adresse email en cliquant sur le lien qu'on vient de t'envoyer ? Si tu ne l'as pas reçu, on peut t'en renvoyer un autre.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 text-sm font-medium text-green-600">
            Un nouveau lien de vérification a été envoyé à l'adresse email que tu as fournie lors de l'inscription.
        </div>
    @endif

    <div class="flex items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Renvoyer l'email de vérification
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-gray-500 hover:text-accent transition-colors duration-200 cursor-pointer">
                Déconnexion
            </button>
        </form>
    </div>
</x-guest-layout>
