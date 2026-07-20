<x-layouts.admin title="Page d'accueil">
    <div>
        <h1 class="text-2xl font-bold text-foreground">Page d'accueil du site</h1>
        <p class="text-muted-foreground mt-1">Gère le contenu affiché sur la page d'accueil publique (hero vidéo, à propos, contact, réseaux sociaux).</p>
    </div>

    <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 text-sm text-accent hover:underline">
        <x-icon name="globe" class="w-4 h-4" />
        Voir le site public
    </a>

    <form method="POST" action="{{ route('admin.pages.home.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Hero -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-foreground flex items-center gap-2">
                <x-icon name="video" class="w-4 h-4 text-accent" /> Section Hero (bannière vidéo)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="hero_title" value="Titre principal" />
                    <x-text-input id="hero_title" name="hero_title" type="text" class="mt-1 block w-full" :value="old('hero_title', $settings->hero_title)" />
                </div>
                <div>
                    <x-input-label for="hero_subtitle" value="Sous-titre" />
                    <x-text-input id="hero_subtitle" name="hero_subtitle" type="text" class="mt-1 block w-full" :value="old('hero_subtitle', $settings->hero_subtitle)" />
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="hero_description" value="Description courte" />
                    <textarea id="hero_description" name="hero_description" rows="2" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('hero_description', $settings->hero_description) }}</textarea>
                </div>
                <div>
                    <x-input-label for="hero_cta_label" value="Texte du bouton" />
                    <x-text-input id="hero_cta_label" name="hero_cta_label" type="text" class="mt-1 block w-full" :value="old('hero_cta_label', $settings->hero_cta_label)" placeholder="ex: Rejoindre la communauté" />
                </div>
                <div>
                    <x-input-label for="hero_cta_url" value="Lien du bouton" />
                    <x-text-input id="hero_cta_url" name="hero_cta_url" type="text" class="mt-1 block w-full" :value="old('hero_cta_url', $settings->hero_cta_url)" placeholder="/register ou https://..." />
                </div>
            </div>

            <div class="border-t border-border pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="hero_video" value="Vidéo de fond (upload, MP4 recommandé)" />
                    @if ($settings->heroVideoUrl())
                        <video src="{{ $settings->heroVideoUrl() }}" class="mt-2 mb-2 rounded-lg h-24 w-full object-cover" muted></video>
                    @endif
                    <input id="hero_video" name="hero_video" type="file" accept="video/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                    <p class="text-xs text-muted-foreground mt-1">100 Mo max. Idéalement un extrait de masterclass, sans son, en boucle.</p>
                    <x-input-error :messages="$errors->get('hero_video')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="hero_video_url" value="...ou lien vidéo externe (utilisé si pas d'upload)" />
                    <x-text-input id="hero_video_url" name="hero_video_url" type="url" class="mt-1 block w-full" :value="old('hero_video_url', $settings->hero_video_url)" placeholder="https://..." />
                    <p class="text-xs text-muted-foreground mt-1">Accepte un lien YouTube, Vimeo, ou un lien direct vers un fichier .mp4.</p>
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="hero_poster" value="Image de secours (affichée pendant le chargement / si vidéo indisponible)" />
                    @if ($settings->heroPosterUrl())
                        <img src="{{ $settings->heroPosterUrl() }}" class="mt-2 mb-2 rounded-lg h-24 object-cover">
                    @endif
                    <input id="hero_poster" name="hero_poster" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-secondary file:text-foreground file:font-medium">
                </div>
            </div>
        </div>

        <!-- À propos -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-foreground flex items-center gap-2">
                <x-icon name="users-round" class="w-4 h-4 text-accent" /> Section "À propos" (accueil)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="about_title" value="Titre" />
                    <x-text-input id="about_title" name="about_title" type="text" class="mt-1 block w-full" :value="old('about_title', $settings->about_title)" />
                </div>
                <div>
                    <x-input-label for="about_image" value="Image" />
                    @if ($settings->aboutImageUrl())
                        <img src="{{ $settings->aboutImageUrl() }}" class="h-16 rounded-lg object-cover mb-2">
                    @endif
                    <input id="about_image" name="about_image" type="file" accept="image/*" class="mt-1 block w-full text-sm text-foreground file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent file:text-accent-foreground file:font-medium">
                </div>
                <div class="md:col-span-2">
                    <x-input-label for="about_text" value="Texte" />
                    <textarea id="about_text" name="about_text" rows="4" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('about_text', $settings->about_text) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Newsletter CTA -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-foreground flex items-center gap-2">
                <x-icon name="mail" class="w-4 h-4 text-accent" /> Bloc Newsletter
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-input-label for="newsletter_title" value="Titre" />
                    <x-text-input id="newsletter_title" name="newsletter_title" type="text" class="mt-1 block w-full" :value="old('newsletter_title', $settings->newsletter_title)" />
                </div>
                <div>
                    <x-input-label for="newsletter_text" value="Texte" />
                    <x-text-input id="newsletter_text" name="newsletter_text" type="text" class="mt-1 block w-full" :value="old('newsletter_text', $settings->newsletter_text)" />
                </div>
            </div>
        </div>

        <!-- Contact & réseaux -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-foreground flex items-center gap-2">
                <x-icon name="mail" class="w-4 h-4 text-accent" /> Contact & réseaux sociaux
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <x-input-label for="contact_email" value="Email de contact" />
                    <x-text-input id="contact_email" name="contact_email" type="email" class="mt-1 block w-full" :value="old('contact_email', $settings->contact_email)" />
                </div>
                <div>
                    <x-input-label for="contact_phone" value="Téléphone" />
                    <x-text-input id="contact_phone" name="contact_phone" type="text" class="mt-1 block w-full" :value="old('contact_phone', $settings->contact_phone)" />
                </div>
                <div>
                    <x-input-label for="contact_address" value="Adresse" />
                    <x-text-input id="contact_address" name="contact_address" type="text" class="mt-1 block w-full" :value="old('contact_address', $settings->contact_address)" />
                </div>
                <div>
                    <x-input-label for="facebook_url" value="Facebook" />
                    <x-text-input id="facebook_url" name="facebook_url" type="url" class="mt-1 block w-full" :value="old('facebook_url', $settings->facebook_url)" />
                </div>
                <div>
                    <x-input-label for="instagram_url" value="Instagram" />
                    <x-text-input id="instagram_url" name="instagram_url" type="url" class="mt-1 block w-full" :value="old('instagram_url', $settings->instagram_url)" />
                </div>
                <div>
                    <x-input-label for="twitter_url" value="X / Twitter" />
                    <x-text-input id="twitter_url" name="twitter_url" type="url" class="mt-1 block w-full" :value="old('twitter_url', $settings->twitter_url)" />
                </div>
                <div>
                    <x-input-label for="linkedin_url" value="LinkedIn" />
                    <x-text-input id="linkedin_url" name="linkedin_url" type="url" class="mt-1 block w-full" :value="old('linkedin_url', $settings->linkedin_url)" />
                </div>
                <div>
                    <x-input-label for="youtube_url" value="YouTube" />
                    <x-text-input id="youtube_url" name="youtube_url" type="url" class="mt-1 block w-full" :value="old('youtube_url', $settings->youtube_url)" />
                </div>
            </div>
        </div>

        <!-- SEO -->
        <div class="bg-card border border-border rounded-xl p-6 space-y-4">
            <h2 class="font-semibold text-foreground flex items-center gap-2">
                <x-icon name="search" class="w-4 h-4 text-accent" /> SEO par défaut
            </h2>
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <x-input-label for="meta_title" value="Titre meta" />
                    <x-text-input id="meta_title" name="meta_title" type="text" class="mt-1 block w-full" :value="old('meta_title', $settings->meta_title)" />
                </div>
                <div>
                    <x-input-label for="meta_description" value="Description meta" />
                    <textarea id="meta_description" name="meta_description" rows="2" class="mt-1 block w-full rounded-lg border-border bg-background text-foreground focus:border-accent focus:ring-accent">{{ old('meta_description', $settings->meta_description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2.5 rounded-lg bg-accent text-accent-foreground font-medium hover:opacity-90 transition-all duration-200">
                Enregistrer les modifications
            </button>
        </div>
    </form>
</x-layouts.admin>
