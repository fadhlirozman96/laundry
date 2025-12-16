<?php $page = 'expense-category'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Expense Category
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Manage Your Expense Categories
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    <a href="javascript:void(0);" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add-category">
                        <i data-feather="plus-circle" class="me-1"></i> Add Category
                    </a>
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fa fa-info-circle me-1"></i>
                Expense categories are specific to the currently selected store.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" id="category-search" placeholder="Search..." class="form-control form-control-sm">
                                <a href="" class="btn btn-searchset"><i data-feather="search" class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="<?php echo e(URL::asset('/build/img/icons/closes.svg')); ?>" alt="img"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="category-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="add-category" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="add-category-form">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="add-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="add-description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" id="add-is-active" class="form-check-input" checked>
                            <label class="form-check-label" for="add-is-active">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">Save</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="edit-category" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Expense Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="edit-category-form">
                        <input type="hidden" id="edit-id">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit-name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="edit-description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" name="is_active" id="edit-is-active" class="form-check-input">
                            <label class="form-check-label" for="edit-is-active">Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="updateCategory()">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
    #category-table_wrapper .dataTables_length, #category-table_wrapper .dataTables_filter { display: none !important; }
    .edit-delete-action { display: flex; align-items: center; gap: 5px; }
    .edit-delete-action a {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px; transition: all 0.3s ease;
    }
    .edit-delete-action a.action-edit { background-color: rgba(255, 193, 7, 0.1); }
    .edit-delete-action a.action-edit:hover { background-color: rgba(255, 193, 7, 0.2); }
    .edit-delete-action a.action-delete { background-color: rgba(220, 53, 69, 0.1); }
    .edit-delete-action a.action-delete:hover { background-color: rgba(220, 53, 69, 0.2); }
    .badge-linesuccess {
        background-color: transparent;
        border: 1px solid #28a745;
        color: #28a745;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .badge-linedanger {
        background-color: transparent;
        border: 1px solid #dc3545;
        color: #dc3545;
        font-size: 12px;
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 5px;
    }
</style>
<script>
    $(document).ready(function() {
        var categoryTable = $('#category-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "<?php echo e(route('expense-category')); ?>",
                data: function (d) {
                    d.search = $('#category-search').val();
                }
            },
            columns: [
                { data: 'row_number', name: 'row_number', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'description', name: 'description' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'asc']],
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

        $('#category-search').on('keyup', function() {
            categoryTable.search(this.value).draw();
        });

        window.categoryTable = categoryTable;
    });

    function saveCategory() {
        var data = {
            _token: '<?php echo e(csrf_token()); ?>',
            name: $('#add-name').val(),
            description: $('#add-description').val(),
            is_active: $('#add-is-active').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: "<?php echo e(route('expense-categories.store')); ?>",
            type: "POST",
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#add-category').modal('hide');
                    $('#add-category-form')[0].reset();
                    window.categoryTable.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    function editCategory(id) {
        $.ajax({
            url: "<?php echo e(url('expense-categories')); ?>/" + id,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    $('#edit-id').val(response.category.id);
                    $('#edit-name').val(response.category.name);
                    $('#edit-description').val(response.category.description);
                    $('#edit-is-active').prop('checked', response.category.is_active);
                    $('#edit-category').modal('show');
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Failed to fetch category details.', 'error');
            }
        });
    }

    function updateCategory() {
        var id = $('#edit-id').val();
        var data = {
            _token: '<?php echo e(csrf_token()); ?>',
            _method: 'PUT',
            name: $('#edit-name').val(),
            description: $('#edit-description').val(),
            is_active: $('#edit-is-active').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: "<?php echo e(url('expense-categories')); ?>/" + id,
            type: "POST",
            data: data,
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success');
                    $('#edit-category').modal('hide');
                    window.categoryTable.ajax.reload();
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                var errorMsg = 'An error occurred. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                Swal.fire('Error', errorMsg, 'error');
            }
        });
    }

    function deleteCategory(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the category and all related expenses!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "<?php echo e(url('expense-categories')); ?>/" + id,
                    type: "DELETE",
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success');
                            window.categoryTable.ajax.reload();
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to delete category.', 'error');
                    }
                });
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/expense-category.blade.php ENDPATH**/ ?>