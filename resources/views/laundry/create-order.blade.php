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
                <div class="col-lg-4">
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
                <div class="col-lg-8">
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
                                            <th style="width: 25%">Garment Type</th>
                                            <th style="width: 20%">Description</th>
                                            <th style="width: 10%">Qty</th>
                                            <th style="width: 15%">Price (MYR)</th>
                                            <th style="width: 15%">Subtotal</th>
                                            <th style="width: 10%">Color</th>
                                            <th style="width: 5%"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="items-body">
                                        <!-- Items will be added here -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                            <td colspan="3"><span id="subtotal">MYR 0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Tax (6%):</strong></td>
                                            <td colspan="3"><span id="tax">MYR 0.00</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                            <td colspan="3">
                                                <input type="number" class="form-control form-control-sm" name="discount" id="discount" value="0" min="0" step="0.01" style="width: 100px;">
                                            </td>
                                        </tr>
                                        <tr class="table-primary">
                                            <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                            <td colspan="3"><strong><span id="total">MYR 0.00</span></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea class="form-control" name="notes" rows="2" placeholder="Order notes..."></textarea>
                            </div>

                            <div class="text-end">
                                <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i data-feather="save" class="me-2"></i>Create Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Garment Types Data -->
<script>
    var garmentTypes = @json($garmentTypes);
</script>
@endsection

@push('scripts')
<script>
    var itemIndex = 0;

    function addItem() {
        var options = '<option value="">Select Type</option>';
        garmentTypes.forEach(function(type) {
            options += '<option value="' + type.id + '" data-price="' + type.default_price + '">' + type.name + '</option>';
        });

        var row = `
            <tr class="item-row" data-index="${itemIndex}">
                <td>
                    <select class="form-control form-control-sm garment-type" name="items[${itemIndex}][garment_type_id]" onchange="setGarmentPrice(this, ${itemIndex})">
                        ${options}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][garment_name]" required placeholder="e.g. White Shirt">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-qty" name="items[${itemIndex}][quantity]" value="1" min="1" onchange="calculateItemSubtotal(${itemIndex})">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm item-price" name="items[${itemIndex}][price]" value="0" min="0" step="0.01" onchange="calculateItemSubtotal(${itemIndex})">
                </td>
                <td>
                    <span class="item-subtotal">MYR 0.00</span>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" name="items[${itemIndex}][color]" placeholder="Color">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeItem(${itemIndex})">
                        <i data-feather="trash-2"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#items-body').append(row);
        itemIndex++;
        feather.replace();
    }

    function setGarmentPrice(select, index) {
        var price = $(select).find(':selected').data('price') || 0;
        var row = $('[data-index="' + index + '"]');
        row.find('.item-price').val(price);
        calculateItemSubtotal(index);
    }

    function calculateItemSubtotal(index) {
        var row = $('[data-index="' + index + '"]');
        var qty = parseFloat(row.find('.item-qty').val()) || 0;
        var price = parseFloat(row.find('.item-price').val()) || 0;
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

        var tax = subtotal * 0.06;
        var discount = parseFloat($('#discount').val()) || 0;
        var total = subtotal + tax - discount;

        $('#subtotal').text('MYR ' + subtotal.toFixed(2));
        $('#tax').text('MYR ' + tax.toFixed(2));
        $('#total').text('MYR ' + total.toFixed(2));
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

    $('#discount').on('change', function() {
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
        addItem(); // Add first item row
        
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        if ($.fn.select2) {
            $('.select2').select2({
                placeholder: 'Select customer',
                allowClear: true
            });
        }
    });
</script>
@endpush


