<?php $page = 'laundry-qc'; ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quality Control</h4>
                <h6>Inspect orders before marking as ready</h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('laundry.qc.history')); ?>" class="btn btn-outline-primary">
                    <i data-feather="clock" class="me-2"></i>QC History
                </a>
            </div>
        </div>

        <!-- Pending QC Orders -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i data-feather="alert-circle" class="me-2"></i>
                    Orders Pending QC (<?php echo e(count($pendingOrders)); ?>)
                </h5>
            </div>
            <div class="card-body">
                <?php if(count($pendingOrders) > 0): ?>
                <div class="row">
                    <?php $__currentLoopData = $pendingOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-0"><?php echo e($order->order_number); ?></h5>
                                        <small class="text-muted"><?php echo e($order->created_at->format('d M Y H:i')); ?></small>
                                    </div>
                                    <?php echo $order->getStatusBadge(); ?>

                                </div>
                                
                                <p class="mb-1"><strong>Customer:</strong> <?php echo e($order->customer_name); ?></p>
                                <p class="mb-1"><strong>Items:</strong> <?php echo e($order->total_garments); ?> pcs</p>
                                <p class="mb-3"><strong>Total:</strong> MYR <?php echo e(number_format($order->total, 2)); ?></p>
                                
                                <div class="d-grid">
                                    <a href="<?php echo e(route('laundry.qc.create', $order->id)); ?>" class="btn btn-warning">
                                        <i data-feather="check-square" class="me-2"></i>Start QC
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i data-feather="check-circle" style="width: 64px; height: 64px;" class="text-success mb-3"></i>
                    <h5 class="text-muted">No orders pending QC</h5>
                    <p class="text-muted">All orders have been inspected. Great work!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent QC -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Quality Checks</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Inspector</th>
                                <th>Cleanliness</th>
                                <th>Odour</th>
                                <th>Quantity</th>
                                <th>Folding</th>
                                <th>Result</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentQC; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('laundry.show', $qc->laundry_order_id)); ?>">
                                        <?php echo e($qc->laundryOrder->order_number ?? 'N/A'); ?>

                                    </a>
                                </td>
                                <td><?php echo e($qc->user->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($qc->cleanliness_check): ?>
                                        <span class="badge bg-<?php echo e($qc->cleanliness_rating >= 3 ? 'success' : 'danger'); ?>">
                                            <?php echo e($qc->cleanliness_rating); ?>/5
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($qc->odour_check): ?>
                                        <span class="badge bg-<?php echo e($qc->odour_rating >= 3 ? 'success' : 'danger'); ?>">
                                            <?php echo e($qc->odour_rating); ?>/5
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($qc->quantity_check): ?>
                                        <span class="badge bg-<?php echo e($qc->quantity_match ? 'success' : 'danger'); ?>">
                                            <?php echo e($qc->items_counted); ?>/<?php echo e($qc->items_received); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($qc->folding_check): ?>
                                        <span class="badge bg-<?php echo e($qc->folding_rating >= 3 ? 'success' : 'danger'); ?>">
                                            <?php echo e($qc->folding_rating); ?>/5
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($qc->passed): ?>
                                        <span class="badge bg-success">PASSED</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">FAILED</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($qc->created_at->format('d M H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted">No QC records yet</td>
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

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/laundry/quality-check.blade.php ENDPATH**/ ?>