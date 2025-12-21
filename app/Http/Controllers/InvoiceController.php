<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $customers = Customer::orderBy('name')->get();
        
        // Check if it's a DataTables AJAX request (has 'draw' parameter)
        if ($request->ajax() || $request->has('draw')) {
            return $this->getInvoiceData($request);
        }
        
        return view('invoice-report', compact('customers'));
    }

    protected function getInvoiceData(Request $request)
    {
        try {
            $user = auth()->user();
            
            $query = Invoice::with(['user', 'store', 'customer']);

            // Filter by user's accessible stores
            if (!$user->isSuperAdmin()) {
                $accessibleStoreIds = $user->getAccessibleStores()->pluck('id')->toArray();
                if (!empty($accessibleStoreIds)) {
                    $query->whereIn('store_id', $accessibleStoreIds);
                }
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

            // DataTables processing
            $totalData = $query->count();
            $totalFiltered = $totalData;

            // Search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', '%' . $search . '%')
                      ->orWhere('customer_name', 'like', '%' . $search . '%');
                });
                $totalFiltered = $query->count();
            }

            // Order
            $query->orderBy('created_at', 'desc');

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;
            $invoices = $query->skip($start)->take($length)->get();

            $data = [];
            $rowNum = $start + 1;
            foreach ($invoices as $invoice) {
                $statusBadge = match($invoice->status) {
                    'paid' => 'badge-linesuccess',
                    'partial' => 'badge-linewarning',
                    'overdue' => 'badge-linedanger',
                    'sent' => 'badge-lineinfo',
                    default => 'badge-linedanger'
                };

                $data[] = [
                    'row_number' => $rowNum++,
                    'invoice_no' => $invoice->invoice_number,
                    'customer' => $invoice->customer_name ?: 'Walk in Customer',
                    'due_date' => $invoice->due_date ? $invoice->due_date->format('d M Y') : '-',
                    'amount' => 'MYR ' . number_format($invoice->total, 2),
                    'paid' => 'MYR ' . number_format($invoice->amount_paid, 2),
                    'amount_due' => 'MYR ' . number_format($invoice->amount_due, 2),
                    'status' => '<span class="badge ' . $statusBadge . '">' . ucfirst($invoice->status) . '</span>',
                    'action' => $this->getActionButtons($invoice)
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalData,
                'recordsFiltered' => $totalFiltered,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->draw ?? 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function getActionButtons($invoice)
    {
        $paymentBtn = '';
        if ($invoice->status !== 'paid') {
            $paymentBtn = '<a class="me-2 p-2" href="javascript:void(0);" onclick="openPaymentModal(' . $invoice->id . ', \'' . $invoice->invoice_number . '\', ' . $invoice->amount_due . ')" title="Record Payment">
                            <i data-feather="dollar-sign" class="feather-dollar-sign"></i>
                          </a>';
        }
        
        return '<div class="edit-delete-action">
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="viewInvoice(' . $invoice->id . ')" title="View">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    ' . $paymentBtn . '
                    <a class="me-2 p-2" href="javascript:void(0);" onclick="printInvoice(' . $invoice->id . ')" title="Print">
                        <i data-feather="printer" class="feather-printer"></i>
                    </a>
                    <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteInvoice(' . $invoice->id . ')" title="Delete">
                        <i data-feather="trash-2" class="feather-trash-2"></i>
                    </a>
                </div>';
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
            'due_date' => 'nullable|date',
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

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
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
                'amount_paid' => 0,
                'amount_due' => $total,
                'due_date' => $request->due_date,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
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
                'message' => 'Invoice created successfully',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $invoice = Invoice::with(['items.product', 'user', 'store'])->findOrFail($id);
        return response()->json(['success' => true, 'invoice' => $invoice]);
    }

    public function updatePayment(Request $request, $id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $previousStatus = $invoice->status;
        
        // Handle coupon if applied
        if ($request->coupon_id) {
            $coupon = Coupon::find($request->coupon_id);
            if ($coupon) {
                $coupon->incrementUsage();
                $invoice->coupon_id = $coupon->id;
                // Apply coupon discount to invoice
                $invoice->discount = ($invoice->discount ?? 0) + ($request->coupon_discount ?? 0);
            }
        }
        
        $invoice->amount_paid += $request->amount;
        $invoice->amount_due = $invoice->total - $invoice->amount_paid - ($invoice->discount ?? 0);
        
        if ($invoice->amount_due <= 0) {
            $invoice->status = 'paid';
            $invoice->amount_due = 0;
            
            // Create a sale (order) when invoice is fully paid
            if ($previousStatus != 'paid') {
                $this->createSaleFromInvoice($invoice);
            }
        } else {
            $invoice->status = 'partial';
        }
        
        $invoice->save();

        return response()->json(['success' => true, 'message' => 'Payment recorded successfully']);
    }
    
    /**
     * Create a sale (order) from a paid invoice
     */
    protected function createSaleFromInvoice(Invoice $invoice)
    {
        // Check if order already exists for this invoice
        $existingOrder = Order::where('invoice_id', $invoice->id)->first();
        if ($existingOrder) {
            // Update existing order payment status
            $existingOrder->payment_status = 'paid';
            $existingOrder->save();
            return $existingOrder;
        }
        
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        
        // Create new order
        $order = Order::create([
            'order_number' => $orderNumber,
            'invoice_id' => $invoice->id,
            'store_id' => $invoice->store_id,
            'user_id' => $invoice->user_id,
            'customer_name' => $invoice->customer_name,
            'customer_email' => $invoice->customer_email,
            'customer_phone' => $invoice->customer_phone,
            'subtotal' => $invoice->subtotal,
            'tax' => $invoice->tax,
            'discount' => $invoice->discount,
            'total' => $invoice->total,
            'payment_method' => 'invoice',
            'payment_status' => 'paid',
            'order_status' => 'completed',
            'notes' => 'Generated from Invoice: ' . $invoice->invoice_number,
        ]);
        
        // Create order items from invoice items
        foreach ($invoice->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product_name,
                'product_sku' => $item->product_sku ?? '',
                'quantity' => $item->quantity,
                'price' => $item->price,
                'discount' => $item->discount ?? 0,
                'tax' => $item->tax ?? 0,
                'subtotal' => $item->subtotal,
            ]);
        }
        
        return $order;
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $invoice->items()->delete();
            $invoice->delete();
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Invoice deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}

