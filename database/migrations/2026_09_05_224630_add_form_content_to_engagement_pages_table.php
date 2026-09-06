<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('engagement_pages', function (Blueprint $table) {
            $table->json('form_content')->nullable()->after('cta_label');
        });
    }

    public function down(): void
    {
        Schema::table('engagement_pages', function (Blueprint $table) {
            $table->dropColumn('form_content');
        });
    }
};
