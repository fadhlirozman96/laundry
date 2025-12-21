<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->has('draw')) {
            return $this->getCategoryData($request);
        }

        return view('expense-category');
    }

    protected function getCategoryData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');

        $query = ExpenseCategory::with('store');

        // Filter by selected store
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } else {
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                $query->whereIn('store_id', $accessibleStoreIds);
            }
        }

        // DataTables processing
        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where('name', 'like', '%' . $search . '%');
            $totalFiltered = $query->count();
        }

        // Order
        $orderColumn = $request->order[0]['column'] ?? 0;
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        $columns = ['id', 'name', 'description', 'is_active', 'id'];

        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $categories = $query->skip($start)->take($length)->get();

        $data = [];
        $rowNum = $start + 1;
        foreach ($categories as $category) {
            $statusBadge = $category->is_active ? 'badge-linesuccess' : 'badge-linedanger';
            $statusText = $category->is_active ? 'Active' : 'Inactive';

            $data[] = [
                'row_number' => $rowNum++,
                'name' => $category->name,
                'description' => $category->description ?: '-',
                'status' => '<span class="badge ' . $statusBadge . '">' . $statusText . '</span>',
                'action' => $this->getActionButtons($category)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($category)
    {
        return '<div class="edit-delete-action">
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="editCategory(' . $category->id . ')" title="Edit">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteCategory(' . $category->id . ')" title="Delete">
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category = ExpenseCategory::create([
                'store_id' => $selectedStoreId,
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return response()->json(['success' => true, 'message' => 'Category created successfully', 'category' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $category = ExpenseCategory::findOrFail($id);
        return response()->json(['success' => true, 'category' => $category]);
    }

    public function update(Request $request, $id)
    {
        $category = ExpenseCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category->update([
                'name' => $request->name,
                'description' => $request->description,
                'is_active' => $request->has('is_active') ? true : false,
            ]);

            return response()->json(['success' => true, 'message' => 'Category updated successfully', 'category' => $category]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $category = ExpenseCategory::findOrFail($id);
            $category->delete();
            return response()->json(['success' => true, 'message' => 'Category deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getCategories()
    {
        $selectedStoreId = session('selected_store_id');
        $categories = ExpenseCategory::where('store_id', $selectedStoreId)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        return response()->json(['success' => true, 'categories' => $categories]);
    }
}
