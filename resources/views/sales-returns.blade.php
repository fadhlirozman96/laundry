<?php $page = 'sales-returns'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Sales Return List</h4>
                        <h6>Manage your Returns</h6>
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
                    <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-return-modal">
                        <i data-feather="plus-circle" class="me-2"></i>Create Return
                    </a>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" id="return-search" placeholder="Search..." class="form-control form-control-sm">
                                <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select" id="sort-date">
                                <option value="">Sort by Date</option>
                                <option value="desc">Newest</option>
                                <option value="asc">Oldest</option>
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
                                        <i data-feather="zap" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">Choose Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="completed">Completed</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto" onclick="applyFilters()"> <i data-feather="search" class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="returns-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Refunded</th>
                                    <th>Balance</th>
                                    <th>Payment Status</th>
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

    <!-- Add Return Modal -->
    <div class="modal fade" id="add-return-modal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Sales Return</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="return-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Select Order (Optional)</label>
                                    <select name="order_id" class="form-control" id="order-select">
                                        <option value="">-- No Order (Manual Entry) --</option>
                                        @if(isset($orders))
                                        @foreach($orders as $order)
                                        <option value="{{ $order->id }}">{{ $order->order_number }} - {{ $order->customer_name ?: 'Walk in' }} (MYR {{ number_format($order->total, 2) }})</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Customer Name</label>
                                    <input type="text" name="customer_name" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label">Reason for Return <span class="text-danger">*</span></label>
                                    <textarea name="reason" class="form-control" rows="2" required placeholder="Why is this being returned?"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <h6>Return Items</h6>
                        <div id="return-items">
                            <div class="row item-row mb-2">
                                <div class="col-md-5">
                                    <select name="items[0][product_id]" class="form-control product-select" required>
                                        <option value="">Select Product</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input type="number" name="items[0][quantity]" class="form-control item-qty" placeholder="Qty" step="0.01" required>
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
                                    <textarea name="notes" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <tr><td>Subtotal:</td><td class="text-end" id="return-subtotal">MYR 0.00</td></tr>
                                    <tr><td><strong>Total Refund:</strong></td><td class="text-end"><strong id="return-total">MYR 0.00</strong></td></tr>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveReturn()">Create Return</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Return Details Modal -->
    <div class="modal fade" id="return-details-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Return Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="return-details-content"></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    #returns-table_wrapper .dataTables_length, #returns-table_wrapper .dataTables_filter { display: none !important; }
</style>
<script>
var returnsTable, products = [], itemIndex = 0;

$(document).ready(function() {
    $.get('{{ route("pos.products") }}', function(r) { if (r.success) { products = r.products; populateProductSelects(); } });

    returnsTable = $('#returns-table').DataTable({
        processing: true, serverSide: true,
        ajax: { url: '{{ route("sales-returns") }}', type: 'GET', data: function(d) { d.customer = $('#filter-customer').val(); d.status = $('#filter-status').val(); } },
        columns: [
            { data: 'row_number', orderable: false, searchable: false },
            { data: 'product' }, { data: 'date' }, { data: 'customer' }, { data: 'status' },
            { data: 'grand_total' }, { data: 'paid' }, { data: 'due' }, { data: 'payment_status' },
            { data: 'action', orderable: false, searchable: false, className: 'action-table-data' }
        ],
        order: [[2, 'desc']],
        language: { info: "Showing _START_ - _END_ of _TOTAL_ Results", paginate: { previous: '<i class="fa fa-angle-left"></i>', next: '<i class="fa fa-angle-right"></i>' } },
        drawCallback: function() { if (typeof feather !== 'undefined') feather.replace(); }
    });

    $('#return-search').on('keyup', function() { returnsTable.search(this.value).draw(); });
    $('#add-item-btn').on('click', function() {
        itemIndex++;
        var newRow = `<div class="row item-row mb-2">
            <div class="col-md-5"><select name="items[${itemIndex}][product_id]" class="form-control product-select" required><option value="">Select Product</option></select></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][quantity]" class="form-control item-qty" placeholder="Qty" step="0.01" required></div>
            <div class="col-md-2"><input type="number" name="items[${itemIndex}][price]" class="form-control item-price" placeholder="Price" step="0.01" required></div>
            <div class="col-md-2"><input type="text" class="form-control item-subtotal" placeholder="Subtotal" readonly></div>
            <div class="col-md-1"><button type="button" class="btn btn-danger btn-sm remove-item"><i class="fa fa-times"></i></button></div>
        </div>`;
        $('#return-items').append(newRow);
        populateProductSelects();
    });
    $(document).on('click', '.remove-item', function() { $(this).closest('.item-row').remove(); calculateTotals(); });
    $(document).on('change', '.product-select', function() {
        var p = products.find(x => x.id == $(this).val());
        if (p) { var row = $(this).closest('.item-row'); row.find('.item-price').val(p.price); row.find('.item-qty').val(1); calculateRowSubtotal(row); }
    });
    $(document).on('input', '.item-qty, .item-price', function() { calculateRowSubtotal($(this).closest('.item-row')); });
});

