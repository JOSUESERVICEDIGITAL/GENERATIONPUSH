<x-layouts.public title="Mes messages — Generation PUSH">

    <x-front.page-banner title="Mes messages" subtitle="Discute directement avec l'équipe Generation PUSH" />

    <section class="py-12 md:py-16 bg-[#F8F9FA] min-h-[60vh]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ editingId: null, editingContent: '' }">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[65vh]">
                <!-- Fil de discussion -->
                <div class="flex-1 overflow-y-auto p-6 space-y-4">
                    @forelse ($messages as $message)
                        <div class="flex {{ $message->is_from_admin ? 'justify-start' : 'justify-end' }}">
                            <div class="max-w-[80%]">
                                <div
                                    x-show="editingId !== {{ $message->id }}"
                                    class="rounded-2xl px-4 py-2.5 {{ $message->is_from_admin ? 'bg-gray-100 text-[#1A1A1A]' : 'bg-accent text-white' }}"
                                >
                                    <p class="text-sm whitespace-pre-line">{{ $message->content }}</p>
                                </div>

                                @if (!$message->is_from_admin)
                                    <form
                                        x-show="editingId === {{ $message->id }}"
                                        method="POST"
                                        action="{{ route('front.chat.update', $message) }}"
                                        class="flex items-center gap-2"
                                    >
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="content" x-model="editingContent" class="flex-1 text-sm px-3 py-2 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent">
                                        <button type="submit" class="text-xs font-semibold text-accent cursor-pointer">OK</button>
                                        <button type="button" @click="editingId = null" class="text-xs text-gray-400 cursor-pointer">Annuler</button>
                                    </form>
                                @endif

                                <div class="flex items-center gap-2 mt-1 {{ $message->is_from_admin ? 'justify-start' : 'justify-end' }}">
                                    <p class="text-[11px] text-gray-400">
                                        {{ $message->is_from_admin ? 'Équipe' : 'Toi' }} · {{ $message->created_at->format('d/m H:i') }}
                                        @if ($message->edited_at) (modifié) @endif
                                    </p>
                                    @if (!$message->is_from_admin)
                                        <button type="button" @click="editingId = {{ $message->id }}; editingContent = @js($message->content)" class="text-[11px] text-gray-400 hover:text-accent cursor-pointer">Modifier</button>
                                        <form method="POST" action="{{ route('front.chat.destroy', $message) }}" onsubmit="return confirm('Supprimer ce message ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-[11px] text-gray-400 hover:text-destructive cursor-pointer">Supprimer</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 text-sm py-12">Aucun message pour l'instant. Écris à l'équipe ci-dessous !</p>
                    @endforelse
                </div>

                <!-- Zone d'envoi -->
                <div class="border-t border-gray-100 p-4">
                    @if ($user->chat_enabled)
                        <form method="POST" action="{{ route('front.chat.store') }}" class="flex items-center gap-3">
                            @csrf
                            <input type="text" name="content" placeholder="Écris ton message..." required class="flex-1 px-4 py-2.5 rounded-full border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                            <button type="submit" class="w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center hover:opacity-90 transition-all duration-200 cursor-pointer shrink-0">
                                <x-icon name="send" class="w-4 h-4" />
                            </button>
                        </form>
                    @else
                        <p class="text-center text-sm text-gray-400 py-2">L'envoi de messages a été désactivé pour ton compte par l'équipe.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

</x-layouts.public>
