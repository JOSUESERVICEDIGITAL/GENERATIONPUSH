<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('contacted_at')
                ->nullable()
                ->after('delivery_status');

            $table->timestamp('confirmed_at')
                ->nullable()
                ->after('contacted_at');

            $table->text('admin_notes')
                ->nullable()
                ->after('confirmed_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'contacted_at',
                'confirmed_at',
                'admin_notes',
            ]);
        });
    }
};
