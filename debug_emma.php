<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Product;
use App\Models\Store;

echo "=== EMMA DEBUG ===\n\n";

$emma = User::where('email', 'owner.emma@retail.com')->first();

if (!$emma) {
    echo "❌ Emma not found!\n";
    exit;
}

echo "✅ Emma found: {$emma->name} (ID: {$emma->id})\n";
echo "   Role: {$emma->role}\n\n";

echo "Stores owned by Emma:\n";
$ownedStores = $emma->ownedStores()->get();
foreach ($ownedStores as $store) {
    echo "  - {$store->name} (ID: {$store->id})\n";
}
echo "\n";

echo "Accessible stores (via getAccessibleStores()):\n";
$accessibleStores = $emma->getAccessibleStores();
foreach ($accessibleStores as $store) {
    $productCount = Product::where('store_id', $store->id)->count();
    echo "  - {$store->name} (ID: {$store->id}) - {$productCount} products\n";
}
echo "\n";

$accessibleStoreIds = $accessibleStores->pluck('id')->toArray();
echo "Accessible store IDs: " . json_encode($accessibleStoreIds) . "\n\n";

echo "Products in Emma's accessible stores:\n";
$emmaProducts = Product::whereIn('store_id', $accessibleStoreIds)->get();
echo "Total: {$emmaProducts->count()}\n";
foreach ($emmaProducts as $product) {
    echo "  - {$product->name} (Store ID: {$product->store_id})\n";
}
echo "\n";

echo "All products in database:\n";
$allProducts = Product::with('store')->get();
echo "Total: {$allProducts->count()}\n";
foreach ($allProducts as $product) {
    $storeName = $product->store ? $product->store->name : 'NO STORE';
    echo "  - {$product->name} (Store: {$storeName}, Store ID: {$product->store_id})\n";
}

