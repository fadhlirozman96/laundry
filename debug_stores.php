<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Store;
use App\Models\User;

echo "=== STORE OWNERSHIP DEBUG ===\n\n";

$stores = Store::with('owner')->get();

foreach ($stores as $store) {
    echo "Store: {$store->name} (ID: {$store->id})\n";
    echo "  Owner: " . ($store->owner ? $store->owner->name . " ({$store->owner->email})" : "NONE") . "\n";
    echo "  Created By: {$store->created_by}\n";
    echo "\n";
}

