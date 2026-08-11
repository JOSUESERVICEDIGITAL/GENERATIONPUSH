<x-layouts.public :title="($page->title ?? 'Nous rejoindre') . ' — Generation PUSH'">

    <x-front.page-banner :title="$page->title ?? ($type === 'partner' ? 'Devenir partenaire' : 'Devenir bénévole')" :subtitle="$page->subtitle" />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            <!-- Contenu -->
            <div x-data x-reveal="'left'">
                @if ($page->imageUrl())
                    <img src="{{ $page->imageUrl() }}" class="w-full aspect-video object-cover rounded-2xl shadow-lg mb-8">
                @else
                    <div class="w-full aspect-video rounded-2xl mb-8 bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center">
                        <x-icon :name="$type === 'partner' ? 'award' : 'users-round'" class="w-14 h-14 text-accent/50" />
                    </div>
                @endif

                @if ($page->description)
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $page->description }}</p>
                @endif

                @if (! empty($page->benefitsList()))
                    <div class="mt-8 space-y-3">
                        @foreach ($page->benefitsList() as $benefit)
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-accent/10 flex items-center justify-center shrink-0 mt-0.5">
                                    <x-icon name="check" class="w-3.5 h-3.5 text-accent" />
                                </div>
                                <p class="text-gray-700 text-sm">{{ $benefit }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Formulaire -->
            <div class="bg-[#F8F9FA] rounded-2xl p-8" x-data x-reveal="'right'">
                <h2 class="font-bold text-xl text-[#1A1A1A] mb-6">{{ $page->cta_label ?? 'Envoyer ma candidature' }}</h2>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('front.engagement.store') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $type === 'partner' ? 'Entreprise / Organisation' : 'Organisation (optionnel)' }}
                        </label>
                        <input type="text" name="organization" value="{{ old('organization') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            {{ $type === 'partner' ? 'Ta proposition de partenariat' : 'Pourquoi veux-tu devenir bénévole ?' }}
                        </label>
                        <textarea name="message" rows="4" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">{{ old('message') }}</textarea>
                        @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full px-8 py-3 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200 cursor-pointer">
                        {{ $page->cta_label ?? 'Envoyer ma candidature' }}
                    </button>
                </form>
            </div>
        </div>
    </section>

</x-layouts.public>
