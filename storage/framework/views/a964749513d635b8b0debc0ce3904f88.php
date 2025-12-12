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
    <title>Dreams Pos Admin Template</title>

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo e(URL::asset('/build/img/favicon.png')); ?>">

    <?php echo $__env->make('layout.partials.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<?php if(Route::is(['chat'])): ?>

    <body class="main-chat-blk">
<?php endif; ?>
<?php if(!Route::is(['chat', 'under-maintenance', 'coming-soon', 'error-404', 'error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','success','success-2','success-3'])): ?>

    <body>
<?php endif; ?>
<?php if(Route::is(['under-maintenance', 'coming-soon', 'error-404', 'error-500'])): ?>

    <body class="error-page">
<?php endif; ?>
<?php if(Route::is(['two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','success','success-2','success-3'])): ?>

    <body class="account-page">
<?php endif; ?>
<?php $__env->startComponent('components.loader'); ?>
<?php echo $__env->renderComponent(); ?>
<!-- Main Wrapper -->
<?php if(!Route::is(['lock-screen'])): ?>
    <div class="main-wrapper">
<?php endif; ?>
<?php if(Route::is(['lock-screen'])): ?>
    <div class="main-wrapper login-body">
<?php endif; ?>
<?php if(!Route::is(['under-maintenance', 'coming-soon','error-404','error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','success','success-2','success-3','lock-screen'])): ?>
    <?php echo $__env->make('layout.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php if(!Route::is(['pos', 'under-maintenance', 'coming-soon','error-404','error-500','two-step-verification-3','two-step-verification-2','two-step-verification','email-verification-3','email-verification-2','email-verification','reset-password-3','reset-password-2','reset-password','forgot-password-3','forgot-password-2','forgot-password','register-3','register-2','register','signin-3','signin-2','signin','success','success-2','success-3','lock-screen'])): ?>
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
<?php /**PATH C:\laragon\www\laundry3\resources\views/layout/mainlayout.blade.php ENDPATH**/ ?>