<?php $page = 'storefront-cms'; ?>

<?php $__env->startSection('content'); ?>
    <div class="page-wrapper">
        <div class="content">
            <?php $__env->startComponent('components.breadcrumb'); ?>
                <?php $__env->slot('title'); ?>
                    Storefront Theme & CMS
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_1'); ?>
                    Customize your store website
                <?php $__env->endSlot(); ?>
                <?php $__env->slot('li_2'); ?>
                    Theme Settings
                <?php $__env->endSlot(); ?>
            <?php echo $__env->renderComponent(); ?>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
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

                            <!-- Store Selector -->
                            <div class="mb-4">
                                <label class="form-label">Select Store</label>
                                <form method="GET" action="<?php echo e(route('storefront-cms')); ?>" class="d-inline">
                                    <select name="store_id" class="select" onchange="this.form.submit()" style="width: 300px;">
                                        <?php $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($store->id); ?>" <?php echo e(($selectedStore && $selectedStore->id == $store->id) ? 'selected' : ''); ?>>
                                            <?php echo e($store->name); ?>

                                        </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </form>
                                <?php if($selectedStore): ?>
                                <a href="<?php echo e(route('storefront.index', $selectedStore->slug)); ?>" target="_blank" class="btn btn-primary ms-3">
                                    <i data-feather="eye" class="feather-16 me-2"></i>Preview Website
                                </a>
                                <span class="ms-3 text-muted">
                                    <i data-feather="info" class="feather-16"></i> Editing: <strong><?php echo e($selectedStore->name); ?></strong>
                                </span>
                                <?php endif; ?>
                            </div>

                            <?php if($selectedStore && $theme): ?>
                            <form action="<?php echo e(route('storefront-theme.update', $selectedStore->id)); ?>" method="POST" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PUT'); ?>

                                <!-- Tabs -->
                                <ul class="nav nav-tabs nav-tabs-bottom" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#colors" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-user"></i></span>
                                            <span class="d-none d-sm-block">Colors</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#typography" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-envelope"></i></span>
                                            <span class="d-none d-sm-block">Typography</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#content" role="tab">
                                            <span class="d-block d-sm-none"><i class="fas fa-cog"></i></span>
                                            <span class="d-none d-sm-block">Content/Text</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#images" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-image"></i></span>
                                            <span class="d-none d-sm-block">Images</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#css" role="tab">
                                            <span class="d-block d-sm-none"><i class="far fa-file"></i></span>
                                            <span class="d-none d-sm-block">Custom CSS</span>
                                        </a>
                                    </li>
                                </ul>

                                <!-- Tab Content -->
                                <div class="tab-content">
                                    <!-- Colors Tab -->
                                    <div class="tab-pane fade show active" id="colors" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Primary Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="primary_color" class="form-control form-control-color" value="<?php echo e($theme->primary_color ?? '#1a2a2a'); ?>" title="Choose primary color">
                                                        <input type="text" class="form-control" value="<?php echo e($theme->primary_color ?? '#1a2a2a'); ?>" onchange="this.previousElementSibling.value = this.value">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Secondary Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="secondary_color" class="form-control form-control-color" value="<?php echo e($theme->secondary_color ?? '#f5f5f0'); ?>" title="Choose secondary color">
                                                        <input type="text" class="form-control" value="<?php echo e($theme->secondary_color ?? '#f5f5f0'); ?>" onchange="this.previousElementSibling.value = this.value">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Text Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="text_color" class="form-control form-control-color" value="<?php echo e($theme->text_color ?? '#1a2a2a'); ?>" title="Choose text color">
                                                        <input type="text" class="form-control" value="<?php echo e($theme->text_color ?? '#1a2a2a'); ?>" onchange="this.previousElementSibling.value = this.value">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Background Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="background_color" class="form-control form-control-color" value="<?php echo e($theme->background_color ?? '#ffffff'); ?>" title="Choose background color">
                                                        <input type="text" class="form-control" value="<?php echo e($theme->background_color ?? '#ffffff'); ?>" onchange="this.previousElementSibling.value = this.value">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Accent Color</label>
                                                    <div class="input-group">
                                                        <input type="color" name="accent_color" class="form-control form-control-color" value="<?php echo e($theme->accent_color ?? '#1a2a2a'); ?>" title="Choose accent color">
                                                        <input type="text" class="form-control" value="<?php echo e($theme->accent_color ?? '#1a2a2a'); ?>" onchange="this.previousElementSibling.value = this.value">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Typography Tab -->
                                    <div class="tab-pane fade" id="typography" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Heading Font</label>
                                                    <select name="heading_font" class="select">
                                                        <option value="Playfair Display" <?php echo e(($theme->heading_font ?? 'Playfair Display') == 'Playfair Display' ? 'selected' : ''); ?>>Playfair Display</option>
                                                        <option value="Georgia" <?php echo e(($theme->heading_font ?? '') == 'Georgia' ? 'selected' : ''); ?>>Georgia</option>
                                                        <option value="Times New Roman" <?php echo e(($theme->heading_font ?? '') == 'Times New Roman' ? 'selected' : ''); ?>>Times New Roman</option>
                                                        <option value="Inter" <?php echo e(($theme->heading_font ?? '') == 'Inter' ? 'selected' : ''); ?>>Inter</option>
                                                        <option value="Arial" <?php echo e(($theme->heading_font ?? '') == 'Arial' ? 'selected' : ''); ?>>Arial</option>
                                                        <option value="Helvetica" <?php echo e(($theme->heading_font ?? '') == 'Helvetica' ? 'selected' : ''); ?>>Helvetica</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Body Font</label>
                                                    <select name="body_font" class="select">
                                                        <option value="Inter" <?php echo e(($theme->body_font ?? 'Inter') == 'Inter' ? 'selected' : ''); ?>>Inter</option>
                                                        <option value="Arial" <?php echo e(($theme->body_font ?? '') == 'Arial' ? 'selected' : ''); ?>>Arial</option>
                                                        <option value="Helvetica" <?php echo e(($theme->body_font ?? '') == 'Helvetica' ? 'selected' : ''); ?>>Helvetica</option>
                                                        <option value="Georgia" <?php echo e(($theme->body_font ?? '') == 'Georgia' ? 'selected' : ''); ?>>Georgia</option>
                                                        <option value="Playfair Display" <?php echo e(($theme->body_font ?? '') == 'Playfair Display' ? 'selected' : ''); ?>>Playfair Display</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Heading Size (px)</label>
                                                    <input type="number" name="heading_size" class="form-control" value="<?php echo e($theme->heading_size ?? 36); ?>" min="12" max="72">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Body Size (px)</label>
                                                    <input type="number" name="body_size" class="form-control" value="<?php echo e($theme->body_size ?? 16); ?>" min="10" max="24">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content Tab -->
                                    <div class="tab-pane fade" id="content" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Hero Title</label>
                                                    <input type="text" name="hero_title" class="form-control" value="<?php echo e($theme->hero_title ?? ''); ?>" placeholder="e.g., Holiday Sweaters, Perfected">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Hero Subtitle</label>
                                                    <textarea name="hero_subtitle" class="form-control" rows="3" placeholder="e.g., Shop specialized care that keeps your holiday knits looking their absolute best."><?php echo e($theme->hero_subtitle ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Promo Banner Text</label>
                                                    <input type="text" name="promo_banner_text" class="form-control" value="<?php echo e($theme->promo_banner_text ?? ''); ?>" placeholder="e.g., FREE Gift Wrap for the Holidays">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Footer Text</label>
                                                    <textarea name="footer_text" class="form-control" rows="3" placeholder="Footer description text"><?php echo e($theme->footer_text ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Newsletter Text</label>
                                                    <textarea name="newsletter_text" class="form-control" rows="2" placeholder="Newsletter subscription text"><?php echo e($theme->newsletter_text ?? ''); ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Images Tab -->
                                    <div class="tab-pane fade" id="images" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-12 mb-4">
                                                <h5>Logo & Header</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Logo Image</label>
                                                    <?php if($theme->logo_image): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->logo_image)); ?>" alt="Logo" style="max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="logo_image_remove" value="1">
                                                        <span class="checkmarks"></span> Remove current image
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="logo_image" class="form-control" accept="image/*">
                                                    <small class="form-text text-muted">Recommended: 200x50px, PNG with transparent background</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Favicon</label>
                                                    <?php if($theme->favicon): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->favicon)); ?>" alt="Favicon" style="max-height: 32px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="favicon_remove" value="1">
                                                        <span class="checkmarks"></span> Remove current image
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="favicon" class="form-control" accept="image/*">
                                                    <small class="form-text text-muted">Recommended: 32x32px, ICO or PNG</small>
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 mt-4">
                                                <h5>Hero Section</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Hero Background Image</label>
                                                    <?php if($theme->hero_background_image): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_background_image)); ?>" alt="Hero Background" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="hero_background_image_remove" value="1">
                                                        <span class="checkmarks"></span> Remove current image
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="hero_background_image" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Hero Product Image 1</label>
                                                    <?php if($theme->hero_image_1): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_image_1)); ?>" alt="Hero Image 1" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="hero_image_1_remove" value="1">
                                                        <span class="checkmarks"></span> Remove current image
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="hero_image_1" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Hero Product Image 2</label>
                                                    <?php if($theme->hero_image_2): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_image_2)); ?>" alt="Hero Image 2" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="hero_image_2_remove" value="1">
                                                        <span class="checkmarks"></span> Remove current image
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="hero_image_2" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Hero Product Image 3</label>
                                                    <?php if($theme->hero_image_3): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_image_3)); ?>" alt="Hero Image 3" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="hero_image_3_remove" value="1">
                                                        <span class="checkmarks"></span> Remove current image
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="hero_image_3" class="form-control" accept="image/*">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 mt-4">
                                                <h5>Category Showcase Images</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Category Showcase Image 1</label>
                                                    <?php if($theme->category_showcase_image_1): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->category_showcase_image_1)); ?>" alt="Category 1" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="category_showcase_image_1_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="category_showcase_image_1" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Category Showcase Image 2</label>
                                                    <?php if($theme->category_showcase_image_2): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->category_showcase_image_2)); ?>" alt="Category 2" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="category_showcase_image_2_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="category_showcase_image_2" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Category Showcase Image 3</label>
                                                    <?php if($theme->category_showcase_image_3): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->category_showcase_image_3)); ?>" alt="Category 3" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="category_showcase_image_3_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="category_showcase_image_3" class="form-control" accept="image/*">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 mt-4">
                                                <h5>Scent Card Background Images</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Indulgent Scent Background</label>
                                                    <?php if($theme->scent_indulgent_image): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->scent_indulgent_image)); ?>" alt="Indulgent" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="scent_indulgent_image_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="scent_indulgent_image" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Beauty Sleep Scent Background</label>
                                                    <?php if($theme->scent_beauty_sleep_image): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->scent_beauty_sleep_image)); ?>" alt="Beauty Sleep" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="scent_beauty_sleep_image_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="scent_beauty_sleep_image" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Classic Scent Background</label>
                                                    <?php if($theme->scent_classic_image): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->scent_classic_image)); ?>" alt="Classic" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="scent_classic_image_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="scent_classic_image" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Marine Scent Background</label>
                                                    <?php if($theme->scent_marine_image): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->scent_marine_image)); ?>" alt="Marine" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="scent_marine_image_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="scent_marine_image" class="form-control" accept="image/*">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 mt-4">
                                                <h5>Article/Blog Images</h5>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Article Image 1</label>
                                                    <?php if($theme->article_image_1): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->article_image_1)); ?>" alt="Article 1" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="article_image_1_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="article_image_1" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Article Image 2</label>
                                                    <?php if($theme->article_image_2): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->article_image_2)); ?>" alt="Article 2" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="article_image_2_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="article_image_2" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Article Image 3</label>
                                                    <?php if($theme->article_image_3): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->article_image_3)); ?>" alt="Article 3" style="max-height: 150px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="article_image_3_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="article_image_3" class="form-control" accept="image/*">
                                                </div>
                                            </div>

                                            <div class="col-md-12 mb-4 mt-4">
                                                <h5>Footer</h5>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Footer Logo</label>
                                                    <?php if($theme->footer_logo): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('uploads/storefront/' . $theme->footer_logo)); ?>" alt="Footer Logo" style="max-height: 100px; border: 1px solid #ddd; padding: 5px;">
                                                    </div>
                                                    <label class="checkboxs">
                                                        <input type="checkbox" name="footer_logo_remove" value="1">
                                                        <span class="checkmarks"></span> Remove
                                                    </label>
                                                    <?php endif; ?>
                                                    <input type="file" name="footer_logo" class="form-control" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CSS Tab -->
                                    <div class="tab-pane fade" id="css" role="tabpanel">
                                        <div class="row mt-4">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Custom CSS</label>
                                                    <textarea name="custom_css" class="form-control" rows="15" placeholder="/* Add your custom CSS here */"><?php echo e($theme->custom_css ?? ''); ?></textarea>
                                                    <small class="form-text text-muted">Add custom CSS to override default styles. This will be applied after the main stylesheet.</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="save" class="feather-16 me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.mainlayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry3\resources\views/storefront-cms.blade.php ENDPATH**/ ?>