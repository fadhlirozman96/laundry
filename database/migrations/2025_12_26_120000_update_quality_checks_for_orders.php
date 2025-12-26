<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            // Drop the old foreign key constraint if it exists
            $table->dropForeign(['laundry_order_id']);
            
            // Rename the column
            $table->renameColumn('laundry_order_id', 'order_id');
        });
        
        // Add the new foreign key constraint
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->renameColumn('order_id', 'laundry_order_id');
        });
        
        Schema::table('quality_checks', function (Blueprint $table) {
            $table->foreign('laundry_order_id')->references('id')->on('laundry_orders')->cascadeOnDelete();
        });
    }
};

