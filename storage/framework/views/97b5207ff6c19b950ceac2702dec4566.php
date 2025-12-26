
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Feature Gating</h4>
                <h6>Manage feature access per plan</h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('superadmin.features.logs')); ?>" class="btn btn-info">
                    <i data-feather="list"></i> View Access Logs
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <th class="text-center">
                                    <?php echo e($plan->name); ?><br>
                                    <small class="text-muted">MYR <?php echo e(number_format($plan->price, 0)); ?>/mo</small>
                                </th>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $allFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $featureName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><strong><?php echo e($featureName); ?></strong></td>
                                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <td class="text-center">
                                    <?php if($plan->hasFeature($key)): ?>
                                        <span class="badge bg-success">
                                            <i data-feather="check"></i> Enabled
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">
                                            <i data-feather="x"></i> Disabled
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4">
                    <i data-feather="info"></i>
                    <strong>Feature Gating is Active:</strong> Features are automatically checked based on the user's subscription plan. Access attempts are logged for auditing.
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/features/index.blade.php ENDPATH**/ ?>