<?php $page = 'laundry-orders'; ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laundry Orders</h4>
                <h6>Manage all laundry orders</h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('laundry.create')); ?>" class="btn btn-added">
                    <i data-feather="plus-circle" class="me-2"></i>New Order
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('laundry.orders')); ?>" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="received" <?php echo e(request('status') == 'received' ? 'selected' : ''); ?>>Received</option>
                            <option value="washing" <?php echo e(request('status') == 'washing' ? 'selected' : ''); ?>>Washing</option>
                            <option value="drying" <?php echo e(request('status') == 'drying' ? 'selected' : ''); ?>>Drying</option>
                            <option value="folding" <?php echo e(request('status') == 'folding' ? 'selected' : ''); ?>>Folding</option>
                            <option value="ready" <?php echo e(request('status') == 'ready' ? 'selected' : ''); ?>>Ready</option>
                            <option value="collected" <?php echo e(request('status') == 'collected' ? 'selected' : ''); ?>>Collected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" value="<?php echo e(request('date')); ?>">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="<?php echo e(route('laundry.orders')); ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>QC</th>
                                <th>Payment</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('laundry.show', $order->id)); ?>">
                                        <strong><?php echo e($order->order_number); ?></strong>
                                    </a>
                                </td>
                                <td><?php echo e($order->customer_name); ?></td>
                                <td><?php echo e($order->customer_phone ?? '-'); ?></td>
                                <td><?php echo e($order->total_garments); ?> pcs</td>
                                <td>MYR <?php echo e(number_format($order->total, 2)); ?></td>
                                <td><?php echo $order->getStatusBadge(); ?></td>
                                <td>
                                    <?php if($order->qc_passed): ?>
                                        <span class="badge bg-success">✓</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($order->payment_status == 'paid'): ?>
                                        <span class="badge bg-success">Paid</span>
                                    <?php elseif($order->payment_status == 'partial'): ?>
                                        <span class="badge bg-warning">Partial</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                                <td>
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2" href="<?php echo e(route('laundry.show', $order->id)); ?>" title="View">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        <?php if($order->status !== 'collected'): ?>
                                        <a class="me-2 p-2" href="javascript:void(0);" onclick="updateOrderStatus(<?php echo e($order->id); ?>, '<?php echo e($order->getNextStatus()); ?>')" title="Next Status">
                                            <i data-feather="arrow-right" class="text-primary"></i>
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="10" class="text-center text-muted">No orders found</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    <?php echo e($orders->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function updateOrderStatus(orderId, newStatus) {
        if (!newStatus) {
            Swal.fire('Info', 'Order is already at final status', 'info');
            return;
        }
        
        Swal.fire({
            title: 'Update Status',
            text: 'Move order to ' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/laundry/orders/' + orderId + '/status',
                    type: 'PUT',
                    data: { status: newStatus, _token: '<?php echo e(csrf_token()); ?>' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update status', 'error');
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/laundry/orders.blade.php ENDPATH**/ ?>