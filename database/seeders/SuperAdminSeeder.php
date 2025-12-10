<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if superadmin already exists
        $superAdmin = User::where('email', 'superadmin@gmail.com')->first();
        
        if (!$superAdmin) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@gmail.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            
            $this->command->info('Super Admin user created successfully!');
            $this->command->info('Email: superadmin@gmail.com');
            $this->command->info('Password: password');
        } else {
            $this->command->warn('Super Admin user already exists!');
        }
    }
}


