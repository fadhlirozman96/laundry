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
        Schema::table('laundry_order_items', function (Blueprint $table) {
            // Add service_id if not exists
            if (!Schema::hasColumn('laundry_order_items', 'service_id')) {
                $table->foreignId('service_id')->nullable()->after('laundry_order_id')->constrained('products')->nullOnDelete();
            }
            
            // Add service_name if not exists (nullable because garment_name already exists)
            if (!Schema::hasColumn('laundry_order_items', 'service_name')) {
                $table->string('service_name')->nullable()->after('service_id');
            }
            
            // Add garment_name if not exists (for backward compatibility)
            if (!Schema::hasColumn('laundry_order_items', 'garment_name')) {
                $table->string('garment_name')->nullable()->after('service_id');
            }
            
            // Add item_code if not exists
            if (!Schema::hasColumn('laundry_order_items', 'item_code')) {
                $table->string('item_code')->nullable()->after('service_name');
            }
            
            // Add quantity if not exists
            if (!Schema::hasColumn('laundry_order_items', 'quantity')) {
                $table->integer('quantity')->default(1)->after('item_code');
            }
            
            // Add color if not exists
            if (!Schema::hasColumn('laundry_order_items', 'color')) {
                $table->string('color')->nullable()->after('quantity');
            }
            
            // Add brand if not exists
            if (!Schema::hasColumn('laundry_order_items', 'brand')) {
                $table->string('brand')->nullable()->after('color');
            }
            
            // Add condition_notes if not exists
            if (!Schema::hasColumn('laundry_order_items', 'condition_notes')) {
                $table->text('condition_notes')->nullable()->after('brand');
            }
            
            // Add price if not exists
            if (!Schema::hasColumn('laundry_order_items', 'price')) {
                $table->decimal('price', 10, 2)->default(0)->after('condition_notes');
            }
            
            // Add subtotal if not exists
            if (!Schema::hasColumn('laundry_order_items', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('price');
            }
            
            // Add status if not exists (check if it's enum or string)
            if (!Schema::hasColumn('laundry_order_items', 'status')) {
                $table->enum('status', ['pending', 'processing', 'completed', 'issue'])->default('pending')->after('subtotal');
            }
            
            // Add issue_notes if not exists
            if (!Schema::hasColumn('laundry_order_items', 'issue_notes')) {
                $table->text('issue_notes')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laundry_order_items', function (Blueprint $table) {
            $columns = ['service_id', 'service_name', 'item_code', 'quantity', 'color', 'brand', 
                       'condition_notes', 'price', 'subtotal', 'status', 'issue_notes'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('laundry_order_items', $column)) {
                    if ($column === 'service_id') {
                        $table->dropForeign(['service_id']);
                    }
                    $table->dropColumn($column);
                }
            }
        });
    }
};

