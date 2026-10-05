<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Hero Boutique
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'shop_hero_eyebrow')) {
                $table->string('shop_hero_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_hero_title')) {
                $table->string('shop_hero_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_hero_highlight')) {
                $table->string('shop_hero_highlight')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_hero_text')) {
                $table->text('shop_hero_text')->nullable();
            }

            /*
            |--------------------------------------------------------------------------
            | Catalogue
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'shop_catalogue_eyebrow')) {
                $table->string('shop_catalogue_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_catalogue_title')) {
                $table->string('shop_catalogue_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_catalogue_highlight')) {
                $table->string('shop_catalogue_highlight')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_catalogue_text')) {
                $table->text('shop_catalogue_text')->nullable();
            }

            /*
            |--------------------------------------------------------------------------
            | CTA final
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'shop_cta_eyebrow')) {
                $table->string('shop_cta_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_cta_title')) {
                $table->string('shop_cta_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_cta_highlight')) {
                $table->string('shop_cta_highlight')->nullable();
            }
            if (!Schema::hasColumn('pages', 'shop_cta_text')) {
                $table->text('shop_cta_text')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {

            $columns = [
                'shop_hero_eyebrow',
                'shop_hero_title',
                'shop_hero_highlight',
                'shop_hero_text',

                'shop_catalogue_eyebrow',
                'shop_catalogue_title',
                'shop_catalogue_highlight',
                'shop_catalogue_text',

                'shop_cta_eyebrow',
                'shop_cta_title',
                'shop_cta_highlight',
                'shop_cta_text',
            ];

            // Filtrer uniquement les colonnes qui existent réellement
            $existingColumns = array_filter($columns, function ($column) {
                return Schema::hasColumn('pages', $column);
            });

            if (!empty($existingColumns)) {
                $table->dropColumn(array_values($existingColumns));
            }
        });
    }
};