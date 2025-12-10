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
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->get();
        
        $products = Product::with(['category', 'brand', 'unit'])
            ->where('is_active', true)
            ->where('quantity', '>', 0)
            ->get();
        
        return view('pos', compact('categories', 'products'));
    }

    public function getProducts(Request $request)
    {
        $query = Product::with(['category', 'brand', 'unit'])
            ->where('is_active', true)
            ->where('quantity', '>', 0);

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%')
                  ->orWhere('barcode', 'like', '%' . $request->search . '%');
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
        $product = Product::with(['category', 'brand', 'unit'])->findOrFail($id);
        
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
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string',
            'tax' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
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
            $total = $subtotal + $tax - $discount;

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'shipping' => 0,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'notes' => $request->notes,
            ]);

            // Create order items and update product quantities
            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Check if enough quantity
                if ($product->quantity < $item['quantity']) {
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

                // Update product quantity
                $product->decrement('quantity', $item['quantity']);
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
        $orders = Order::with(['items', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
