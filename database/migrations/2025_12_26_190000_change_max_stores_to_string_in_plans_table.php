<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('max_stores', 20)->default('1')->change();
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->integer('max_stores')->default(1)->change();
        });
    }
};


