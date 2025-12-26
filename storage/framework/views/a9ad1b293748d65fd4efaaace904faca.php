
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Grace Periods</h4>
                <h6>Manage subscription grace periods</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Active Grace Periods</h5>
                        <h2><?php echo e($active); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Expired</h5>
                        <h2><?php echo e($expired); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $gracePeriods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grace): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($grace->business->name ?? 'N/A'); ?></td>
                                <td><?php echo e($grace->grace_start_date->format('d M Y')); ?></td>
                                <td><?php echo e($grace->grace_end_date->format('d M Y')); ?></td>
                                <td>
                                    <?php if($grace->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif($grace->status === 'expired'): ?>
                                        <span class="badge bg-danger">Expired</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?php echo e($grace->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><small><?php echo e($grace->reason); ?></small></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">No grace periods found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($gracePeriods->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/grace-periods.blade.php ENDPATH**/ ?>