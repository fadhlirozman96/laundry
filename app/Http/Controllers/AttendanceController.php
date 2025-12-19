<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function adminIndex()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $employees = $this->getEmployees();
        $shifts = $this->getShifts();
        
        return view('attendance-admin', compact('employees', 'shifts'));
    }

    public function employeeIndex()
    {
        $user = auth()->user();
        $attendances = Attendance::where('user_id', $user->id)
            ->with('shift')
            ->orderBy('date', 'desc')
            ->paginate(15);
        
        return view('attendance-employee', compact('attendances'));
    }

    public function getAttendanceData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Attendance::with(['user', 'shift']);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('employee_name', function($att) {
                return $att->user ? $att->user->name : '-';
            })
            ->addColumn('employee_id', function($att) {
                return $att->user && $att->user->employee_id ? $att->user->employee_id : '-';
            })
            ->addColumn('shift_name', function($att) {
                return $att->shift ? $att->shift->name : '-';
            })
            ->addColumn('clock_in_time', function($att) {
                return $att->clock_in ? $att->clock_in->format('h:i A') : '-';
            })
            ->addColumn('clock_out_time', function($att) {
                return $att->clock_out ? $att->clock_out->format('h:i A') : '-';
            })
            ->addColumn('production', function($att) {
                return $att->production_hours;
            })
            ->addColumn('overtime', function($att) {
                return $att->overtime_display;
            })
            ->addColumn('total', function($att) {
                return $att->total_hours_display;
            })
            ->addColumn('status_badge', function($att) {
                $colors = [
                    'present' => 'success',
                    'approved' => 'success',
                    'absent' => 'danger',
                    'late' => 'warning',
                    'half_day' => 'info',
                    'on_leave' => 'secondary',
                    'pending' => 'warning',
                    'rejected' => 'danger',
                ];
                $color = $colors[$att->status] ?? 'secondary';
                return '<span class="badge badge-line'.$color.'">'.ucfirst(str_replace('_', ' ', $att->status)).'</span>';
            })
            ->addColumn('action', function($att) {
                return '<div class="edit-delete-action">
                    <a class="me-2 p-2 view-attendance" href="javascript:void(0);" data-id="'.$att->id.'">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    <a class="me-2 p-2 edit-attendance" href="javascript:void(0);" data-id="'.$att->id.'">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2 delete-attendance" href="javascript:void(0);" data-id="'.$att->id.'">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'shift_id' => 'nullable|exists:shifts,id',
            'clock_in' => 'nullable|date_format:H:i',
            'clock_out' => 'nullable|date_format:H:i',
            'status' => 'required|in:present,absent,late,half_day,on_leave,pending,approved,rejected',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $date = Carbon::parse($request->date);
        $clockIn = $request->clock_in ? Carbon::parse($request->date . ' ' . $request->clock_in) : null;
        $clockOut = $request->clock_out ? Carbon::parse($request->date . ' ' . $request->clock_out) : null;

        $totalHours = 0;
        if ($clockIn && $clockOut) {
            $totalHours = $clockIn->diffInMinutes($clockOut) / 60;
        }

        $attendance = Attendance::updateOrCreate(
            ['user_id' => $request->user_id, 'date' => $date],
            [
                'store_id' => $selectedStoreId,
                'shift_id' => $request->shift_id,
                'clock_in' => $clockIn,
                'clock_out' => $clockOut,
                'total_hours' => $totalHours,
                'status' => $request->status,
                'notes' => $request->notes,
            ]
        );

        return response()->json(['success' => true, 'message' => 'Attendance recorded successfully.']);
    }

    public function show($id)
    {
        $attendance = Attendance::with(['user', 'shift'])->findOrFail($id);
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,half_day,on_leave,pending,approved,rejected',
        ]);

        $attendance = Attendance::findOrFail($id);
        $attendance->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return response()->json(['success' => true, 'message' => 'Attendance updated successfully.']);
    }

    public function clockIn(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        $today = Carbon::today();
        
        $attendance = Attendance::updateOrCreate(
            ['user_id' => $user->id, 'date' => $today],
            [
                'store_id' => $selectedStoreId,
                'shift_id' => $user->shift_id,
                'clock_in' => Carbon::now(),
                'status' => 'present',
            ]
        );

        return response()->json(['success' => true, 'message' => 'Clocked in successfully.', 'attendance' => $attendance]);
    }

    public function clockOut(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();
        
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();
        
        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No clock-in record found for today.'], 400);
        }

        $clockOut = Carbon::now();
        $totalHours = $attendance->clock_in ? $attendance->clock_in->diffInMinutes($clockOut) / 60 : 0;

        $attendance->update([
            'clock_out' => $clockOut,
            'total_hours' => $totalHours,
        ]);

        return response()->json(['success' => true, 'message' => 'Clocked out successfully.', 'attendance' => $attendance]);
    }

    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json(['success' => true, 'message' => 'Attendance record deleted successfully.']);
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

    private function getShifts()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Shift::where('is_active', true);
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return $query->get();
    }
}

