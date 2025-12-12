<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;

class RemoveEmmaProductsSeeder extends Seeder
{
    /**
     * Remove products from Emma's store that were created by LaundryServiceSeeder
     */
    public function run(): void
    {
        $this->command->info('🔄 Removing duplicate products from Emma\'s store...');

        $emma = User::where('email', 'owner.emma@retail.com')->first();
        
        if (!$emma) {
            $this->command->error('❌ Emma not found!');
            return;
        }

        $emmaStore = Store::where('created_by', $emma->id)->first();
        
        if (!$emmaStore) {
            $this->command->error('❌ Emma\'s store not found!');
            return;
        }

        // Find products in Emma's store that match the service SKU pattern
        $serviceProducts = Product::where('store_id', $emmaStore->id)
            ->where('sku', 'like', 'SVC-%')
            ->get();

        $count = $serviceProducts->count();
        
        if ($count > 0) {
            foreach ($serviceProducts as $product) {
                $this->command->info("  - Deleting: {$product->name} (SKU: {$product->sku})");
                $product->delete();
            }
            $this->command->info("✅ Removed {$count} products from Emma's store.");
        } else {
            $this->command->info("✅ No service products found in Emma's store.");
        }
    }
}

