<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engagement_pages', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['partner', 'volunteer'])->unique();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->text('benefits')->nullable(); // une ligne par avantage
            $table->string('image_path')->nullable();
            $table->string('cta_label')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engagement_pages');
    }
};
