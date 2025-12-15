<?php $page = 'edit-product'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Edit Product</h4>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <div class="page-btn">
                            <a href="<?php echo e(url('product-list')); ?>" class="btn btn-secondary"><i data-feather="arrow-left"
                                    class="me-2"></i>Back to Product</a>
                        </div>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Collapse" id="collapse-header"><i
                                data-feather="chevron-up" class="feather-chevron-up"></i></a>
                    </li>
                </ul>

            </div>
            <!-- /add -->
            
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Validation Errors:</strong>
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('products.update', $product->id)); ?>" method="POST" enctype="multipart/form-data" id="edit-product-form">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="card">
                    <div class="card-body add-product pb-0">
                        <div class="accordion-card-one accordion" id="accordionExample0">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingImage">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseImage"
                                        aria-controls="collapseImage">
                                        <div class="addproduct-icon list">
                                            <h5><i data-feather="image" class="add-info"></i><span>Product Image</span></h5>
                                            <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseImage" class="accordion-collapse collapse show"
                                    aria-labelledby="headingImage" data-bs-parent="#accordionExample0">
                                    <div class="accordion-body">
                                        <div class="text-editor add-list add">
                                            <div class="col-lg-12">
                                                <div class="add-choosen" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-start;">
                                                    <div class="input-blocks">
                                                        <div class="image-upload" id="image-upload-wrapper-edit">
                                                            <input type="file" name="image" id="image-input-edit" accept="image/*">
                                                            <div class="image-uploads">
                                                                <i data-feather="plus-circle"
                                                                    class="plus-down-add me-0"></i>
                                                                <h4><?php echo e($product->image ? 'Change Image' : 'Add Images'); ?></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if($product->image): ?>
                                                    <div id="current-image-container">
                                                        <div class="phone-img" style="position: relative !important; display: block !important; width: 150px !important; height: 150px !important;">
                                                            <img src="<?php echo e(URL::asset($product->image)); ?>" alt="Current Image" style="width: 100% !important; height: 100% !important; object-fit: contain !important; border-radius: 10px !important; border: 2px solid #ddd !important; background: #f9f9f9 !important;">
                                                            <small class="d-block text-center mt-1">Current Image</small>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>

                                                    <div id="image-preview-container-edit" class="image-preview-box" style="display: none !important;">
                                                        <div class="phone-img" style="position: relative !important; display: block !important; width: 150px !important; height: 150px !important;">
                                                            <img id="image-preview-edit" src="" alt="Preview" style="width: 100% !important; height: 100% !important; object-fit: contain !important; border-radius: 10px !important; border: 2px solid #ddd !important; background: #f9f9f9 !important;">
                                                            <a href="javascript:void(0);" onclick="removeImageEdit()" style="position: absolute !important; top: -8px !important; right: -8px !important; background: #ea5455 !important; border-radius: 50% !important; width: 28px !important; height: 28px !important; display: flex !important; align-items: center !important; justify-content: center !important; box-shadow: 0 2px 5px rgba(0,0,0,0.2) !important; cursor: pointer !important; z-index: 999 !important;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                            </a>
                                                            <small class="d-block text-center mt-1">New Image</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-card-one accordion" id="accordionExample">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingOne">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                        aria-controls="collapseOne">
                                        <div class="addproduct-icon">
                                            <h5><i data-feather="info" class="add-info"></i><span>Product Information</span>
                                            </h5>
                                            <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                    class="chevron-down-add"></i></a>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Store</label>
                                                    <select name="store_id" class="select">
                                                        <option value="">Choose</option>
                                                        <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($store->id); ?>" <?php echo e(old('store_id', $product->store_id) == $store->id ? 'selected' : ''); ?>>
                                                                <?php echo e($store->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-5 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $product->name)); ?>" required>
                                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="text-danger"><?php echo e($message); ?></span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product list">
                                                    <label>SKU <span class="text-danger">*</span></label>
                                                    <input type="text" name="sku" class="form-control list" placeholder="Enter SKU" value="<?php echo e(old('sku', $product->sku)); ?>" required>
                                                    <button type="button" class="btn btn-primaryadd" onclick="generateSKU()">
                                                        Generate Code
                                                    </button>
                                                    <?php $__errorArgs = ['sku'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="text-danger"><?php echo e($message); ?></span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Category</label>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#add-units-category"><i
                                                                data-feather="plus-circle"
                                                                class="plus-down-add"></i><span>Add New</span></a>
                                                    </div>
                                                    <select name="category_id" class="select">
                                                        <option value="">Choose</option>
                                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $product->category_id) == $category->id ? 'selected' : ''); ?>>
                                                                <?php echo e($category->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <div class="add-newplus">
                                                        <label class="form-label">Unit</label>
                                                        <a href="javascript:void(0);" data-bs-toggle="modal"
                                                            data-bs-target="#add-unit"><i data-feather="plus-circle"
                                                                class="plus-down-add"></i><span>Add New</span></a>
                                                    </div>
                                                    <select name="unit_id" class="select">
                                                        <option value="">Choose</option>
                                                        <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($unit->id); ?>" <?php echo e(old('unit_id', $product->unit_id) == $unit->id ? 'selected' : ''); ?>>
                                                                <?php echo e($unit->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Slug</label>
                                                    <input type="text" name="slug" class="form-control" value="<?php echo e(old('slug', $product->slug)); ?>" placeholder="Auto-generated from name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="input-blocks summer-description-box transfer mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control h-100" rows="3"><?php echo e(old('description', $product->description)); ?></textarea>
                                                    <p class="mt-1">Maximum 60 Characters</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-card-one accordion" id="accordionExample2">
                            <div class="accordion-item">
                                <div class="accordion-header" id="headingTwo">
                                    <div class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                        aria-controls="collapseTwo">
                                        <div class="text-editor add-list">
                                            <div class="addproduct-icon list icon">
                                                <h5><i data-feather="life-buoy" class="add-info"></i><span>Pricing &
                                                        Stocks</span></h5>
                                                <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                        class="chevron-down-add"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseTwo" class="accordion-collapse collapse show"
                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample2">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Quantity <span class="text-danger">*</span></label>
                                                    <input type="number" name="quantity" class="form-control" value="<?php echo e(old('quantity', $product->quantity)); ?>" required style="color: #000000 !important;">
                                                    <?php $__errorArgs = ['quantity'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="text-danger"><?php echo e($message); ?></span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important; margin-bottom: 10px; display: block;">Track Quantity</label>
                                                    <div class="form-check form-switch d-flex align-items-center" style="margin-bottom: 8px;">
                                                        <input class="form-check-input" type="checkbox" name="track_quantity" id="track_quantity" value="1" <?php echo e(old('track_quantity', $product->track_quantity ?? false) ? 'checked' : ''); ?> style="margin-right: 8px;">
                                                        <label class="form-check-label mb-0" for="track_quantity" style="color: #000000 !important;">
                                                            Enable quantity tracking
                                                        </label>
                                                    </div>
                                                    <small class="text-muted" style="color: #6c757d !important; display: block;">Enable this for products that need stock management</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Price <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" name="price" class="form-control" value="<?php echo e(old('price', $product->price)); ?>" required style="color: #000000 !important;">
                                                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <span class="text-danger"><?php echo e($message); ?></span>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Cost</label>
                                                    <input type="number" step="0.01" name="cost" class="form-control" value="<?php echo e(old('cost', $product->cost)); ?>" style="color: #000000 !important;">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Tax Type</label>
                                                    <select name="tax_type" class="select" style="color: #000000 !important;">
                                                        <option value="Exclusive" <?php echo e(old('tax_type', $product->tax_type) == 'Exclusive' ? 'selected' : ''); ?>>Exclusive</option>
                                                        <option value="Inclusive" <?php echo e(old('tax_type', $product->tax_type) == 'Inclusive' ? 'selected' : ''); ?>>Inclusive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Quantity Alert</label>
                                                    <input type="number" name="alert_quantity" class="form-control" value="<?php echo e(old('alert_quantity', $product->alert_quantity)); ?>" style="color: #000000 !important;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Discount Type</label>
                                                    <select name="discount_type" class="select" style="color: #000000 !important;">
                                                        <option value="fixed" <?php echo e(old('discount_type', $product->discount_type) == 'fixed' ? 'selected' : ''); ?>>Fixed</option>
                                                        <option value="percentage" <?php echo e(old('discount_type', $product->discount_type) == 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Discount Value</label>
                                                    <input type="number" step="0.01" name="discount_value" class="form-control" value="<?php echo e(old('discount_value', $product->discount_value)); ?>" style="color: #000000 !important;">
                                                </div>
                                            </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="btn-addproduct mb-4" style="display: flex !important; justify-content: flex-end !important; align-items: center !important; gap: 10px !important; width: 100% !important; float: none !important; clear: both !important;">
                        <button type="button" class="btn btn-cancel" style="min-height: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">Cancel</button>
                        <button type="submit" class="btn btn-submit" style="min-height: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center;">Save Product</button>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
console.log('Edit Product Scripts Loaded');

// Auto-generate slug from product name
$('input[name="name"]').on('keyup', function() {
    var name = $(this).val();
    var slug = name.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/--+/g, '-')
        .trim();
    $('input[name="slug"]').val(slug);
});

// Generate SKU
function generateSKU() {
    var prefix = 'PT';
    var random = Math.floor(1000 + Math.random() * 9000);
    $('input[name="sku"]').val(prefix + random);
}


// Image preview for edit page
$('body').on('change', '#image-input-edit', function(e) {
    console.log('File input changed (Edit)!');
    var input = this;
    var file = input.files[0];
    
    if (file) {
        console.log('File selected:', file.name);
        var reader = new FileReader();
        
        reader.onload = function(e) {
            console.log('Image loaded, showing preview');
            $('#image-preview-edit').attr('src', e.target.result);
            $('#image-preview-container-edit').css('display', 'block');
            $('#image-preview-container-edit').show();
            
            // Reinitialize feather icons
            setTimeout(function() {
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
            }, 100);
        }
        
        reader.readAsDataURL(file);
    }
});

// Remove new image preview
function removeImageEdit() {
    console.log('Removing image (Edit)');
    
    // Hide preview
    $('#image-preview-container-edit').hide();
    $('#image-preview-edit').attr('src', '');
    
    // Clear the file input by replacing it
    var $oldInput = $('#image-input-edit');
    var $newInput = $oldInput.clone();
    $newInput.val('');
    $oldInput.replaceWith($newInput);
}

// Main form Cancel button only (not modal cancel buttons)
$('.btn-addproduct .btn-cancel').on('click', function() {
    window.location.href = "<?php echo e(route('product-list')); ?>";
});

// Debug form submission
$('#edit-product-form').on('submit', function(e) {
    console.log('Form submitted!');
    console.log('Form action:', $(this).attr('action'));
    console.log('Form method:', $(this).attr('method'));
    console.log('Product Name:', $('input[name="name"]').val());
    console.log('Price:', $('input[name="price"]').val());
    console.log('Quantity:', $('input[name="quantity"]').val());
    // Let the form submit normally
});
</script>
<?php $__env->stopPush(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/edit-product.blade.php ENDPATH**/ ?>