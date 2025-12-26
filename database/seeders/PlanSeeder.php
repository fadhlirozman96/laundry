<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Free plan to get started',
                'price' => 0.00,
                'annual_price' => 0.00,
                'max_stores' => 1,
                'qc_level' => 'basic',
                'audit_trail_level' => 'basic',
                'has_store_switcher' => false,
                'has_all_stores_view' => false,
                'features' => json_encode([
                    'max_users' => 1,
                    'max_products' => 5,
                    'max_orders_per_month' => 50,
                    'customer_support' => 'email',
                    'pos_system' => true,
                ]),
                'is_active' => true,
                'trial_days' => 0,
                'sort_order' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for small businesses starting out',
                'price' => 99.00,
                'annual_price' => 999.00,
                'max_stores' => 1,
                'qc_level' => 'basic',
                'audit_trail_level' => 'basic',
                'has_store_switcher' => false,
                'has_all_stores_view' => false,
                'features' => json_encode([
                    'max_users' => 5,
                    'max_products' => 100,
                    'max_orders_per_month' => 500,
                    'customer_support' => 'email',
                    'pos_system' => true,
                ]),
                'is_active' => true,
                'trial_days' => 14,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Standard',
                'slug' => 'standard',
                'description' => 'Great for growing businesses with multiple locations',
                'price' => 249.00,
                'annual_price' => 2499.00,
                'max_stores' => 3,
                'qc_level' => 'full',
                'audit_trail_level' => 'full',
                'has_store_switcher' => true,
                'has_all_stores_view' => false,
                'features' => json_encode([
                    'max_users' => 20,
                    'max_products' => 1000,
                    'max_orders_per_month' => 5000,
                    'customer_support' => 'priority_email',
                    'laundry_qc' => true,
                    'advanced_reporting' => true,
                    'pos_system' => true,
                    'landing_page_module' => true,
                    'theme_customization' => true,
                ]),
                'is_active' => true,
                'trial_days' => 14,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Enterprise solution with unlimited stores and advanced features',
                'price' => 499.00,
                'annual_price' => 4999.00,
                'max_stores' => 'unlimited',
                'qc_level' => 'basic',
                'audit_trail_level' => 'advanced',
                'has_store_switcher' => true,
                'has_all_stores_view' => true,
                'features' => json_encode([
                    'max_users' => 'unlimited',
                    'max_products' => 'unlimited',
                    'max_orders_per_month' => 'unlimited',
                    'customer_support' => 'phone_priority',
                    'advanced_reporting' => true,
                    'api_access' => true,
                    'laundry_qc' => true,
                    'machine_tracking' => true,
                    'pos_system' => true,
                    'landing_page_module' => true,
                    'theme_customization' => true,
                ]),
                'is_active' => true,
                'trial_days' => 14,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['slug' => $planData['slug']], // Find by slug
                $planData // Update or create with this data
            );
        }
        
        $this->command->info('Plans seeded successfully!');
    }
}

