<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('garment_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // shirt, pants, blanket, etc.
            $table->string('category')->nullable(); // tops, bottoms, bedding, etc.
            $table->text('description')->nullable();
            $table->decimal('default_price', 10, 2)->default(0);
            $table->string('icon')->nullable(); // icon for display
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('garment_types');
    }
};


