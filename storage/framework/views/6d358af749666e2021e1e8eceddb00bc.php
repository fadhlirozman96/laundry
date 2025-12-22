<?php $page = 'profile'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Profile</h4>
                    <h6>User Profile</h6>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Profile Info Card -->
            <div class="card">
                <div class="card-body">
                    <div class="profile-set">
                        <div class="profile-head"></div>
                        <div class="profile-top">
                            <div class="profile-content">
                                <div class="profile-contentimg">
                                    <img src="<?php echo e($user->avatar ?? URL::asset('/build/img/customer/customer5.jpg')); ?>" alt="Profile" id="profile-image">
                                    <div class="profileupload">
                                        <input type="file" id="imgInp" accept="image/*">
                                        <a href="javascript:void(0);"><img src="<?php echo e(URL::asset('/build/img/icons/edit-set.svg')); ?>" alt="Edit"></a>
                                    </div>
                                </div>
                                <div class="profile-contentname">
                                    <h2><?php echo e($user->name); ?></h2>
                                    <h4>
                                        <?php $role = $user->roles->first(); ?>
                                        <?php echo e($role ? ucfirst(str_replace('_', ' ', $role->name)) : 'User'); ?>

                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="<?php echo e(route('profile.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row mt-4">
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" value="<?php echo e(old('name', $user->name)); ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="<?php echo e(old('email', $user->email)); ?>" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" value="<?php echo e(old('phone', $user->phone)); ?>">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Member Since</label>
                                    <input type="text" class="form-control" value="<?php echo e($user->created_at->format('d M Y')); ?>" disabled>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-submit me-2">Update Profile</button>
                                <a href="<?php echo e(route('index')); ?>" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Change Password</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('profile.password')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <div class="row">
                            <div class="col-lg-4 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-input form-control" name="current_password" required>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">New Password <span class="text-danger">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-input form-control" name="password" required>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="input-blocks">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" class="pass-input form-control" name="password_confirmation" required>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-submit">Change Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Password toggle
    $('.toggle-password').on('click', function() {
        var input = $(this).siblings('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        }
    });

    // Profile image preview
    $('#imgInp').on('change', function() {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#profile-image').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/profile.blade.php ENDPATH**/ ?>