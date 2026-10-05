<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->timestamp('read_by_member_at')
                ->nullable()
                ->after('is_from_admin');

            $table->timestamp('read_by_admin_at')
                ->nullable()
                ->after('read_by_member_at');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropColumn([
                'read_by_member_at',
                'read_by_admin_at',
            ]);
        });
    }
};
