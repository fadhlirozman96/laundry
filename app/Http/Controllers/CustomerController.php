<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    protected function getStoreId()
    {
        $user = auth()->user();
        $storeId = session('selected_store_id');
        
        if (!$storeId) {
            $userStores = $user->getAccessibleStores();
            if ($userStores->count() > 0) {
                $storeId = $userStores->first()->id;
            }
        }
        
        return $storeId;
    }

    // List all customers (for customer management page)
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTableData($request);
        }
        
        return view('customers');
    }

    protected function getDataTableData(Request $request)
    {
        $user = auth()->user();
        $storeId = $this->getStoreId();

        if (!$storeId) {
            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ]);
        }

        // Base query - only customers for accessible stores
        if ($user->isSuperAdmin()) {
            $query = Customer::with(['store', 'creator']);
        } else {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query = Customer::with(['store', 'creator'])
                            ->whereIn('store_id', $accessibleStoreIds);
        }

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        $totalData = $query->count();
        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columns = ['id', 'name', 'id', 'name', 'email', 'phone', 'country', 'id'];
            $orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
            $orderDir = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $customers = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($customers as $customer) {
            $data[] = [
                'checkbox' => '<label class="checkboxs"><input type="checkbox" value="'.$customer->id.'"><span class="checkmarks"></span></label>',
                'customer_name' => '<div class="userimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            <div class="avatar avatar-sm bg-light-primary text-primary rounded-circle">
                                                '.strtoupper(substr($customer->name, 0, 1)).'
                                            </div>
                                        </a>
                                        <a href="javascript:void(0);">'.$customer->name.'</a>
                                    </div>',
                'code' => $customer->id,
                'customer' => $customer->name,
                'email' => $customer->email ?? 'N/A',
                'phone' => $customer->phone ?? 'N/A',
                'country' => $customer->country ?? 'N/A',
                'action' => $this->getActionButtons($customer)
            ];
        }

        return response()->json([
            'draw' => intval($request->get('draw')),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($customer)
    {
        return '<div class="edit-delete-action">
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="viewCustomer('.$customer->id.')" title="View">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="editCustomer('.$customer->id.')" title="Edit">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteCustomer('.$customer->id.')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    // Get customers for a store (for POS dropdown)
    public function getCustomers(Request $request)
    {
        $user = auth()->user();
        $storeId = session('selected_store_id');
        
        if (!$storeId) {
            // Get first accessible store
            $userStores = $user->getAccessibleStores();
            if ($userStores->count() > 0) {
                $storeId = $userStores->first()->id;
            }
        }
        
        $query = Customer::where('store_id', $storeId)
                        ->where('is_active', true)
                        ->orderBy('name', 'asc');
        
        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        $customers = $query->get();
        
        return response()->json([
            'success' => true,
            'customers' => $customers
        ]);
    }
    
    // Create new customer (from POS)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);
        
        $user = auth()->user();
        $storeId = session('selected_store_id');
        
        // Fallback to first accessible store
        if (!$storeId) {
            $userStores = $user->getAccessibleStores();
            if ($userStores->count() > 0) {
                $storeId = $userStores->first()->id;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No store available. Please select a store first.'
                ], 400);
            }
        }
        
        // Verify user has access to this store
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($storeId, $accessibleStoreIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this store.'
                ], 403);
            }
        }
        
        try {
            $customer = Customer::create([
                'store_id' => $storeId,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'city' => $request->city,
                'country' => $request->country,
                'is_active' => true,
                'created_by' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully!',
                'customer' => $customer
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating customer: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $customer = Customer::with(['store', 'orders'])->findOrFail($id);

        // Check access
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($customer->store_id, $accessibleStoreIds)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        return response()->json([
            'success' => true,
            'customer' => $customer
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $customer = Customer::findOrFail($id);

        // Check access
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($customer->store_id, $accessibleStoreIds)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        $customer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully!',
            'customer' => $customer
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $customer = Customer::findOrFail($id);

        // Check access
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($customer->store_id, $accessibleStoreIds)) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
        }

        // Check if customer has orders
        if ($customer->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete customer with existing orders.'
            ], 400);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully!'
        ]);
    }
}

