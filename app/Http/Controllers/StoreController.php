<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super admin sees ALL stores from ALL business owners
            $stores = Store::with(['users', 'owner'])->get();
        } elseif ($user->isBusinessOwner()) {
            // Business owner sees only their stores
            $stores = Store::where('created_by', $user->id)->with('users')->get();
        } else {
            // Staff sees only their assigned stores
            $stores = $user->stores()->with('users')->get();
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
        ]);

        $store = Store::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'created_by' => auth()->id(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Store created successfully!');
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'account_owner_id' => auth()->id(),
        ]);

        // Assign user to store
        $store->users()->attach($user->id);

        return redirect()->back()->with('success', 'User created and assigned to store successfully!');
    }

    // Select store (set active store in session)
    public function selectStore($id)
    {
        $store = Store::findOrFail($id);
        $user = auth()->user();

        // Check if user has access to this store
        $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
        
        if (!in_array($store->id, $accessibleStoreIds)) {
            return redirect()->back()->with('error', 'You do not have access to this store.');
        }

        // Set selected store in session
        session(['selected_store_id' => $store->id]);
        session(['selected_store_name' => $store->name]);

        return redirect()->back()->with('success', 'Store switched to: ' . $store->name);
    }
}

