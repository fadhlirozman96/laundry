<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Request;

class HolidayController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Holiday::query();
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        $holidays = $query->orderBy('date', 'desc')->get();
        
        return view('holidays', compact('holidays'));
    }

    public function getHolidayData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Holiday::query();
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('date_formatted', function($holiday) {
                return $holiday->date->format('d M Y');
            })
            ->addColumn('duration', function($holiday) {
                return $holiday->duration;
            })
            ->addColumn('status', function($holiday) {
                return $holiday->is_active 
                    ? '<span class="badge badge-linesuccess">Active</span>' 
                    : '<span class="badge badge-linedanger">Inactive</span>';
            })
            ->addColumn('action', function($holiday) {
                return '<div class="edit-delete-action">
                    <a class="me-2 p-2 edit-holiday" href="javascript:void(0);" data-id="'.$holiday->id.'">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2 delete-holiday" href="javascript:void(0);" data-id="'.$holiday->id.'">
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
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $holiday = Holiday::create([
            'store_id' => $selectedStoreId,
            'name' => $request->name,
            'date' => $request->date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Holiday created successfully.', 'holiday' => $holiday]);
    }

    public function show($id)
    {
        $holiday = Holiday::findOrFail($id);
        return response()->json(['success' => true, 'holiday' => $holiday]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $holiday = Holiday::findOrFail($id);
        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'end_date' => $request->end_date,
            'description' => $request->description,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json(['success' => true, 'message' => 'Holiday updated successfully.']);
    }

    public function destroy($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();

        return response()->json(['success' => true, 'message' => 'Holiday deleted successfully.']);
    }
}




