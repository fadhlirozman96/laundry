<?php

namespace App\Http\Controllers;

use App\Models\QualityCheck;
use App\Models\LaundryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualityCheckController extends Controller
{
    protected function getStoreId()
    {
        return session('store_id');
    }

    /**
     * QC Dashboard - Orders pending QC
     */
    public function index()
    {
        $storeId = $this->getStoreId();
        
        // Orders that need QC (in folding status, not yet passed QC)
        $pendingOrders = LaundryOrder::where('store_id', $storeId)
            ->where('status', 'folding')
            ->where('qc_passed', false)
            ->with('items')
            ->orderBy('created_at', 'asc')
            ->get();

        // Recently completed QC
        $recentQC = QualityCheck::where('store_id', $storeId)
            ->with(['laundryOrder', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('laundry.quality-check', compact('pendingOrders', 'recentQC'));
    }

    /**
     * Show QC form for an order
     */
    public function create($orderId)
    {
        $storeId = $this->getStoreId();
        
        $order = LaundryOrder::where('store_id', $storeId)
            ->with('items')
            ->findOrFail($orderId);

        // Check if order is ready for QC
        if (!in_array($order->status, ['folding', 'ready'])) {
            return redirect()->route('laundry.qc.index')
                ->with('error', 'Order is not ready for Quality Check');
        }

        return view('laundry.qc-form', compact('order'));
    }

    /**
     * Submit QC result
     */
    public function store(Request $request, $orderId)
    {
        $storeId = $this->getStoreId();
        $userId = auth()->id();
        
        $order = LaundryOrder::where('store_id', $storeId)->findOrFail($orderId);

        $request->validate([
            'cleanliness_check' => 'required|boolean',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'odour_check' => 'required|boolean',
            'odour_rating' => 'required|integer|min:1|max:5',
            'quantity_check' => 'required|boolean',
            'items_counted' => 'required|integer|min:0',
            'folding_check' => 'required|boolean',
            'folding_rating' => 'required|integer|min:1|max:5',
        ]);

        try {
            DB::beginTransaction();

            // Determine if quantity matches
            $quantityMatch = $request->items_counted == $order->total_garments;

            // Determine if passed (all checks done and ratings >= 3)
            $passed = $request->cleanliness_check 
                && $request->odour_check 
                && $request->quantity_check 
                && $request->folding_check
                && $quantityMatch
                && $request->cleanliness_rating >= 3
                && $request->odour_rating >= 3
                && $request->folding_rating >= 3;

            // Create QC record
            $qc = QualityCheck::create([
                'laundry_order_id' => $order->id,
                'user_id' => $userId,
                'store_id' => $storeId,
                'cleanliness_check' => $request->cleanliness_check,
                'cleanliness_rating' => $request->cleanliness_rating,
                'cleanliness_notes' => $request->cleanliness_notes,
                'odour_check' => $request->odour_check,
                'odour_rating' => $request->odour_rating,
                'odour_notes' => $request->odour_notes,
                'quantity_check' => $request->quantity_check,
                'items_received' => $order->total_garments,
                'items_counted' => $request->items_counted,
                'quantity_match' => $quantityMatch,
                'quantity_notes' => $request->quantity_notes,
                'folding_check' => $request->folding_check,
                'folding_rating' => $request->folding_rating,
                'folding_notes' => $request->folding_notes,
                'stain_check' => $request->stain_check ?? false,
                'stain_notes' => $request->stain_notes,
                'damage_check' => $request->damage_check ?? false,
                'damage_notes' => $request->damage_notes,
                'passed' => $passed,
                'overall_notes' => $request->overall_notes,
                'rejection_reason' => !$passed ? $request->rejection_reason : null,
            ]);

            // Update order QC status
            if ($passed) {
                $order->update([
                    'qc_passed' => true,
                    'qc_by' => $userId,
                    'qc_at' => now(),
                ]);
            }

            DB::commit();

            if ($passed) {
                return response()->json([
                    'success' => true,
                    'message' => 'Quality Check PASSED. Order can now be marked as Ready.',
                    'passed' => true,
                    'qc' => $qc
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Quality Check FAILED. Order needs to be re-processed.',
                    'passed' => false,
                    'qc' => $qc
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving QC: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View QC details
     */
    public function show($id)
    {
        $storeId = $this->getStoreId();
        
        $qc = QualityCheck::where('store_id', $storeId)
            ->with(['laundryOrder.items', 'user'])
            ->findOrFail($id);

        return view('laundry.qc-details', compact('qc'));
    }

    /**
     * QC History
     */
    public function history(Request $request)
    {
        $storeId = $this->getStoreId();
        
        $query = QualityCheck::where('store_id', $storeId)
            ->with(['laundryOrder', 'user']);

        if ($request->status === 'passed') {
            $query->where('passed', true);
        } elseif ($request->status === 'failed') {
            $query->where('passed', false);
        }

        if ($request->date) {
            $query->whereDate('created_at', $request->date);
        }

        $qcRecords = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('laundry.qc-history', compact('qcRecords'));
    }
}

