<?php $page = 'laundry-qc'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quality Check: {{ $order->order_number }}</h4>
                <h6>Step-by-step quality inspection</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.qc.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back
                </a>
            </div>
        </div>

        <!-- Stepper -->
        <div class="card mb-4 stepper-card">
            <div class="card-body py-3">
                <div class="stepper-wrapper">
                    <div class="stepper-item active" data-step="1">
                        <div class="stepper-circle">1</div>
                        <div class="stepper-label">Items</div>
                    </div>
                    <div class="stepper-item" data-step="2">
                        <div class="stepper-circle">2</div>
                        <div class="stepper-label">Workflow</div>
                    </div>
                    <div class="stepper-item" data-step="3">
                        <div class="stepper-circle">3</div>
                        <div class="stepper-label">Clean</div>
                    </div>
                    <div class="stepper-item" data-step="4">
                        <div class="stepper-circle">4</div>
                        <div class="stepper-label">Dry</div>
                    </div>
                    <div class="stepper-item" data-step="5">
                        <div class="stepper-circle">5</div>
                        <div class="stepper-label">Finish</div>
                    </div>
                    <div class="stepper-item" data-step="6">
                        <div class="stepper-circle">6</div>
                        <div class="stepper-label">Damage</div>
                    </div>
                    <div class="stepper-item" data-step="7">
                        <div class="stepper-circle">7</div>
                        <div class="stepper-label">Package</div>
                    </div>
                    <div class="stepper-item" data-step="8">
                        <div class="stepper-circle">8</div>
                        <div class="stepper-label">Approve</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row gx-4">
            <!-- QC Form -->
            <div class="col-lg-8 mb-4">
                <form id="qc-form" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- STEP 1: UNIT-AWARE QC -->
                    <div class="qc-step" data-step="1">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i data-feather="hash" class="me-2"></i>Step 1: Item Verification
                                </h5>
                                <small class="text-white">CRITICAL - Unit-aware quality check</small>
                            </div>
                            <div class="card-body" style="padding: 20px;">
                                <div class="alert alert-danger mb-3" style="padding: 10px 14px; font-size: 13px;">
                                    <strong>MANDATORY:</strong> Verify all items returned correctly!
                                </div>
                                
                                @php
                                    $groupedItems = $order->items->groupBy(function($item) {
                                        $product = \App\Models\Product::find($item->product_id);
                                        return $product->qc_mode ?? 'count';
                                    });
                                @endphp
                                
                                <!-- PIECE-BASED ITEMS (COUNT) -->
                                @if(isset($groupedItems['count']) && $groupedItems['count']->count() > 0)
                                <div class="qc-section mb-3">
                                    <div class="section-header-compact">
                                        <div>
                                            <i data-feather="hash"></i>
                                            <span>Count Items</span>
                                            <small>{{ $groupedItems['count']->count() }} item(s)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="qc-items-grid">
                                        @foreach($groupedItems['count'] as $item)
                                        <div class="qc-item-compact">
                                            <div class="qc-item-label">{{ $item->product_name }}</div>
                                            <div class="qc-item-row">
                                                <span class="qc-expected">Exp: <strong>{{ $item->quantity }}</strong></span>
                                                <input type="number" 
                                                       class="form-control form-control-sm piece-count step-required" 
                                                       name="item_count[{{ $item->id }}]" 
                                                       data-expected="{{ $item->quantity }}" 
                                                       data-item-id="{{ $item->id }}"
                                                       data-step="1"
                                                       min="0" 
                                                       value="{{ $item->quantity }}"
                                                       required>
                                            </div>
                                            <div class="count-status-{{ $item->id }}"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <!-- SET-BASED ITEMS (COMPLETENESS) -->
                                @if(isset($groupedItems['completeness']) && $groupedItems['completeness']->count() > 0)
                                <div class="qc-section mb-3">
                                    <div class="section-header-compact">
                                        <div>
                                            <i data-feather="layers"></i>
                                            <span>Check Sets</span>
                                            <small>{{ $groupedItems['completeness']->count() }} set(s)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="qc-sets-compact">
                                        @foreach($groupedItems['completeness'] as $item)
                                            @php
                                                $product = \App\Models\Product::find($item->product_id);
                                                $components = $product->set_components ?? [];
                                                // Only show individual components if set has custom/variable components
                                                $hasCustomComponents = !empty($components) && count($components) > 0;
                                            @endphp
                                            <div class="qc-set-compact">
                                                <div class="qc-set-title">
                                                    <span>{{ $item->product_name }}</span>
                                                    <span class="qc-set-qty-badge">x{{ $item->quantity }}</span>
                                                </div>
                                                <div class="qc-set-checks">
                                                    @if($hasCustomComponents && count($components) <= 2)
                                                        {{-- Show individual components only if 2 or fewer --}}
                                                        @foreach($components as $component)
                                                        <label class="qc-checkbox-compact">
                                                            <input type="checkbox" 
                                                                   class="step-required set-component" 
                                                                   name="set_check[{{ $item->id }}][{{ $loop->index }}]" 
                                                                   data-step="1"
                                                                   value="1">
                                                            <span class="checkmark-small"></span>
                                                            <span>{{ $component }}</span>
                                                        </label>
                                                        @endforeach
                                                    @else
                                                        {{-- Default: Single checkbox for set completeness --}}
                                                        <label class="qc-checkbox-compact">
                                                            <input type="checkbox" 
                                                                   class="step-required" 
                                                                   name="set_complete[{{ $item->id }}]" 
                                                                   data-step="1"
                                                                   value="1">
                                                            <span class="checkmark-small"></span>
                                                            <span>Set complete</span>
                                                        </label>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <!-- KG-BASED ITEMS (INTEGRITY) -->
                                @if(isset($groupedItems['integrity']) && $groupedItems['integrity']->count() > 0)
                                <div class="qc-section mb-3">
                                    <div class="section-header-compact">
                                        <div>
                                            <i data-feather="shield"></i>
                                            <span>Verify Batch</span>
                                            <small>{{ $groupedItems['integrity']->count() }} batch(es)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="qc-batch-compact">
                                        @foreach($groupedItems['integrity'] as $item)
                                        <div class="qc-batch-item">
                                            <div class="qc-batch-title">
                                                <span>{{ $item->product_name }}</span>
                                                <span class="qc-batch-qty-badge">{{ $item->quantity }} kg</span>
                                            </div>
                                            <div class="qc-batch-checks">
                                                <label class="qc-checkbox-compact">
                                                    <input type="checkbox" 
                                                           class="step-required" 
                                                           name="integrity_check[{{ $item->id }}]" 
                                                           data-step="1"
                                                           value="1">
                                                    <span class="checkmark-small"></span>
                                                    <span>Batch complete</span>
                                                </label>
                                                <label class="qc-checkbox-compact">
                                                    <input type="checkbox" 
                                                           name="integrity_no_foreign[{{ $item->id }}]" 
                                                           value="1">
                                                    <span class="checkmark-small"></span>
                                                    <span>No foreign items</span>
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <!-- SQFT-BASED ITEMS (IDENTITY) -->
                                @if(isset($groupedItems['identity']) && $groupedItems['identity']->count() > 0)
                                <div class="qc-section mb-3">
                                    <div class="section-header-compact">
                                        <div>
                                            <i data-feather="check-circle"></i>
                                            <span>Verify Identity</span>
                                            <small>{{ $groupedItems['identity']->count() }} item(s)</small>
                                        </div>
                                    </div>
                                    
                                    <div class="qc-identity-compact">
                                        @foreach($groupedItems['identity'] as $item)
                                        <div class="qc-identity-item">
                                            <div class="qc-identity-title">
                                                <span>{{ $item->product_name }}</span>
                                                <span class="qc-identity-qty-badge">{{ $item->quantity }} sqft</span>
                                            </div>
                                            <label class="qc-checkbox-compact">
                                                <input type="checkbox" 
                                                       class="step-required" 
                                                       name="identity_check[{{ $item->id }}]" 
                                                       data-step="1"
                                                       value="1">
                                                <span class="checkmark-small"></span>
                                                <span>Correct item returned</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                <!-- General Check -->
                                <div class="qc-final-check">
                                    <label class="qc-checkbox large">
                                        <input type="checkbox" class="step-required" name="quantity_check" id="quantity_check" value="1" data-step="1">
                                        <span class="checkmark"></span>
                                        <span class="label-text">All items verified and accounted for</span>
                                    </label>
                                </div>
                                
                                <div class="qc-notes-section">
                                    <label class="form-label">Notes (optional)</label>
                                    <input type="text" class="form-control" name="quantity_notes" placeholder="Add any notes or observations...">
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-fail" onclick="failAndReturn()">
                                        <i data-feather="x-circle"></i>
                                        <span>Fail & Return</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-next" onclick="nextStep()">
                                        <span>Next Step</span>
                                        <i data-feather="arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: WORKFLOW CHECK -->
                    <div class="qc-step" data-step="2" style="display: none;">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i data-feather="check-circle" class="me-2"></i>Step 2: Status & Workflow Check
                                </h5>
                                <small>Ensure all items went through proper process</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <strong>MANDATORY:</strong> All items must complete wash and dry cycles!
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="status_wash_check" id="status_wash_check" value="1" data-step="2">
                                            <label class="form-check-label" for="status_wash_check">
                                                <strong>All items WASHED</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="status_dry_check" id="status_dry_check" value="1" data-step="2">
                                            <label class="form-check-label" for="status_dry_check">
                                                <strong>All items DRIED</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input" type="checkbox" name="status_iron_check" id="status_iron_check" value="1">
                                            <label class="form-check-label" for="status_iron_check">
                                                IRONED (if applicable)
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="button" class="qc-pass-all-btn" onclick="passAllWorkflow()">
                                        <i data-feather="check"></i>
                                        <span>Pass All</span>
                                    </button>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Notes (optional)</label>
                                    <input type="text" class="form-control" name="status_notes" placeholder="Any status issues?">
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-prev" onclick="prevStep()">
                                        <i data-feather="arrow-left"></i>
                                        <span>Previous</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-fail" onclick="failAndReturn()">
                                        <i data-feather="x-circle"></i>
                                        <span>Fail</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-next" onclick="nextStep()">
                                        <span>Next Step</span>
                                        <i data-feather="arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: CLEANLINESS & ODOUR -->
                    <div class="qc-step" data-step="3" style="display: none;">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i data-feather="droplet" class="me-2"></i>Step 3: Cleanliness & Odour
                                </h5>
                                <small class="text-white">Check for dirt and smell</small>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-3">Cleanliness</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="cleanliness_check" id="cleanliness_check" value="1" data-step="3">
                                            <label class="form-check-label" for="cleanliness_check">
                                                <strong>No visible dirt</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Rating *</strong></label>
                                        <select class="form-control rating-select step-required" name="cleanliness_rating" data-step="3">
                                            <option value="">Select</option>
                                            <option value="5">5 - Excellent</option>
                                            <option value="4">4 - Good</option>
                                            <option value="3">3 - Acceptable</option>
                                            <option value="2">2 - Below (FAIL)</option>
                                            <option value="1">1 - Poor (FAIL)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Notes</label>
                                        <input type="text" class="form-control" name="cleanliness_notes">
                                    </div>
                                </div>
                                
                                <hr>
                                <h6 class="mb-3">Odour</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="odour_check" id="odour_check" value="1" data-step="3">
                                            <label class="form-check-label" for="odour_check">
                                                <strong>No bad odours</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Rating *</strong></label>
                                        <select class="form-control rating-select step-required" name="odour_rating" data-step="3">
                                            <option value="">Select</option>
                                            <option value="5">5 - Fresh</option>
                                            <option value="4">4 - Good</option>
                                            <option value="3">3 - Neutral</option>
                                            <option value="2">2 - Slight (FAIL)</option>
                                            <option value="1">1 - Bad (FAIL)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Notes</label>
                                        <input type="text" class="form-control" name="odour_notes">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="button" class="qc-pass-all-btn" onclick="passAllClean()">
                                        <i data-feather="check"></i>
                                        <span>Pass All</span>
                                    </button>
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-prev" onclick="prevStep()">
                                        <i data-feather="arrow-left"></i>
                                        <span>Previous</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-fail" onclick="failAndReturn()">
                                        <i data-feather="x-circle"></i>
                                        <span>Fail</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-next" onclick="nextStep()">
                                        <span>Next Step</span>
                                        <i data-feather="arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 4: DRYNESS -->
                    <div class="qc-step" data-step="4" style="display: none;">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i data-feather="sun" class="me-2"></i>Step 4: Dryness Inspection
                                </h5>
                                <small class="text-white">Ensure completely dry</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>TIP:</strong> Check bulky items thoroughly - they take longer to dry!
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="dryness_check" id="dryness_check" value="1" data-step="4">
                                            <label class="form-check-label" for="dryness_check">
                                                <strong>All items completely dry</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Rating *</strong></label>
                                        <select class="form-control rating-select step-required" name="dryness_rating" data-step="4">
                                            <option value="">Select</option>
                                            <option value="5">5 - Fully Dry</option>
                                            <option value="4">4 - Almost Dry</option>
                                            <option value="3">3 - Acceptable</option>
                                            <option value="2">2 - Damp (FAIL)</option>
                                            <option value="1">1 - Wet (FAIL)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Notes</label>
                                        <input type="text" class="form-control" name="dryness_notes">
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="bulky_items_dry" id="bulky_items_dry" value="1">
                                            <label class="form-check-label" for="bulky_items_dry">
                                                Bulky items dry to core
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="button" class="qc-pass-all-btn" onclick="passAllDry()">
                                        <i data-feather="check"></i>
                                        <span>Pass All</span>
                                    </button>
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-prev" onclick="prevStep()">
                                        <i data-feather="arrow-left"></i>
                                        <span>Previous</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-fail" onclick="failAndReturn()">
                                        <i data-feather="x-circle"></i>
                                        <span>Fail</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-next" onclick="nextStep()">
                                        <span>Next Step</span>
                                        <i data-feather="arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 5: FINISHING -->
                    <div class="qc-step" data-step="5" style="display: none;">
                        <div class="card border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i data-feather="layers" class="me-2"></i>Step 5: Finishing & Folding
                                </h5>
                                <small class="text-white">Check presentation quality</small>
                            </div>
                            <div class="card-body">
                                <h6 class="mb-3">Ironing (if applicable)</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input" type="checkbox" name="ironing_check" id="ironing_check" value="1">
                                            <label class="form-check-label" for="ironing_check">
                                                Items ironed
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Rating</label>
                                        <select class="form-control" name="ironing_rating">
                                            <option value="">N/A</option>
                                            <option value="5">5 - Perfect</option>
                                            <option value="4">4 - Good</option>
                                            <option value="3">3 - OK</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>
                                <h6 class="mb-3">Folding</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="folding_check" id="folding_check" value="1" data-step="5">
                                            <label class="form-check-label" for="folding_check">
                                                <strong>Neat and professional</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label"><strong>Rating *</strong></label>
                                        <select class="form-control rating-select step-required" name="folding_rating" data-step="5">
                                            <option value="">Select</option>
                                            <option value="5">5 - Perfect</option>
                                            <option value="4">4 - Good</option>
                                            <option value="3">3 - Acceptable</option>
                                            <option value="2">2 - Needs Work (FAIL)</option>
                                            <option value="1">1 - Poor (FAIL)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Notes</label>
                                        <input type="text" class="form-control" name="folding_notes">
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="button" class="qc-pass-all-btn" onclick="passAllFinish()">
                                        <i data-feather="check"></i>
                                        <span>Pass All</span>
                                    </button>
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-prev" onclick="prevStep()">
                                        <i data-feather="arrow-left"></i>
                                        <span>Previous</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-fail" onclick="failAndReturn()">
                                        <i data-feather="x-circle"></i>
                                        <span>Fail</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-next" onclick="nextStep()">
                                        <span>Next Step</span>
                                        <i data-feather="arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 6: DAMAGE -->
                    <div class="qc-step" data-step="6" style="display: none;">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i data-feather="alert-triangle" class="me-2"></i>Step 6: Damage Inspection
                                </h5>
                                <small class="text-white">CRITICAL - Record all damage to avoid disputes</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-danger">
                                    <strong>IMPORTANT:</strong> If damage found, photos are MANDATORY!
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label fs-5"><strong>Damage Status *</strong></label>
                                    <div class="form-check form-check-lg mb-2">
                                        <input class="form-check-input step-required" type="radio" name="damage_status" id="no_damage" value="no_damage" data-step="6" checked>
                                        <label class="form-check-label" for="no_damage">
                                            <strong>No damage found</strong>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input step-required" type="radio" name="damage_status" id="damage_found" value="damage_found" data-step="6">
                                        <label class="form-check-label text-danger" for="damage_found">
                                            <strong>Damage found</strong>
                                        </label>
                                    </div>
                                </div>
                                
                                <div id="damage-details" style="display: none;">
                                    <div class="alert alert-warning">
                                        <strong>MANDATORY FIELDS:</strong> Description and at least 1 photo required!
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="stain_check" id="stain_check" value="1">
                                                <label class="form-check-label" for="stain_check">
                                                    Stains detected
                                                </label>
                                            </div>
                                            <textarea class="form-control" name="stain_notes" id="stain_notes" rows="2" placeholder="Describe stains..."></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="damage_check" id="damage_check" value="1">
                                                <label class="form-check-label" for="damage_check">
                                                    Physical damage
                                                </label>
                                            </div>
                                            <textarea class="form-control" name="damage_notes" id="damage_notes" rows="2" placeholder="Torn/buttons/discolor..."></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-danger"><strong>Upload Photos * (Min 1)</strong></label>
                                        <input type="file" class="form-control" name="damage_photos[]" id="damage_photos" accept="image/*" multiple>
                                        <small class="text-muted">Max 5 photos, 5MB each</small>
                                    </div>
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-prev" onclick="prevStep()">
                                        <i data-feather="arrow-left"></i>
                                        <span>Previous</span>
                                    </button>
                                    <button type="button" class="qc-btn qc-btn-next" onclick="nextStep()">
                                        <span>Next Step</span>
                                        <i data-feather="arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 7: PACKAGING -->
                    <div class="qc-step" data-step="7" style="display: none;">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i data-feather="package" class="me-2"></i>Step 7: Packaging & Labeling
                                </h5>
                                <small>Avoid mix-ups - Double check labels!</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning">
                                    <strong>MANDATORY:</strong> Label must match order exactly!
                                </div>
                                
                                @if($order->special_instructions)
                                <div class="alert alert-info mb-4">
                                    <h6>Special Instructions from Customer:</h6>
                                    <p class="mb-2"><strong>{{ $order->special_instructions }}</strong></p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="special_instructions_check" id="special_instructions_check" value="1">
                                        <label class="form-check-label" for="special_instructions_check">
                                            Instructions read and followed
                                        </label>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="packaging_check" id="packaging_check" value="1" data-step="7">
                                            <label class="form-check-label" for="packaging_check">
                                                <strong>Properly packaged</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input step-required" type="checkbox" name="label_correct" id="label_correct" value="1" data-step="7">
                                            <label class="form-check-label" for="label_correct">
                                                <strong>Label correct</strong>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check form-check-lg">
                                            <input class="form-check-input" type="checkbox" name="qr_tag_intact" id="qr_tag_intact" value="1">
                                            <label class="form-check-label" for="qr_tag_intact">
                                                QR tag attached
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <button type="button" class="qc-pass-all-btn" onclick="passAllPackaging()">
                                        <i data-feather="check"></i>
                                        <span>Pass All</span>
                                    </button>
                                </div>
                                
                                <!-- Payment Info -->
                                <hr>
                                <h6 class="mb-3">Payment Status (Info Only)</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="mb-1 text-muted">Status:</p>
                                        <h5>{!! $order->getPaymentStatusBadge() !!}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1 text-muted">Amount:</p>
                                        <h5>MYR {{ number_format($order->total, 2) }}</h5>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-1 text-muted">Method:</p>
                                        <h5>{{ $order->payment_method ?? 'N/A' }}</h5>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <button type="button" class="btn btn-secondary" onclick="prevStep()">
                                        <i data-feather="arrow-left" class="me-2"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">
                                        Next Step <i data-feather="arrow-right" class="ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 8: FINAL APPROVAL -->
                    <div class="qc-step" data-step="8" style="display: none;">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0 text-white">
                                    <i data-feather="shield" class="me-2"></i>Step 8: Final Approval
                                </h5>
                                <small class="text-white">Last checkpoint before completion</small>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-success">
                                    <h5 class="alert-heading">Ready to Approve?</h5>
                                    <p class="mb-0">This is the gate before money is collected. Ensure everything is perfect!</p>
                                </div>
                                
                                <div class="card bg-light mb-4">
                                    <div class="card-body">
                                        <h6 class="mb-3">QC Summary</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p><strong>Order:</strong> {{ $order->order_number }}</p>
                                                <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                                                <p><strong>Total Items:</strong> {{ $order->items->sum('quantity') }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p><strong>QC Officer:</strong> {{ auth()->user()->name }}</p>
                                                <p><strong>Date & Time:</strong> <span id="current-datetime"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-check form-check-lg mb-4">
                                    <input class="form-check-input step-required" type="checkbox" name="final_approval" id="final_approval" value="1" data-step="8">
                                    <label class="form-check-label" for="final_approval">
                                        <strong>I confirm ALL checklist items have passed and this order is ready for "Completed" status</strong>
                                    </label>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Overall Notes (Optional)</label>
                                    <textarea class="form-control" name="overall_notes" rows="3" placeholder="Any final comments?"></textarea>
                                </div>
                                
                                <div class="qc-step-buttons">
                                    <button type="button" class="qc-btn qc-btn-prev" onclick="prevStep()">
                                        <i data-feather="arrow-left"></i>
                                        <span>Previous</span>
                                    </button>
                                    <button type="submit" id="submit-btn" class="qc-btn qc-btn-submit" disabled>
                                        <i data-feather="check-circle"></i>
                                        <span>Submit QC Result</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary Sidebar (STICKY) -->
            <div class="col-lg-4">
                <div class="order-summary-sticky">
                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="card-title mb-0 text-white">Order Summary</h5>
                        </div>
                        <div class="card-body order-summary-body">
                        <div class="mb-3">
                            <small class="text-muted d-block">Order Number</small>
                            <h5>{{ $order->order_number }}</h5>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Customer</small>
                            <h6>{{ $order->customer_name }}</h6>
                            @if($order->customer_phone)
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Status</small>
                            {!! $order->getStatusBadge() !!}
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted d-block">Total Items</small>
                            @php
                                $itemSummary = [];
                                foreach($order->items as $item) {
                                    $product = \App\Models\Product::find($item->product_id);
                                    $unitType = $product->unit_type ?? 'piece';
                                    if (!isset($itemSummary[$unitType])) {
                                        $itemSummary[$unitType] = 0;
                                    }
                                    $itemSummary[$unitType] += $item->quantity;
                                }
                            @endphp
                            @foreach($itemSummary as $type => $qty)
                                <h5 class="mb-1">
                                    {{ $qty }} 
                                    <small class="text-muted">
                                        @if($type == 'piece') pieces
                                        @elseif($type == 'set') set(s)
                                        @elseif($type == 'kg') kg
                                        @elseif($type == 'sqft') sqft
                                        @endif
                                    </small>
                                </h5>
                            @endforeach
                        </div>
                        
                        @if($order->items->filter(function($item) {
                            return str_contains(strtolower($item->product_name), 'comforter') ||
                                   str_contains(strtolower($item->product_name), 'blanket') ||
                                   str_contains(strtolower($item->product_name), 'curtain');
                        })->count() > 0)
                        <div class="alert alert-warning mb-3">
                            <strong>BULKY ITEMS DETECTED!</strong>
                            <br>Extra dryness check required.
                        </div>
                        @endif
                        
                        <hr>
                        <h6 class="mb-3">Items List</h6>
                        <div class="mb-2 small">
                            <span class="badge bg-danger me-1">COUNT</span>
                            <span class="badge bg-primary me-1">SET</span>
                            <span class="badge bg-info me-1">BATCH</span>
                            <span class="badge bg-success">IDENTITY</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tbody>
                                    @foreach($order->items as $item)
                                        @php
                                            $product = \App\Models\Product::find($item->product_id);
                                            $unitType = $product->unit_type ?? 'piece';
                                            $unitLabel = '';
                                            switch($unitType) {
                                                case 'piece': $unitLabel = 'pcs'; break;
                                                case 'set': $unitLabel = 'set'; break;
                                                case 'kg': $unitLabel = 'kg'; break;
                                                case 'sqft': $unitLabel = 'sqft'; break;
                                            }
                                            
                                            $badgeColor = '';
                                            switch($product->qc_mode ?? 'count') {
                                                case 'count': $badgeColor = 'bg-danger'; break;
                                                case 'completeness': $badgeColor = 'bg-primary'; break;
                                                case 'integrity': $badgeColor = 'bg-info'; break;
                                                case 'identity': $badgeColor = 'bg-success'; break;
                                            }
                                        @endphp
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td class="text-end">
                                            <span class="badge {{ $badgeColor }}">{{ $item->quantity }} {{ $unitLabel }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($order->special_instructions)
                        <hr>
                        <div class="alert alert-danger mb-0">
                            <h6 class="alert-heading">SPECIAL INSTRUCTIONS!</h6>
                            <p class="mb-0 small">{{ $order->special_instructions }}</p>
                        </div>
                        @endif
                        
                        <hr>
                        <div class="text-center">
                            <h6 class="text-muted mb-2">Total Amount</h6>
                            <h3 class="mb-0">MYR {{ number_format($order->total, 2) }}</h3>
                            {!! $order->getPaymentStatusBadge() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page Layout Fix */
html {
    scroll-behavior: smooth;
}

.page-wrapper .content {
    padding-bottom: 50px;
}

.stepper-card {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 80px;
    z-index: 99;
    background: white;
}

/* Order Summary Sticky Fix */
.order-summary-sticky {
    position: sticky;
    top: 180px; /* Below stepper */
    max-height: calc(100vh - 200px);
    z-index: 10;
    align-self: flex-start;
}

.order-summary-sticky .card {
    margin-bottom: 0;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.order-summary-body {
    max-height: calc(100vh - 280px);
    overflow-y: auto;
    overflow-x: hidden;
    padding-right: 5px;
}

/* Stepper Styles */
.stepper-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.stepper-wrapper::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background: #e0e0e0;
    z-index: 0;
}

.stepper-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
    cursor: pointer;
}

.stepper-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e0e0e0;
    color: #666;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    transition: all 0.3s;
    border: 3px solid #fff;
}

.stepper-label {
    margin-top: 8px;
    font-size: 12px;
    color: #666;
    font-weight: 500;
}

.stepper-item.active .stepper-circle {
    background: #0d6efd;
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.3);
}

