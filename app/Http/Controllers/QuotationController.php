<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $customers = Customer::orderBy('name')->get();
        $products = Product::where('is_active', true);
        
        // Filter by store
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $products->whereIn('store_id', $accessibleStoreIds);
        }
        if (session('selected_store_id')) {
            $products->where('store_id', session('selected_store_id'));
        }
        $products = $products->get();
        
        // Check if it's a DataTables AJAX request (has 'draw' parameter)
        if ($request->ajax() || $request->has('draw')) {
            return $this->getQuotationData($request);
        }
        
        return view('quotation-list', compact('customers', 'products'));
    }

    /**
     * Get products/services for quotation dropdown
     */
    public function getProducts()
    {
        try {
            $user = auth()->user();
            $storeId = session('selected_store_id');
            
            // Get products for the selected store only
            $query = Product::with(['category', 'unit'])
                ->where('is_active', true);
            
            // Filter by selected store
            if ($storeId) {
                $query->where('store_id', $storeId);
            } else {
                // If no store selected, get products from user's accessible stores
                if (!$user->isSuperAdmin()) {
                    $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                    if (!empty($accessibleStoreIds)) {
                        $query->whereIn('store_id', $accessibleStoreIds);
                    }
                }
            }
            
            $products = $query->orderBy('name')->get();
            
            // Remove duplicates by name (in case same product exists multiple times)
            $products = $products->unique('name')->values();

            return response()->json([
                'success' => true,
                'products' => $products
            ]);
        } catch (\Exception $e) {
            \Log::error('getProducts error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'products' => []
            ], 500);
        }
    }

    protected function getQuotationData(Request $request)
    {
        $user = auth()->user();
        
        $query = Quotation::with(['items.product', 'user', 'store', 'customer']);

        // Filter by user's accessible stores
        if (!$user->isSuperAdmin()) {
            $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
            $query->whereIn('store_id', $accessibleStoreIds);
        }
        
        if (session('selected_store_id')) {
            $query->where('store_id', session('selected_store_id'));
        }

        // Apply filters
        if ($request->has('customer') && $request->customer != '') {
            $query->where('customer_name', 'like', '%' . $request->customer . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('product') && $request->product != '') {
            $query->whereHas('items', function($q) use ($request) {
                $q->where('product_id', $request->product);
            });
        }

        // DataTables processing
        $totalData = $query->count();
        $totalFiltered = $totalData;

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('quotation_number', 'like', '%' . $search . '%')
                  ->orWhere('customer_name', 'like', '%' . $search . '%');
            });
            $totalFiltered = $query->count();
        }

        // Order
        $query->orderBy('created_at', 'desc');

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $quotations = $query->skip($start)->take($length)->get();

        $data = [];
        $rowNum = $start + 1;
        foreach ($quotations as $quotation) {
            $statusBadge = match($quotation->status) {
                'sent' => 'status-badge',
                'accepted' => 'badges bg-lightgreen',
                'rejected' => 'badges bg-lightred',
                'converted' => 'order-badge',
                'expired' => 'badges bg-lightred',
                default => 'unstatus-badge'
            };

            // Get first product name for display
            $firstItem = $quotation->items->first();
            $productName = $firstItem ? $firstItem->product_name : 'N/A';

            $data[] = [
                'row_number' => $rowNum++,
                'reference' => $quotation->quotation_number,
                'customer' => $quotation->customer_name ?: 'Walk in Customer',
                'phone' => $quotation->customer_phone ?: '-',
                'status' => '<span class="badges ' . $statusBadge . '">' . ucfirst($quotation->status) . '</span>',
                'grand_total' => 'MYR ' . number_format($quotation->total, 2),
                'action' => $this->getActionButtons($quotation)
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalFiltered,
            'data' => $data
        ]);
    }

    protected function getActionButtons($quotation)
    {
        $actionBtn = '';
        
        // Show Accept button for pending/sent quotations
        if (in_array($quotation->status, ['pending', 'sent'])) {
            $actionBtn = '<a class="action-accept" href="javascript:void(0);" onclick="acceptQuotation(' . $quotation->id . ')" title="Accept Quotation">
                            <i data-feather="check-circle" class="feather-check-circle"></i>
                          </a>';
        }
        
        // Show View Invoice button for accepted quotations
        if ($quotation->status == 'accepted') {
            $actionBtn = '<a class="action-invoice" href="javascript:void(0);" onclick="viewQuotationInvoice(' . $quotation->id . ')" title="View Invoice">
                            <i data-feather="file-text" class="feather-file-text"></i>
                          </a>';
        }
        
        return '<div class="edit-delete-action">
                    <a class="action-view" href="javascript:void(0);" onclick="viewQuotation(' . $quotation->id . ')" title="View">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    ' . $actionBtn . '
                    <a class="action-delete" href="javascript:void(0);" onclick="deleteQuotation(' . $quotation->id . ')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'valid_until' => 'nullable|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();
        $storeId = session('selected_store_id');

        if (!$storeId) {
            $userStores = $user->getAccessibleStores();
            if ($userStores->count() > 0) {
                $storeId = $userStores->first()->id;
            } else {
                return response()->json(['success' => false, 'message' => 'No store available'], 422);
            }
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['price'] * $item['quantity'];
            }

            $tax = $request->tax ?? 0;
            $discount = $request->discount ?? 0;
            $total = $subtotal + $tax - $discount;

            $quotation = Quotation::create([
                'quotation_number' => Quotation::generateQuotationNumber(),
                'store_id' => $storeId,
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'valid_until' => $request->valid_until ?? now()->addDays(30),
                'notes' => $request->notes,
                'terms' => $request->terms,
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'discount' => $item['discount'] ?? 0,
                    'tax' => $item['tax'] ?? 0,
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Quotation created successfully',
                'quotation' => $quotation
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $quotation = Quotation::with(['items.product.unit', 'user', 'store'])->findOrFail($id);
        return response()->json(['success' => true, 'quotation' => $quotation]);
    }

    /**
     * Download quotation as PDF
     */
    public function downloadPdf($id)
    {
        $quotation = Quotation::with(['items.product.unit', 'user', 'store'])->findOrFail($id);
        
        // Generate HTML for PDF
        $html = view('pdf.quotation', compact('quotation'))->render();
        
        // Use browser to render PDF (or use a PDF library)
        return response($html)
            ->header('Content-Type', 'text/html');
    }

    public function updateStatus(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->status = $request->status;
        $quotation->save();
        
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }

    /**
     * Accept a quotation - this generates an invoice automatically
     */
    public function accept($id)
    {
        $quotation = Quotation::with('items')->findOrFail($id);
        
        if ($quotation->status === 'accepted') {
            return response()->json(['success' => false, 'message' => 'Quotation already accepted'], 400);
        }
        
        DB::beginTransaction();
        try {
            // Update quotation status to accepted
            $quotation->status = 'accepted';
            $quotation->save();

            // Auto-generate Invoice
            $invoiceNumber = Invoice::generateInvoiceNumber();
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'store_id' => $quotation->store_id,
                'user_id' => Auth::id(),
                'customer_name' => $quotation->customer_name ?: 'Walk in Customer',
                'customer_email' => $quotation->customer_email,
                'customer_phone' => $quotation->customer_phone,
                'customer_address' => $quotation->customer_address,
                'subtotal' => $quotation->subtotal,
                'tax' => $quotation->tax,
                'discount' => $quotation->discount,
                'total' => $quotation->total,
                'amount_paid' => 0,
                'amount_due' => $quotation->total,
                'due_date' => now()->addDays(30),
                'status' => 'sent',
                'notes' => 'Generated from Quotation: ' . $quotation->quotation_number,
            ]);

            // Create invoice items from quotation items
            foreach ($quotation->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product_name,
                    'product_sku' => $item->product_sku,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'discount' => $item->discount,
                    'tax' => $item->tax,
                    'subtotal' => $item->subtotal,
                ]);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Quotation accepted! Invoice has been generated.',
                'invoice_number' => $invoiceNumber,
                'invoice_id' => $invoice->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get invoice for an accepted quotation
     */
    public function getInvoice($id)
    {
        $quotation = Quotation::findOrFail($id);
        
        // Find invoice by matching quotation number in notes
        $invoice = Invoice::where('notes', 'like', '%' . $quotation->quotation_number . '%')
            ->orWhere(function($q) use ($quotation) {
                $q->where('store_id', $quotation->store_id)
                  ->where('customer_name', $quotation->customer_name)
                  ->where('total', $quotation->total)
                  ->whereDate('created_at', '>=', $quotation->updated_at->toDateString());
            })
            ->first();
        
        if (!$invoice) {
            return response()->json(['success' => false, 'message' => 'Invoice not found'], 404);
        }
        
        return response()->json([
            'success' => true,
            'invoice' => $invoice->load('items')
        ]);
    }

    public function destroy($id)
    {
        $quotation = Quotation::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $quotation->items()->delete();
            $quotation->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Quotation deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

