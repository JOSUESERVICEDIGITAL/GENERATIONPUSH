<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volunteer_applications', function (Blueprint $table) {
            $table->id();

            // Informations personnelles
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('city_country');
            $table->unsignedTinyInteger('age');
            $table->string('profession');

            // Domaines de contribution
            $table->json('contribution_areas');
            $table->string('contribution_other')->nullable();

            // Motivation
            $table->text('motivation');
            $table->text('skills');

            // Disponibilité
            $table->enum('availability', [
                'part_time',
                'events_only',
                'full_time',
            ]);

            // Expérience Generation PUSH
            $table->boolean('participated_before')->default(false);

            // Réseaux sociaux
            $table->string('social_link')->nullable();

            // Gestion administrative de la candidature
            $table->enum('status', [
                'pending',
                'reviewing',
                'accepted',
                'rejected',
            ])->default('pending');

            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volunteer_applications');
    }
};
