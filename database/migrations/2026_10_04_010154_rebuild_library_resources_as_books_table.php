<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('library_resources', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | IDENTITÉ DU LIVRE
            |--------------------------------------------------------------------------
            */

            $table->string('slug')->nullable()->unique()->after('title');
            $table->string('author')->nullable()->after('slug');
            $table->string('subtitle')->nullable()->after('author');

            /*
            |--------------------------------------------------------------------------
            | VISUELS
            |--------------------------------------------------------------------------
            */

            $table->string('cover_image')->nullable()->after('description');
            $table->string('showcase_image')->nullable()->after('cover_image');

            /*
            |--------------------------------------------------------------------------
            | INFORMATIONS ÉDITORIALES
            |--------------------------------------------------------------------------
            */

            $table->string('isbn')->nullable()->after('showcase_image');
            $table->string('publisher')->nullable()->after('isbn');
            $table->date('publication_date')->nullable()->after('publisher');
            $table->unsignedInteger('pages')->nullable()->after('publication_date');

            /*
            |--------------------------------------------------------------------------
            | COMMERCIALISATION
            |--------------------------------------------------------------------------
            */

            $table->decimal('price', 10, 2)->nullable()->after('pages');
            $table->decimal('promotional_price', 10, 2)->nullable()->after('price');

            $table->enum('format', [
                'physical',
                'digital',
                'both',
            ])->default('physical')->after('promotional_price');

            $table->unsignedInteger('stock')->nullable()->after('format');

            /*
            |--------------------------------------------------------------------------
            | CONTENU DE LA PAGE LIVRE
            |--------------------------------------------------------------------------
            */

            $table->text('value_description')->nullable()->after('stock');
            $table->longText('discover_content')->nullable()->after('value_description');

            /*
            |--------------------------------------------------------------------------
            | MISE EN AVANT
            |--------------------------------------------------------------------------
            */

            $table->boolean('is_bestseller')->default(false)->after('discover_content');
            $table->boolean('show_on_podium')->default(false)->after('is_bestseller');

            $table->unsignedInteger('display_order')->default(0)->after('show_on_podium');
        });
    }

    public function down(): void
    {
        Schema::table('library_resources', function (Blueprint $table) {
            $table->dropUnique(['slug']);

            $table->dropColumn([
                'slug',
                'author',
                'subtitle',
                'cover_image',
                'showcase_image',
                'isbn',
                'publisher',
                'publication_date',
                'pages',
                'price',
                'promotional_price',
                'format',
                'stock',
                'value_description',
                'discover_content',
                'is_bestseller',
                'show_on_podium',
                'display_order',
            ]);
        });
    }
};
