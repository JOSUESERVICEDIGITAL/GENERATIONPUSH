<section x-data="{ confirmingDeletion: false }">
    <header class="mb-6">
        <h2 class="text-lg font-bold text-[#1A1A1A]">Supprimer le compte</h2>
        <p class="mt-1 text-sm text-gray-500">
            Une fois ton compte supprimé, toutes ses données seront définitivement effacées. Télécharge toute donnée que tu souhaites conserver avant de continuer.
        </p>
    </header>

    <button type="button" @click="confirmingDeletion = true" class="inline-flex items-center justify-center px-6 py-2.5 bg-destructive border border-transparent rounded-lg font-semibold text-sm text-white tracking-wide hover:opacity-90 transition-all duration-200 cursor-pointer">
        Supprimer le compte
    </button>

    <div
        x-show="confirmingDeletion"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div class="fixed inset-0 bg-black/50" @click="confirmingDeletion = false"></div>
        <div class="relative bg-white rounded-2xl shadow-lg w-full max-w-md p-6">
            <h3 class="font-bold text-[#1A1A1A]">Es-tu sûr de vouloir supprimer ton compte ?</h3>
            <p class="text-sm text-gray-500 mt-2">
                Cette action est irréversible. Entre ton mot de passe pour confirmer la suppression définitive de ton compte.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="mt-5 space-y-4">
                @csrf
                @method('delete')

                <div>
                    <x-input-label for="password" value="Mot de passe" class="sr-only" />
                    <x-text-input id="password" name="password" type="password" class="block w-full" placeholder="Mot de passe" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="flex justify-end gap-3">
                    <x-secondary-button @click="confirmingDeletion = false">Annuler</x-secondary-button>
                    <x-danger-button>Supprimer le compte</x-danger-button>
                </div>
            </form>
        </div>
    </div>
</section>
