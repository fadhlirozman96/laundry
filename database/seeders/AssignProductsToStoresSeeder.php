<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;

class AssignProductsToStoresSeeder extends Seeder
{
    /**
     * Assign existing products to stores based on their creator
     */
    public function run(): void
    {
        $this->command->info('🔄 Assigning products to stores...');

        // Get all products without store_id
        $productsWithoutStore = Product::whereNull('store_id')->get();
        
        if ($productsWithoutStore->isEmpty()) {
            $this->command->info('✅ All products already have stores assigned!');
            return;
        }

        $this->command->info("Found {$productsWithoutStore->count()} products without store assignment.");

        foreach ($productsWithoutStore as $product) {
            // Find the creator of the product
            $creator = $product->creator;
            
            if ($creator) {
                // If creator is a business owner, assign to their first store
                if ($creator->isBusinessOwner()) {
                    $store = Store::where('created_by', $creator->id)->first();
                    if ($store) {
                        $product->store_id = $store->id;
                        $product->save();
                        $this->command->info("  - Product '{$product->name}' assigned to '{$store->name}'");
                        continue;
                    }
                }
                
                // If creator is staff/admin, find their account owner's store
                if ($creator->account_owner_id) {
                    $store = Store::where('created_by', $creator->account_owner_id)->first();
                    if ($store) {
                        $product->store_id = $store->id;
                        $product->save();
                        $this->command->info("  - Product '{$product->name}' assigned to '{$store->name}'");
                        continue;
                    }
                }
            }

            // If no creator or no store found, assign to the first business owner's first store
            $firstBusinessOwner = User::where('role', 'business_owner')->first();
            if ($firstBusinessOwner) {
                $store = Store::where('created_by', $firstBusinessOwner->id)->first();
                if ($store) {
                    $product->store_id = $store->id;
                    $product->save();
                    $this->command->info("  - Product '{$product->name}' assigned to '{$store->name}' (default)");
                }
            }
        }

        $this->command->info('✅ Product store assignment completed!');
    }
}

