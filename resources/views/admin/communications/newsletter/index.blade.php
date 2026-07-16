<x-layouts.admin title="Newsletter">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($campaigns->pluck('id')),
            openEdit(campaign) {
                this.editing = campaign;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Newsletter</h1>
                <p class="text-muted-foreground mt-2">Crée et envoie des campagnes email à ta communauté</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="mail" class="w-4 h-4" />
                Créer une campagne
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-900 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-lg text-sm">
            ℹ️ "Envoyer" marque la campagne comme envoyée et calcule les destinataires réels, mais n'expédie pas encore d'email tant qu'un service SMTP n'est pas configuré (voir <code>.env</code>).
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total campagnes</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Envoyées</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['sent'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Brouillons</p>
                <p class="text-2xl font-bold text-gray-500">{{ $stats['draft'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Destinataires touchés</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['recipients'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Campagnes</h2>
            </div>

            <div class="p-6 space-y-4">
                <!-- Filtres -->
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="relative flex-1 min-w-[220px] max-w-sm">
                        <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Rechercher un objet..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                        <option value="scheduled" @selected($status === 'scheduled')>Programmée</option>
                        <option value="sent" @selected($status === 'sent')>Envoyée</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.communications.newsletter.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="campagne(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} campagne(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.communications.newsletter.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Tableau -->
                <div class="border border-border rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-secondary border-b border-border">
                                <tr>
                                    <th class="px-4 py-3 w-10">
                                        <input
                                            type="checkbox"
                                            class="rounded border-border"
                                            @change="selected = $event.target.checked ? [...allIds] : []"
                                            :checked="selected.length === allIds.length && allIds.length > 0"
                                        >
                                    </th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Objet</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Audience</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Destinataires</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($campaigns as $campaign)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $campaign->id }}"
                                                @change="$event.target.checked ? selected.push({{ $campaign->id }}) : selected = selected.filter(i => i !== {{ $campaign->id }})"
                                                :checked="selected.includes({{ $campaign->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4 font-medium text-foreground max-w-xs truncate">{{ $campaign->subject }}</td>
                                        <td class="px-6 py-4">{{ $campaign->audienceLabel() }}</td>
                                        <td class="px-6 py-4">{{ $campaign->recipients_count }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ match($campaign->status) { 'sent' => 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400', 'scheduled' => 'bg-blue-50 text-blue-700 dark:bg-blue-950/30 dark:text-blue-400', default => 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' } }}">
                                                {{ $campaign->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-muted-foreground">
                                            {{ $campaign->sent_at?->format('d/m/Y H:i') ?? $campaign->scheduled_at?->format('d/m/Y H:i') ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                @if ($campaign->status !== 'sent')
                                                    <form method="POST" action="{{ route('admin.communications.newsletter.send', $campaign) }}" onsubmit="return confirm('Envoyer cette campagne à {{ $campaign->audienceLabel() }} ?');">
                                                        @csrf
                                                        <button type="submit" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200" title="Envoyer">
                                                            <x-icon name="send" class="w-4 h-4 text-accent" />
                                                        </button>
                                                    </form>
                                                @endif
                                                <button
                                                    type="button"
                                                    @click="openEdit({ id: {{ $campaign->id }}, subject: @js($campaign->subject), content: @js($campaign->content), audience: @js($campaign->audience), status: @js($campaign->status), scheduled_at: @js($campaign->scheduled_at?->format('Y-m-d\TH:i')) })"
                                                    class="p-2 hover:bg-secondary rounded-lg transition-all duration-200"
                                                >
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </button>
                                                <form method="POST" action="{{ route('admin.communications.newsletter.destroy', $campaign) }}" onsubmit="return confirm('Supprimer cette campagne ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                        <x-icon name="trash" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-8 text-center text-muted-foreground">Aucune campagne trouvée</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $campaigns->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Créer une campagne newsletter">
            <form method="POST" action="{{ route('admin.communications.newsletter.store') }}" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_subject" value="Objet" />
                    <x-text-input id="create_subject" name="subject" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_content" value="Contenu" />
                    <textarea id="create_content" name="content" rows="5" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="create_audience" value="Audience" />
                        <select id="create_audience" name="audience" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="all">Tous les utilisateurs</option>
                            <option value="members">Membres</option>
                            <option value="leaders">Leaders</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="scheduled">Programmée</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="create_scheduled_at" value="Date de programmation (optionnel)" />
                        <x-text-input id="create_scheduled_at" name="scheduled_at" type="datetime-local" class="mt-1 block w-full" />
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                        Créer
                    </button>
                    <button type="button" @click="createOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                        Annuler
                    </button>
                </div>
            </form>
        </x-modal>

        <!-- Modal Édition -->
        <x-modal open="editOpen" title="Modifier la campagne">
            <form method="POST" :action="`{{ url('admin/communications/newsletter') }}/${editing.id}`" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <x-input-label for="edit_subject" value="Objet" />
                    <input id="edit_subject" name="subject" type="text" x-model="editing.subject" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_content" value="Contenu" />
                    <textarea id="edit_content" name="content" rows="5" x-model="editing.content" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="edit_audience" value="Audience" />
                        <select id="edit_audience" name="audience" x-model="editing.audience" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="all">Tous les utilisateurs</option>
                            <option value="members">Membres</option>
                            <option value="leaders">Leaders</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="scheduled">Programmée</option>
                            <option value="sent">Envoyée</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="edit_scheduled_at" value="Date de programmation" />
                        <input id="edit_scheduled_at" name="scheduled_at" type="datetime-local" x-model="editing.scheduled_at" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                </div>
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                        Enregistrer
                    </button>
                    <button type="button" @click="editOpen = false" class="px-4 py-2 rounded-lg border border-border text-foreground hover:bg-secondary transition-all duration-200">
                        Annuler
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</x-layouts.admin>
