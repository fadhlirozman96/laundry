<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('account_owner_id')->constrained()->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->after('department_id')->constrained()->nullOnDelete();
            $table->foreignId('shift_id')->nullable()->after('designation_id')->constrained()->nullOnDelete();
            $table->string('employee_id')->nullable()->after('shift_id');
            $table->date('joining_date')->nullable()->after('employee_id');
            $table->decimal('salary', 12, 2)->nullable()->after('joining_date');
            $table->string('phone')->nullable()->after('salary');
            $table->text('address')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['designation_id']);
            $table->dropForeign(['shift_id']);
            $table->dropColumn(['department_id', 'designation_id', 'shift_id', 'employee_id', 'joining_date', 'salary', 'phone', 'address']);
        });
    }
};
