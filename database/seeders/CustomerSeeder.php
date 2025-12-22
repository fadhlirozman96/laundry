<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get unique customers from existing orders
        $orders = Order::select('customer_name', 'customer_email', 'customer_phone', 'store_id', 'user_id')
                      ->whereNotNull('customer_name')
                      ->where('customer_name', '!=', '')
                      ->where('customer_name', '!=', 'Walk-in Customer')
                      ->get()
                      ->unique('customer_name');

        foreach ($orders as $order) {
            // Check if customer already exists
            $existing = Customer::where('store_id', $order->store_id)
                               ->where('name', $order->customer_name)
                               ->first();

            if (!$existing) {
                Customer::create([
                    'store_id' => $order->store_id,
                    'name' => $order->customer_name,
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                    'is_active' => true,
                    'created_by' => $order->user_id,
                ]);
            }
        }

        // Create a few walk-in customers for each store
        $stores = Store::all();
        foreach ($stores as $store) {
            // Check if walk-in customer already exists
            $existing = Customer::where('store_id', $store->id)
                               ->where('name', 'Walk-in Customer')
                               ->first();

            if (!$existing) {
                Customer::create([
                    'store_id' => $store->id,
                    'name' => 'Walk-in Customer',
                    'email' => null,
                    'phone' => null,
                    'is_active' => true,
                    'created_by' => $store->owner_id ?? 1,
                ]);
            }
        }

        $this->command->info('Customers seeded successfully!');
        $this->command->info('Total customers: ' . Customer::count());
    }
}

