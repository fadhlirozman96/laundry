<?php

namespace App\Http\Controllers;

use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $customers = Customer::orderBy('name')->get();
        
        // Get orders for creating returns
        $ordersQuery = Order::with('items');
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $ordersQuery->whereIn('store_id', $accessibleStoreIds);
        }
        if (session('selected_store_id')) {
            $ordersQuery->where('store_id', session('selected_store_id'));
        }
        $orders = $ordersQuery->orderBy('created_at', 'desc')->take(50)->get();
        
        // Check if it's a DataTables AJAX request (has 'draw' parameter)
        if ($request->ajax() || $request->has('draw')) {
            return $this->getReturnsData($request);
        }
        
        return view('sales-returns', compact('customers', 'orders'));
    }

    protected function getReturnsData(Request $request)
    {
        $user = auth()->user();
        
        $query = SalesReturn::with(['items.product', 'order', 'user', 'store']);

        // Filter by user's accessible stores
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        if (session('selected_store_id')) {
            $query->where('store_id', session('selected_store_id'));
        }

        // Apply filters
        if ($request->has('customer') && $request->customer != '') {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // DataTables processing
        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('return_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
            $totalFiltered = $query->count();
        }

        // Order
        $query->orderBy('created_at', 'desc');

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $returns = $query->skip($start)->take($length)->get();

        $data = [];
        $rowNum = $start + 1;
        foreach ($returns as $return) {
            $statusBadge = match($return->status) {
                'completed' => 'bg-lightgreen',
                'approved' => 'bg-lightyellow',
                'rejected' => 'bg-lightred',
                default => 'bg-lightred'
            };

            $paymentBadge = $return->amount_refunded >= $return->total ? 'bg-lightgreen' : ($return->amount_refunded > 0 ? 'bg-lightyellow' : 'bg-lightred');
            $paymentStatus = $return->amount_refunded >= $return->total ? 'Refunded' : ($return->amount_refunded > 0 ? 'Partial' : 'Pending');

            // Get first product name for display
            $firstItem = $return->items->first();
            $productName = $firstItem ? $firstItem->product_name : 'N/A';

            $data[] = [
                'row_number' => $rowNum++,
                'product' => $productName,
                'date' => $return->created_at->format('d M Y'),
                'customer' => $return->customer_name ?: 'Walk in Customer',
                'status' => '<span class="badges ' . $statusBadge . '">' . ucfirst($return->status) . '</span>',
                'grand_total' => 'MYR ' . number_format($return->total, 2),
                'paid' => 'MYR ' . number_format($return->amount_refunded, 2),
                'due' => 'MYR ' . number_format($return->total - $return->amount_refunded, 2),
                'payment_status' => '<span class="badges ' . $paymentBadge . '">' . $paymentStatus . '</span>',
                'action' => $this->getActionButtons($return)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($return)
    {
        return '<div class="edit-delete-action">
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="viewReturn(' . $return->id . ')" title="View">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="editReturn(' . $return->id . ')" title="Edit">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteReturn(' . $return->id . ')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:orders,id',
            'customer_name' => 'nullable|string|max:255',
            'reason' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $storeId = session('selected_store_id');

        if (!$storeId) {
            $userStores = $user->getAccessibleStores();
            if ($userStores->count() > 0) {
                $storeId = $userStores->first()->id;
            } else {
                return response()->json(['success' => false, 'message' => 'No store available'], 422);
            }
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $tax = $request->tax ?? 0;
            $total = $subtotal + $tax;

            // Get customer info from order if provided
            $customerName = $request->customer_name;
            $customerEmail = $request->customer_email;
            $customerPhone = $request->customer_phone;
            
            if ($request->order_id) {
                $order = Order::find($request->order_id);
                if ($order) {
                    $customerName = $customerName ?: $order->customer_name;
                    $customerEmail = $customerEmail ?: $order->customer_email;
                    $customerPhone = $customerPhone ?: $order->customer_phone;
                }
            }

            $return = SalesReturn::create([
                'return_number' => SalesReturn::generateReturnNumber(),
                'order_id' => $request->order_id,
                'store_id' => $storeId,
                'user_id' => auth()->id(),
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'total' => $total,
                'amount_refunded' => 0,
                'status' => 'pending',
                'reason' => $request->reason,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                SalesReturnItem::create([
                    'sales_return_id' => $return->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                // Restore product quantity
                if ($product->track_quantity) {
                    $product->increment('quantity', ceil($item['quantity']));
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Sales return created successfully',
                'return' => $return
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $return = SalesReturn::with(['items.product', 'order', 'user', 'store'])->findOrFail($id);
        return response()->json(['success' => true, 'return' => $return]);
    }

    public function updateStatus(Request $request, $id)
    {
        $return = SalesReturn::findOrFail($id);
        $return->status = $request->status;
        
        if ($request->status == 'completed' && $request->has('amount_refunded')) {
            $return->amount_refunded = $request->amount_refunded;
        }
        
        $return->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    public function processRefund(Request $request, $id)
    {
        $return = SalesReturn::findOrFail($id);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $return->amount_refunded += $request->amount;
        
        if ($return->amount_refunded >= $return->total) {
            $return->status = 'completed';
            $return->amount_refunded = $return->total;
        }
        
        $return->save();

        return response()->json(['success' => true, 'message' => 'Refund processed successfully']);
    }

    public function destroy($id)
    {
        $return = SalesReturn::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $return->items()->delete();
            $return->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sales return deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

