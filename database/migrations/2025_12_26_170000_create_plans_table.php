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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Standard, Pro
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // Monthly price
            $table->decimal('annual_price', 10, 2)->nullable(); // Annual price (if different)
            $table->integer('max_stores')->default(1); // -1 for unlimited
            $table->string('qc_level')->default('basic'); // basic, full, full_sop
            $table->boolean('has_sop_module')->default(false);
            $table->string('audit_trail_level')->default('basic'); // basic, full, advanced
            $table->boolean('has_store_switcher')->default(false);
            $table->boolean('has_all_stores_view')->default(false);
            $table->json('features')->nullable(); // Additional features as JSON
            $table->boolean('is_active')->default(true);
            $table->integer('trial_days')->default(14);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

