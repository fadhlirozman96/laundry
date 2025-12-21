<?php $page = 'store-list'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Store List
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Manage your Store
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    Add Store
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('show_password_modal')): ?>
                <!-- Password Display Modal -->
                <div class="modal fade" id="passwordModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Person In Charge Account Created</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="alert alert-warning">
                                    <i data-feather="alert-triangle" class="me-2"></i>
                                    <strong>Important:</strong> Please save this password. It will not be shown again.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Name:</strong></label>
                                    <p class="mb-0"><?php echo e(session('person_in_charge_name')); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Email:</strong></label>
                                    <p class="mb-0"><?php echo e(session('person_in_charge_email')); ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Auto-Generated Password:</strong></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control font-monospace" id="generatedPassword" value="<?php echo e(session('auto_generated_password')); ?>" readonly style="font-weight: 600; font-size: 16px;">
                                        <button class="btn btn-outline-secondary" type="button" onclick="copyPassword()">
                                            <i data-feather="copy"></i> Copy
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label"><strong>Role:</strong></label>
                                    <p class="mb-0"><span class="badge badge-light-info">Store Admin</span> (Automatically assigned with full store management permissions)</p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I've Saved the Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="<?php echo e(URL::asset('/build/img/icons/closes.svg')); ?>" alt="img"></span>
                                </a>
                            </div>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="zap" class="info-img"></i>
                                        <select class="select" id="filter-store">
                                            <option value="">All Stores</option>
                                            <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($store->id); ?>"><?php echo e($store->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="calendar" class="info-img"></i>
                                        <div class="input-groupicon">
                                            <input type="text" class="datetimepicker" placeholder="Choose Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table datanew" id="store-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">#</th>
                                    <th>Store name</th>
                                    <th>Owner</th>
                                    <th>Person In Charge</th>
                                    <th>PIC Email</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php
                                        $personInCharge = $store->users->first();
                                    ?>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($store->name); ?></td>
                                    <td><?php echo e($store->owner ? $store->owner->name : 'N/A'); ?></td>
                                    <td><?php echo e($personInCharge ? $personInCharge->name : 'N/A'); ?></td>
                                    <td><?php echo e($personInCharge ? $personInCharge->email : 'N/A'); ?></td>
                                    <td><?php echo e($store->phone ?? 'N/A'); ?></td>
                                    <td>
                                        <?php if($store->is_active): ?>
                                            <span class="badge badge-linesuccess">Active</span>
                                        <?php else: ?>
                                            <span class="badge badge-linedanger">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="javascript:void(0);" 
                                                data-bs-toggle="modal" data-bs-target="#edit-stores"
                                                onclick="editStore(<?php echo e($store->id); ?>, <?php echo e(json_encode($store->name)); ?>, <?php echo e(json_encode($store->phone)); ?>, <?php echo e(json_encode($store->email)); ?>, <?php echo e(json_encode($store->address)); ?>)"
                                                title="Edit Store">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="me-2 p-2" href="javascript:void(0);" 
                                                onclick="toggleStoreStatus(<?php echo e($store->id); ?>, <?php echo e($store->is_active ? 'true' : 'false'); ?>)"
                                                title="<?php echo e($store->is_active ? 'Deactivate Store' : 'Activate Store'); ?>">
                                                <?php if($store->is_active): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                                <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc3545" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path><line x1="12" y1="2" x2="12" y2="12"></line></svg>
                                                <?php endif; ?>
                                            </a>
                                            <a class="confirm-text p-2" href="javascript:void(0);" 
                                                onclick="deleteStore(<?php echo e($store->id); ?>)"
                                                title="Delete Store">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function editStore(id, name, phone, email, address) {
    document.getElementById('edit-store-id').value = id;
    document.getElementById('edit-store-name').value = name;
    document.getElementById('edit-store-phone').value = phone || '';
    document.getElementById('edit-store-email').value = email || '';
    document.getElementById('edit-store-address').value = address || '';
    document.getElementById('edit-store-form').action = '/stores/' + id;
}

function deleteStore(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/stores/' + id;
            
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrf);
            
            var method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function toggleStoreStatus(id, isActive) {
    var action = isActive ? 'deactivate' : 'activate';
    var title = isActive ? 'Deactivate Store?' : 'Activate Store?';
    var message = isActive 
        ? 'The person in charge will not be able to access this store.' 
        : 'The person in charge will be able to access this store again.';
    var confirmText = isActive ? 'Yes, deactivate!' : 'Yes, activate!';
    var icon = isActive ? 'warning' : 'question';
    
    Swal.fire({
        title: title,
        text: message,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: isActive ? '#d33' : '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: confirmText
    }).then((result) => {
        if (result.isConfirmed) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/stores/' + id + '/toggle-status';
            
            var csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrf);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
}


// Initialize DataTable for store-table (since script.js excludes it)
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#store-table')) {
        $('#store-table').DataTable({
            "bFilter": true,
            "sDom": 'fBtlpi',
            "ordering": true,
            "language": {
                search: ' ',
                sLengthMenu: '_MENU_',
                searchPlaceholder: "Search",
                info: "_START_ - _END_ of _TOTAL_ items",
                paginate: {
                    next: ' <i class="fa fa-angle-right"></i>',
                    previous: '<i class="fa fa-angle-left"></i> '
                }
            },
            initComplete: function(settings, json) {
                $('.dataTables_filter').appendTo('#tableSearch');
                $('.dataTables_filter').appendTo('.search-input');
            }
        });
    }
});

// Show password modal if needed
<?php if(session('show_password_modal')): ?>
$(document).ready(function() {
    var passwordModal = new bootstrap.Modal(document.getElementById('passwordModal'));
    passwordModal.show();
    
    // Reinitialize feather icons in modal
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
<?php endif; ?>

// Copy password function
function copyPassword() {
    var passwordInput = document.getElementById('generatedPassword');
    passwordInput.select();
    passwordInput.setSelectionRange(0, 99999); // For mobile devices
    document.execCommand('copy');
    
    // Show feedback
    var btn = event.target.closest('button');
    var originalText = btn.innerHTML;
    btn.innerHTML = '<i data-feather="check"></i> Copied!';
    btn.classList.add('btn-success');
    btn.classList.remove('btn-outline-secondary');
    
    setTimeout(function() {
        btn.innerHTML = originalText;
        btn.classList.remove('btn-success');
        btn.classList.add('btn-outline-secondary');
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    }, 2000);
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/store-list.blade.php ENDPATH**/ ?>