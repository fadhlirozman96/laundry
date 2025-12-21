<?php if(Route::is(['add-product'])): ?>
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4><?php echo e($title); ?></h4>
                <h6><?php echo e($li_1); ?></h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <div class="page-btn">
                    <a href="<?php echo e($li_2); ?>" class="btn btn-secondary"><i data-feather="arrow-left"
                            class="me-2"></i><?php echo e($li_3); ?></a>
                </div>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>

    </div>
<?php endif; ?>

<?php if(
    !Route::is([
        'add-product',
        'chart-apex',
        'chart-c3',
        'chart-flot',
        'chart-js',
        'chart-morris',
        'chart-peity',
        'data-tables',
        'tables-basic',
        'form-basic-inputs',
        'form-checkbox-radios',
        'form-input-groups',
        'form-grid-gutters',
        'form-select',
        'form-mask',
        'form-fileupload',
        'form-horizontal',
        'form-vertical',
        'form-floating-labels',
        'form-validation',
        'form-select2',
        'form-wizard',
        'icon-fontawesome',
        'icon-feather',
        'icon-ionic',
        'icon-material',
        'icon-pe7',
        'icon-simpleline',
        'icon-themify',
        'icon-weather',
        'icon-typicon',
        'icon-flag',
        'ui-clipboard',
        'ui-counter',
        'ui-drag-drop',
        'ui-rating',
        'ui-ribbon',
        'ui-scrollbar',
        'ui-stickynote',
        'ui-text-editor',
        'ui-timeline',
    ])): ?>
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4><?php echo e($title); ?></h4>
                <h6><?php echo e($li_1); ?></h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                        src="<?php echo e(URL::asset('/build/img/icons/pdf.svg')); ?>" alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img
                        src="<?php echo e(URL::asset('/build/img/icons/excel.svg')); ?>" alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer"
                        class="feather-rotate-ccw"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh"><i data-feather="rotate-ccw"
                        class="feather-rotate-ccw"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                        data-feather="chevron-up" class="feather-chevron-up"></i></a>
            </li>
        </ul>
        <?php if(Route::is(['warranty'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i> <?php echo e($li_2); ?></a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['warehouse'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i><?php echo e($li_2); ?></a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['varriant-attributes'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i> <?php echo e($li_2); ?></a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['units'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i> <?php echo e($li_2); ?></a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['suppliers'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Supplier</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['sub-categories'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-category"><i
                        data-feather="plus-circle" class="me-2"></i> Add Sub Category</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['store-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-stores"><i
                        data-feather="plus-circle" class="me-2"></i> Add Store</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['stock-transfer'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New</a>
            </div>
            <div class="page-btn import">
                <a href="#" class="btn btn-added color" data-bs-toggle="modal" data-bs-target="#view-notes"><i
                        data-feather="download" class="me-2"></i>Import Transfer</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['stock-adjustment'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['states'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New State</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['shift'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-shift"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Shift</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['sales-returns'])): ?>
            <div class="page-btn">
                <a href="<?php echo e(url('createsalesreturn')); ?>" class="btn btn-added" data-bs-toggle="modal"
                    data-bs-target="#add-sales-new"><i data-feather="plus-circle" class="me-2"></i>Add New Sales
                    Return</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['sales-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-sales-new"><i
                        data-feather="plus-circle" class="me-2"></i> Add New Sales</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['quotation-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Quotation</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['purchase-returns'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-sales-new">
                    <i data-feather="plus-circle" class="me-2"></i>Add Purchase Return
                </a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['payroll-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-payroll"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Payroll</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['manage-stocks'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['leaves-employee'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Apply Leave</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['leaves-admin'])): ?>
            <div class="page-btn">
                <a href="<?php echo e(url('leave-types')); ?>" class="btn btn-secondary me-2">Leave Types</a>
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-leave"><i
                        data-feather="plus-circle" class="me-2"></i>Add Leave Request</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['leave-types'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-leave-type"><i
                        data-feather="plus-circle" class="me-2"></i>Add Leave Type</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['holidays'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-holiday"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Holiday</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['expense-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i> Add New Expense</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['expense-category'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i> Add Expense Category</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['employees-grid'])): ?>
            <div class="page-btn">
                <a href="<?php echo e(url('add-employee')); ?>" class="btn btn-added"><i data-feather="plus-circle"
                        class="me-2"></i>Add New Employee</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['designation'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-designation"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Designation</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['department-grid'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-department"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Department</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['customers'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Customer</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['coupons'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Coupons</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['countries'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Country</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['category-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-category"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Category</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['brand-list'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-brand"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Brand</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['attendance-admin'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-attendance"><i
                        data-feather="plus-circle" class="me-2"></i>Add New Attendance</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['users'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i>Add New User</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['roles-permissions'])): ?>
            <div class="page-btn">
                <a href="#" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-units"><i
                        data-feather="plus-circle" class="me-2"></i> Add New Role</a>
            </div>
        <?php endif; ?>
        <?php if(Route::is(['product-list'])): ?>
            <div class="page-btn">
                <a href="<?php echo e($li_2); ?>" class="btn btn-added"><i data-feather="plus-circle"
                        class="me-2"></i><?php echo e($li_3); ?></a>
            </div>
            <div class="page-btn import">
                <a href="#" class="btn btn-added color" data-bs-toggle="modal" data-bs-target="#view-notes"><i
                        data-feather="download" class="me-2"></i><?php echo e($li_4); ?></a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if(Route::is([
        'chart-apex',
        'chart-c3',
        'chart-flot',
        'chart-js',
        'chart-morris',
        'chart-peity',
        'data-tables',
        'tables-basic',
        'form-basic-inputs',
        'form-checkbox-radios',
        'form-input-groups',
        'form-grid-gutters',
        'form-select',
        'form-mask',
        'form-fileupload',
        'form-horizontal',
        'form-vertical',
        'form-floating-labels',
        'form-validation',
        'form-select2',
        'form-wizard',
        'icon-fontawesome',
        'icon-feather',
        'icon-ionic',
        'icon-material',
        'icon-pe7',
        'icon-simpleline',
        'icon-themify',
        'icon-weather',
        'icon-typicon',
        'icon-flag',
        'ui-clipboard',
        'ui-counter',
        'ui-drag-drop',
        'ui-rating',
        'ui-ribbon',
        'ui-scrollbar',
        'ui-stickynote',
        'ui-text-editor',
        'ui-timeline',
    ])): ?>
    <!-- Page Header -->
    <div class="page-header">
        <div class="row">
            <div class="col-sm-12">
                <h3 class="page-title"><?php echo e($title); ?></h3>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(url('index')); ?>"><?php echo e($li_1); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo e($li_2); ?></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Page Header -->
<?php endif; ?>
<?php /**PATH C:\laragon\www\laundry\resources\views/components/breadcrumb.blade.php ENDPATH**/ ?>