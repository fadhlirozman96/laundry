<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Handle DataTables AJAX request
        if ($request->ajax() || $request->has('draw')) {
            $query = Order::with(['items', 'user', 'store']);
            
            // Filter by user's accessible stores
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                $query->whereIn('store_id', $accessibleStoreIds);
            }
            
            // If a specific store is selected in session, filter by that store
            if (session('selected_store_id')) {
                $query->where('store_id', session('selected_store_id'));
            }
            
            // DataTables search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', '%' . $search . '%')
                      ->orWhere('customer_name', 'like', '%' . $search . '%')
                      ->orWhere('customer_email', 'like', '%' . $search . '%')
                      ->orWhere('customer_phone', 'like', '%' . $search . '%');
                });
            }
            
            // Get total records (filtered by store access)
            $totalQuery = Order::query();
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                $totalQuery->whereIn('store_id', $accessibleStoreIds);
            }
            if (session('selected_store_id')) {
                $totalQuery->where('store_id', session('selected_store_id'));
            }
            $totalRecords = $totalQuery->count();
            $filteredRecords = $query->count();
            
            // DataTables ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                
                // Column mapping: 0=checkbox, 1=order_number, 2=customer, 3=store, 4=items, 5=total, 6=payment_method, 7=status, 8=date, 9=action
                $columns = ['id', 'order_number', 'customer_name', 'store_id', 'id', 'total', 'payment_method', 'payment_status', 'created_at', 'id'];
                if ($orderColumnIndex > 0 && $orderColumnIndex < 10) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }
            
            // DataTables pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $orders = $query->skip($start)->take($length)->get();
            
            $data = [];
            foreach ($orders as $order) {
                $statusBadge = $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning';
                
                // Count total items
                $itemCount = $order->items->sum('quantity');
                
                $data[] = [
                    'checkbox' => '<label class="checkboxs"><input type="checkbox"><span class="checkmarks"></span></label>',
                    'order_number' => '<strong>' . $order->order_number . '</strong>',
                    'customer' => $order->customer_name ?: 'Walk-in Customer',
                    'store' => '<span class="badge bg-outline-primary">' . ($order->store->name ?? 'No Store') . '</span>',
                    'items' => $order->items->count() . ' items (' . $itemCount . ' qty)',
                    'total' => '<strong>MYR ' . number_format($order->total, 2) . '</strong>',
                    'payment_method' => ucfirst($order->payment_method ?? 'N/A'),
                    'status' => '<span class="badge ' . $statusBadge . '">' . $order->payment_status . '</span>',
                    'date' => $order->created_at->format('Y-m-d H:i'),
                    'action' => '<div class="edit-delete-action">
                                    <a class="me-2 p-2" href="javascript:void(0);" onclick="viewOrder(' . $order->id . ')">
                                        <i data-feather="eye" class="feather-eye"></i>
                                    </a>
                                    <a class="me-2 p-2" href="javascript:void(0);">
                                        <i data-feather="printer" class="feather-printer"></i>
                                    </a>
                                 </div>'
                ];
            }
            
            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        }
        
        // Regular page load
        return view('purchase-list');
    }
    
    public function show($id)
    {
        $order = Order::with(['items.product', 'user', 'store'])->findOrFail($id);
        
        // Check if user has access to this order's store
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $order->store_id) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($order->store_id, $accessibleStoreIds)) {
                abort(403, 'You do not have access to this order.');
            }
        }
        
        return response()->json([
            'success' => true,
            'order' => $order
        ]);
    }
}

