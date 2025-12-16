<?php $page = 'invoice-report'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Invoices</h4>
                        <h6>Manage Your Invoices</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{ URL::asset('/build/img/icons/pdf.svg') }}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{ URL::asset('/build/img/icons/excel.svg') }}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload()"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="page-btn">
                    <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-invoice-modal">
                        <i data-feather="plus-circle" class="me-2"></i>Create Invoice
                    </a>
                </div>
            </div>

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" id="invoice-search" placeholder="Search..." class="form-control form-control-sm">
                                <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select" id="sort-date">
                                <option value="">Sort by Date</option>
                                <option value="desc">Newest First</option>
                                <option value="asc">Oldest First</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <input type="text" id="filter-customer" class="form-control" placeholder="Customer Name">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">Choose Status</option>
                                            <option value="paid">Paid</option>
                                            <option value="partial">Partial</option>
                                            <option value="overdue">Overdue</option>
                                            <option value="sent">Sent</option>
                                            <option value="draft">Draft</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <div class="position-relative daterange-wraper">
                                            <input type="text" class="form-control" name="datetimes" id="filter-dates" placeholder="From Date - To Date">
                                            <i data-feather="calendar" class="feather-14 info-img"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto" onclick="applyFilters()"> <i data-feather="search" class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table" id="invoice-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Customer</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Paid</th>
                                    <th>Amount Due</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add Invoice Modal -->
    <div class="modal fade" id="add-invoice-modal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Invoice</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="invoice-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Email</label>
                                    <input type="email" name="customer_email" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Phone</label>
                                    <input type="text" name="customer_phone" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Due Date</label>
                                    <input type="date" name="due_date" class="form-control">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Invoice Items</h6>
                        <div id="invoice-items">
                            <div class="row item-row mb-2 align-items-center">
                                <div class="col-md-4">
                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                        <option value="">Select Product/Service</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="number" name="items[0][quantity]" class="form-control item-qty" placeholder="Qty" step="0.01" required>
                                        <span class="input-group-text item-unit" style="min-width: 50px;">-</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="items[0][price]" class="form-control item-price" placeholder="Price" step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control item-subtotal" placeholder="Subtotal" readonly>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-item-btn"><i class="fa fa-plus"></i> Add Item</button>
                        <hr>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea name="notes" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr><td>Subtotal:</td><td class="text-end" id="invoice-subtotal">MYR 0.00</td></tr>
                                    <tr><td>Tax:</td><td><input type="number" name="tax" class="form-control form-control-sm" value="0" step="0.01"></td></tr>
                                    <tr><td>Discount:</td><td><input type="number" name="discount" class="form-control form-control-sm" value="0" step="0.01"></td></tr>
                                    <tr><td><strong>Total:</strong></td><td class="text-end"><strong id="invoice-total">MYR 0.00</strong></td></tr>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveInvoice()">Create Invoice</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Details Modal -->
    <div class="modal fade" id="invoice-details-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Invoice Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="invoice-details-content">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer" id="invoice-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="record-payment-btn" onclick="showPaymentForm()" style="display:none;">
                        <i data-feather="dollar-sign" class="me-1"></i> Record Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Record Payment Modal -->
    <div class="modal fade" id="payment-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Record Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="payment-form">
                        <input type="hidden" id="payment-invoice-id">
                        <div class="mb-3">
                            <label class="form-label">Invoice Number</label>
                            <input type="text" class="form-control" id="payment-invoice-number" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Amount Due</label>
                            <input type="text" class="form-control" id="payment-amount-due" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Apply Coupon (Optional)</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="payment-coupon-code" placeholder="Enter coupon code" style="text-transform: uppercase;">
                                <button type="button" class="btn btn-outline-primary" onclick="applyPaymentCoupon()">Apply</button>
                            </div>
                            <small class="text-success d-none" id="payment-coupon-success"></small>
                            <small class="text-danger d-none" id="payment-coupon-error"></small>
                            <input type="hidden" id="payment-coupon-id">
                            <input type="hidden" id="payment-coupon-discount" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Amount <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="payment-amount" step="0.01" min="0" required>
                            <small class="text-muted" id="payment-amount-after-discount"></small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Payment Method</label>
                            <select class="form-control" id="payment-method">
                                <option value="cash">Cash</option>
                                <option value="card">Credit/Debit Card</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="ewallet">E-Wallet</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" id="payment-notes" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="recordPayment()">Record Payment</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    #invoice-table_wrapper .dataTables_length,
    #invoice-table_wrapper .dataTables_filter { display: none !important; }
    
    /* Custom badge styles */
    .badge-lineinfo {
        background-color: transparent;
        border: 1px solid #0dcaf0;
        color: #0dcaf0;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .badge-linewarning {
        background-color: transparent;
        border: 1px solid #ffc107;
        color: #ffc107;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 5px;
    }
    
    .edit-delete-action { display: flex; align-items: center; gap: 5px; }
    .edit-delete-action a {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px; transition: all 0.3s ease;
    }
    .edit-delete-action a.action-view { background-color: rgba(13, 202, 240, 0.1); }
    .edit-delete-action a.action-view:hover { background-color: rgba(13, 202, 240, 0.2); }
    .edit-delete-action a.action-view svg { color: #0dcaf0; stroke: #0dcaf0; }
    .edit-delete-action a.action-print { background-color: rgba(0, 103, 226, 0.1); }
    .edit-delete-action a.action-print:hover { background-color: rgba(0, 103, 226, 0.2); }
    .edit-delete-action a.action-print svg { color: #0067e2; stroke: #0067e2; }
    .edit-delete-action a.action-payment { background-color: rgba(40, 167, 69, 0.1); }
    .edit-delete-action a.action-payment:hover { background-color: rgba(40, 167, 69, 0.2); }
    .edit-delete-action a.action-payment svg { color: #28a745; stroke: #28a745; }
    .edit-delete-action a.action-delete { background-color: rgba(234, 84, 85, 0.1); }
    .edit-delete-action a.action-delete:hover { background-color: rgba(234, 84, 85, 0.2); }
    .edit-delete-action a.action-delete svg { color: #ea5455; stroke: #ea5455; }
    
    /* Select2 Styles */
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single { height: 40px; border: 1px solid #e9ecef; border-radius: 5px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px; padding-left: 12px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px; }
    .select2-dropdown { border: 1px solid #e9ecef; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .select2-search--dropdown .select2-search__field { border: 1px solid #e9ecef; border-radius: 5px; padding: 8px 12px; }
    .select2-results__option--highlighted { background-color: #0067e2 !important; }
    .item-unit { background-color: #f8f9fa; font-weight: 600; color: #495057; }
    .item-row { border-bottom: 1px solid #f1f1f1; padding-bottom: 10px; }
</style>
<script>
var invoiceTable;
var products = [];
var itemIndex = 0;

$(document).ready(function() {
    // Load products
    $.get('{{ route("quotations.products") }}', function(response) {
        if (response.success) {
            products = response.products;
            populateProductSelects();
            initSelect2();
        }
    });

    invoiceTable = $('#invoice-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("invoice-report") }}',
            type: 'GET',
            data: function(d) {
                d.customer = $('#filter-customer').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'row_number', orderable: false, searchable: false },
            { data: 'invoice_no' },
            { data: 'customer' },
            { data: 'due_date' },
            { data: 'amount' },
            { data: 'paid' },
            { data: 'amount_due' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: {
            info: "Showing _START_ - _END_ of _TOTAL_ Results",
            paginate: { previous: '<i class="fa fa-angle-left"></i>', next: '<i class="fa fa-angle-right"></i>' }
        },
        drawCallback: function() { if (typeof feather !== 'undefined') feather.replace(); }
    });

    $('#invoice-search').on('keyup', function() { invoiceTable.search(this.value).draw(); });

    // Add item row
    $('#add-item-btn').on('click', function() {
        itemIndex++;
        var newRow = `<div class="row item-row mb-2 align-items-center">
            <div class="col-md-4"><select name="items[${itemIndex}][product_id]" class="form-control product-select" required><option value="">Select Product/Service</option></select></div>
            <div class="col-md-2"><div class="input-group"><input type="number" name="items[${itemIndex}][quantity]" class="form-control item-qty" placeholder="Qty" step="0.01" required><span class="input-group-text item-unit" style="min-width: 50px;">-</span></div></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][price]" class="form-control item-price" placeholder="Price" step="0.01" required></div>
            <div class="col-md-2"><input type="text" class="form-control item-subtotal" placeholder="Subtotal" readonly></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-times"></i></button></div>
        </div>`;
        $('#invoice-items').append(newRow);
        populateProductSelects();
        initSelect2();
    });

    $(document).on('click', '.remove-item', function() {
        $(this).closest('.item-row').remove();
        calculateTotals();
    });

    // Handle product selection - update unit and price
    $(document).on('change select2:select select2:clear', '.product-select', function() {
        var productId = $(this).val();
        var row = $(this).closest('.item-row');
        var product = products.find(p => p.id == productId);
        if (product) {
            row.find('.item-price').val(product.price);
            row.find('.item-qty').val(1);
            // Show unit (kg, pc, set, etc.)
            var unitName = product.unit ? (product.unit.short_name || product.unit.name) : 'pc';
            row.find('.item-unit').text(unitName);
            calculateRowSubtotal(row);
        } else {
            row.find('.item-unit').text('-');
            row.find('.item-price').val('');
            row.find('.item-qty').val('');
            row.find('.item-subtotal').val('');
        }
    });

    $(document).on('input', '.item-qty, .item-price', function() {
        calculateRowSubtotal($(this).closest('.item-row'));
    });

    $('input[name="tax"], input[name="discount"]').on('input', calculateTotals);
    
    // Init Select2 when modal opens
    $('#add-invoice-modal').on('shown.bs.modal', function() {
        setTimeout(function() { initSelect2(); }, 100);
    });
});

function populateProductSelects() {
    $('.product-select').each(function() {
        var select = $(this);
        var currentVal = select.val();
        select.find('option:not(:first)').remove();
        products.forEach(function(product) {
            var unitName = product.unit ? (product.unit.short_name || product.unit.name) : 'pc';
            select.append(`<option value="${product.id}" data-price="${product.price}" data-unit="${unitName}">${product.name} (MYR ${parseFloat(product.price).toFixed(2)}/${unitName})</option>`);
        });
        if (currentVal) select.val(currentVal);
    });
}

function initSelect2() {
    $('.product-select').each(function() {
        if (!$(this).hasClass('select2-hidden-accessible')) {
            $(this).select2({
                placeholder: 'Search product/service...',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#add-invoice-modal')
            });
        }
    });
}

function calculateRowSubtotal(row) {
    var qty = parseFloat(row.find('.item-qty').val()) || 0;
    var price = parseFloat(row.find('.item-price').val()) || 0;
    var subtotal = qty * price;
    row.find('.item-subtotal').val('MYR ' + subtotal.toFixed(2));
    calculateTotals();
}

function calculateTotals() {
    var subtotal = 0;
    $('.item-row').each(function() {
        var qty = parseFloat($(this).find('.item-qty').val()) || 0;
        var price = parseFloat($(this).find('.item-price').val()) || 0;
        subtotal += qty * price;
    });
    var tax = parseFloat($('input[name="tax"]').val()) || 0;
    var discount = parseFloat($('input[name="discount"]').val()) || 0;
    var total = subtotal + tax - discount;
    $('#invoice-subtotal').text('MYR ' + subtotal.toFixed(2));
    $('#invoice-total').text('MYR ' + total.toFixed(2));
}

function applyFilters() { invoiceTable.ajax.reload(); }

function saveInvoice() {
    var items = [];
    $('.item-row').each(function() {
        var productId = $(this).find('.product-select').val();
        if (productId) {
            items.push({
                product_id: productId,
                quantity: $(this).find('.item-qty').val(),
                price: $(this).find('.item-price').val()
            });
        }
    });

    if (items.length === 0) {
        Swal.fire('Error', 'Please add at least one item', 'error');
        return;
    }

    var formData = {
        _token: '{{ csrf_token() }}',
        customer_name: $('input[name="customer_name"]').val(),
        customer_email: $('input[name="customer_email"]').val(),
        customer_phone: $('input[name="customer_phone"]').val(),
        due_date: $('input[name="due_date"]').val(),
        tax: $('input[name="tax"]').val(),
        discount: $('input[name="discount"]').val(),
        notes: $('textarea[name="notes"]').val(),
        items: items
    };

    $.ajax({
        url: '{{ route("invoices.store") }}',
        type: 'POST',
        data: formData,
        success: function(response) {
            if (response.success) {
                Swal.fire('Success', response.message, 'success');
                $('#add-invoice-modal').modal('hide');
                $('#invoice-form')[0].reset();
                invoiceTable.ajax.reload();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
        }
    });
}

var currentInvoice = null;

function viewInvoice(id) {
    $.get('{{ url("invoices") }}/' + id, function(response) {
        if (response.success) {
            var invoice = response.invoice;
            currentInvoice = invoice;
            var statusBadge = invoice.status === 'paid' ? 'badge bg-success' : (invoice.status === 'partial' ? 'badge bg-warning' : (invoice.status === 'overdue' ? 'badge bg-danger' : 'badge bg-info'));
            var html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Invoice:</strong> ${invoice.invoice_number}</p>
                        <p><strong>Customer:</strong> ${invoice.customer_name || 'Walk in Customer'}</p>
                        <p><strong>Email:</strong> ${invoice.customer_email || '-'}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Due Date:</strong> ${invoice.due_date || '-'}</p>
                        <p><strong>Status:</strong> <span class="${statusBadge}">${invoice.status.charAt(0).toUpperCase() + invoice.status.slice(1)}</span></p>
                    </div>
                </div>
                <table class="table table-bordered">
                    <thead><tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>`;
            invoice.items.forEach(function(item) {
                html += `<tr><td>${item.product_name}</td><td class="text-end">${item.quantity}</td><td class="text-end">MYR ${parseFloat(item.price).toFixed(2)}</td><td class="text-end">MYR ${parseFloat(item.subtotal).toFixed(2)}</td></tr>`;
            });
            html += `</tbody>
                <tfoot>
                    <tr><td colspan="3" class="text-end"><strong>Total:</strong></td><td class="text-end"><strong>MYR ${parseFloat(invoice.total).toFixed(2)}</strong></td></tr>
                    <tr><td colspan="3" class="text-end"><strong>Paid:</strong></td><td class="text-end text-success">MYR ${parseFloat(invoice.amount_paid).toFixed(2)}</td></tr>
                    <tr><td colspan="3" class="text-end"><strong>Due:</strong></td><td class="text-end text-danger">MYR ${parseFloat(invoice.amount_due).toFixed(2)}</td></tr>
                </tfoot></table>`;
            $('#invoice-details-content').html(html);
            // Show record payment button if invoice is not fully paid
            $('#record-payment-btn').toggle(invoice.status !== 'paid');
            $('#invoice-details-modal').modal('show');
            if (typeof feather !== 'undefined') feather.replace();
        }
    });
}

function showPaymentForm() {
    if (!currentInvoice) return;
    $('#invoice-details-modal').modal('hide');
    $('#payment-invoice-id').val(currentInvoice.id);
    $('#payment-invoice-number').val(currentInvoice.invoice_number);
    $('#payment-amount-due').val('MYR ' + parseFloat(currentInvoice.amount_due).toFixed(2));
    $('#payment-amount').val(currentInvoice.amount_due).attr('max', currentInvoice.amount_due);
    $('#payment-method').val('cash');
    $('#payment-notes').val('');
    // Reset coupon fields
    $('#payment-coupon-code').val('');
    $('#payment-coupon-id').val('');
    $('#payment-coupon-discount').val('0');
    $('#payment-coupon-success, #payment-coupon-error, #payment-amount-after-discount').addClass('d-none');
    $('#payment-modal').modal('show');
}

function applyPaymentCoupon() {
    var code = $('#payment-coupon-code').val().trim().toUpperCase();
    var amountDue = parseFloat($('#payment-amount-due').val().replace('MYR ', '').replace(',', '')) || 0;
    
    if (!code) {
        $('#payment-coupon-error').text('Please enter a coupon code').removeClass('d-none');
        $('#payment-coupon-success').addClass('d-none');
        return;
    }
    
    $.ajax({
        url: '{{ route("coupons.apply") }}',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            code: code,
            amount: amountDue
        },
        success: function(response) {
            if (response.success) {
                $('#payment-coupon-id').val(response.coupon.id);
                $('#payment-coupon-discount').val(response.coupon.discount_amount);
                $('#payment-coupon-success').text('Coupon applied: -MYR ' + response.coupon.discount_amount.toFixed(2)).removeClass('d-none');
                $('#payment-coupon-error').addClass('d-none');
                
                var newAmount = amountDue - response.coupon.discount_amount;
                $('#payment-amount').val(newAmount.toFixed(2));
                $('#payment-amount-after-discount').text('After discount: MYR ' + newAmount.toFixed(2)).removeClass('d-none');
            } else {
                $('#payment-coupon-error').text(response.message).removeClass('d-none');
                $('#payment-coupon-success').addClass('d-none');
            }
        },
        error: function(xhr) {
            $('#payment-coupon-error').text(xhr.responseJSON?.message || 'Failed to apply coupon').removeClass('d-none');
            $('#payment-coupon-success').addClass('d-none');
        }
    });
}

function recordPayment() {
    var invoiceId = $('#payment-invoice-id').val();
    var amount = parseFloat($('#payment-amount').val());
    var couponId = $('#payment-coupon-id').val();
    var couponDiscount = parseFloat($('#payment-coupon-discount').val()) || 0;
    
    if (!amount || amount <= 0) {
        Swal.fire('Error', 'Please enter a valid payment amount', 'error');
        return;
    }
    
    // If coupon is applied, include the discount amount
    var totalPayment = amount + couponDiscount;
    
    $.ajax({
        url: '{{ url("invoices") }}/' + invoiceId + '/payment',
        type: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            amount: totalPayment,
            payment_method: $('#payment-method').val(),
            notes: $('#payment-notes').val(),
            coupon_id: couponId,
            coupon_discount: couponDiscount
        },
        success: function(response) {
            if (response.success) {
                Swal.fire('Success', response.message, 'success');
                $('#payment-modal').modal('hide');
                invoiceTable.ajax.reload();
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr) {
            Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
        }
    });
}

function openPaymentModal(invoiceId, invoiceNumber, amountDue) {
    $('#payment-invoice-id').val(invoiceId);
    $('#payment-invoice-number').val(invoiceNumber);
    $('#payment-amount-due').val('MYR ' + parseFloat(amountDue).toFixed(2));
    $('#payment-amount').val(amountDue).attr('max', amountDue);
    $('#payment-method').val('cash');
    $('#payment-notes').val('');
    $('#payment-modal').modal('show');
}

function printInvoice(id) { window.open('{{ url("invoices") }}/' + id + '?print=1', '_blank'); }

function deleteInvoice(id) {
    Swal.fire({
        title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning',
        showCancelButton: true, confirmButtonColor: '#3085d6', cancelButtonColor: '#d33', confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("invoices") }}/' + id, type: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(response) {
                    if (response.success) { Swal.fire('Deleted!', response.message, 'success'); invoiceTable.ajax.reload(); }
                    else { Swal.fire('Error!', response.message, 'error'); }
                },
                error: function() { Swal.fire('Error!', 'Something went wrong', 'error'); }
            });
        }
    });
}
</script>
@endpush
