<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Product;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create Units
        $units = [
            ['name' => 'Piece', 'short_name' => 'Pc'],
            ['name' => 'Kilogram', 'short_name' => 'Kg'],
            ['name' => 'Gram', 'short_name' => 'g'],
            ['name' => 'Liter', 'short_name' => 'L'],
            ['name' => 'Meter', 'short_name' => 'm'],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['short_name' => $unit['short_name']],
                $unit
            );
        }

        // Create Categories
        $categories = [
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic devices and gadgets',
            ],
            [
                'name' => 'Laptops',
                'slug' => 'laptops',
                'description' => 'Laptop computers',
            ],
            [
                'name' => 'Mobiles',
                'slug' => 'mobiles',
                'description' => 'Mobile phones and smartphones',
            ],
            [
                'name' => 'Watches',
                'slug' => 'watches',
                'description' => 'Watches and smartwatches',
            ],
            [
                'name' => 'Headphones',
                'slug' => 'headphones',
                'description' => 'Headphones and earbuds',
            ],
            [
                'name' => 'Shoes',
                'slug' => 'shoes',
                'description' => 'Footwear and sneakers',
            ],
            [
                'name' => 'Bags',
                'slug' => 'bags',
                'description' => 'Bags and accessories',
            ],
            [
                'name' => 'Furniture',
                'slug' => 'furniture',
                'description' => 'Furniture items',
            ],
            [
                'name' => 'Speaker',
                'slug' => 'speaker',
                'description' => 'Speakers and audio devices',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        // Create Brands
        $brands = [
            ['name' => 'Apple', 'slug' => 'apple', 'description' => 'Apple Inc.'],
            ['name' => 'Nike', 'slug' => 'nike', 'description' => 'Nike Brand'],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'description' => 'Lenovo'],
            ['name' => 'Bolt', 'slug' => 'bolt', 'description' => 'Bolt Electronics'],
            ['name' => 'Versace', 'slug' => 'versace', 'description' => 'Versace'],
            ['name' => 'Amazon', 'slug' => 'amazon', 'description' => 'Amazon'],
            ['name' => 'Woodmart', 'slug' => 'woodmart', 'description' => 'Woodmart'],
            ['name' => 'Iphone', 'slug' => 'iphone', 'description' => 'iPhone brand'],
            ['name' => 'Rolex', 'slug' => 'rolex', 'description' => 'Rolex watches'],
        ];

        foreach ($brands as $brand) {
            Brand::firstOrCreate(
                ['slug' => $brand['slug']],
                $brand
            );
        }

        // Get IDs for relationships
        $pcUnit = Unit::where('short_name', 'Pc')->first()->id;
        $kgUnit = Unit::where('short_name', 'Kg')->first()->id;

        // Create Products
        $products = [
            [
                'sku' => 'PT001',
                'name' => 'Lenovo 3rd Generation',
                'slug' => 'lenovo-3rd-generation',
                'category_id' => Category::where('slug', 'laptops')->first()->id,
                'brand_id' => Brand::where('slug', 'lenovo')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Lenovo 3rd Generation laptop',
                'price' => 12500.00,
                'cost' => 10000.00,
                'quantity' => 100,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT002',
                'name' => 'Bold V3.2',
                'slug' => 'bold-v3-2',
                'category_id' => Category::where('slug', 'electronics')->first()->id,
                'brand_id' => Brand::where('slug', 'bolt')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Bold V3.2 electronic device',
                'price' => 1600.00,
                'cost' => 1200.00,
                'quantity' => 140,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT003',
                'name' => 'Nike Jordan',
                'slug' => 'nike-jordan',
                'category_id' => Category::where('slug', 'shoes')->first()->id,
                'brand_id' => Brand::where('slug', 'nike')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Nike Jordan shoes',
                'price' => 6000.00,
                'cost' => 4500.00,
                'quantity' => 780,
                'alert_quantity' => 20,
            ],
            [
                'sku' => 'PT004',
                'name' => 'Apple Series 5 Watch',
                'slug' => 'apple-series-5-watch',
                'category_id' => Category::where('slug', 'watches')->first()->id,
                'brand_id' => Brand::where('slug', 'apple')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Apple Watch Series 5',
                'price' => 25000.00,
                'cost' => 20000.00,
                'quantity' => 450,
                'alert_quantity' => 15,
            ],
            [
                'sku' => 'PT005',
                'name' => 'Amazon Echo Dot',
                'slug' => 'amazon-echo-dot',
                'category_id' => Category::where('slug', 'speaker')->first()->id,
                'brand_id' => Brand::where('slug', 'amazon')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Amazon Echo Dot smart speaker',
                'price' => 1600.00,
                'cost' => 1200.00,
                'quantity' => 477,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT006',
                'name' => 'Lobar Handy',
                'slug' => 'lobar-handy',
                'category_id' => Category::where('slug', 'furniture')->first()->id,
                'brand_id' => Brand::where('slug', 'woodmart')->first()->id,
                'unit_id' => $kgUnit,
                'description' => 'Lobar Handy furniture',
                'price' => 4521.00,
                'cost' => 3500.00,
                'quantity' => 145,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT007',
                'name' => 'Red Premium Handy',
                'slug' => 'red-premium-handy',
                'category_id' => Category::where('slug', 'bags')->first()->id,
                'brand_id' => Brand::where('slug', 'versace')->first()->id,
                'unit_id' => $kgUnit,
                'description' => 'Red Premium Handy bag',
                'price' => 2024.00,
                'cost' => 1500.00,
                'quantity' => 747,
                'alert_quantity' => 20,
            ],
            [
                'sku' => 'PT008',
                'name' => 'iPhone 14 Pro',
                'slug' => 'iphone-14-pro',
                'category_id' => Category::where('slug', 'mobiles')->first()->id,
                'brand_id' => Brand::where('slug', 'iphone')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'iPhone 14 Pro',
                'price' => 1698.00,
                'cost' => 1400.00,
                'quantity' => 897,
                'alert_quantity' => 25,
            ],
            [
                'sku' => 'PT0005',
                'name' => 'Red Nike Laser',
                'slug' => 'red-nike-laser',
                'category_id' => Category::where('slug', 'shoes')->first()->id,
                'brand_id' => Brand::where('slug', 'nike')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Red Nike Laser shoes',
                'price' => 2000.00,
                'cost' => 1500.00,
                'quantity' => 30,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT0235',
                'name' => 'iPhone 14',
                'slug' => 'iphone-14',
                'category_id' => Category::where('slug', 'mobiles')->first()->id,
                'brand_id' => Brand::where('slug', 'iphone')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'iPhone 14',
                'price' => 3000.00,
                'cost' => 2500.00,
                'quantity' => 220,
                'alert_quantity' => 15,
            ],
            [
                'sku' => 'PT009',
                'name' => 'Airpod 2',
                'slug' => 'airpod-2',
                'category_id' => Category::where('slug', 'headphones')->first()->id,
                'brand_id' => Brand::where('slug', 'apple')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Airpod 2 headphones',
                'price' => 5478.00,
                'cost' => 4500.00,
                'quantity' => 47,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT010',
                'name' => 'Blue White OGR',
                'slug' => 'blue-white-ogr',
                'category_id' => Category::where('slug', 'shoes')->first()->id,
                'brand_id' => Brand::where('slug', 'nike')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Blue White OGR shoes',
                'price' => 987.00,
                'cost' => 750.00,
                'quantity' => 54,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT011',
                'name' => 'IdeaPad Slim 5 Gen 7',
                'slug' => 'ideapad-slim-5-gen-7',
                'category_id' => Category::where('slug', 'laptops')->first()->id,
                'brand_id' => Brand::where('slug', 'lenovo')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'IdeaPad Slim 5 Gen 7 laptop',
                'price' => 1454.00,
                'cost' => 1200.00,
                'quantity' => 74,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT012',
                'name' => 'SWAGME Headphones',
                'slug' => 'swagme-headphones',
                'category_id' => Category::where('slug', 'headphones')->first()->id,
                'brand_id' => Brand::where('slug', 'bolt')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'SWAGME headphones',
                'price' => 5587.00,
                'cost' => 4500.00,
                'quantity' => 14,
                'alert_quantity' => 5,
            ],
            [
                'sku' => 'PT013',
                'name' => 'Red Nike Angelo',
                'slug' => 'red-nike-angelo',
                'category_id' => Category::where('slug', 'shoes')->first()->id,
                'brand_id' => Brand::where('slug', 'nike')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Red Nike Angelo shoes',
                'price' => 7800.00,
                'cost' => 6000.00,
                'quantity' => 78,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT014',
                'name' => 'Rolex Tribute V3',
                'slug' => 'rolex-tribute-v3',
                'category_id' => Category::where('slug', 'watches')->first()->id,
                'brand_id' => Brand::where('slug', 'rolex')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'Rolex Tribute V3 watch',
                'price' => 6800.00,
                'cost' => 5500.00,
                'quantity' => 220,
                'alert_quantity' => 15,
            ],
            [
                'sku' => 'PT015',
                'name' => 'MacBook Pro',
                'slug' => 'macbook-pro',
                'category_id' => Category::where('slug', 'laptops')->first()->id,
                'brand_id' => Brand::where('slug', 'apple')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'MacBook Pro laptop',
                'price' => 1000.00,
                'cost' => 800.00,
                'quantity' => 140,
                'alert_quantity' => 10,
            ],
            [
                'sku' => 'PT016',
                'name' => 'iPhone 14 64GB',
                'slug' => 'iphone-14-64gb',
                'category_id' => Category::where('slug', 'mobiles')->first()->id,
                'brand_id' => Brand::where('slug', 'iphone')->first()->id,
                'unit_id' => $pcUnit,
                'description' => 'iPhone 14 with 64GB storage',
                'price' => 15800.00,
                'cost' => 13000.00,
                'quantity' => 30,
                'alert_quantity' => 10,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }
    }
}
