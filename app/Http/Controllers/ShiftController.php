<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Shift::with('employees');
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        $shifts = $query->latest()->get();
        
        return view('shift', compact('shifts'));
    }

    public function getShiftData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Shift::with('employees');
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('time_range', function($shift) {
                return $shift->time_range;
            })
            ->addColumn('week_off_display', function($shift) {
                return $shift->week_off_display;
            })
            ->addColumn('status', function($shift) {
                return $shift->is_active 
                    ? '<span class="badge badge-linesuccess">Active</span>' 
                    : '<span class="badge badge-linedanger">Inactive</span>';
            })
            ->addColumn('action', function($shift) {
                return '<div class="edit-delete-action">
                    <a class="me-2 p-2 edit-shift" href="javascript:void(0);" data-id="'.$shift->id.'">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2 delete-shift" href="javascript:void(0);" data-id="'.$shift->id.'">
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
            'start_time' => 'required',
            'end_time' => 'required',
            'break_duration' => 'nullable|integer|min:0',
            'week_off' => 'nullable|array',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $shift = Shift::create([
            'store_id' => $selectedStoreId,
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_duration' => $request->break_duration ?? 60,
            'week_off' => $request->week_off,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Shift created successfully.', 'shift' => $shift]);
    }

    public function show($id)
    {
        $shift = Shift::with('employees')->findOrFail($id);
        return response()->json(['success' => true, 'shift' => $shift]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_time' => 'required',
            'end_time' => 'required',
            'break_duration' => 'nullable|integer|min:0',
            'week_off' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        $shift = Shift::findOrFail($id);
        $shift->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_duration' => $request->break_duration ?? 60,
            'week_off' => $request->week_off,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['success' => true, 'message' => 'Shift updated successfully.']);
    }

    public function destroy($id)
    {
        $shift = Shift::findOrFail($id);
        $shift->delete();

        return response()->json(['success' => true, 'message' => 'Shift deleted successfully.']);
    }
}

