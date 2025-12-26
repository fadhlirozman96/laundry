<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LaundryController extends Controller
{
    protected function getStoreId()
    {
        // Check for selected_store_id first (from POS store selector)
        $storeId = session('selected_store_id');
        
        if (!$storeId) {
            // Fallback to user's first accessible store
            $user = auth()->user();
            if ($user) {
                $userStores = $user->getAccessibleStores();
                if ($userStores->count() > 0) {
                    $storeId = $userStores->first()->id;
                    // Set it in session for future use
                    session(['selected_store_id' => $storeId]);
                }
            }
        }
        
        return $storeId;
    }

    /**
     * Laundry Dashboard
     */
    public function dashboard()
    {
        $storeId = $this->getStoreId();
        
        // Get order counts by actual status
        $pendingCount = Order::where('store_id', $storeId)
            ->where('order_status', 'pending')
            ->count();
            
        $processingCount = Order::where('store_id', $storeId)
            ->where('order_status', 'processing')
            ->count();
            
        $completedCount = Order::where('store_id', $storeId)
            ->where('order_status', 'completed')
            ->count();
            
        $cancelledCount = Order::where('store_id', $storeId)
            ->where('order_status', 'cancelled')
            ->count();

        // Today's metrics
        $todayOrders = Order::where('store_id', $storeId)
            ->whereDate('created_at', today())
            ->count();
            
        $todayRevenue = Order::where('store_id', $storeId)
            ->whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total');

        // Pending QC (orders in 'processing' status without a quality check)
        $pendingQC = Order::where('store_id', $storeId)
            ->where('order_status', 'processing')
            ->doesntHave('qualityCheck')
            ->count();
            
        // Orders with QC passed (ready for collection)
        $qcPassed = Order::where('store_id', $storeId)
            ->whereHas('qualityCheck', function($q) {
                $q->where('passed', true);
            })
            ->where('order_status', 'completed')
            ->count();

        // Recent orders
        $recentOrders = Order::where('store_id', $storeId)
            ->with(['items.product', 'qualityCheck'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Total orders
        $totalOrders = Order::where('store_id', $storeId)->count();
        
        // Total revenue (paid orders)
        $totalRevenue = Order::where('store_id', $storeId)
            ->where('payment_status', 'paid')
            ->sum('total');

        return view('laundry.dashboard', compact(
            'pendingCount', 'processingCount', 'completedCount', 'cancelledCount',
            'todayOrders', 'todayRevenue', 'pendingQC', 'qcPassed',
            'recentOrders', 'totalOrders', 'totalRevenue'
        ));
    }

    /**
     * Order list
     */
    public function index(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $query = Order::where('store_id', $storeId)
            ->with(['items.product', 'user', 'customer', 'qualityCheck']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('order_status', $request->status);
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('laundry.orders', compact('orders'));
    }

    /**
     * Create new order form
     */
    public function create()
    {
        $storeId = $this->getStoreId();
        
        // Get services for this store
        $services = Product::where('store_id', $storeId)
            ->where('is_active', true)
            ->with(['category', 'unit'])
            ->orderBy('name')
            ->get();
        
        // If no services found for this store, get all services as fallback
        if ($services->isEmpty()) {
            $services = Product::where('is_active', true)
                ->with(['category', 'unit'])
                ->orderBy('name')
                ->get();
        }
        
        $customers = Customer::where('store_id', $storeId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('laundry.create-order', compact('services', 'customers'));
    }

    /**
     * Store new order
     */
    public function store(Request $request)
    {
        $storeId = $this->getStoreId();
        $userId = auth()->id();

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'nullable|exists:products,id',
            'items.*.service_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            // Verify store ID
            if (!$storeId) {
                throw new \Exception('No store selected. Please select a store from the POS System page first.');
            }

            // Handle customer - create or find existing customer if not walk-in
            $customerId = $request->customer_id;
            $customerName = $request->customer_name;
            $customerEmail = $request->customer_email;
            $customerPhone = $request->customer_phone;
            
            // Only create/link customer if name is provided and is not "Walk-in Customer"
            if ($customerName && trim(strtolower($customerName)) !== 'walk-in customer') {
                // Try to find existing customer by phone or email in the same store
                $customer = null;
                
                if ($customerPhone) {
                    $customer = Customer::where('store_id', $storeId)
                                      ->where('phone', $customerPhone)
                                      ->first();
                }
                
                if (!$customer && $customerEmail) {
                    $customer = Customer::where('store_id', $storeId)
                                      ->where('email', $customerEmail)
                                      ->first();
                }
                
                // If customer not found, create new one
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $customerName,
                        'email' => $customerEmail,
                        'phone' => $customerPhone,
                        'store_id' => $storeId,
                        'is_active' => true,
                        'created_by' => $userId,
                    ]);
                } else {
                    // Update existing customer info if provided
                    $updateData = [];
                    if ($customerName && $customer->name !== $customerName) {
                        $updateData['name'] = $customerName;
                    }
                    if ($customerEmail && $customer->email !== $customerEmail) {
                        $updateData['email'] = $customerEmail;
                    }
                    if ($customerPhone && $customer->phone !== $customerPhone) {
                        $updateData['phone'] = $customerPhone;
                    }
                    if (!empty($updateData)) {
                        $customer->update($updateData);
                    }
                }
                
                $customerId = $customer->id;
            }

            // Calculate totals first
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['price'];
            }
            
            $taxPercent = $request->order_tax_percent ?? 6;
            $tax = $subtotal * ($taxPercent / 100);
            $shipping = $request->shipping ?? 0;
            $discount = $request->discount ?? 0;
            $total = $subtotal + $tax + $shipping - $discount;
            
            // Ensure total is not negative
            if ($total < 0) $total = 0;

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Determine payment status based on payment method
            $paymentMethod = $request->payment_method ?? 'cash';
            $paymentStatus = 'pending';
            
            // Log payment method for debugging
            \Log::info('Order payment method received: ' . $paymentMethod);
            
            // If cash or QR payment is selected, mark as paid immediately
            if (in_array($paymentMethod, ['cash', 'qr', 'card', 'debit_card'])) {
                $paymentStatus = 'paid';
            }

            $order = Order::create([
                'order_number' => $orderNumber,
                'store_id' => $storeId,
                'user_id' => $userId,
                'customer_id' => $customerId,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'customer_email' => $customerEmail,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'shipping' => $shipping,
                'total' => $total,
                'order_status' => 'pending',
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'expected_completion' => $request->expected_completion,
                'notes' => $request->notes,
                'special_instructions' => $request->special_instructions,
            ]);

            // Create order items
            foreach ($request->items as $index => $item) {
                $itemSubtotal = $item['quantity'] * $item['price'];

                // Get product details
                $product = null;
                if (!empty($item['service_id'])) {
                    $product = Product::find($item['service_id']);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['service_id'] ?? null,
                    'product_name' => $item['service_name'],
                    'product_sku' => $product ? $product->sku : 'N/A',
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => 0,
                    'tax' => 0,
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // Generate receipts if payment is paid
            if ($paymentStatus === 'paid') {
                try {
                    $receiptController = new \App\Http\Controllers\ReceiptController();
                    // Generate regular receipt
                    $receiptController->generate($order->id);
                    // Generate thermal receipt
                    $receiptController->generateThermal($order->id);
                } catch (\Exception $e) {
                    // Log error but don't fail the order creation
                    \Log::error('Failed to generate receipts for order ' . $order->order_number . ': ' . $e->getMessage());
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order_number' => $orderNumber,
                'redirect' => route('laundry.orders')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating order: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show order details
     */
    public function show($id)
    {
        $storeId = $this->getStoreId();
        
        $order = Order::where('store_id', $storeId)
            ->with(['items.product', 'user', 'customer'])
            ->findOrFail($id);

        return view('laundry.order-details', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $storeId = $this->getStoreId();
        $userId = auth()->id();
        
        $order = Order::where('store_id', $storeId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update([
            'order_status' => $request->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . $request->status,
            'order' => $order->fresh()
        ]);
    }

    /**
     * Scan QR code to find order
     */
    public function scanQR(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $order = Order::where('store_id', $storeId)
            ->where('order_number', $request->order_number)
            ->with('items')
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    /**
     * Get order by order number
     */
    public function findByOrderNumber(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $order = Order::where('store_id', $storeId)
            ->where('order_number', $request->order_number)
            ->with('items')
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    /**
     * Generate QR code image
     */
    public function generateQRImage($id)
    {
        $order = Order::findOrFail($id);
        
        // Using simple QR code library or generate URL
        $qrData = route('laundry.show', $order->id);
        
        return response()->json([
            'qr_code' => $order->qr_code,
            'qr_url' => $qrData
        ]);
    }

}



