<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->string('business_slug')->unique();
            $table->string('business_email')->nullable();
            $table->string('business_phone')->nullable();
            $table->text('business_address')->nullable();
            $table->string('business_city')->nullable();
            $table->string('business_state')->nullable();
            $table->string('business_country')->default('Malaysia');
            $table->string('business_postcode')->nullable();
            
            // Business Registration
            $table->string('registration_number')->nullable();
            $table->string('tax_number')->nullable();
            
            // Subscription Owner
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            
            // Billing Owner (can be different from owner)
            $table->foreignId('billing_owner_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Business Status
            $table->enum('status', ['active', 'suspended', 'cancelled', 'trial'])->default('trial');
            $table->timestamp('suspended_at')->nullable();
            $table->text('suspension_reason')->nullable();
            
            // Subscription Link
            $table->foreignId('current_subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();
            
            // Branding
            $table->string('logo_path')->nullable();
            $table->string('primary_color')->default('#667eea');
            $table->string('secondary_color')->default('#764ba2');
            
            // Limits (from plan)
            $table->integer('max_stores')->default(1);
            $table->integer('max_users_per_store')->default(5);
            $table->integer('current_store_count')->default(0);
            
            // Trial
            $table->timestamp('trial_ends_at')->nullable();
            $table->boolean('trial_used')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};


