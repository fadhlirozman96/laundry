<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaveController extends Controller
{
    public function adminIndex()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $leaveTypes = $this->getLeaveTypes();
        $employees = $this->getEmployees();
        
        return view('leaves-admin', compact('leaveTypes', 'employees'));
    }

    public function employeeIndex()
    {
        $user = auth()->user();
        $leaves = Leave::where('user_id', $user->id)
            ->with('leaveType')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        $leaveTypes = LeaveType::where('is_active', true)->get();
        
        return view('leaves-employee', compact('leaves', 'leaveTypes'));
    }

    public function getLeaveData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Leave::with(['user', 'leaveType', 'approver']);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('employee_name', function($leave) {
                return $leave->user ? $leave->user->name : '-';
            })
            ->addColumn('leave_type_name', function($leave) {
                return $leave->leaveType ? $leave->leaveType->name : '-';
            })
            ->addColumn('date_range', function($leave) {
                return $leave->start_date->format('d M Y') . ' - ' . $leave->end_date->format('d M Y');
            })
            ->addColumn('duration', function($leave) {
                return $leave->duration_display;
            })
            ->addColumn('status_badge', function($leave) {
                $colors = [
                    'pending' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    'cancelled' => 'secondary',
                ];
                $color = $colors[$leave->status] ?? 'secondary';
                return '<span class="badge badge-line'.$color.'">'.ucfirst($leave->status).'</span>';
            })
            ->addColumn('action', function($leave) {
                $actions = '<div class="edit-delete-action">';
                if ($leave->status === 'pending') {
                    $actions .= '<a class="me-2 p-2 approve-leave" href="javascript:void(0);" data-id="'.$leave->id.'" title="Approve">
                        <i data-feather="check" class="feather-check text-success"></i>
                    </a>
                    <a class="me-2 p-2 reject-leave" href="javascript:void(0);" data-id="'.$leave->id.'" title="Reject">
                        <i data-feather="x" class="feather-x text-danger"></i>
                    </a>';
                }
                $actions .= '<a class="confirm-text p-2 delete-leave" href="javascript:void(0);" data-id="'.$leave->id.'">
                    <i data-feather="trash-2" class="feather-trash-2"></i>
                </a></div>';
                return $actions;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $userId = $request->user_id ?? $user->id;
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1;

        $leave = Leave::create([
            'store_id' => $selectedStoreId,
            'user_id' => $userId,
            'leave_type_id' => $request->leave_type_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days' => $days,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'message' => 'Leave request submitted successfully.', 'leave' => $leave]);
    }

    public function show($id)
    {
        $leave = Leave::with(['user', 'leaveType', 'approver'])->findOrFail($id);
        return response()->json(['success' => true, 'leave' => $leave]);
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Leave approved successfully.']);
    }

    public function reject(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => Carbon::now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return response()->json(['success' => true, 'message' => 'Leave rejected.']);
    }

    public function destroy($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->delete();

        return response()->json(['success' => true, 'message' => 'Leave request deleted successfully.']);
    }

    private function getLeaveTypes()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = LeaveType::where('is_active', true);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return $query->get();
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


