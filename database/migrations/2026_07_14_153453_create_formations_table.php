<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trainer')->nullable();
            $table->string('duration')->nullable();
            $table->unsignedInteger('participants')->default(0);
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('status', ['active', 'draft', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formations');
    }
};
