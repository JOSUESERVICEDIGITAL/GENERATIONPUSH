<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->string('label');
            $table->string('url');
            $table->enum('location', ['navbar', 'footer']);
            $table->string('footer_column')->nullable(); // ex: "Liens rapides", "Ressources" (pour grouper en colonnes)
            $table->boolean('open_in_new_tab')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