.stepper-item.completed .stepper-circle {
    background: #198754;
    color: white;
}

.stepper-item.active .stepper-label {
    color: #0d6efd;
    font-weight: bold;
}

.stepper-item.completed .stepper-label {
    color: #198754;
}

/* QC Step Cards */
.qc-step .card {
    border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    margin-bottom: 0;
    border: none;
}

/* Section Headers - Compact */
.section-header-compact {
    padding: 10px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    margin-bottom: 12px;
    font-size: 14px;
    font-weight: 600;
}

.section-header-compact > div {
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-header-compact i {
    width: 18px;
    height: 18px;
}

.section-header-compact small {
    margin-left: auto;
    opacity: 0.9;
    font-weight: 400;
    font-size: 12px;
}

/* QC Items Grid (Compact) */
.qc-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 10px;
}

.qc-item-compact {
    background: #f8f9fa;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    transition: all 0.2s;
}

.qc-item-compact:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}

.qc-item-label {
    font-size: 13px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}

.qc-item-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}

.qc-expected {
    font-size: 12px;
    color: #718096;
    white-space: nowrap;
}

.qc-item-compact input {
    width: 70px;
    border: 2px solid #cbd5e0;
    border-radius: 6px;
    font-weight: 600;
    font-size: 14px;
    padding: 6px 8px;
    text-align: center;
}

