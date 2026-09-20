<x-layouts.public :title="'Inscription en attente — Generation PUSH'">

    <section class="min-h-[70vh] flex items-center justify-center bg-white py-20">
        <div class="w-full max-w-2xl px-6">

            <div class="rounded-3xl border border-border bg-card p-8 md:p-12 text-center shadow-sm">

                {{-- Icône --}}
                <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-primary/10 text-primary">
                    <x-icon name="clock-3" class="h-10 w-10" />
                </div>

                {{-- Titre --}}
                <h1 class="text-3xl font-bold tracking-tight text-foreground md:text-4xl">
                    Demande d'inscription reçue
                </h1>

                {{-- Message --}}
                <p class="mx-auto mt-5 max-w-xl text-base leading-7 text-muted-foreground md:text-lg">
                    Merci
                    @if(!empty($name))
                        <strong class="text-foreground">{{ $name }}</strong>
                    @endif
                    d'avoir souhaité rejoindre la communauté
                    <strong class="text-foreground">Generation PUSH</strong>.
                </p>

                <p class="mx-auto mt-4 max-w-xl text-base leading-7 text-muted-foreground">
                    Votre demande a bien été enregistrée et votre compte est
                    actuellement <strong class="text-foreground">en attente de validation</strong>
                    par notre équipe.
                </p>

                {{-- Informations --}}
                @if(!empty($email))
                    <div class="mx-auto mt-8 max-w-md rounded-2xl border border-border bg-muted/30 p-5 text-left">
                        <div class="text-sm text-muted-foreground">
                            Adresse e-mail
                        </div>

                        <div class="mt-1 break-all font-medium text-foreground">
                            {{ $email }}
                        </div>
                    </div>
                @endif

                {{-- Délai --}}
                <div class="mt-8 rounded-2xl bg-primary/5 p-5 text-sm leading-6 text-muted-foreground">
                    Nous allons examiner votre demande et vous contacter dès que
                    votre inscription aura été traitée.
                </div>

                {{-- Retour --}}
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">

                    <a
                        href="{{ route('front.home') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-primary px-6 py-3 font-semibold text-primary-foreground transition hover:opacity-90"
                    >
                        Retour à l'accueil
                    </a>

                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-border bg-white px-6 py-3 font-semibold text-foreground transition hover:bg-muted"
                    >
                        Aller à la connexion
                    </a>

                </div>

            </div>

        </div>
    </section>

</x-layouts.public>
