@php
    $settings = $settings ?? \App\Models\SiteSetting::current();

    $footerColumns = \App\Models\MenuItem::footer()
        ->active()
        ->orderBy('order')
        ->get()
        ->groupBy(fn($item) => $item->footer_column ?? 'Liens');

    $socials = collect([
        ['url' => $settings->facebook_url, 'icon' => 'facebook'],
        ['url' => $settings->instagram_url, 'icon' => 'instagram'],
        ['url' => $settings->twitter_url, 'icon' => 'twitter'],
        ['url' => $settings->linkedin_url, 'icon' => 'linkedin'],
        ['url' => $settings->youtube_url, 'icon' => 'youtube'],
    ])->filter(fn($social) => $social['url']);
@endphp


<footer class="bg-[#1A1A1A] text-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- =========================================================
        CONTENU PRINCIPAL
        ========================================================== --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 items-start gap-x-10 gap-y-8 py-9">
            {{-- MARQUE --}}
            <div>
                <a href="{{ route('front.home') }}" class="inline-flex items-start mb-3 -mt-8">
                    <img src="{{ asset('front/images/logo.png') }}" alt="Generation PUSH"
                        class="h-28 w-auto object-contain">
                </a>


                <p class="text-xs leading-5 text-gray-400 max-w-[260px] mb-0">
                    {{ $settings->about_text
    ? \Illuminate\Support\Str::limit(
        $settings->about_text,
        105
    )
    : 'La communauté qui forme et connecte les leaders africains de demain.'
                    }}
                </p>


                @if($socials->isNotEmpty())

                    <div class="flex items-center gap-2 mt-4">

                        @foreach($socials as $social)

                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer"
                                aria-label="{{ ucfirst($social['icon']) }}" class="w-8 h-8 rounded-full
                                                       bg-white/10
                                                       hover:bg-[#E8631A]
                                                       flex items-center justify-center
                                                       transition-all duration-200">
                                <x-icon :name="$social['icon']" class="w-3.5 h-3.5" />
                            </a>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- =========================================================
            COLONNES DYNAMIQUES
            ========================================================== --}}
            @foreach($footerColumns as $column => $links)

                <div>

                    <h3 class="text-sm font-semibold text-white mb-3">
                        {{ $column }}
                    </h3>

                    <ul class="space-y-2">

                        @foreach($links as $link)

                            <li>

                                <a href="{{ $link->url }}" @if($link->open_in_new_tab) target="_blank" rel="noopener noreferrer"
                                @endif class="text-xs text-gray-400
                                                           hover:text-[#E8631A]
                                                           transition-colors duration-200">
                                    {{ $link->label }}
                                </a>

                            </li>

                        @endforeach

                    </ul>

                </div>

            @endforeach


            {{-- =========================================================
            CONTACT
            ========================================================== --}}
            <div>

                <h3 class="text-sm font-semibold text-white mb-3">
                    Contact
                </h3>


                <ul class="space-y-2.5 text-xs text-gray-400">

                    {{-- EMAIL --}}
                    @if($settings->contact_email)

                        <li class="flex items-start gap-2">

                            <x-icon name="mail" class="w-4 h-4 mt-0.5 shrink-0 text-[#E8631A]" />

                            <a href="mailto:{{ $settings->contact_email }}"
                                class="hover:text-white transition-colors break-all">
                                {{ $settings->contact_email }}
                            </a>

                        </li>

                    @endif


                    {{-- TÉLÉPHONE --}}
                    @if($settings->contact_phone)

                        <li class="flex items-start gap-2">

                            <x-icon name="phone" class="w-4 h-4 mt-0.5 shrink-0 text-[#E8631A]" />

                            <a href="tel:{{ preg_replace('/\s+/', '', $settings->contact_phone) }}"
                                class="hover:text-white transition-colors">
                                {{ $settings->contact_phone }}
                            </a>

                        </li>

                    @endif


                    {{-- ADRESSE --}}
                    @if($settings->contact_address)

                        <li class="flex items-start gap-2">

                            <x-icon name="map-pin" class="w-4 h-4 mt-0.5 shrink-0 text-[#E8631A]" />

                            <span class="leading-5">
                                {{ $settings->contact_address }}
                            </span>

                        </li>

                    @endif

                </ul>

            </div>

        </div>


        {{-- =========================================================
        BARRE INFÉRIEURE
        ========================================================== --}}
        <div class="border-t border-white/10
                   py-4
                   flex flex-col sm:flex-row
                   items-center justify-between
                   gap-2">

            <p class="text-[11px] text-gray-500 m-0">
                © {{ now()->year }} Generation PUSH.
                Tous droits réservés.
            </p>


            <p class="text-[11px] text-gray-500 m-0 sm:pr-14">

                Développé par

                <a href="https://eunital.com" target="_blank" rel="noopener noreferrer" class="font-bold text-[#E8631A]
                           hover:text-white
                           transition-colors duration-200">
                    EUNITAL
                </a>

            </p>

        </div>

    </div>

</footer>
