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
        // This migration is now replaced by create_user_roles_table migration
        // Just remove the role column if it exists (role_id will be handled by user_roles table)
        
        // Drop role_id column if it exists (from previous migration attempts)
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                if (DB::getDriverName() !== 'sqlite') {
                    $table->dropForeign(['role_id']);
                }
                $table->dropColumn('role_id');
            });
        }

        // Drop the old role column if it exists
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add role column back (for rollback compatibility)
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('staff')->after('password');
            });
        }

        // Migrate from user_roles table back to role column
        if (Schema::hasTable('user_roles')) {
            $roles = DB::table('roles')->pluck('name', 'id')->toArray();
            $userRoles = DB::table('user_roles')->get();
            
            foreach ($userRoles as $userRole) {
                if (isset($roles[$userRole->role_id])) {
                    DB::table('users')
                        ->where('id', $userRole->user_id)
                        ->update(['role' => $roles[$userRole->role_id]]);
                }
            }
        }
    }
};

