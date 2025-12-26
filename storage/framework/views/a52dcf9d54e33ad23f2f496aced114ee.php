

<?php $__env->startSection('content'); ?>
<style>
.feather-sm {
    width: 16px;
    height: 16px;
    vertical-align: middle;
}
.badge-lg {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}
.bg-success-light {
    background-color: #d4edda;
}
.bg-info-light {
    background-color: #d1ecf1;
}
.bg-danger-light {
    background-color: #f8d7da;
}
</style>
<div class="page-wrapper">
    <div class="content">
        <!-- Breadcrumb -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">
                        <a href="<?php echo e(route('superadmin.subscriptions')); ?>" class="text-primary me-2">
                            <i data-feather="arrow-left"></i>
                        </a>
                        <?php echo e($owner->name); ?>

                    </h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('superadmin.subscriptions')); ?>">Subscriptions</a></li>
                        <li class="breadcrumb-item active">Business Owner Profile</li>
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

        <!-- Alerts -->
        <?php if(count($alerts) > 0): ?>
        <div class="row mb-3">
            <div class="col-12">
                <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="alert alert-<?php echo e($alert['type']); ?> alert-dismissible fade show" role="alert">
                    <i data-feather="<?php echo e($alert['icon']); ?>"></i>
                    <strong>Alert:</strong> <?php echo e($alert['message']); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Business Overview Card -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="avatar avatar-xxl bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 32px;">
                                    <?php echo e(strtoupper(substr($owner->name, 0, 2))); ?>

                                </div>
                            </div>
                            <div class="col">
                                <h3 class="mb-1"><?php echo e($owner->name); ?></h3>
                                <p class="text-muted mb-2"><i data-feather="mail" class="feather-sm me-1"></i> <?php echo e($owner->email); ?></p>
                                <p class="text-muted mb-0"><i data-feather="phone" class="feather-sm me-1"></i> <?php echo e($owner->phone ?? 'N/A'); ?></p>
                            </div>
                            <div class="col-auto text-end">
                                <div class="mb-2">
                                    <?php if($owner->currentPlan): ?>
                                        <span class="badge badge-lg bg-<?php echo e($owner->currentPlan->slug == 'free' ? 'secondary' : ($owner->currentPlan->slug == 'pro' ? 'success' : 'primary')); ?>">
                                            <?php echo e($owner->currentPlan->name); ?> Plan
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-lg bg-secondary">No Plan</span>
                                    <?php endif; ?>
                                </div>
                                <div class="mb-2">
                                    <?php if($currentSubscription): ?>
                                        <?php if($currentSubscription->status === 'active'): ?>
                                            <span class="badge bg-success-light text-success">● Active</span>
                                        <?php elseif($currentSubscription->status === 'trial'): ?>
                                            <span class="badge bg-info-light text-info">● Trial</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-light text-danger">● <?php echo e(ucfirst($currentSubscription->status)); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Subscription</span>
                                    <?php endif; ?>
                                </div>
                                <?php if($owner->currentPlan): ?>
                                <small class="text-muted">MYR <?php echo e(number_format($owner->currentPlan->price, 2)); ?>/month</small>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="row text-center">
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Company</p>
                                <h6 class="mb-0"><?php echo e($owner->company_name ?? 'N/A'); ?></h6>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Member Since</p>
                                <h6 class="mb-0"><?php echo e($owner->created_at->format('d M Y')); ?></h6>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Last Activity</p>
                                <h6 class="mb-0"><?php echo e($accountHealth['days_since_login']); ?> days ago</h6>
                            </div>
                            <div class="col-md-3">
                                <p class="text-muted mb-1 small">Health Score</p>
                                <h6 class="mb-0">
                                    <span class="badge bg-<?php echo e($accountHealth['score'] >= 80 ? 'success' : ($accountHealth['score'] >= 50 ? 'warning' : 'danger')); ?>">
                                        <?php echo e($accountHealth['score']); ?>%
                                    </span>
                                    <?php if($accountHealth['score'] >= 80): ?>
                                        Healthy
                                    <?php elseif($accountHealth['score'] >= 50): ?>
                                        At Risk
                                    <?php else: ?>
                                        Inactive
                                    <?php endif; ?>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Admin Actions Bar -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="mb-0"><i data-feather="settings" class="feather-sm"></i> Admin Actions</h6>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#assignPlanModal">
                                    <i data-feather="package" class="feather-sm"></i> Assign Plan
                                </button>
                                <button class="btn btn-sm btn-info me-2" onclick="alert('Extend Trial feature - Coming Soon')">
                                    <i data-feather="clock" class="feather-sm"></i> Extend Trial
                                </button>
                                <button class="btn btn-sm btn-warning me-2" onclick="if(confirm('Are you sure you want to pause this subscription?')) { alert('Pause feature - Coming Soon'); }">
                                    <i data-feather="pause" class="feather-sm"></i> Pause
                                </button>
                                <button class="btn btn-sm btn-danger me-2" onclick="if(confirm('Are you sure you want to lock this account?')) { alert('Lock Account feature - Coming Soon'); }">
                                    <i data-feather="lock" class="feather-sm"></i> Lock
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="alert('Impersonate feature - Coming Soon')">
                                    <i data-feather="eye" class="feather-sm"></i> Impersonate
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Main Content Column -->
            <div class="col-lg-8">

                <!-- Billing Health Widget -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="credit-card" class="feather-sm"></i> Billing Health</h5>
                        
                        <div class="row g-3">
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Last Payment</p>
                                    <?php if($billingHealth['last_payment']): ?>
                                        <h5 class="mb-1">MYR <?php echo e(number_format($billingHealth['last_payment']->amount, 2)); ?></h5>
                                        <small class="text-success"><?php echo e($billingHealth['last_payment']->paid_at->format('d M Y')); ?></small>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">No payments yet</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Failed Attempts</p>
                                    <h5 class="mb-0 text-<?php echo e($billingHealth['failed_attempts'] > 0 ? 'danger' : 'success'); ?>">
                                        <?php echo e($billingHealth['failed_attempts']); ?>

                                    </h5>
                                    <small class="text-muted">Last 30 days</small>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Days Past Due</p>
                                    <h5 class="mb-0 text-<?php echo e($billingHealth['days_past_due'] > 0 ? 'danger' : 'success'); ?>">
                                        <?php echo e($billingHealth['days_past_due']); ?>

                                    </h5>
                                    <small class="text-muted">days</small>
                                </div>
                            </div>
                            
                            <div class="col-sm-6 col-xl-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <p class="text-muted mb-2 small">Total Revenue</p>
                                    <h5 class="mb-0 text-success">MYR <?php echo e(number_format($billingHealth['total_paid'], 2)); ?></h5>
                                    <small class="text-muted">all time</small>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($billingHealth['pending_amount'] > 0): ?>
                        <div class="alert alert-warning mt-3 mb-0">
                            <i data-feather="alert-triangle"></i> <strong>Pending Payment:</strong> MYR <?php echo e(number_format($billingHealth['pending_amount'], 2)); ?>

                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment History -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="dollar-sign" class="feather-sm"></i> Payment History</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Invoice ID</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $paymentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong>#<?php echo e($payment->id); ?></strong></td>
                                        <td><?php echo e($payment->subscription->plan->name); ?></td>
                                        <td><?php echo e($payment->currency); ?> <?php echo e(number_format($payment->amount, 2)); ?></td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                <?php echo e($payment->payment_method ? ucfirst($payment->payment_method) : 'N/A'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <?php if($payment->status === 'completed'): ?>
                                                <span class="badge bg-success">Paid</span>
                                            <?php elseif($payment->status === 'pending'): ?>
                                                <span class="badge bg-warning">Pending</span>
                                            <?php elseif($payment->status === 'failed'): ?>
                                                <span class="badge bg-danger">Failed</span>
                                            <?php else: ?>
                                                <span class="badge bg-info"><?php echo e(ucfirst($payment->status)); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($payment->paid_at ? $payment->paid_at->format('d M Y') : $payment->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <?php if($payment->status === 'failed'): ?>
                                                <form action="<?php echo e(route('superadmin.payments.retry', $payment->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-warning" title="Retry Payment">
                                                        <i data-feather="refresh-cw" style="width: 14px; height: 14px;"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <?php if($payment->status === 'pending'): ?>
                                                <form action="<?php echo e(route('superadmin.payments.mark-paid', $payment->id)); ?>" method="POST" class="d-inline">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" class="btn btn-sm btn-success" title="Mark as Paid">
                                                        <i data-feather="check" style="width: 14px; height: 14px;"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            
                                            <button class="btn btn-sm btn-info" title="Download Invoice">
                                                <i data-feather="download" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No payment history</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- User & Role Summary -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="users" class="feather-sm"></i> Users & Roles</h5>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <h4 class="text-primary mb-0"><?php echo e($userMetrics['total_users']); ?></h4>
                                    <p class="text-muted small mb-0">Total</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <h4 class="text-success mb-0"><?php echo e($userMetrics['active_users']); ?></h4>
                                    <p class="text-muted small mb-0">Active</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-2 bg-light rounded">
                                    <h4 class="text-danger mb-0"><?php echo e($userMetrics['inactive_users']); ?></h4>
                                    <p class="text-muted small mb-0">Inactive</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Roles</th>
                                        <th>Stores</th>
                                        <th>Last Login</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $userSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $userInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($userInfo['user']->name); ?></strong><br>
                                            <small class="text-muted"><?php echo e($userInfo['user']->email); ?></small>
                                        </td>
                                        <td>
                                            <?php $__currentLoopData = $userInfo['roles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge bg-info"><?php echo e(ucfirst(str_replace('_', ' ', $role))); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary"><?php echo e(count($userInfo['stores'])); ?> store(s)</span>
                                        </td>
                                        <td>
                                            <?php echo e($userInfo['last_login']->format('d M Y')); ?><br>
                                            <small class="text-muted"><?php echo e($userInfo['last_login']->diffForHumans()); ?></small>
                                        </td>
                                        <td>
                                            <?php if($userInfo['is_active']): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center">No users found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Store Performance -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="shopping-bag" class="feather-sm"></i> Store Performance (Last 30 Days)</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Store Name</th>
                                        <th>Status</th>
                                        <th>Orders</th>
                                        <th>Revenue</th>
                                        <th>QC Pending</th>
                                        <th>Users</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $storeStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><strong><?php echo e($store['name']); ?></strong></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($store['is_active'] ? 'success' : 'secondary'); ?>">
                                                <?php echo e($store['is_active'] ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($store['orders_30d']); ?></td>
                                        <td>MYR <?php echo e(number_format($store['revenue_30d'], 2)); ?></td>
                                        <td>
                                            <?php if($store['qc_pending'] > 0): ?>
                                                <span class="badge bg-warning"><?php echo e($store['qc_pending']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($store['users_count']); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No stores found</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar Column -->
            <div class="col-lg-4">
                <!-- Usage & Limits -->
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="bar-chart-2" class="feather-sm"></i> Usage & Limits</h5>
                        
                        <?php
                            $storePercentage = $usage['stores']['allowed'] > 0 ? ($usage['stores']['used'] / $usage['stores']['allowed']) * 100 : 0;
                            $userPercentage = $usage['users']['allowed'] > 0 ? ($usage['users']['used'] / $usage['users']['allowed']) * 100 : 0;
                            $orderPercentage = $usage['orders_allowed'] > 0 ? ($usage['orders_this_month'] / $usage['orders_allowed']) * 100 : 0;
                        ?>
                        
                        <!-- Stores -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Stores</span>
                                <span><strong><?php echo e($usage['stores']['used']); ?></strong> / <?php echo e($usage['stores']['allowed']); ?></span>
                            </div>
                            <div class="progress mb-1" style="height: 8px;">
                                <div class="progress-bar bg-<?php echo e($storePercentage >= 100 ? 'danger' : ($storePercentage >= 80 ? 'warning' : 'primary')); ?>" 
                                     style="width: <?php echo e(min(100, $storePercentage)); ?>%">
                                </div>
                            </div>
                            <small class="text-<?php echo e($storePercentage >= 80 ? 'danger' : 'muted'); ?>"><?php echo e(number_format($storePercentage, 0)); ?>% used</small>
                        </div>

                        <!-- Users -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Users</span>
                                <span><strong><?php echo e($usage['users']['used']); ?></strong> / <?php echo e($usage['users']['allowed']); ?></span>
                            </div>
                            <div class="progress mb-1" style="height: 8px;">
                                <div class="progress-bar bg-<?php echo e($userPercentage >= 100 ? 'danger' : ($userPercentage >= 80 ? 'warning' : 'success')); ?>" 
                                     style="width: <?php echo e(min(100, $userPercentage)); ?>%">
                                </div>
                            </div>
                            <small class="text-<?php echo e($userPercentage >= 80 ? 'danger' : 'muted'); ?>"><?php echo e(number_format($userPercentage, 0)); ?>% used</small>
                        </div>

                        <!-- Orders -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Orders (This Month)</span>
                                <span><strong><?php echo e($usage['orders_this_month']); ?></strong> / <?php echo e($usage['orders_allowed']); ?></span>
                            </div>
                            <div class="progress mb-1" style="height: 8px;">
                                <div class="progress-bar bg-<?php echo e($orderPercentage >= 100 ? 'danger' : ($orderPercentage >= 80 ? 'warning' : 'info')); ?>" 
                                     style="width: <?php echo e(min(100, $orderPercentage)); ?>%">
                                </div>
                            </div>
                            <small class="text-<?php echo e($orderPercentage >= 80 ? 'danger' : 'muted'); ?>"><?php echo e(number_format($orderPercentage, 0)); ?>% used</small>
                        </div>

                        <?php if($storePercentage >= 80 || $userPercentage >= 80 || $orderPercentage >= 80): ?>
                        <div class="alert alert-warning mb-0">
                            <i data-feather="trending-up" class="feather-sm"></i> <strong>Upsell Opportunity!</strong> Approaching plan limits.
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Subscription History -->
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4"><i data-feather="clock" class="feather-sm"></i> Subscription History</h5>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Plan</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $subscriptionHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><span class="badge bg-primary"><?php echo e($subscription->plan->name); ?></span></td>
                                        <td>
                                            <?php echo e(\Carbon\Carbon::parse($subscription->starts_at)->format('d M Y')); ?> - 
                                            <?php echo e(\Carbon\Carbon::parse($subscription->ends_at)->format('d M Y')); ?>

                                        </td>
                                        <td>
                                            <?php if($subscription->status === 'active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php elseif($subscription->status === 'trial'): ?>
                                                <span class="badge bg-info">Trial</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo e(ucfirst($subscription->status)); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>MYR <?php echo e(number_format($subscription->amount, 2)); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No subscription history</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Plan Modal -->
<div class="modal fade" id="assignPlanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="<?php echo e(url('superadmin/subscriptions/' . $owner->id . '/assign-plan')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Assign Plan to <?php echo e($owner->name); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Select Plan <span class="text-danger">*</span></label>
                        <select name="plan_id" class="form-control" required>
                            <option value="">Choose a plan...</option>
                            <?php
                                $allPlans = \App\Models\Plan::where('is_active', true)->orderBy('sort_order')->get();
                            ?>
                            <?php $__currentLoopData = $allPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($plan->id); ?>" <?php echo e($owner->currentPlan && $owner->currentPlan->id == $plan->id ? 'selected' : ''); ?>>
                                <?php echo e($plan->name); ?> - MYR <?php echo e(number_format($plan->price, 2)); ?>/mo
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Start Date <span class="text-danger">*</span></label>
                        <input type="date" name="starts_at" class="form-control" 
                            value="<?php echo e($currentSubscription && $currentSubscription->starts_at ? $currentSubscription->starts_at : now()->format('Y-m-d')); ?>" 
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label>End Date <span class="text-danger">*</span></label>
                        <input type="date" name="ends_at" class="form-control" 
                            value="<?php echo e($currentSubscription && $currentSubscription->ends_at ? $currentSubscription->ends_at : now()->addYear()->format('Y-m-d')); ?>" 
                            required>
                    </div>

                    <div class="form-group mb-3">
                        <label>Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="active" <?php echo e($currentSubscription && $currentSubscription->status == 'active' ? 'selected' : ''); ?>>Active</option>
                            <option value="trial" <?php echo e($currentSubscription && $currentSubscription->status == 'trial' ? 'selected' : ''); ?>>Trial</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Assign Plan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Re-initialize Feather icons after any dynamic content changes
    setTimeout(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 100);
});

// Also initialize on window load
window.addEventListener('load', function() {
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});

// Re-initialize Feather icons when modal is shown
var assignPlanModal = document.getElementById('assignPlanModal');
if (assignPlanModal) {
    assignPlanModal.addEventListener('shown.bs.modal', function () {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/business-owners/profile.blade.php ENDPATH**/ ?>