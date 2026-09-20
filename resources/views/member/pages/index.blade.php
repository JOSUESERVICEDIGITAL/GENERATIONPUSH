<x-layouts.member :title="$title">

    {{-- ==========================================================
        HEADER
    =========================================================== --}}

    <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

        <div>

            <div class="flex items-center gap-3 mb-3">

                <div
                    class="
                        h-10 w-10
                        rounded-xl
                        bg-primary/10
                        text-primary
                        flex items-center justify-center
                    "
                >

                    <x-icon
                        :name="$icon"
                        class="w-5 h-5"
                    />

                </div>

                <span class="text-sm font-medium text-muted-foreground">
                    Espace membre
                </span>

            </div>


            <h1 class="text-2xl md:text-3xl font-bold tracking-tight">
                {{ $title }}
            </h1>

            <p class="mt-1 text-muted-foreground">
                {{ $description }}
            </p>

        </div>

    </div>


    {{-- ==========================================================
        CONTENU
    =========================================================== --}}

    <div
        class="
            rounded-2xl
            border border-border
            bg-card
            overflow-hidden
        "
    >

        <div class="p-8 md:p-12 text-center">

            <div
                class="
                    mx-auto
                    h-16 w-16
                    rounded-2xl
                    bg-primary/10
                    text-primary
                    flex items-center justify-center
                "
            >

                <x-icon
                    :name="$icon"
                    class="w-7 h-7"
                />

            </div>


            <h2 class="mt-5 text-xl font-bold">
                {{ $title }}
            </h2>


            <p
                class="
                    max-w-xl
                    mx-auto
                    mt-2
                    text-muted-foreground
                "
            >
                Cette section de votre espace membre est prête.
                Les fonctionnalités détaillées seront intégrées
                progressivement à partir des données correspondantes.
            </p>


            <div class="mt-6">

                <a
                    href="{{ route('member.dashboard') }}"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        rounded-xl
                        bg-primary
                        text-primary-foreground
                        px-5 py-2.5
                        text-sm font-semibold
                        hover:opacity-90
                        transition
                    "
                >

                    <x-icon
                        name="arrow-left"
                        class="w-4 h-4"
                    />

                    Retour au tableau de bord

                </a>

            </div>

        </div>

    </div>

</x-layouts.member>