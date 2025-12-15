<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating sample users for all roles...');

        // Get roles
        $superAdminRole = Role::superAdmin();
        $businessOwnerRole = Role::businessOwner();
        $adminRole = Role::admin();
        $staffRole = Role::staff();

        if (!$superAdminRole || !$businessOwnerRole || !$adminRole || !$staffRole) {
            $this->command->error('❌ Roles not found. Please run RoleSeeder first.');
            return;
        }

        // 1. Create Super Admin
        $superAdmin = User::create([
            'name' => 'System Administrator',
            'email' => 'superadmin@dreampos.com',
            'password' => Hash::make('superadmin123'),
            'account_owner_id' => null,
        ]);
        $superAdmin->roles()->attach($superAdminRole->id);
        $this->command->info("✅ Super Admin created: {$superAdmin->email} / superadmin123");

        // 2. Create Business Owner A
        $businessOwnerA = User::create([
            'name' => 'John Business Owner',
            'email' => 'owner.john@business.com',
            'password' => Hash::make('owner123'),
            'account_owner_id' => null,
        ]);
        $businessOwnerA->roles()->attach($businessOwnerRole->id);
        $this->command->info("✅ Business Owner A created: {$businessOwnerA->email} / owner123");

        // Create stores for Business Owner A
        $storeA1 = Store::create([
            'name' => 'John\'s Restaurant Downtown',
            'slug' => 'johns-restaurant-downtown',
            'email' => 'downtown@johns.com',
            'phone' => '+1234567890',
            'address' => '123 Downtown Street, City A',
            'created_by' => $businessOwnerA->id,
            'is_active' => true,
        ]);

        $storeA2 = Store::create([
            'name' => 'John\'s Restaurant Mall',
            'slug' => 'johns-restaurant-mall',
            'email' => 'mall@johns.com',
            'phone' => '+1234567891',
            'address' => '456 Mall Avenue, City A',
            'created_by' => $businessOwnerA->id,
            'is_active' => true,
        ]);
        $this->command->info("✅ Created 2 stores for Business Owner A");

        // Create Store Admin for Store A1
        $adminA1 = User::create([
            'name' => 'Mike Manager',
            'email' => 'mike.manager@johns.com',
            'password' => Hash::make('admin123'),
            'account_owner_id' => $businessOwnerA->id,
        ]);
        $adminA1->roles()->attach($adminRole->id);
        $storeA1->users()->attach($adminA1->id);
        $this->command->info("✅ Store Admin created for Downtown: {$adminA1->email} / admin123");

        // Create Staff for Store A1
        $staffA1 = User::create([
            'name' => 'Sarah Cashier',
            'email' => 'sarah.cashier@johns.com',
            'password' => Hash::make('staff123'),
            'account_owner_id' => $businessOwnerA->id,
        ]);
        $staffA1->roles()->attach($staffRole->id);
        $storeA1->users()->attach($staffA1->id);
        $this->command->info("✅ Store Staff created for Downtown: {$staffA1->email} / staff123");

        // Create Staff for Store A2
        $staffA2 = User::create([
            'name' => 'Tom Sales',
            'email' => 'tom.sales@johns.com',
            'password' => Hash::make('staff123'),
            'account_owner_id' => $businessOwnerA->id,
        ]);
        $staffA2->roles()->attach($staffRole->id);
        $storeA2->users()->attach($staffA2->id);
        $this->command->info("✅ Store Staff created for Mall: {$staffA2->email} / staff123");

        // 3. Create Business Owner B
        $businessOwnerB = User::create([
            'name' => 'Emma Retail Owner',
            'email' => 'owner.emma@retail.com',
            'password' => Hash::make('owner123'),
            'account_owner_id' => null,
        ]);
        $businessOwnerB->roles()->attach($businessOwnerRole->id);
        $this->command->info("✅ Business Owner B created: {$businessOwnerB->email} / owner123");

        // Create stores for Business Owner B
        $storeB1 = Store::create([
            'name' => 'Emma\'s Boutique Central',
            'slug' => 'emmas-boutique-central',
            'email' => 'central@emmas.com',
            'phone' => '+1234567892',
            'address' => '789 Central Road, City B',
            'created_by' => $businessOwnerB->id,
            'is_active' => true,
        ]);
        $this->command->info("✅ Created 1 store for Business Owner B");

        // Create Store Admin for Store B1
        $adminB1 = User::create([
            'name' => 'Lisa Manager',
            'email' => 'lisa.manager@emmas.com',
            'password' => Hash::make('admin123'),
            'account_owner_id' => $businessOwnerB->id,
        ]);
        $adminB1->roles()->attach($adminRole->id);
        $storeB1->users()->attach($adminB1->id);
        $this->command->info("✅ Store Admin created for Central: {$adminB1->email} / admin123");

        // Create Staff for Store B1
        $staffB1 = User::create([
            'name' => 'Bob Cashier',
            'email' => 'bob.cashier@emmas.com',
            'password' => Hash::make('staff123'),
            'account_owner_id' => $businessOwnerB->id,
        ]);
        $staffB1->roles()->attach($staffRole->id);
        $storeB1->users()->attach($staffB1->id);
        $this->command->info("✅ Store Staff created for Central: {$staffB1->email} / staff123");

        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('📋 SAMPLE ACCOUNTS SUMMARY');
        $this->command->info('═══════════════════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('🔑 SUPER ADMIN (System Administrator)');
        $this->command->info('   Email: superadmin@dreampos.com');
        $this->command->info('   Password: superadmin123');
        $this->command->info('   Access: ALL stores from ALL business owners');
        $this->command->info('');
        $this->command->info('👔 BUSINESS OWNER A (John)');
        $this->command->info('   Email: owner.john@business.com');
        $this->command->info('   Password: owner123');
        $this->command->info('   Stores: John\'s Restaurant Downtown, John\'s Restaurant Mall');
        $this->command->info('');
        $this->command->info('   📌 Store Admin (Downtown)');
        $this->command->info('      Email: mike.manager@johns.com');
        $this->command->info('      Password: admin123');
        $this->command->info('');
        $this->command->info('   📌 Staff (Downtown)');
        $this->command->info('      Email: sarah.cashier@johns.com');
        $this->command->info('      Password: staff123');
        $this->command->info('');
        $this->command->info('   📌 Staff (Mall)');
        $this->command->info('      Email: tom.sales@johns.com');
        $this->command->info('      Password: staff123');
        $this->command->info('');
        $this->command->info('👔 BUSINESS OWNER B (Emma)');
        $this->command->info('   Email: owner.emma@retail.com');
        $this->command->info('   Password: owner123');
        $this->command->info('   Stores: Emma\'s Boutique Central');
        $this->command->info('');
        $this->command->info('   📌 Store Admin (Central)');
        $this->command->info('      Email: lisa.manager@emmas.com');
        $this->command->info('      Password: admin123');
        $this->command->info('');
        $this->command->info('   📌 Staff (Central)');
        $this->command->info('      Email: bob.cashier@emmas.com');
        $this->command->info('      Password: staff123');
        $this->command->info('');
        $this->command->info('═══════════════════════════════════════════════════════');
    }
}

