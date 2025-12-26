<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // subscription_renewal, payment_failed, grace_period, etc.
            $table->string('title');
            $table->text('message');
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            $table->string('priority')->default('normal'); // low, normal, high, urgent
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'is_read']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

