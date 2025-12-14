<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Only Business Owners can access employee management
        if (!$user->isBusinessOwner()) {
            abort(403, 'Unauthorized access. Only Business Owners can manage employees.');
        }
        
        // Get all staff users created by this business owner
        $employees = User::where('account_owner_id', $user->id)
            ->whereIn('role', ['admin', 'staff'])
            ->with(['stores'])
            ->get();
        
        // Get all stores owned by this business owner
        $stores = Store::where('created_by', $user->id)->where('is_active', true)->get();
        
        return view('employees-grid', compact('employees', 'stores'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Only Business Owners can create employees
        if (!$user->isBusinessOwner()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,staff',
            'store_id' => 'required|exists:stores,id',
        ]);
        
        // Verify the store belongs to this business owner
        $store = Store::findOrFail($request->store_id);
        if ($store->created_by !== $user->id) {
            return redirect()->back()->with('error', 'You can only assign employees to your own stores.');
        }
        
        // Create the employee
        $employee = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'account_owner_id' => $user->id,
        ]);
        
        // Assign employee to store
        $store->users()->attach($employee->id);
        
        return redirect()->back()->with('success', 'Employee created and assigned to store successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        
        // Only Business Owners can update employees
        if (!$user->isBusinessOwner()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $employee = User::findOrFail($id);
        
        // Verify the employee belongs to this business owner
        if ($employee->account_owner_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($id)],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,staff',
            'store_id' => 'required|exists:stores,id',
        ]);
        
        // Verify the store belongs to this business owner
        $store = Store::findOrFail($request->store_id);
        if ($store->created_by !== $user->id) {
            return redirect()->back()->with('error', 'You can only assign employees to your own stores.');
        }
        
        // Update employee
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
        
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        $employee->update($updateData);
        
        // Update store assignment (sync to ensure only one store)
        $employee->stores()->sync([$request->store_id]);
        
        return redirect()->back()->with('success', 'Employee updated successfully!');
    }

    public function destroy($id)
    {
        $user = auth()->user();
        
        // Only Business Owners can delete employees
        if (!$user->isBusinessOwner()) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        $employee = User::findOrFail($id);
        
        // Verify the employee belongs to this business owner
        if ($employee->account_owner_id !== $user->id) {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }
        
        // Remove from all stores
        $employee->stores()->detach();
        
        // Delete employee
        $employee->delete();
        
        return redirect()->back()->with('success', 'Employee deleted successfully!');
    }
}

