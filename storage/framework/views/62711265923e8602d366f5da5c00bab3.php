<?php $page = 'laundry-garments'; ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Garment Types</h4>
                <h6>Configure garment types and pricing</h6>
            </div>
            <div class="page-btn">
                <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-garment-modal">
                    <i data-feather="plus-circle" class="me-2"></i>Add Garment Type
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datanew">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Default Price (MYR)</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $garmentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($type->name); ?></strong></td>
                                <td><?php echo e($type->category ?? '-'); ?></td>
                                <td>MYR <?php echo e(number_format($type->default_price, 2)); ?></td>
                                <td>
                                    <?php if($type->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2" href="javascript:void(0);" 
                                           onclick="editGarment(<?php echo e($type->id); ?>, '<?php echo e(addslashes($type->name)); ?>', '<?php echo e(addslashes($type->category)); ?>', '<?php echo e(addslashes($type->description)); ?>', <?php echo e($type->default_price); ?>, <?php echo e($type->is_active ? 1 : 0); ?>)">
                                            <i data-feather="edit" class="feather-edit"></i>
                                        </a>
                                        <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteGarment(<?php echo e($type->id); ?>)">
                                            <i data-feather="trash-2" class="feather-trash-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Garment Modal -->
<div class="modal fade" id="add-garment-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Garment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="add-garment-form">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="e.g. Shirt, Pants, Blanket">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control" name="category">
                            <option value="">Select Category</option>
                            <option value="Tops">Tops</option>
                            <option value="Bottoms">Bottoms</option>
                            <option value="Outerwear">Outerwear</option>
                            <option value="Undergarments">Undergarments</option>
                            <option value="Bedding">Bedding</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Price (MYR) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="default_price" required step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Garment Type</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Garment Modal -->
<div class="modal fade" id="edit-garment-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Garment Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="edit-garment-form">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="garment_id" id="edit_garment_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-control" name="category" id="edit_category">
                            <option value="">Select Category</option>
                            <option value="Tops">Tops</option>
                            <option value="Bottoms">Bottoms</option>
                            <option value="Outerwear">Outerwear</option>
                            <option value="Undergarments">Undergarments</option>
                            <option value="Bedding">Bedding</option>
                            <option value="Accessories">Accessories</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Default Price (MYR) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="default_price" id="edit_price" required step="0.01" min="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function editGarment(id, name, category, description, price, isActive) {
        $('#edit_garment_id').val(id);
        $('#edit_name').val(name);
        $('#edit_category').val(category);
        $('#edit_description').val(description);
        $('#edit_price').val(price);
        $('#edit_is_active').prop('checked', isActive == 1);
        $('#edit-garment-modal').modal('show');
    }

    function deleteGarment(id) {
        Swal.fire({
            title: 'Delete Garment Type?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Yes, delete'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/laundry/garment-types/' + id,
                    type: 'DELETE',
                    data: { _token: '<?php echo e(csrf_token()); ?>' },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
                    }
                });
            }
        });
    }

    $('#add-garment-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo e(route("laundry.garment-types.store")); ?>',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                $('#add-garment-modal').modal('hide');
                Swal.fire('Success', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
            }
        });
    });

    $('#edit-garment-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit_garment_id').val();
        $.ajax({
            url: '/laundry/garment-types/' + id,
            type: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#edit-garment-modal').modal('hide');
                Swal.fire('Success', response.message, 'success').then(() => location.reload());
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
            }
        });
    });

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/laundry/garment-types.blade.php ENDPATH**/ ?>