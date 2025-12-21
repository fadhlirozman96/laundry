<?php $page = 'audit-trail'; ?>

<?php $__env->startSection('content'); ?>
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Audit Trail</h4>
                <h6>Track all system activities and changes</h6>
            </div>
            <div class="page-btn">
                <a href="<?php echo e(route('audit-trail.export')); ?>?<?php echo e(http_build_query(request()->all())); ?>" class="btn btn-outline-primary me-2">
                    <i data-feather="download" class="me-2"></i>Export CSV
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-sm-6">
                <div class="dash-widget dash1">
                    <div class="dash-widgetimg">
                        <span><i data-feather="activity" class="text-primary"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($todayCount); ?></h5>
                        <h6>Today's Activities</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="dash-widget dash2">
                    <div class="dash-widgetimg">
                        <span><i data-feather="calendar" class="text-info"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e($weekCount); ?></h5>
                        <h6>This Week</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="dash-widget dash3">
                    <div class="dash-widgetimg">
                        <span><i data-feather="users" class="text-success"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e(count($users)); ?></h5>
                        <h6>Active Users</h6>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="dash-widget">
                    <div class="dash-widgetimg">
                        <span><i data-feather="home" class="text-warning"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5><?php echo e(count($stores)); ?></h5>
                        <h6>Stores Monitored</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('audit-trail.index')); ?>" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select class="form-control" name="action">
                            <option value="">All Actions</option>
                            <?php $__currentLoopData = $actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(request('action') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Module</label>
                        <select class="form-control" name="module">
                            <option value="">All Modules</option>
                            <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>" <?php echo e(request('module') == $key ? 'selected' : ''); ?>><?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">User</label>
                        <select class="form-control" name="user_id">
                            <option value="">All Users</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($u->id); ?>" <?php echo e(request('user_id') == $u->id ? 'selected' : ''); ?>><?php echo e($u->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Store</label>
                        <select class="form-control" name="store_id">
                            <option value="">All Stores</option>
                            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s->id); ?>" <?php echo e(request('store_id') == $s->id ? 'selected' : ''); ?>><?php echo e($s->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">From</label>
                        <input type="date" class="form-control" name="date_from" value="<?php echo e(request('date_from')); ?>">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">To</label>
                        <input type="date" class="form-control" name="date_to" value="<?php echo e(request('date_to')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="<?php echo e(route('audit-trail.index')); ?>" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Activity Log Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Date/Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Module</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Store</th>
                                <th>IP</th>
                                <th style="width: 80px;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <small><?php echo e($log->created_at->format('d M Y')); ?></small><br>
                                    <small class="text-muted"><?php echo e($log->created_at->format('H:i:s')); ?></small>
                                </td>
                                <td>
                                    <strong><?php echo e($log->user_name); ?></strong><br>
                                    <small class="text-muted"><?php echo e($log->user_role); ?></small>
                                </td>
                                <td><?php echo $log->getActionBadge(); ?></td>
                                <td>
                                    <span class="badge bg-light text-dark"><?php echo e(ucfirst($log->module ?? 'General')); ?></span>
                                </td>
                                <td>
                                    <?php echo e($log->model_name ?? '-'); ?>

                                    <?php if($log->model_identifier): ?>
                                        <br><small class="text-muted"><?php echo e($log->model_identifier); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td style="max-width: 250px;">
                                    <span class="text-truncate d-inline-block" style="max-width: 250px;" title="<?php echo e($log->description); ?>">
                                        <?php echo e($log->description); ?>

                                    </span>
                                </td>
                                <td>
                                    <small><?php echo e($log->store_name ?? '-'); ?></small>
                                </td>
                                <td>
                                    <small class="text-muted" title="<?php echo e($log->browser); ?> / <?php echo e($log->platform); ?>"><?php echo e($log->ip_address ?? '-'); ?></small>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" onclick="viewDetails(<?php echo e($log->id); ?>)" title="View Details">
                                        <i data-feather="eye"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-2">No activity logs found</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($logs->appends(request()->query())->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="details-modal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Activity Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="details-content">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .dash-widget {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .table td {
        vertical-align: middle;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function viewDetails(id) {
        $('#details-content').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);
        $('#details-modal').modal('show');

        $.get('/audit-trail/' + id, function(log) {
            var html = `
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">User</h6>
                        <p class="mb-0"><strong>${log.user_name}</strong> (${log.user_role})</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-1">Date/Time</h6>
                        <p class="mb-0">${new Date(log.created_at).toLocaleString()}</p>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">Action</h6>
                        <p class="mb-0"><span class="badge bg-${getActionColor(log.action)}">${log.action_label}</span></p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">Module</h6>
                        <p class="mb-0">${log.module ? log.module.charAt(0).toUpperCase() + log.module.slice(1) : 'General'}</p>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted mb-1">Store</h6>
                        <p class="mb-0">${log.store_name || '-'}</p>
                    </div>
                </div>
                <div class="mb-4">
                    <h6 class="text-muted mb-1">Subject</h6>
                    <p class="mb-0">${log.model_name || '-'} ${log.model_identifier ? '(' + log.model_identifier + ')' : ''}</p>
                </div>
                <div class="mb-4">
                    <h6 class="text-muted mb-1">Description</h6>
                    <p class="mb-0">${log.description}</p>
                </div>
            `;

            // Show changed fields if update
            if (log.action === 'update' && log.changed_fields && log.changed_fields.length > 0) {
                html += `
                    <div class="mb-4">
                        <h6 class="text-muted mb-2">Changed Fields</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Field</th>
                                        <th>Old Value</th>
                                        <th>New Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;
                log.changed_fields.forEach(function(field) {
                    var oldVal = log.old_values ? (log.old_values[field] ?? '-') : '-';
                    var newVal = log.new_values ? (log.new_values[field] ?? '-') : '-';
                    html += `
                        <tr>
                            <td><strong>${field}</strong></td>
                            <td class="text-danger">${formatValue(oldVal)}</td>
                            <td class="text-success">${formatValue(newVal)}</td>
                        </tr>
                    `;
                });
                html += `
                                </tbody>
                            </table>
                        </div>
                    </div>
                `;
            }

            // Technical details
            html += `
                <div class="border-top pt-3">
                    <h6 class="text-muted mb-2">Technical Details</h6>
                    <div class="row">
                        <div class="col-md-4">
                            <small class="text-muted">IP Address</small><br>
                            <code>${log.ip_address || '-'}</code>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Browser</small><br>
                            <code>${log.browser || '-'}</code>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Platform</small><br>
                            <code>${log.platform || '-'}</code>
                        </div>
                    </div>
                </div>
            `;

            $('#details-content').html(html);
        }).fail(function() {
            $('#details-content').html('<div class="text-center text-danger py-4">Error loading details</div>');
        });
    }

    function getActionColor(action) {
        var colors = {
            'create': 'success',
            'read': 'info',
            'update': 'warning',
            'delete': 'danger',
            'login': 'primary',
            'logout': 'secondary',
            'export': 'info',
            'import': 'info'
        };
        return colors[action] || 'secondary';
    }

    function formatValue(val) {
        if (val === null || val === undefined) return '-';
        if (typeof val === 'object') return JSON.stringify(val);
        if (typeof val === 'boolean') return val ? 'Yes' : 'No';
        return String(val).substring(0, 100);
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/audit-trail/index.blade.php ENDPATH**/ ?>