@php
    $settings = $settings ?? \App\Models\SiteSetting::current();
    $footerColumns = \App\Models\MenuItem::footer()->active()->orderBy('order')->get()->groupBy(fn ($i) => $i->footer_column ?? 'Liens');
    $socials = collect([
        ['url' => $settings->facebook_url, 'icon' => 'facebook'],
        ['url' => $settings->instagram_url, 'icon' => 'instagram'],
        ['url' => $settings->twitter_url, 'icon' => 'twitter'],
        ['url' => $settings->linkedin_url, 'icon' => 'linkedin'],
        ['url' => $settings->youtube_url, 'icon' => 'youtube'],
    ])->filter(fn ($s) => $s['url']);
@endphp

<footer class="bg-[#1A1A1A] text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10">
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-10 h-10 rounded-lg bg-accent flex items-center justify-center text-white font-bold">GP</div>
                    <span class="font-bold">Generation PUSH</span>
                </div>
                <p class="text-sm text-gray-400 max-w-sm">{{ $settings->about_text ? \Illuminate\Support\Str::limit($settings->about_text, 160) : "La communauté qui forme les leaders de demain en Afrique." }}</p>

                @if ($socials->isNotEmpty())
                    <div class="flex items-center gap-3 mt-5">
                        @foreach ($socials as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-white/10 hover:bg-accent flex items-center justify-center transition-colors duration-200">
                                <x-icon :name="$social['icon']" class="w-4 h-4" />
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @foreach ($footerColumns as $column => $links)
                <div>
                    <h3 class="font-semibold text-sm mb-4">{{ $column }}</h3>
                    <ul class="space-y-2.5">
                        @foreach ($links as $link)
                            <li>
                                <a href="{{ $link->url }}" @if($link->open_in_new_tab) target="_blank" @endif class="text-sm text-gray-400 hover:text-white transition-colors duration-200">{{ $link->label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach

            <div>
                <h3 class="font-semibold text-sm mb-4">Contact</h3>
                <ul class="space-y-2.5 text-sm text-gray-400">
                    @if ($settings->contact_email)
                        <li class="flex items-start gap-2"><x-icon name="mail" class="w-4 h-4 mt-0.5 shrink-0" /> {{ $settings->contact_email }}</li>
                    @endif
                    @if ($settings->contact_phone)
                        <li class="flex items-start gap-2"><x-icon name="send" class="w-4 h-4 mt-0.5 shrink-0" /> {{ $settings->contact_phone }}</li>
                    @endif
                    @if ($settings->contact_address)
                        <li class="flex items-start gap-2"><x-icon name="calendar" class="w-4 h-4 mt-0.5 shrink-0" /> {{ $settings->contact_address }}</li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-gray-500">&copy; {{ now()->year }} Generation PUSH. Tous droits réservés.</p>
            <p class="text-xs text-gray-500">Fait avec 🧡 pour les leaders africains de demain.</p>
        </div>
    </div>
</footer>
