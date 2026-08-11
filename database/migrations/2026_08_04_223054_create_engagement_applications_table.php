<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('engagement_applications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['partner', 'volunteer']);
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('organization')->nullable(); // structure/entreprise (surtout partenaires)
            $table->text('message');
            $table->enum('status', ['new', 'reviewed', 'accepted', 'rejected'])->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('engagement_applications');
    }
};
