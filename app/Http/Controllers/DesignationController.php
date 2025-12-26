<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Designation::with(['department', 'employees']);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        $designations = $query->latest()->get();
        $departments = $this->getDepartments();
        
        return view('designation', compact('designations', 'departments'));
    }

    public function getDesignationData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Designation::with(['department', 'employees']);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('department_name', function($des) {
                return $des->department ? $des->department->name : '-';
            })
            ->addColumn('employee_count', function($des) {
                return $des->employees->count();
            })
            ->addColumn('status', function($des) {
                return $des->is_active 
                    ? '<span class="badge badge-linesuccess">Active</span>' 
                    : '<span class="badge badge-linedanger">Inactive</span>';
            })
            ->addColumn('action', function($des) {
                return '<div class="edit-delete-action">
                    <a class="me-2 p-2 edit-designation" href="javascript:void(0);" data-id="'.$des->id.'">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2 delete-designation" href="javascript:void(0);" data-id="'.$des->id.'">
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
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $designation = Designation::create([
            'store_id' => $selectedStoreId,
            'name' => $request->name,
            'department_id' => $request->department_id,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Designation created successfully.', 'designation' => $designation]);
    }

    public function show($id)
    {
        $designation = Designation::with(['department', 'employees'])->findOrFail($id);
        return response()->json(['success' => true, 'designation' => $designation]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $designation = Designation::findOrFail($id);
        $designation->update([
            'name' => $request->name,
            'department_id' => $request->department_id,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['success' => true, 'message' => 'Designation updated successfully.']);
    }

    public function destroy($id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();

        return response()->json(['success' => true, 'message' => 'Designation deleted successfully.']);
    }

    private function getDepartments()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Department::where('is_active', true);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return $query->get();
    }
}




