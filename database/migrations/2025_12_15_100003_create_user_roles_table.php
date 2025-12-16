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
        // First, ensure roles table exists (should be created by create_roles_table migration)
        if (!Schema::hasTable('roles')) {
            throw new \Exception('Roles table does not exist. Please run the create_roles_table migration first.');
        }

        // Ensure roles exist (insert if they don't exist)
        $rolesToCreate = [
            ['name' => 'super_admin', 'display_name' => 'Super Admin', 'description' => 'System-level administrator'],
            ['name' => 'business_owner', 'display_name' => 'Business Owner', 'description' => 'Tenant/Account owner'],
            ['name' => 'admin', 'display_name' => 'Store Admin', 'description' => 'Store manager'],
            ['name' => 'staff', 'display_name' => 'Store Staff', 'description' => 'Store employee'],
        ];

        foreach ($rolesToCreate as $role) {
            DB::table('roles')->insertOrIgnore([
                'name' => $role['name'],
                'display_name' => $role['display_name'],
                'description' => $role['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create user_roles pivot table
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('role_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            
            // Ensure one role per user (if you want to allow multiple roles, remove this unique constraint)
            $table->unique('user_id');
        });

        // Migrate existing role data from users table to user_roles table
        if (Schema::hasColumn('users', 'role')) {
            // If users table still has 'role' column (string), migrate it
            $roles = DB::table('roles')->pluck('id', 'name')->toArray();
            
            $users = DB::table('users')->whereNotNull('role')->get();
            foreach ($users as $user) {
                if (isset($roles[$user->role])) {
                    DB::table('user_roles')->insertOrIgnore([
                        'user_id' => $user->id,
                        'role_id' => $roles[$user->role],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } elseif (Schema::hasColumn('users', 'role_id')) {
            // If users table has 'role_id' column, migrate it
            $users = DB::table('users')->whereNotNull('role_id')->get();
            foreach ($users as $user) {
                DB::table('user_roles')->insertOrIgnore([
                    'user_id' => $user->id,
                    'role_id' => $user->role_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};



