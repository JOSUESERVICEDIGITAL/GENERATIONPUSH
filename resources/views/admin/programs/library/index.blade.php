<x-layouts.admin title="Livres">
    <div x-data="{
            createOpen: false,
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($resources->pluck('id')),

            openEdit(book) {
                this.editing = book;
                this.editOpen = true;
            }
        }" class="space-y-6">

        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center">
                        <x-icon name="book-open" class="w-5 h-5 text-accent" />
                    </div>

                    <div>
                        <h1 class="text-3xl font-bold text-foreground">
                            Livres
                        </h1>

                        <p class="text-muted-foreground mt-1">
                            Gérez les ouvrages de Generation PUSH et le livre mis à la une.
                        </p>
                    </div>
                </div>
            </div>

            <button type="button" @click="
    editOpen = false;
    editing = {};
    createOpen = true;
" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-accent text-accent-foreground font-semibold hover:opacity-90 transition">
                <x-icon name="plus" class="w-4 h-4" />
                Ajouter un livre
            </button>
        </div>


        {{-- ========================================================= --}}
        {{-- MESSAGE SUCCESS --}}
        {{-- ========================================================= --}}

        @if (session('success'))
            <div
                class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- ERREURS --}}
        {{-- ========================================================= --}}

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900 rounded-xl px-5 py-4">
                <p class="font-semibold text-red-700 dark:text-red-400 mb-2">
                    Certains champs doivent être corrigés.
                </p>

                <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ========================================================= --}}
        {{-- STATISTIQUES --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-2 xl:grid-cols-5 gap-4">

            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">
                    Total
                </p>

                <p class="text-3xl font-bold text-foreground mt-2">
                    {{ $stats['total'] }}
                </p>

                <p class="text-xs text-muted-foreground mt-1">
                    Livres enregistrés
                </p>
            </div>

            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">
                    Publiés
                </p>

                <p class="text-3xl font-bold text-green-600 mt-2">
                    {{ $stats['published'] }}
                </p>

                <p class="text-xs text-muted-foreground mt-1">
                    Visibles au public
                </p>
            </div>

            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">
                    Brouillons
                </p>

                <p class="text-3xl font-bold text-foreground mt-2">
                    {{ $stats['draft'] }}
                </p>

                <p class="text-xs text-muted-foreground mt-1">
                    Non publiés
                </p>
            </div>

            <div class="bg-card border border-border rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-muted-foreground">
                    Best-sellers
                </p>

                <p class="text-3xl font-bold text-accent mt-2">
                    {{ $stats['bestsellers'] }}
                </p>

                <p class="text-xs text-muted-foreground mt-1">
                    Livres valorisés
                </p>
            </div>

            <div class="bg-gradient-to-br from-orange-500/10 to-orange-500/5 border border-accent/30 rounded-xl p-5">
                <p class="text-xs uppercase tracking-wide text-accent font-semibold">
                    Sur le plateau
                </p>

                <p class="text-3xl font-bold text-accent mt-2">
                    {{ $stats['podium'] }}
                </p>

                <p class="text-xs text-muted-foreground mt-1">
                    Livre principal
                </p>
            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- LISTE --}}
        {{-- ========================================================= --}}

        <div class="bg-card border border-border rounded-xl overflow-hidden">

            <div class="p-6 border-b border-border flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-foreground">
                        Bibliothèque
                    </h2>

                    <p class="text-sm text-muted-foreground mt-1">
                        Tous les livres enregistrés sur la plateforme.
                    </p>
                </div>


                {{-- FILTRES --}}

                <form method="GET" class="flex flex-wrap gap-2">

                    <div class="relative">
                        <x-icon name="search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />

                        <input type="text" name="search" value="{{ $search }}" placeholder="Titre, auteur, ISBN..."
                            class="w-64 pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground text-sm focus:ring-accent focus:border-accent">
                    </div>

                    <select name="status" onchange="this.form.submit()"
                        class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>

                        <option value="published" @selected($status === 'published')>
                            Publiés
                        </option>

                        <option value="draft" @selected($status === 'draft')>
                            Brouillons
                        </option>
                    </select>

                    <button type="submit"
                        class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition">
                        Filtrer
                    </button>

                    @if ($search || $status)
                        <a href="{{ route('admin.programs.library.index') }}"
                            class="px-3 py-2 text-sm text-muted-foreground hover:text-foreground">
                            Réinitialiser
                        </a>
                    @endif

                </form>
            </div>


            {{-- BULK ACTION --}}

            <div class="px-6 pt-5">

                <x-bulk-action-bar count="selected.length" label="livre(s)">
                    <button type="button" @click="
                            if (confirm(`Supprimer ${selected.length} livre(s) ?`)) {
                                $refs.bulkForm.submit()
                            }
                        "
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90">
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.programs.library.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')

                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

            </div>


            {{-- TABLEAU --}}

            <div class="p-6">

                <div class="border border-border rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">

                        <table class="w-full text-sm">

                            <thead class="bg-secondary border-b border-border">
                                <tr>

                                    <th class="px-4 py-3 w-10">
                                        <input type="checkbox" class="rounded border-border"
                                            @change="selected = $event.target.checked ? [...allIds] : []"
                                            :checked="selected.length === allIds.length && allIds.length > 0">
                                    </th>

                                    <th class="px-5 py-3 text-left">
                                        Livre
                                    </th>

                                    <th class="px-5 py-3 text-left">
                                        Format
                                    </th>

                                    <th class="px-5 py-3 text-left">
                                        Prix
                                    </th>

                                    <th class="px-5 py-3 text-left">
                                        Mise en avant
                                    </th>

                                    <th class="px-5 py-3 text-left">
                                        Statut
                                    </th>

                                    <th class="px-5 py-3 text-right">
                                        Actions
                                    </th>

                                </tr>
                            </thead>


                            <tbody>

                                @forelse ($resources as $resource)

                                                            <tr class="border-b border-border last:border-0 hover:bg-secondary/40 transition">

                                                                {{-- CHECKBOX --}}

                                                                <td class="px-4 py-4">
                                                                    <input type="checkbox" class="rounded border-border" value="{{ $resource->id }}"
                                                                        @change="
                                                                                $event.target.checked
                                                                                    ? selected.push({{ $resource->id }})
                                                                                    : selected = selected.filter(i => i !== {{ $resource->id }})
                                                                            " :checked="selected.includes({{ $resource->id }})">
                                                                </td>


                                                                {{-- LIVRE --}}

                                                                <td class="px-5 py-4">

                                                                    <div class="flex items-center gap-4">

                                                                        <div
                                                                            class="w-14 h-20 rounded-lg overflow-hidden bg-secondary border border-border shrink-0">

                                                                            @if ($resource->cover_image)

                                                                                <img src="{{ $resource->coverUrl() }}" alt="{{ $resource->title }}"
                                                                                    class="w-full h-full object-cover">

                                                                            @else

                                                                                <div class="w-full h-full flex items-center justify-center">
                                                                                    <x-icon name="book-open" class="w-6 h-6 text-muted-foreground" />
                                                                                </div>

                                                                            @endif

                                                                        </div>


                                                                        <div class="min-w-0">

                                                                            <p class="font-semibold text-foreground truncate max-w-xs">
                                                                                {{ $resource->title }}
                                                                            </p>

                                                                            @if ($resource->author)
                                                                                <p class="text-xs text-muted-foreground mt-1">
                                                                                    {{ $resource->author }}
                                                                                </p>
                                                                            @endif

                                                                            @if ($resource->isbn)
                                                                                <p class="text-xs text-muted-foreground mt-1">
                                                                                    ISBN : {{ $resource->isbn }}
                                                                                </p>
                                                                            @endif

                                                                        </div>

                                                                    </div>

                                                                </td>


                                                                {{-- FORMAT --}}

                                                                <td class="px-5 py-4">
                                                                    <span class="text-foreground">
                                                                        {{ $resource->formatLabel() }}
                                                                    </span>

                                                                    @if ($resource->format !== 'digital')
                                                                        <p class="text-xs text-muted-foreground mt-1">
                                                                            Stock :
                                                                            {{ $resource->stock ?? 'Illimité' }}
                                                                        </p>
                                                                    @endif
                                                                </td>


                                                                {{-- PRIX --}}

                                                                <td class="px-5 py-4">

                                                                    @if ($resource->price !== null)

                                                                        @if ($resource->hasPromotion())

                                                                            <div>
                                                                                <span class="font-bold text-accent">
                                                                                    {{ number_format((float) $resource->promotional_price, 0, ',', ' ') }}
                                                                                </span>

                                                                                <span class="text-xs text-muted-foreground">
                                                                                    FCFA
                                                                                </span>
                                                                            </div>

                                                                            <div class="text-xs text-muted-foreground line-through">
                                                                                {{ number_format((float) $resource->price, 0, ',', ' ') }}
                                                                                FCFA
                                                                            </div>

                                                                        @else

                                                                            <span class="font-semibold text-foreground">
                                                                                {{ number_format((float) $resource->price, 0, ',', ' ') }}
                                                                                FCFA
                                                                            </span>

                                                                        @endif

                                                                    @else

                                                                        <span class="text-muted-foreground">
                                                                            —
                                                                        </span>

                                                                    @endif

                                                                </td>


                                                                {{-- BADGES --}}

                                                                <td class="px-5 py-4">

                                                                    <div class="flex flex-wrap gap-2">

                                                                        @if ($resource->show_on_podium)
                                                                            <span
                                                                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 dark:bg-orange-950/40 dark:text-orange-400">
                                                                                ★ Plateau
                                                                            </span>
                                                                        @endif

                                                                        @if ($resource->is_bestseller)
                                                                            <span
                                                                                class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-950/40 dark:text-yellow-400">
                                                                                Best-seller
                                                                            </span>
                                                                        @endif

                                                                        @if (!$resource->show_on_podium && !$resource->is_bestseller)
                                                                            <span class="text-muted-foreground">
                                                                                —
                                                                            </span>
                                                                        @endif

                                                                    </div>

                                                                </td>


                                                                {{-- STATUT --}}

                                                                <td class="px-5 py-4">

                                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                                                            {{
                                    $resource->status === 'published'
                                    ? 'bg-green-50 text-green-700 dark:bg-green-950/30 dark:text-green-400'
                                    : 'bg-gray-100 text-gray-700 dark:bg-gray-800/50 dark:text-gray-400'
                                                                            }}">
                                                                        {{ $resource->statusLabel() }}
                                                                    </span>

                                                                </td>


                                                                {{-- ACTIONS --}}

                                                                <td class="px-5 py-4">

                                                                    <div class="flex items-center justify-end gap-2">

                                                                        <button type="button" @click="openEdit({
                                                                                    id: @js($resource->id),
                                                                                    title: @js($resource->title),
                                                                                    author: @js($resource->author),
                                                                                    subtitle: @js($resource->subtitle),
                                                                                    description: @js($resource->description),
                                                                                    isbn: @js($resource->isbn),
                                                                                    publisher: @js($resource->publisher),
                                                                                    publication_date: @js(optional($resource->publication_date)->format('Y-m-d')),
                                                                                    pages: @js($resource->pages),
                                                                                    price: @js($resource->price),
                                                                                    promotional_price: @js($resource->promotional_price),
                                                                                    format: @js($resource->format),
                                                                                    stock: @js($resource->stock),
                                                                                    value_description: @js($resource->value_description),
                                                                                    discover_content: @js($resource->discover_content),
                                                                                    is_bestseller: @js($resource->is_bestseller),
                                                                                    show_on_podium: @js($resource->show_on_podium),
                                                                                    display_order: @js($resource->display_order),
                                                                                    status: @js($resource->status),
                                                                                    cover_url: @js($resource->coverUrl()),
                                                                                    showcase_url: @js($resource->showcaseUrl())
                                                                                })" class="p-2 rounded-lg hover:bg-secondary transition"
                                                                            title="Modifier">
                                                                            <x-icon name="pencil" class="w-4 h-4" />
                                                                        </button>


                                                                        <form method="POST"
                                                                            action="{{ route('admin.programs.library.destroy', $resource) }}"
                                                                            onsubmit="return confirm('Supprimer définitivement ce livre ?');">
                                                                            @csrf
                                                                            @method('DELETE')

                                                                            <button type="submit"
                                                                                class="p-2 rounded-lg hover:bg-destructive/10 text-muted-foreground hover:text-destructive transition"
                                                                                title="Supprimer">
                                                                                <x-icon name="trash" class="w-4 h-4" />
                                                                            </button>

                                                                        </form>

                                                                    </div>

                                                                </td>

                                                            </tr>

                                @empty

                                    <tr>
                                        <td colspan="7" class="px-6 py-16 text-center">

                                            <div
                                                class="w-14 h-14 mx-auto rounded-full bg-secondary flex items-center justify-center mb-4">
                                                <x-icon name="book-open" class="w-6 h-6 text-muted-foreground" />
                                            </div>

                                            <p class="font-semibold text-foreground">
                                                Aucun livre
                                            </p>

                                            <p class="text-sm text-muted-foreground mt-1">
                                                Commencez par ajouter le premier livre de Generation PUSH.
                                            </p>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>
                </div>


                <div class="mt-5">
                    {{ $resources->links() }}
                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- MODAL : AJOUTER --}}
        {{-- ========================================================= --}}

        <x-modal open="createOpen" title="Ajouter un livre">

            <form method="POST" action="{{ route('admin.programs.library.store') }}" enctype="multipart/form-data"
                class="space-y-6">
                @csrf


                {{-- IDENTITÉ --}}

                <div>
                    <h3 class="font-semibold text-foreground mb-4">
                        Informations principales
                    </h3>

                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="create_title" value="Titre du livre *" />

                            <x-text-input id="create_title" name="title" type="text" value="{{ old('title') }}"
                                class="mt-1 block w-full" required />
                        </div>

                        <div>
                            <x-input-label for="create_author" value="Auteur" />

                            <x-text-input id="create_author" name="author" type="text" value="{{ old('author') }}"
                                class="mt-1 block w-full" placeholder="Ex. Débora Hermine N. Tapsoba" />
                        </div>

                    </div>


                    <div class="mt-4">
                        <x-input-label for="create_subtitle" value="Sous-titre / accroche" />

                        <x-text-input id="create_subtitle" name="subtitle" type="text" value="{{ old('subtitle') }}"
                            class="mt-1 block w-full" />
                    </div>


                    <div class="mt-4">
                        <x-input-label for="create_description" value="Description du livre" />

                        <textarea id="create_description" name="description" rows="4"
                            class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('description') }}</textarea>
                    </div>

                </div>


                {{-- IMAGES --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-1">
                        Visuels
                    </h3>

                    <p class="text-sm text-muted-foreground mb-4">
                        Ajoutez la couverture et, si disponible, un visuel promotionnel spécial.
                    </p>


                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="create_cover_image" value="Couverture du livre" />

                            <input id="create_cover_image" name="cover_image" type="file" accept=".jpg,.jpeg,.png,.webp"
                                class="mt-2 block w-full text-sm text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground">

                            <p class="text-xs text-muted-foreground mt-2">
                                JPG, PNG ou WEBP — maximum 5 Mo.
                            </p>
                        </div>


                        <div>
                            <x-input-label for="create_showcase_image" value="Visuel promotionnel du plateau" />

                            <input id="create_showcase_image" name="showcase_image" type="file"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="mt-2 block w-full text-sm text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground">

                            <p class="text-xs text-muted-foreground mt-2">
                                Optionnel — maximum 10 Mo.
                            </p>
                        </div>

                    </div>

                </div>


                {{-- ÉDITION --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Informations éditoriales
                    </h3>


                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="create_isbn" value="ISBN" />

                            <x-text-input id="create_isbn" name="isbn" type="text" value="{{ old('isbn') }}"
                                class="mt-1 block w-full" />
                        </div>


                        <div>
                            <x-input-label for="create_publisher" value="Maison d'édition" />

                            <x-text-input id="create_publisher" name="publisher" type="text"
                                value="{{ old('publisher') }}" class="mt-1 block w-full" />
                        </div>


                        <div>
                            <x-input-label for="create_publication_date" value="Date de publication" />

                            <x-text-input id="create_publication_date" name="publication_date" type="date"
                                value="{{ old('publication_date') }}" class="mt-1 block w-full" />
                        </div>


                        <div>
                            <x-input-label for="create_pages" value="Nombre de pages" />

                            <x-text-input id="create_pages" name="pages" type="number" min="1"
                                value="{{ old('pages') }}" class="mt-1 block w-full" />
                        </div>

                    </div>

                </div>


                {{-- PRIX --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Prix et disponibilité
                    </h3>


                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div>
                            <x-input-label for="create_price" value="Prix (FCFA)" />

                            <x-text-input id="create_price" name="price" type="number" min="0" step="1"
                                value="{{ old('price') }}" class="mt-1 block w-full" />
                        </div>


                        <div>
                            <x-input-label for="create_promotional_price" value="Prix promotionnel" />

                            <x-text-input id="create_promotional_price" name="promotional_price" type="number" min="0"
                                step="1" value="{{ old('promotional_price') }}" class="mt-1 block w-full" />
                        </div>


                        <div>
                            <x-input-label for="create_format" value="Format *" />

                            <select id="create_format" name="format"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"
                                required>
                                <option value="physical" @selected(old('format') === 'physical')>
                                    Physique
                                </option>

                                <option value="digital" @selected(old('format') === 'digital')>
                                    Numérique
                                </option>

                                <option value="both" @selected(old('format') === 'both')>
                                    Physique + numérique
                                </option>
                            </select>
                        </div>


                        <div>
                            <x-input-label for="create_stock" value="Stock physique" />

                            <x-text-input id="create_stock" name="stock" type="number" min="0"
                                value="{{ old('stock') }}" class="mt-1 block w-full" />
                        </div>

                    </div>

                </div>


                {{-- VALORISATION --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Valorisation du livre
                    </h3>


                    <div class="space-y-4">

                        <div>
                            <x-input-label for="create_value_description" value="Pourquoi prendre ce livre ?" />

                            <textarea id="create_value_description" name="value_description" rows="4"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"
                                placeholder="Présentez la valeur et l'impact du livre...">{{ old('value_description') }}</textarea>
                        </div>


                        <div>
                            <x-input-label for="create_discover_content" value="Ce que le lecteur va découvrir" />

                            <textarea id="create_discover_content" name="discover_content" rows="5"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"
                                placeholder="Leadership, vision, discipline, impact...">{{ old('discover_content') }}</textarea>
                        </div>

                    </div>

                </div>


                {{-- PUBLICATION --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Publication et mise en avant
                    </h3>


                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="create_status" value="Statut *" />

                            <select id="create_status" name="status"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"
                                required>
                                <option value="draft" @selected(old('status') === 'draft')>
                                    Brouillon
                                </option>

                                <option value="published" @selected(old('status', 'published') === 'published')>
                                    Publié
                                </option>
                            </select>
                        </div>


                        <div>
                            <x-input-label for="create_display_order" value="Ordre d'affichage" />

                            <x-text-input id="create_display_order" name="display_order" type="number" min="0"
                                value="{{ old('display_order', 0) }}" class="mt-1 block w-full" />
                        </div>

                    </div>


                    <div class="grid md:grid-cols-2 gap-4 mt-5">

                        <label
                            class="flex items-start gap-3 p-4 border border-border rounded-xl cursor-pointer hover:bg-secondary/50 transition">

                            <input type="checkbox" name="is_bestseller" value="1" @checked(old('is_bestseller'))
                                class="mt-1 rounded border-border text-accent focus:ring-accent">

                            <span>
                                <span class="block font-semibold text-foreground">
                                    Best-seller
                                </span>

                                <span class="block text-xs text-muted-foreground mt-1">
                                    Afficher le badge Best-seller sur le livre.
                                </span>
                            </span>

                        </label>


                        <label
                            class="flex items-start gap-3 p-4 border border-accent/30 bg-accent/5 rounded-xl cursor-pointer">

                            <input type="checkbox" name="show_on_podium" value="1" @checked(old('show_on_podium'))
                                class="mt-1 rounded border-border text-accent focus:ring-accent">

                            <span>
                                <span class="block font-semibold text-accent">
                                    Afficher sur le plateau
                                </span>

                                <span class="block text-xs text-muted-foreground mt-1">
                                    Ce livre deviendra le livre principal de la page publique.
                                </span>
                            </span>

                        </label>

                    </div>

                </div>


                {{-- ACTIONS --}}

                <div class="flex justify-end gap-3 pt-4 border-t border-border">

                    <button type="button" @click="createOpen = false"
                        class="px-5 py-2.5 rounded-lg border border-border text-foreground hover:bg-secondary transition">
                        Annuler
                    </button>

                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition">
                        Enregistrer le livre
                    </button>

                </div>

            </form>

        </x-modal>


        {{-- ========================================================= --}}
        {{-- MODAL : MODIFIER --}}
        {{-- ========================================================= --}}

        <x-modal open="editOpen" title="Modifier le livre">

            <form method="POST" :action="`{{ url('admin/programs/library') }}/${editing.id}`"
                enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')


                {{-- IDENTITÉ --}}

                <div>

                    <h3 class="font-semibold text-foreground mb-4">
                        Informations principales
                    </h3>


                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="edit_title" value="Titre du livre *" />

                            <input id="edit_title" name="title" type="text" x-model="editing.title" required
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>


                        <div>
                            <x-input-label for="edit_author" value="Auteur" />

                            <input id="edit_author" name="author" type="text" x-model="editing.author"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>

                    </div>


                    <div class="mt-4">
                        <x-input-label for="edit_subtitle" value="Sous-titre / accroche" />

                        <input id="edit_subtitle" name="subtitle" type="text" x-model="editing.subtitle"
                            class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>


                    <div class="mt-4">
                        <x-input-label for="edit_description" value="Description" />

                        <textarea id="edit_description" name="description" rows="4" x-model="editing.description"
                            class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                    </div>

                </div>


                {{-- VISUELS --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Visuels
                    </h3>


                    <div class="grid md:grid-cols-2 gap-5">

                        <div>

                            <template x-if="editing.cover_url">
                                <img :src="editing.cover_url"
                                    class="w-24 h-36 object-cover rounded-lg border border-border mb-3">
                            </template>

                            <x-input-label for="edit_cover_image" value="Changer la couverture" />

                            <input id="edit_cover_image" name="cover_image" type="file" accept=".jpg,.jpeg,.png,.webp"
                                class="mt-2 block w-full text-sm">

                        </div>


                        <div>

                            <template x-if="editing.showcase_url">
                                <img :src="editing.showcase_url"
                                    class="w-full max-w-[220px] h-36 object-cover rounded-lg border border-border mb-3">
                            </template>

                            <x-input-label for="edit_showcase_image" value="Changer le visuel du plateau" />

                            <input id="edit_showcase_image" name="showcase_image" type="file"
                                accept=".jpg,.jpeg,.png,.webp" class="mt-2 block w-full text-sm">

                        </div>

                    </div>

                </div>


                {{-- ÉDITORIAL --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Informations éditoriales
                    </h3>


                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="edit_isbn" value="ISBN" />

                            <input id="edit_isbn" name="isbn" type="text" x-model="editing.isbn"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>


                        <div>
                            <x-input-label for="edit_publisher" value="Maison d'édition" />

                            <input id="edit_publisher" name="publisher" type="text" x-model="editing.publisher"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>


                        <div>
                            <x-input-label for="edit_publication_date" value="Date de publication" />

                            <input id="edit_publication_date" name="publication_date" type="date"
                                x-model="editing.publication_date"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>


                        <div>
                            <x-input-label for="edit_pages" value="Nombre de pages" />

                            <input id="edit_pages" name="pages" type="number" min="1" x-model="editing.pages"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>

                    </div>

                </div>


                {{-- PRIX --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Prix et disponibilité
                    </h3>


                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-4">

                        <div>
                            <x-input-label for="edit_price" value="Prix (FCFA)" />

                            <input id="edit_price" name="price" type="number" min="0" step="1" x-model="editing.price"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>


                        <div>
                            <x-input-label for="edit_promotional_price" value="Prix promotionnel" />

                            <input id="edit_promotional_price" name="promotional_price" type="number" min="0" step="1"
                                x-model="editing.promotional_price"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>


                        <div>
                            <x-input-label for="edit_format" value="Format *" />

                            <select id="edit_format" name="format" x-model="editing.format"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                                <option value="physical">
                                    Physique
                                </option>

                                <option value="digital">
                                    Numérique
                                </option>

                                <option value="both">
                                    Physique + numérique
                                </option>
                            </select>
                        </div>


                        <div>
                            <x-input-label for="edit_stock" value="Stock" />

                            <input id="edit_stock" name="stock" type="number" min="0" x-model="editing.stock"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>

                    </div>

                </div>


                {{-- VALORISATION --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Valorisation du livre
                    </h3>


                    <div class="space-y-4">

                        <div>
                            <x-input-label for="edit_value_description" value="Pourquoi prendre ce livre ?" />

                            <textarea id="edit_value_description" name="value_description" rows="4"
                                x-model="editing.value_description"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                        </div>


                        <div>
                            <x-input-label for="edit_discover_content" value="Ce que le lecteur va découvrir" />

                            <textarea id="edit_discover_content" name="discover_content" rows="5"
                                x-model="editing.discover_content"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                        </div>

                    </div>

                </div>


                {{-- PUBLICATION --}}

                <div class="border-t border-border pt-6">

                    <h3 class="font-semibold text-foreground mb-4">
                        Publication et mise en avant
                    </h3>


                    <div class="grid md:grid-cols-2 gap-4">

                        <div>
                            <x-input-label for="edit_status" value="Statut" />

                            <select id="edit_status" name="status" x-model="editing.status"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                                <option value="draft">
                                    Brouillon
                                </option>

                                <option value="published">
                                    Publié
                                </option>
                            </select>
                        </div>


                        <div>
                            <x-input-label for="edit_display_order" value="Ordre d'affichage" />

                            <input id="edit_display_order" name="display_order" type="number" min="0"
                                x-model="editing.display_order"
                                class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        </div>

                    </div>


                    <div class="grid md:grid-cols-2 gap-4 mt-5">

                        <label class="flex items-start gap-3 p-4 border border-border rounded-xl cursor-pointer">

                            <input type="checkbox" name="is_bestseller" value="1" x-model="editing.is_bestseller"
                                class="mt-1 rounded border-border text-accent focus:ring-accent">

                            <span>
                                <span class="block font-semibold text-foreground">
                                    Best-seller
                                </span>

                                <span class="text-xs text-muted-foreground">
                                    Mettre ce livre en valeur.
                                </span>
                            </span>

                        </label>


                        <label
                            class="flex items-start gap-3 p-4 border border-accent/30 bg-accent/5 rounded-xl cursor-pointer">

                            <input type="checkbox" name="show_on_podium" value="1" x-model="editing.show_on_podium"
                                class="mt-1 rounded border-border text-accent focus:ring-accent">

                            <span>
                                <span class="block font-semibold text-accent">
                                    Afficher sur le plateau
                                </span>

                                <span class="text-xs text-muted-foreground">
                                    Il remplacera automatiquement l'ancien livre principal.
                                </span>
                            </span>

                        </label>

                    </div>

                </div>


                {{-- ACTIONS --}}

                <div class="flex justify-end gap-3 pt-4 border-t border-border">

                    <button type="button" @click="editOpen = false"
                        class="px-5 py-2.5 rounded-lg border border-border text-foreground hover:bg-secondary transition">
                        Annuler
                    </button>

                    <button type="submit"
                        class="px-5 py-2.5 rounded-lg bg-accent text-accent-foreground font-semibold hover:opacity-90 transition">
                        Enregistrer les modifications
                    </button>

                </div>

            </form>

        </x-modal>
    </div>
</x-layouts.admin>
