<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->morphs('ticketable');
            $table->string('title');
            $table->decimal('price', 8, 2)->default(0);
            $table->unsignedInteger('quantity_total')->default(0);
            $table->unsignedInteger('quantity_sold')->default(0);
            $table->enum('status', ['active', 'sold_out', 'closed'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
