<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // le membre propriétaire du thread
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete(); // qui a écrit ce message (membre ou admin)
            $table->boolean('is_from_admin')->default(false);
            $table->text('content');
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('read_by_admin_at')->nullable();
            $table->timestamp('read_by_member_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
