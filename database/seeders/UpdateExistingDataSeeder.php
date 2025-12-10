<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;

class UpdateExistingDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (assuming this is the main user)
        $user = User::first();
        
        if ($user) {
            // Make them Business Owner (not super admin)
            $user->update([
                'role' => 'business_owner',
                'account_owner_id' => null, // They own their own account
            ]);

            // Link all existing stores to this business owner
            Store::query()->update(['created_by' => $user->id]);

            $this->command->info("✅ User '{$user->name}' is now Business Owner");
            $this->command->info("✅ All stores are now owned by '{$user->name}'");
            
            // Check if we need to create Super Admin
            $superAdmin = User::where('role', 'super_admin')->first();
            if (!$superAdmin) {
                $this->command->info("ℹ️  Note: You may want to create a Super Admin account separately");
            }
        } else {
            $this->command->error("❌ No users found in database");
        }
    }
}

