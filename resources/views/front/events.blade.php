<x-layouts.public title="Événements — Generation PUSH">

    <x-front.page-banner title="Nos événements" subtitle="Conférences et masterclass pour apprendre, se connecter et grandir ensemble" />

    <section class="py-16 md:py-20 bg-white" x-data="{ tab: 'upcoming' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Onglets -->
            <div class="flex items-center justify-center gap-2 mb-14" x-data x-reveal>
                <button @click="tab = 'upcoming'" :class="tab === 'upcoming' ? 'bg-accent text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 cursor-pointer">
                    À venir ({{ $upcoming->count() }})
                </button>
                <button @click="tab = 'past'" :class="tab === 'past' ? 'bg-accent text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'" class="px-6 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 cursor-pointer">
                    Passés ({{ $past->count() }})
                </button>
            </div>

            <!-- À venir -->
            <div x-show="tab === 'upcoming'">
                @if ($upcoming->isEmpty())
                    <p class="text-center text-gray-500 py-12">Aucun événement à venir pour l'instant.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($upcoming as $i => $event)
                            <div class="border border-gray-100 rounded-2xl overflow-hidden hover:shadow-xl transition-shadow duration-300 group" x-data x-reveal.delay.{{ ($i % 3) * 100 }}>
                                <div class="aspect-video bg-gradient-to-br from-[#1A1A1A] to-accent/40 flex items-center justify-center relative overflow-hidden">
                                    <x-icon name="zap" class="w-10 h-10 text-white/70 group-hover:scale-110 transition-transform duration-500" />
                                    <span class="absolute top-3 start-3 px-2.5 py-1 rounded-lg bg-white text-[#1A1A1A] text-xs font-bold">{{ $event['date']?->format('d M Y') }}</span>
                                    <span class="absolute top-3 end-3 px-2.5 py-1 rounded-lg bg-accent text-white text-xs font-semibold">{{ $event['type'] }}</span>
                                </div>
                                <div class="p-6">
                                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-2 line-clamp-2">{{ $event['title'] }}</h3>
                                    <p class="text-sm text-gray-500 flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $event['location'] }}, {{ $event['country'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Passés -->
            <div x-show="tab === 'past'" x-cloak>
                @if ($past->isEmpty())
                    <p class="text-center text-gray-500 py-12">Aucun événement passé pour l'instant.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($past as $i => $event)
                            <div class="border border-gray-100 rounded-2xl overflow-hidden opacity-75 hover:opacity-100 transition-opacity duration-300" x-data x-reveal.delay.{{ ($i % 3) * 100 }}>
                                <div class="aspect-video bg-gray-100 flex items-center justify-center relative">
                                    <x-icon name="zap" class="w-10 h-10 text-gray-300" />
                                    <span class="absolute top-3 start-3 px-2.5 py-1 rounded-lg bg-white text-gray-600 text-xs font-bold">{{ $event['date']?->format('d M Y') }}</span>
                                    <span class="absolute top-3 end-3 px-2.5 py-1 rounded-lg bg-gray-700 text-white text-xs font-semibold">{{ $event['type'] }}</span>
                                </div>
                                <div class="p-6">
                                    <h3 class="font-bold text-lg text-[#1A1A1A] mb-2 line-clamp-2">{{ $event['title'] }}</h3>
                                    <p class="text-sm text-gray-500 flex items-center gap-1"><x-icon name="calendar" class="w-3.5 h-3.5" /> {{ $event['location'] }}, {{ $event['country'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

</x-layouts.public>
