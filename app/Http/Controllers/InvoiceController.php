<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
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
                    'partial' => 'badges-warning',
                    'overdue' => 'badge-linedanger',
                    'sent' => 'badge-light-info',
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
            $paymentBtn = '<a class="action-payment" href="javascript:void(0);" onclick="openPaymentModal(' . $invoice->id . ', \'' . $invoice->invoice_number . '\', ' . $invoice->amount_due . ')" title="Record Payment">
                            <i data-feather="dollar-sign" class="feather-dollar-sign"></i>
                          </a>';
        }
        
        return '<div class="edit-delete-action">
                    <a class="action-view" href="javascript:void(0);" onclick="viewInvoice(' . $invoice->id . ')" title="View">
                        <i data-feather="eye" class="feather-eye"></i>
                    </a>
                    ' . $paymentBtn . '
                    <a class="action-print" href="javascript:void(0);" onclick="printInvoice(' . $invoice->id . ')" title="Print">
                        <i data-feather="printer" class="feather-printer"></i>
                    </a>
                    <a class="action-delete" href="javascript:void(0);" onclick="deleteInvoice(' . $invoice->id . ')" title="Delete">
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
        $invoice = Invoice::findOrFail($id);
        
        $request->validate([
            'amount' => 'required|numeric|min:0.01'
        ]);

        $invoice->amount_paid += $request->amount;
        $invoice->amount_due = $invoice->total - $invoice->amount_paid;
        
        if ($invoice->amount_due <= 0) {
            $invoice->status = 'paid';
            $invoice->amount_due = 0;
        } else {
            $invoice->status = 'partial';
        }
        
        $invoice->save();

        return response()->json(['success' => true, 'message' => 'Payment recorded successfully']);
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

