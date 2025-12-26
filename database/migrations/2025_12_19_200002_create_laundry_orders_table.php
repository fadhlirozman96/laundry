<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // staff who created
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->unique();
            $table->string('qr_code')->unique(); // unique QR code
            $table->string('customer_name');
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            
            // Status: received, washing, drying, folding, ready, collected
            $table->enum('status', ['received', 'washing', 'drying', 'folding', 'ready', 'collected'])->default('received');
            
            // Pricing
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            
            // Tracking
            $table->integer('total_items')->default(0);
            $table->integer('total_services')->default(0);
            
            // Dates
            $table->timestamp('received_at')->nullable();
            $table->timestamp('washing_at')->nullable();
            $table->timestamp('drying_at')->nullable();
            $table->timestamp('folding_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->timestamp('expected_completion')->nullable();
            
            // QC
            $table->boolean('qc_passed')->default(false);
            $table->foreignId('qc_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('qc_at')->nullable();
            
            // Payment
            $table->enum('payment_status', ['pending', 'partial', 'paid'])->default('pending');
            $table->decimal('amount_paid', 10, 2)->default(0);
            
            $table->text('notes')->nullable();
            $table->text('special_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_orders');
    }
};



