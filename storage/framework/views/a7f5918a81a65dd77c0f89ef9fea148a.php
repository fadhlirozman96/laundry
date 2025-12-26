
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Business Management</h4>
                <h6>Manage all business tenants</h6>
            </div>
        </div>

        <!-- Stats Cards -->
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
                            <h6 class="text-muted">Total Businesses</h6>
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
                            <h6 class="text-muted">Active</h6>
                            <h3><?php echo e($stats['active']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="clock"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">On Trial</h6>
                            <h3><?php echo e($stats['trial']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-danger">
                                <i data-feather="x-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Suspended</h6>
                            <h3><?php echo e($stats['suspended']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business List -->
        <div class="card">
            <div class="card-body">
                <div class="table-top">
                    <div class="search-set">
                        <h5>Business List</h5>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business Name</th>
                                <th>Owner</th>
                                <th>Stores</th>
                                <th>Status</th>
                                <th>Plan</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $businesses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $business): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($business->name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($business->slug); ?></small>
                                </td>
                                <td>
                                    <?php echo e($business->owner->name ?? 'N/A'); ?><br>
                                    <small class="text-muted"><?php echo e($business->owner->email ?? ''); ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($business->stores->count()); ?> stores</span>
                                </td>
                                <td>
                                    <?php if($business->status === 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif($business->status === 'trial'): ?>
                                        <span class="badge bg-warning">Trial</span>
                                    <?php elseif($business->status === 'suspended'): ?>
                                        <span class="badge bg-danger">Suspended</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($business->subscriptions->first()): ?>
                                        <?php echo e($business->subscriptions->first()->plan->name ?? 'N/A'); ?>

                                    <?php else: ?>
                                        <span class="text-muted">No Plan</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($business->created_at->format('d M Y')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('superadmin.businesses.show', $business->id)); ?>" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                    <?php if($business->status === 'active'): ?>
                                        <form action="<?php echo e(route('superadmin.businesses.suspend', $business->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-warning">Suspend</button>
                                        </form>
                                    <?php elseif($business->status === 'suspended'): ?>
                                        <form action="<?php echo e(route('superadmin.businesses.activate', $business->id)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="btn btn-sm btn-success">Activate</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">No businesses found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($businesses->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/businesses/index.blade.php ENDPATH**/ ?>