

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">My Subscription</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('index')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Subscription</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Current Subscription -->
        <?php if($currentSubscription): ?>
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title mb-3">Current Plan: <?php echo e($currentSubscription->plan->name); ?></h4>
                                <?php $status = $currentSubscription->calculateStatus(); ?>
                                <span class="badge badge-lg bg-<?php echo e($status === 'active' ? 'success' : ($status === 'grace' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($status)); ?>

                                </span>
                            </div>
                            <div class="col-auto">
                                <h2 class="mb-0">MYR <?php echo e(number_format($currentSubscription->amount, 2)); ?></h2>
                                <p class="text-muted mb-0">/<?php echo e($currentSubscription->billing_cycle === 'annual' ? 'year' : 'month'); ?></p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">Start Date</p>
                                <h6><?php echo e(\Carbon\Carbon::parse($currentSubscription->starts_at)->format('d M Y')); ?></h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">End Date</p>
                                <h6><?php echo e(\Carbon\Carbon::parse($currentSubscription->ends_at)->format('d M Y')); ?></h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">Next Renewal</p>
                                <h6><?php echo e($currentSubscription->next_billing_date ? \Carbon\Carbon::parse($currentSubscription->next_billing_date)->format('d M Y') : 'N/A'); ?></h6>
                            </div>
                            <div class="col-md-6 mb-3">
                                <p class="text-muted mb-1 small">Days Remaining</p>
                                <h6>
                                    <?php if($status === 'active'): ?>
                                        <span class="text-success"><?php echo e($currentSubscription->daysRemaining()); ?> days</span>
                                    <?php elseif($status === 'grace'): ?>
                                        <span class="text-danger">Grace: <?php echo e($currentSubscription->graceDaysRemaining()); ?> days</span>
                                    <?php else: ?>
                                        <span class="text-muted">Expired</span>
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>

                        <?php if($status === 'grace'): ?>
                        <div class="alert alert-danger">
                            <i data-feather="alert-triangle"></i> <strong>Payment Required!</strong> Your subscription is in grace period. Please make payment to continue service.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Usage & Limits -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Usage & Limits</h5>
                        
                        <?php
                            $storesUsed = (int)$usage['stores']['used'];
                            $storesAllowed = (int)$usage['stores']['allowed'];
                            $usersUsed = (int)$usage['users']['used'];
                            $usersAllowed = (int)$usage['users']['allowed'];
                            $ordersThisMonth = (int)$usage['orders_this_month'];
                            $ordersAllowed = (int)$usage['orders_allowed'];
                            
                            $storePercentage = $storesAllowed > 0 ? ($storesUsed / $storesAllowed) * 100 : 0;
                            $userPercentage = $usersAllowed > 0 ? ($usersUsed / $usersAllowed) * 100 : 0;
                            $orderPercentage = $ordersAllowed > 0 ? ($ordersThisMonth / $ordersAllowed) * 100 : 0;
                        ?>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Stores</span>
                                <span><strong><?php echo e($storesUsed); ?></strong> / <?php echo e($storesAllowed); ?></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-<?php echo e($storePercentage >= 100 ? 'danger' : ($storePercentage >= 80 ? 'warning' : 'primary')); ?>" 
                                     style="width: <?php echo e(min(100, $storePercentage)); ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Users</span>
                                <span><strong><?php echo e($usersUsed); ?></strong> / <?php echo e($usersAllowed); ?></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-<?php echo e($userPercentage >= 100 ? 'danger' : ($userPercentage >= 80 ? 'warning' : 'success')); ?>" 
                                     style="width: <?php echo e(min(100, $userPercentage)); ?>%"></div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Orders This Month</span>
                                <span><strong><?php echo e($ordersThisMonth); ?></strong> / <?php echo e($ordersAllowed); ?></span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-<?php echo e($orderPercentage >= 100 ? 'danger' : ($orderPercentage >= 80 ? 'warning' : 'info')); ?>" 
                                     style="width: <?php echo e(min(100, $orderPercentage)); ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Upgrade Plans -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Available Plans</h5>
                        
                        <?php $__currentLoopData = $availablePlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="mb-3 p-3 border rounded <?php echo e($currentSubscription->plan_id == $plan->id ? 'bg-light' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="mb-1"><?php echo e($plan->name); ?></h6>
                                    <p class="text-muted small mb-0"><?php echo e($plan->description); ?></p>
                                </div>
                                <?php if($currentSubscription->plan_id == $plan->id): ?>
                                <span class="badge bg-success">Current</span>
                                <?php endif; ?>
                            </div>
                            <h5 class="mb-2">MYR <?php echo e(number_format($plan->price, 2)); ?><small class="text-muted">/mo</small></h5>
                            
                            <?php if($currentSubscription->plan_id != $plan->id): ?>
                            <a href="<?php echo e(route('business-owner.checkout', ['plan_id' => $plan->id])); ?>" class="btn btn-sm btn-primary w-100">
                                <?php if($plan->price > $currentSubscription->plan->price): ?>
                                    Upgrade to <?php echo e($plan->name); ?>

                                <?php else: ?>
                                    Switch to <?php echo e($plan->name); ?>

                                <?php endif; ?>
                            </a>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <!-- No Active Subscription -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i data-feather="alert-circle" class="mb-3" style="width: 48px; height: 48px;"></i>
                        <h4>No Active Subscription</h4>
                        <p class="text-muted">Choose a plan to get started</p>
                        <a href="#plans" class="btn btn-primary">View Plans</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Show Available Plans -->
        <div class="row" id="plans">
            <?php $__currentLoopData = $availablePlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3"><?php echo e($plan->name); ?></h4>
                        <h2 class="mb-3">MYR <?php echo e(number_format($plan->price, 2)); ?><small class="text-muted">/mo</small></h2>
                        <p class="text-muted"><?php echo e($plan->description); ?></p>
                        <a href="<?php echo e(route('business-owner.checkout', ['plan_id' => $plan->id])); ?>" class="btn btn-primary w-100">
                            Choose Plan
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <?php endif; ?>

        <!-- Payment History -->
        <div class="card mt-3">
            <div class="card-body">
                <h5 class="card-title mb-3">Payment History</h5>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $paymentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><code><?php echo e($payment->transaction_id); ?></code></td>
                                <td><?php echo e($payment->subscription->plan->name); ?></td>
                                <td><?php echo e($payment->currency); ?> <?php echo e(number_format($payment->amount, 2)); ?></td>
                                <td><span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $payment->payment_method))); ?></span></td>
                                <td>
                                    <span class="badge bg-<?php echo e($payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning')); ?>">
                                        <?php echo e(ucfirst($payment->status)); ?>

                                    </span>
                                </td>
                                <td><?php echo e($payment->paid_at ? $payment->paid_at->format('d M Y H:i') : $payment->created_at->format('d M Y H:i')); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="6" class="text-center">No payment history</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($paymentHistory->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/business-owner/subscription.blade.php ENDPATH**/ ?>