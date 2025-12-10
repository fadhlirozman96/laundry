<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
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
            $query = Product::with(['category', 'brand', 'unit', 'creator']);

            // DataTables search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('sku', 'like', '%' . $search . '%')
                      ->orWhere('barcode', 'like', '%' . $search . '%');
                });
            }

            // Get total records
            $totalRecords = Product::count();
            $filteredRecords = $query->count();

            // DataTables ordering
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDirection = $request->order[0]['dir'];
                
                // Column mapping: 0=checkbox, 1=name, 2=sku, 3=category, 4=brand, 5=price, 6=unit, 7=quantity, 8=creator, 9=action
                $columns = ['id', 'name', 'sku', 'category_id', 'brand_id', 'price', 'unit_id', 'quantity', 'created_by', 'id'];
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
            foreach ($products as $product) {
                $imageUrl = $product->image 
                    ? asset($product->image) 
                    : asset('/build/img/products/stock-img-01.png');

                $data[] = [
                    'checkbox' => '<label class="checkboxs"><input type="checkbox"><span class="checkmarks"></span></label>',
                    'product' => '<div class="productimgname">
                                    <a href="' . route('product-details', $product->id) . '" class="product-img stock-img">
                                        <img src="' . $imageUrl . '" alt="' . $product->name . '">
                                    </a>
                                    <a href="' . route('product-details', $product->id) . '">' . $product->name . '</a>
                                  </div>',
                    'sku' => $product->sku,
                    'category' => $product->category->name ?? 'N/A',
                    'brand' => $product->brand->name ?? 'N/A',
                    'price' => '$' . number_format($product->price, 2),
                    'unit' => $product->unit->short_name ?? 'Pc',
                    'quantity' => $product->quantity,
                    'created_by' => '<div class="userimgname">
                                        <a href="javascript:void(0);" class="product-img">
                                            <img src="' . asset('/build/img/users/user-30.jpg') . '" alt="user">
                                        </a>
                                        <a href="javascript:void(0);">' . ($product->creator->name ?? 'Admin') . '</a>
                                     </div>',
                    'action' => '<div class="edit-delete-action">
                                    <a class="me-2 p-2" href="' . route('product-details', $product->id) . '">
                                        <i data-feather="eye" class="feather-eye"></i>
                                    </a>
                                    <a class="me-2 p-2" href="' . route('edit-product', $product->id) . '">
                                        <i data-feather="edit" class="feather-edit"></i>
                                    </a>
                                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteProduct(' . $product->id . ')">
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
        return view('product-list');
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        
        return view('add-product', compact('categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'barcode' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['created_by'] = Auth::id();

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
        $product = Product::with(['category', 'brand', 'unit', 'creator'])->findOrFail($id);
        return view('product-details', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::where('is_active', true)->get();
        $brands = Brand::where('is_active', true)->get();
        $units = Unit::where('is_active', true)->get();
        
        return view('edit-product', compact('product', 'categories', 'brands', 'units'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $id,
            'category_id' => 'nullable|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'unit_id' => 'nullable|exists:units,id',
            'price' => 'required|numeric|min:0',
            'cost' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'alert_quantity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'barcode' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);

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

        $product->update($data);

        return redirect()->route('product-list')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

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
