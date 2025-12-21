<?php $page = 'department-grid'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Department
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Manage your departments
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    Add New Department
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <div class="card">
                <div class="card-body pb-0">
                    <div class="table-top table-top-new">
                        <div class="search-set mb-0">
                            <div class="total-employees">
                                <h6><i data-feather="users" class="feather-user"></i>Total Departments <span><?php echo e($departments->count()); ?></span></h6>
                            </div>
                            <div class="search-input">
                                <input type="text" class="form-control" id="search-department" placeholder="Search...">
                            </div>
                        </div>
                        <div class="search-path d-flex align-items-center search-path-new">
                            <a href="<?php echo e(url('department-list')); ?>" class="btn-list"><i data-feather="list" class="feather-user"></i></a>
                            <a href="<?php echo e(url('department-grid')); ?>" class="btn-grid active"><i data-feather="grid" class="feather-user"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="employee-grid-widget">
                <div class="row" id="departments-container">
                    <?php $__empty_1 = true; $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 department-card">
                        <div class="employee-grid-profile">
                            <div class="profile-head">
                                <div class="dep-name">
                                    <h5 class="<?php echo e($department->is_active ? 'active' : 'inactive'); ?>"><?php echo e($department->name); ?></h5>
                                </div>
                                <div class="profile-head-action">
                                    <div class="dropdown profile-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical" class="feather-user"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item edit-department" data-id="<?php echo e($department->id); ?>">
                                                    <i data-feather="edit" class="info-img"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item delete-department mb-0" data-id="<?php echo e($department->id); ?>">
                                                    <i data-feather="trash-2" class="info-img"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-info department-profile-info">
                                <div class="profile-pic">
                                    <?php if($department->head && $department->head->profile_photo): ?>
                                        <img src="<?php echo e(asset('storage/' . $department->head->profile_photo)); ?>" alt="">
                                    <?php else: ?>
                                        <img src="<?php echo e(URL::asset('/build/img/users/user-01.jpg')); ?>" alt="">
                                    <?php endif; ?>
                                </div>
                                <h4><?php echo e($department->head ? $department->head->name : 'No Head Assigned'); ?></h4>
                            </div>
                            <ul class="team-members">
                                <li>
                                    Total Members: <?php echo e(str_pad($department->employees->count(), 2, '0', STR_PAD_LEFT)); ?>

                                </li>
                                <li>
                                    <ul>
                                        <?php $__currentLoopData = $department->employees->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a href="javascript:void(0);">
                                                <?php if($employee->profile_photo): ?>
                                                    <img src="<?php echo e(asset('storage/' . $employee->profile_photo)); ?>" alt="">
                                                <?php else: ?>
                                                    <img src="<?php echo e(URL::asset('/build/img/users/user-0' . (($index % 9) + 1) . '.jpg')); ?>" alt="">
                                                <?php endif; ?>
                                                <?php if($index == 3 && $department->employees->count() > 4): ?>
                                                    <span>+<?php echo e($department->employees->count() - 4); ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-info">No departments found. Click "Add New Department" to create one.</div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="add-department" tabindex="-1" aria-labelledby="addDepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentLabel">Add New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-department-form">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Head</label>
                            <select class="form-control select" name="head_id">
                                <option value="">Select Head</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="edit-department" tabindex="-1" aria-labelledby="editDepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDepartmentLabel">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-department-form">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="department_id" id="edit-department-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-department-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit-department-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Head</label>
                            <select class="form-control select" name="head_id" id="edit-department-head">
                                <option value="">Select Head</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control select" name="is_active" id="edit-department-status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script>
$(document).ready(function() {
    // Search functionality
    $('#search-department').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.department-card').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Add Department
    $('#add-department-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo e(route("departments.store")); ?>',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    // Edit Department
    $(document).on('click', '.edit-department', function() {
        var id = $(this).data('id');
        $.get('/departments/' + id, function(response) {
            if (response.success) {
                var dept = response.department;
                $('#edit-department-id').val(dept.id);
                $('#edit-department-name').val(dept.name);
                $('#edit-department-description').val(dept.description);
                $('#edit-department-head').val(dept.head_id);
                $('#edit-department-status').val(dept.is_active ? '1' : '0');
                $('#edit-department').modal('show');
            }
        });
    });

    $('#edit-department-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-department-id').val();
        $.ajax({
            url: '/departments/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    // Delete Department
    $(document).on('click', '.delete-department', function() {
        var id = $(this).data('id');
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
                    url: '/departments/' + id,
                    method: 'DELETE',
                    data: { _token: '<?php echo e(csrf_token()); ?>' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
                    }
                });
            }
        });
    });

    // Trigger add modal from breadcrumb button
    $('[data-bs-target="#add-department"]').on('click', function() {
        $('#add-department').modal('show');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/department-grid.blade.php ENDPATH**/ ?>