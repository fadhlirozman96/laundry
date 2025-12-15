<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Store;
use App\Models\Role;

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
            // Get business owner role
            $businessOwnerRole = Role::businessOwner();
            if (!$businessOwnerRole) {
                $this->command->error("❌ Business Owner role not found. Please run RoleSeeder first.");
                return;
            }

            // Make them Business Owner (not super admin)
            $user->update([
                'account_owner_id' => null, // They own their own account
            ]);

            // Assign business owner role via pivot table
            $user->roles()->sync([$businessOwnerRole->id]);

            // Link all existing stores to this business owner
            Store::query()->update(['created_by' => $user->id]);

            $this->command->info("✅ User '{$user->name}' is now Business Owner");
            $this->command->info("✅ All stores are now owned by '{$user->name}'");
            
            // Check if we need to create Super Admin
            $superAdminRole = Role::superAdmin();
            if ($superAdminRole) {
                $superAdmin = User::whereHas('roles', function($query) use ($superAdminRole) {
                    $query->where('roles.id', $superAdminRole->id);
                })->first();
                if (!$superAdmin) {
                    $this->command->info("ℹ️  Note: You may want to create a Super Admin account separately");
                }
            }
        } else {
            $this->command->error("❌ No users found in database");
        }
    }
}

