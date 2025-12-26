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
        Schema::table('users', function (Blueprint $table) {
            $table->string('company_name')->nullable()->after('name');
            $table->string('company_address')->nullable()->after('company_name');
            $table->string('company_phone')->nullable()->after('company_address');
            $table->foreignId('current_plan_id')->nullable()->constrained('plans')->nullOnDelete()->after('company_phone');
            $table->timestamp('plan_expires_at')->nullable()->after('current_plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_plan_id']);
            $table->dropColumn(['company_name', 'company_address', 'company_phone', 'current_plan_id', 'plan_expires_at']);
        });
    }
};

