<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machine_usage_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained()->cascadeOnDelete();
            $table->foreignId('laundry_order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // staff who operated
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            
            // Load details
            $table->decimal('load_weight_kg', 5, 2)->nullable();
            $table->integer('items_count')->default(0);
            $table->boolean('overload_warning')->default(false);
            
            // Time tracking
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('set_duration_minutes')->nullable(); // SOP duration
            
            // Settings
            $table->string('wash_type')->nullable(); // normal, delicate, heavy duty
            $table->string('temperature')->nullable(); // cold, warm, hot
            $table->string('spin_speed')->nullable(); // low, medium, high
            
            // Status
            $table->enum('status', ['running', 'completed', 'interrupted', 'error'])->default('running');
            $table->text('notes')->nullable();
            $table->text('issues')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machine_usage_logs');
    }
};




