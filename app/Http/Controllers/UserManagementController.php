<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Store;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    /**
     * Check if user is Super Admin
     */
    protected function isSuperAdmin()
    {
        return auth()->user()->isSuperAdmin();
    }

    /**
     * Check if user is Business Owner
     */
    protected function isBusinessOwner()
    {
        return auth()->user()->isBusinessOwner();
    }

    /**
     * Users List - Super Admin sees all, Business Owner sees their staff only
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            // Super Admin sees all users
            $users = User::with('roles')->get();
            $roles = Role::all();
            $stores = Store::all();
        } elseif ($user->isBusinessOwner()) {
            // Business Owner sees only their staff (admin/staff created under them)
            $users = User::where('account_owner_id', $user->id)
                ->with('roles', 'stores')
                ->get();
            $roles = Role::whereIn('name', ['admin', 'staff'])->get();
            $stores = Store::where('created_by', $user->id)->where('is_active', true)->get();
        } else {
            abort(403, 'Unauthorized access');
        }

        return view('users', compact('users', 'roles', 'stores'));
    }

    /**
     * Get users data for AJAX DataTable
     */
    public function getUsersData(Request $request)
    {
        $user = auth()->user();
        
        if ($user->isSuperAdmin()) {
            $query = User::with('roles');
        } elseif ($user->isBusinessOwner()) {
            $query = User::where('account_owner_id', $user->id)->with('roles', 'stores');
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return datatables()->of($query)
            ->addColumn('checkbox', function($row) {
                return '<label class="checkboxs"><input type="checkbox" value="'.$row->id.'"><span class="checkmarks"></span></label>';
            })
            ->addColumn('user_display', function($row) {
                $image = asset('build/img/users/user-01.jpg');
                return '<div class="userimgname">
                    <a href="javascript:void(0);" class="userslist-img bg-img">
                        <img src="'.$image.'" alt="user">
                    </a>
                    <div>
                        <a href="javascript:void(0);">'.$row->name.'</a>
                    </div>
                </div>';
            })
            ->addColumn('role_display', function($row) {
                $role = $row->roles->first();
                return $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'N/A';
            })
            ->addColumn('status_display', function($row) {
                // Assuming is_active field or default to active
                return '<span class="badge badge-linesuccess">Active</span>';
            })
            ->addColumn('created_date', function($row) {
                return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
            })
            ->addColumn('actions', function($row) use ($user) {
                $actions = '<div class="edit-delete-action">';
                $actions .= '<a class="me-2 p-2 mb-0" href="javascript:void(0);" onclick="viewUser('.$row->id.')"><i data-feather="eye" class="action-eye"></i></a>';
                $actions .= '<a class="me-2 p-2 mb-0" href="javascript:void(0);" onclick="editUser('.$row->id.')"><i data-feather="edit" class="feather-edit"></i></a>';
                
                // Don't allow deleting yourself or super admin
                if ($row->id != $user->id && !$row->isSuperAdmin()) {
                    $actions .= '<a class="me-2 confirm-text p-2 mb-0" href="javascript:void(0);" onclick="deleteUser('.$row->id.')"><i data-feather="trash-2" class="feather-trash-2"></i></a>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['checkbox', 'user_display', 'status_display', 'actions'])
            ->make(true);
    }

    /**
     * Store a new user
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            $newUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'account_owner_id' => $user->isBusinessOwner() ? $user->id : null,
            ]);

            // Attach role
            $newUser->roles()->attach($request->role_id);

            // Attach stores if business owner is creating
            if ($user->isBusinessOwner() && $request->has('store_ids')) {
                $newUser->stores()->attach($request->store_ids);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User created successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error creating user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get user details
     */
    public function show($id)
    {
        $authUser = auth()->user();
        $user = User::with('roles', 'stores')->findOrFail($id);

        // Check access
        if (!$authUser->isSuperAdmin() && $user->account_owner_id != $authUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($user);
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        // Check access
        if (!$authUser->isSuperAdmin() && $user->account_owner_id != $authUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role_id' => 'required|exists:roles,id',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            if ($request->filled('password')) {
                $user->update(['password' => Hash::make($request->password)]);
            }

            // Update role
            $user->roles()->sync([$request->role_id]);

            // Update stores if provided
            if ($request->has('store_ids')) {
                $user->stores()->sync($request->store_ids);
            }

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error updating user: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id == $authUser->id) {
            return response()->json(['error' => 'Cannot delete yourself'], 400);
        }

        // Prevent deleting super admin
        if ($user->isSuperAdmin()) {
            return response()->json(['error' => 'Cannot delete super admin'], 400);
        }

        // Check access for business owner
        if (!$authUser->isSuperAdmin() && $user->account_owner_id != $authUser->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    // ========== ROLES & PERMISSIONS (Super Admin Only) ==========

    /**
     * Roles & Permissions page
     */
    public function rolesPermissions()
    {
        if (!$this->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only Super Admin can access Roles & Permissions.');
        }

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('roles-permissions', compact('roles', 'permissions'));
    }

    /**
     * Get roles data
     */
    public function getRolesData()
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $roles = Role::all();

        return datatables()->of($roles)
            ->addColumn('checkbox', function($row) {
                return '<label class="checkboxs"><input type="checkbox" value="'.$row->id.'"><span class="checkmarks"></span></label>';
            })
            ->addColumn('role_name', function($row) {
                return ucfirst(str_replace('_', ' ', $row->name));
            })
            ->addColumn('created_date', function($row) {
                return $row->created_at ? $row->created_at->format('d M Y') : 'N/A';
            })
            ->addColumn('actions', function($row) {
                $actions = '<div class="edit-delete-action">';
                $actions .= '<a class="me-2 p-2" href="javascript:void(0);" onclick="editRole('.$row->id.')"><i data-feather="edit" class="feather-edit"></i></a>';
                $actions .= '<a class="p-2 me-2" href="'.route('permissions', ['role_id' => $row->id]).'"><i data-feather="shield" class="shield"></i></a>';
                
                // Don't allow deleting system roles
                if (!in_array($row->name, ['super_admin', 'business_owner', 'admin', 'staff'])) {
                    $actions .= '<a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteRole('.$row->id.')"><i data-feather="trash-2" class="feather-trash-2"></i></a>';
                }
                
                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['checkbox', 'actions'])
            ->make(true);
    }

    /**
     * Create role
     */
    public function storeRole(Request $request)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $role = Role::create([
            'name' => strtolower(str_replace(' ', '_', $request->name)),
            'display_name' => $request->display_name ?? $request->name,
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Role created successfully', 'role' => $role]);
    }

    /**
     * Update role
     */
    public function updateRole(Request $request, $id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $role = Role::findOrFail($id);

        // Prevent editing system roles name
        if (in_array($role->name, ['super_admin', 'business_owner', 'admin', 'staff'])) {
            $request->validate([
                'display_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);
            
            $role->update([
                'display_name' => $request->display_name,
                'description' => $request->description,
            ]);
        } else {
            $request->validate([
                'name' => 'required|string|max:255|unique:roles,name,' . $id,
                'display_name' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            $role->update([
                'name' => strtolower(str_replace(' ', '_', $request->name)),
                'display_name' => $request->display_name ?? $request->name,
                'description' => $request->description,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Role updated successfully']);
    }

    /**
     * Delete role
     */
    public function destroyRole($id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $role = Role::findOrFail($id);

        // Prevent deleting system roles
        if (in_array($role->name, ['super_admin', 'business_owner', 'admin', 'staff'])) {
            return response()->json(['error' => 'Cannot delete system roles'], 400);
        }

        $role->delete();

        return response()->json(['success' => true, 'message' => 'Role deleted successfully']);
    }

    // ========== DELETE ACCOUNT REQUESTS (Super Admin Only) ==========

    /**
     * Delete account requests page
     */
    public function deleteAccountRequests()
    {
        if (!$this->isSuperAdmin()) {
            abort(403, 'Unauthorized. Only Super Admin can access Delete Account Requests.');
        }

        // For now, show all business owners who might want to delete their accounts
        // In a real system, you'd have a delete_requests table
        $users = User::whereHas('roles', function($q) {
            $q->where('name', 'business_owner');
        })->get();

        return view('delete-account', compact('users'));
    }

    /**
     * Process delete account request
     */
    public function processDeleteRequest($id)
    {
        if (!$this->isSuperAdmin()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);

        // Delete all related data
        DB::beginTransaction();
        try {
            // Delete user's stores and related data
            $stores = Store::where('created_by', $user->id)->get();
            foreach ($stores as $store) {
                // Delete store-related data would go here
                $store->delete();
            }

            // Delete sub-users
            User::where('account_owner_id', $user->id)->delete();

            // Delete the user
            $user->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Account and all related data deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error deleting account: ' . $e->getMessage()], 500);
        }
    }
}


