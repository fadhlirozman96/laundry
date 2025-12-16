<?php $page = 'quotation-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Quotation List</h4>
                        <h6>Manage Your Quotations</h6>
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
                    <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-quotation-modal">
                        <i data-feather="plus-circle" class="me-2"></i>Create Quotation
                    </a>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" id="quotation-search" placeholder="Search..." class="form-control form-control-sm">
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
                                            <option value="pending">Pending</option>
                                            <option value="sent">Sent</option>
                                            <option value="accepted">Accepted</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="converted">Converted</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="file-text" class="info-img"></i>
                                        <input type="text" id="filter-reference" class="form-control" placeholder="Reference">
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
                    <div class="table-responsive">
                        <table class="table" id="quotation-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Reference</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Quotation Modal -->
    <div class="modal fade" id="add-quotation-modal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="quotation-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                    <input type="text" name="customer_name" class="form-control" required>
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
                                    <label class="form-label">Valid Until</label>
                                    <input type="date" name="valid_until" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Customer Address</label>
                                    <textarea name="customer_address" class="form-control" rows="2" placeholder="Enter customer address..."></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Quotation Items</h6>
                        <!-- Column Headers -->
                        <div class="row mb-2">
                            <div class="col-md-5"><label class="form-label mb-0">Product/Service</label></div>
                            <div class="col-md-2"><label class="form-label mb-0">Quantity</label></div>
                            <div class="col-md-2"><label class="form-label mb-0">Price (MYR)</label></div>
                            <div class="col-md-2"><label class="form-label mb-0">Subtotal</label></div>
                            <div class="col-md-1"></div>
                        </div>
                        <div id="quotation-items">
                            <div class="row item-row mb-2 align-items-center">
                                <div class="col-md-5">
                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                        <option value="">Select Product/Service</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="number" name="items[0][quantity]" class="form-control item-qty" placeholder="Qty" step="1" min="1" required>
                                        <span class="input-group-text item-unit">-</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="items[0][price]" class="form-control item-price" placeholder="0.00" step="0.01" required>
                                </div>
                                <div class="col-md-2">
                                    <input type="text" class="form-control item-subtotal" placeholder="0.00" readonly>
                                </div>
                                <div class="col-md-1 text-center">
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
                                    <textarea name="notes" id="quotation-notes" class="form-control"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Terms & Conditions</label>
                                    <textarea name="terms" id="quotation-terms" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr><td>Subtotal:</td><td class="text-end" id="quote-subtotal">MYR 0.00</td></tr>
                                    <tr><td>Tax:</td><td><input type="number" name="tax" class="form-control form-control-sm" value="0" step="0.01"></td></tr>
                                    <tr><td>Discount:</td><td><input type="number" name="discount" class="form-control form-control-sm" value="0" step="0.01"></td></tr>
                                    <tr><td><strong>Total:</strong></td><td class="text-end"><strong id="quote-total">MYR 0.00</strong></td></tr>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveQuotation()">Create Quotation</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Quotation Details Modal -->
    <div class="modal fade" id="quotation-details-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Quotation Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quotation-details-content"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="convert-btn" style="display:none;" onclick="convertCurrentQuotation()">Convert to Order</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    #quotation-table_wrapper .dataTables_length, 
    #quotation-table_wrapper .dataTables_filter { display: none !important; }
    .search-set .search-input label,
    .table-top .search-set label { display: none !important; }
    .edit-delete-action { display: flex; align-items: center; gap: 5px; }
    .edit-delete-action a { display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; transition: all 0.3s ease; }
    .edit-delete-action a.action-view { background-color: rgba(13, 202, 240, 0.1); }
    .edit-delete-action a.action-view:hover { background-color: rgba(13, 202, 240, 0.2); }
    .edit-delete-action a.action-view svg { color: #0dcaf0; stroke: #0dcaf0; }
    .edit-delete-action a.action-accept { background-color: rgba(40, 167, 69, 0.1); }
    .edit-delete-action a.action-accept:hover { background-color: rgba(40, 167, 69, 0.2); }
    .edit-delete-action a.action-accept svg { color: #28a745; stroke: #28a745; }
    .edit-delete-action a.action-invoice { background-color: rgba(0, 103, 226, 0.1); }
    .edit-delete-action a.action-invoice:hover { background-color: rgba(0, 103, 226, 0.2); }
    .edit-delete-action a.action-invoice svg { color: #0067e2; stroke: #0067e2; }
    .edit-delete-action a.action-delete { background-color: rgba(234, 84, 85, 0.1); }
    .edit-delete-action a.action-delete:hover { background-color: rgba(234, 84, 85, 0.2); }
    .edit-delete-action a.action-delete svg { color: #ea5455; stroke: #ea5455; }
    
    /* Select2 Styles for Quotation Modal */
    .select2-container { width: 100% !important; }
    .select2-container .select2-selection--single { height: 40px; border: 1px solid #e9ecef; border-radius: 5px; }
    .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: 38px; padding-left: 12px; padding-right: 30px; }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 38px; }
    .select2-container--default .select2-selection--single .select2-selection__clear { display: none !important; }
    .select2-dropdown { border: 1px solid #e9ecef; border-radius: 5px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; }
    .select2-search--dropdown .select2-search__field { border: 1px solid #e9ecef; border-radius: 5px; padding: 8px 12px; }
    .select2-results__option--highlighted { background-color: #0067e2 !important; }
    
    /* Fix Select2 dropdown position - must be above modal (z-index 1050) */
    .select2-container--open .select2-dropdown { z-index: 99999 !important; }
    
    /* Unit display style */
    .item-unit { background-color: #f8f9fa; font-weight: 600; color: #495057; min-width: 45px; text-align: center; }
    .item-row { border-bottom: 1px solid #f1f1f1; padding-bottom: 10px; }
    
    /* Ensure full width */
    #quotation-items .col-md-5, #quotation-items .col-md-2, #quotation-items .col-md-1 { padding: 0 5px; }
    #quotation-items .form-control, #quotation-items .input-group { width: 100%; }
    
    /* Summernote styling */
    .note-editor.note-frame { border: 1px solid #e9ecef; border-radius: 5px; }
    .note-editor .note-toolbar { background-color: #f8f9fa; border-bottom: 1px solid #e9ecef; border-radius: 5px 5px 0 0; padding: 5px 10px; }
    .note-editor .note-editing-area { background: #fff; }
    .note-editor .note-editable { padding: 10px 15px; min-height: 80px; line-height: 1.4; }
    .note-editor .note-editable ul { list-style: disc !important; padding-left: 20px !important; margin: 5px 0 !important; }
    .note-editor .note-editable ol { list-style: decimal !important; padding-left: 20px !important; margin: 5px 0 !important; }
    .note-editor .note-editable li { display: list-item !important; margin-bottom: 2px; }
    .note-editor .note-editable p { margin-bottom: 3px; }
    
    /* Quotation details modal styling */
    .quotation-notes-display { font-size: 13px; line-height: 1.6; }
    .quotation-notes-display ul { list-style: disc !important; padding-left: 20px !important; margin: 10px 0 !important; }
    .quotation-notes-display ol { list-style: decimal !important; padding-left: 20px !important; margin: 10px 0 !important; }
    .quotation-notes-display li { margin-bottom: 5px; }
    .quotation-notes-display p { margin-bottom: 8px; }
    
    /* Table styling in modal */
    #quotation-details-modal .table th { font-weight: 600; background-color: #f8f9fa; }
    #quotation-details-modal .table td { font-weight: normal; }
    #quotation-details-modal .table tfoot td { border-top: none; padding: 5px 8px; }
    
</style>
<script>
var quotationTable, products = [], itemIndex = 0, currentQuotationId = null;
var defaultTerms = '<p>1. Please check all pockets before washing. We are not responsible for items left inside.</p><p>2. Colour fading, shrinkage, wear &amp; tear, and stain removal results are <b>not guaranteed</b>.</p><p>3. We are not liable for damage due to fabric quality or manufacturer defects.</p><p>4. All items must be collected within 7 days after completion.</p><p>5. Items uncollected after 30 days may be disposed of without notice.</p>';

$(document).ready(function() {
    // Load products for quotation dropdown
    loadProducts();

    quotationTable = $('#quotation-table').DataTable({
        processing: true, serverSide: true,
        ajax: {
            url: '{{ route("quotation-list") }}', type: 'GET',
            data: function(d) {
                d.customer = $('#filter-customer').val();
                d.status = $('#filter-status').val();
            }
        },
        columns: [
            { data: 'row_number', orderable: false, searchable: false },
            { data: 'reference' },
            { data: 'customer' },
            { data: 'phone' },
            { data: 'status' },
            { data: 'grand_total' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[1, 'desc']],
        language: { info: "Showing _START_ - _END_ of _TOTAL_ Results", paginate: { previous: '<i class="fa fa-angle-left"></i>', next: '<i class="fa fa-angle-right"></i>' } },
        drawCallback: function() { if (typeof feather !== 'undefined') feather.replace(); }
    });

    $('#quotation-search').on('keyup', function() { quotationTable.search(this.value).draw(); });
    
    // Add new item row
    $('#add-item-btn').on('click', function() {
        itemIndex++;
        var newRow = `<div class="row item-row mb-2 align-items-center">
            <div class="col-md-5"><select name="items[${itemIndex}][product_id]" class="form-control product-select-new" required><option value="">Select Product/Service</option></select></div>
            <div class="col-md-2"><div class="input-group"><input type="number" name="items[${itemIndex}][quantity]" class="form-control item-qty" placeholder="Qty" step="1" min="1" required><span class="input-group-text item-unit">-</span></div></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][price]" class="form-control item-price" placeholder="0.00" step="0.01" required></div>
            <div class="col-md-2"><input type="text" class="form-control item-subtotal" placeholder="0.00" readonly></div>
            <div class="col-md-1 text-center"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-times"></i></button></div>
        </div>`;
        $('#quotation-items').append(newRow);
        
        // Populate the new select with products
        var newSelect = $('#quotation-items .product-select-new:last');
        products.forEach(function(p) {
            var unitName = p.unit ? (p.unit.short_name || p.unit.name) : 'pc';
            newSelect.append('<option value="' + p.id + '" data-unit="' + unitName + '" data-price="' + p.price + '">' + 
                p.name + ' (MYR ' + parseFloat(p.price).toFixed(2) + '/' + unitName + ')</option>');
        });
        
        // Change class and init Select2
        newSelect.removeClass('product-select-new').addClass('product-select');
        newSelect.select2({
            placeholder: 'Search product/service...',
            allowClear: false,
            width: '100%',
            dropdownParent: $('body')
        });
    });
    
    $(document).on('click', '.remove-item', function() { 
        $(this).closest('.item-row').remove(); 
        calculateTotals(); 
    });
    
    // Handle product selection - update unit and price (works for both regular select and Select2)
    $(document).on('change select2:select select2:clear', '.product-select', function() {
        var productId = $(this).val();
        var product = products.find(p => p.id == productId);
        var row = $(this).closest('.item-row');
        if (product) {
            row.find('.item-price').val(product.price);
            row.find('.item-qty').val(1);
            // Show unit (kg, pc, set, etc.)
            var unitName = product.unit ? (product.unit.short_name || product.unit.name) : 'pc';
            row.find('.item-unit').text(unitName);
            
            // Only allow decimal for Kg (weight-based), whole numbers for Pc, Pair, Set, etc.
            var qtyInput = row.find('.item-qty');
            if (unitName.toLowerCase() === 'kg') {
                qtyInput.attr('step', '0.01').attr('min', '0.01');
            } else {
                qtyInput.attr('step', '1').attr('min', '1');
                // Round to whole number if decimal was entered
                var currentVal = parseFloat(qtyInput.val());
                if (currentVal && currentVal % 1 !== 0) {
                    qtyInput.val(Math.ceil(currentVal));
                }
            }
            
            calculateRowSubtotal(row);
        } else {
            row.find('.item-unit').text('-');
            row.find('.item-price').val('');
            row.find('.item-qty').val('');
            row.find('.item-subtotal').val('');
            row.find('.item-qty').attr('step', '1').attr('min', '1');
        }
    });
    
    $(document).on('input', '.item-qty', function() { 
        var row = $(this).closest('.item-row');
        var unitName = row.find('.item-unit').text().toLowerCase();
        
        // For non-Kg units, enforce whole numbers
        if (unitName !== 'kg' && unitName !== '-') {
            var val = parseFloat($(this).val());
            if (val && val % 1 !== 0) {
                $(this).val(Math.ceil(val));
            }
        }
        calculateRowSubtotal(row); 
    });
    
    $(document).on('input', '.item-price', function() { 
        calculateRowSubtotal($(this).closest('.item-row')); 
    });
    $('input[name="tax"], input[name="discount"]').on('input', calculateTotals);
    
    // Re-initialize Select2 when modal is shown
    $('#add-quotation-modal').on('shown.bs.modal', function() {
        // Only init if not already initialized
        $('.product-select').each(function() {
            if (!$(this).data('select2')) {
                populateProductSelects();
            }
        });
    });
    
    // Close Select2 dropdown when scrolling modal
    $('#add-quotation-modal .modal-body').on('scroll', function() {
        $('.product-select').each(function() {
            if ($(this).data('select2')) {
                $(this).select2('close');
            }
        });
    });
    
    // Close Select2 when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.select2-container').length && 
            !$(e.target).closest('.select2-dropdown').length) {
            $('.product-select').each(function() {
                if ($(this).data('select2')) {
                    $(this).select2('close');
                }
            });
        }
    });
    
    // Initialize Summernote for Notes field
    $('#quotation-notes').summernote({
        placeholder: 'Add notes, special instructions...',
        height: 100,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
    
    // Initialize Summernote for Terms & Conditions with default content
    $('#quotation-terms').summernote({
        placeholder: 'Add terms & conditions...',
        height: 150,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
    
    // Set default terms content
    setTimeout(function() {
        $('#quotation-terms').summernote('code', defaultTerms);
    }, 100);
});

function loadProducts() {
    $.ajax({
        url: '{{ route("quotations.products") }}',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success && response.products) {
                products = response.products;
                console.log('Loaded', products.length, 'products');
                populateProductSelects();
            } else {
                console.error('Failed to load products:', response.message || 'Unknown error');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error loading products:', status, error);
        }
    });
}

function populateProductSelects() {
    $('.product-select').each(function() {
        var select = $(this), val = select.val();
        
        // Destroy Select2 first if it exists
        if (select.data('select2')) {
            select.select2('destroy');
        }
        
        // Clear and repopulate options
        select.empty().append('<option value="">Select Product/Service</option>');
        products.forEach(function(p) {
            var unitName = p.unit ? (p.unit.short_name || p.unit.name) : 'pc';
            select.append('<option value="' + p.id + '" data-unit="' + unitName + '" data-price="' + p.price + '">' + 
                p.name + ' (MYR ' + parseFloat(p.price).toFixed(2) + '/' + unitName + ')</option>');
        });
        
        if (val) select.val(val);
        
        // Initialize Select2 without clear button
        select.select2({
            placeholder: 'Search product/service...',
            allowClear: false,
            width: '100%',
            dropdownParent: $('body')
        });
    });
}

function initSelect2() {
    // Re-populate and re-init Select2 for all product selects
    populateProductSelects();
}
function calculateRowSubtotal(row) {
    var sub = (parseFloat(row.find('.item-qty').val()) || 0) * (parseFloat(row.find('.item-price').val()) || 0);
    row.find('.item-subtotal').val('MYR ' + sub.toFixed(2));
    calculateTotals();
}
function calculateTotals() {
    var subtotal = 0;
    $('.item-row').each(function() { subtotal += (parseFloat($(this).find('.item-qty').val()) || 0) * (parseFloat($(this).find('.item-price').val()) || 0); });
    var total = subtotal + (parseFloat($('input[name="tax"]').val()) || 0) - (parseFloat($('input[name="discount"]').val()) || 0);
    $('#quote-subtotal').text('MYR ' + subtotal.toFixed(2));
    $('#quote-total').text('MYR ' + total.toFixed(2));
}
function applyFilters() { quotationTable.ajax.reload(); }

function saveQuotation() {
    var items = [];
    $('.item-row').each(function() {
        var pid = $(this).find('.product-select').val();
        if (pid) items.push({ product_id: pid, quantity: $(this).find('.item-qty').val(), price: $(this).find('.item-price').val() });
    });
    if (!items.length) { Swal.fire('Error', 'Add at least one item', 'error'); return; }
    $.ajax({
        url: '{{ route("quotations.store") }}', type: 'POST',
        data: { 
            _token: '{{ csrf_token() }}', 
            customer_name: $('input[name="customer_name"]').val(), 
            customer_email: $('input[name="customer_email"]').val(),
            customer_phone: $('input[name="customer_phone"]').val(), 
            customer_address: $('textarea[name="customer_address"]').val(),
            valid_until: $('input[name="valid_until"]').val(),
            tax: $('input[name="tax"]').val(), 
            discount: $('input[name="discount"]').val(), 
            notes: $('#quotation-notes').summernote('code'), 
            terms: $('#quotation-terms').summernote('code'),
            items: items 
        },
        success: function(r) {
            if (r.success) { 
                Swal.fire('Success', r.message, 'success'); 
                $('#add-quotation-modal').modal('hide'); 
                $('#quotation-form')[0].reset(); 
                $('#quotation-notes').summernote('reset');
                $('#quotation-terms').summernote('code', defaultTerms); // Reset to default terms
                quotationTable.ajax.reload(); 
            }
            else Swal.fire('Error', r.message, 'error');
        },
        error: function(x) { Swal.fire('Error', x.responseJSON?.message || 'Error', 'error'); }
    });
}

function viewQuotation(id) {
    currentQuotationId = id;
    $.get('{{ url("quotations") }}/' + id, function(r) {
        if (r.success) {
            var q = r.quotation;
            var store = q.store || {};
            var statusBadge = q.status === 'accepted' ? 'badge bg-success' : (q.status === 'pending' ? 'badge bg-warning' : 'badge bg-secondary');
            
            // Format valid_until date
            var validUntil = q.valid_until ? new Date(q.valid_until).toLocaleDateString('en-GB', {day: '2-digit', month: 'short', year: 'numeric'}) : '-';
            
            var html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2">Store Details</h6>
                        <p class="mb-1"><strong>${store.name || 'Store'}</strong></p>
                        <p class="mb-1 text-muted">${store.address || ''}</p>
                        <p class="mb-1 text-muted">${store.phone || ''} ${store.email ? '| ' + store.email : ''}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6 class="text-primary mb-2">Quotation Info</h6>
                        <p class="mb-1"><strong>Reference:</strong> ${q.quotation_number}</p>
                        <p class="mb-1"><strong>Valid Until:</strong> ${validUntil}</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="${statusBadge}">${q.status.charAt(0).toUpperCase() + q.status.slice(1)}</span></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-primary mb-2">Customer Details</h6>
                        <p class="mb-1"><strong>${q.customer_name || 'Walk in Customer'}</strong></p>
                        <p class="mb-1 text-muted">${q.customer_address || ''}</p>
                        <p class="mb-1 text-muted">${q.customer_phone || ''} ${q.customer_email ? '| ' + q.customer_email : ''}</p>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Product/Service</th>
                            <th class="text-center">Qty</th>
                            <th class="text-center">Unit</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            q.items.forEach(i => {
                var unitName = i.product && i.product.unit ? (i.product.unit.short_name || i.product.unit.name) : 'pc';
                html += `<tr>
                    <td>${i.product_name}</td>
                    <td class="text-center">${parseFloat(i.quantity).toFixed(2)}</td>
                    <td class="text-center">${unitName}</td>
                    <td class="text-end">MYR ${parseFloat(i.price).toFixed(2)}</td>
                    <td class="text-end">MYR ${parseFloat(i.subtotal).toFixed(2)}</td>
                </tr>`;
            });
            
            html += `</tbody>
                <tfoot>
                    <tr><td colspan="4" class="text-end">Subtotal:</td><td class="text-end">MYR ${parseFloat(q.subtotal).toFixed(2)}</td></tr>
                    <tr><td colspan="4" class="text-end">Tax:</td><td class="text-end">MYR ${parseFloat(q.tax || 0).toFixed(2)}</td></tr>
                    <tr><td colspan="4" class="text-end">Discount:</td><td class="text-end">- MYR ${parseFloat(q.discount || 0).toFixed(2)}</td></tr>
                    <tr class="table-primary"><td colspan="4" class="text-end"><strong>Total:</strong></td><td class="text-end"><strong>MYR ${parseFloat(q.total).toFixed(2)}</strong></td></tr>
                </tfoot>
            </table>`;
            
            // Show notes if available
            if (q.notes && q.notes.trim() !== '' && q.notes !== '<p><br></p>') {
                html += `<div class="mt-4 mb-3"><h6 class="text-primary">Notes</h6><div class="p-2 bg-light rounded quotation-notes-display">${q.notes}</div></div>`;
            }
            
            // Show terms if available
            if (q.terms && q.terms.trim() !== '' && q.terms !== '<p><br></p>') {
                html += `<div class="mb-3"><h6 class="text-primary">Terms & Conditions</h6><div class="p-2 bg-light rounded quotation-notes-display">${q.terms}</div></div>`;
            }
            
            // Action buttons
            html += `<div class="text-center mt-3">
                <a href="{{ url('quotations') }}/${q.id}/pdf" target="_blank" class="btn btn-outline-primary me-2">
                    <i data-feather="download" class="me-1"></i> Download PDF
                </a>`;
            
            if (q.status === 'pending' || q.status === 'sent') {
                html += `<button class="btn btn-success" onclick="$('#quotation-details-modal').modal('hide'); acceptQuotation(${q.id});">
                    <i data-feather="check-circle" class="me-1"></i> Accept Quotation
                </button>`;
            }
            html += `</div>`;
            
            $('#quotation-details-content').html(html);
            $('#quotation-details-modal .modal-title').text('Quotation Details');
            $('#convert-btn').hide();
            $('#quotation-details-modal').modal('show');
            
            // Re-initialize feather icons
            if (typeof feather !== 'undefined') feather.replace();
        }
    });
}

function acceptQuotation(id) {
    Swal.fire({ 
        title: 'Accept Quotation?', 
        text: "This will accept the quotation and automatically generate an invoice", 
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Yes, Accept!' 
    }).then(r => {
        if (r.isConfirmed) {
            $.post('{{ url("quotations") }}/' + id + '/accept', { _token: '{{ csrf_token() }}' }, function(r) {
                if (r.success) { 
                    Swal.fire({
                        title: 'Accepted!', 
                        html: r.message + '<br><br><strong>Invoice Number:</strong> ' + r.invoice_number,
                        icon: 'success'
                    }); 
                    quotationTable.ajax.reload(); 
                } else {
                    Swal.fire('Error', r.message, 'error');
                }
            }).fail(function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
            });
        }
    });
}

function viewQuotationInvoice(id) {
    $.get('{{ url("quotations") }}/' + id + '/invoice', function(r) {
        if (r.success) {
            var inv = r.invoice;
            var statusBadge = inv.status === 'paid' ? 'badge-linesuccess' : (inv.status === 'partial' ? 'badges-warning' : 'badge-linedanger');
            var html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Invoice Number:</strong> ${inv.invoice_number}</p>
                        <p><strong>Customer:</strong> ${inv.customer_name || 'Walk in Customer'}</p>
                        <p><strong>Email:</strong> ${inv.customer_email || '-'}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Due Date:</strong> ${inv.due_date || '-'}</p>
                        <p><strong>Status:</strong> <span class="badge ${statusBadge}">${inv.status.charAt(0).toUpperCase() + inv.status.slice(1)}</span></p>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered">
                    <thead><tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>`;
            inv.items.forEach(i => html += `<tr><td>${i.product_name}</td><td class="text-end">${i.quantity}</td><td class="text-end">MYR ${parseFloat(i.price).toFixed(2)}</td><td class="text-end">MYR ${parseFloat(i.subtotal).toFixed(2)}</td></tr>`);
            html += `</tbody>
                <tfoot>
                    <tr><td colspan="3" class="text-end"><strong>Total:</strong></td><td class="text-end"><strong>MYR ${parseFloat(inv.total).toFixed(2)}</strong></td></tr>
                    <tr><td colspan="3" class="text-end"><strong>Paid:</strong></td><td class="text-end">MYR ${parseFloat(inv.amount_paid).toFixed(2)}</td></tr>
                    <tr><td colspan="3" class="text-end"><strong>Due:</strong></td><td class="text-end">MYR ${parseFloat(inv.amount_due).toFixed(2)}</td></tr>
                </tfoot>
            </table>
            <div class="text-center mt-3">
                <a href="{{ url("invoice-report") }}" class="btn btn-primary">Go to Invoice Module</a>
            </div>`;
            $('#quotation-details-content').html(html);
            $('#quotation-details-modal .modal-title').text('Invoice Details');
            $('#convert-btn').hide();
            $('#quotation-details-modal').modal('show');
        } else {
            Swal.fire('Error', r.message, 'error');
        }
    }).fail(function(xhr) {
        Swal.fire('Error', xhr.responseJSON?.message || 'Invoice not found', 'error');
    });
}

function deleteQuotation(id) {
    Swal.fire({ title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete!' })
    .then(r => {
        if (r.isConfirmed) {
            $.ajax({ url: '{{ url("quotations") }}/' + id, type: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, success: function(r) {
                if (r.success) { Swal.fire('Deleted!', r.message, 'success'); quotationTable.ajax.reload(); } else Swal.fire('Error', r.message, 'error');
            }});
        }
    });
}
</script>
@endpush
