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
            | Banner
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'banner_video')) {
                $table->string('banner_video')->nullable();
            }
            if (!Schema::hasColumn('pages', 'banner_poster')) {
                $table->string('banner_poster')->nullable();
            }
            if (!Schema::hasColumn('pages', 'banner_video_enabled')) {
                $table->boolean('banner_video_enabled')->default(false);
            }


            /*
            |--------------------------------------------------------------------------
            | Notre histoire
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'story_eyebrow')) {
                $table->string('story_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'story_title')) {
                $table->string('story_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'story_text')) {
                $table->longText('story_text')->nullable();
            }
            if (!Schema::hasColumn('pages', 'story_image')) {
                $table->string('story_image')->nullable();
            }
            if (!Schema::hasColumn('pages', 'story_card_title')) {
                $table->string('story_card_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'story_card_text')) {
                $table->string('story_card_text')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | Fondatrice
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'founder_eyebrow')) {
                $table->string('founder_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'founder_title')) {
                $table->string('founder_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'founder_text')) {
                $table->longText('founder_text')->nullable();
            }
            if (!Schema::hasColumn('pages', 'founder_button_text')) {
                $table->string('founder_button_text')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | Valeurs — section
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'values_eyebrow')) {
                $table->string('values_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'values_title')) {
                $table->string('values_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'values_intro')) {
                $table->text('values_intro')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | Valeur 1
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'value_1_title')) {
                $table->string('value_1_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'value_1_short')) {
                $table->string('value_1_short')->nullable();
            }
            if (!Schema::hasColumn('pages', 'value_1_text')) {
                $table->text('value_1_text')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | Valeur 2
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'value_2_title')) {
                $table->string('value_2_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'value_2_short')) {
                $table->string('value_2_short')->nullable();
            }
            if (!Schema::hasColumn('pages', 'value_2_text')) {
                $table->text('value_2_text')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | Valeur 3
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'value_3_title')) {
                $table->string('value_3_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'value_3_short')) {
                $table->string('value_3_short')->nullable();
            }
            if (!Schema::hasColumn('pages', 'value_3_text')) {
                $table->text('value_3_text')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | Équipe
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'team_eyebrow')) {
                $table->string('team_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'team_title')) {
                $table->string('team_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'team_intro')) {
                $table->text('team_intro')->nullable();
            }


            /*
            |--------------------------------------------------------------------------
            | CTA final
            |--------------------------------------------------------------------------
            */

            if (!Schema::hasColumn('pages', 'cta_eyebrow')) {
                $table->string('cta_eyebrow')->nullable();
            }
            if (!Schema::hasColumn('pages', 'cta_title')) {
                $table->string('cta_title')->nullable();
            }
            if (!Schema::hasColumn('pages', 'cta_highlight')) {
                $table->string('cta_highlight')->nullable();
            }
            if (!Schema::hasColumn('pages', 'cta_text')) {
                $table->text('cta_text')->nullable();
            }
            if (!Schema::hasColumn('pages', 'cta_button_text')) {
                $table->string('cta_button_text')->nullable();
            }
            if (!Schema::hasColumn('pages', 'cta_button_url')) {
                $table->string('cta_button_url')->nullable();
            }
        });
    }


    public function down(): void
    {
        Schema::table('pages', function (Blueprint $table) {

            $columns = [
                'banner_video',
                'banner_poster',
                'banner_video_enabled',

                'story_eyebrow',
                'story_title',
                'story_text',
                'story_image',
                'story_card_title',
                'story_card_text',

                'founder_eyebrow',
                'founder_title',
                'founder_text',
                'founder_button_text',

                'values_eyebrow',
                'values_title',
                'values_intro',

                'value_1_title',
                'value_1_short',
                'value_1_text',

                'value_2_title',
                'value_2_short',
                'value_2_text',

                'value_3_title',
                'value_3_short',
                'value_3_text',

                'team_eyebrow',
                'team_title',
                'team_intro',

                'cta_eyebrow',
                'cta_title',
                'cta_highlight',
                'cta_text',
                'cta_button_text',
                'cta_button_url',
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