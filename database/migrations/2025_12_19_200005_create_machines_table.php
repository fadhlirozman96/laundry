<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Machine name/number
            $table->string('code')->nullable(); // Machine code
            $table->enum('type', ['washer', 'dryer']);
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->decimal('capacity_kg', 5, 2)->default(0); // Load capacity in kg
            $table->integer('default_cycle_minutes')->default(30); // Default cycle time
            $table->enum('status', ['available', 'in_use', 'maintenance', 'out_of_order'])->default('available');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('machines');
    }
};



