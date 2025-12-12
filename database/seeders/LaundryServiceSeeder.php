<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Str;

class LaundryServiceSeeder extends Seeder
{
    /**
     * Run the database seeder.
     * Creates laundry service categories and products based on BBSB LAUNDRY price list
     */
    public function run(): void
    {
        // Create Units for Laundry Services
        $units = [
            ['name' => 'Kilogram', 'short_name' => 'Kg'],
            ['name' => 'Piece', 'short_name' => 'Pc'],
            ['name' => 'Set', 'short_name' => 'Set'],
            ['name' => 'Square Feet', 'short_name' => 'Sq.ft'],
            ['name' => 'Pair', 'short_name' => 'Pair'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['short_name' => $unit['short_name']],
                $unit
            );
        }

        // Get unit IDs
        $kgUnit = Unit::where('short_name', 'Kg')->first()->id;
        $pcUnit = Unit::where('short_name', 'Pc')->first()->id;
        $setUnit = Unit::where('short_name', 'Set')->first()->id;
        $sqftUnit = Unit::where('short_name', 'Sq.ft')->first()->id;
        $pairUnit = Unit::where('short_name', 'Pair')->first()->id;

        // Create Laundry Service Categories
        $categories = [
            [
                'name' => 'Normal Wash & Dry Services',
                'slug' => 'normal-wash-dry-services',
                'description' => 'Wash & Dry Below 2kg minimum price RM9.00',
                'is_active' => true,
            ],
            [
                'name' => 'Fold Services',
                'slug' => 'fold-services',
                'description' => 'Fold only (includes plastic packing)',
                'is_active' => true,
            ],
            [
                'name' => 'Carpet Services',
                'slug' => 'carpet-services',
                'description' => 'Carpet cleaning services',
                'is_active' => true,
            ],
            [
                'name' => 'Ironing Services',
                'slug' => 'ironing-services',
                'description' => 'Ironing services - Kindly provide your own hanger, or else just buy RM1/hanger from us',
                'is_active' => true,
            ],
            [
                'name' => 'Hand Wash Services',
                'slug' => 'hand-wash-services',
                'description' => 'All handwash include iron & plastic. Kindly provide your own hanger, or else just buy RM1/hanger from us',
                'is_active' => true,
            ],
            [
                'name' => 'Dryclean Services',
                'slug' => 'dryclean-services',
                'description' => 'Professional drycleaning services',
                'is_active' => true,
            ],
        ];

        $categoryIds = [];
        foreach ($categories as $category) {
            $cat = Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
            $categoryIds[$category['slug']] = $cat->id;
        }

        // Get all stores to assign products
        $stores = Store::where('is_active', true)->get();
        
        if ($stores->isEmpty()) {
            $this->command->warn('No stores found. Please run TestAccountsSeeder first.');
            return;
        }

        // Create Products/Services for each category
        $services = [
            // Normal Wash & Dry Services
            [
                'name' => 'Normal Wash (1-2 days)',
                'slug' => 'normal-wash-1-2-days',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 4.00,
                'description' => 'Normal wash service with 1-2 days turnaround',
                'sku' => 'SVC-NW-001',
            ],
            [
                'name' => 'Express Wash (Same Day Below 24hrs)',
                'slug' => 'express-wash-same-day',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 8.00,
                'description' => 'Express wash service - same day below 24 hours',
                'sku' => 'SVC-NW-002',
            ],
            [
                'name' => 'Drying Only',
                'slug' => 'drying-only',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 3.00,
                'description' => 'Drying service only',
                'sku' => 'SVC-NW-003',
            ],
            [
                'name' => 'Comforter/Toto',
                'slug' => 'comforter-toto',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $pcUnit,
                'price' => 21.50, // Average of RM20-23
                'description' => 'Comforter or Toto cleaning service',
                'sku' => 'SVC-NW-004',
            ],
            [
                'name' => 'Set Comforter',
                'slug' => 'set-comforter',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $setUnit,
                'price' => 27.50, // Average of RM25-30
                'description' => 'Set comforter cleaning service',
                'sku' => 'SVC-NW-005',
            ],
            [
                'name' => 'Toy, Pillow',
                'slug' => 'toy-pillow',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $pcUnit,
                'price' => 11.50, // Average of RM8-15
                'description' => 'Toy or pillow cleaning service',
                'sku' => 'SVC-NW-006',
            ],
            [
                'name' => 'Sejadah/Rug',
                'slug' => 'sejadah-rug',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $pcUnit,
                'price' => 16.00, // Average of RM12-20
                'description' => 'Sejadah or rug cleaning service',
                'sku' => 'SVC-NW-007',
            ],
            [
                'name' => 'Bedsheet/Towel',
                'slug' => 'bedsheet-towel',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 10.00,
                'description' => 'Bedsheet or towel washing service',
                'sku' => 'SVC-NW-008',
            ],
            [
                'name' => 'Bedsheet (Wash+Ironing)',
                'slug' => 'bedsheet-wash-ironing',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 15.00,
                'description' => 'Bedsheet washing and ironing service',
                'sku' => 'SVC-NW-009',
            ],
            [
                'name' => 'Curtain/Table Cloth',
                'slug' => 'curtain-table-cloth',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 12.00,
                'description' => 'Curtain or table cloth washing service',
                'sku' => 'SVC-NW-010',
            ],
            [
                'name' => 'Curtain/Table Cloth (Wash+Ironing)',
                'slug' => 'curtain-table-cloth-wash-ironing',
                'category_id' => $categoryIds['normal-wash-dry-services'],
                'unit_id' => $kgUnit,
                'price' => 15.00,
                'description' => 'Curtain or table cloth washing and ironing service',
                'sku' => 'SVC-NW-011',
            ],

            // Fold Services
            [
                'name' => 'Fold Only (Includes Plastic Packing)',
                'slug' => 'fold-only',
                'category_id' => $categoryIds['fold-services'],
                'unit_id' => $kgUnit,
                'price' => 2.00,
                'description' => 'Folding service with plastic packing included',
                'sku' => 'SVC-FD-001',
            ],

            // Carpet Services
            [
                'name' => 'Soft Carpet',
                'slug' => 'soft-carpet',
                'category_id' => $categoryIds['carpet-services'],
                'unit_id' => $pcUnit,
                'price' => 47.50, // Average of RM35-60
                'description' => 'Soft carpet cleaning service',
                'sku' => 'SVC-CP-001',
            ],
            [
                'name' => 'Hard Carpet',
                'slug' => 'hard-carpet',
                'category_id' => $categoryIds['carpet-services'],
                'unit_id' => $sqftUnit,
                'price' => 3.00,
                'description' => 'Hard carpet cleaning service (minimum charge at RM50.00)',
                'sku' => 'SVC-CP-002',
            ],

            // Ironing Services
            [
                'name' => 'T-Shirt, Shirt, Blouse, Pants, Top',
                'slug' => 'ironing-tshirt-shirt-blouse-pants-top',
                'category_id' => $categoryIds['ironing-services'],
                'unit_id' => $pcUnit,
                'price' => 2.50,
                'description' => 'Ironing service for T-shirt, shirt, blouse, pants, or top',
                'sku' => 'SVC-IR-001',
            ],
            [
                'name' => 'Skirt, Jubah, Dress, Kaftan',
                'slug' => 'ironing-skirt-jubah-dress-kaftan',
                'category_id' => $categoryIds['ironing-services'],
                'unit_id' => $pcUnit,
                'price' => 8.00, // Average of RM6-10
                'description' => 'Ironing service for skirt, jubah, dress, or kaftan',
                'sku' => 'SVC-IR-002',
            ],
            [
                'name' => 'Set of Baju Kurung',
                'slug' => 'ironing-set-baju-kurung',
                'category_id' => $categoryIds['ironing-services'],
                'unit_id' => $setUnit,
                'price' => 6.00,
                'description' => 'Ironing service for set of Baju Kurung',
                'sku' => 'SVC-IR-003',
            ],
            [
                'name' => 'Set of Uniform',
                'slug' => 'ironing-set-uniform',
                'category_id' => $categoryIds['ironing-services'],
                'unit_id' => $setUnit,
                'price' => 7.25, // Average of RM6-8.50
                'description' => 'Ironing service for set of uniform',
                'sku' => 'SVC-IR-004',
            ],
            [
                'name' => 'Bedsheet/Curtain',
                'slug' => 'ironing-bedsheet-curtain',
                'category_id' => $categoryIds['ironing-services'],
                'unit_id' => $pcUnit,
                'price' => 12.00,
                'description' => 'Ironing service for bedsheet or curtain',
                'sku' => 'SVC-IR-005',
            ],
            [
                'name' => 'Saree/Vesti',
                'slug' => 'ironing-saree-vesti',
                'category_id' => $categoryIds['ironing-services'],
                'unit_id' => $pcUnit,
                'price' => 9.00,
                'description' => 'Ironing service for saree or vesti',
                'sku' => 'SVC-IR-006',
            ],

            // Hand Wash Services
            [
                'name' => 'Hand Wash - T-Shirt, Shirt, Blouse, Pants, Top',
                'slug' => 'handwash-tshirt-shirt-blouse-pants-top',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $pcUnit,
                'price' => 6.00,
                'description' => 'Hand wash service for T-shirt, shirt, blouse, pants, or top (includes iron & plastic)',
                'sku' => 'SVC-HW-001',
            ],
            [
                'name' => 'Hand Wash - Skirt, Jubah, Dress, Kaftan',
                'slug' => 'handwash-skirt-jubah-dress-kaftan',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $pcUnit,
                'price' => 13.50, // Average of RM12-15
                'description' => 'Hand wash service for skirt, jubah, dress, or kaftan (includes iron & plastic)',
                'sku' => 'SVC-HW-002',
            ],
            [
                'name' => 'Hand Wash - Set of Baju Kurung/Baju Melayu',
                'slug' => 'handwash-set-baju-kurung-melayu',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $setUnit,
                'price' => 13.50, // Average of RM12-15
                'description' => 'Hand wash service for set of Baju Kurung or Baju Melayu (includes iron & plastic)',
                'sku' => 'SVC-HW-003',
            ],
            [
                'name' => 'Hand Wash - Set of Uniform',
                'slug' => 'handwash-set-uniform',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $setUnit,
                'price' => 12.00,
                'description' => 'Hand wash service for set of uniform (includes iron & plastic)',
                'sku' => 'SVC-HW-004',
            ],
            [
                'name' => 'Hand Wash - Jacket',
                'slug' => 'handwash-jacket',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $pcUnit,
                'price' => 10.00, // Average of RM8-12
                'description' => 'Hand wash service for jacket (includes iron & plastic)',
                'sku' => 'SVC-HW-005',
            ],
            [
                'name' => 'Hand Wash - Shoe',
                'slug' => 'handwash-shoe',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $pairUnit,
                'price' => 20.00,
                'description' => 'Hand wash service for shoes (includes iron & plastic)',
                'sku' => 'SVC-HW-006',
            ],
            [
                'name' => 'Hand Wash - Bag',
                'slug' => 'handwash-bag',
                'category_id' => $categoryIds['hand-wash-services'],
                'unit_id' => $pcUnit,
                'price' => 17.50, // Average of RM15-20
                'description' => 'Hand wash service for bag (includes iron & plastic)',
                'sku' => 'SVC-HW-007',
            ],

            // Dryclean Services
            [
                'name' => 'Dryclean - Shirt, T-Shirt, Pants, Jeans, Blouse',
                'slug' => 'dryclean-shirt-tshirt-pants-jeans-blouse',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 12.00,
                'description' => 'Drycleaning service for shirt, T-shirt, pants, jeans, or blouse',
                'sku' => 'SVC-DC-001',
            ],
            [
                'name' => 'Dryclean - Skirt',
                'slug' => 'dryclean-skirt',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 18.50, // Average of RM12-25
                'description' => 'Drycleaning service for skirt',
                'sku' => 'SVC-DC-002',
            ],
            [
                'name' => 'Dryclean - Jubah, Kaftan',
                'slug' => 'dryclean-jubah-kaftan',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 30.00, // Average of RM25-35
                'description' => 'Drycleaning service for jubah or kaftan',
                'sku' => 'SVC-DC-003',
            ],
            [
                'name' => 'Dryclean - Dress/Long/Evening',
                'slug' => 'dryclean-dress-long-evening',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 35.00, // Average of RM25-45
                'description' => 'Drycleaning service for dress, long dress, or evening dress',
                'sku' => 'SVC-DC-004',
            ],
            [
                'name' => 'Dryclean - Wedding Gown/Dress',
                'slug' => 'dryclean-wedding-gown-dress',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 100.00, // Average of RM90-110
                'description' => 'Drycleaning service for wedding gown or dress',
                'sku' => 'SVC-DC-005',
            ],
            [
                'name' => 'Dryclean - Sampin',
                'slug' => 'dryclean-sampin',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 12.00,
                'description' => 'Drycleaning service for sampin',
                'sku' => 'SVC-DC-006',
            ],
            [
                'name' => 'Dryclean - Set of Baju Kurung',
                'slug' => 'dryclean-set-baju-kurung',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 30.00, // Average of RM25-35
                'description' => 'Drycleaning service for set of Baju Kurung',
                'sku' => 'SVC-DC-007',
            ],
            [
                'name' => 'Dryclean - Set of Baju Kurung/Baju Melayu Kids',
                'slug' => 'dryclean-set-baju-kurung-melayu-kids',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 20.00,
                'description' => 'Drycleaning service for set of Baju Kurung or Baju Melayu (Kids)',
                'sku' => 'SVC-DC-008',
            ],
            [
                'name' => 'Dryclean - Set of Baju Melayu',
                'slug' => 'dryclean-set-baju-melayu',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 22.50, // Average of RM20-25
                'description' => 'Drycleaning service for set of Baju Melayu',
                'sku' => 'SVC-DC-009',
            ],
            [
                'name' => 'Dryclean - Set of Uniform/Blazer',
                'slug' => 'dryclean-set-uniform-blazer',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 25.00,
                'description' => 'Drycleaning service for set of uniform or blazer',
                'sku' => 'SVC-DC-010',
            ],
            [
                'name' => 'Dryclean - Coat',
                'slug' => 'dryclean-coat',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 20.00,
                'description' => 'Drycleaning service for coat',
                'sku' => 'SVC-DC-011',
            ],
            [
                'name' => 'Dryclean - Set of Coat',
                'slug' => 'dryclean-set-coat',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 25.00,
                'description' => 'Drycleaning service for set of coat',
                'sku' => 'SVC-DC-012',
            ],
            [
                'name' => 'Dryclean - Overcoat',
                'slug' => 'dryclean-overcoat',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 40.00,
                'description' => 'Drycleaning service for overcoat',
                'sku' => 'SVC-DC-013',
            ],
            [
                'name' => 'Dryclean - Jacket',
                'slug' => 'dryclean-jacket',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 25.00,
                'description' => 'Drycleaning service for jacket',
                'sku' => 'SVC-DC-014',
            ],
            [
                'name' => 'Dryclean - Leather Jacket',
                'slug' => 'dryclean-leather-jacket',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 75.00,
                'description' => 'Drycleaning service for leather jacket',
                'sku' => 'SVC-DC-015',
            ],
            [
                'name' => 'Dryclean - Winter Jacket',
                'slug' => 'dryclean-winter-jacket',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 32.50, // Average of RM25-40
                'description' => 'Drycleaning service for winter jacket',
                'sku' => 'SVC-DC-016',
            ],
            [
                'name' => 'Dryclean - Sweater/Cardigan',
                'slug' => 'dryclean-sweater-cardigan',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 16.50, // Average of RM15-18
                'description' => 'Drycleaning service for sweater or cardigan',
                'sku' => 'SVC-DC-017',
            ],
            [
                'name' => 'Dryclean - Set Saree',
                'slug' => 'dryclean-set-saree',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 37.50, // Average of RM30-45
                'description' => 'Drycleaning service for set saree',
                'sku' => 'SVC-DC-018',
            ],
            [
                'name' => 'Dryclean - Saree',
                'slug' => 'dryclean-saree',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 30.00, // Average of RM25-35
                'description' => 'Drycleaning service for saree',
                'sku' => 'SVC-DC-019',
            ],
            [
                'name' => 'Dryclean - Vesti',
                'slug' => 'dryclean-vesti',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 20.00,
                'description' => 'Drycleaning service for vesti',
                'sku' => 'SVC-DC-020',
            ],
            [
                'name' => 'Dryclean - Set Punjabi/Set Kurta (3pcs)',
                'slug' => 'dryclean-set-punjabi-kurta',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $setUnit,
                'price' => 40.00, // Average of RM35-45
                'description' => 'Drycleaning service for set Punjabi or set Kurta (3 pieces)',
                'sku' => 'SVC-DC-021',
            ],
            [
                'name' => 'Dryclean - Punjabi/Kurta Top',
                'slug' => 'dryclean-punjabi-kurta-top',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 22.50, // Average of RM15-30
                'description' => 'Drycleaning service for Punjabi or Kurta top',
                'sku' => 'SVC-DC-022',
            ],
            [
                'name' => 'Dryclean - Scarf/Shawl/Selendang/Veil',
                'slug' => 'dryclean-scarf-shawl-selendang-veil',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 13.50, // Average of RM12-15
                'description' => 'Drycleaning service for scarf, shawl, selendang, or veil',
                'sku' => 'SVC-DC-023',
            ],
            [
                'name' => 'Dryclean - Cap/Hat',
                'slug' => 'dryclean-cap-hat',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pcUnit,
                'price' => 12.00,
                'description' => 'Drycleaning service for cap or hat',
                'sku' => 'SVC-DC-024',
            ],
            [
                'name' => 'Dryclean - Glove/Socks',
                'slug' => 'dryclean-glove-socks',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $pairUnit,
                'price' => 12.00,
                'description' => 'Drycleaning service for glove or socks',
                'sku' => 'SVC-DC-025',
            ],
            [
                'name' => 'Dryclean - Curtain',
                'slug' => 'dryclean-curtain',
                'category_id' => $categoryIds['dryclean-services'],
                'unit_id' => $kgUnit,
                'price' => 20.00,
                'description' => 'Drycleaning service for curtain',
                'sku' => 'SVC-DC-026',
            ],
        ];

        // Clean up any existing products with service SKU pattern that might conflict
        // Remove products with base SKU format (SVC-XXX-XXX) that don't have store ID suffix
        $storeIds = $stores->pluck('id')->toArray();
        $baseSkus = array_map(function($service) {
            return $service['sku']; // Get base SKUs like 'SVC-NW-001'
        }, $services);
        
        $baseSlugs = array_map(function($service) {
            return $service['slug']; // Get base slugs like 'normal-wash-1-2-days'
        }, $services);
        
        $deletedCount = Product::whereIn('store_id', $storeIds)
            ->where(function($query) use ($baseSkus, $baseSlugs) {
                $query->whereIn('sku', $baseSkus) // Match base SKUs without store ID
                      ->orWhereIn('slug', $baseSlugs); // Match base slugs without store ID
            })
            ->delete();
        
        if ($deletedCount > 0) {
            $this->command->info("🔄 Cleaned up {$deletedCount} old service products with conflicting SKUs/slugs.");
        }

        // Create services for each store
        foreach ($stores as $store) {
            foreach ($services as $service) {
                $uniqueSku = $service['sku'] . '-' . $store->id;
                $uniqueSlug = $service['slug'] . '-' . $store->id; // Make slug unique per store
                Product::firstOrCreate(
                    [
                        'store_id' => $store->id,
                        'sku' => $uniqueSku,
                    ],
                    array_merge($service, [
                        'store_id' => $store->id,
                        'sku' => $uniqueSku, // Use unique SKU with store ID
                        'slug' => $uniqueSlug, // Use unique slug with store ID
                        'cost' => $service['price'] * 0.6, // Assume 40% margin
                        'quantity' => 9999, // Unlimited service capacity
                        'alert_quantity' => 0,
                        'is_active' => true,
                        'created_by' => $store->created_by,
                    ])
                );
            }
        }

        $this->command->info('✅ Laundry service categories and products created successfully!');
        $this->command->info('   Categories: ' . count($categories));
        $this->command->info('   Services: ' . count($services) . ' per store');
        $this->command->info('   Total services created: ' . (count($services) * $stores->count()));
    }
}

