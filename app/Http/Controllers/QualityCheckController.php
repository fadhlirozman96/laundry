<?php

namespace App\Http\Controllers;

use App\Models\QualityCheck;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QualityCheckController extends Controller
{
    protected function getStoreId()
    {
        // Check for selected_store_id first (from POS store selector)
        $storeId = session('selected_store_id');
        
        if (!$storeId) {
            // Fallback to user's first accessible store
            $user = auth()->user();
            if ($user) {
                $userStores = $user->getAccessibleStores();
                if ($userStores->count() > 0) {
                    $storeId = $userStores->first()->id;
                    // Set it in session for future use
                    session(['selected_store_id' => $storeId]);
                }
            }
        }
        
        return $storeId;
    }

    /**
     * QC Dashboard - Orders pending QC
     */
    public function index()
    {
        $storeId = $this->getStoreId();
        
        // Orders that need QC (in 'processing' status without a quality check)
        $pendingOrders = Order::where('store_id', $storeId)
            ->where('order_status', 'processing')
            ->doesntHave('qualityCheck')
            ->with('items')
            ->orderBy('created_at', 'asc')
            ->get();

        // Recently completed QC
        $recentQC = QualityCheck::where('store_id', $storeId)
            ->with(['order', 'user'])
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
        
        $order = Order::where('store_id', $storeId)
            ->with('items')
            ->findOrFail($orderId);

        // Check if order is ready for QC
        if ($order->order_status !== 'processing') {
            return redirect()->route('laundry.qc.index')
                ->with('error', 'Order is not ready for Quality Check');
        }

        // Check if QC already exists
        if ($order->qualityCheck) {
            return redirect()->route('laundry.qc.index')
                ->with('error', 'Quality Check already completed for this order');
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
        
        $order = Order::where('store_id', $storeId)->findOrFail($orderId);

        $request->validate([
            // 1. Quantity (MOST CRITICAL) - now unit-aware
            'quantity_check' => 'required|boolean',
            
            // 2. Status & Workflow
            'status_wash_check' => 'required|boolean',
            'status_dry_check' => 'required|boolean',
            'status_iron_check' => 'nullable|boolean',
            
            // 3. Cleanliness
            'cleanliness_check' => 'required|boolean',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            
            // 3. Odour
            'odour_check' => 'required|boolean',
            'odour_rating' => 'required|integer|min:1|max:5',
            
            // 4. Dryness
            'dryness_check' => 'required|boolean',
            'dryness_rating' => 'required|integer|min:1|max:5',
            'bulky_items_dry' => 'nullable|boolean',
            
            // 5. Finishing/Ironing
            'ironing_check' => 'nullable|boolean',
            'ironing_rating' => 'nullable|integer|min:1|max:5',
            'no_wrinkles' => 'nullable|boolean',
            
            // 5. Folding
            'folding_check' => 'required|boolean',
            'folding_rating' => 'required|integer|min:1|max:5',
            
            // 6. Damage
            'stain_check' => 'nullable|boolean',
            'damage_check' => 'nullable|boolean',
            'damage_photos' => 'nullable|array',
            'damage_photos.*' => 'nullable|image|max:5120', // Max 5MB per image
            
            // 7. Special Instructions
            'special_instructions_check' => 'nullable|boolean',
            'special_instructions_followed' => 'nullable|boolean',
            
            // 8. Packaging & Labeling
            'packaging_check' => 'required|boolean',
            'label_correct' => 'required|boolean',
            'qr_tag_intact' => 'nullable|boolean',
            
            // Final approval
            'final_approval' => 'required|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Unit-aware quantity validation
            $quantityMatch = true;
            $quantityErrors = [];
            
            foreach ($order->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                $qcMode = $product->qc_mode ?? 'count';
                
                switch ($qcMode) {
                    case 'count':
                        // PIECE-BASED: Must match exactly
                        $itemCount = $request->input("item_count.{$item->id}");
                        if ($itemCount != $item->quantity) {
                            $quantityMatch = false;
                            $quantityErrors[] = "{$item->product_name}: Expected {$item->quantity}, got {$itemCount}";
                        }
                        break;
                        
                    case 'completeness':
                        // SET-BASED: All components must be checked
                        $setComplete = $request->input("set_complete.{$item->id}");
                        if (!$setComplete && !$request->has("set_check.{$item->id}")) {
                            $quantityMatch = false;
                            $quantityErrors[] = "{$item->product_name}: Incomplete set";
                        }
                        break;
                        
                    case 'integrity':
                        // KG-BASED: Integrity must be confirmed
                        $integrityCheck = $request->input("integrity_check.{$item->id}");
                        if (!$integrityCheck) {
                            $quantityMatch = false;
                            $quantityErrors[] = "{$item->product_name}: Batch integrity not confirmed";
                        }
                        break;
                        
                    case 'identity':
                        // SQFT-BASED: Identity must be verified
                        $identityCheck = $request->input("identity_check.{$item->id}");
                        if (!$identityCheck) {
                            $quantityMatch = false;
                            $quantityErrors[] = "{$item->product_name}: Identity not verified";
                        }
                        break;
                }
            }
            
            // Handle damage photo uploads
            $damagePhotos = [];
            if ($request->hasFile('damage_photos')) {
                foreach ($request->file('damage_photos') as $photo) {
                    $filename = 'qc_damage_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('qc/damage', $filename, 'public');
                    $damagePhotos[] = $path;
                }
            }

            // Comprehensive pass criteria
            $passed = $request->quantity_check 
                && $quantityMatch // Quantity MUST match (MOST CRITICAL)
                && $request->status_wash_check // Must go through wash
                && $request->status_dry_check // Must go through dry
                && $request->cleanliness_check 
                && $request->cleanliness_rating >= 3
                && $request->odour_check 
                && $request->odour_rating >= 3
                && $request->dryness_check
                && $request->dryness_rating >= 3
                && $request->folding_check
                && $request->folding_rating >= 3
                && $request->packaging_check
                && $request->label_correct
                && $request->final_approval
                && !$request->damage_check; // Cannot pass if damage detected

            // Store unit-aware QC data in order items
            foreach ($order->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                $qcMode = $product->qc_mode ?? 'count';
                $qcData = [];
                
                switch ($qcMode) {
                    case 'count':
                        $qcData = ['counted' => $request->input("item_count.{$item->id}")];
                        break;
                    case 'completeness':
                        $components = $request->input("set_check.{$item->id}", []);
                        $qcData = ['components_checked' => array_keys($components)];
                        break;
                    case 'integrity':
                        $qcData = ['integrity_confirmed' => true];
                        break;
                    case 'identity':
                        $qcData = ['identity_confirmed' => true];
                        break;
                }
                
                $item->update([
                    'qc_completed' => true,
                    'qc_data' => $qcData
                ]);
            }
            
            // Create QC record
            $qc = QualityCheck::create([
                'order_id' => $order->id,
                'user_id' => $userId,
                'store_id' => $storeId,
                
                // 1. Quantity (MOST CRITICAL) - Unit-aware
                'quantity_check' => $request->quantity_check,
                'items_received' => $order->items->count(),
                'items_counted' => $order->items->count(),
                'quantity_match' => $quantityMatch,
                'quantity_notes' => $quantityErrors ? implode('; ', $quantityErrors) : $request->quantity_notes,
                
                // 2. Status & Workflow
                'status_wash_check' => $request->status_wash_check,
                'status_dry_check' => $request->status_dry_check,
                'status_iron_check' => $request->status_iron_check ?? false,
                'status_notes' => $request->status_notes,
                
                // 3. Cleanliness
                'cleanliness_check' => $request->cleanliness_check,
                'cleanliness_rating' => $request->cleanliness_rating,
                'cleanliness_notes' => $request->cleanliness_notes,
                
                // 3. Odour
                'odour_check' => $request->odour_check,
                'odour_rating' => $request->odour_rating,
                'odour_notes' => $request->odour_notes,
                
                // 4. Dryness
                'dryness_check' => $request->dryness_check,
                'dryness_rating' => $request->dryness_rating,
                'bulky_items_dry' => $request->bulky_items_dry ?? false,
                'dryness_notes' => $request->dryness_notes,
                
                // 5. Finishing/Ironing
                'ironing_check' => $request->ironing_check ?? false,
                'ironing_rating' => $request->ironing_rating,
                'no_wrinkles' => $request->no_wrinkles ?? false,
                'ironing_notes' => $request->ironing_notes,
                
                // 5. Folding
                'folding_check' => $request->folding_check,
                'folding_rating' => $request->folding_rating,
                'folding_notes' => $request->folding_notes,
                
                // 6. Damage
                'stain_check' => $request->stain_check ?? false,
                'stain_notes' => $request->stain_notes,
                'damage_check' => $request->damage_check ?? false,
                'damage_notes' => $request->damage_notes,
                'damage_photos' => $damagePhotos,
                
                // 7. Special Instructions
                'special_instructions_check' => $request->special_instructions_check ?? false,
                'special_instructions_followed' => $request->special_instructions_followed ?? false,
                'special_instructions_notes' => $request->special_instructions_notes,
                
                // 8. Packaging & Labeling
                'packaging_check' => $request->packaging_check,
                'label_correct' => $request->label_correct,
                'qr_tag_intact' => $request->qr_tag_intact ?? false,
                'packaging_notes' => $request->packaging_notes,
                
                // Overall
                'passed' => $passed,
                'overall_notes' => $request->overall_notes,
                'rejection_reason' => !$passed ? $request->rejection_reason : null,
                'final_approval' => $request->final_approval,
                'approved_at' => $request->final_approval ? now() : null,
            ]);

            // If QC passed, automatically move order to 'completed' status
            if ($passed) {
                $order->update([
                    'order_status' => 'completed'
                ]);
            }

            DB::commit();

            if ($passed) {
                $message = 'Quality Check PASSED! Order #' . $order->order_number . ' has been marked as Completed and is ready for collection.';
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'passed' => true,
                    'qc' => $qc,
                    'order_status' => 'completed'
                ]);
            } else {
                // Provide specific failure reasons
                $failureReasons = [];
                if (!$quantityMatch) {
                    if (!empty($quantityErrors)) {
                        $failureReasons = array_merge($failureReasons, $quantityErrors);
                    } else {
                        $failureReasons[] = "Item verification failed";
                    }
                }
                if (!$request->status_wash_check) {
                    $failureReasons[] = "Items not washed";
                }
                if (!$request->status_dry_check) {
                    $failureReasons[] = "Items not dried";
                }
                if ($request->cleanliness_rating < 3) {
                    $failureReasons[] = "Cleanliness below standard (Rating: {$request->cleanliness_rating}/5)";
                }
                if ($request->odour_rating < 3) {
                    $failureReasons[] = "Odour issue detected (Rating: {$request->odour_rating}/5)";
                }
                if ($request->dryness_rating < 3) {
                    $failureReasons[] = "Items not fully dry (Rating: {$request->dryness_rating}/5)";
                }
                if ($request->folding_rating < 3) {
                    $failureReasons[] = "Folding quality below standard (Rating: {$request->folding_rating}/5)";
                }
                if (!$request->packaging_check || !$request->label_correct) {
                    $failureReasons[] = "Packaging or labeling issue";
                }
                if ($request->damage_check) {
                    $failureReasons[] = "WARNING: Damage detected - Cannot mark as completed";
                }
                if (!$request->final_approval) {
                    $failureReasons[] = "Final approval not given";
                }
                
                $message = "Quality Check FAILED for Order #" . $order->order_number . ". \n\nReasons:\n" . implode("\n", $failureReasons);
                
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'passed' => false,
                    'qc' => $qc,
                    'failure_reasons' => $failureReasons
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
            ->with(['order.items', 'user'])
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
            ->with(['order', 'user']);

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



