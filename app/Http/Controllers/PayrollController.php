<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $employees = $this->getEmployees();
        
        return view('payroll-list', compact('employees'));
    }

    public function getPayrollData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');
        
        $query = Payroll::with('user');
        
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } elseif (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        return datatables()->of($query)
            ->addColumn('employee_name', function($payroll) {
                return $payroll->user ? $payroll->user->name : '-';
            })
            ->addColumn('employee_id', function($payroll) {
                return $payroll->user && $payroll->user->employee_id ? $payroll->user->employee_id : '-';
            })
            ->addColumn('email', function($payroll) {
                return $payroll->user ? $payroll->user->email : '-';
            })
            ->addColumn('salary_formatted', function($payroll) {
                return 'MYR ' . number_format($payroll->net_salary, 2);
            })
            ->addColumn('month_year', function($payroll) {
                return $payroll->month_year;
            })
            ->addColumn('status_badge', function($payroll) {
                $colors = [
                    'draft' => 'secondary',
                    'pending' => 'warning',
                    'paid' => 'success',
                    'cancelled' => 'danger',
                ];
                $color = $colors[$payroll->status] ?? 'secondary';
                return '<span class="badge badge-line'.$color.'">'.ucfirst($payroll->status).'</span>';
            })
            ->addColumn('action', function($payroll) {
                return '<div class="edit-delete-action data-view">
                    <a class="me-2 view-payroll" href="javascript:void(0);" data-id="'.$payroll->id.'">
                        <i data-feather="eye" class="action-eye"></i>
                    </a>
                    <a class="me-2 download-payslip" href="'.route('payroll.payslip', $payroll->id).'">
                        <i data-feather="download" class="action-download"></i>
                    </a>
                    <a class="me-2 edit-payroll" href="javascript:void(0);" data-id="'.$payroll->id.'">
                        <i data-feather="edit" class="action-edit"></i>
                    </a>
                    <a class="confirm-text delete-payroll" href="javascript:void(0);" data-id="'.$payroll->id.'">
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
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2020|max:2100',
            'basic_salary' => 'required|numeric|min:0',
        ]);

        $selectedStoreId = session('selected_store_id');
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first.'], 400);
        }

        // Check for duplicate
        $exists = Payroll::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->exists();
        
        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Payroll already exists for this employee and month.'], 400);
        }

        $payroll = new Payroll([
            'store_id' => $selectedStoreId,
            'user_id' => $request->user_id,
            'month' => $request->month,
            'year' => $request->year,
            'basic_salary' => $request->basic_salary,
            'hra_allowance' => $request->hra_allowance ?? 0,
            'conveyance' => $request->conveyance ?? 0,
            'medical_allowance' => $request->medical_allowance ?? 0,
            'bonus' => $request->bonus ?? 0,
            'other_allowance' => $request->other_allowance ?? 0,
            'pf_deduction' => $request->pf_deduction ?? 0,
            'professional_tax' => $request->professional_tax ?? 0,
            'tds' => $request->tds ?? 0,
            'loans_deduction' => $request->loans_deduction ?? 0,
            'other_deduction' => $request->other_deduction ?? 0,
            'status' => $request->status ?? 'draft',
            'notes' => $request->notes,
        ]);

        $payroll->calculateTotals();
        $payroll->save();

        return response()->json(['success' => true, 'message' => 'Payroll created successfully.', 'payroll' => $payroll]);
    }

    public function show($id)
    {
        $payroll = Payroll::with('user')->findOrFail($id);
        return response()->json(['success' => true, 'payroll' => $payroll]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'basic_salary' => 'required|numeric|min:0',
            'status' => 'required|in:draft,pending,paid,cancelled',
        ]);

        $payroll = Payroll::findOrFail($id);
        
        $payroll->fill([
            'basic_salary' => $request->basic_salary,
            'hra_allowance' => $request->hra_allowance ?? 0,
            'conveyance' => $request->conveyance ?? 0,
            'medical_allowance' => $request->medical_allowance ?? 0,
            'bonus' => $request->bonus ?? 0,
            'other_allowance' => $request->other_allowance ?? 0,
            'pf_deduction' => $request->pf_deduction ?? 0,
            'professional_tax' => $request->professional_tax ?? 0,
            'tds' => $request->tds ?? 0,
            'loans_deduction' => $request->loans_deduction ?? 0,
            'other_deduction' => $request->other_deduction ?? 0,
            'status' => $request->status,
            'payment_date' => $request->status === 'paid' ? now() : $payroll->payment_date,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes,
        ]);

        $payroll->calculateTotals();
        $payroll->save();

        return response()->json(['success' => true, 'message' => 'Payroll updated successfully.']);
    }

    public function destroy($id)
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        return response()->json(['success' => true, 'message' => 'Payroll deleted successfully.']);
    }

    public function payslip($id)
    {
        $payroll = Payroll::with(['user', 'store'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.payslip', compact('payroll'));
        
        return $pdf->download('payslip-'.$payroll->user->name.'-'.$payroll->month_year.'.pdf');
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

