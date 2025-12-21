<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $selectedStoreId = session('selected_store_id');
        $categories = [];
        
        if ($selectedStoreId) {
            $categories = ExpenseCategory::where('store_id', $selectedStoreId)
                ->where('is_active', true)
                ->orderBy('name')
                ->get();
        }
        
        if ($request->ajax() || $request->has('draw')) {
            return $this->getExpenseData($request);
        }

        return view('expense-list', compact('categories'));
    }

    protected function getExpenseData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');

        $query = Expense::with(['category', 'user', 'store']);

        // Filter by selected store
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } else {
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                $query->whereIn('store_id', $accessibleStoreIds);
            }
        }

        // Apply filters
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('expense_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('expense_date', '<=', $request->date_to);
        }

        // DataTables processing
        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('category', function($q) use ($search) {
                      $q->where('name', 'like', '%' . $search . '%');
                  });
            });
            $totalFiltered = $query->count();
        }

        // Order
        $orderColumn = $request->order[0]['column'] ?? 0;
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        $columns = ['id', 'category_id', 'reference', 'amount', 'expense_date', 'payment_method', 'user_id', 'is_active', 'id'];

        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        } else {
            $query->orderBy('expense_date', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $expenses = $query->skip($start)->take($length)->get();

        $data = [];
        $rowNum = $start + 1;
        foreach ($expenses as $expense) {
            $statusBadge = $expense->is_active ? 'badge-linesuccess' : 'badge-linedanger';
            $statusText = $expense->is_active ? 'Active' : 'Inactive';

            $data[] = [
                'row_number' => $rowNum++,
                'category' => $expense->category ? $expense->category->name : '-',
                'reference' => $expense->reference ?: '-',
                'amount' => 'MYR ' . number_format($expense->amount, 2),
                'expense_date' => $expense->expense_date->format('d M Y'),
                'payment_method' => ucfirst($expense->payment_method),
                'created_by' => $expense->user ? $expense->user->name : '-',
                'status' => '<span class="badge ' . $statusBadge . '">' . $statusText . '</span>',
                'action' => $this->getActionButtons($expense)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($expense)
    {
        return '<div class="edit-delete-action">
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="viewExpense(' . $expense->id . ')" title="View">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="editExpense(' . $expense->id . ')" title="Edit">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteExpense(' . $expense->id . ')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    public function store(Request $request)
    {
        $selectedStoreId = session('selected_store_id');
        
        if (!$selectedStoreId) {
            return response()->json(['success' => false, 'message' => 'Please select a store first'], 400);
        }

        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $expense = Expense::create([
                'store_id' => $selectedStoreId,
                'category_id' => $request->category_id,
                'user_id' => Auth::id(),
                'reference' => Expense::generateReference(),
                'amount' => $request->amount,
                'expense_date' => $request->expense_date,
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'is_active' => true,
            ]);

            return response()->json(['success' => true, 'message' => 'Expense created successfully', 'expense' => $expense]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $expense = Expense::with(['category', 'user', 'store'])->findOrFail($id);
        return response()->json(['success' => true, 'expense' => $expense]);
    }

    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'payment_method' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            $expense->update([
                'category_id' => $request->category_id,
                'amount' => $request->amount,
                'expense_date' => $request->expense_date,
                'payment_method' => $request->payment_method,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? true : $expense->is_active,
            ]);

            return response()->json(['success' => true, 'message' => 'Expense updated successfully', 'expense' => $expense]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $expense = Expense::findOrFail($id);
            $expense->delete();
            return response()->json(['success' => true, 'message' => 'Expense deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function report(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');

        $query = Expense::with(['category', 'store']);

        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } else {
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                $query->whereIn('store_id', $accessibleStoreIds);
            }
        }

        // Get summary data
        $totalExpenses = $query->sum('amount');
        $thisMonthExpenses = (clone $query)->whereMonth('expense_date', now()->month)->sum('amount');
        $thisYearExpenses = (clone $query)->whereYear('expense_date', now()->year)->sum('amount');

        // Get expenses by category
        $byCategory = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->where('store_id', $selectedStoreId)
            ->groupBy('category_id')
            ->with('category')
            ->get();

        return view('expense-report', compact('totalExpenses', 'thisMonthExpenses', 'thisYearExpenses', 'byCategory'));
    }
}
