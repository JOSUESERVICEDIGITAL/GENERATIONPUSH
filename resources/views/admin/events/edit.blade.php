@extends('layouts.admin')

@section('title', 'Modifier l’événement')

@section('content')

<div class="space-y-6">

    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">

        <div>

            <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">

                <a
                    href="{{ route('admin.events.index') }}"
                    class="hover:text-accent"
                >
                    Événements
                </a>

                <x-icon
                    name="chevron-right"
                    class="w-4 h-4"
                />

                <span class="text-foreground">
                    Modifier
                </span>

            </div>


            <h1 class="text-2xl md:text-3xl font-bold text-foreground">
                Modifier l'événement
            </h1>

            <p class="text-sm text-muted-foreground mt-1">
                {{ $event->title }}
            </p>

        </div>


        <a
            href="{{ route('admin.events.show', $event) }}"
            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-border text-foreground hover:bg-secondary transition"
        >

            <x-icon
                name="eye"
                class="w-4 h-4"
            />

            Voir l'événement

        </a>

    </div>


    <form
        method="POST"
        action="{{ route('admin.events.update', $event) }}"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')

        @include('admin.events.partials.form')

    </form>

</div>

@endsection
