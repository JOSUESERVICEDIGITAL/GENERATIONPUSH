<x-layouts.public title="Mon compte — Generation PUSH">

    <x-front.page-banner title="Mon compte" subtitle="Gère tes informations personnelles et ta sécurité" />

    <section class="py-16 bg-[#F8F9FA]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @isset($header)
                {{ $header }}
            @endisset

            {{ $slot }}
        </div>
    </section>

</x-layouts.public>
