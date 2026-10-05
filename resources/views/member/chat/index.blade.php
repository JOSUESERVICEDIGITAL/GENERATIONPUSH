<x-layouts.member :title="'Chat communautaire'">

    @php
        $currentUser = auth()->user();

        $displayMessages = $messages->getCollection()->reverse()->values();
        $totalMessages = $messages->total();
    @endphp

    <style>
        :root {
            --brand: #E8631A;
            --brand-soft: rgba(232, 99, 26, .08);
            --brand-ring: rgba(232, 99, 26, .18);
        }

        .brand-text {
            color: var(--brand);
        }

        .brand-bg {
            background-color: var(--brand);
        }

        .brand-bg-soft {
            background-color: var(--brand-soft);
        }

        .brand-btn {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 12px 28px -14px rgba(232, 99, 26, .55);
            transition:
                transform .18s ease,
                box-shadow .18s ease,
                background-color .18s ease;
        }

        .brand-btn:hover {
            background: #d95713;
            transform: translateY(-1px);
            box-shadow: 0 18px 34px -15px rgba(232, 99, 26, .65);
        }

        .brand-btn:active {
            transform: translateY(0) scale(.98);
        }

        .brand-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .ghost-btn {
            transition:
                background-color .18s ease,
                border-color .18s ease,
                color .18s ease;
        }

        .ghost-btn:hover {
            border-color: rgba(232, 99, 26, .35);
            color: var(--brand);
        }

        /* ----------------------------------------------------------
           CHAT
        ---------------------------------------------------------- */

        .chat-shell {
            height: clamp(420px, 60vh, 720px);
        }

        .chat-scroll {
            overflow-y: auto;
            scrollbar-gutter: stable;
            overscroll-behavior: contain;
            scroll-behavior: smooth;
        }

        .chat-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .chat-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .chat-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* ----------------------------------------------------------
           BULLES
        ---------------------------------------------------------- */

        .bubble {
            word-wrap: break-word;
            word-break: break-word;
            max-width: min(82%, 560px);
            animation: bubbleIn .26s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes bubbleIn {
            from {
                opacity: 0;
                transform: translateY(6px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .bubble-mine {
            background: var(--brand);
            color: #fff;
            border-bottom-right-radius: 6px;
            box-shadow: 0 10px 24px -14px rgba(232, 99, 26, .55);
        }

        .bubble-admin {
            background: #fff7ed;
            color: #0f172a;
            border: 1px solid #fed7aa;
            border-bottom-left-radius: 6px;
        }

        .bubble-other {
            background: #fff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-bottom-left-radius: 6px;
            box-shadow: 0 8px 20px -18px rgba(15, 23, 42, .25);
        }

        /* ----------------------------------------------------------
           ACTIONS
        ---------------------------------------------------------- */

        .msg-row {
            position: relative;
        }

        .msg-actions {
            opacity: 0;
            transition: opacity .18s ease;
        }

        .msg-row:hover .msg-actions,
        .msg-row:focus-within .msg-actions {
            opacity: 1;
        }

        @media (hover: none) {
            .msg-actions {
                opacity: 1;
            }
        }

        /* ----------------------------------------------------------
           COMPOSER
        ---------------------------------------------------------- */

        .composer textarea {
            transition:
                border-color .18s ease,
                box-shadow .18s ease,
                background-color .18s ease;

            resize: none;
            min-height: 48px;
            max-height: 180px;
        }

        .composer textarea:focus {
            outline: none !important;
            border-color: var(--brand) !important;
            background: #fff !important;
            box-shadow: 0 0 0 4px var(--brand-ring) !important;
        }

        /* ----------------------------------------------------------
           SEPARATEUR JOUR
        ---------------------------------------------------------- */

        .day-sep {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .day-sep::before,
        .day-sep::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        @media (prefers-reduced-motion: reduce) {
            .bubble {
                animation: none;
            }

            .brand-btn,
            .ghost-btn {
                transition: none;
            }

            .chat-scroll {
                scroll-behavior: auto;
            }
        }
    </style>


    {{-- =========================================================
         WRAPPER
    ========================================================== --}}

    <div
        x-data="communityChat({
            currentUserId: {{ $currentUser->id }},
            chatEnabled: {{ $currentUser->chat_enabled ? 'true' : 'false' }}
        })"
        x-cloak
        @keydown.escape.window="cancelEdit()"
    >

        {{-- =====================================================
             EN-TÊTE
        ====================================================== --}}

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex min-w-0 items-center gap-3">

                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl brand-bg-soft brand-text">
                    <x-icon name="message-circle" class="h-5 w-5" />
                </div>

                <div class="min-w-0">

                    <nav class="flex items-center gap-2 text-xs font-semibold text-slate-400">
                        <span>Espace membre</span>

                        <x-icon
                            name="chevron-right"
                            class="h-3.5 w-3.5"
                        />

                        <span class="brand-text">
                            Chat communautaire
                        </span>
                    </nav>

                    <h1 class="mt-0.5 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">
                        Chat communautaire
                    </h1>

                </div>

            </div>

            <div class="flex flex-wrap items-center gap-2">

                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">

                    <span class="relative flex h-2 w-2">

                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>

                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>

                    </span>

                    Communauté active

                </span>

                @if($unreadMessagesCount > 0)

                    <span class="inline-flex items-center gap-1.5 rounded-full brand-bg-soft px-3 py-1.5 text-xs font-bold brand-text">

                        {{ $unreadMessagesCount }}

                        {{ $unreadMessagesCount > 1
                            ? 'nouveaux messages'
                            : 'nouveau message'
                        }}

                    </span>

                @endif

            </div>

        </div>


        {{-- =====================================================
             ALERTES
        ====================================================== --}}

        @if(session('success'))

            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700">

                <x-icon
                    name="check-circle"
                    class="mt-0.5 h-5 w-5 shrink-0"
                />

                <p class="font-medium">
                    {{ session('success') }}
                </p>

            </div>

        @endif


        @if(session('error'))

            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                <x-icon
                    name="alert-circle"
                    class="mt-0.5 h-5 w-5 shrink-0"
                />

                <p class="font-medium">
                    {{ session('error') }}
                </p>

            </div>

        @endif


        @if($errors->any())

            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700">

                <x-icon
                    name="alert-circle"
                    class="mt-0.5 h-5 w-5 shrink-0"
                />

                <div class="space-y-1">

                    @foreach($errors->all() as $error)

                        <p class="font-medium">
                            {{ $error }}
                        </p>

                    @endforeach

                </div>

            </div>

        @endif


        {{-- =====================================================
             CHAT
        ====================================================== --}}

        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-[0_10px_40px_-24px_rgba(15,23,42,.18)]">

            {{-- -------------------------------------------------
                 HEADER DISCUSSION
            -------------------------------------------------- --}}

            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100 text-slate-600">

                        <x-icon
                            name="users"
                            class="h-5 w-5"
                        />

                    </div>

                    <div>

                        <p class="font-bold text-slate-900">
                            Discussion générale
                        </p>

                        <p class="text-xs text-slate-500">

                            {{ $totalMessages }}

                            message{{ $totalMessages > 1 ? 's' : '' }}

                            · visible par tous

                        </p>

                    </div>

                </div>


                <button
                    type="button"
                    @click="scrollToBottom(true)"
                    class="ghost-btn hidden h-9 w-9 items-center justify-center rounded-xl border border-slate-200 text-slate-400 sm:flex"
                    title="Aller en bas"
                    aria-label="Aller en bas"
                >

                    <x-icon
                        name="arrow-down"
                        class="h-4 w-4"
                    />

                </button>

            </div>


            {{-- -------------------------------------------------
                 MESSAGES
            -------------------------------------------------- --}}

            <div
                x-ref="messages"
                class="chat-shell chat-scroll bg-slate-50 px-4 py-6 sm:px-6"
            >

                @forelse($displayMessages as $i => $message)

                    @php
                        $isMine = $message->author_id === $currentUser->id;
                        $isAdmin = (bool) $message->is_from_admin;

                        $authorName = $isAdmin
                            ? 'Generation PUSH'
                            : ($message->author?->name ?? 'Membre');

                        $initials = collect(
                            preg_split('/\s+/', trim($authorName))
                        )
                            ->filter()
                            ->map(
                                fn ($part) =>
                                    mb_strtoupper(
                                        mb_substr($part, 0, 1)
                                    )
                            )
                            ->take(2)
                            ->implode('');

                        $previousMessage = $displayMessages->get($i - 1);

                        $showDay =
                            ! $previousMessage ||
                            ! $message->created_at->isSameDay(
                                $previousMessage->created_at
                            );

                        $bubbleClass = $isMine
                            ? 'bubble-mine'
                            : ($isAdmin
                                ? 'bubble-admin'
                                : 'bubble-other');
                    @endphp


                    {{-- -------------------------------------------------
                         JOUR
                    -------------------------------------------------- --}}

                    @if($showDay)

                        <div class="day-sep my-5">

                            {{ $message->created_at->translatedFormat('d F Y') }}

                        </div>

                    @endif


                    {{-- -------------------------------------------------
                         MESSAGE
                    -------------------------------------------------- --}}

                    <div
                        class="msg-row mb-4 flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }}"
                        data-message-id="{{ $message->id }}"
                    >

                        <div class="flex max-w-full gap-2.5 {{ $isMine ? 'flex-row-reverse' : '' }}">

                            {{-- AVATAR --}}

                            <div
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-[11px] font-black
                                {{
                                    $isAdmin
                                        ? 'brand-bg text-white'
                                        : ($isMine
                                            ? 'bg-slate-200 text-slate-600'
                                            : 'brand-bg-soft brand-text')
                                }}"
                            >

                                @if($isAdmin)

                                    <x-icon
                                        name="sparkles"
                                        class="h-3.5 w-3.5"
                                    />

                                @else

                                    {{ $initials ?: 'M' }}

                                @endif

                            </div>


                            {{-- CONTENU --}}

                            <div class="min-w-0 flex-1">

                                {{-- NOM --}}

                                <div class="mb-1 flex flex-wrap items-center gap-2 {{ $isMine ? 'justify-end' : '' }}">

                                    <span class="text-xs font-bold text-slate-700">
                                        {{ $isMine ? 'Vous' : $authorName }}
                                    </span>

                                    @if($isAdmin)

                                        <span class="inline-flex items-center gap-1 rounded-full brand-bg-soft px-2 py-0.5 text-[10px] font-black brand-text">

                                            <x-icon
                                                name="shield-check"
                                                class="h-3 w-3"
                                            />

                                            Équipe

                                        </span>

                                    @endif

                                    @if($message->edited_at)

                                        <span class="text-[10px] font-medium italic text-slate-400">
                                            modifié
                                        </span>

                                    @endif

                                </div>


                                {{-- BULLE --}}

                                <div class="relative">

                                    <div
                                        x-show="editingId !== {{ $message->id }}"
                                        x-cloak
                                        class="bubble {{ $bubbleClass }} px-4 py-3 text-sm leading-6"
                                    >

                                        <p class="whitespace-pre-line">
                                            {!! nl2br(e($message->content)) !!}
                                        </p>

                                        <div
                                            class="mt-1.5 flex items-center gap-2 text-[10px]
                                            {{
                                                $isMine
                                                    ? 'justify-end text-white/70'
                                                    : 'text-slate-400'
                                            }}"
                                        >

                                            <span>
                                                {{ $message->created_at->translatedFormat('d M Y à H:i') }}
                                            </span>

                                        </div>

                                    </div>


                                    {{-- ÉDITION --}}

                                    @if($isMine)

                                        <form
                                            x-show="editingId === {{ $message->id }}"
                                            x-cloak
                                            method="POST"
                                            action="{{ route('member.chat.update', $message) }}"
                                            class="bubble bubble-mine px-3 py-3"
                                        >

                                            @csrf
                                            @method('PUT')

                                            <textarea
                                                x-model="editingContent"
                                                name="content"
                                                rows="2"
                                                maxlength="5000"
                                                required
                                                @input="resize($event)"
                                                class="block w-full resize-none rounded-xl border border-white/30 bg-white/15 px-3 py-2 text-sm text-white placeholder:text-white/60 focus:border-white/70 focus:bg-white/20 focus:outline-none"
                                                style="min-height: 60px; max-height: 180px;"
                                            ></textarea>


                                            <div class="mt-2 flex items-center justify-between gap-2">

                                                <span
                                                    class="text-[10px] text-white/70"
                                                    x-text="`${editingContent.length}/5000`"
                                                ></span>


                                                <div class="flex items-center gap-2">

                                                    <button
                                                        type="button"
                                                        @click="cancelEdit()"
                                                        class="rounded-lg border border-white/30 px-3 py-1.5 text-[11px] font-bold text-white transition hover:bg-white/10"
                                                    >
                                                        Annuler
                                                    </button>


                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-white px-3 py-1.5 text-[11px] font-bold brand-text transition hover:bg-white/90"
                                                    >

                                                        <x-icon
                                                            name="check"
                                                            class="h-3 w-3"
                                                        />

                                                        Enregistrer

                                                    </button>

                                                </div>

                                            </div>

                                        </form>

                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- ACTIONS --}}

                        @if($isMine)

                            <div
                                class="msg-actions flex shrink-0 items-center gap-1 self-center ps-1"
                                x-show="editingId !== {{ $message->id }}"
                            >

                                <button
                                    type="button"
                                    @click="startEdit(
                                        {{ $message->id }},
                                        @js($message->content)
                                    )"
                                    class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                    title="Modifier"
                                    aria-label="Modifier"
                                >

                                    <x-icon
                                        name="pencil"
                                        class="h-3.5 w-3.5"
                                    />

                                </button>


                                <form
                                    method="POST"
                                    action="{{ route('member.chat.destroy', $message) }}"
                                    onsubmit="return confirm('Supprimer ce message ?');"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="flex h-8 w-8 items-center justify-center rounded-lg text-slate-400 transition hover:bg-red-50 hover:text-red-600"
                                        title="Supprimer"
                                        aria-label="Supprimer"
                                    >

                                        <x-icon
                                            name="trash"
                                            class="h-3.5 w-3.5"
                                        />

                                    </button>

                                </form>

                            </div>

                        @endif

                    </div>

                @empty

                    {{-- ÉTAT VIDE --}}

                    <div class="flex h-full flex-col items-center justify-center py-10 text-center">

                        <div class="flex h-16 w-16 items-center justify-center rounded-2xl brand-bg-soft brand-text">

                            <x-icon
                                name="message-circle"
                                class="h-7 w-7"
                            />

                        </div>

                        <h3 class="mt-5 text-lg font-black text-slate-900">
                            Aucun message pour le moment
                        </h3>

                        <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                            Soyez le premier à lancer la discussion avec la communauté.
                        </p>

                    </div>

                @endforelse


                <div x-ref="bottom"></div>

            </div>


            {{-- -------------------------------------------------
                 PAGINATION
            -------------------------------------------------- --}}

            @if($messages->hasPages())

                <div class="border-t border-slate-100 bg-white px-5 py-4">

                    {{ $messages->links() }}

                </div>

            @endif


            {{-- =================================================
                 COMPOSER
            ================================================== --}}

            <div class="border-t border-slate-100 bg-white p-4 sm:p-5">

                @if(! $currentUser->chat_enabled)

                    <div class="flex items-start gap-3 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">

                        <x-icon
                            name="alert-triangle"
                            class="mt-0.5 h-4 w-4 shrink-0"
                        />

                        <p>
                            Votre accès au chat est désactivé.
                            Contactez l'équipe Generation PUSH pour le réactiver.
                        </p>

                    </div>

                @else

                    <form
                        method="POST"
                        action="{{ route('member.chat.store') }}"
                        class="composer mx-auto flex max-w-4xl items-end gap-3"
                        @submit="submit($event)"
                    >

                        @csrf

                        <div class="min-w-0 flex-1">

                            <label
                                for="content"
                                class="sr-only"
                            >
                                Votre message
                            </label>

                            <textarea
                                name="content"
                                id="content"
                                x-ref="textarea"
                                rows="1"
                                maxlength="5000"
                                required
                                x-model="content"
                                @input="resize($event)"
                                @keydown.enter="handleEnter($event)"
                                placeholder="Écrivez votre message… (Entrée pour envoyer, Maj+Entrée pour un saut de ligne)"
                                class="block w-full resize-none rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400"
                            >{{ old('content') }}</textarea>


                            <div class="mt-1.5 flex items-center justify-between gap-2 text-[11px] text-slate-400">

                                <span class="flex items-center gap-1.5">

                                    <x-icon
                                        name="info"
                                        class="h-3 w-3"
                                    />

                                    <span class="hidden sm:inline">
                                        Entrée pour envoyer · Maj+Entrée pour saut de ligne
                                    </span>

                                    <span class="sm:hidden">
                                        Appuyez sur Envoyer
                                    </span>

                                </span>

                                <span x-text="`${content.length}/5000`"></span>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="brand-btn inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl sm:w-auto sm:px-5"
                            aria-label="Envoyer le message"
                        >

                            <x-icon
                                name="send"
                                class="h-5 w-5"
                            />

                            <span class="ml-2 hidden text-sm font-bold sm:inline">
                                Envoyer
                            </span>

                        </button>

                    </form>

                @endif

            </div>

        </div>


        {{-- =====================================================
             SÉCURITÉ
        ====================================================== --}}

        <div class="mt-6 rounded-3xl brand-bg-soft border border-[#E8631A]/15 p-5">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-white/70 brand-text">

                    <x-icon
                        name="shield-check"
                        class="h-4 w-4"
                    />

                </div>

                <div>

                    <p class="font-bold text-slate-900">
                        Espace communautaire bienveillant
                    </p>

                    <p class="mt-1 text-xs leading-6 text-slate-600">
                        Restez respectueux et constructif.
                        Les messages inappropriés peuvent être supprimés par l'équipe.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         JAVASCRIPT
    ========================================================== --}}

    <script>
        function communityChat(config) {
            return {
                currentUserId: config.currentUserId,
                chatEnabled: config.chatEnabled,

                content: '',

                editingId: null,
                editingContent: '',

                init() {
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                },

                scrollToBottom(smooth = false) {
                    this.$nextTick(() => {

                        const el = this.$refs.messages;

                        if (!el) {
                            return;
                        }

                        if (smooth) {
                            el.scrollTo({
                                top: el.scrollHeight,
                                behavior: 'smooth'
                            });
                        } else {
                            el.scrollTop = el.scrollHeight;
                        }

                    });
                },

                handleEnter(event) {

                    if (event.shiftKey) {
                        return;
                    }

                    if (event.isComposing) {
                        return;
                    }

                    event.preventDefault();

                    if (!this.content.trim()) {
                        return;
                    }

                    const form = event.target.closest('form');

                    if (form) {
                        form.submit();
                    }
                },

                submit(event) {

                    if (!this.content.trim()) {
                        event.preventDefault();
                    }

                },

                resize(event) {

                    const el = event.target;

                    el.style.height = 'auto';

                    el.style.height =
                        Math.min(el.scrollHeight, 180) + 'px';

                },

                startEdit(id, content) {

                    this.editingId = id;
                    this.editingContent = content;

                    this.$nextTick(() => {

                        const textarea = document.querySelector(
                            `[data-message-id="${id}"] textarea`
                        );

                        if (!textarea) {
                            return;
                        }

                        textarea.focus();

                        textarea.style.height = 'auto';

                        textarea.style.height =
                            Math.min(textarea.scrollHeight, 180) + 'px';

                    });

                },

                cancelEdit() {

                    this.editingId = null;
                    this.editingContent = '';

                },
            };
        }
    </script>

</x-layouts.member>
