<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajouter les champs nécessaires aux QR codes des commandes.
     */
    public function up(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Supprimer l'ancien qr_token
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('orders', 'qr_token')) {
            Schema::table('orders', function (Blueprint $table) {
                try {
                    $table->dropUnique(['qr_token']);
                } catch (\Throwable $e) {
                    // L'index peut ne pas exister selon l'état de la base.
                }

                $table->dropColumn('qr_token');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Ajouter qr_content
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('orders', 'qr_content')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('qr_content')
                    ->nullable()
                    ->after('order_number');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Ajouter qr_code_path
        |--------------------------------------------------------------------------
        */

        if (!Schema::hasColumn('orders', 'qr_code_path')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('qr_code_path')
                    ->nullable()
                    ->after('qr_content');
            });
        }
    }

    /**
     * Annuler les changements.
     */
    public function down(): void
    {
        if (!Schema::hasTable('orders')) {
            return;
        }

        if (Schema::hasColumn('orders', 'qr_content')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('qr_content');
            });
        }

        if (Schema::hasColumn('orders', 'qr_code_path')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('qr_code_path');
            });
        }

        if (!Schema::hasColumn('orders', 'qr_token')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('qr_token', 64)
                    ->nullable()
                    ->unique()
                    ->after('order_number');
            });
        }
    }
};
