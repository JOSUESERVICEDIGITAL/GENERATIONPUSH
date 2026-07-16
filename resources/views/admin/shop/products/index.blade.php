<x-layouts.admin title="Produits">
    <div
        x-data="{
            createOpen: {{ $errors->any() ? 'true' : 'false' }},
            editOpen: false,
            editing: {},
            selected: [],
            allIds: @js($products->pluck('id')),
            createFree: false,
            editFree: false,
            openEdit(product) {
                this.editing = product;
                this.editFree = product.is_free;
                this.editOpen = true;
            },
        }"
        class="space-y-6"
    >
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-foreground">Produits</h1>
                <p class="text-muted-foreground mt-2">Livres, masterclass vidéo et clés USB à télécharger ou acheter</p>
            </div>
            <button
                @click="createOpen = true"
                class="flex items-center gap-2 px-4 py-2 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200"
            >
                <x-icon name="plus" class="w-4 h-4" />
                Ajouter un produit
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Total produits</p>
                <p class="text-2xl font-bold text-foreground">{{ $stats['total'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Publiés</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['published'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Gratuits</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['free'] }}</p>
            </div>
            <div class="bg-card border border-border rounded-lg p-4">
                <p class="text-xs text-muted-foreground mb-2">Ventes cumulées</p>
                <p class="text-2xl font-bold text-accent">{{ $stats['sales'] }}</p>
            </div>
        </div>

        <!-- Card -->
        <div class="bg-card border border-border rounded-xl overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-semibold text-foreground">Liste des produits</h2>
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
                            placeholder="Rechercher un produit..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200"
                        >
                    </div>

                    <select name="type" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les types</option>
                        <option value="book" @selected($type === 'book')>Livre</option>
                        <option value="video" @selected($type === 'video')>Masterclass vidéo</option>
                        <option value="usb_key" @selected($type === 'usb_key')>Clé USB</option>
                    </select>

                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-border bg-background text-foreground text-sm focus:border-accent focus:ring-accent">
                        <option value="">Tous les statuts</option>
                        <option value="published" @selected($status === 'published')>Publié</option>
                        <option value="draft" @selected($status === 'draft')>Brouillon</option>
                    </select>

                    <button type="submit" class="px-4 py-2 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200">
                        Filtrer
                    </button>

                    @if ($search || $type || $status)
                        <a href="{{ route('admin.shop.products.index') }}" class="text-sm text-muted-foreground hover:text-foreground">Réinitialiser</a>
                    @endif
                </form>

                <!-- Barre d'actions groupées -->
                <x-bulk-action-bar count="selected.length" label="produit(s)">
                    <button
                        type="button"
                        @click="if (confirm(`Supprimer ${selected.length} produit(s) ?`)) $refs.bulkForm.submit()"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-destructive text-white text-sm font-medium hover:opacity-90 transition-all duration-200"
                    >
                        <x-icon name="trash" class="w-4 h-4" />
                        Supprimer la sélection
                    </button>
                </x-bulk-action-bar>

                <form x-ref="bulkForm" method="POST" action="{{ route('admin.shop.products.bulk-destroy') }}">
                    @csrf
                    @method('DELETE')
                    <template x-for="id in selected" :key="id">
                        <input type="hidden" name="ids[]" :value="id">
                    </template>
                </form>

                <!-- Grille produits -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse ($products as $product)
                        <div class="border border-border rounded-lg overflow-hidden bg-background">
                            <div class="aspect-video bg-secondary relative">
                                @if ($product->imageUrl())
                                    <img src="{{ $product->imageUrl() }}" alt="{{ $product->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <x-icon :name="match($product->type) { 'video' => 'video', 'usb_key' => 'hard-drive', default => 'book' }" class="w-10 h-10 text-muted-foreground" />
                                    </div>
                                @endif
                                <label class="absolute top-2 left-2">
                                    <input
                                        type="checkbox"
                                        class="rounded border-border"
                                        value="{{ $product->id }}"
                                        @change="$event.target.checked ? selected.push({{ $product->id }}) : selected = selected.filter(i => i !== {{ $product->id }})"
                                        :checked="selected.includes({{ $product->id }})"
                                    >
                                </label>
                                <span class="absolute top-2 right-2 px-2 py-1 rounded-md text-xs font-semibold {{ $product->status === 'published' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                                    {{ $product->statusLabel() }}
                                </span>
                            </div>
                            <div class="p-4 space-y-2">
                                <div class="flex items-center gap-2 text-xs text-muted-foreground">
                                    <x-icon :name="match($product->type) { 'video' => 'video', 'usb_key' => 'hard-drive', default => 'book' }" class="w-3.5 h-3.5" />
                                    <span>{{ $product->typeLabel() }}</span>
                                </div>
                                <h3 class="font-semibold text-foreground line-clamp-1">{{ $product->title }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold {{ $product->is_free ? 'text-blue-600' : 'text-accent' }}">{{ $product->priceLabel() }}</span>
                                    @if ($product->type === 'usb_key')
                                        <span class="text-xs text-muted-foreground">Stock: {{ $product->stock ?? 0 }}</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 pt-2">
                                    <button
                                        type="button"
                                        @click="openEdit({ id: {{ $product->id }}, title: @js($product->title), description: @js($product->description), type: @js($product->type), is_free: {{ $product->is_free ? 'true' : 'false' }}, price: {{ $product->price }}, video_url: @js($product->video_url), stock: {{ $product->stock ?? 0 }}, status: @js($product->status), image_url: @js($product->imageUrl()) })"
                                        class="flex-1 px-3 py-1.5 rounded-lg border border-border text-sm text-foreground hover:bg-secondary transition-all duration-200 text-center"
                                    >
                                        Modifier
                                    </button>
                                    <form method="POST" action="{{ route('admin.shop.products.destroy', $product) }}" onsubmit="return confirm('Supprimer ce produit ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg border border-border hover:bg-secondary transition-all duration-200">
                                            <x-icon name="trash" class="w-4 h-4 text-muted-foreground hover:text-destructive" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-muted-foreground py-8">Aucun produit trouvé</div>
                    @endforelse
                </div>

                <div>
                    {{ $products->links() }}
                </div>
            </div>
        </div>

        <!-- Modal Création -->
        <x-modal open="createOpen" title="Ajouter un produit">
            <form method="POST" action="{{ route('admin.shop.products.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <x-input-label for="create_title" value="Titre" />
                    <x-text-input id="create_title" name="title" type="text" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label for="create_description" value="Description" />
                    <textarea id="create_description" name="description" rows="3" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div>
                    <x-input-label for="create_type" value="Type de produit" />
                    <select id="create_type" name="type" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="book">Livre</option>
                        <option value="video">Masterclass vidéo</option>
                        <option value="usb_key">Clé USB</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="create_image" value="Image du produit" />
                    <input id="create_image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium" required>
                    <p class="text-xs text-muted-foreground mt-1">JPG/PNG, 4 Mo max.</p>
                </div>
                <div>
                    <x-input-label for="create_file" value="Fichier à télécharger (PDF, vidéo...) — optionnel" />
                    <input id="create_file" name="file" type="file" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium">
                    <p class="text-xs text-muted-foreground mt-1">Livre (PDF) ou vidéo. 50 Mo max. Laisse vide pour une clé USB physique.</p>
                </div>
                <div>
                    <x-input-label for="create_video_url" value="Ou lien vidéo externe (YouTube, Vimeo...)" />
                    <x-text-input id="create_video_url" name="video_url" type="url" class="mt-1 block w-full" placeholder="https://..." />
                </div>
                <div class="flex items-center gap-2">
                    <input id="create_is_free" name="is_free" type="checkbox" value="1" x-model="createFree" class="rounded border-border">
                    <x-input-label for="create_is_free" value="Produit gratuit" class="!mb-0" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div x-show="!createFree">
                        <x-input-label for="create_price" value="Prix ($)" />
                        <x-text-input id="create_price" name="price" type="number" min="0" step="0.01" class="mt-1 block w-full" />
                    </div>
                    <div>
                        <x-input-label for="create_stock" value="Stock (clé USB uniquement)" />
                        <x-text-input id="create_stock" name="stock" type="number" min="0" class="mt-1 block w-full" value="0" />
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="create_status" value="Statut" />
                        <select id="create_status" name="status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                        </select>
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
        <x-modal open="editOpen" title="Modifier le produit">
            <form method="POST" :action="`{{ url('admin/shop/products') }}/${editing.id}`" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div x-show="editing.image_url" class="flex justify-center">
                    <img :src="editing.image_url" class="h-24 rounded-lg object-cover border border-border">
                </div>
                <div>
                    <x-input-label for="edit_title" value="Titre" />
                    <input id="edit_title" name="title" type="text" x-model="editing.title" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent" required>
                </div>
                <div>
                    <x-input-label for="edit_description" value="Description" />
                    <textarea id="edit_description" name="description" rows="3" x-model="editing.description" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent"></textarea>
                </div>
                <div>
                    <x-input-label for="edit_type" value="Type de produit" />
                    <select id="edit_type" name="type" x-model="editing.type" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                        <option value="book">Livre</option>
                        <option value="video">Masterclass vidéo</option>
                        <option value="usb_key">Clé USB</option>
                    </select>
                </div>
                <div>
                    <x-input-label for="edit_image" value="Remplacer l'image (optionnel)" />
                    <input id="edit_image" name="image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_file" value="Remplacer le fichier (optionnel)" />
                    <input id="edit_file" name="file" type="file" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium">
                </div>
                <div>
                    <x-input-label for="edit_video_url" value="Lien vidéo externe" />
                    <input id="edit_video_url" name="video_url" type="url" x-model="editing.video_url" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                </div>
                <div class="flex items-center gap-2">
                    <input id="edit_is_free" name="is_free" type="checkbox" value="1" x-model="editFree" class="rounded border-border">
                    <x-input-label for="edit_is_free" value="Produit gratuit" class="!mb-0" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div x-show="!editFree">
                        <x-input-label for="edit_price" value="Prix ($)" />
                        <input id="edit_price" name="price" type="number" min="0" step="0.01" x-model="editing.price" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div>
                        <x-input-label for="edit_stock" value="Stock (clé USB)" />
                        <input id="edit_stock" name="stock" type="number" min="0" x-model="editing.stock" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                    </div>
                    <div class="col-span-2">
                        <x-input-label for="edit_status" value="Statut" />
                        <select id="edit_status" name="status" x-model="editing.status" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">
                            <option value="draft">Brouillon</option>
                            <option value="published">Publié</option>
                        </select>
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
