<x-layouts.admin title="Candidatures bénévoles">

    <div x-data="{
        selectedApplication: null,
        showDetailsModal: false,
        showStatusModal: false,
        showDeleteModal: false,

        openDetails(application) {
            this.selectedApplication = application;
            this.showDetailsModal = true;
        },

        openStatus(application) {
            this.selectedApplication = application;
            this.showStatusModal = true;
        },

        openDelete(application) {
            this.selectedApplication = application;
            this.showDeleteModal = true;
        },

        closeModals() {
            this.showDetailsModal = false;
            this.showStatusModal = false;
            this.showDeleteModal = false;
            this.selectedApplication = null;
        }
    }" class="space-y-6">

        {{-- ============================================================ --}}
        {{-- EN-TÊTE --}}
        {{-- ============================================================ --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center">
                        <x-icon name="heart-handshake" class="w-5 h-5 text-accent" />
                    </div>

                    <div>
                        <h1 class="text-2xl font-bold text-foreground">
                            Candidatures bénévoles
                        </h1>
                        <p class="text-sm text-muted-foreground mt-1">
                            Gérez les personnes souhaitant rejoindre l'équipe Generation PUSH.
                        </p>
                    </div>
                </div>
            </div>

            <a href="{{ route('front.volunteer') }}" target="_blank"
                class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-border hover:bg-secondary transition-colors text-sm font-medium">
                <x-icon name="external-link" class="w-4 h-4" />
                Voir le formulaire
            </a>
        </div>


        {{-- ============================================================ --}}
        {{-- KPI --}}
        {{-- ============================================================ --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

            {{-- Total --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Total candidatures
                        </p>
                        <p class="text-3xl font-bold text-foreground mt-2">
                            {{ $stats['total'] ?? 0 }}
                        </p>
                        <p class="text-xs text-muted-foreground mt-2">
                            Toutes les candidatures reçues
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center">
                        <x-icon name="users-round" class="w-5 h-5 text-accent" />
                    </div>
                </div>
            </div>

            {{-- En attente --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            En attente
                        </p>
                        <p class="text-3xl font-bold text-foreground mt-2">
                            {{ $stats['pending'] ?? 0 }}
                        </p>
                        <p class="text-xs text-amber-600 mt-2">
                            À traiter
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-amber-500/10 flex items-center justify-center">
                        <x-icon name="clock-3" class="w-5 h-5 text-amber-600" />
                    </div>
                </div>
            </div>

            {{-- En cours --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            En examen
                        </p>
                        <p class="text-3xl font-bold text-foreground mt-2">
                            {{ $stats['reviewing'] ?? 0 }}
                        </p>
                        <p class="text-xs text-blue-600 mt-2">
                            En cours d'étude
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-blue-500/10 flex items-center justify-center">
                        <x-icon name="search-check" class="w-5 h-5 text-blue-600" />
                    </div>
                </div>
            </div>

            {{-- Acceptées --}}
            <div class="bg-card border border-border rounded-xl p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Acceptées
                        </p>
                        <p class="text-3xl font-bold text-foreground mt-2">
                            {{ $stats['accepted'] ?? 0 }}
                        </p>
                        <p class="text-xs text-green-600 mt-2">
                            Prêtes à rejoindre GP
                        </p>
                    </div>

                    <div class="w-11 h-11 rounded-xl bg-green-500/10 flex items-center justify-center">
                        <x-icon name="circle-check" class="w-5 h-5 text-green-600" />
                    </div>
                </div>
            </div>

        </div>


        {{-- ============================================================ --}}
        {{-- RECHERCHE & FILTRES --}}
        {{-- ============================================================ --}}
        <div class="bg-card border border-border rounded-xl p-5">

            <form method="GET" action="{{ route('admin.volunteer-applications.index') }}">

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

                    {{-- Recherche --}}
                    <div class="xl:col-span-2">
                        <label class="block text-sm font-medium text-foreground mb-2">
                            Rechercher
                        </label>

                        <div class="relative">
                            <x-icon name="search"
                                class="absolute start-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />

                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Nom, email, téléphone ou profession..."
                                class="w-full ps-10 pe-4 py-2.5 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-accent">
                        </div>
                    </div>

                    {{-- Statut --}}
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-2">
                            Statut
                        </label>

                        <select name="status"
                            class="w-full px-4 py-2.5 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-accent">
                            <option value="">Tous les statuts</option>
                            <option value="pending" @selected(request('status') === 'pending')>
                                En attente
                            </option>
                            <option value="reviewing" @selected(request('status') === 'reviewing')>
                                En examen
                            </option>
                            <option value="accepted" @selected(request('status') === 'accepted')>
                                Acceptée
                            </option>
                            <option value="rejected" @selected(request('status') === 'rejected')>
                                Refusée
                            </option>
                        </select>
                    </div>

                    {{-- Disponibilité --}}
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-2">
                            Disponibilité
                        </label>

                        <select name="availability"
                            class="w-full px-4 py-2.5 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-accent">
                            <option value="">Toutes</option>
                            <option value="part_time" @selected(request('availability') === 'part_time')>
                                Temps partiel
                            </option>
                            <option value="events_only" @selected(request('availability') === 'events_only')>
                                Événements uniquement
                            </option>
                            <option value="full_time" @selected(request('availability') === 'full_time')>
                                Engagement fort
                            </option>
                        </select>
                    </div>

                </div>

                <div class="flex flex-wrap items-center gap-3 mt-5">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition">
                        <x-icon name="search" class="w-4 h-4" />
                        Rechercher
                    </button>

                    @if (request()->hasAny(['search', 'status', 'availability']))
                        <a href="{{ route('admin.volunteer-applications.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg hover:bg-secondary text-muted-foreground transition">
                            <x-icon name="x" class="w-4 h-4" />
                            Réinitialiser
                        </a>
                    @endif
                </div>

            </form>
        </div>


        {{-- ============================================================ --}}
        {{-- TABLEAU --}}
        {{-- ============================================================ --}}
        <div class="bg-card border border-border rounded-xl overflow-hidden">

            <div class="flex items-center justify-between p-5 border-b border-border">
                <div>
                    <h2 class="font-semibold text-foreground">
                        Liste des candidatures
                    </h2>
                    <p class="text-sm text-muted-foreground mt-1">
                        {{ $applications->total() }} candidature(s) trouvée(s)
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-secondary/50 border-b border-border">
                        <tr class="text-start">
                            <th class="px-5 py-3 font-medium text-muted-foreground">
                                Candidat
                            </th>
                            <th class="px-5 py-3 font-medium text-muted-foreground">
                                Localisation
                            </th>
                            <th class="px-5 py-3 font-medium text-muted-foreground">
                                Contribution
                            </th>
                            <th class="px-5 py-3 font-medium text-muted-foreground">
                                Disponibilité
                            </th>
                            <th class="px-5 py-3 font-medium text-muted-foreground">
                                Statut
                            </th>
                            <th class="px-5 py-3 font-medium text-muted-foreground">
                                Date
                            </th>
                            <th class="px-5 py-3 font-medium text-muted-foreground text-end">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-border">

                        @forelse($applications as $application)

                            @php
                                $statusClasses = match ($application->status) {
                                    'accepted' => 'bg-green-500/10 text-green-700',
                                    'rejected' => 'bg-red-500/10 text-red-700',
                                    'reviewing' => 'bg-blue-500/10 text-blue-700',
                                    default => 'bg-amber-500/10 text-amber-700',
                                };

                                $statusLabels = match ($application->status) {
                                    'accepted' => 'Acceptée',
                                    'rejected' => 'Refusée',
                                    'reviewing' => 'En examen',
                                    default => 'En attente',
                                };

                                $availabilityLabels = [
                                    'part_time' => 'Temps partiel',
                                    'events_only' => 'Événements',
                                    'full_time' => 'Engagement fort',
                                ];
                            @endphp

                            <tr class="hover:bg-secondary/30 transition-colors">

                                {{-- Candidat --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-accent/10 text-accent flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($application->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-medium text-foreground">
                                                {{ $application->name }}
                                            </p>
                                            <p class="text-xs text-muted-foreground">
                                                {{ $application->email }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- Localisation --}}
                                <td class="px-5 py-4 text-muted-foreground">
                                    {{ $application->city_country }}
                                </td>

                                {{-- Contribution --}}
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1 max-w-[220px]">
                                        @foreach (array_slice($application->contribution_areas ?? [], 0, 2) as $area)
                                            <span class="px-2 py-1 rounded-md bg-secondary text-xs text-foreground">
                                                {{ $area }}
                                            </span>
                                        @endforeach

                                        @if (count($application->contribution_areas ?? []) > 2)
                                            <span class="px-2 py-1 rounded-md bg-secondary text-xs text-muted-foreground">
                                                +{{ count($application->contribution_areas) - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Disponibilité --}}
                                <td class="px-5 py-4 text-muted-foreground">
                                    {{ $availabilityLabels[$application->availability] ?? $application->availability }}
                                </td>

                                {{-- Statut --}}
                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClasses }}">
                                        {{ $statusLabels }}
                                    </span>
                                </td>

                                {{-- Date --}}
                                <td class="px-5 py-4 text-muted-foreground whitespace-nowrap">
                                    {{ $application->created_at->format('d/m/Y') }}
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">

                                        {{-- Voir --}}
                                        <button type="button" @click='openDetails(@json($application))'
                                            title="Voir la candidature"
                                            class="w-9 h-9 rounded-lg hover:bg-secondary flex items-center justify-center text-muted-foreground hover:text-foreground transition">
                                            <x-icon name="eye" class="w-4 h-4" />
                                        </button>

                                        {{-- Statut --}}
                                        <button type="button" @click='openStatus(@json($application))'
                                            title="Modifier le statut"
                                            class="w-9 h-9 rounded-lg hover:bg-accent/10 flex items-center justify-center text-muted-foreground hover:text-accent transition">
                                            <x-icon name="square-pen" class="w-4 h-4" />
                                        </button>

                                        {{-- Supprimer --}}
                                        <button type="button" @click='openDelete(@json($application))' title="Supprimer"
                                            class="w-9 h-9 rounded-lg hover:bg-red-500/10 flex items-center justify-center text-muted-foreground hover:text-red-600 transition">
                                            <x-icon name="trash-2" class="w-4 h-4" />
                                        </button>

                                    </div>
                                </td>
                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">
                                    <div
                                        class="w-14 h-14 mx-auto rounded-full bg-secondary flex items-center justify-center mb-4">
                                        <x-icon name="users-round" class="w-6 h-6 text-muted-foreground" />
                                    </div>

                                    <h3 class="font-semibold text-foreground">
                                        Aucune candidature trouvée
                                    </h3>

                                    <p class="text-sm text-muted-foreground mt-1">
                                        Aucune candidature ne correspond à votre recherche.
                                    </p>
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>

            </div>

            {{-- Pagination --}}
            @if ($applications->hasPages())
                <div class="p-5 border-t border-border">
                    {{ $applications->links() }}
                </div>
            @endif

        </div>


        {{-- ============================================================ --}}
        {{-- MODAL : DÉTAILS --}}
        {{-- ============================================================ --}}
        <div x-show="showDetailsModal" x-cloak @keydown.escape.window="closeModals()"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
            {{-- Overlay --}}
            <div @click="closeModals()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            {{-- Contenu --}}
            <div @click.stop
                class="relative bg-card w-full max-w-3xl max-h-[90vh] overflow-y-auto rounded-2xl shadow-2xl">
                <div
                    class="sticky top-0 bg-card border-b border-border px-6 py-4 flex items-center justify-between z-10">
                    <div>
                        <h2 class="text-lg font-bold text-foreground">
                            Candidature bénévole
                        </h2>
                        <p class="text-sm text-muted-foreground" x-text="selectedApplication?.name"></p>
                    </div>

                    <button type="button" @click="closeModals()"
                        class="w-9 h-9 rounded-lg hover:bg-secondary flex items-center justify-center">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <div class="p-6 space-y-7" x-show="selectedApplication">

                    {{-- Informations personnelles --}}
                    <div>
                        <h3 class="font-semibold text-foreground mb-4">
                            Informations personnelles
                        </h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="p-4 rounded-xl bg-secondary/50">
                                <p class="text-xs text-muted-foreground">Email</p>
                                <p class="text-sm font-medium mt-1" x-text="selectedApplication?.email"></p>
                            </div>

                            <div class="p-4 rounded-xl bg-secondary/50">
                                <p class="text-xs text-muted-foreground">WhatsApp</p>
                                <p class="text-sm font-medium mt-1" x-text="selectedApplication?.phone"></p>
                            </div>

                            <div class="p-4 rounded-xl bg-secondary/50">
                                <p class="text-xs text-muted-foreground">Ville et pays</p>
                                <p class="text-sm font-medium mt-1" x-text="selectedApplication?.city_country"></p>
                            </div>

                            <div class="p-4 rounded-xl bg-secondary/50">
                                <p class="text-xs text-muted-foreground">Âge</p>
                                <p class="text-sm font-medium mt-1">
                                    <span x-text="selectedApplication?.age"></span> ans
                                </p>
                            </div>

                            <div class="p-4 rounded-xl bg-secondary/50 sm:col-span-2">
                                <p class="text-xs text-muted-foreground">Profession / Études</p>
                                <p class="text-sm font-medium mt-1" x-text="selectedApplication?.profession"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Contributions --}}
                    <div>
                        <h3 class="font-semibold text-foreground mb-3">
                            Domaines de contribution
                        </h3>

                        <div class="flex flex-wrap gap-2">
                            <template x-for="area in (selectedApplication?.contribution_areas || [])" :key="area">
                                <span class="px-3 py-1.5 rounded-lg bg-accent/10 text-accent text-sm"
                                    x-text="area"></span>
                            </template>
                        </div>

                        <template x-if="selectedApplication?.contribution_other">
                            <p class="text-sm text-muted-foreground mt-3">
                                <strong>Autre :</strong>
                                <span x-text="selectedApplication?.contribution_other"></span>
                            </p>
                        </template>
                    </div>

                    {{-- Motivation --}}
                    <div>
                        <h3 class="font-semibold text-foreground mb-3">
                            Motivation
                        </h3>

                        <div class="p-4 rounded-xl bg-secondary/50 text-sm leading-relaxed whitespace-pre-line">
                            <p x-text="selectedApplication?.motivation"></p>
                        </div>
                    </div>

                    {{-- Compétences --}}
                    <div>
                        <h3 class="font-semibold text-foreground mb-3">
                            Ce que la personne apporte à GP
                        </h3>

                        <div class="p-4 rounded-xl bg-secondary/50 text-sm leading-relaxed whitespace-pre-line">
                            <p x-text="selectedApplication?.skills"></p>
                        </div>
                    </div>

                    {{-- Informations supplémentaires --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl border border-border">
                            <p class="text-xs text-muted-foreground">Disponibilité</p>
                            <p class="text-sm font-medium mt-1" x-text="selectedApplication?.availability"></p>
                        </div>

                        <div class="p-4 rounded-xl border border-border">
                            <p class="text-xs text-muted-foreground">A participé à un événement GP ?</p>
                            <p class="text-sm font-medium mt-1"
                                x-text="selectedApplication?.participated_before ? 'Oui' : 'Non'"></p>
                        </div>
                    </div>

                    {{-- Réseau social --}}
                    <template x-if="selectedApplication?.social_link">
                        <div>
                            <a :href="selectedApplication?.social_link" target="_blank"
                                class="inline-flex items-center gap-2 text-accent hover:underline text-sm font-medium">
                                <x-icon name="external-link" class="w-4 h-4" />
                                Voir le profil LinkedIn / réseau social
                            </a>
                        </div>
                    </template>

                </div>
            </div>
        </div>


        {{-- ============================================================ --}}
        {{-- MODAL : MODIFIER LE STATUT --}}
        {{-- ============================================================ --}}
        <div x-show="showStatusModal" x-cloak @keydown.escape.window="closeModals()"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
            <div @click="closeModals()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div @click.stop class="relative bg-card w-full max-w-lg rounded-2xl shadow-2xl">

                <div class="p-6 border-b border-border flex items-center justify-between">
                    <div>
                        <h2 class="font-bold text-lg">Modifier le statut</h2>
                        <p class="text-sm text-muted-foreground mt-1" x-text="selectedApplication?.name"></p>
                    </div>

                    <button @click="closeModals()" class="p-2 rounded-lg hover:bg-secondary">
                        <x-icon name="x" class="w-5 h-5" />
                    </button>
                </div>

                <form method="POST" :action="selectedApplication ? '{{ url('/admin/engagements/volunteers') }}/' + selectedApplication.id +
                        '/status' : ''" class="p-6 space-y-5">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Nouveau statut
                        </label>

                        <select name="status" :value="selectedApplication?.status"
                            class="w-full px-4 py-2.5 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-accent">
                            <option value="pending">En attente</option>
                            <option value="reviewing">En examen</option>
                            <option value="accepted">Acceptée</option>
                            <option value="rejected">Refusée</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2">
                            Note administrative
                            <span class="text-muted-foreground font-normal">(optionnel)</span>
                        </label>

                        <textarea name="admin_notes" rows="4" x-text="selectedApplication?.admin_notes || ''"
                            class="w-full px-4 py-3 rounded-lg border border-border bg-background focus:outline-none focus:ring-2 focus:ring-accent"
                            placeholder="Ajouter une note interne..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" @click="closeModals()"
                            class="px-4 py-2.5 rounded-lg hover:bg-secondary font-medium">
                            Annuler
                        </button>

                        <button type="submit"
                            class="px-5 py-2.5 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90">
                            Enregistrer
                        </button>
                    </div>

                </form>
            </div>
        </div>


        {{-- ============================================================ --}}
        {{-- MODAL : SUPPRESSION --}}
        {{-- ============================================================ --}}
        <div x-show="showDeleteModal" x-cloak @keydown.escape.window="closeModals()"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
            <div @click="closeModals()" class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

            <div @click.stop class="relative bg-card w-full max-w-md rounded-2xl shadow-2xl p-6">

                <div class="w-12 h-12 rounded-full bg-red-500/10 flex items-center justify-center mb-4">
                    <x-icon name="trash-2" class="w-6 h-6 text-red-600" />
                </div>

                <h2 class="font-bold text-lg text-foreground">
                    Supprimer cette candidature ?
                </h2>

                <p class="text-sm text-muted-foreground mt-2 leading-relaxed">
                    Vous êtes sur le point de supprimer définitivement la candidature de
                    <strong class="text-foreground" x-text="selectedApplication?.name"></strong>.
                    Cette action est irréversible.
                </p>

                <form method="POST"
                    :action="selectedApplication ? '{{ url('/admin/engagements/volunteers') }}/' + selectedApplication.id : ''"
                    class="flex justify-end gap-3 mt-6">
                    @csrf
                    @method('DELETE')

                    <button type="button" @click="closeModals()"
                        class="px-4 py-2.5 rounded-lg hover:bg-secondary font-medium">
                        Annuler
                    </button>

                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700 transition">
                        Oui, supprimer
                    </button>
                </form>

            </div>
        </div>

    </div>

</x-layouts.admin>
