<x-layouts.admin title="Blog">
    <div x-data="{ selected: [], allIds: @js($posts->pluck('id')) }" class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Blog</h1>
                <p class="text-muted-foreground mt-2">Gère les articles publiés sur le site</p>
            </div>
            <a
                href="{{ route('admin.content.blog.create') }}"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="plus" class="w-4 h-4" />
                Nouvel article
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Publiés</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Brouillons</p>
                <p class="text-2xl font-bold text-gray-500">{{ $stats['draft'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Vues cumulées</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['views'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Articles</h2>
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
                            placeholder="Rechercher un article..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="category_id" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Toutes les catégories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" @selected((string) $categoryId === (string) $cat->id)>{{ $cat->name }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="published" @selected($status === 'published')>Publié</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $status || $categoryId)
                        <a href="{{ route('admin.content.blog.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <x-bulk-action-bar count="selected.length" label="article(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} article(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.content.blog.bulk-destroy') }}">
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
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Article</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Catégorie</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Vues</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Engagement</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Statut</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Date</th>
                                    <th class="px-6 py-3 text-left font-semibold text-foreground">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($posts as $post)
                                    <tr class="border-b border-border last:border-0 hover:bg-secondary/50 transition-colors duration-200">
                                        <td class="px-4 py-4">
                                            <input
                                                type="checkbox"
                                                class="rounded border-border"
                                                value="{{ $post->id }}"
                                                @change="$event.target.checked ? selected.push({{ $post->id }}) : selected = selected.filter(i => i !== {{ $post->id }})"
                                                :checked="selected.includes({{ $post->id }})"
                                            >
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-lg bg-secondary overflow-hidden shrink-0 flex items-center justify-center">
                                                    @if ($post->coverImageUrl())
                                                        <img src="{{ $post->coverImageUrl() }}" class="w-full h-full object-cover">
                                                    @else
                                                        <x-icon name="file-text" class="w-4 h-4 text-muted-foreground" />
                                                    @endif
                                                </div>
                                                <span class="font-medium text-foreground max-w-xs truncate">{{ $post->title }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($post->category)
                                                <span class="px-2 py-1 rounded-md text-xs font-semibold" style="background-color: {{ $post->category->color }}20; color: {{ $post->category->color }}">{{ $post->category->name }}</span>
                                            @else
                                                <span class="text-muted-foreground">—</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $post->views }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3 text-xs text-muted-foreground">
                                                <span class="flex items-center gap-1"><svg class="w-3.5 h-3.5 text-red-500" viewBox="0 0 24 24" fill="currentColor"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg> {{ $post->likes_count }}</span>
                                                <span class="flex items-center gap-1"><x-icon name="star" class="w-3.5 h-3.5 text-yellow-500" style="fill: currentColor" /> {{ $post->averageRating() ?: '—' }}</span>
                                                <span class="flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $post->averageReadLabel() }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $post->status === 'published' ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400' : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400' }}">
                                                {{ $post->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-xs text-muted-foreground">{{ $post->published_at?->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('admin.content.blog.edit', $post) }}" class="p-2 hover:bg-secondary rounded-lg transition-all duration-200">
                                                    <x-icon name="pencil" class="w-4 h-4 text-muted-foreground hover:text-foreground" />
                                                </a>
                                                <form method="POST" action="{{ route('admin.content.blog.destroy', $post) }}" onsubmit="return confirm('Supprimer cet article ?');">
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
                                        <td colspan="8" class="px-6 py-8 text-center text-muted-foreground">Aucun article trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    {{ $posts->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
