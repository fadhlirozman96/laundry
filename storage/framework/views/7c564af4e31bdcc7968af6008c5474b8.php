<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h3 class="page-title">Payment History</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('superadmin.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <select class="select" id="statusFilter">
                                <option value="">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="pending">Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-12">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Search by Transaction ID" id="searchInput">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Transaction ID</th>
                                <th>Customer</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Payment Method</th>
                                <th>Payment Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>#<?php echo e($payment->id); ?></td>
                                <td>
                                    <code><?php echo e($payment->transaction_id ?? 'N/A'); ?></code>
                                </td>
                                <td>
                                    <div>
                                        <p class="mb-0"><strong><?php echo e($payment->subscription->user->name ?? 'N/A'); ?></strong></p>
                                        <p class="text-muted mb-0 small"><?php echo e($payment->subscription->user->email ?? 'N/A'); ?></p>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($payment->subscription->plan->name ?? 'N/A'); ?></span>
                                </td>
                                <td>
                                    <strong><?php echo e($payment->currency ?? 'MYR'); ?> <?php echo e(number_format($payment->amount, 2)); ?></strong>
                                </td>
                                <td>
                                    <?php if($payment->payment_method): ?>
                                    <span class="badge bg-secondary"><?php echo e(strtoupper(str_replace('_', ' ', $payment->payment_method))); ?></span>
                                    <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e($payment->payment_date ? $payment->payment_date->format('d M Y H:i') : 'N/A'); ?>

                                </td>
                                <td>
                                    <?php if($payment->status === 'completed'): ?>
                                    <span class="badge bg-success">Completed</span>
                                    <?php elseif($payment->status === 'pending'): ?>
                                    <span class="badge bg-warning">Pending</span>
                                    <?php elseif($payment->status === 'failed'): ?>
                                    <span class="badge bg-danger">Failed</span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary"><?php echo e(ucfirst($payment->status)); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center">No payments found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                                <td colspan="4">
                                    <strong>MYR <?php echo e(number_format($payments->sum('amount'), 2)); ?></strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="pagination-wrapper mt-3">
                    <?php echo e($payments->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/payments.blade.php ENDPATH**/ ?>