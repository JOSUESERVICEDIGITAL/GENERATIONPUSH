<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('partner_applications', function (Blueprint $table) {
            $table->id();

            // Informations sur l'organisation
            $table->string('organization');
            $table->string('sector');
            $table->string('contact_name');
            $table->string('position');
            $table->string('email');
            $table->string('phone');
            $table->string('country');

            // Type de partenariat
            $table->json('partnership_types');
            $table->string('partnership_other')->nullable();

            // Projet de collaboration
            $table->text('collaboration_project');

            // Budget indicatif
            $table->enum('budget', [
                'less_5000',
                '5000_20000',
                'more_20000',
                'discuss',
            ])->nullable();

            // Informations complémentaires
            $table->string('website')->nullable();

            // Comment le partenaire a connu GP
            $table->enum('discovery_source', [
                'social_media',
                'word_of_mouth',
                'gp_event',
                'other',
            ]);

            $table->string('discovery_other')->nullable();

            // Gestion administrative de la demande
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
        Schema::dropIfExists('partner_applications');
    }
};
