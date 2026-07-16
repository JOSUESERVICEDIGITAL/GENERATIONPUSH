<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->enum('type', ['book', 'video', 'usb_key'])->default('book');
            $table->boolean('is_free')->default(false);
            $table->decimal('price', 8, 2)->default(0);
            $table->string('image_path')->nullable();
            $table->string('file_path')->nullable();
            $table->string('video_url')->nullable();
            $table->unsignedInteger('stock')->nullable();
            $table->unsignedInteger('sales')->default(0);
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
