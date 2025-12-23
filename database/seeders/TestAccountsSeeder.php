<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TestAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates all test accounts, stores, and assignments as specified in TEST_ACCOUNTS.md
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding test accounts and stores...');

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
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@dreampos.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@dreampos.com',
                'password' => Hash::make('superadmin123'),
                'account_owner_id' => null,
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$superAdmin->roles()->where('role_id', $superAdminRole->id)->exists()) {
            $superAdmin->roles()->attach($superAdminRole->id);
        }
        $this->command->info('✅ Super Admin created: superadmin@dreampos.com');

        // 2. Create Business Owner A - John
        $john = User::firstOrCreate(
            ['email' => 'owner.john@business.com'],
            [
                'name' => 'John Business Owner',
                'email' => 'owner.john@business.com',
                'password' => Hash::make('owner123'),
                'account_owner_id' => null, // Business owners don't have account owners
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$john->roles()->where('role_id', $businessOwnerRole->id)->exists()) {
            $john->roles()->attach($businessOwnerRole->id);
        }
        $this->command->info('✅ Business Owner A (John) created: owner.john@business.com');

        // 3. Create Business Owner B - Emma
        $emma = User::firstOrCreate(
            ['email' => 'owner.emma@retail.com'],
            [
                'name' => 'Emma Business Owner',
                'email' => 'owner.emma@retail.com',
                'password' => Hash::make('owner123'),
                'account_owner_id' => null,
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$emma->roles()->where('role_id', $businessOwnerRole->id)->exists()) {
            $emma->roles()->attach($businessOwnerRole->id);
        }
        $this->command->info('✅ Business Owner B (Emma) created: owner.emma@retail.com');

        // 4. Create Stores for John
        $downtownStore = Store::firstOrCreate(
            ['slug' => 'bbsb-laundry-downtown'],
            [
                'name' => "BBSB Laundry Downtown",
                'slug' => 'bbsb-laundry-downtown',
                'email' => 'downtown@bbsblaundry.com',
                'phone' => '+60123456789',
                'address' => '123 Downtown Street, Kuala Lumpur, Malaysia',
                'is_active' => true,
                'created_by' => $john->id,
            ]
        );
        $this->command->info("✅ Store created: BBSB Laundry Downtown");

        $mallStore = Store::firstOrCreate(
            ['slug' => 'bbsb-laundry-mall'],
            [
                'name' => "BBSB Laundry Mall",
                'slug' => 'bbsb-laundry-mall',
                'email' => 'mall@bbsblaundry.com',
                'phone' => '+60123456790',
                'address' => '456 Mall Road, Kuala Lumpur, Malaysia',
                'is_active' => true,
                'created_by' => $john->id,
            ]
        );
        $this->command->info("✅ Store created: BBSB Laundry Mall");

        // 5. Create Stores for Emma
        $centralStore = Store::firstOrCreate(
            ['slug' => 'emma-laundry-central'],
            [
                'name' => "Emma Laundry Central",
                'slug' => 'emma-laundry-central',
                'email' => 'central@emmalaundry.com',
                'phone' => '+60123456791',
                'address' => '789 Central Avenue, Kuala Lumpur, Malaysia',
                'is_active' => true,
                'created_by' => $emma->id,
            ]
        );
        $this->command->info("✅ Store created: Emma Laundry Central");

        // 6. Create Store Admin for BBSB Laundry Downtown - Mike Manager
        $mike = User::firstOrCreate(
            ['email' => 'mike.manager@bbsblaundry.com'],
            [
                'name' => 'Mike Manager',
                'email' => 'mike.manager@bbsblaundry.com',
                'password' => Hash::make('admin123'),
                'account_owner_id' => $john->id, // Linked to John (business owner)
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$mike->roles()->where('role_id', $adminRole->id)->exists()) {
            $mike->roles()->attach($adminRole->id);
        }
        // Assign Mike to Downtown store
        if (!$mike->stores->contains($downtownStore->id)) {
            $mike->stores()->attach($downtownStore->id);
        }
        $this->command->info('✅ Store Admin (Mike) created and assigned to Downtown: mike.manager@bbsblaundry.com');

        // 7. Create Staff for BBSB Laundry Downtown - Sarah Cashier
        $sarah = User::firstOrCreate(
            ['email' => 'sarah.cashier@bbsblaundry.com'],
            [
                'name' => 'Sarah Cashier',
                'email' => 'sarah.cashier@bbsblaundry.com',
                'password' => Hash::make('staff123'),
                'account_owner_id' => $john->id,
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$sarah->roles()->where('role_id', $staffRole->id)->exists()) {
            $sarah->roles()->attach($staffRole->id);
        }
        // Assign Sarah to Downtown store
        if (!$sarah->stores->contains($downtownStore->id)) {
            $sarah->stores()->attach($downtownStore->id);
        }
        $this->command->info('✅ Staff (Sarah) created and assigned to Downtown: sarah.cashier@bbsblaundry.com');

        // 8. Create Staff for BBSB Laundry Mall - Tom Sales
        $tom = User::firstOrCreate(
            ['email' => 'tom.sales@bbsblaundry.com'],
            [
                'name' => 'Tom Sales',
                'email' => 'tom.sales@bbsblaundry.com',
                'password' => Hash::make('staff123'),
                'account_owner_id' => $john->id,
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$tom->roles()->where('role_id', $staffRole->id)->exists()) {
            $tom->roles()->attach($staffRole->id);
        }
        // Assign Tom to Mall store
        if (!$tom->stores->contains($mallStore->id)) {
            $tom->stores()->attach($mallStore->id);
        }
        $this->command->info('✅ Staff (Tom) created and assigned to Mall: tom.sales@bbsblaundry.com');

        // 9. Create Store Admin for Emma Laundry Central - Lisa Manager
        $lisa = User::firstOrCreate(
            ['email' => 'lisa.manager@emmalaundry.com'],
            [
                'name' => 'Lisa Manager',
                'email' => 'lisa.manager@emmalaundry.com',
                'password' => Hash::make('admin123'),
                'account_owner_id' => $emma->id,
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$lisa->roles()->where('role_id', $adminRole->id)->exists()) {
            $lisa->roles()->attach($adminRole->id);
        }
        // Assign Lisa to Central store
        if (!$lisa->stores->contains($centralStore->id)) {
            $lisa->stores()->attach($centralStore->id);
        }
        $this->command->info('✅ Store Admin (Lisa) created and assigned to Central: lisa.manager@emmalaundry.com');

        // 10. Create Staff for Emma Laundry Central - Bob Cashier
        $bob = User::firstOrCreate(
            ['email' => 'bob.cashier@emmalaundry.com'],
            [
                'name' => 'Bob Cashier',
                'email' => 'bob.cashier@emmalaundry.com',
                'password' => Hash::make('staff123'),
                'account_owner_id' => $emma->id,
                'email_verified_at' => now(),
            ]
        );
        // Attach role if not already attached
        if (!$bob->roles()->where('role_id', $staffRole->id)->exists()) {
            $bob->roles()->attach($staffRole->id);
        }
        // Assign Bob to Central store
        if (!$bob->stores->contains($centralStore->id)) {
            $bob->stores()->attach($centralStore->id);
        }
        $this->command->info('✅ Staff (Bob) created and assigned to Central: bob.cashier@emmalaundry.com');

        $this->command->info('');
        $this->command->info('🎉 Test accounts seeding completed!');
        $this->command->info('');
        $this->command->info('📋 Summary:');
        $this->command->info('   • 1 Super Admin');
        $this->command->info('   • 2 Business Owners');
        $this->command->info('   • 2 Store Admins');
        $this->command->info('   • 3 Staff members');
        $this->command->info('   • 3 Stores');
        $this->command->info('');
        $this->command->info('🔑 Quick Login:');
        $this->command->info('   Super Admin: superadmin@dreampos.com / superadmin123');
        $this->command->info('   Business Owner: owner.john@business.com / owner123');
        $this->command->info('   Store Admin: mike.manager@bbsblaundry.com / admin123');
        $this->command->info('   Staff: sarah.cashier@bbsblaundry.com / staff123');
        $this->command->info('');
        $this->command->info('🌐 Store Websites:');
        $this->command->info('   BBSB Laundry Downtown: laundry3.test/bbsb-laundry-downtown');
        $this->command->info('   BBSB Laundry Mall: laundry3.test/bbsb-laundry-mall');
        $this->command->info('   Emma Laundry Central: laundry3.test/emma-laundry-central');
    }
}

