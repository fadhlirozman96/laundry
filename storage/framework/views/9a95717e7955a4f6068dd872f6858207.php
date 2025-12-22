<?php $page = 'customers'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Customer List
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Manage your Customers
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    javascript:void(0);
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_3'); ?>
                    Add New Customer
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Customer list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" placeholder="Search..." class="form-control" id="customer-search">
                                <a href="javascript:void(0);" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="<?php echo e(URL::asset('/build/img/icons/closes.svg')); ?>" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select" id="sort-by">
                                <option value="desc">Newest</option>
                                <option value="asc">Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!-- Filter -->
                    <div class="card mb-0" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="globe" class="info-img"></i>
                                        <input type="text" class="form-control" id="filter-country" placeholder="Search by Country">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto" onclick="customerTable.ajax.reload()">
                                            <i data-feather="search" class="feather-search"></i> Search
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table" id="customer-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Customer Name</th>
                                    <th>Code</th>
                                    <th>Customer</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Country</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /Customer list -->
        </div>
    </div>

    <!-- Add Customer Modal -->
    <div class="modal fade" id="add-customer-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-customer-form">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="add-name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="add-email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" id="add-phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country" id="add-country">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" id="add-city">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="add-address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="edit-customer-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-customer-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" id="edit-id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="edit-name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" id="edit-email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" id="edit-phone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <input type="text" class="form-control" name="country" id="edit-country">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">City</label>
                                <input type="text" class="form-control" name="city" id="edit-city">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="address" id="edit-address" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Customer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Customer Modal -->
    <div class="modal fade" id="view-customer-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Name</label>
                            <p class="fw-bold" id="view-name"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="fw-bold" id="view-email"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Phone</label>
                            <p class="fw-bold" id="view-phone"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Country</label>
                            <p class="fw-bold" id="view-country"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">City</label>
                            <p class="fw-bold" id="view-city"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Store</label>
                            <p class="fw-bold" id="view-store"></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted">Address</label>
                            <p class="fw-bold" id="view-address"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Total Orders</label>
                            <p class="fw-bold" id="view-orders"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted">Created On</label>
                            <p class="fw-bold" id="view-created"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
    #customer-table_wrapper .dataTables_length,
    #customer-table_wrapper .dataTables_filter { display: none !important; }
</style>
<script>
var customerTable;

$(document).ready(function() {
    // Initialize DataTable
    customerTable = $('#customer-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route("customers")); ?>',
            type: 'GET'
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'customer_name' },
            { data: 'code' },
            { data: 'customer' },
            { data: 'email' },
            { data: 'phone' },
            { data: 'country' },
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
    $('#customer-search').on('keyup', function() {
        customerTable.search(this.value).draw();
    });

    // Add Customer Form Submit
    $('#add-customer-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo e(route("customers.store")); ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-customer-modal').modal('hide');
                    $('#add-customer-form')[0].reset();
                    customerTable.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var msg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Error', msg, 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });

    // Edit Customer Form Submit
    $('#edit-customer-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-id').val();
        
        $.ajax({
            url: '/customers/' + id,
            type: 'PUT',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                name: $('#edit-name').val(),
                email: $('#edit-email').val(),
                phone: $('#edit-phone').val(),
                country: $('#edit-country').val(),
                city: $('#edit-city').val(),
                address: $('#edit-address').val()
            },
            success: function(response) {
                if (response.success) {
                    $('#edit-customer-modal').modal('hide');
                    customerTable.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var msg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Error', msg, 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                }
            }
        });
    });

    // Add button click - using the breadcrumb button
    $(document).on('click', 'a[href="javascript:void(0);"].btn-added', function(e) {
        e.preventDefault();
        $('#add-customer-modal').modal('show');
    });
});

// View Customer
function viewCustomer(id) {
    $.get('/customers/' + id, function(response) {
        if (response.success) {
            var c = response.customer;
            $('#view-name').text(c.name);
            $('#view-email').text(c.email || 'N/A');
            $('#view-phone').text(c.phone || 'N/A');
            $('#view-country').text(c.country || 'N/A');
            $('#view-city').text(c.city || 'N/A');
            $('#view-address').text(c.address || 'N/A');
            $('#view-store').text(c.store ? c.store.name : 'N/A');
            $('#view-orders').text((c.orders ? c.orders.length : 0) + ' orders');
            $('#view-created').text(new Date(c.created_at).toLocaleDateString());
            $('#view-customer-modal').modal('show');
        }
    }).fail(function() {
        Swal.fire('Error', 'Failed to load customer', 'error');
    });
}

// Edit Customer
function editCustomer(id) {
    $.get('/customers/' + id, function(response) {
        if (response.success) {
            var c = response.customer;
            $('#edit-id').val(c.id);
            $('#edit-name').val(c.name);
            $('#edit-email').val(c.email || '');
            $('#edit-phone').val(c.phone || '');
            $('#edit-country').val(c.country || '');
            $('#edit-city').val(c.city || '');
            $('#edit-address').val(c.address || '');
            $('#edit-customer-modal').modal('show');
        }
    }).fail(function() {
        Swal.fire('Error', 'Failed to load customer', 'error');
    });
}

// Delete Customer
function deleteCustomer(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/customers/' + id,
                type: 'DELETE',
                data: { _token: '<?php echo e(csrf_token()); ?>' },
                success: function(response) {
                    if (response.success) {
                        customerTable.ajax.reload();
                        Swal.fire('Deleted!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete customer', 'error');
                }
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/customers.blade.php ENDPATH**/ ?>