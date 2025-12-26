<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get super admin role
        $superAdminRole = Role::superAdmin();
        if (!$superAdminRole) {
            $this->command->error('Super Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Check if superadmin already exists
        $superAdmin = User::where('email', 'superadmin@gmail.com')->first();
        
        if (!$superAdmin) {
            $superAdmin = User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            // Assign super admin role via pivot table
            $superAdmin->roles()->attach($superAdminRole->id);
            
            $this->command->info('Super Admin user created successfully!');
            $this->command->info('Email: superadmin@gmail.com');
            $this->command->info('Password: password');
        } else {
            $this->command->warn('Super Admin user already exists!');
        }
    }
}





















