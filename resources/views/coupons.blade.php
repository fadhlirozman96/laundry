<?php $page = 'coupons'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="add-item d-flex">
                <div class="page-title">
                    <h4>Coupons</h4>
                    <h6>Manage Your Coupons</h6>
                </div>
            </div>
            <ul class="table-top-head">
                <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img src="{{ URL::asset('/build/img/icons/pdf.svg') }}" alt="img"></a></li>
                <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img src="{{ URL::asset('/build/img/icons/excel.svg') }}" alt="img"></a></li>
                <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer" class="feather-rotate-ccw"></i></a></li>
                <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" onclick="location.reload();"><i data-feather="rotate-ccw" class="feather-rotate-ccw"></i></a></li>
                <li><a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i data-feather="chevron-up" class="feather-chevron-up"></i></a></li>
            </ul>
            <div class="page-btn">
                <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-coupon-modal">
                    <i data-feather="plus-circle" class="me-2"></i>Add New Coupon
                </a>
            </div>
        </div>

        @if(session('selected_store_id'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i data-feather="info" class="me-2" style="width:16px;height:16px;"></i>
            Coupons shown are specific to the currently selected store. Each coupon can only be used at the store it was created for.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @else
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i data-feather="alert-triangle" class="me-2" style="width:16px;height:16px;"></i>
            Please select a store to manage coupons.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        @endif

        <div class="card table-list-card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <div class="search-input">
                            <input type="text" id="coupon-search" placeholder="Search..." class="form-control form-control-sm">
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
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </div>
                </div>

                <!-- Filter -->
                <div class="card" id="filter_inputs">
                    <div class="card-body pb-0">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="input-blocks">
                                    <i data-feather="box" class="info-img"></i>
                                    <select class="select" id="filter-type">
                                        <option value="">Choose Type</option>
                                        <option value="fixed">Fixed</option>
                                        <option value="percentage">Percentage</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="input-blocks">
                                    <i data-feather="check-circle" class="info-img"></i>
                                    <select class="select" id="filter-status">
                                        <option value="">Choose Status</option>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                <div class="input-blocks">
                                    <a class="btn btn-filters ms-auto" onclick="applyFilters()">
                                        <i data-feather="search" class="feather-search"></i> Search
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table" id="coupon-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Discount</th>
                                <th>Limit</th>
                                <th>Used</th>
                                <th>Valid Until</th>
                                <th>Status</th>
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

<!-- Add Coupon Modal -->
<div class="modal fade" id="add-coupon-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="add-coupon-form">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="code" class="form-control" required style="text-transform: uppercase;">
                                    <button type="button" class="btn btn-outline-primary" onclick="generateCode('add')">Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" class="form-select" required>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Discount <span class="text-danger">*</span></label>
                                <input type="number" name="discount" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Usage Limit</label>
                                <input type="number" name="limit" class="form-control" min="1" placeholder="Leave empty for unlimited">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Minimum Purchase (MYR)</label>
                                <input type="number" name="min_purchase" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Valid From</label>
                                <input type="date" name="valid_from" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Valid Until</label>
                                <input type="date" name="valid_until" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Max Discount (MYR)</label>
                                <input type="number" name="max_discount" class="form-control" step="0.01" min="0" placeholder="For percentage type">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 pt-4">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="add-is-active" checked>
                                    <label class="form-check-label" for="add-is-active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Coupon Modal -->
<div class="modal fade" id="edit-coupon-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Coupon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="edit-coupon-form">
                @csrf
                @method('PUT')
                <input type="hidden" name="coupon_id" id="edit-coupon-id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coupon Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="code" id="edit-code" class="form-control" required style="text-transform: uppercase;">
                                    <button type="button" class="btn btn-outline-primary" onclick="generateCode('edit')">Generate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Type <span class="text-danger">*</span></label>
                                <select name="type" id="edit-type" class="form-select" required>
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Discount <span class="text-danger">*</span></label>
                                <input type="number" name="discount" id="edit-discount" class="form-control" step="0.01" min="0" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Usage Limit</label>
                                <input type="number" name="limit" id="edit-limit" class="form-control" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Minimum Purchase (MYR)</label>
                                <input type="number" name="min_purchase" id="edit-min-purchase" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Valid From</label>
                                <input type="date" name="valid_from" id="edit-valid-from" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Valid Until</label>
                                <input type="date" name="valid_until" id="edit-valid-until" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Max Discount (MYR)</label>
                                <input type="number" name="max_discount" id="edit-max-discount" class="form-control" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3 pt-4">
                                <div class="form-check">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="edit-is-active">
                                    <label class="form-check-label" for="edit-is-active">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit-description" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Coupon</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<style>
    #coupon-table_wrapper .dataTables_length,
    #coupon-table_wrapper .dataTables_filter { display: none !important; }
    .table-top .search-set label { display: none !important; }
</style>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var couponTable = $('#coupon-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("coupons") }}',
            type: 'GET'
        },
        columns: [
            { data: 'row_number', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'code' },
            { data: 'type' },
            { data: 'discount' },
            { data: 'limit' },
            { data: 'used' },
            { data: 'valid' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false, className: 'action-table-data' }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        language: {
            info: "Showing _START_ - _END_ of _TOTAL_ Results",
            paginate: {
                previous: '<i class="fa fa-angle-left"></i>',
                next: '<i class="fa fa-angle-right"></i>'
            }
        },
        drawCallback: function() {
            if (typeof feather !== 'undefined') feather.replace();
        }
    });

    // Search
    $('#coupon-search').on('keyup', function() {
        couponTable.search(this.value).draw();
    });

    // Add Coupon Form
    $('#add-coupon-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '{{ route("coupons.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-coupon-modal').modal('hide');
                    $('#add-coupon-form')[0].reset();
                    couponTable.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var errorMsg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Error', errorMsg, 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });

    // Edit Coupon Form
    $('#edit-coupon-form').on('submit', function(e) {
        e.preventDefault();
        var couponId = $('#edit-coupon-id').val();
        
        $.ajax({
            url: '/coupons/' + couponId,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#edit-coupon-modal').modal('hide');
                    couponTable.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var errorMsg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Error', errorMsg, 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });
});

function generateCode(type) {
    var name = '';
    if (type === 'add') {
        name = $('#add-coupon-form input[name="name"]').val().trim();
    } else {
        name = $('#edit-name').val().trim();
    }
    
    if (!name) {
        Swal.fire('Info', 'Please enter coupon name first', 'info');
        return;
    }
    
    $.get('{{ route("coupons.generate-code") }}', { name: name }, function(response) {
        if (response.success) {
            if (type === 'add') {
                $('#add-coupon-form input[name="code"]').val(response.code);
            } else {
                $('#edit-code').val(response.code);
            }
        }
    });
}

function editCoupon(id) {
    $.get('/coupons/' + id, function(response) {
        if (response.success) {
            var c = response.coupon;
            $('#edit-coupon-id').val(c.id);
            $('#edit-name').val(c.name);
            $('#edit-code').val(c.code);
            $('#edit-type').val(c.type);
            $('#edit-discount').val(c.discount);
            $('#edit-limit').val(c.limit);
            $('#edit-min-purchase').val(c.min_purchase);
            $('#edit-valid-from').val(c.valid_from ? c.valid_from.split('T')[0] : '');
            $('#edit-valid-until').val(c.valid_until ? c.valid_until.split('T')[0] : '');
            $('#edit-max-discount').val(c.max_discount);
            $('#edit-is-active').prop('checked', c.is_active);
            $('#edit-description').val(c.description);
            $('#edit-coupon-modal').modal('show');
        }
    });
}

function deleteCoupon(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This coupon will be deleted permanently!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/coupons/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        $('#coupon-table').DataTable().ajax.reload();
                        Swal.fire('Deleted!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete coupon', 'error');
                }
            });
        }
    });
}

function applyFilters() {
    // For now, reload table - can be extended for server-side filtering
    $('#coupon-table').DataTable().ajax.reload();
}
</script>
@endpush
