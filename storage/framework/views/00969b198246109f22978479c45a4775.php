<?php $page = 'add-product'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    New Product
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Create new product
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    product-list
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_3'); ?>
                    Back to Product
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>
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

            <form action="<?php echo e(route('products.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
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
                                                        <div class="image-upload" id="image-upload-wrapper">
                                                            <input type="file" name="image" id="image-input" accept="image/*">
                                                            <div class="image-uploads">
                                                                <i data-feather="plus-circle"
                                                                    class="plus-down-add me-0"></i>
                                                                <h4>Add Images</h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div id="image-preview-container" class="image-preview-box" style="display: none !important;">
                                                        <div class="phone-img" style="position: relative !important; display: block !important; width: 150px !important; height: 150px !important;">
                                                            <img id="image-preview" src="" alt="Preview" style="width: 100% !important; height: 100% !important; object-fit: contain !important; border-radius: 10px !important; border: 2px solid #ddd !important; background: #f9f9f9 !important;">
                                                            <a href="javascript:void(0);" onclick="removeImage()" style="position: absolute !important; top: -8px !important; right: -8px !important; background: #ea5455 !important; border-radius: 50% !important; width: 28px !important; height: 28px !important; display: flex !important; align-items: center !important; justify-content: center !important; box-shadow: 0 2px 5px rgba(0,0,0,0.2) !important; cursor: pointer !important; z-index: 999 !important;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                                            </a>
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
                                                    <label class="form-label">Store <span class="text-danger">*</span></label>
                                                    <select name="store_id" class="select" required>
                                                        <option value="">Choose Store</option>
                                                        <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($store->id); ?>" <?php echo e((old('store_id') == $store->id || (isset($selectedStoreId) && $selectedStoreId == $store->id)) ? 'selected' : ''); ?>>
                                                                <?php echo e($store->name); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                    <?php $__errorArgs = ['store_id'];
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
                                            <div class="col-lg-5 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="name" class="form-control" required>
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
                                                    <input type="text" name="sku" class="form-control list" placeholder="Enter SKU" required>
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
                                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
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
                                                            <option value="<?php echo e($unit->id); ?>"><?php echo e($unit->name); ?></option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-sm-6 col-12">
                                                <div class="mb-3 add-product">
                                                    <label class="form-label">Slug</label>
                                                    <input type="text" name="slug" class="form-control" placeholder="Auto-generated from name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="input-blocks summer-description-box transfer mb-3">
                                                    <label>Description</label>
                                                    <textarea name="description" class="form-control h-100" rows="3"></textarea>
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
                                                    <input type="number" name="quantity" class="form-control" value="0" required style="color: #000000 !important;">
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
                                                        <input class="form-check-input" type="checkbox" name="track_quantity" id="track_quantity" value="1" style="margin-right: 8px;">
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
                                                    <input type="number" step="0.01" name="price" class="form-control" required style="color: #000000 !important;">
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
                                                    <input type="number" step="0.01" name="cost" class="form-control" value="0" style="color: #000000 !important;">
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Tax Type</label>
                                                    <select name="tax_type" class="select" style="color: #000000 !important;">
                                                        <option value="Exclusive">Exclusive</option>
                                                        <option value="Inclusive">Inclusive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Quantity Alert</label>
                                                    <input type="number" name="alert_quantity" class="form-control" value="10" style="color: #000000 !important;">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Discount Type</label>
                                                    <select name="discount_type" class="select" style="color: #000000 !important;">
                                                        <option value="fixed">Fixed</option>
                                                        <option value="percentage">Percentage</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-sm-6 col-12">
                                                <div class="input-blocks add-product">
                                                    <label style="color: #000000 !important;">Discount Value</label>
                                                    <input type="number" step="0.01" name="discount_value" class="form-control" value="0" style="color: #000000 !important;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-card-one accordion" id="accordionExample4" style="display: none;">
                            <div class="accordion-item" style="display: none;">
                                <div class="accordion-header" id="headingFour" style="display: none;">
                                    <div class="accordion-button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseFour" aria-controls="collapseFour" style="display: none;">
                                        <div class="text-editor add-list" style="display: none;">
                                            <div class="addproduct-icon list" style="display: none;">
                                                <h5><i data-feather="list" class="add-info"></i><span>Custom Fields</span>
                                                </h5>
                                                <a href="javascript:void(0);"><i data-feather="chevron-down"
                                                        class="chevron-down-add"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseFour" class="accordion-collapse collapse show"
                                    aria-labelledby="headingFour" data-bs-parent="#accordionExample4" style="display: none;">
                                    <div class="accordion-body" style="display: none;">
                                        <div class="text-editor add-list add" style="display: none;">
                                            <div class="custom-filed" style="display: none;">
                                                <div class="input-block add-lists" style="display: none;">
                                                    <label class="checkboxs" style="display: none;">
                                                        <input type="checkbox" style="display: none;">
                                                        <span class="checkmarks" style="display: none;"></span>Warranties
                                                    </label>
                                                    <label class="checkboxs" style="display: none;">
                                                        <input type="checkbox" style="display: none;">
                                                        <span class="checkmarks" style="display: none;"></span>Manufacturer
                                                    </label>
                                                    <label class="checkboxs" style="display: none;">
                                                        <input type="checkbox" style="display: none;">
                                                        <span class="checkmarks" style="display: none;"></span>Expiry
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row" style="display: none;">
                                                <div class="col-lg-4 col-sm-6 col-12" style="display: none;">
                                                    <div class="input-blocks add-product" style="display: none;">
                                                        <label>Discount Type</label>
                                                        <select class="select" style="display: none;">
                                                            <option>Choose</option>
                                                            <option>Percentage</option>
                                                            <option>Cash</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" style="display: none;">
                                                <div class="col-lg-4 col-sm-6 col-12" style="display: none;">
                                                    <div class="input-blocks add-product" style="display: none;">
                                                        <label>Quantity Alert</label>
                                                        <input type="text" class="form-control" style="display: none;">
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12" style="display: none;">
                                                    <div class="input-blocks" style="display: none;">
                                                        <label>Manufactured Date</label>

                                                        <div class="input-groupicon calender-input" style="display: none;">
                                                            <i data-feather="calendar" class="info-img"></i>
                                                            <input type="text" class="datetimepicker"
                                                                placeholder="Choose Date" style="display: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-4 col-sm-6 col-12" style="display: none;">
                                                    <div class="input-blocks" style="display: none;">
                                                        <label>Expiry On</label>

                                                        <div class="input-groupicon calender-input" style="display: none;">
                                                            <i data-feather="calendar" class="info-img"></i>
                                                            <input type="text" class="datetimepicker"
                                                                placeholder="Choose Date" style="display: none;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                            aria-labelledby="pills-profile-tab" style="display: none;">
                                                <div class="row select-color-add">
                                                    <div class="col-lg-6 col-sm-6 col-12">
                                                        <div class="input-blocks add-product">
                                                            <label>Variant Attribute</label>
                                                            <div class="row">
                                                                <div class="col-lg-10 col-sm-10 col-10">
                                                                    <select
                                                                        class="form-control variant-select select-option"
                                                                        id="colorSelect">
                                                                        <option>Choose</option>
                                                                        <option>Color</option>
                                                                        <option value="red">Red</option>
                                                                        <option value="black">Black</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-lg-2 col-sm-2 col-2 ps-0">
                                                                    <div class="add-icon tab">
                                                                        <a class="btn btn-filter" data-bs-toggle="modal"
                                                                            data-bs-target="#add-units"><i
                                                                                class="feather feather-plus-circle"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="selected-hide-color" id="input-show">
                                                            <div class="row align-items-center">
                                                                <div class="col-sm-10">
                                                                    <div class="input-blocks">
                                                                        <input class="input-tags form-control"
                                                                            id="inputBox" type="text"
                                                                            data-role="tagsinput" name="specialist"
                                                                            value="red, black">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-2">
                                                                    <div class="input-blocks ">
                                                                        <a href="javascript:void(0);"
                                                                            class="remove-color"><i
                                                                                class="far fa-trash-alt"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="modal-body-table variant-table" id="variant-table">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Variantion</th>
                                                                    <th>Variant Value</th>
                                                                    <th>SKU</th>
                                                                    <th>Quantity</th>
                                                                    <th>Price</th>
                                                                    <th class="no-sort">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="color">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="red">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="1234">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="product-quantity">
                                                                            <span class="quantity-btn"><i
                                                                                    data-feather="minus-circle"
                                                                                    class="feather-search"></i></span>
                                                                            <input type="text" class="quntity-input"
                                                                                value="2">
                                                                            <span class="quantity-btn">+<i
                                                                                    data-feather="plus-circle"
                                                                                    class="plus-circle"></i></span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="50000">
                                                                        </div>
                                                                    </td>
                                                                    <td class="action-table-data">
                                                                        <div class="edit-delete-action">
                                                                            <div class="input-block add-lists">
                                                                                <label class="checkboxs">
                                                                                    <input type="checkbox" checked>
                                                                                    <span class="checkmarks"></span>
                                                                                </label>
                                                                            </div>
                                                                            <a class="me-2 p-2" href="javascript:void(0);"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#add-variation">
                                                                                <i data-feather="plus"
                                                                                    class="feather-edit"></i>
                                                                            </a>
                                                                            <a class="confirm-text p-2"
                                                                                href="javascript:void(0);">
                                                                                <i data-feather="trash-2"
                                                                                    class="feather-trash-2"></i>
                                                                            </a>
                                                                        </div>

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="color">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="black">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="2345">
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="product-quantity">
                                                                            <span class="quantity-btn"><i
                                                                                    data-feather="minus-circle"
                                                                                    class="feather-search"></i></span>
                                                                            <input type="text" class="quntity-input"
                                                                                value="3">
                                                                            <span class="quantity-btn">+<i
                                                                                    data-feather="plus-circle"
                                                                                    class="plus-circle"></i></span>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div class="add-product">
                                                                            <input type="text" class="form-control"
                                                                                value="50000">
                                                                        </div>
                                                                    </td>
                                                                    <td class="action-table-data">
                                                                        <div class="edit-delete-action">
                                                                            <div class="input-block add-lists">
                                                                                <label class="checkboxs">
                                                                                    <input type="checkbox" checked>
                                                                                    <span class="checkmarks"></span>
                                                                                </label>
                                                                            </div>
                                                                            <a class="me-2 p-2" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#edit-units">
                                                                                <i data-feather="plus"
                                                                                    class="feather-edit"></i>
                                                                            </a>
                                                                            <a class="confirm-text p-2"
                                                                                href="javascript:void(0);">
                                                                                <i data-feather="trash-2"
                                                                                    class="feather-trash-2"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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
                <div class="row">
                    <div class="col-12">
                        <div class="btn-addproduct mb-4 d-flex justify-content-end align-items-center gap-2" style="padding-right: 15px;">
                            <button type="button" class="btn btn-cancel" style="height: 40px; padding: 10px 20px;" onclick="window.location.href='<?php echo e(route('product-list')); ?>'">Cancel</button>
                            <button type="submit" class="btn btn-submit" style="height: 40px; padding: 10px 20px;">Save Product</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /add -->

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
console.log('Add Product Scripts Loaded');

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


