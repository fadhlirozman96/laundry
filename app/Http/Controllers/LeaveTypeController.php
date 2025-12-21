<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = LeaveType::withCount('leaves');
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        $leaveTypes = $query->latest()->get();
        
        return view('leave-types', compact('leaveTypes'));
    }

    public function getLeaveTypeData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = LeaveType::query();
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('status', function($type) {
                return $type->is_active 
                    ? '<span class="badge badge-linesuccess">Active</span>' 
                    : '<span class="badge badge-linedanger">Inactive</span>';
            })
            ->addColumn('action', function($type) {
                return '<div class="edit-delete-action">
                    <a class="me-2 p-2 edit-leave-type" href="javascript:void(0);" data-id="'.$type->id.'">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2 delete-leave-type" href="javascript:void(0);" data-id="'.$type->id.'">
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
            'days_allowed' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $leaveType = LeaveType::create([
            'store_id' => $selectedStoreId,
            'name' => $request->name,
            'days_allowed' => $request->days_allowed,
            'description' => $request->description,
            'is_paid' => $request->is_paid ?? true,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Leave type created successfully.', 'leaveType' => $leaveType]);
    }

    public function show($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        return response()->json(['success' => true, 'leaveType' => $leaveType]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'days_allowed' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_paid' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $leaveType = LeaveType::findOrFail($id);
        $leaveType->update([
            'name' => $request->name,
            'days_allowed' => $request->days_allowed,
            'description' => $request->description,
            'is_paid' => $request->is_paid ?? true,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['success' => true, 'message' => 'Leave type updated successfully.']);
    }

    public function destroy($id)
    {
        $leaveType = LeaveType::findOrFail($id);
        $leaveType->delete();

        return response()->json(['success' => true, 'message' => 'Leave type deleted successfully.']);
    }
}


