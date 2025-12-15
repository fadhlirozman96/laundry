<!-- Header -->
<div class="header">

    <!-- Logo -->
    <div class="header-left active">
        <a href="<?php echo e(url('index')); ?>" class="logo logo-normal">
            <img src="<?php echo e(URL::asset('/build/img/logo.png')); ?>" alt="">
        </a>
        <a href="<?php echo e(url('index')); ?>" class="logo logo-white">
            <img src="<?php echo e(URL::asset('/build/img/logo-white.png')); ?>" alt="">
        </a>
        <a href="<?php echo e(url('index')); ?>" class="logo-small">
            <img src="<?php echo e(URL::asset('/build/img/logo-small.png')); ?>" alt="">
        </a>
        <a id="toggle_btn" href="javascript:void(0);">
            <i data-feather="chevrons-left" class="feather-16"></i>
        </a>
    </div>
    <!-- /Logo -->

    <a id="mobile_btn" class="mobile_btn" href="#sidebar">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <!-- Header Menu -->
    <ul class="nav user-menu">

        <!-- POS Button -->
        <li class="nav-item" style="flex: 1; max-width: 400px;">
            
            <a href="<?php echo e(url('pos')); ?>" >
                <button type="button" class="btn btn-primary"><i data-feather="shopping-cart" class="feather-18 me-2"></i>
                POS SYSTEM</button>
                
            </a>
        </li>
        <!-- /POS Button -->


        <!-- Select Store -->
        <?php if(auth()->guard()->check()): ?>
        <?php
            $userStores = auth()->user()->getAccessibleStores();
            $selectedStore = session('selected_store_id') ? \App\Models\Store::find(session('selected_store_id')) : ($userStores->first() ?? null);
        ?>
        <li class="nav-item dropdown has-arrow main-drop select-store-dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link select-store" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="<?php echo e(URL::asset('/build/img/store/store-01.png')); ?>" alt="Store Logo"
                            class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name"><?php echo e($selectedStore ? $selectedStore->name : 'Select Store'); ?></span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <?php $__empty_1 = true; $__currentLoopData = $userStores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <a href="<?php echo e(route('select-store', $store->id)); ?>" class="dropdown-item <?php echo e($selectedStore && $selectedStore->id == $store->id ? 'active' : ''); ?>">
                    <img src="<?php echo e(URL::asset('/build/img/store/store-01.png')); ?>" alt="Store Logo" class="img-fluid">
                    <?php echo e($store->name); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <span class="dropdown-item text-muted">No stores available</span>
                <?php endif; ?>
            </div>
        </li>
        <?php endif; ?>
        <!-- /Select Store -->

       

        <li class="nav-item nav-item-box">
            <a href="<?php echo e(url('general-settings')); ?>"><i data-feather="settings"></i></a>
        </li>
        <li class="nav-item dropdown has-arrow main-drop">
            <a href="javascript:void(0);" class="dropdown-toggle nav-link userset" data-bs-toggle="dropdown">
                <span class="user-info">
                    <span class="user-letter">
                        <img src="<?php echo e(URL::asset('/build/img/profiles/avator1.jpg')); ?>" alt=""
                            class="img-fluid">
                    </span>
                    <span class="user-detail">
                        <span class="user-name"><?php echo e(auth()->check() ? auth()->user()->name : 'Guest'); ?></span>
                        <span class="user-role"><?php echo e(auth()->check() && auth()->user()->role ? auth()->user()->role->display_name : ''); ?></span>
                    </span>
                </span>
            </a>
            <div class="dropdown-menu menu-drop-user">
                <div class="profilename">
                    <div class="profileset">
                        <span class="user-img"><img src="<?php echo e(URL::asset('/build/img/profiles/avator1.jpg')); ?>"
                                alt="">
                            <span class="status online"></span></span>
                        <div class="profilesets">
                            <h6><?php echo e(auth()->check() ? auth()->user()->name : 'Guest'); ?></h6>
                            <h5><?php echo e(auth()->check() && auth()->user()->role ? auth()->user()->role->display_name : ''); ?></h5>
                        </div>
                    </div>
                    <hr class="m-0">
                    <a class="dropdown-item" href="<?php echo e(url('profile')); ?>"> <i class="me-2"
                            data-feather="user"></i> My Profile</a>
                    <?php if(auth()->check() && (auth()->user()->isSuperAdmin() || auth()->user()->isBusinessOwner())): ?>
                    <a class="dropdown-item" href="<?php echo e(url('general-settings')); ?>"><i class="me-2"
                            data-feather="settings"></i>Settings</a>
                    <?php endif; ?>
                    <hr class="m-0">
                    <a class="dropdown-item logout pb-0" href="<?php echo e(route('logout')); ?>"><img
                            src="<?php echo e(URL::asset('/build/img/icons/log-out.svg')); ?>" class="me-2"
                            alt="img">Logout</a>
                </div>
            </div>
        </li>
    </ul>
    <!-- /Header Menu -->

    <!-- Mobile Menu -->
    <div class="dropdown mobile-user-menu">
        <a href="javascript:void(0);" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"
            aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="<?php echo e(url('profile')); ?>">My Profile</a>
            <a class="dropdown-item" href="<?php echo e(url('general-settings')); ?>">Settings</a>
            <a class="dropdown-item" href="<?php echo e(route('logout')); ?>">Logout</a>
        </div>
    </div>
    <!-- /Mobile Menu -->
</div>
<!-- /Header -->
<?php /**PATH C:\laragon\www\laundry\resources\views/layout/partials/header.blade.php ENDPATH**/ ?>