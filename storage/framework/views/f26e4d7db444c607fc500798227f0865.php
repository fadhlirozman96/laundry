
<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Store Containers</h4>
                <h6>Manage all stores across businesses</h6>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="box"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Stores</h6>
                            <h3><?php echo e($stats['total']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12">
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
            <div class="col-xl-4 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-warning">
                                <i data-feather="x-circle"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Inactive</h6>
                            <h3><?php echo e($stats['inactive']); ?></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Store Name</th>
                                <th>Business</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($store->name); ?></strong></td>
                                <td><?php echo e($store->owner->name ?? 'N/A'); ?></td>
                                <td>
                                    <?php if($store->is_active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($store->created_at->format('d M Y')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('superadmin.store-containers.show', $store->id)); ?>" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center">No stores found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($stores->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/superadmin/store-containers/index.blade.php ENDPATH**/ ?>