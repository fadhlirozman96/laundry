<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // QC completion tracking
            $table->boolean('qc_completed')->default(false)->after('subtotal');
            $table->json('qc_data')->nullable()->after('qc_completed');
            // For count-based: {"counted": 5}
            // For set-based: {"components": ["bedsheet", "pillowcase"]}
            // For integrity: {"integrity_confirmed": true}
            // For identity: {"identity_confirmed": true}
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['qc_completed', 'qc_data']);
        });
    }
};


