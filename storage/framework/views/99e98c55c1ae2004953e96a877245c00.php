
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Usage & Limits Tracking</h4>
                <h6>Monitor usage across all businesses</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business</th>
                                <th>Plan</th>
                                <th>Stores Usage</th>
                                <th>Users</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $usageData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $business = $data['business'];
                                $usage = $data['usage'];
                                $plan = $business->subscriptions->first()->plan ?? null;
                                $storesUsed = $business->stores->count();
                                $storesLimit = $plan ? $plan->max_stores : 0;
                                $usersUsed = $business->users->count();
                            ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($business->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($business->owner->name ?? 'N/A'); ?></small>
                                </td>
                                <td><?php echo e($plan->name ?? 'No Plan'); ?></td>
                                <td>
                                    <div class="progress" style="height: 25px;">
                                        <?php
                                            $percentage = $storesLimit > 0 ? ($storesUsed / $storesLimit) * 100 : 0;
                                            $color = $percentage >= 90 ? 'danger' : ($percentage >= 75 ? 'warning' : 'success');
                                        ?>
                                        <div class="progress-bar bg-<?php echo e($color); ?>" style="width: <?php echo e(min($percentage, 100)); ?>%">
                                            <?php echo e($storesUsed); ?> / <?php echo e($storesLimit == -1 ? '∞' : $storesLimit); ?>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($usersUsed); ?> users</span>
                                </td>
                                <td>
                                    <?php if($storesLimit == -1 || $storesUsed < $storesLimit): ?>
                                        <span class="badge bg-success">Within Limit</span>
                                    <?php elseif($storesUsed == $storesLimit): ?>
                                        <span class="badge bg-warning">At Limit</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Over Limit</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('superadmin.usage-limits.business', $business->id)); ?>" class="btn btn-sm btn-info">
                                        <i data-feather="bar-chart"></i> Details
                                    </a>
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
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/usage-limits/index.blade.php ENDPATH**/ ?>