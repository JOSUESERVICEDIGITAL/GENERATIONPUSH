<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('founder_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('role_title')->nullable(); // ex: Fondatrice & Directrice générale
            $table->string('main_photo_path')->nullable(); // photo de couverture (bannière)

            $table->text('bio')->nullable();
            $table->text('why_founded')->nullable();
            $table->text('mission')->nullable();

            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();

            // Interrupteurs de visibilité par section (contrôlés depuis l'admin)
            $table->boolean('show_bio')->default(true);
            $table->boolean('show_why_founded')->default(true);
            $table->boolean('show_mission')->default(true);
            $table->boolean('show_social')->default(true);
            $table->boolean('show_gallery')->default(true);
            $table->boolean('is_page_enabled')->default(true); // désactive toute la page publique

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('founder_profiles');
    }
};
