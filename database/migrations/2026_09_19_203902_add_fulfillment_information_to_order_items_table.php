<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('fulfillment_type', [
                'physical',
                'digital',
            ])->default('physical')->after('quantity');

            $table->string('digital_file_path')->nullable()->after('fulfillment_type');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'fulfillment_type',
                'digital_file_path',
            ]);
        });
    }
};
