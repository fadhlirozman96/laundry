<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\LaundryOrderStatusLog;
use App\Models\Product;
use App\Models\Customer;
use App\Models\QualityCheck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaundryController extends Controller
{
    protected function getStoreId()
    {
        return session('store_id');
    }

    /**
     * Laundry Dashboard
     */
    public function dashboard()
    {
        $storeId = $this->getStoreId();
        
        // Get order counts by status
        $statusCounts = LaundryOrder::where('store_id', $storeId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Today's orders
        $todayOrders = LaundryOrder::where('store_id', $storeId)
            ->whereDate('created_at', today())
            ->count();

        // Pending QC
        $pendingQC = LaundryOrder::where('store_id', $storeId)
            ->where('status', 'folding')
            ->where('qc_passed', false)
            ->count();

        // Recent orders
        $recentOrders = LaundryOrder::where('store_id', $storeId)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Orders ready for collection
        $readyOrders = LaundryOrder::where('store_id', $storeId)
            ->where('status', 'ready')
            ->count();

        return view('laundry.dashboard', compact(
            'statusCounts', 'todayOrders', 'pendingQC', 'recentOrders', 'readyOrders'
        ));
    }

    /**
     * Order list
     */
    public function index(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $query = LaundryOrder::where('store_id', $storeId)
            ->with(['items', 'user']);

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
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
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Create order
            $orderNumber = LaundryOrder::generateOrderNumber($storeId);
            $qrCode = LaundryOrder::generateQRCode();

            $order = LaundryOrder::create([
                'store_id' => $storeId,
                'user_id' => $userId,
                'customer_id' => $request->customer_id,
                'order_number' => $orderNumber,
                'qr_code' => $qrCode,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_email' => $request->customer_email,
                'status' => LaundryOrder::STATUS_RECEIVED,
                'received_at' => now(),
                'expected_completion' => $request->expected_completion,
                'notes' => $request->notes,
                'special_instructions' => $request->special_instructions,
            ]);

            // Create order items
            $subtotal = 0;
            $totalItems = 0;
            $totalServices = 0;

            foreach ($request->items as $index => $item) {
                $itemSubtotal = $item['quantity'] * $item['price'];
                $subtotal += $itemSubtotal;
                $totalItems++;
                $totalServices += $item['quantity'];

                LaundryOrderItem::create([
                    'laundry_order_id' => $order->id,
                    'service_id' => $item['service_id'] ?? null,
                    'service_name' => $item['service_name'],
                    'item_code' => LaundryOrderItem::generateItemCode($order->id, $index + 1),
                    'quantity' => $item['quantity'],
                    'color' => $item['color'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'condition_notes' => $item['condition_notes'] ?? null,
                    'price' => $item['price'],
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // Calculate totals
            $taxPercent = $request->order_tax_percent ?? 6;
            $tax = $subtotal * ($taxPercent / 100);
            $shipping = $request->shipping ?? 0;
            $discount = $request->discount ?? 0;
            $couponDiscount = $request->coupon_discount ?? 0;
            $total = $subtotal + $tax + $shipping - $discount - $couponDiscount;
            
            // Ensure total is not negative
            if ($total < 0) $total = 0;

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'order_tax_percent' => $taxPercent,
                'shipping' => $shipping,
                'discount' => $discount,
                'coupon_code' => $request->coupon_code,
                'coupon_discount' => $couponDiscount,
                'total' => $total,
                'total_items' => $totalItems,
                'total_services' => $totalServices,
                'payment_method' => $request->payment_method ?? 'cash',
                'payment_status' => 'pending',
            ]);

            // Create initial status log
            LaundryOrderStatusLog::create([
                'laundry_order_id' => $order->id,
                'user_id' => $userId,
                'from_status' => null,
                'to_status' => LaundryOrder::STATUS_RECEIVED,
                'notes' => 'Order created',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order,
                'redirect' => route('laundry.show', $order->id)
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
        
        $order = LaundryOrder::where('store_id', $storeId)
            ->with(['items.service', 'statusLogs.user', 'qualityChecks.user', 'user', 'machineUsageLogs.machine'])
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
        
        $order = LaundryOrder::where('store_id', $storeId)->findOrFail($id);

        $request->validate([
            'status' => 'required|in:received,washing,drying,folding,ready,collected',
        ]);

        $newStatus = $request->status;

        // Check if can update to this status
        if (!$order->canUpdateStatusTo($newStatus)) {
            return response()->json([
                'success' => false,
                'message' => $newStatus === 'ready' && !$order->qc_passed 
                    ? 'Order must pass Quality Check before marking as Ready'
                    : 'Cannot update status'
            ], 400);
        }

        $order->updateStatus($newStatus, $userId, $request->notes);

        return response()->json([
            'success' => true,
            'message' => 'Status updated to ' . LaundryOrder::STATUSES[$newStatus],
            'order' => $order->fresh()
        ]);
    }

    /**
     * Scan QR code to find order
     */
    public function scanQR(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $order = LaundryOrder::where('store_id', $storeId)
            ->where('qr_code', $request->qr_code)
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
        
        $order = LaundryOrder::where('store_id', $storeId)
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
        $order = LaundryOrder::findOrFail($id);
        
        // Using simple QR code library or generate URL
        $qrData = route('laundry.show', $order->id);
        
        return response()->json([
            'qr_code' => $order->qr_code,
            'qr_url' => $qrData
        ]);
    }

}


