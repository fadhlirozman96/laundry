<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            // 2. Status & Workflow Checks
            $table->boolean('status_wash_check')->default(false)->after('quantity_notes');
            $table->boolean('status_dry_check')->default(false);
            $table->boolean('status_iron_check')->default(false);
            $table->text('status_notes')->nullable();
            
            // 4. Dryness Checks
            $table->boolean('dryness_check')->default(false);
            $table->tinyInteger('dryness_rating')->nullable(); // 1-5
            $table->boolean('bulky_items_dry')->default(false);
            $table->text('dryness_notes')->nullable();
            
            // 5. Finishing/Ironing Checks (separate from folding)
            $table->boolean('ironing_check')->default(false);
            $table->tinyInteger('ironing_rating')->nullable(); // 1-5
            $table->boolean('no_wrinkles')->default(false);
            $table->text('ironing_notes')->nullable();
            
            // 7. Special Instructions Check
            $table->boolean('special_instructions_check')->default(false);
            $table->boolean('special_instructions_followed')->default(false);
            $table->text('special_instructions_notes')->nullable();
            
            // 8. Packaging & Labeling
            $table->boolean('packaging_check')->default(false);
            $table->boolean('label_correct')->default(false);
            $table->boolean('qr_tag_intact')->default(false);
            $table->text('packaging_notes')->nullable();
            
            // Enhanced damage check with photo support
            $table->text('damage_photos')->nullable(); // JSON array of photo paths
            
            // QC Officer info (already has user_id, but add confirmation)
            $table->boolean('final_approval')->default(false);
            $table->timestamp('approved_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->dropColumn([
                'status_wash_check',
                'status_dry_check',
                'status_iron_check',
                'status_notes',
                'dryness_check',
                'dryness_rating',
                'bulky_items_dry',
                'dryness_notes',
                'ironing_check',
                'ironing_rating',
                'no_wrinkles',
                'ironing_notes',
                'special_instructions_check',
                'special_instructions_followed',
                'special_instructions_notes',
                'packaging_check',
                'label_correct',
                'qr_tag_intact',
                'packaging_notes',
                'damage_photos',
                'final_approval',
                'approved_at',
            ]);
        });
    }
};


