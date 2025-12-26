<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('feature_key'); // e.g., 'laundry_qc', 'pos_system', 'storefront'
            $table->string('feature_name'); // Display name
            $table->text('feature_description')->nullable();
            $table->boolean('is_enabled')->default(true);
            $table->integer('usage_limit')->nullable(); // e.g., max 100 orders per month
            $table->timestamps();

            $table->unique(['plan_id', 'feature_key']);
        });

        // Feature Access Logs (for audit)
        Schema::create('feature_access_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('business_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('feature_key');
            $table->enum('action', ['allowed', 'denied'])->default('allowed');
            $table->string('reason')->nullable(); // Why denied
            $table->timestamp('accessed_at');
            $table->timestamps();

            $table->index(['user_id', 'accessed_at']);
            $table->index(['business_id', 'feature_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_access_logs');
        Schema::dropIfExists('plan_features');
    }
};


