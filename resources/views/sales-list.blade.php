<?php $page = 'sales-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Sales List
                @endslot
                @slot('li_1')
                    Manage Your Sales
                @endslot
            @endcomponent

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" id="sales-search" placeholder="Search..." class="form-control form-control-sm">
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
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">Choose Status</option>
                                            <option value="completed">Completed</option>
                                            <option value="pending">Pending</option>
                                            <option value="processing">Processing</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="file-text" class="info-img"></i>
                                        <input type="text" id="filter-reference" placeholder="Enter Reference" class="form-control">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-payment">
                                            <option value="">Choose Payment Status</option>
                                            <option value="paid">Paid</option>
                                            <option value="pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto" onclick="applyFilters()"> <i data-feather="search" class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table" id="sales-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Reference</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Payment Status</th>
                                    <th>Biller</th>
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

    <!-- Sale Details Modal -->
    <div class="modal fade" id="sale-details-modal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sale Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="sale-details-content">
                    <!-- Content loaded via AJAX -->
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    /* Hide DataTables default elements */
    #sales-table_wrapper .dataTables_length,
    #sales-table_wrapper .dataTables_filter {
        display: none !important;
    }
</style>
<script>
var salesTable;
$(document).ready(function() {
    // Initialize DataTable
    salesTable = $('#sales-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("sales-list") }}',
            type: 'GET',
            data: function(d) {
                d.customer = $('#filter-customer').val();
                d.status = $('#filter-status').val();
                d.payment_status = $('#filter-payment').val();
                d.reference = $('#filter-reference').val();
            }
        },
        columns: [
            { data: 'row_number', orderable: false, searchable: false },
            { data: 'customer' },
            { data: 'reference' },
            { data: 'date' },
            { data: 'status' },
            { data: 'grand_total' },
            { data: 'paid' },
            { data: 'due' },
            { data: 'payment_status' },
            { data: 'biller' },
            { data: 'action', orderable: false, searchable: false, className: 'action-table-data' }
        ],
        order: [[3, 'desc']],
        language: {
            info: "Showing _START_ - _END_ of _TOTAL_ Results",
            paginate: {
                previous: '<i class="fa fa-angle-left"></i>',
                next: '<i class="fa fa-angle-right"></i>'
            }
        },
        drawCallback: function() {
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }
    });

    // Custom search
    $('#sales-search').on('keyup', function() {
        salesTable.search(this.value).draw();
    });
});

function applyFilters() {
    salesTable.ajax.reload();
}

function viewSale(id) {
    $.get('{{ url("sales") }}/' + id, function(response) {
        if (response.success) {
            var order = response.order;
            var html = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Order Number:</strong> ${order.order_number}</p>
                        <p><strong>Customer:</strong> ${order.customer_name || 'Walk in Customer'}</p>
                        <p><strong>Email:</strong> ${order.customer_email || '-'}</p>
                        <p><strong>Phone:</strong> ${order.customer_phone || '-'}</p>
                    </div>
                    <div class="col-md-6 text-end">
                        <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                        <p><strong>Status:</strong> ${order.order_status}</p>
                        <p><strong>Payment:</strong> ${order.payment_status}</p>
                        <p><strong>Method:</strong> ${order.payment_method}</p>
                    </div>
                </div>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Price</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>`;
            
            order.items.forEach(function(item) {
                html += `<tr>
                    <td>${item.product_name}</td>
                    <td>${item.product_sku || '-'}</td>
                    <td class="text-end">${item.quantity}</td>
                    <td class="text-end">MYR ${parseFloat(item.price).toFixed(2)}</td>
                    <td class="text-end">MYR ${parseFloat(item.subtotal).toFixed(2)}</td>
                </tr>`;
            });
            
            html += `</tbody>
                <tfoot>
                    <tr><td colspan="4" class="text-end"><strong>Subtotal:</strong></td><td class="text-end">MYR ${parseFloat(order.subtotal).toFixed(2)}</td></tr>
                    <tr><td colspan="4" class="text-end"><strong>Tax:</strong></td><td class="text-end">MYR ${parseFloat(order.tax).toFixed(2)}</td></tr>
                    <tr><td colspan="4" class="text-end"><strong>Discount:</strong></td><td class="text-end">MYR ${parseFloat(order.discount).toFixed(2)}</td></tr>
                    <tr><td colspan="4" class="text-end"><strong>Total:</strong></td><td class="text-end"><strong>MYR ${parseFloat(order.total).toFixed(2)}</strong></td></tr>
                </tfoot>
            </table>`;
            
            $('#sale-details-content').html(html);
            $('#sale-details-modal').modal('show');
        }
    });
}

function printSale(id) {
    window.open('{{ url("sales") }}/' + id + '?print=1', '_blank');
}

function deleteSale(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ url("sales") }}/' + id,
                type: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Deleted!', response.message, 'success');
                        salesTable.ajax.reload();
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error!', 'Something went wrong', 'error');
                }
            });
        }
    });
}
</script>
@endpush
