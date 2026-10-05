<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Suppression de l'ancienne structure du chat
        |--------------------------------------------------------------------------
        |
        | L'ancien système utilisait :
        | - user_id
        | - read_by_admin_at
        | - read_by_member_at
        |
        | Le nouveau chat est communautaire :
        | - author_id = auteur du message
        | - chat_message_reads = suivi individuel des lectures
        |
        */

        if (Schema::hasColumn('chat_messages', 'user_id')) {
            Schema::table('chat_messages', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('chat_messages', function (Blueprint $table) {
            $columnsToDrop = [];

            if (Schema::hasColumn('chat_messages', 'user_id')) {
                $columnsToDrop[] = 'user_id';
            }

            if (Schema::hasColumn('chat_messages', 'read_by_admin_at')) {
                $columnsToDrop[] = 'read_by_admin_at';
            }

            if (Schema::hasColumn('chat_messages', 'read_by_member_at')) {
                $columnsToDrop[] = 'read_by_member_at';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    public function down(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Restauration de l'ancienne structure
        |--------------------------------------------------------------------------
        */

        Schema::table('chat_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('chat_messages', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('users')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('chat_messages', 'read_by_admin_at')) {
                $table->timestamp('read_by_admin_at')
                    ->nullable();
            }

            if (! Schema::hasColumn('chat_messages', 'read_by_member_at')) {
                $table->timestamp('read_by_member_at')
                    ->nullable();
            }
        });
    }
};
