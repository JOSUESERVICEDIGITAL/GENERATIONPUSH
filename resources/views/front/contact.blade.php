<x-layouts.public title="Contact — Generation PUSH">

    <x-front.page-banner title="Contacte-nous" subtitle="Une question, un partenariat, une idée ? Écris-nous." />

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-5 gap-12">

            <!-- Coordonnées -->
            <div class="lg:col-span-2 space-y-6" x-data x-reveal="'left'">
                @if ($settings->contact_email)
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                            <x-icon name="mail" class="w-5 h-5 text-accent" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold text-[#1A1A1A]">{{ $settings->contact_email }}</p>
                        </div>
                    </div>
                @endif
                @if ($settings->contact_phone)
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                            <x-icon name="send" class="w-5 h-5 text-accent" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Téléphone</p>
                            <p class="font-semibold text-[#1A1A1A]">{{ $settings->contact_phone }}</p>
                        </div>
                    </div>
                @endif
                @if ($settings->contact_address)
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center shrink-0">
                            <x-icon name="calendar" class="w-5 h-5 text-accent" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Adresse</p>
                            <p class="font-semibold text-[#1A1A1A]">{{ $settings->contact_address }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Formulaire -->
            <div class="lg:col-span-3" x-data x-reveal="'right'">
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('front.contact.store') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                            @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                        <textarea name="message" rows="5" required class="w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent">{{ old('message') }}</textarea>
                        @error('message') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="px-8 py-3 rounded-lg bg-accent text-white font-semibold hover:opacity-90 transition-all duration-200 cursor-pointer">
                        Envoyer le message
                    </button>
                </form>
            </div>
        </div>
    </section>

</x-layouts.public>
