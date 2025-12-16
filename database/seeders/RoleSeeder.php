<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'System-level administrator with access to all data',
            ],
            [
                'name' => 'business_owner',
                'display_name' => 'Business Owner',
                'description' => 'Tenant/Account owner who creates and manages stores',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Store Admin',
                'description' => 'Store manager with full store management permissions',
            ],
            [
                'name' => 'staff',
                'display_name' => 'Store Staff',
                'description' => 'Store employee with limited permissions',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                $role
            );
        }

        $this->command->info('✅ Roles created successfully!');
    }
}



