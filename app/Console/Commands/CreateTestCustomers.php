<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Customer;
use App\Models\User;
use App\Models\Store;

class CreateTestCustomers extends Command
{
    protected $signature = 'laundry:create-test-customers 
                            {--count=10 : Number of customers to create}
                            {--store= : Store ID}';

    protected $description = 'Create test customers for testing purposes';

    public function handle()
    {
        $count = (int) $this->option('count');
        $storeId = $this->option('store');

        // Get or create store
        if (!$storeId) {
            $store = Store::first();
            if (!$store) {
                $this->error('No store found. Please create a store first.');
                return 1;
            }
            $storeId = $store->id;
        } else {
            $store = Store::find($storeId);
            if (!$store) {
                $this->error("Store with ID {$storeId} not found.");
                return 1;
            }
        }

        // Get user
        $user = User::first();
        if (!$user) {
            $this->error('No user found. Please create a user first.');
            return 1;
        }

        $this->info("Creating {$count} test customers for store: {$store->name}...");
        $this->newLine();

        $names = [
            'Amad', 'Fadhli Rozman', 'Ahmad Zaki', 'Siti Nurhaliza', 
            'Rahman Ali', 'Fatimah Zahra', 'Hassan Abdullah', 'Nurul Iman',
            'Mohd Azlan', 'Nur Aisyah', 'Ahmad Fauzi', 'Siti Aishah',
            'Zulkifli Hassan', 'Norazila Mohd', 'Razak Ismail', 'Mazlina Ahmad'
        ];

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $created = 0;
        for ($i = 0; $i < $count; $i++) {
            $name = $names[array_rand($names)] . ' ' . rand(1, 100);
            
            // Check if customer already exists
            $exists = Customer::where('name', $name)
                ->where('store_id', $storeId)
                ->exists();
            
            if (!$exists) {
                Customer::create([
                    'store_id' => $storeId,
                    'name' => $name,
                    'phone' => '01' . rand(2000000, 9999999),
                    'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                    'address' => rand(0, 1) ? 'Test Address ' . rand(1, 100) : null,
                    'city' => rand(0, 1) ? 'Kuala Lumpur' : null,
                    'country' => 'Malaysia',
                    'is_active' => true,
                    'created_by' => $user->id,
                ]);
                $created++;
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("✅ Created {$created} test customers!");
        $this->info("Store: {$store->name} (ID: {$storeId})");

        return 0;
    }
}

