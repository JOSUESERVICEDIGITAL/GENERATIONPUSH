<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
            $table->string('customer_email')->nullable()->after('customer_name');
            $table->string('customer_phone', 30)->nullable()->after('customer_email');
            $table->string('customer_country', 100)->nullable()->after('customer_phone');
            $table->string('customer_city', 100)->nullable()->after('customer_country');
            $table->string('customer_address', 500)->nullable()->after('customer_city');
            $table->text('notes')->nullable()->after('customer_address');

            $table->enum('delivery_status', [
                'not_required',
                'pending',
                'processing',
                'shipped',
                'delivered',
            ])->default('not_required')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'customer_name',
                'customer_email',
                'customer_phone',
                'customer_country',
                'customer_city',
                'customer_address',
                'notes',
                'delivery_status',
            ]);
        });
    }
};
