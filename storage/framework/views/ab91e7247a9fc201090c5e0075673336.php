
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Details</h4>
                <h6><?php echo e($user->name); ?></h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('superadmin.users.index')); ?>" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>User Information</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> <?php echo e($user->name); ?></p>
                        <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                        <p><strong>Business:</strong> <?php echo e($user->business->name ?? 'N/A'); ?></p>
                        <p><strong>Roles:</strong> 
                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-secondary"><?php echo e($role->name); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </p>
                        <p><strong>Status:</strong> 
                            <?php if($user->is_active): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>Joined:</strong> <?php echo e($user->created_at->format('d M Y H:i')); ?></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Stores Access</h5>
                    </div>
                    <div class="card-body">
                        <?php $__empty_1 = true; $__currentLoopData = $user->stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <p><i data-feather="check-circle" class="text-success"></i> <?php echo e($store->store_name); ?></p>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted">No stores assigned</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/users/show.blade.php ENDPATH**/ ?>