// Image preview - using body delegation for better compatibility
$('body').on('change', '#image-input', function(e) {
    console.log('File input changed!');
    var input = this;
    var file = input.files[0];
    
    if (file) {
        console.log('File selected:', file.name);
        var reader = new FileReader();
        
        reader.onload = function(e) {
            console.log('Image loaded, showing preview');
            $('#image-preview').attr('src', e.target.result);
            $('#image-preview-container').css('display', 'block');
            $('#image-preview-container').show();
            
            console.log('Preview container display:', $('#image-preview-container').css('display'));
            console.log('Preview container visible:', $('#image-preview-container').is(':visible'));
            
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

// Remove image
function removeImage() {
    console.log('Removing image');
    
    // Hide preview
    $('#image-preview-container').hide();
    $('#image-preview').attr('src', '');
    
    // Create a new file input to replace the old one
    var oldInput = document.getElementById('image-input');
    var newInput = document.createElement('input');
    newInput.type = 'file';
    newInput.name = 'image';
    newInput.id = 'image-input';
    newInput.accept = 'image/*';
    
    // Replace the old input with the new one
    oldInput.parentNode.replaceChild(newInput, oldInput);
    
    console.log('Input replaced');
}

// Main form Cancel button only (not modal cancel buttons)
$('.btn-addproduct .btn-cancel').on('click', function() {
    window.location.href = "<?php echo e(route('product-list')); ?>";
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/add-product.blade.php ENDPATH**/ ?>