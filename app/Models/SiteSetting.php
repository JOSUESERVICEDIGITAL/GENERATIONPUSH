<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SiteSetting extends Model
{
    protected $fillable = [
        'hero_title', 'hero_subtitle', 'hero_description',
        'hero_video_path', 'hero_video_url', 'hero_poster_path',
        'hero_cta_label', 'hero_cta_url',
        'about_title', 'about_text', 'about_image_path',
        'contact_email', 'contact_phone', 'contact_address',
        'facebook_url', 'instagram_url', 'twitter_url', 'linkedin_url', 'youtube_url',
        'newsletter_title', 'newsletter_text',
        'meta_title', 'meta_description',
    ];

    /**
     * Récupère (ou crée) l'unique ligne de paramètres du site.
     */
    public static function current(): self
    {
        return static::first() ?? static::create([]);
    }

    /**
     * URL du fichier vidéo uploadé (MP4 direct), à utiliser dans une balise <video>.
     * Retourne null si aucun fichier n'a été uploadé (utiliser heroEmbedUrl() dans ce cas).
     */
    public function heroVideoUrl(): ?string
    {
        return $this->hero_video_path ? Storage::disk('public')->url($this->hero_video_path) : null;
    }

    /**
     * Si hero_video_url est un lien YouTube/Vimeo, retourne l'URL d'embed (iframe).
     * Retourne null si ce n'est pas un lien YouTube/Vimeo reconnu (ou si vide).
     */
    public function heroEmbedUrl(): ?string
    {
        $url = $this->hero_video_url;

        if (! $url) {
            return null;
        }

        // YouTube : watch?v=ID, youtu.be/ID, ou déjà /embed/ID
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
            return "https://www.youtube.com/embed/{$m[1]}?autoplay=1&mute=1&loop=1&playlist={$m[1]}&controls=0&playsinline=1";
        }

        // Vimeo : vimeo.com/ID
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}?autoplay=1&muted=1&loop=1&background=1";
        }

        return null;
    }

    /**
     * URL à utiliser directement dans une balise <video><source> (fichier .mp4 direct uniquement).
     * Retourne null si hero_video_url n'est pas un fichier direct (ex: lien YouTube).
     */
    public function heroDirectVideoUrl(): ?string
    {
        if ($this->hero_video_path) {
            return $this->heroVideoUrl();
        }

        if ($this->hero_video_url && ! $this->heroEmbedUrl()) {
            return $this->hero_video_url;
        }

        return null;
    }

    public function heroPosterUrl(): ?string
    {
        return $this->hero_poster_path ? Storage::disk('public')->url($this->hero_poster_path) : null;
    }

    public function aboutImageUrl(): ?string
    {
        return $this->about_image_path ? Storage::disk('public')->url($this->about_image_path) : null;
    }
}