function populateProductSelects() {
    $('.product-select').each(function() {
        var s = $(this), v = s.val();
        s.find('option:not(:first)').remove();
        products.forEach(p => s.append(`<option value="${p.id}">${p.name} (MYR ${parseFloat(p.price).toFixed(2)})</option>`));
        if (v) s.val(v);
    });
}
function calculateRowSubtotal(row) {
    var sub = (parseFloat(row.find('.item-qty').val()) || 0) * (parseFloat(row.find('.item-price').val()) || 0);
    row.find('.item-subtotal').val('MYR ' + sub.toFixed(2));
    calculateTotals();
}
function calculateTotals() {
    var subtotal = 0;
    $('.item-row').each(function() { subtotal += (parseFloat($(this).find('.item-qty').val()) || 0) * (parseFloat($(this).find('.item-price').val()) || 0); });
    $('#return-subtotal').text('MYR ' + subtotal.toFixed(2));
    $('#return-total').text('MYR ' + subtotal.toFixed(2));
}
function applyFilters() { returnsTable.ajax.reload(); }

function saveReturn() {
    var items = [];
    $('.item-row').each(function() {
        var pid = $(this).find('.product-select').val();
        if (pid) items.push({ product_id: pid, quantity: $(this).find('.item-qty').val(), price: $(this).find('.item-price').val() });
    });
    if (!items.length) { Swal.fire('Error', 'Add at least one item', 'error'); return; }
    if (!$('textarea[name="reason"]').val()) { Swal.fire('Error', 'Please provide a reason for return', 'error'); return; }
    $.ajax({
        url: '{{ route("sales-returns.store") }}', type: 'POST',
        data: { _token: '{{ csrf_token() }}', order_id: $('#order-select').val(), customer_name: $('input[name="customer_name"]').val(),
            reason: $('textarea[name="reason"]').val(), notes: $('textarea[name="notes"]').val(), items: items },
        success: function(r) {
            if (r.success) { Swal.fire('Success', r.message, 'success'); $('#add-return-modal').modal('hide'); $('#return-form')[0].reset(); returnsTable.ajax.reload(); }
            else Swal.fire('Error', r.message, 'error');
        },
        error: function(x) { Swal.fire('Error', x.responseJSON?.message || 'Error', 'error'); }
    });
}

function viewReturn(id) {
    $.get('{{ url("sales-returns") }}/' + id, function(r) {
        if (r.success) {
            var ret = r.return;
            var html = `<div class="row mb-3"><div class="col-md-6"><p><strong>Return #:</strong> ${ret.return_number}</p><p><strong>Customer:</strong> ${ret.customer_name || 'Walk in Customer'}</p><p><strong>Status:</strong> ${ret.status}</p><p><strong>Reason:</strong> ${ret.reason || '-'}</p></div><div class="col-md-6 text-end"><p><strong>Date:</strong> ${new Date(ret.created_at).toLocaleDateString()}</p><p><strong>Total:</strong> MYR ${parseFloat(ret.total).toFixed(2)}</p><p><strong>Refunded:</strong> MYR ${parseFloat(ret.amount_refunded).toFixed(2)}</p></div></div><hr><table class="table table-bordered"><thead><tr><th>Product</th><th class="text-end">Qty</th><th class="text-end">Price</th><th class="text-end">Subtotal</th></tr></thead><tbody>`;
            ret.items.forEach(i => html += `<tr><td>${i.product_name}</td><td class="text-end">${i.quantity}</td><td class="text-end">MYR ${parseFloat(i.price).toFixed(2)}</td><td class="text-end">MYR ${parseFloat(i.subtotal).toFixed(2)}</td></tr>`);
            html += '</tbody></table>';
            $('#return-details-content').html(html);
            $('#return-details-modal').modal('show');
        }
    });
}

function editReturn(id) { viewReturn(id); }

function deleteReturn(id) {
    Swal.fire({ title: 'Are you sure?', text: "You won't be able to revert this!", icon: 'warning', showCancelButton: true, confirmButtonText: 'Yes, delete!' })
    .then(r => {
        if (r.isConfirmed) {
            $.ajax({ url: '{{ url("sales-returns") }}/' + id, type: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, success: function(r) {
                if (r.success) { Swal.fire('Deleted!', r.message, 'success'); returnsTable.ajax.reload(); } else Swal.fire('Error', r.message, 'error');
            }});
        }
    });
}
</script>
@endpush
