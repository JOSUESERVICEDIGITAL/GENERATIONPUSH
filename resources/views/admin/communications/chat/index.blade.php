<x-layouts.admin title="Chat interne">
    <div>
        <h1 class="text-3xl font-bold text-foreground">Chat interne</h1>
        <p class="text-muted-foreground mt-2">Conversations avec les membres connectés au site</p>
    </div>

    @if (session('success'))
        <div class="bg-green-50 dark:bg-green-950/30 border border-green-200 dark:border-green-900 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg text-sm mt-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-2 gap-4 mt-6 max-w-md">
        <div class="bg-card border border-border rounded-lg p-4">
            <p class="text-xs text-muted-foreground mb-2">Conversations</p>
            <p class="text-2xl font-bold text-foreground">{{ $stats['threads'] }}</p>
        </div>
        <div class="bg-card border border-border rounded-lg p-4">
            <p class="text-xs text-muted-foreground mb-2">Messages non lus</p>
            <p class="text-2xl font-bold text-accent">{{ $stats['unread'] }}</p>
        </div>
    </div>

    <div class="bg-card border border-border rounded-xl overflow-hidden mt-6">
        <div class="p-6 border-b border-border">
            <h2 class="text-lg font-semibold text-foreground">Conversations</h2>
        </div>
        <div class="p-6 space-y-4">
            <form method="GET" class="flex items-center gap-3">
                <div class="relative flex-1 max-w-sm">
                    <x-icon name="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                    <input type="text" name="search" value="{{ $search }}" placeholder="Rechercher un membre..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-border bg-background text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-200">
                </div>
            </form>

            <div class="divide-y divide-border border border-border rounded-lg overflow-hidden">
                @forelse ($threads as $thread)
                    <a href="{{ route('admin.communications.chat.show', $thread) }}" class="flex items-center gap-4 p-4 hover:bg-secondary/50 transition-colors duration-200 {{ $thread->unread_count > 0 ? 'bg-accent/5' : '' }}">
                        <div class="w-10 h-10 rounded-full bg-accent flex items-center justify-center text-white font-semibold text-sm shrink-0">
                            {{ Str::of($thread->name)->explode(' ')->map(fn($w) => Str::substr($w, 0, 1))->take(2)->join('') }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-medium text-foreground text-sm">{{ $thread->name }}</p>
                                @if (! $thread->chat_enabled)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold bg-red-50 text-red-700 dark:bg-red-950/30 dark:text-red-400">Bloqué</span>
                                @endif
                            </div>
                            <p class="text-xs text-muted-foreground truncate">{{ $thread->last_message?->content }}</p>
                        </div>
                        <div class="text-end shrink-0">
                            <p class="text-xs text-muted-foreground">{{ $thread->last_message?->created_at->diffForHumans() }}</p>
                            @if ($thread->unread_count > 0)
                                <span class="inline-block mt-1 px-2 py-0.5 rounded-full bg-accent text-white text-[10px] font-bold">{{ $thread->unread_count }}</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <p class="p-6 text-center text-muted-foreground">Aucune conversation pour l'instant</p>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
