<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add role_id column
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('id');
        });

        // Migrate existing role strings to role_id
        $roles = DB::table('roles')->pluck('id', 'name')->toArray();
        
        foreach ($roles as $roleName => $roleId) {
            DB::table('role_permissions')
                ->where('role', $roleName)
                ->update(['role_id' => $roleId]);
        }

        // Make role_id required and add foreign key, then drop old role column
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable(false)->change();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->dropUnique(['role', 'permission_id']);
            $table->dropColumn('role');
            $table->unique(['role_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add role column back
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->string('role')->after('id');
        });

        // Migrate role_id back to role strings
        $roles = DB::table('roles')->pluck('name', 'id')->toArray();
        
        foreach ($roles as $roleId => $roleName) {
            DB::table('role_permissions')
                ->where('role_id', $roleId)
                ->update(['role' => $roleName]);
        }

        // Drop role_id
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropUnique(['role_id', 'permission_id']);
            $table->dropColumn('role_id');
            $table->unique(['role', 'permission_id']);
        });
    }
};


