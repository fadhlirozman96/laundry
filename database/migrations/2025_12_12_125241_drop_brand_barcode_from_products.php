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
        Schema::table('products', function (Blueprint $table) {
            // Check if foreign key exists before dropping
            if (Schema::hasColumn('products', 'brand_id')) {
                // Drop foreign key constraint first
                $table->dropForeign(['brand_id']);
                // Drop columns
                $table->dropColumn(['brand_id', 'barcode']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Re-add columns
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->string('barcode')->nullable();
        });
    }
};
