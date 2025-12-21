<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            
            // Who performed the action
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable(); // Store name in case user is deleted
            $table->string('user_role')->nullable();
            
            // Store context (for filtering by business owner)
            $table->foreignId('store_id')->nullable()->constrained()->nullOnDelete();
            $table->string('store_name')->nullable();
            
            // What action was performed
            $table->enum('action', ['create', 'read', 'update', 'delete', 'login', 'logout', 'export', 'import', 'other']);
            $table->string('action_label')->nullable(); // Human readable action
            
            // What was affected
            $table->string('model_type')->nullable(); // e.g., App\Models\LaundryOrder
            $table->string('model_name')->nullable(); // Human readable, e.g., "Laundry Order"
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_identifier')->nullable(); // e.g., Order number, customer name
            
            // Details
            $table->string('description'); // Brief description of the action
            $table->json('old_values')->nullable(); // Previous values for updates
            $table->json('new_values')->nullable(); // New values for creates/updates
            $table->json('changed_fields')->nullable(); // List of fields that changed
            
            // Context
            $table->string('module')->nullable(); // e.g., laundry, hrm, pos, inventory
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            
            // Additional metadata
            $table->json('metadata')->nullable(); // Any additional context
            
            $table->timestamps();
            
            // Indexes for faster querying
            $table->index(['user_id', 'created_at']);
            $table->index(['store_id', 'created_at']);
            $table->index(['model_type', 'model_id']);
            $table->index(['action', 'created_at']);
            $table->index('module');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};


