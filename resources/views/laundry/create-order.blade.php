<?php $page = 'laundry-create'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Create New Laundry Order</h4>
                <h6>Enter order details and items</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.orders') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back to Orders
                </a>
            </div>
        </div>

        <form id="create-order-form">
            @csrf
            <div class="row">
                <!-- Customer Info -->
                <div class="col-lg-3">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Select Existing Customer</label>
                                <select class="form-control select2" id="customer_select" name="customer_id">
                                    <option value="">-- New Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" 
                                            data-name="{{ $customer->name }}"
                                            data-phone="{{ $customer->phone }}"
                                            data-email="{{ $customer->email }}">
                                            {{ $customer->name }} - {{ $customer->phone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="customer_name" id="customer_name" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="customer_phone" id="customer_phone">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="customer_email" id="customer_email">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Expected Completion</label>
                                <input type="datetime-local" class="form-control" name="expected_completion">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Special Instructions</label>
                                <textarea class="form-control" name="special_instructions" rows="3" placeholder="Any special handling instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Items -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Order Items</h5>
                            <button type="button" class="btn btn-sm btn-primary" onclick="addItem()">
                                <i data-feather="plus" class="me-1"></i>Add Item
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table" id="items-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 30%">Service</th>
                                            <th style="width: 25%">Description</th>
                                            <th style="width: 15%">Qty</th>
                                            <th style="width: 12%">Price (MYR)</th>
                                            <th style="width: 13%">Subtotal</th>
                                            <th style="width: 5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body">
                                        <!-- Items will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <!-- Totals will be shown in the separate card below -->
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2" placeholder="Order notes..."></textarea>
                            </div>

                            <!-- Order Calculation Card -->
                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- Order Tax -->
                                        <div class="col-md-4">
                                            <label class="form-label">Order Tax (%)</label>
                                            <input type="number" class="form-control" name="order_tax_percent" id="order_tax_percent" value="6" min="0" max="100" step="0.01" onchange="calculateTotals()">
                                        </div>
                                        <!-- Shipping -->
                                        <div class="col-md-4">
                                            <label class="form-label">Shipping</label>
                                            <input type="number" class="form-control" name="shipping" id="shipping" value="0" min="0" step="0.01" onchange="calculateTotals()">
                                        </div>
                                        <!-- Discount -->
                                        <div class="col-md-4">
                                            <label class="form-label">Discount</label>
                                            <input type="number" class="form-control" name="discount" id="discount" value="0" min="0" step="0.01" onchange="calculateTotals()">
                                        </div>
                                    </div>

                                    <!-- Coupon Code -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <label class="form-label">Coupon Code</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="coupon_code" id="coupon_code" placeholder="ENTER COUPON CODE">
                                                <button type="button" class="btn btn-primary" onclick="applyCoupon()">Apply</button>
                                            </div>
                                            <input type="hidden" name="coupon_discount" id="coupon_discount" value="0">
                                            <small class="text-success d-none" id="coupon_message"></small>
                                        </div>
                                    </div>

                                    <!-- Totals Summary -->
                                    <div class="mt-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Sub Total</span>
                                            <strong><span id="subtotal">MYR 0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tax (<span id="tax_percent_display">6</span>%)</span>
                                            <strong><span id="tax">MYR 0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Shipping</span>
                                            <strong><span id="shipping_display">MYR 0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 text-danger">
                                            <span>Discount</span>
                                            <strong><span id="discount_display">MYR 0.00</span></strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2 text-success d-none" id="coupon_discount_row">
                                            <span>Coupon Discount</span>
                                            <strong><span id="coupon_discount_display">MYR 0.00</span></strong>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between">
                                            <h5>Total</h5>
                                            <h5 class="text-primary"><span id="total">MYR 0.00</span></h5>
                                        </div>
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="mt-4">
                                        <label class="form-label">Payment Method</label>
                                        <div class="row g-2">
                                            <div class="col-4">
                                                <input type="radio" class="btn-check" name="payment_method" id="payment_cash" value="cash" checked>
                                                <label class="btn btn-outline-primary w-100" for="payment_cash">
                                                    <i data-feather="dollar-sign" class="me-1"></i> Cash
                                                </label>
                                            </div>
                                            <div class="col-4">
                                                <input type="radio" class="btn-check" name="payment_method" id="payment_debit" value="debit_card">
                                                <label class="btn btn-outline-primary w-100" for="payment_debit">
                                                    <i data-feather="credit-card" class="me-1"></i> Debit Card
                                                </label>
                                            </div>
                                            <div class="col-4">
                                                <input type="radio" class="btn-check" name="payment_method" id="payment_qr" value="qr">
                                                <label class="btn btn-outline-primary w-100" for="payment_qr">
                                                    <i data-feather="smartphone" class="me-1"></i> QR
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Grand Total Display -->
                                    <div class="mt-4 p-4 text-center rounded" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <h3 class="mb-0 text-white fw-bold">Grand Total: <span id="grand_total">MYR 0.00</span></h3>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="row g-2 mt-4">
                                        <div class="col-4">
                                            <button type="button" class="btn btn-info w-100 py-3" onclick="holdOrder()">
                                                <i data-feather="pause" class="me-1"></i> Hold
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <button type="button" class="btn btn-danger w-100 py-3" onclick="voidOrder()">
                                                <i data-feather="x-circle" class="me-1"></i> Void
                                            </button>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-success w-100 py-3">
                                                <i data-feather="check-circle" class="me-1"></i> Payment
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Services Data -->
<script>
    var services = @json($services);
    console.log('Services loaded:', services ? services.length : 0);
    console.log('First 3 services:', services ? services.slice(0, 3) : []);
    
    // Check if services are loaded
    if (!services || services.length === 0) {
        console.error('No services available! Please ensure services are seeded in the database.');
        Swal.fire({
            icon: 'warning',
            title: 'No Services Found',
            text: 'No services are available for this store. Please add services first.',
            confirmButtonText: 'Go to Services'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ url("product-list") }}';
            }
        });
    }
