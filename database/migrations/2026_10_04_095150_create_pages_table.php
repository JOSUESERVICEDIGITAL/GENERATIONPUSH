<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();

            // Identifiant interne unique : about, home, contact...
            $table->string('key')->unique();

            $table->string('title');
            $table->text('subtitle')->nullable();

            // Banner
            $table->string('banner_video')->nullable();
            $table->string('banner_poster')->nullable();
            $table->boolean('banner_video_enabled')->default(false);

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};