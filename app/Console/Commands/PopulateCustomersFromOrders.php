<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Store;

class PopulateCustomersFromOrders extends Command
{
    protected $signature = 'customers:populate';
    protected $description = 'Populate customers table from existing orders';

    public function handle()
    {
        $this->info('Populating customers from orders...');

        // Get unique customers from existing orders
        $orders = Order::select('customer_name', 'customer_email', 'customer_phone', 'store_id', 'user_id')
                      ->whereNotNull('customer_name')
                      ->where('customer_name', '!=', '')
                      ->get()
                      ->groupBy(function($order) {
                          return $order->store_id . '-' . strtolower(trim($order->customer_name));
                      });

        $created = 0;
        $skipped = 0;

        foreach ($orders as $key => $orderGroup) {
            $order = $orderGroup->first();
            
            // Check if customer already exists
            $existing = Customer::where('store_id', $order->store_id)
                               ->whereRaw('LOWER(name) = ?', [strtolower(trim($order->customer_name))])
                               ->first();

            if (!$existing) {
                Customer::create([
                    'store_id' => $order->store_id,
                    'name' => trim($order->customer_name),
                    'email' => $order->customer_email,
                    'phone' => $order->customer_phone,
                    'is_active' => true,
                    'created_by' => $order->user_id ?? 1,
                ]);
                $created++;
                $this->info("Created: {$order->customer_name} for Store ID: {$order->store_id}");
            } else {
                $skipped++;
            }
        }

        // Create walk-in customers for stores without one
        $stores = Store::all();
        foreach ($stores as $store) {
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
                $created++;
                $this->info("Created Walk-in Customer for Store: {$store->name}");
            }
        }

        $this->info("\nCustomers populated successfully!");
        $this->info("Created: $created customers");
        $this->info("Skipped: $skipped (already exist)");
        $this->info("Total customers in database: " . Customer::count());

        return 0;
    }
}

