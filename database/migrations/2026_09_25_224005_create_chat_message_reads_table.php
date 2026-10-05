<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_message_reads', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Message concerné
            |--------------------------------------------------------------------------
            */
            $table->foreignId('chat_message_id')
                ->constrained('chat_messages')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Membre ayant lu le message
            |--------------------------------------------------------------------------
            |
            | On utilise users car l'admin est également un User.
            |
            */
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Date de lecture
            |--------------------------------------------------------------------------
            */
            $table->timestamp('read_at');

            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | Un utilisateur ne peut avoir qu'une seule lecture
            | pour un même message.
            |--------------------------------------------------------------------------
            */
            $table->unique([
                'chat_message_id',
                'user_id',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Index
            |--------------------------------------------------------------------------
            */
            $table->index('user_id');
            $table->index('read_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_message_reads');
    }
};
