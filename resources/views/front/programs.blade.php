<x-layouts.public title="Programmes — Generation PUSH">

    <x-front.page-banner title="Nos programmes de formation" subtitle="Des formations pensées pour révéler et renforcer le leader en toi" />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Recherche -->
            <form method="GET" class="max-w-md mx-auto mb-14" x-data x-reveal>
                <div class="relative">
                    <x-icon name="search" class="absolute start-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Rechercher une formation..."
                        class="w-full ps-11 pe-4 py-3 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent"
                    >
                </div>
            </form>

            @if ($formations->isEmpty())
                <p class="text-center text-gray-500 py-12">Aucune formation trouvée{{ $search ? " pour « {$search} »" : '' }}.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($formations as $i => $formation)
                        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl transition-shadow duration-300 overflow-hidden group" x-data x-reveal.delay.{{ ($i % 3) * 100 }}>
                            <div class="aspect-video bg-gradient-to-br from-accent/20 to-accent/5 flex items-center justify-center group-hover:scale-105 transition-transform duration-500 relative">
                                <x-icon name="book-open" class="w-10 h-10 text-accent/60" />
                                @if ($formation->status === 'completed')
                                    <span class="absolute top-3 start-3 px-2.5 py-1 rounded-lg bg-gray-800 text-white text-xs font-semibold">Terminée</span>
                                @else
                                    <span class="absolute top-3 start-3 px-2.5 py-1 rounded-lg bg-accent text-white text-xs font-semibold">En cours</span>
                                @endif
                            </div>
                            <div class="p-6">
                                <h3 class="font-bold text-lg text-[#1A1A1A] mb-2 line-clamp-2">{{ $formation->name }}</h3>
                                <p class="text-sm text-gray-500 mb-1">Formateur : {{ $formation->trainer ?? '—' }}</p>
                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-5">
                                    <span class="flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $formation->duration ?? '—' }}</span>
                                    <span class="flex items-center gap-1"><x-icon name="users" class="w-3.5 h-3.5" /> {{ $formation->participants }} inscrits</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-accent text-lg">{{ number_format($formation->price, 0) }} $</span>
                                    <a href="{{ route('register') }}" class="px-4 py-2 rounded-lg bg-accent text-white text-sm font-semibold hover:opacity-90 transition-all duration-200">
                                        S'inscrire
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">
                    {{ $formations->links() }}
                </div>
            @endif
        </div>
    </section>

</x-layouts.public>
