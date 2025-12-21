<?php $page = 'signin'; ?>

<?php $__env->startSection('content'); ?>
    <div class="account-content">
        <div class="login-wrapper bg-img">
            <div class="login-content">
                <form action="<?php echo e(route('signin.custom')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="login-userset">
                        <div class="login-logo logo-normal">
                            <img src="<?php echo e(URL::asset('/build/img/logo.png')); ?>" alt="img">
                        </div>
                        <a href="<?php echo e(url('index')); ?>" class="login-logo logo-white">
                            <img src="<?php echo e(URL::asset('/build/img/logo-white.png')); ?>" alt="">
                        </a>
                        <div class="login-userheading">
                            <h3>Sign In</h3>
                            <h4>Access the Dreamspos panel using your email and passcode.</h4>
                        </div>
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Login Failed!</strong><br>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php echo e($error); ?><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        <div class="form-login mb-3">
                            <label class="form-label">Email Address</label>
                            <div class="form-addons">
                                <input type="text" class="form-control" id="email" name="email"
                                    value="<?php echo e(old('email', 'superadmin@gmail.com')); ?>">
                                <img src="<?php echo e(URL::asset('/build/img/icons/mail.svg')); ?>" alt="img">
                            </div>
                        </div>
                        <div class="form-login mb-3">
                            <label class="form-label">Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input form-control" id="password" name="password"
                                    value="password">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login authentication-check">
                            <div class="row">
                                <div class="col-12 d-flex align-items-center justify-content-between">
                                    <div class="custom-control custom-checkbox">
                                        <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                            <input type="checkbox" class="form-control">
                                            <span class="checkmarks"></span>Remember me
                                        </label>
                                    </div>
                                    <div class="text-end">
                                        <a class="forgot-link" href="<?php echo e(url('forgot-password')); ?>">Forgot Password?</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Sign In</button>
                        </div>
                        <div class="signinform">
                            <h4>New on our platform?<a href="<?php echo e(url('register')); ?>" class="hover-a"> Create an account</a>
                            </h4>
                        </div>
                        <div class="form-setlogin or-text">
                            <h4>OR</h4>
                        </div>
                        <div class="form-sociallink">
                            <ul class="d-flex">
                                <li>
                                    <a href="javascript:void(0);" class="facebook-logo">
                                        <img src="<?php echo e(URL::asset('/build/img/icons/facebook-logo.svg')); ?>" alt="Facebook">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);">
                                        <img src="<?php echo e(URL::asset('/build/img/icons/google.png')); ?>" alt="Google">
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0);" class="apple-logo">
                                        <img src="<?php echo e(URL::asset('/build/img/icons/apple-logo.svg')); ?>" alt="Apple">
                                    </a>
                                </li>

                            </ul>
                            <div class="my-4 d-flex justify-content-center align-items-center copyright-text">
                                <p>Copyright &copy; 2023 DreamsPOS. All rights reserved</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/signin.blade.php ENDPATH**/ ?>