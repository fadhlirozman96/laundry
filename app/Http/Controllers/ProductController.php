<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Handle DataTables AJAX request
        if ($request->ajax() || $request->has('draw')) {
            $query = Product::with(['category', 'unit', 'creator', 'store']);

            // Filter by user's accessible stores
            $user = auth()->user();
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                // Only show products that belong to user's accessible stores
                $query->whereIn('store_id', $accessibleStoreIds);
            }

            // If a specific store is selected in session, filter by that store
            if (session('selected_store_id')) {
                $query->where('store_id', session('selected_store_id'));
            }

            // DataTables search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%');
                });
            }

            // Get total records (filtered by store access)
            $totalQuery = Product::query();
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                $totalQuery->whereIn('store_id', $accessibleStoreIds);
            }
            if (session('selected_store_id')) {
                $totalQuery->where('store_id', session('selected_store_id'));
            }
            $totalRecords = $totalQuery->count();
            $filteredRecords = $query->count();

            // DataTables ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                
                // Column mapping: 0=checkbox, 1=name, 2=sku, 3=category, 4=price, 5=unit, 6=quantity, 7=creator, 8=action
                $columns = ['id', 'name', 'sku', 'category_id', 'price', 'unit_id', 'quantity', 'created_by', 'id'];
                if ($orderColumnIndex > 0 && $orderColumnIndex < 9) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDirection);
                }
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // DataTables pagination
            $start = $request->get('start', 0);
            $length = $request->get('length', 10);
            $products = $query->skip($start)->take($length)->get();

            $data = [];
            $rowNumber = $start + 1; // Start from the current page offset
            foreach ($products as $product) {
                $imageUrl = $product->image 
                    ? asset($product->image) 
                    : asset('/build/img/products/stock-img-01.png');

                $data[] = [
                    'checkbox' => $rowNumber++,
                    'product' => '<div class="productimgname">
                                    <a href="' . route('product-details', $product->id) . '" class="product-img stock-img">
                                        <img src="' . $imageUrl . '" alt="' . $product->name . '">
                                    </a>
                                    <a href="' . route('product-details', $product->id) . '">' . $product->name . '</a>
                                  </div>',
                    'sku' => $product->sku,
                    'category' => $product->category->name ?? 'N/A',
                    'price' => 'MYR ' . number_format($product->price, 2),
                    'unit' => $product->unit->short_name ?? 'Pc',
                    'quantity' => $product->quantity,
                    'store' => '<span class="badge bg-outline-primary">' . ($product->store->name ?? 'No Store') . '</span>',
                    'created_by' => '<div class="userimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            <img src="' . asset('/build/img/users/user-30.jpg') . '" alt="user">
                                        </a>
                                        <a href="javascript:void(0);">' . ($product->creator->name ?? 'Admin') . '</a>
                                     </div>',
                    'action' => '<div class="edit-delete-action">
                                    <a class="action-view" href="' . route('product-details', $product->id) . '" title="View Product">
                                        <i data-feather="eye" class="feather-eye"></i>
                                    </a>
                                    <a class="action-edit" href="' . route('edit-product', $product->id) . '" title="Edit Product">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class="action-delete" href="javascript:void(0);" onclick="deleteProduct(' . $product->id . ')" title="Delete Product">
                                        <i data-feather="trash-2" class="feather-trash-2"></i>
                                    </a>
                                 </div>'
                ];
            }

            return response()->json([
                'draw' => intval($request->get('draw')),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $filteredRecords,
                'data' => $data
            ]);
        }

        // Regular page load
        $categories = Category::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        $stores = auth()->user()->getAccessibleStores();
        return view('product-list', compact('categories', 'units', 'stores'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        $stores = auth()->user()->getAccessibleStores();
        $selectedStoreId = session('selected_store_id');

        return view('add-product', compact('categories', 'units', 'stores', 'selectedStoreId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'store_id' => 'nullable|exists:stores,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['created_by'] = Auth::id();
        $data['track_quantity'] = $request->has('track_quantity') ? true : false;

        // If no store_id provided, use selected store from session
        if (empty($data['store_id']) && session('selected_store_id')) {
            $data['store_id'] = session('selected_store_id');
        }

        // Validate user has access to the store
        if (!empty($data['store_id'])) {
            $accessibleStoreIds = auth()->user()->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($data['store_id'], $accessibleStoreIds)) {
                return redirect()->back()->with('error', 'You do not have access to this store.')->withInput();
            }
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        Product::create($data);

        return redirect()->route('product-list')->with('success', 'Product created successfully!');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'unit', 'creator', 'store'])->findOrFail($id);
        
        // Check if user has access to this product's store
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $product->store_id) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($product->store_id, $accessibleStoreIds)) {
                abort(403, 'You do not have access to this product.');
            }
        }
        
        return view('product-details', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        
        // Check if user has access to this product's store
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $product->store_id) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($product->store_id, $accessibleStoreIds)) {
                abort(403, 'You do not have access to this product.');
            }
        }
        
        $categories = Category::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        $stores = auth()->user()->getAccessibleStores();

        return view('edit-product', compact('product', 'categories', 'units', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        // Check if user has access to this product's store
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $product->store_id) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($product->store_id, $accessibleStoreIds)) {
                return redirect()->route('product-list')->with('error', 'You do not have access to this product.');
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'store_id' => 'nullable|exists:stores,id',
            'category_id' => 'nullable|exists:categories,id',
            'unit_id' => 'nullable|exists:units,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Validate user has access to the new store
        if (!empty($request->store_id)) {
            $accessibleStoreIds = auth()->user()->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($request->store_id, $accessibleStoreIds)) {
                return redirect()->back()->with('error', 'You do not have access to this store.')->withInput();
            }
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['track_quantity'] = $request->has('track_quantity') ? true : false;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/products'), $imageName);
            $data['image'] = 'uploads/products/' . $imageName;
        }

        $updated = $product->update($data);

        if ($updated) {
            return redirect()->route('product-list')->with('success', 'Product updated successfully!');
        } else {
            return redirect()->back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Check if user has access to this product's store
        $user = auth()->user();
        if (!$user->isSuperAdmin() && $product->store_id) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            if (!in_array($product->store_id, $accessibleStoreIds)) {
                return response()->json(['error' => 'You do not have access to delete this product.'], 403);
            }
        }

        // Delete image if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update(['is_active' => $request->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Product status updated successfully!'
        ]);
    }
}
