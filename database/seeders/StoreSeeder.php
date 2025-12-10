<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use Illuminate\Support\Str;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stores = [
            [
                'name' => 'Main Store',
                'slug' => 'main-store',
                'email' => 'main@dreampos.com',
                'phone' => '+1234567890',
                'address' => '123 Main Street, City, Country',
                'is_active' => true,
            ],
            [
                'name' => 'Downtown Branch',
                'slug' => 'downtown-branch',
                'email' => 'downtown@dreampos.com',
                'phone' => '+1234567891',
                'address' => '456 Downtown Ave, City, Country',
                'is_active' => true,
            ],
            [
                'name' => 'Shopping Mall Store',
                'slug' => 'shopping-mall-store',
                'email' => 'mall@dreampos.com',
                'phone' => '+1234567892',
                'address' => '789 Mall Road, City, Country',
                'is_active' => true,
            ],
        ];

        foreach ($stores as $store) {
            Store::create($store);
        }
    }
}

