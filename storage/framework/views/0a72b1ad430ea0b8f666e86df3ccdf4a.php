
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Store Details</h4>
                <h6><?php echo e($store->name); ?></h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('superadmin.store-containers.index')); ?>" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <p><strong>Store Name:</strong> <?php echo e($store->name); ?></p>
                        <p><strong>Business:</strong> <?php echo e($store->owner->name ?? 'N/A'); ?></p>
                        <p><strong>Status:</strong> 
                            <?php if($store->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-warning">Inactive</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <p><strong>Created:</strong> <?php echo e($store->created_at->format('d M Y')); ?></p>
                        <p><strong>Users:</strong> <?php echo e($store->users->count()); ?></p>
                    </div>
                </div>

                <hr>

                <h5>Assigned Users</h5>
                <div class="table-responsive mt-3">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $store->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->email); ?></td>
                                <td>
                                    <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-secondary"><?php echo e($role->name); ?></span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3" class="text-center">No users assigned</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/store-containers/show.blade.php ENDPATH**/ ?>