<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTableData($request);
        }
        
        return view('category-list');
    }

    protected function getDataTableData(Request $request)
    {
        $query = Category::query();

        // Search
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $totalData = Category::count();
        $totalFiltered = $query->count();

        // Ordering
        if ($request->has('order')) {
            $columns = ['id', 'name', 'slug', 'created_at', 'is_active', 'id'];
            $orderColumn = $columns[$request->order[0]['column']] ?? 'created_at';
            $orderDir = $request->order[0]['dir'] ?? 'desc';
            $query->orderBy($orderColumn, $orderDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $categories = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($categories as $index => $category) {
            $data[] = [
                'checkbox' => '<label class="checkboxs"><input type="checkbox" value="'.$category->id.'"><span class="checkmarks"></span></label>',
                'name' => $category->name,
                'slug' => $category->slug,
                'created_at' => $category->created_at->format('d M Y'),
                'status' => $category->is_active 
                    ? '<span class="badge badge-linesuccess">Active</span>' 
                    : '<span class="badge badge-linedanger">Inactive</span>',
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
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="editCategory('.$category->id.')" title="Edit">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteCategory('.$category->id.')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'category' => $category
        ]);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? $request->is_active : $category->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category with products. Please remove or reassign products first.'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

    public function toggleStatus($id)
    {
        $category = Category::findOrFail($id);
        $category->is_active = !$category->is_active;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Category status updated successfully',
            'is_active' => $category->is_active
        ]);
    }
}

