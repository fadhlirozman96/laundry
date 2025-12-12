<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
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

        // 1. Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@dreampos.com'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@dreampos.com',
                'password' => Hash::make('superadmin123'),
                'role' => 'super_admin',
                'account_owner_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Super Admin created: superadmin@dreampos.com');

        // 2. Create Business Owner A - John
        $john = User::firstOrCreate(
            ['email' => 'owner.john@business.com'],
            [
                'name' => 'John Business Owner',
                'email' => 'owner.john@business.com',
                'password' => Hash::make('owner123'),
                'role' => 'business_owner',
                'account_owner_id' => null, // Business owners don't have account owners
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Business Owner A (John) created: owner.john@business.com');

        // 3. Create Business Owner B - Emma
        $emma = User::firstOrCreate(
            ['email' => 'owner.emma@retail.com'],
            [
                'name' => 'Emma Business Owner',
                'email' => 'owner.emma@retail.com',
                'password' => Hash::make('owner123'),
                'role' => 'business_owner',
                'account_owner_id' => null,
                'email_verified_at' => now(),
            ]
        );
        $this->command->info('✅ Business Owner B (Emma) created: owner.emma@retail.com');

        // 4. Create Stores for John
        $downtownStore = Store::firstOrCreate(
            ['slug' => 'johns-restaurant-downtown'],
            [
                'name' => "John's Restaurant Downtown",
                'slug' => 'johns-restaurant-downtown',
                'email' => 'downtown@johns.com',
                'phone' => '+1234567890',
                'address' => '123 Downtown Street, City, Country',
                'is_active' => true,
                'created_by' => $john->id,
            ]
        );
        $this->command->info("✅ Store created: John's Restaurant Downtown");

        $mallStore = Store::firstOrCreate(
            ['slug' => 'johns-restaurant-mall'],
            [
                'name' => "John's Restaurant Mall",
                'slug' => 'johns-restaurant-mall',
                'email' => 'mall@johns.com',
                'phone' => '+1234567891',
                'address' => '456 Mall Road, City, Country',
                'is_active' => true,
                'created_by' => $john->id,
            ]
        );
        $this->command->info("✅ Store created: John's Restaurant Mall");

        // 5. Create Stores for Emma
        $centralStore = Store::firstOrCreate(
            ['slug' => 'emmas-boutique-central'],
            [
                'name' => "Emma's Boutique Central",
                'slug' => 'emmas-boutique-central',
                'email' => 'central@emmas.com',
                'phone' => '+1234567892',
                'address' => '789 Central Avenue, City, Country',
                'is_active' => true,
                'created_by' => $emma->id,
            ]
        );
        $this->command->info("✅ Store created: Emma's Boutique Central");

        // 6. Create Store Admin for John's Downtown - Mike Manager
        $mike = User::firstOrCreate(
            ['email' => 'mike.manager@johns.com'],
            [
                'name' => 'Mike Manager',
                'email' => 'mike.manager@johns.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'account_owner_id' => $john->id, // Linked to John (business owner)
                'email_verified_at' => now(),
            ]
        );
        // Assign Mike to Downtown store
        if (!$mike->stores->contains($downtownStore->id)) {
            $mike->stores()->attach($downtownStore->id);
        }
        $this->command->info('✅ Store Admin (Mike) created and assigned to Downtown: mike.manager@johns.com');

        // 7. Create Staff for John's Downtown - Sarah Cashier
        $sarah = User::firstOrCreate(
            ['email' => 'sarah.cashier@johns.com'],
            [
                'name' => 'Sarah Cashier',
                'email' => 'sarah.cashier@johns.com',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'account_owner_id' => $john->id,
                'email_verified_at' => now(),
            ]
        );
        // Assign Sarah to Downtown store
        if (!$sarah->stores->contains($downtownStore->id)) {
            $sarah->stores()->attach($downtownStore->id);
        }
        $this->command->info('✅ Staff (Sarah) created and assigned to Downtown: sarah.cashier@johns.com');

        // 8. Create Staff for John's Mall - Tom Sales
        $tom = User::firstOrCreate(
            ['email' => 'tom.sales@johns.com'],
            [
                'name' => 'Tom Sales',
                'email' => 'tom.sales@johns.com',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'account_owner_id' => $john->id,
                'email_verified_at' => now(),
            ]
        );
        // Assign Tom to Mall store
        if (!$tom->stores->contains($mallStore->id)) {
            $tom->stores()->attach($mallStore->id);
        }
        $this->command->info('✅ Staff (Tom) created and assigned to Mall: tom.sales@johns.com');

        // 9. Create Store Admin for Emma's Central - Lisa Manager
        $lisa = User::firstOrCreate(
            ['email' => 'lisa.manager@emmas.com'],
            [
                'name' => 'Lisa Manager',
                'email' => 'lisa.manager@emmas.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'account_owner_id' => $emma->id,
                'email_verified_at' => now(),
            ]
        );
        // Assign Lisa to Central store
        if (!$lisa->stores->contains($centralStore->id)) {
            $lisa->stores()->attach($centralStore->id);
        }
        $this->command->info('✅ Store Admin (Lisa) created and assigned to Central: lisa.manager@emmas.com');

        // 10. Create Staff for Emma's Central - Bob Cashier
        $bob = User::firstOrCreate(
            ['email' => 'bob.cashier@emmas.com'],
            [
                'name' => 'Bob Cashier',
                'email' => 'bob.cashier@emmas.com',
                'password' => Hash::make('staff123'),
                'role' => 'staff',
                'account_owner_id' => $emma->id,
                'email_verified_at' => now(),
            ]
        );
        // Assign Bob to Central store
        if (!$bob->stores->contains($centralStore->id)) {
            $bob->stores()->attach($centralStore->id);
        }
        $this->command->info('✅ Staff (Bob) created and assigned to Central: bob.cashier@emmas.com');

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
        $this->command->info('   Store Admin: mike.manager@johns.com / admin123');
        $this->command->info('   Staff: sarah.cashier@johns.com / staff123');
    }
}

