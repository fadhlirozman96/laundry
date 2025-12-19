<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Department::with(['head', 'employees', 'store']);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        $departments = $query->latest()->get();
        
        // Get employees for dropdown
        $employees = $this->getEmployees();
        
        return view('department-grid', compact('departments', 'employees'));
    }

    public function getDepartmentData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Department::with(['head', 'employees', 'store']);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('head_name', function($dept) {
                return $dept->head ? $dept->head->name : '-';
            })
            ->addColumn('employee_count', function($dept) {
                return $dept->employees->count();
            })
            ->addColumn('status', function($dept) {
                return $dept->is_active 
                    ? '<span class="badge badge-linesuccess">Active</span>' 
                    : '<span class="badge badge-linedanger">Inactive</span>';
            })
            ->addColumn('action', function($dept) {
                return '<div class="edit-delete-action">
                    <a class="me-2 p-2 edit-department" href="javascript:void(0);" data-id="'.$dept->id.'">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2 delete-department" href="javascript:void(0);" data-id="'.$dept->id.'">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $department = Department::create([
            'store_id' => $selectedStoreId,
            'name' => $request->name,
            'description' => $request->description,
            'head_id' => $request->head_id,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Department created successfully.', 'department' => $department]);
    }

    public function show($id)
    {
        $department = Department::with(['head', 'employees', 'store'])->findOrFail($id);
        return response()->json(['success' => true, 'department' => $department]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $department = Department::findOrFail($id);
        $department->update([
            'name' => $request->name,
            'description' => $request->description,
            'head_id' => $request->head_id,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['success' => true, 'message' => 'Department updated successfully.']);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();

        return response()->json(['success' => true, 'message' => 'Department deleted successfully.']);
    }

    private function getEmployees()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = User::query();
        
        if ($selectedStoreId) {
            $query->whereHas('stores', function($q) use ($selectedStoreId) {
                $q->where('stores.id', $selectedStoreId);
            });
        } elseif (!$user->isSuperAdmin()) {
            $query->where('account_owner_id', $user->id);
        }
        
        return $query->get();
    }
}

