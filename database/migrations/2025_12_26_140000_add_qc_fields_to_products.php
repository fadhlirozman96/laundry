<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Unit type: piece, set, kg, sqft
            $table->enum('unit_type', ['piece', 'set', 'kg', 'sqft'])->default('piece')->after('unit_id');
            
            // QC mode: count, completeness, integrity, identity
            $table->enum('qc_mode', ['count', 'completeness', 'integrity', 'identity'])->default('count')->after('unit_type');
            
            // For set-based items: JSON array of components
            $table->json('set_components')->nullable()->after('qc_mode');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['unit_type', 'qc_mode', 'set_components']);
        });
    }
};

