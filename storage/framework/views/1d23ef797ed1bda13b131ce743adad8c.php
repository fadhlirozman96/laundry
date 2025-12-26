
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Feature Access Logs</h4>
                <h6>Track feature access attempts</h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('superadmin.features.index')); ?>" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back to Features
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Business</th>
                                <th>Feature</th>
                                <th>Result</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($log->created_at->format('d M Y H:i')); ?></td>
                                <td><?php echo e($log->user->name ?? 'N/A'); ?></td>
                                <td><?php echo e($log->business->name ?? 'N/A'); ?></td>
                                <td><code><?php echo e($log->feature_name); ?></code></td>
                                <td>
                                    <?php if($log->access_granted): ?>
                                        <span class="badge bg-success">Granted</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Denied</span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?php echo e($log->reason); ?></small></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">No logs found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($logs->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/features/logs.blade.php ENDPATH**/ ?>