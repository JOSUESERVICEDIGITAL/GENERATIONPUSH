<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // Hero (accueil)
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_video_path')->nullable();
            $table->string('hero_video_url')->nullable();
            $table->string('hero_poster_path')->nullable();
            $table->string('hero_cta_label')->nullable();
            $table->string('hero_cta_url')->nullable();

            // À propos (extrait utilisé sur l'accueil)
            $table->string('about_title')->nullable();
            $table->text('about_text')->nullable();
            $table->string('about_image_path')->nullable();

            // Contact
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_address')->nullable();

            // Réseaux sociaux
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();

            // Newsletter (bloc CTA)
            $table->string('newsletter_title')->nullable();
            $table->text('newsletter_text')->nullable();

            // SEO par défaut
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
