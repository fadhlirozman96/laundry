<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm-12">
                    <h3 class="page-title">SaaS Dashboard</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('superadmin.dashboard')); ?>">Dashboard</a></li>
                        <li class="breadcrumb-item active">Overview</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="row">
            <!-- Total Revenue -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="dollar-sign"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Revenue</h6>
                            <h3>MYR <?php echo e(number_format($totalRevenue, 2)); ?></h3>
                            <p class="text-success mb-0">
                                <span><i data-feather="trending-up"></i> +2.5%</span> than last month
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Customers -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success">
                                <i data-feather="users"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Active Customers</h6>
                            <h3><?php echo e(number_format($activeSubscriptions)); ?></h3>
                            <p class="text-success mb-0">
                                <span><i data-feather="trending-up"></i> +<?php echo e($newCustomersThisMonth); ?></span> this month
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Recurring Revenue -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="repeat"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Monthly Revenue</h6>
                            <h3>MYR <?php echo e(number_format($monthlyRevenue, 2)); ?></h3>
                            <p class="text-success mb-0">
                                <span><i data-feather="trending-up"></i> +9.5%</span> growth
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Churn Rate -->
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-danger">
                                <i data-feather="user-minus"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Churn Rate</h6>
                            <h3><?php echo e(number_format($churnRate, 1)); ?>%</h3>
                            <p class="text-<?php echo e($canceledLast30Days > 0 ? 'danger' : 'muted'); ?> mb-0">
                                <span><i data-feather="trending-<?php echo e($canceledLast30Days > 0 ? 'down' : 'up'); ?>"></i> <?php echo e($canceledLast30Days); ?></span> canceled this month
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Revenue Chart -->
            <div class="col-xl-8 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Revenue Trend</h5>
                        <div class="card-tools">
                            <span class="badge badge-soft-primary">Last 12 Months</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <!-- Plan Distribution -->
            <div class="col-xl-4 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Plan Distribution</h5>
                    </div>
                    <div class="card-body">
                        <?php if($planDistribution->sum('count') > 0): ?>
                        <canvas id="planChart" height="300"></canvas>
                        <?php else: ?>
                        <div class="text-center py-5">
                            <i data-feather="pie-chart" style="width: 48px; height: 48px; color: #ccc;"></i>
                            <p class="text-muted mt-3">No active subscriptions yet</p>
                            <small class="text-muted">Plan distribution will appear here once customers subscribe</small>
                        </div>
                        <?php endif; ?>
                        <div class="mt-4">
                            <?php $__currentLoopData = $planDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <?php
                                        $colors = ['primary', 'success', 'warning', 'info'];
                                        $color = $colors[$loop->index % count($colors)];
                                    ?>
                                    <span class="badge bg-<?php echo e($color); ?> me-2" style="width: 12px; height: 12px;"></span>
                                    <span><?php echo e($plan->plan_name); ?></span>
                                </div>
                                <span class="fw-bold <?php echo e($plan->count > 0 ? 'text-success' : 'text-muted'); ?>"><?php echo e($plan->count); ?></span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Subscription Stats -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Subscription Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Active</span>
                                <span class="badge bg-success"><?php echo e($activeSubscriptions); ?></span>
                            </div>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Trial</span>
                                <span class="badge bg-info"><?php echo e($trialSubscriptions); ?></span>
                            </div>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Canceled</span>
                                <span class="badge bg-warning"><?php echo e($canceledSubscriptions); ?></span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total Customers</span>
                                <span class="fw-bold"><?php echo e($totalCustomers); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Stats -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">System Overview</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Stores</span>
                                <span class="h5 mb-0"><?php echo e(number_format($totalStores)); ?></span>
                            </div>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Total Orders</span>
                                <span class="h5 mb-0"><?php echo e(number_format($totalOrders)); ?></span>
                            </div>
                        </div>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Orders This Month</span>
                                <span class="h5 mb-0"><?php echo e(number_format($ordersThisMonth)); ?></span>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">Yearly Revenue</span>
                                <span class="h5 mb-0">MYR <?php echo e(number_format($yearlyRevenue, 2)); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Renewals -->
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Upcoming Renewals</h5>
                        <span class="badge badge-soft-warning">Next 30 Days</span>
                    </div>
                    <div class="card-body" style="max-height: 300px; overflow-y: auto;">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingRenewals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $renewal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1"><?php echo e($renewal->user->name); ?></h6>
                                    <p class="mb-0 text-muted small"><?php echo e($renewal->plan->name); ?> Plan</p>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold d-block">MYR <?php echo e(number_format($renewal->amount, 2)); ?></span>
                                    <span class="text-muted small"><?php echo e($renewal->next_billing_date->format('M d')); ?></span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted text-center">No upcoming renewals</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Customers & Payments -->
        <div class="row">
            <!-- Recent Customers -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Customers</h5>
                        <a href="<?php echo e(route('superadmin.customers')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Plan</th>
                                        <th>Stores</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentCustomers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-title rounded-circle bg-primary">
                                                        <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo e($customer->name); ?></h6>
                                                    <p class="mb-0 text-muted small"><?php echo e($customer->email); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if($customer->currentPlan): ?>
                                            <span class="badge badge-soft-primary"><?php echo e($customer->currentPlan->name); ?></span>
                                            <?php else: ?>
                                            <span class="badge badge-soft-secondary">No Plan</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($customer->ownedStores->count()); ?></td>
                                        <td><?php echo e($customer->created_at->format('M d, Y')); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('superadmin.impersonate', $customer->id)); ?>" 
                                               class="btn btn-sm btn-outline-primary"
                                               title="View as <?php echo e($customer->name); ?>">
                                                <i data-feather="eye" class="feather-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No customers yet</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Payments</h5>
                        <a href="<?php echo e(route('superadmin.payments')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Plan</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $recentPayments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <h6 class="mb-0"><?php echo e($payment->user->name); ?></h6>
                                            <p class="mb-0 text-muted small"><?php echo e($payment->transaction_id ?? 'N/A'); ?></p>
                                        </td>
                                        <td>
                                            <?php if($payment->subscription && $payment->subscription->plan): ?>
                                            <span class="badge badge-soft-primary"><?php echo e($payment->subscription->plan->name); ?></span>
                                            <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>MYR <?php echo e(number_format($payment->amount, 2)); ?></td>
                                        <td><?php echo e($payment->created_at->format('M d, Y')); ?></td>
                                        <td><?php echo $payment->getStatusBadge(); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No payments yet</td>
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

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode(array_column($revenueChartData, 'month')); ?>,
            datasets: [{
                label: 'Revenue (MYR)',
                data: <?php echo json_encode(array_column($revenueChartData, 'revenue')); ?>,
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderColor: 'rgb(59, 130, 246)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'MYR ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Plan Distribution Chart
    <?php if($planDistribution->sum('count') > 0): ?>
    const planCtx = document.getElementById('planChart');
    if (planCtx) {
        const planChart = new Chart(planCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($planDistribution->pluck('plan_name')); ?>,
                datasets: [{
                    data: <?php echo json_encode($planDistribution->pluck('count')); ?>,
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(99, 102, 241)'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }
    <?php endif; ?>
</script>

<style>
.dashboard-card {
    border: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.15);
}

.dash-widget-header {
    margin-bottom: 1rem;
}

.dash-widget-icon {
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.dash-widget-icon i {
    width: 24px;
    height: 24px;
    color: white;
}

.dash-widget-info h3 {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.dash-widget-info h6 {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.card-tools {
    margin-left: auto;
}

.badge-soft-primary {
    background-color: rgba(59, 130, 246, 0.1);
    color: rgb(59, 130, 246);
}

.badge-soft-success {
    background-color: rgba(16, 185, 129, 0.1);
    color: rgb(16, 185, 129);
}

.badge-soft-warning {
    background-color: rgba(245, 158, 11, 0.1);
    color: rgb(245, 158, 11);
}

.badge-soft-secondary {
    background-color: rgba(107, 114, 128, 0.1);
    color: rgb(107, 114, 128);
}

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    color: white;
    font-weight: 600;
}

.feather-sm {
    width: 14px;
    height: 14px;
}
</style>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/dashboard.blade.php ENDPATH**/ ?>