<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta name="description" content="POS - Bootstrap Admin Template">
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, invoice, html5, responsive, Projects">
    <meta name="author" content="Dreamguys - Bootstrap Admin Template">
    <meta name="robots" content="noindex, nofollow">
    <title>Rapy</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(URL::asset('/build/img/favicon.png')); ?>">

    <?php echo $__env->make('layout.partials.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<?php if(Route::is(['chat'])): ?>

    <body class="main-chat-blk">
<?php endif; ?>
<?php if(!Route::is(['chat', 'under-maintenance', 'coming-soon', 'error-404', 'error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','login','success','success-2','success-3'])): ?>

    <body>
<?php endif; ?>
<?php if(Route::is(['under-maintenance', 'coming-soon', 'error-404', 'error-500'])): ?>

    <body class="error-page">
<?php endif; ?>
<?php if(Route::is(['two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','login','success','success-2','success-3'])): ?>

    <body class="account-page">
<?php endif; ?>
<?php $__env->startComponent('components.loader'); ?>
<?php echo $__env->renderComponent(); ?>

<!-- Impersonation Banner -->
<?php if(session('impersonate_from')): ?>
<div class="impersonation-banner" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 20px; text-align: center; position: fixed; top: 0; left: 0; right: 0; z-index: 9999; box-shadow: 0 2px 10px rgba(0,0,0,0.2);">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <span>
                <i data-feather="eye" style="width: 18px; height: 18px; margin-right: 8px;"></i>
                <strong>Viewing as: <?php echo e(auth()->user()->name); ?></strong>
                <span class="ms-2 opacity-75">(<?php echo e(auth()->user()->email); ?>)</span>
            </span>
            <a href="<?php echo e(route('superadmin.stop-impersonate')); ?>" 
               class="btn btn-sm btn-light" 
               style="padding: 4px 16px;">
                <i data-feather="log-out" style="width: 14px; height: 14px; margin-right: 4px;"></i>
                Back to Superadmin
            </a>
        </div>
    </div>
</div>
<div style="height: 46px;"></div>
<?php endif; ?>

<!-- Main Wrapper -->
<?php if(!Route::is(['lock-screen'])): ?>
    <div class="main-wrapper">
<?php endif; ?>
<?php if(Route::is(['lock-screen'])): ?>
    <div class="main-wrapper login-body">
<?php endif; ?>
<?php if(!Route::is(['under-maintenance', 'coming-soon','error-404','error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','login','success','success-2','success-3','lock-screen'])): ?>
    <?php echo $__env->make('layout.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php if(!Route::is(['pos', 'under-maintenance', 'coming-soon','error-404','error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','login','success','success-2','success-3','lock-screen'])): ?>
    <?php echo $__env->make('layout.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layout.partials.collapsed-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('layout.partials.horizontal-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->yieldContent('content'); ?>
</div>
<!-- /Main Wrapper -->
<?php echo $__env->make('layout.partials.theme-settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->startComponent('components.modalpopup'); ?>
<?php echo $__env->renderComponent(); ?>
<?php echo $__env->make('layout.partials.footer-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH C:\laragon\www\laundry\resources\views/layout/mainlayout.blade.php ENDPATH**/ ?>