<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laundry_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laundry_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('garment_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('garment_name'); // Name/description
            $table->string('garment_code')->nullable(); // Unique code for tracking
            $table->integer('quantity')->default(1);
            $table->string('color')->nullable();
            $table->string('brand')->nullable();
            $table->text('condition_notes')->nullable(); // Notes about condition when received
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->enum('status', ['pending', 'processing', 'completed', 'issue'])->default('pending');
            $table->text('issue_notes')->nullable(); // If there's an issue
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laundry_order_items');
    }
};

