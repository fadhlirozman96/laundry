<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax() || $request->has('draw')) {
            return $this->getCouponData($request);
        }

        return view('coupons');
    }

    protected function getCouponData(Request $request)
    {
        $user = auth()->user();
        $selectedStoreId = session('selected_store_id');

        $query = Coupon::query();

        // Always filter by selected store - coupons are store-specific
        if ($selectedStoreId) {
            $query->where('store_id', $selectedStoreId);
        } else {
            // If no store selected, filter by user's accessible stores
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
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
            $totalFiltered = $query->count();
        }

        // Order
        $orderColumn = $request->order[0]['column'] ?? 0;
        $orderDir = $request->order[0]['dir'] ?? 'desc';
        $columns = ['id', 'name', 'code', 'type', 'discount', 'limit', 'used', 'valid_until', 'is_active', 'id'];

        if (isset($columns[$orderColumn])) {
            $query->orderBy($columns[$orderColumn], $orderDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $coupons = $query->skip($start)->take($length)->get();

        $data = [];
        $rowNum = $start + 1;
        foreach ($coupons as $coupon) {
            $statusBadge = $coupon->is_active ? 'badge-linesuccess' : 'badge-linedanger';
            $statusText = $coupon->is_active ? 'Active' : 'Inactive';
            
            $discountDisplay = $coupon->type === 'percentage' 
                ? $coupon->discount . '%' 
                : 'MYR ' . number_format($coupon->discount, 2);

            $data[] = [
                'row_number' => $rowNum++,
                'name' => $coupon->name,
                'code' => '<span class="badge bg-primary">' . $coupon->code . '</span>',
                'type' => ucfirst($coupon->type),
                'discount' => $discountDisplay,
                'limit' => $coupon->limit ? str_pad($coupon->limit, 2, '0', STR_PAD_LEFT) : '-',
                'used' => str_pad($coupon->used, 2, '0', STR_PAD_LEFT),
                'valid' => $coupon->valid_until ? $coupon->valid_until->format('d M Y') : '-',
                'status' => '<span class="badge ' . $statusBadge . '">' . $statusText . '</span>',
                'action' => $this->getActionButtons($coupon)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($coupon)
    {
        return '<div class="edit-delete-action">
                    <a class="me-2 p-2 action-edit" href="javascript:void(0);" onclick="editCoupon(' . $coupon->id . ')" title="Edit Coupon">
                        <i data-feather="edit" class="feather-edit"></i>
                    </a>
                    <a class="p-2 action-delete" href="javascript:void(0);" onclick="deleteCoupon(' . $coupon->id . ')" title="Delete Coupon">
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
            'code' => 'required|string|max:50',
            'type' => 'required|in:fixed,percentage',
            'discount' => 'required|numeric|min:0',
            'limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
        ]);
        
        // Check if coupon code already exists for this store
        $existingCoupon = Coupon::where('code', strtoupper($request->code))
            ->where('store_id', $selectedStoreId)
            ->exists();
            
        if ($existingCoupon) {
            return response()->json(['success' => false, 'message' => 'Coupon code already exists for this store'], 400);
        }

        try {
            $coupon = Coupon::create([
                'store_id' => $selectedStoreId,
                'user_id' => Auth::id(),
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'type' => $request->type,
                'discount' => $request->discount,
                'limit' => $request->limit,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
                'min_purchase' => $request->min_purchase,
                'max_discount' => $request->max_discount,
                'is_active' => $request->has('is_active') ? true : false,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Coupon created successfully', 'coupon' => $coupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $coupon = Coupon::findOrFail($id);
        return response()->json(['success' => true, 'coupon' => $coupon]);
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'type' => 'required|in:fixed,percentage',
            'discount' => 'required|numeric|min:0',
            'limit' => 'nullable|integer|min:1',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
        ]);

        try {
            $coupon->update([
                'name' => $request->name,
                'code' => strtoupper($request->code),
                'type' => $request->type,
                'discount' => $request->discount,
                'limit' => $request->limit,
                'valid_from' => $request->valid_from,
                'valid_until' => $request->valid_until,
                'min_purchase' => $request->min_purchase,
                'max_discount' => $request->max_discount,
                'is_active' => $request->has('is_active') ? true : false,
                'description' => $request->description,
            ]);

            return response()->json(['success' => true, 'message' => 'Coupon updated successfully', 'coupon' => $coupon]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();
            return response()->json(['success' => true, 'message' => 'Coupon deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->is_active = !$coupon->is_active;
            $coupon->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function generateCode(Request $request)
    {
        $name = $request->get('name', '');
        $code = Coupon::generateCodeFromName($name);
        return response()->json(['success' => true, 'code' => $code]);
    }

    /**
     * Validate and apply coupon (for POS and Invoice)
     */
    public function applyCoupon(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required|string',
                'amount' => 'required|numeric|min:0',
            ]);

            $selectedStoreId = session('selected_store_id');
            $code = strtoupper(trim($request->code));
            
            if (!$selectedStoreId) {
                return response()->json(['success' => false, 'message' => 'Please select a store first']);
            }
            
            // Find coupon that matches the code AND belongs to the selected store
            $coupon = Coupon::where('code', $code)
                ->where('store_id', $selectedStoreId)
                ->first();

            if (!$coupon) {
                return response()->json(['success' => false, 'message' => 'Coupon "' . $code . '" not found for this store']);
            }

            $validation = $coupon->isValid($request->amount);
            if (!$validation['valid']) {
                return response()->json(['success' => false, 'message' => $validation['message']]);
            }

            $discountAmount = $coupon->calculateDiscount($request->amount);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully',
                'coupon' => [
                    'id' => $coupon->id,
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'type' => $coupon->type,
                    'discount' => floatval($coupon->discount),
                    'discount_amount' => floatval($discountAmount),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}
