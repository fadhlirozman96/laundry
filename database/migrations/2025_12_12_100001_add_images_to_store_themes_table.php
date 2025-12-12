<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('store_themes', function (Blueprint $table) {
            // Logo and Header Images
            $table->string('logo_image')->nullable()->after('newsletter_text');
            $table->string('favicon')->nullable()->after('logo_image');
            
            // Hero Section Images
            $table->string('hero_background_image')->nullable()->after('favicon');
            $table->string('hero_image_1')->nullable()->after('hero_background_image');
            $table->string('hero_image_2')->nullable()->after('hero_image_1');
            $table->string('hero_image_3')->nullable()->after('hero_image_2');
            
            // Section Images
            $table->string('category_showcase_image_1')->nullable()->after('hero_image_3');
            $table->string('category_showcase_image_2')->nullable()->after('category_showcase_image_1');
            $table->string('category_showcase_image_3')->nullable()->after('category_showcase_image_2');
            
            // Scent Card Background Images
            $table->string('scent_indulgent_image')->nullable()->after('category_showcase_image_3');
            $table->string('scent_beauty_sleep_image')->nullable()->after('scent_indulgent_image');
            $table->string('scent_classic_image')->nullable()->after('scent_beauty_sleep_image');
            $table->string('scent_marine_image')->nullable()->after('scent_classic_image');
            
            // Article/Blog Images
            $table->string('article_image_1')->nullable()->after('scent_marine_image');
            $table->string('article_image_2')->nullable()->after('article_image_1');
            $table->string('article_image_3')->nullable()->after('article_image_2');
            
            // Footer Images
            $table->string('footer_logo')->nullable()->after('article_image_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_themes', function (Blueprint $table) {
            $table->dropColumn([
                'logo_image',
                'favicon',
                'hero_background_image',
                'hero_image_1',
                'hero_image_2',
                'hero_image_3',
                'category_showcase_image_1',
                'category_showcase_image_2',
                'category_showcase_image_3',
                'scent_indulgent_image',
                'scent_beauty_sleep_image',
                'scent_classic_image',
                'scent_marine_image',
                'article_image_1',
                'article_image_2',
                'article_image_3',
                'footer_logo',
            ]);
        });
    }
};

