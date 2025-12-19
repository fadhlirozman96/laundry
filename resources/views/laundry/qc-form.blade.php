<?php $page = 'laundry-qc'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quality Check: {{ $order->order_number }}</h4>
                <h6>Complete all inspection items before submission</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.qc.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back
                </a>
            </div>
        </div>

        <div class="row">
            <!-- QC Form -->
            <div class="col-lg-8">
                <form id="qc-form">
                    @csrf
                    
                    <!-- Cleanliness Check -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i data-feather="droplet" class="me-2"></i>1. Cleanliness Inspection
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input" type="checkbox" name="cleanliness_check" id="cleanliness_check" value="1" required>
                                        <label class="form-check-label" for="cleanliness_check">
                                            <strong>Cleanliness Verified</strong>
                                            <br><small class="text-muted">All items are clean without visible dirt or stains</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rating</label>
                                    <select class="form-control" name="cleanliness_rating" required>
                                        <option value="">Select</option>
                                        <option value="5">5 - Excellent</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Acceptable</option>
                                        <option value="2">2 - Below Standard</option>
                                        <option value="1">1 - Poor</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" class="form-control" name="cleanliness_notes" placeholder="Optional notes">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Odour Check -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i data-feather="wind" class="me-2"></i>2. Odour Inspection
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input" type="checkbox" name="odour_check" id="odour_check" value="1" required>
                                        <label class="form-check-label" for="odour_check">
                                            <strong>Odour Verified</strong>
                                            <br><small class="text-muted">Items smell fresh without bad odours</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rating</label>
                                    <select class="form-control" name="odour_rating" required>
                                        <option value="">Select</option>
                                        <option value="5">5 - Fresh</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Neutral</option>
                                        <option value="2">2 - Slight Odour</option>
                                        <option value="1">1 - Bad Odour</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" class="form-control" name="odour_notes" placeholder="Optional notes">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Check -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i data-feather="hash" class="me-2"></i>3. Quantity Verification
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input" type="checkbox" name="quantity_check" id="quantity_check" value="1" required>
                                        <label class="form-check-label" for="quantity_check">
                                            <strong>Quantity Verified</strong>
                                            <br><small class="text-muted">All {{ $order->total_garments }} items are accounted for</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Items Counted</label>
                                    <input type="number" class="form-control" name="items_counted" min="0" required placeholder="Count items">
                                    <small class="text-muted">Expected: {{ $order->total_garments }}</small>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" class="form-control" name="quantity_notes" placeholder="e.g. Missing 1 sock">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Folding Check -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i data-feather="layers" class="me-2"></i>4. Folding Quality
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg">
                                        <input class="form-check-input" type="checkbox" name="folding_check" id="folding_check" value="1" required>
                                        <label class="form-check-label" for="folding_check">
                                            <strong>Folding Verified</strong>
                                            <br><small class="text-muted">Items are properly folded and presentable</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Rating</label>
                                    <select class="form-control" name="folding_rating" required>
                                        <option value="">Select</option>
                                        <option value="5">5 - Perfect</option>
                                        <option value="4">4 - Good</option>
                                        <option value="3">3 - Acceptable</option>
                                        <option value="2">2 - Needs Improvement</option>
                                        <option value="1">1 - Poor</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Notes</label>
                                    <input type="text" class="form-control" name="folding_notes" placeholder="Optional notes">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Checks -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i data-feather="alert-triangle" class="me-2"></i>5. Additional Checks (Optional)
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="stain_check" id="stain_check" value="1">
                                        <label class="form-check-label" for="stain_check">
                                            <strong>Stain Issues Found</strong>
                                        </label>
                                    </div>
                                    <textarea class="form-control mt-2" name="stain_notes" rows="2" placeholder="Describe stain issues..."></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="damage_check" id="damage_check" value="1">
                                        <label class="form-check-label" for="damage_check">
                                            <strong>Damage Found</strong>
                                        </label>
                                    </div>
                                    <textarea class="form-control mt-2" name="damage_notes" rows="2" placeholder="Describe damage..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overall Notes -->
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">Overall Notes</h5>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="overall_notes" rows="3" placeholder="Any additional notes about this order..."></textarea>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="button" class="btn btn-secondary btn-lg me-2" onclick="window.history.back()">Cancel</button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i data-feather="check" class="me-2"></i>Submit QC Result
                        </button>
                    </div>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card sticky-top" style="top: 80px;">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Order:</strong> {{ $order->order_number }}</p>
                        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Items:</strong> {{ $order->total_garments }} pcs</p>
                        <hr>
                        <h6>Items List:</h6>
                        <ul class="list-unstyled">
                            @foreach($order->items as $item)
                            <li class="mb-2">
                                <code>{{ $item->garment_code }}</code>
                                {{ $item->garment_name }} 
                                <span class="badge bg-secondary">x{{ $item->quantity }}</span>
                                @if($item->color)
                                    <span class="badge" style="background: {{ strtolower($item->color) }}; border: 1px solid #ccc;">&nbsp;</span>
                                @endif
                            </li>
                            @endforeach
                        </ul>
                        @if($order->special_instructions)
                        <hr>
                        <div class="alert alert-info mb-0">
                            <strong>Special Instructions:</strong><br>
                            {{ $order->special_instructions }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-check-lg .form-check-input {
        width: 1.5em;
        height: 1.5em;
        margin-top: 0.15em;
    }
</style>
@endsection

@push('scripts')
<script>
    $('#qc-form').on('submit', function(e) {
        e.preventDefault();
        
        // Convert checkbox values
        var formData = $(this).serializeArray();
        
        // Add checkbox values properly
        formData = formData.map(function(item) {
            if (['cleanliness_check', 'odour_check', 'quantity_check', 'folding_check', 'stain_check', 'damage_check'].includes(item.name)) {
                return { name: item.name, value: 1 };
            }
            return item;
        });
        
        // Add unchecked checkboxes as 0
        ['cleanliness_check', 'odour_check', 'quantity_check', 'folding_check', 'stain_check', 'damage_check'].forEach(function(name) {
            if (!formData.find(item => item.name === name)) {
                formData.push({ name: name, value: 0 });
            }
        });
        
        $.ajax({
            url: '{{ route("laundry.qc.store", $order->id) }}',
            type: 'POST',
            data: $.param(formData),
            success: function(response) {
                if (response.success) {
                    if (response.passed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'QC PASSED!',
                            text: response.message,
                            confirmButtonText: 'View Order'
                        }).then(() => {
                            window.location.href = '{{ route("laundry.show", $order->id) }}';
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'QC FAILED',
                            text: response.message,
                            confirmButtonText: 'View Order'
                        }).then(() => {
                            window.location.href = '{{ route("laundry.show", $order->id) }}';
                        });
                    }
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to submit QC', 'error');
            }
        });
    });

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush

