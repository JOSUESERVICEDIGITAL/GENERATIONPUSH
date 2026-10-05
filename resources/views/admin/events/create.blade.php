@extends('layouts.admin')

@section('title', 'Nouvel événement')

@section('content')

<div class="space-y-6">

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
                Nouveau
            </span>

        </div>

        <h1 class="text-2xl md:text-3xl font-bold text-foreground">
            Créer un événement
        </h1>

        <p class="text-sm text-muted-foreground mt-1">
            Ajoutez une conférence, masterclass, session de coaching,
            PushConnect ou tout autre événement Generation PUSH.
        </p>

    </div>


    <form
        method="POST"
        action="{{ route('admin.events.store') }}"
        enctype="multipart/form-data"
    >

        @csrf

        @include('admin.events.partials.form')

    </form>

</div>

@endsection