.qc-item-compact input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

.qc-item-compact [class*="count-status"] {
    margin-top: 6px;
    min-height: 20px;
    font-size: 11px;
}

/* QC Sets Compact */
.qc-sets-compact {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 10px;
}

.qc-set-compact {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    transition: all 0.2s;
}

.qc-set-compact:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
}

.qc-set-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #2d3748;
}

.qc-set-qty-badge {
    font-size: 11px;
    font-weight: 600;
    color: #667eea;
    background: #edf2f7;
    padding: 2px 8px;
    border-radius: 12px;
}

.qc-set-checks {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* QC Batch Compact */
.qc-batch-compact {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 10px;
}

.qc-batch-item {
    background: white;
    border: 1px solid #bee3f8;
    border-radius: 8px;
    padding: 12px;
}

.qc-batch-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #2c5282;
}

.qc-batch-qty-badge {
    font-size: 11px;
    font-weight: 600;
    color: #3182ce;
    background: #ebf8ff;
    padding: 2px 8px;
    border-radius: 12px;
}

.qc-batch-checks {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* QC Identity Compact */
.qc-identity-compact {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 10px;
}

.qc-identity-item {
    background: white;
    border: 1px solid #c6f6d5;
    border-radius: 8px;
    padding: 12px;
}

.qc-identity-title {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    font-size: 13px;
    font-weight: 600;
    color: #22543d;
}

.qc-identity-qty-badge {
    font-size: 11px;
    font-weight: 600;
    color: #38a169;
    background: #f0fff4;
    padding: 2px 8px;
    border-radius: 12px;
}

/* Custom Checkbox - Compact */
.qc-checkbox-compact {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
    padding: 6px 8px;
    background: #f7fafc;
    border-radius: 6px;
    transition: all 0.2s;
    position: relative;
    font-size: 12px;
}

.qc-checkbox-compact:hover {
    background: #edf2f7;
}

.qc-checkbox-compact input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.qc-checkbox-compact .checkmark-small {
    width: 18px;
    height: 18px;
    border: 2px solid #cbd5e0;
    border-radius: 4px;
    background: white;
    margin-right: 8px;
    flex-shrink: 0;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qc-checkbox-compact input:checked ~ .checkmark-small {
    background: #667eea;
    border-color: #667eea;
}

.qc-checkbox-compact input:checked ~ .checkmark-small:after {
    content: '✓';
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.qc-checkbox-compact span:not(.checkmark-small) {
    color: #2d3748;
    font-weight: 500;
}

.qc-checkbox-compact input:checked ~ span:not(.checkmark-small) {
    color: #667eea;
    font-weight: 600;
}

/* Custom Checkbox - Large (for final check) */
.qc-checkbox {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;
    padding: 12px 16px;
    background: #f7fafc;
    border-radius: 8px;
    transition: all 0.2s;
    position: relative;
}

.qc-checkbox:hover {
    background: #edf2f7;
}

.qc-checkbox input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.qc-checkbox .checkmark {
    width: 24px;
    height: 24px;
    border: 2px solid #cbd5e0;
    border-radius: 6px;
    background: white;
    margin-right: 12px;
    flex-shrink: 0;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qc-checkbox input:checked ~ .checkmark {
    background: #667eea;
    border-color: #667eea;
}

.qc-checkbox input:checked ~ .checkmark:after {
    content: '✓';
    color: white;
    font-size: 16px;
    font-weight: bold;
}

.qc-checkbox .label-text {
    color: #2d3748;
    font-size: 14px;
    font-weight: 500;
}

.qc-checkbox input:checked ~ .label-text {
    color: #667eea;
    font-weight: 600;
}

/* Status Badges */
.qc-status-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.qc-status-badge svg {
    width: 14px;
    height: 14px;
}

.qc-status-badge.success {
    background: #c6f6d5;
    color: #22543d;
}

.qc-status-badge.error {
    background: #fed7d7;
    color: #742a2a;
}

.qc-status-badge.warning {
    background: #fef5e7;
    color: #975a16;
}

/* QC Section Spacing */
.qc-section {
    margin-bottom: 16px !important;
}

/* Final Check */
.qc-final-check {
    margin: 20px 0 16px;
    padding: 14px 16px;
    background: linear-gradient(135deg, #667eea15, #764ba215);
    border: 2px dashed #667eea;
    border-radius: 8px;
}

.qc-checkbox.large {
    padding: 16px 20px;
    background: white;
}

.qc-checkbox.large .checkmark {
    width: 28px;
    height: 28px;
}

.qc-checkbox.large .label-text {
    font-size: 16px;
    font-weight: 600;
}

/* Notes Section */
.qc-notes-section {
    margin-top: 12px;
}

.qc-notes-section .form-control {
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    transition: all 0.2s;
}

.qc-notes-section .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

/* Standardized Button System */
.qc-step-buttons {
    display: flex;
    justify-content: space-between;
    gap: 12px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 2px solid #e2e8f0;
}

/* Base Button Style - ALL buttons use this */
.qc-btn,
.qc-pass-all-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    min-height: 44px;
}

.qc-btn svg,
.qc-pass-all-btn svg {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
}

/* Primary Button (Next, Submit) */
.qc-btn-next,
.qc-btn-submit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.qc-btn-next:hover,
.qc-btn-submit:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.qc-btn-submit:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

/* Secondary Button (Previous) */
.qc-btn-prev {
    background: white;
    color: #4a5568;
    border: 2px solid #cbd5e0;
}

.qc-btn-prev:hover {
    background: #f7fafc;
    border-color: #a0aec0;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Danger Button (Fail) */
.qc-btn-fail {
    background: white;
    color: #e53e3e;
    border: 2px solid #fc8181;
}

.qc-btn-fail:hover {
    background: #fff5f5;
    border-color: #e53e3e;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(229, 62, 62, 0.2);
}

/* Success Button (Pass All) */
.qc-pass-all-btn {
    background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
    color: white;
    padding: 10px 20px;
}

.qc-pass-all-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(72, 187, 120, 0.4);
}

/* Form Styles */
.form-check-lg .form-check-input {
    width: 1.5em;
    height: 1.5em;
    margin-top: 0.15em;
}

.rating-select option[value="2"],
.rating-select option[value="1"] {
    color: red;
    font-weight: bold;
}

/* Ensure proper spacing */
.row.gx-4 {
    --bs-gutter-x: 1.5rem;
}

/* Animation */
.qc-step {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateX(20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Responsive Adjustments */
@media (max-width: 991px) {
    .order-summary-sticky {
        position: relative !important;
        top: 0 !important;
        max-height: none !important;
        overflow-y: visible !important;
        margin-top: 20px;
    }
    
    .order-summary-body {
        max-height: none !important;
        overflow-y: visible !important;
    }
}

/* Scrollbar Styling */
.order-summary-body::-webkit-scrollbar {
    width: 6px;
}

.order-summary-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.order-summary-body::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

.order-summary-body::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection

@push('scripts')
<script>
    let currentStep = 1;
    const totalSteps = 8;
    const expectedQty = {{ $order->items->sum('quantity') }};
    
    // Update current datetime
    function updateDateTime() {
        const now = new Date();
        document.getElementById('current-datetime').textContent = now.toLocaleString('en-MY');
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Piece-based count check
    function updateCountStatus(input) {
        const counted = parseInt(input.val()) || 0;
        const expected = parseInt(input.data('expected'));
        const itemId = input.data('item-id');
        const statusCell = $('.count-status-' + itemId);
        
        if (counted === 0) {
            statusCell.html('');
            input.css('border-color', '#cbd5e0');
        } else if (counted === expected) {
            statusCell.html('<div class="qc-status-badge success"><i data-feather="check"></i> OK</div>');
            input.css('border-color', '#48bb78');
            feather.replace();
        } else if (counted < expected) {
            statusCell.html('<div class="qc-status-badge error"><i data-feather="x"></i> -' + (expected - counted) + '</div>');
            input.css('border-color', '#f56565');
            feather.replace();
        } else {
            statusCell.html('<div class="qc-status-badge error"><i data-feather="x"></i> +' + (counted - expected) + '</div>');
            input.css('border-color', '#f56565');
            feather.replace();
        }
    }
    
    $('.piece-count').on('input', function() {
        updateCountStatus($(this));
    });
    
    // Initialize status on page load
    $('.piece-count').each(function() {
        updateCountStatus($(this));
    });
    
    // Damage radio buttons
    $('input[name="damage_status"]').on('change', function() {
        if ($(this).val() === 'damage_found') {
            $('#damage-details').slideDown();
            $('#damage_notes, #damage_photos').prop('required', true);
        } else {
            $('#damage-details').slideUp();
            $('#damage_notes, #damage_photos').prop('required', false);
            $('#stain_check, #damage_check').prop('checked', false);
            $('#stain_notes, #damage_notes').val('');
            $('#damage_photos').val('');
        }
    });
    
    // Step Navigation
    function showStep(step) {
        $('.qc-step').hide();
        $(`.qc-step[data-step="${step}"]`).show();
        
        // Update stepper UI
        $('.stepper-item').removeClass('active');
        $(`.stepper-item[data-step="${step}"]`).addClass('active');
        
        // Mark previous steps as completed
        for (let i = 1; i < step; i++) {
            $(`.stepper-item[data-step="${i}"]`).addClass('completed');
        }
        
        currentStep = step;
        
        // Scroll to stepper (not behind header)
        const offset = $('.stepper-card').offset().top - 100;
        $('html, body').animate({ scrollTop: offset }, 300);
        
        // Refresh feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }
    
    function validateStep(step) {
        let isValid = true;
        let errors = [];
        
        // Check all required fields for this step
        $(`.step-required[data-step="${step}"]`).each(function() {
            if ($(this).is(':checkbox') || $(this).is(':radio')) {
                const name = $(this).attr('name');
                if (!$(`input[name="${name}"]:checked`).length) {
                    isValid = false;
                    errors.push($(this).closest('.form-check').find('label').text());
                }
            } else if ($(this).is('select')) {
                if (!$(this).val()) {
                    isValid = false;
                    errors.push($(this).closest('.row').find('label:first').text());
                }
            } else {
                if (!$(this).val()) {
                    isValid = false;
                }
            }
        });
        
        // Step-specific validation
        if (step === 1) {
            // Validate piece-based items (count must match)
            let countErrors = [];
            $('.piece-count').each(function() {
                const counted = parseInt($(this).val()) || 0;
                const expected = parseInt($(this).data('expected'));
                if (counted !== expected) {
                    const row = $(this).closest('tr');
                    const itemName = row.find('td:first').text();
                    countErrors.push('Missing piece item: ' + itemName + ' (expected ' + expected + ', counted ' + counted + ')');
                    isValid = false;
                }
            });
            
            // Validate set-based items (all components must be checked)
            $('.set-component').each(function() {
                const card = $(this).closest('.card');
                const setName = card.find('h6').text();
                const allComponents = card.find('.set-component');
                const checkedComponents = card.find('.set-component:checked');
                
                if (allComponents.length > 0 && checkedComponents.length !== allComponents.length) {
                    countErrors.push('Incomplete set: ' + setName);
                    isValid = false;
                }
            });
            
            if (countErrors.length > 0) {
                errors = errors.concat(countErrors);
            }
        }
        
        // Check ratings (must be >= 3)
        $(`.rating-select[data-step="${step}"]`).each(function() {
            const rating = parseInt($(this).val());
            if (rating && rating < 3) {
                isValid = false;
                errors.push($(this).attr('name').replace('_rating', '') + ' rating below standard');
            }
        });
        
        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Cannot Proceed',
                html: '<p>Please fix the following:</p><ul class="text-start">' + 
                      errors.map(e => '<li>' + e + '</li>').join('') + '</ul>',
                confirmButtonText: 'OK'
            });
        }
        
        return isValid;
    }
    
    function nextStep() {
        if (!validateStep(currentStep)) {
            return;
        }
        
        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
            
            // Enable submit button on final step if all requirements met
            if (currentStep === totalSteps) {
                checkFinalApproval();
            }
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            showStep(currentStep - 1);
        }
    }
    
    function checkFinalApproval() {
        const finalChecked = $('#final_approval').is(':checked');
        $('#submit-btn').prop('disabled', !finalChecked);
    }
    
    $('#final_approval').on('change', checkFinalApproval);
    
    // Stepper click navigation
    $('.stepper-item').on('click', function() {
        const targetStep = parseInt($(this).data('step'));
        if (targetStep < currentStep) {
            showStep(targetStep);
        }
    });
    
    // Pass All Buttons
    function passAllWorkflow() {
        $('#status_wash_check, #status_dry_check').prop('checked', true);
        Swal.fire({ icon: 'success', title: 'Workflow Passed!', timer: 1000, showConfirmButton: false });
    }
    
    function passAllClean() {
        $('#cleanliness_check, #odour_check').prop('checked', true);
        $('select[name="cleanliness_rating"]').val('5');
        $('select[name="odour_rating"]').val('5');
        Swal.fire({ icon: 'success', title: 'Cleanliness Passed!', timer: 1000, showConfirmButton: false });
    }
    
    function passAllDry() {
        $('#dryness_check').prop('checked', true);
        $('select[name="dryness_rating"]').val('5');
        Swal.fire({ icon: 'success', title: 'Dryness Passed!', timer: 1000, showConfirmButton: false });
    }
    
    function passAllFinish() {
        $('#folding_check').prop('checked', true);
        $('select[name="folding_rating"]').val('5');
        Swal.fire({ icon: 'success', title: 'Finishing Passed!', timer: 1000, showConfirmButton: false });
    }
    
    function passAllPackaging() {
        $('#packaging_check, #label_correct, #qr_tag_intact').prop('checked', true);
        Swal.fire({ icon: 'success', title: 'Packaging Passed!', timer: 1000, showConfirmButton: false });
    }
    
    // Fail and Return
    function failAndReturn() {
        Swal.fire({
            title: 'Fail & Return to Process?',
            text: 'This will mark the order as failed QC and return it to processing.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Fail QC',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("laundry.qc.index") }}';
            }
        });
    }
    
    // Form submission
    $('#qc-form').on('submit', function(e) {
        e.preventDefault();
        
        // Final confirmation modal
        Swal.fire({
            title: 'Final Confirmation',
            html: `
                <div class="text-start">
                    <p><strong>You are about to approve:</strong></p>
                    <ul>
                        <li>Order: <strong>{{ $order->order_number }}</strong></li>
                        <li>Customer: <strong>{{ $order->customer_name }}</strong></li>
                        <li>Items: <strong>{{ $order->items->sum('quantity') }}</strong></li>
                    </ul>
                    <hr>
                    <p><strong>QC Officer:</strong> {{ auth()->user()->name }}</p>
                    <p><strong>Timestamp:</strong> ${new Date().toLocaleString('en-MY')}</p>
                    <hr>
                    <p class="text-danger mb-0"><strong>This action creates an immutable log and cannot be undone.</strong></p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm & Submit',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#198754',
            width: '600px'
        }).then((result) => {
            if (result.isConfirmed) {
                submitQC();
            }
        });
    });
    
    function submitQC() {
        const formData = new FormData(document.getElementById('qc-form'));
        
        // Show loading
        Swal.fire({
            title: 'Processing QC...',
            text: 'Creating immutable quality check log...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        $.ajax({
            url: '{{ route("laundry.qc.store", $order->id) }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    if (response.passed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'QC PASSED!',
                            html: '<p>' + response.message + '</p><p class="text-success"><strong>Order marked as COMPLETED</strong></p>',
                            confirmButtonText: 'View Order',
                            confirmButtonColor: '#198754'
                        }).then(() => {
                            window.location.href = '{{ route("laundry.show", $order->id) }}';
                        });
                    } else {
                        let failureList = '';
                        if (response.failure_reasons) {
                            failureList = '<ul class="text-start">';
                            response.failure_reasons.forEach(reason => {
                                failureList += '<li>' + reason + '</li>';
                            });
                            failureList += '</ul>';
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'QC FAILED!',
                            html: '<p><strong>Order returned to PROCESSING</strong></p>' + failureList,
                            confirmButtonText: 'View Order',
                            confirmButtonColor: '#dc3545'
                        }).then(() => {
                            window.location.href = '{{ route("laundry.show", $order->id) }}';
                        });
                    }
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: xhr.responseJSON?.message || 'Failed to submit QC',
                    confirmButtonText: 'OK'
                });
            }
        });
    }
    
    // Rating change warning
    $('.rating-select').on('change', function() {
        const rating = parseInt($(this).val());
        if (rating < 3) {
            $(this).addClass('border-danger');
            Swal.fire({
                icon: 'warning',
                title: 'Low Rating!',
                text: 'Rating below 3 will cause QC to FAIL',
                showConfirmButton: true
            });
        } else {
            $(this).removeClass('border-danger');
        }
    });
    
    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT') {
            return;
        }
        
        // Enter = Next
        if (e.key === 'Enter') {
            e.preventDefault();
            if (currentStep < totalSteps) {
                nextStep();
            }
        }
        
        // Backspace = Previous
        if (e.key === 'Backspace') {
            e.preventDefault();
            prevStep();
        }
        
        // Numbers 1-8 = Go to step
        if (e.key >= '1' && e.key <= '8') {
            const targetStep = parseInt(e.key);
            if (targetStep <= currentStep) {
                showStep(targetStep);
            }
        }
    });
    
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
        showStep(1);
    });
</script>
@endpush
