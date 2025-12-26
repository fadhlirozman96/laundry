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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('active'); // active, canceled, expired, trial, suspended
            $table->date('trial_ends_at')->nullable();
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->date('canceled_at')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('billing_cycle')->default('monthly'); // monthly, annual
            $table->date('next_billing_date')->nullable();
            $table->json('metadata')->nullable(); // Store additional info
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};