</script>

<style>
    .item-unit-badge {
        min-width: 50px;
        background-color: #e9ecef;
        font-weight: 600;
        color: #495057;
    }
    .select2-container {
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--single {
        height: 31px;
        padding: 2px 8px;
        font-size: 0.875rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 27px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 29px;
    }
    
    /* Grand Total Styling */
    #grand_total {
        font-size: 1.8rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }
</style>
@endsection

@push('scripts')
<script>
    var itemIndex = 0;

    function addItem() {
        // Check if services are available
        if (!services || services.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'No Services Available',
                text: 'Please add services to your store first before creating an order.',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        var row = `
            <tr class="item-row" data-index="${itemIndex}">
                <td>
                    <select class="form-control form-control-sm service-select" id="service_select_${itemIndex}" name="items[${itemIndex}][service_id]" onchange="setServicePrice(this, ${itemIndex})" required>
                        <option value="">Select Service</option>
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][service_name]" required placeholder="e.g. Normal Wash" readonly>
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <input type="number" class="form-control item-qty" name="items[${itemIndex}][quantity]" value="1" min="1" step="1" onchange="calculateItemSubtotal(${itemIndex})">
                        <span class="input-group-text item-unit-badge d-none" id="unit_${itemIndex}"></span>
                    </div>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-price" name="items[${itemIndex}][price]" value="0" min="0" step="0.01" onchange="calculateItemSubtotal(${itemIndex})" readonly>
                </td>
                <td>
                    <span class="item-subtotal">MYR 0.00</span>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${itemIndex})">
                        <i data-feather="trash-2"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#items-body').append(row);
        
        // Build options for select2
        var serviceOptions = [{
            id: '',
            text: 'Select Service'
        }];
        
        services.forEach(function(service) {
            var categoryName = service.category ? service.category.name : '';
            var unitName = service.unit ? service.unit.short_name : '';
            var displayText = service.name;
            if (categoryName) {
                displayText += ' (' + categoryName + ')';
            }
            if (unitName) {
                displayText += ' [' + unitName + ']';
            }
            
            serviceOptions.push({
                id: service.id,
                text: displayText,
                price: service.price,
                unit: unitName,
                serviceName: service.name
            });
        });
        
        // Initialize select2 with data
        var selectElement = $('#service_select_' + itemIndex);
        selectElement.select2({
            data: serviceOptions,
            placeholder: 'Search service...',
            width: '100%',
            templateResult: formatService,
            templateSelection: formatServiceSelection
        });
        
        // Store service data on the element for later retrieval
        selectElement.data('services', serviceOptions);
        
        itemIndex++;
        feather.replace();
    }
    
    function formatService(service) {
        if (!service.id) {
            return service.text;
        }
        return $('<span>' + service.text + '</span>');
    }
    
    function formatServiceSelection(service) {
        return service.text || service.text;
    }

    function setServicePrice(select, index) {
        var selectedValue = $(select).val();
        var row = $('[data-index="' + index + '"]');
        
        if (!selectedValue) {
            // Clear everything if no service selected
            row.find('.item-price').val(0);
            row.find('input[name="items[' + index + '][service_name]"]').val('');
            $('#unit_' + index).addClass('d-none').text('');
            calculateItemSubtotal(index);
            return;
        }
        
        // Get all services data stored on the select element
        var servicesData = $(select).data('services');
        var selectedService = null;
        
        // Find the selected service
        for (var i = 0; i < servicesData.length; i++) {
            if (servicesData[i].id == selectedValue) {
                selectedService = servicesData[i];
                break;
            }
        }
        
        if (!selectedService) {
            console.error('Service not found:', selectedValue);
            return;
        }
        
        var price = selectedService.price || 0;
        var unit = selectedService.unit || 'Pc';
        var serviceName = selectedService.serviceName || selectedService.text;
        
        // Update price and service name
        row.find('.item-price').val(price);
        row.find('input[name="items[' + index + '][service_name]"]').val(serviceName);
        
        // Show and update unit badge
        var unitBadge = $('#unit_' + index);
        unitBadge.removeClass('d-none').text(unit);
        
        // Update quantity input attributes based on unit
        var qtyInput = row.find('.item-qty');
        var unitLower = unit.toLowerCase();
        
        // Units that allow decimals: Kg, Sq.ft
        if (unitLower === 'kg' || unitLower === 'sq.ft') {
            qtyInput.attr('step', '0.01');
            qtyInput.attr('min', '0.01');
            qtyInput.val('1.00');
        } else {
            // Pc, Set, Pair - only whole numbers
            qtyInput.attr('step', '1');
            qtyInput.attr('min', '1');
            // Round to nearest integer if it has decimals
            var currentVal = parseFloat(qtyInput.val());
            if (currentVal % 1 !== 0) {
                qtyInput.val(Math.round(currentVal));
            } else {
                qtyInput.val(Math.round(currentVal));
            }
        }
        
        calculateItemSubtotal(index);
    }

    function calculateItemSubtotal(index) {
        var row = $('[data-index="' + index + '"]');
        var qtyInput = row.find('.item-qty');
        var qty = parseFloat(qtyInput.val()) || 0;
        var price = parseFloat(row.find('.item-price').val()) || 0;
        
        // Validate quantity based on step attribute
        var step = parseFloat(qtyInput.attr('step'));
        if (step === 1) {
            // Only whole numbers allowed
            qty = Math.round(qty);
            qtyInput.val(qty);
        }
        
        var subtotal = qty * price;
        row.find('.item-subtotal').text('MYR ' + subtotal.toFixed(2));
        calculateTotals();
    }

    function removeItem(index) {
        $('[data-index="' + index + '"]').remove();
        calculateTotals();
    }

    function calculateTotals() {
        var subtotal = 0;
        $('.item-row').each(function() {
            var qty = parseFloat($(this).find('.item-qty').val()) || 0;
            var price = parseFloat($(this).find('.item-price').val()) || 0;
            subtotal += qty * price;
        });

        var taxPercent = parseFloat($('#order_tax_percent').val()) || 0;
        var tax = subtotal * (taxPercent / 100);
        var shipping = parseFloat($('#shipping').val()) || 0;
        var discount = parseFloat($('#discount').val()) || 0;
        var couponDiscount = parseFloat($('#coupon_discount').val()) || 0;
        
        var total = subtotal + tax + shipping - discount - couponDiscount;
        
        // Ensure total is not negative
        if (total < 0) total = 0;

        // Update displays
        $('#subtotal').text('MYR ' + subtotal.toFixed(2));
        $('#tax').text('MYR ' + tax.toFixed(2));
        $('#tax_percent_display').text(taxPercent.toFixed(2));
        $('#shipping_display').text('MYR ' + shipping.toFixed(2));
        $('#discount_display').text('MYR ' + discount.toFixed(2));
        
        if (couponDiscount > 0) {
            $('#coupon_discount_row').removeClass('d-none');
            $('#coupon_discount_display').text('MYR ' + couponDiscount.toFixed(2));
        } else {
            $('#coupon_discount_row').addClass('d-none');
        }
        
        $('#total').text('MYR ' + total.toFixed(2));
        $('#grand_total').text('MYR ' + total.toFixed(2));
    }

    function applyCoupon() {
        var couponCode = $('#coupon_code').val().trim();
        
        if (!couponCode) {
            Swal.fire('Error', 'Please enter a coupon code', 'error');
            return;
        }

        // TODO: Implement actual coupon validation via AJAX
        // For now, show example behavior
        Swal.fire({
            title: 'Validating Coupon',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate API call
        setTimeout(function() {
            // Example: 10% discount
            var subtotal = 0;
            $('.item-row').each(function() {
                var qty = parseFloat($(this).find('.item-qty').val()) || 0;
                var price = parseFloat($(this).find('.item-price').val()) || 0;
                subtotal += qty * price;
            });
            
            var couponDiscountAmount = subtotal * 0.10; // 10% discount example
            $('#coupon_discount').val(couponDiscountAmount.toFixed(2));
            $('#coupon_message').removeClass('d-none').text('✓ Coupon "' + couponCode + '" applied: 10% discount');
            
            Swal.close();
            calculateTotals();
        }, 1000);
    }

    function holdOrder() {
        Swal.fire({
            title: 'Hold Order?',
            text: 'This order will be saved as a draft',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Hold it',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // TODO: Implement hold order functionality
                Swal.fire('Held!', 'Order has been saved as draft', 'success');
            }
        });
    }

    function voidOrder() {
        Swal.fire({
            title: 'Void Order?',
            text: 'This will clear all entered data',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Void it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
    }

    $('#customer_select').on('change', function() {
        var selected = $(this).find(':selected');
        if (selected.val()) {
            $('#customer_name').val(selected.data('name'));
            $('#customer_phone').val(selected.data('phone'));
            $('#customer_email').val(selected.data('email'));
        } else {
            $('#customer_name').val('');
            $('#customer_phone').val('');
            $('#customer_email').val('');
        }
    });

    $('#discount, #shipping, #order_tax_percent').on('change keyup', function() {
        calculateTotals();
    });

    $('#create-order-form').on('submit', function(e) {
        e.preventDefault();
        
        // Validate at least one item
        if ($('.item-row').length === 0) {
            Swal.fire('Error', 'Please add at least one item', 'error');
            return;
        }

        $.ajax({
            url: '{{ route("laundry.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Order Created!',
                        text: 'Order Number: ' + response.order.order_number,
                        showCancelButton: true,
                        confirmButtonText: 'View Order',
                        cancelButtonText: 'Create Another'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = response.redirect;
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var errorMsg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Validation Error', errorMsg, 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });

    $(document).ready(function() {
        // Initialize customer select2
        if ($.fn.select2) {
            $('#customer_select').select2({
                placeholder: 'Select customer',
                width: '100%'
            });
        }
        
        // Add first item row
        addItem();
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush


