<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use App\Models\Store;
use App\Models\QualityCheck;
use Carbon\Carbon;

class CreateTestOrders extends Command
{
    protected $signature = 'laundry:create-test-orders 
                            {--count=5 : Number of orders to create}
                            {--status= : Order status (pending, processing, completed)}
                            {--store= : Store ID}
                            {--with-qc : Create orders with QC passed}
                            {--mixed-status : Create orders with mixed statuses}
                            {--pending-paid : Create pending orders with paid payment status}';

    protected $description = 'Create test laundry orders for testing purposes';

    public function handle()
    {
        $count = (int) $this->option('count');
        $status = $this->option('status');
        $storeId = $this->option('store');
        $withQC = $this->option('with-qc');
        $mixedStatus = $this->option('mixed-status');
        $pendingPaid = $this->option('pending-paid');

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

        // Get products
        $products = Product::where('store_id', $storeId)->get();
        if ($products->isEmpty()) {
            $this->error('No products found. Please create products first.');
            return 1;
        }

        $infoMessage = "Creating {$count} test orders for store: {$store->name}";
        if ($pendingPaid) {
            $infoMessage .= " (Pending status with Paid payment)";
        }
        $infoMessage .= "...";
        $this->info($infoMessage);
        $this->newLine();

        $bar = $this->output->createProgressBar($count);
        $bar->start();

        $statuses = $mixedStatus 
            ? ['pending', 'processing', 'completed', 'pending', 'processing'] 
            : ($status ? [$status] : ['pending', 'processing', 'completed']);

        $customerNames = [
            'Amad', 'Fadhli Rozman', 'Ahmad Zaki', 'Siti Nurhaliza', 
            'Rahman Ali', 'Fatimah Zahra', 'Hassan Abdullah', 'Nurul Iman',
            'Walk-in Customer', 'John Doe', 'Jane Smith', 'Mohd Azlan'
        ];

        $createdOrders = [];

        for ($i = 0; $i < $count; $i++) {
            // Get or create customer
            $customerName = $customerNames[array_rand($customerNames)];
            $customer = Customer::where('name', $customerName)
                ->where('store_id', $storeId)
                ->first();

            if (!$customer && $customerName !== 'Walk-in Customer') {
                $customer = Customer::create([
                    'store_id' => $storeId,
                    'name' => $customerName,
                    'phone' => '01' . rand(2000000, 9999999),
                    'email' => strtolower(str_replace(' ', '.', $customerName)) . '@example.com',
                    'is_active' => true,
                    'created_by' => $user->id,
                ]);
            }

            // Select random status
            $orderStatus = $statuses[array_rand($statuses)];
            
            // Override status if pending-paid option is set
            if ($pendingPaid) {
                $orderStatus = 'pending';
            }
            
            // Create order
            $todayCount = Order::whereDate('created_at', today())->count();
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad($todayCount + $i + 1, 4, '0', STR_PAD_LEFT);
            
            // Ensure unique order number
            while (Order::where('order_number', $orderNumber)->exists()) {
                $todayCount++;
                $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad($todayCount + 1, 4, '0', STR_PAD_LEFT);
            }
            
            // Random items (1-5 items per order)
            $itemCount = rand(1, 5);
            $selectedProducts = $products->random(min($itemCount, $products->count()));
            
            $subtotal = 0;
            $orderItems = [];
            
            foreach ($selectedProducts as $product) {
                $quantity = rand(1, 10);
                $price = $product->price ?? rand(5, 50);
                $itemSubtotal = $quantity * $price;
                $subtotal += $itemSubtotal;
                
                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $itemSubtotal,
                ];
            }

            $taxPercent = 6;
            $tax = $subtotal * ($taxPercent / 100);
            $shipping = rand(0, 20);
            $discount = rand(0, 50);
            $total = $subtotal + $tax + $shipping - $discount;
            if ($total < 0) $total = 0;

            // Payment status based on order status or pending-paid option
            $paymentStatus = 'pending';
            $paymentMethod = null;
            $amountPaid = 0;
            
            if ($pendingPaid) {
                // Pending orders with paid status
                $paymentStatus = 'paid';
                $paymentMethod = rand(0, 1) ? 'cash' : 'qr';
                $amountPaid = $total;
            } elseif ($orderStatus === 'completed') {
                $paymentStatus = rand(0, 1) ? 'paid' : 'pending';
                $paymentMethod = $paymentStatus === 'paid' ? (rand(0, 1) ? 'cash' : 'qr') : null;
                $amountPaid = $paymentStatus === 'paid' ? $total : 0;
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'store_id' => $storeId,
                'customer_id' => $customer->id ?? null,
                'customer_name' => $customerName,
                'customer_phone' => $customer->phone ?? null,
                'customer_email' => $customer->email ?? null,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'amount_paid' => $amountPaid,
                'order_status' => $orderStatus,
                'order_tax_percent' => $taxPercent,
                'expected_completion' => Carbon::now()->addDays(rand(1, 7)),
                'special_instructions' => rand(0, 1) ? 'Special Instructions test ' . rand(1, 100) : null,
                'notes' => rand(0, 1) ? 'Test order notes ' . rand(1, 100) : null,
                'created_at' => Carbon::now()->subDays(rand(0, 10)),
            ]);

            // Create order items
            foreach ($orderItems as $itemData) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $itemData['product']->id,
                    'product_name' => $itemData['product']->name,
                    'product_sku' => $itemData['product']->sku ?? 'SKU-' . rand(1000, 9999),
                    'quantity' => $itemData['quantity'],
                    'price' => $itemData['price'],
                    'subtotal' => $itemData['subtotal'],
                ]);
            }

            // Create QC if requested
            if ($withQC && $orderStatus === 'completed') {
                QualityCheck::create([
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'store_id' => $storeId,
                    'quantity_check' => true,
                    'items_received' => $order->items->sum('quantity'),
                    'items_counted' => $order->items->sum('quantity'),
                    'quantity_match' => true,
                    'status_wash_check' => true,
                    'status_dry_check' => true,
                    'cleanliness_check' => true,
                    'cleanliness_rating' => 5,
                    'odour_check' => true,
                    'odour_rating' => 5,
                    'dryness_check' => true,
                    'dryness_rating' => 5,
                    'folding_check' => true,
                    'folding_rating' => 5,
                    'packaging_check' => true,
                    'label_correct' => true,
                    'final_approval' => true,
                    'passed' => true,
                    'approved_at' => now(),
                ]);
            }

            $createdOrders[] = $order;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('✅ Test orders created successfully!');
        $this->newLine();
        
        $this->table(
            ['Order #', 'Customer', 'Status', 'Payment', 'Total', 'Items'],
            collect($createdOrders)->map(function($order) {
                return [
                    $order->order_number,
                    $order->customer_name,
                    $order->order_status,
                    $order->payment_status . ($order->payment_method ? ' (' . $order->payment_method . ')' : ''),
                    'MYR ' . number_format($order->total, 2),
                    $order->items->sum('quantity') . ' items',
                ];
            })->toArray()
        );

        $this->newLine();
        $this->info("Total orders created: " . count($createdOrders));
        $this->info("Store: {$store->name} (ID: {$storeId})");

        return 0;
    }
}

