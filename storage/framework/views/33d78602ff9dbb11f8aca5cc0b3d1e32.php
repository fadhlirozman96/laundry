
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Business Owners</h4>
                <h6>Manage business owner accounts and their subscriptions</h6>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="briefcase"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Owners</h6>
                            <h3><?php echo e($stats['total']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success">
                                <i data-feather="check-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">With Stores</h6>
                            <h3><?php echo e($stats['with_stores']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-info">
                                <i data-feather="gift"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Free Plan</h6>
                            <h3><?php echo e($stats['on_free']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="dollar-sign"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Paid Plans</h6>
                            <h3><?php echo e($stats['on_paid']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Owners List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Owner</th>
                                <th>Company</th>
                                <th>Plan</th>
                                <th>Stores</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $owners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $owner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($owner->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($owner->email); ?></small>
                                </td>
                                <td><?php echo e($owner->company_name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($owner->currentPlan): ?>
                                        <span class="badge bg-<?php echo e($owner->currentPlan->slug == 'free' ? 'secondary' : 'primary'); ?>">
                                            <?php echo e($owner->currentPlan->name); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Plan</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($owner->ownedStores->count()); ?> store(s)</span>
                                </td>
                                <td>
                                    <?php
                                        $subscription = $owner->subscriptions->first();
                                    ?>
                                    <?php if($subscription): ?>
                                        <?php if($subscription->status === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php elseif($subscription->status === 'trial'): ?>
                                            <span class="badge bg-info">Trial</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger"><?php echo e(ucfirst($subscription->status)); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No Subscription</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($owner->created_at->format('d M Y')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('superadmin.users.show', $owner->id)); ?>" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">No business owners found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($owners->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/business-owners/index.blade.php ENDPATH**/ ?>