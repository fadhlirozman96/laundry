<?php

namespace App\Http\Controllers;

use App\Models\LaundryOrder;
use App\Models\LaundryOrderItem;
use App\Models\LaundryOrderStatusLog;
use App\Models\GarmentType;
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
        
        $garmentTypes = GarmentType::where('store_id', $storeId)
            ->where('is_active', true)
            ->get();
        
        $customers = Customer::where('store_id', $storeId)
            ->where('is_active', true)
            ->get();

        return view('laundry.create-order', compact('garmentTypes', 'customers'));
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
            'items.*.garment_type_id' => 'nullable|exists:garment_types,id',
            'items.*.garment_name' => 'required|string|max:255',
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
            $totalGarments = 0;

            foreach ($request->items as $index => $item) {
                $itemSubtotal = $item['quantity'] * $item['price'];
                $subtotal += $itemSubtotal;
                $totalItems++;
                $totalGarments += $item['quantity'];

                LaundryOrderItem::create([
                    'laundry_order_id' => $order->id,
                    'garment_type_id' => $item['garment_type_id'] ?? null,
                    'garment_name' => $item['garment_name'],
                    'garment_code' => LaundryOrderItem::generateGarmentCode($order->id, $index + 1),
                    'quantity' => $item['quantity'],
                    'color' => $item['color'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'condition_notes' => $item['condition_notes'] ?? null,
                    'price' => $item['price'],
                    'subtotal' => $itemSubtotal,
                ]);
            }

            // Calculate totals
            $tax = $subtotal * 0.06; // 6% tax
            $total = $subtotal + $tax - ($request->discount ?? 0);

            $order->update([
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $request->discount ?? 0,
                'total' => $total,
                'total_items' => $totalItems,
                'total_garments' => $totalGarments,
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
            ->with(['items.garmentType', 'statusLogs.user', 'qualityChecks.user', 'user', 'machineUsageLogs.machine'])
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

    // ========== GARMENT TYPES ==========

    public function garmentTypes()
    {
        $storeId = $this->getStoreId();
        $garmentTypes = GarmentType::where('store_id', $storeId)->get();
        return view('laundry.garment-types', compact('garmentTypes'));
    }

    public function storeGarmentType(Request $request)
    {
        $storeId = $this->getStoreId();

        $request->validate([
            'name' => 'required|string|max:255',
            'default_price' => 'required|numeric|min:0',
        ]);

        GarmentType::create([
            'store_id' => $storeId,
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'default_price' => $request->default_price,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Garment type added']);
    }

    public function updateGarmentType(Request $request, $id)
    {
        $storeId = $this->getStoreId();
        $garmentType = GarmentType::where('store_id', $storeId)->findOrFail($id);

        $garmentType->update($request->only(['name', 'category', 'description', 'default_price', 'is_active']));

        return response()->json(['success' => true, 'message' => 'Garment type updated']);
    }

    public function deleteGarmentType($id)
    {
        $storeId = $this->getStoreId();
        GarmentType::where('store_id', $storeId)->findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Garment type deleted']);
    }
}

