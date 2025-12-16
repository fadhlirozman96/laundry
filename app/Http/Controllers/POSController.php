<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Auto-set store in session if not already set
        if (!session('selected_store_id')) {
            $userStores = $user->getAccessibleStores();
            if ($userStores->count() > 0) {
                $firstStore = $userStores->first();
                session(['selected_store_id' => $firstStore->id]);
                session(['selected_store_name' => $firstStore->name]);
            }
        }
        
        // Filter categories by user's accessible stores
        $query = Category::where('is_active', true);
        
        // Filter products by user's accessible stores
        // Only show products with quantity tracking disabled (for laundry services)
        $productQuery = Product::with(['category', 'unit'])
            ->where('is_active', true)
            ->where('track_quantity', false);
        
        // Apply store filtering
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $productQuery->whereIn('store_id', $accessibleStoreIds);
        }
        
        // If a specific store is selected in session, filter by that store
        if (session('selected_store_id')) {
            $productQuery->where('store_id', session('selected_store_id'));
        }
        
        $products = $productQuery->get();
        
        // Get categories that have products in the filtered product list
        $productCategoryIds = $products->pluck('category_id')->unique()->filter();
        $categoriesQuery = Category::where('is_active', true);
        
        if ($productCategoryIds->isNotEmpty()) {
            $categoriesQuery->whereIn('id', $productCategoryIds);
        } else {
            // If no products, return empty categories
            $categoriesQuery->whereRaw('1 = 0');
        }
        
        $categories = $categoriesQuery->withCount(['products' => function($q) use ($user) {
                $q->where('is_active', true)
                  ->where('track_quantity', false);
                if (!$user->isSuperAdmin()) {
                    $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                    $q->whereIn('store_id', $accessibleStoreIds);
                }
                if (session('selected_store_id')) {
                    $q->where('store_id', session('selected_store_id'));
                }
            }])
            ->get();
        
        return view('pos', compact('categories', 'products'));
    }

    public function getProducts(Request $request)
    {
        $user = auth()->user();
        
        $query = Product::with(['category', 'unit'])
            ->where('is_active', true)
            ->where('track_quantity', false);

        // Apply store filtering
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        // If a specific store is selected in session, filter by that store
        if (session('selected_store_id')) {
            $query->where('store_id', session('selected_store_id'));
        }

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        $products = $query->get();

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }

    public function getProduct($id)
    {
        $user = auth()->user();
        
        $product = Product::with(['category', 'unit'])->findOrFail($id);
        
        // Check if user has access to this product's store
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($product->store_id, $accessibleStoreIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this product.'
                ], 403);
            }
        }
        
        return response()->json([
            'success' => true,
            'product' => $product
        ]);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'shipping' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $shipping = $request->shipping ?? 0;
            $total = $subtotal + $tax + $shipping - $discount;

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Get store_id from session or from user's first accessible store
            $storeId = session('selected_store_id');
            if (!$storeId) {
                $userStores = $user->getAccessibleStores();
                if ($userStores->count() > 0) {
                    $storeId = $userStores->first()->id;
                } else {
                    throw new \Exception('No store available for this user. Please contact administrator.');
                }
            }

            // Verify the store exists
            $store = \App\Models\Store::find($storeId);
            if (!$store) {
                throw new \Exception('Invalid store selected.');
            }

            // Handle coupon if provided
            $couponId = $request->coupon_id;
            if ($couponId) {
                $coupon = \App\Models\Coupon::find($couponId);
                if ($coupon) {
                    $coupon->incrementUsage();
                }
            }
            
            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'store_id' => $storeId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'shipping' => $shipping,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'notes' => $request->notes,
                'coupon_id' => $couponId,
            ]);

            $user = auth()->user();
            $selectedStoreId = session('selected_store_id');
            
            // Create order items and update product quantities
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Verify product belongs to selected store
                if ($selectedStoreId && $product->store_id != $selectedStoreId) {
                    throw new \Exception("Product '{$product->name}' does not belong to the selected store.");
                }
                
                // Verify user has access to this product's store
                if (!$user->isSuperAdmin()) {
                    $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                    if (!in_array($product->store_id, $accessibleStoreIds)) {
                        throw new \Exception("You do not have access to product '{$product->name}'.");
                    }
                }

                // Check if enough quantity (convert to integer for comparison if needed)
                $requiredQty = ceil($item['quantity']); // Round up for kg-based products
                if ($product->quantity < $requiredQty) {
                    throw new \Exception("Insufficient quantity for product: {$product->name}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Update product quantity (round up for kg-based products)
                $decrementQty = ceil($item['quantity']);
                $product->decrement('quantity', $decrementQty);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order completed successfully',
                'order' => $order->load('items'),
                'order_number' => $orderNumber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function getOrders(Request $request)
    {
        $user = auth()->user();
        $query = Order::with(['items.product', 'user', 'store']);
        
        // Filter by user's accessible stores
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        // If a specific store is selected in session, filter by that store
        if (session('selected_store_id')) {
            $query->where('store_id', session('selected_store_id'));
        }
        
        // Filter by type if requested
        if ($request->has('type')) {
            if ($request->type === 'recent') {
                // Last 20 transactions
                $query->orderBy('created_at', 'desc')->limit(20);
                $orders = $query->get();
            } else if ($request->type === 'pending') {
                // Only pending orders
                $query->where('payment_status', 'pending')->orderBy('created_at', 'desc');
                $orders = $query->get();
            } else if ($request->type === 'all') {
                // All orders (not paginated for modal display)
                $query->orderBy('created_at', 'desc')->limit(50);
                $orders = $query->get();
            } else {
                // Default: paginated list
                $query->orderBy('created_at', 'desc');
                $orders = $query->paginate(10);
            }
        } else {
            // All orders (paginated for table views)
            $query->orderBy('created_at', 'desc');
            $orders = $query->paginate(10);
        }

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    public function getOrder($id)
    {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
}
