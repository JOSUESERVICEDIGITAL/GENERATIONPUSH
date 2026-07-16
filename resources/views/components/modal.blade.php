@props(['open' => 'false', 'title' => ''])

<div
    x-show="{{ $open }}"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display: none;"
>
    <div
        x-show="{{ $open }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        class="fixed inset-0 bg-black/50"
        @click="{{ $open }} = false"
    ></div>

    <div
        x-show="{{ $open }}"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        class="relative bg-card border border-border rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto"
    >
        <div class="flex items-center justify-between px-6 py-4 border-b border-border sticky top-0 bg-card">
            <h3 class="font-semibold text-foreground">{{ $title }}</h3>
            <button type="button" @click="{{ $open }} = false" class="p-1 rounded-lg hover:bg-secondary">
                <x-icon name="x" class="w-5 h-5 text-muted-foreground" />
            </button>
        </div>
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>
