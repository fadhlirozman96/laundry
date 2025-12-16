<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get customers for filter dropdown
        $customers = Customer::orderBy('name')->get();
        
        // Check if it's a DataTables AJAX request (has 'draw' parameter)
        if ($request->ajax() || $request->has('draw')) {
            return $this->getSalesData($request);
        }
        
        return view('sales-list', compact('customers'));
    }

    protected function getSalesData(Request $request)
    {
        $user = auth()->user();
        
        $query = Order::with(['items', 'user', 'store'])
            ->select('orders.*');

        // Filter by user's accessible stores
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        // If a specific store is selected in session, filter by that store
        if (session('selected_store_id')) {
            $query->where('store_id', session('selected_store_id'));
        }

        // Apply filters
        if ($request->has('customer') && $request->customer != '') {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('reference') && $request->reference != '') {
            $query->where('order_number', 'like', '%' . $request->reference . '%');
        }

        // DataTables processing
        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%');
            });
            $totalFiltered = $query->count();
        }

        // Order
        $orderColumn = $request->order[0]['column'] ?? 0;
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        $columns = ['id', 'customer_name', 'order_number', 'created_at', 'order_status', 'total', 'total', 'total', 'payment_status', 'user_id', 'id'];
        
        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $orders = $query->skip($start)->take($length)->get();

        $data = [];
        $rowNum = $start + 1;
        foreach ($orders as $order) {
            $statusBadge = $order->order_status == 'completed' ? 'badge-bgsuccess' : 'badge-bgdanger';
            $paymentBadge = $order->payment_status == 'paid' ? 'badge-linesuccess' : 'badge-linedanger';
            $paid = $order->payment_status == 'paid' ? $order->total : 0;
            $due = $order->payment_status == 'paid' ? 0 : $order->total;

            $data[] = [
                'row_number' => $rowNum++,
                'customer' => $order->customer_name ?: 'Walk in Customer',
                'reference' => $order->order_number,
                'date' => $order->created_at->format('d M Y'),
                'status' => '<span class="badge ' . $statusBadge . '">' . ucfirst($order->order_status) . '</span>',
                'grand_total' => 'MYR ' . number_format($order->total, 2),
                'paid' => 'MYR ' . number_format($paid, 2),
                'due' => 'MYR ' . number_format($due, 2),
                'payment_status' => '<span class="badge ' . $paymentBadge . '">' . ucfirst($order->payment_status) . '</span>',
                'biller' => $order->user->name ?? 'Admin',
                'action' => $this->getActionButtons($order)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($order)
    {
        return '<div class="edit-delete-action">
                    <a class="action-view" href="javascript:void(0);" onclick="viewSale(' . $order->id . ')" title="View Sale">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    <a class="action-print" href="javascript:void(0);" onclick="printSale(' . $order->id . ')" title="Print">
                        <i data-feather="printer" class="feather-printer"></i>
                    </a>
                    <a class="action-delete" href="javascript:void(0);" onclick="deleteSale(' . $order->id . ')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    public function show($id)
    {
        $user = auth()->user();
        $order = Order::with(['items.product', 'user', 'store'])->findOrFail($id);

        // Check access
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($order->store_id, $accessibleStoreIds)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $order = Order::findOrFail($id);

        // Check access
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($order->store_id, $accessibleStoreIds)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        DB::beginTransaction();
        try {
            // Delete order items first
            $order->items()->delete();
            $order->delete();
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Sale deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePayment(Request $request, $id)
    {
        $user = auth()->user();
        $order = Order::findOrFail($id);

        // Check access
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($order->store_id, $accessibleStoreIds)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        $order->payment_status = $request->payment_status;
        $order->save();

        return response()->json(['success' => true, 'message' => 'Payment status updated']);
    }
}

