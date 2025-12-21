<?php $page = 'category-list'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Category
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Manage your categories
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    javascript:void(0);
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_3'); ?>
                    Add New Category
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Category list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <input type="text" placeholder="Search..." class="form-control" id="category-search">
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
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto" onclick="categoryTable.ajax.reload()">
                                            <i data-feather="search" class="feather-search"></i> Search
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table" id="category-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Category</th>
                                    <th>Category slug</th>
                                    <th>Created On</th>
                                    <th>Status</th>
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
            <!-- /Category list -->
        </div>
    </div>

    <!-- Add Category Modal -->
    <div class="modal fade" id="add-category-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-category-form">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="add-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="add-description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="edit-category-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-category-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" id="edit-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="edit-is-active" name="is_active" value="1">
                                <label class="form-check-label" for="edit-is-active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<style>
    #category-table_wrapper .dataTables_length,
    #category-table_wrapper .dataTables_filter { display: none !important; }
</style>
<script>
var categoryTable;

$(document).ready(function() {
    // Initialize DataTable
    categoryTable = $('#category-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '<?php echo e(route("category-list")); ?>',
            type: 'GET'
        },
        columns: [
            { data: 'checkbox', orderable: false, searchable: false },
            { data: 'name' },
            { data: 'slug' },
            { data: 'created_at' },
            { data: 'status', orderable: false },
            { data: 'action', orderable: false, searchable: false, className: 'action-table-data' }
        ],
        order: [[3, 'desc']],
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
    $('#category-search').on('keyup', function() {
        categoryTable.search(this.value).draw();
    });

    // Add Category Form Submit
    $('#add-category-form').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: '<?php echo e(route("categories.store")); ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-category-modal').modal('hide');
                    $('#add-category-form')[0].reset();
                    categoryTable.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var msg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Error', msg, 'error');
                } else {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            }
        });
    });

    // Edit Category Form Submit
    $('#edit-category-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-id').val();
        
        $.ajax({
            url: '/categories/' + id,
            type: 'PUT',
            data: {
                _token: '<?php echo e(csrf_token()); ?>',
                name: $('#edit-name').val(),
                description: $('#edit-description').val(),
                is_active: $('#edit-is-active').is(':checked') ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    $('#edit-category-modal').modal('hide');
                    categoryTable.ajax.reload();
                    Swal.fire('Success', response.message, 'success');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    var msg = Object.values(errors).flat().join('<br>');
                    Swal.fire('Error', msg, 'error');
                } else {
                    Swal.fire('Error', 'Something went wrong', 'error');
                }
            }
        });
    });

    // Add button click - using the breadcrumb button
    $(document).on('click', 'a[href="javascript:void(0);"].btn-added', function(e) {
        e.preventDefault();
        $('#add-category-modal').modal('show');
    });
});

// Edit Category
function editCategory(id) {
    $.get('/categories/' + id, function(response) {
        if (response.success) {
            var cat = response.category;
            $('#edit-id').val(cat.id);
            $('#edit-name').val(cat.name);
            $('#edit-description').val(cat.description || '');
            $('#edit-is-active').prop('checked', cat.is_active);
            $('#edit-category-modal').modal('show');
        }
    }).fail(function() {
        Swal.fire('Error', 'Failed to load category', 'error');
    });
}

// Delete Category
function deleteCategory(id) {
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
                url: '/categories/' + id,
                type: 'DELETE',
                data: { _token: '<?php echo e(csrf_token()); ?>' },
                success: function(response) {
                    if (response.success) {
                        categoryTable.ajax.reload();
                        Swal.fire('Deleted!', response.message, 'success');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete category', 'error');
                }
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/category-list.blade.php ENDPATH**/ ?>