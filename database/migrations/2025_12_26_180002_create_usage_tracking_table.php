<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usage_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('metric_key'); // 'stores', 'users', 'orders', 'storage_mb'
            $table->integer('current_value')->default(0);
            $table->integer('limit_value')->default(0);
            $table->integer('percentage_used')->default(0);
            $table->date('period_start');
            $table->date('period_end');
            $table->timestamps();

            $table->index(['business_id', 'metric_key', 'period_start']);
        });

        // Limit Alerts
        Schema::create('limit_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->onDelete('cascade');
            $table->string('metric_key');
            $table->integer('threshold_percentage'); // e.g., 80, 90, 100
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->string('alert_type'); // 'warning', 'critical', 'exceeded'
            $table->text('message');
            $table->timestamps();

            $table->index(['business_id', 'is_sent']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('limit_alerts');
        Schema::dropIfExists('usage_tracking');
    }
};


