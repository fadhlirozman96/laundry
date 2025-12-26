<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // QC inspector
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            
            // Checklist items (1-5 rating or pass/fail)
            $table->boolean('cleanliness_check')->default(false);
            $table->tinyInteger('cleanliness_rating')->nullable(); // 1-5
            $table->text('cleanliness_notes')->nullable();
            
            $table->boolean('odour_check')->default(false);
            $table->tinyInteger('odour_rating')->nullable(); // 1-5
            $table->text('odour_notes')->nullable();
            
            $table->boolean('quantity_check')->default(false);
            $table->integer('items_received')->default(0);
            $table->integer('items_counted')->default(0);
            $table->boolean('quantity_match')->default(false);
            $table->text('quantity_notes')->nullable();
            
            $table->boolean('folding_check')->default(false);
            $table->tinyInteger('folding_rating')->nullable(); // 1-5
            $table->text('folding_notes')->nullable();
            
            // Additional checks
            $table->boolean('stain_check')->default(false);
            $table->text('stain_notes')->nullable();
            
            $table->boolean('damage_check')->default(false);
            $table->text('damage_notes')->nullable();
            
            // Overall
            $table->boolean('passed')->default(false);
            $table->text('overall_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_checks');
    }
};




