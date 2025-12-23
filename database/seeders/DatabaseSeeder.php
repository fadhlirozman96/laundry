<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        $this->call([
            RoleSeeder::class,            // Seed roles first
            PermissionSeeder::class,      // Seed permissions (depends on roles)
            TestAccountsSeeder::class,    // Seed test accounts and stores (depends on roles)
            SuperAdminSeeder::class,      // Keep existing seeder (optional)
            LaundryServiceSeeder::class,  // Seed laundry services (replaces ProductSeeder)
        ]);
    }
}
