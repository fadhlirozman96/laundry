<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
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
}

