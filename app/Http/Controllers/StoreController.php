<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Only Super Admin and Business Owners can access store management
        if (!$user->isSuperAdmin() && !$user->isBusinessOwner()) {
            abort(403, 'Unauthorized access. Only Super Admin and Business Owners can manage stores.');
        }
        
        if ($user->isSuperAdmin()) {
            // Super admin sees ALL stores from ALL business owners
            $stores = Store::with(['users', 'owner'])->get();
        } else {
            // Business owner sees only their stores
            $stores = Store::where('created_by', $user->id)->with('users')->get();
        }

        return view('store-list', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'person_name' => 'required|string|max:255',
            'person_email' => 'required|email|unique:users,email',
            'person_phone' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create the store
            $store = Store::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'created_by' => auth()->id(),
                'is_active' => true,
            ]);

            // Auto-generate password (12 characters: mix of letters and numbers)
            $autoPassword = Str::random(8) . rand(1000, 9999);
            
            // Get admin role
            $adminRole = Role::admin();
            if (!$adminRole) {
                throw new \Exception('Admin role not found. Please run migrations and seeders.');
            }
            
            // Create user account for person in charge
            $personInCharge = User::create([
                'name' => $request->person_name,
                'email' => $request->person_email,
                'password' => Hash::make($autoPassword),
                'account_owner_id' => auth()->id(), // Link to business owner
            ]);

            // Assign admin role via pivot table
            $personInCharge->roles()->attach($adminRole->id);

            // Assign user to the store
            $store->users()->attach($personInCharge->id);

            DB::commit();

            // Return success message with generated password
            return redirect()->back()->with([
                'success' => 'Store created successfully! Person in charge account has been created.',
                'person_in_charge_name' => $personInCharge->name,
                'person_in_charge_email' => $personInCharge->email,
                'auto_generated_password' => $autoPassword,
                'show_password_modal' => true
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error creating store: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        $store = Store::findOrFail($id);
        
        // Check if user has permission (Super Admin or Business Owner who owns this store)
        if (!auth()->user()->isSuperAdmin() && $store->created_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $store->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'Store updated successfully!');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        
        // Check if user has permission (Super Admin or Business Owner who owns this store)
        if (!auth()->user()->isSuperAdmin() && $store->created_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $store->delete();

        return redirect()->back()->with('success', 'Store deleted successfully!');
    }

    // Assign user to store
    public function assignUser(Request $request, $storeId)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $store = Store::findOrFail($storeId);
        
        // Check if user has permission (Super Admin or Business Owner who owns this store)
        if (!auth()->user()->isSuperAdmin() && $store->created_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $store->users()->syncWithoutDetaching([$request->user_id]);

        return redirect()->back()->with('success', 'User assigned to store successfully!');
    }

    // Remove user from store
    public function removeUser($storeId, $userId)
    {
        $store = Store::findOrFail($storeId);
        
        // Check if user has permission (Super Admin or Business Owner who owns this store)
        if (!auth()->user()->isSuperAdmin() && $store->created_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $store->users()->detach($userId);

        return redirect()->back()->with('success', 'User removed from store successfully!');
    }

    // Create user for specific store
    public function createStoreUser(Request $request, $storeId)
    {
        $store = Store::findOrFail($storeId);
        
        // Check if user has permission (Super Admin or Business Owner who owns this store)
        if (!auth()->user()->isSuperAdmin() && $store->created_by !== auth()->id()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff',
        ]);

        // Get role by name
        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return redirect()->back()->with('error', 'Invalid role selected.');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_owner_id' => auth()->id(),
        ]);

        // Assign role via pivot table
        $user->roles()->attach($role->id);

        // Assign user to store
        $store->users()->attach($user->id);

        return redirect()->back()->with('success', 'User created and assigned to store successfully!');
    }

    // Select store (set active store in session)
    public function selectStore($id)
    {
        $store = Store::findOrFail($id);
        $user = auth()->user();

        // Check if store is active (Super Admin can access inactive stores)
        if (!$store->is_active && !$user->isSuperAdmin() && !$user->isBusinessOwner()) {
            return redirect()->back()->with('error', 'This store is currently inactive. Please contact the administrator.');
        }

        // Check if user has access to this store
        $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
        
        if (!in_array($store->id, $accessibleStoreIds)) {
            return redirect()->back()->with('error', 'You do not have access to this store.');
        }

        // Disable "All Stores View" when selecting a specific store
        session(['view_all_stores' => false]);
        
        // Set selected store in session
        session(['selected_store_id' => $store->id]);
        session(['selected_store_name' => $store->name]);

        return redirect()->back()->with('success', 'Store switched to: ' . $store->name);
    }
    
    // Toggle All Stores View
    public function viewAllStores()
    {
        $user = auth()->user();
        
        // Check if user has permission to view all stores
        if (!$user->hasAllStoresView()) {
            return redirect()->back()->with('error', 'Your plan does not include All Stores View feature.');
        }
        
        // Check if user has multiple stores
        $accessibleStores = $user->getAccessibleStores();
        if ($accessibleStores->count() < 2) {
            return redirect()->back()->with('error', 'You need at least 2 stores to use All Stores View.');
        }
        
        // Enable "All Stores View" mode
        session(['view_all_stores' => true]);
        session()->forget('selected_store_id'); // Clear single store selection
        
        return redirect()->back()->with('success', 'Now viewing data from all stores.');
    }

    // Toggle store active status
    public function toggleStatus($id)
    {
        $store = Store::findOrFail($id);
        $user = auth()->user();
        
        // Check if user has permission (Super Admin or Business Owner who owns this store)
        if (!$user->isSuperAdmin() && $store->created_by !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        // Toggle the status
        $store->is_active = !$store->is_active;
        $store->save();

        // If store is deactivated and it's currently selected, clear the session
        if (!$store->is_active && session('selected_store_id') == $store->id) {
            session()->forget(['selected_store_id', 'selected_store_name']);
        }

        $status = $store->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Store {$status} successfully!");
    }
}

