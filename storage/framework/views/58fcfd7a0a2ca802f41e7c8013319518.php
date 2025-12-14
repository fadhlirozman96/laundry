<?php $page = 'signin-2'; ?>

<?php $__env->startSection('content'); ?>
    <div class="account-content">
        <div class="login-wrapper">
            <div class="login-content">
                <form action="index">
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
                        <div class="form-login">
                            <label>Email Address</label>
                            <div class="form-addons">
                                <input type="text" class="form-control">
                                <img src="<?php echo e(URL::asset('/build/img/icons/mail.svg')); ?>" alt="img">
                            </div>
                        </div>
                        <div class="form-login">
                            <label>Password</label>
                            <div class="pass-group">
                                <input type="password" class="pass-input">
                                <span class="fas toggle-password fa-eye-slash"></span>
                            </div>
                        </div>
                        <div class="form-login authentication-check">
                            <div class="row">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <label class="checkboxs ps-4 mb-0 pb-0 line-height-1">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <a class="forgot-link" href="<?php echo e(url('forgot-password-2')); ?>">Forgot Password?</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-login">
                            <button type="submit" class="btn btn-login">Sign In</button>
                        </div>
                        <div class="signinform">
                            <h4>New on our platform?<a href="<?php echo e(url('register-2')); ?>" class="hover-a"> Create an account</a>
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
            <div class="login-img">
                <img src="<?php echo e(URL::asset('/build/img/authentication/login02.png')); ?>" alt="img">
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/signin-2.blade.php ENDPATH**/ ?>