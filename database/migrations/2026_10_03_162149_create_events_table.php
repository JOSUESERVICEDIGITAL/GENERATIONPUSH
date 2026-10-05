<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('event_category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Informations principales
            $table->string('title');
            $table->string('slug')->unique();

            $table->string('subtitle')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();

            // Visuels
            $table->string('image')->nullable();
            $table->string('banner')->nullable();

            // Intervenant
            $table->string('speaker')->nullable();
            $table->string('speaker_title')->nullable();
            $table->string('speaker_image')->nullable();

            // Dates
            $table->dateTime('starts_at');
            $table->dateTime('ends_at')->nullable();

            // Format
            $table->enum('format', [
                'physical',
                'online',
                'hybrid'
            ])->default('physical');

            // Localisation
            $table->string('venue')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();

            // Événement en ligne
            $table->string('online_url')->nullable();

            // Réservations
            $table->boolean('reservation_enabled')->default(true);

            $table->unsignedInteger('capacity')->nullable();

            $table->dateTime('reservation_starts_at')->nullable();
            $table->dateTime('reservation_ends_at')->nullable();

            // Tarification
            $table->boolean('is_free')->default(true);
            $table->decimal('price', 12, 2)->default(0);
            $table->string('currency', 10)->default('XOF');

            // Publication
            $table->enum('status', [
                'draft',
                'published',
                'cancelled'
            ])->default('draft');

            $table->boolean('featured')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
