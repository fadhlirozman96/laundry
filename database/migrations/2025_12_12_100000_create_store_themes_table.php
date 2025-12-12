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
        Schema::create('store_themes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->onDelete('cascade');
            
            // Colors
            $table->string('primary_color')->default('#1a2a2a');
            $table->string('secondary_color')->default('#f5f5f0');
            $table->string('text_color')->default('#1a2a2a');
            $table->string('background_color')->default('#ffffff');
            $table->string('accent_color')->default('#1a2a2a');
            
            // Typography
            $table->string('heading_font')->default('Playfair Display');
            $table->string('body_font')->default('Inter');
            $table->integer('heading_size')->default(36);
            $table->integer('body_size')->default(16);
            
            // Content/Text
            $table->text('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->text('promo_banner_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('newsletter_text')->nullable();
            
            // Custom CSS
            $table->text('custom_css')->nullable();
            
            // Layout Settings
            $table->json('layout_settings')->nullable(); // For storing arrangement settings
            
            $table->timestamps();
            
            // Ensure one theme per store
            $table->unique('store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_themes');
    }
};

