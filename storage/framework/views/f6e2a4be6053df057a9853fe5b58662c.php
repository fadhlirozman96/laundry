<?php $page = 'laundry-dashboard'; ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laundry Operations Dashboard</h4>
                <h6>Monitor and manage laundry orders</h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('laundry.create')); ?>" class="btn btn-added">
                    <i data-feather="plus-circle" class="me-2"></i>New Order
                </a>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="row">
            <div class="col-lg-2 col-sm-4 col-6">
                <div class="dash-widget dash1">
                    <div class="dash-widgetimg">
                        <span><i data-feather="inbox" class="text-secondary"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($statusCounts['received'] ?? 0); ?></h5>
                        <h6>Received</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <div class="dash-widget dash2">
                    <div class="dash-widgetimg">
                        <span><i data-feather="droplet" class="text-info"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($statusCounts['washing'] ?? 0); ?></h5>
                        <h6>Washing</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <div class="dash-widget dash3">
                    <div class="dash-widgetimg">
                        <span><i data-feather="wind" class="text-warning"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($statusCounts['drying'] ?? 0); ?></h5>
                        <h6>Drying</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <div class="dash-widget">
                    <div class="dash-widgetimg">
                        <span><i data-feather="layers" class="text-primary"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($statusCounts['folding'] ?? 0); ?></h5>
                        <h6>Folding</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <div class="dash-widget dash1">
                    <div class="dash-widgetimg">
                        <span><i data-feather="check-circle" class="text-success"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($statusCounts['ready'] ?? 0); ?></h5>
                        <h6>Ready</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <div class="dash-widget dash2">
                    <div class="dash-widgetimg">
                        <span><i data-feather="package" class="text-dark"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($statusCounts['collected'] ?? 0); ?></h5>
                        <h6>Collected</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="<?php echo e(route('laundry.create')); ?>" class="btn btn-primary btn-lg">
                                <i data-feather="plus" class="me-2"></i>New Laundry Order
                            </a>
                            <a href="<?php echo e(route('laundry.qc.index')); ?>" class="btn btn-warning btn-lg">
                                <i data-feather="check-square" class="me-2"></i>Quality Check (<?php echo e($pendingQC); ?>)
                            </a>
                            <a href="<?php echo e(route('laundry.machines')); ?>" class="btn btn-info btn-lg">
                                <i data-feather="settings" class="me-2"></i>Machine Control
                            </a>
                            <button class="btn btn-secondary btn-lg" onclick="scanQRCode()">
                                <i data-feather="camera" class="me-2"></i>Scan QR Code
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Today's Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Orders Received
                                <span class="badge bg-primary rounded-pill"><?php echo e($todayOrders); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Ready for Collection
                                <span class="badge bg-success rounded-pill"><?php echo e($readyOrders); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Pending QC
                                <span class="badge bg-warning rounded-pill"><?php echo e($pendingQC); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Find Order</h5>
                    </div>
                    <div class="card-body">
                        <form id="search-order-form">
                            <div class="mb-3">
                                <label class="form-label">Order Number</label>
                                <input type="text" class="form-control" id="search_order_number" placeholder="e.g. LO20251219-0001">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="search" class="me-2"></i>Search
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Orders</h5>
                <a href="<?php echo e(route('laundry.orders')); ?>" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>QC</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('laundry.show', $order->id)); ?>">
                                        <strong><?php echo e($order->order_number); ?></strong>
                                    </a>
                                </td>
                                <td><?php echo e($order->customer_name); ?></td>
                                <td><?php echo e($order->total_services); ?> pcs</td>
                                <td><?php echo $order->getStatusBadge(); ?></td>
                                <td>
                                    <?php if($order->qc_passed): ?>
                                        <span class="badge bg-success">Passed</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($order->created_at->format('d M H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('laundry.show', $order->id)); ?>" class="btn btn-sm btn-primary">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No orders yet</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qr-scanner-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qr-reader" style="width: 100%;"></div>
                <p class="mt-3">Or enter QR code manually:</p>
                <input type="text" class="form-control" id="manual_qr_code" placeholder="Enter QR code">
                <button class="btn btn-primary mt-2" onclick="findByQR()">Find Order</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function scanQRCode() {
        $('#qr-scanner-modal').modal('show');
    }

    function findByQR() {
        var qrCode = $('#manual_qr_code').val();
        if (!qrCode) {
            Swal.fire('Error', 'Please enter a QR code', 'error');
            return;
        }
        
        $.ajax({
            url: '<?php echo e(route("laundry.scan-qr")); ?>',
            type: 'POST',
            data: { qr_code: qrCode, _token: '<?php echo e(csrf_token()); ?>' },
            success: function(response) {
                if (response.success) {
                    window.location.href = '/laundry/orders/' + response.order.id;
                } else {
                    Swal.fire('Not Found', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Order not found', 'error');
            }
        });
    }

    $('#search-order-form').on('submit', function(e) {
        e.preventDefault();
        var orderNumber = $('#search_order_number').val();
        if (!orderNumber) {
            Swal.fire('Error', 'Please enter order number', 'error');
            return;
        }
        
        $.ajax({
            url: '<?php echo e(route("laundry.find-order")); ?>',
            type: 'POST',
            data: { order_number: orderNumber, _token: '<?php echo e(csrf_token()); ?>' },
            success: function(response) {
                if (response.success) {
                    window.location.href = '/laundry/orders/' + response.order.id;
                } else {
                    Swal.fire('Not Found', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Order not found', 'error');
            }
        });
    });

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/laundry/dashboard.blade.php ENDPATH**/ ?>