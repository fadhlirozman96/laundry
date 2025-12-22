<?php $__env->startSection('title', $store->name . ' - Home'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <section class="hero-section" <?php if(isset($theme) && $theme && $theme->hero_background_image): ?> style="background-image: url('<?php echo e(asset('uploads/storefront/' . $theme->hero_background_image)); ?>'); background-size: cover; background-position: center;" <?php endif; ?>>
        <div class="container">
            <div class="hero-slide active">
                <div class="hero-content">
                    <h1 class="hero-title"><?php echo e($theme->hero_title ?? 'Professional Laundry Services'); ?></h1>
                    <p class="hero-subtitle"><?php echo e($theme->hero_subtitle ?? 'You Leave It, We Clean It - Quality laundry services for all your needs'); ?></p>
                    <a href="<?php echo e(route('storefront.products', $store->slug)); ?>" class="btn">View Services</a>
                </div>
                <div class="hero-image">
                    <div class="hero-products">
                        <?php if(isset($theme) && $theme): ?>
                            <?php if($theme->hero_image_1): ?>
                            <div class="hero-product">
                                <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_image_1)); ?>" alt="Hero Product 1">
                            </div>
                            <?php endif; ?>
                            <?php if($theme->hero_image_2): ?>
                            <div class="hero-product">
                                <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_image_2)); ?>" alt="Hero Product 2">
                            </div>
                            <?php endif; ?>
                            <?php if($theme->hero_image_3): ?>
                            <div class="hero-product">
                                <img src="<?php echo e(asset('uploads/storefront/' . $theme->hero_image_3)); ?>" alt="Hero Product 3">
                            </div>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if((!isset($theme) || !$theme || (!$theme->hero_image_1 && !$theme->hero_image_2 && !$theme->hero_image_3)) && $featuredProducts->count() > 0): ?>
                            <?php $__currentLoopData = $featuredProducts->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="hero-product">
                                <?php if($product->image): ?>
                                    <img src="<?php echo e(asset('uploads/products/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('build/img/placeholder.png')); ?>" alt="<?php echo e($product->name); ?>">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gifts Section -->
    <section class="gifts-section">
        <div class="container">
            <div class="gifts-grid">
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'normal-wash-dry-services'])); ?>" class="gift-banner">
                    <div class="gift-banner-text">WASH & DRY</div>
                    <div class="gift-banner-price">MYR 4/kg</div>
                </a>
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'ironing-services'])); ?>" class="gift-banner">
                    <div class="gift-banner-text">IRONING</div>
                    <div class="gift-banner-price">MYR 2.50</div>
                </a>
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'dryclean-services'])); ?>" class="gift-banner">
                    <div class="gift-banner-text">DRYCLEAN</div>
                    <div class="gift-banner-price">MYR 12</div>
                </a>
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'hand-wash-services'])); ?>" class="gift-banner">
                    <div class="gift-banner-text">HAND WASH</div>
                    <div class="gift-banner-price">MYR 6</div>
                </a>
            </div>
        </div>
    </section>

    <!-- Category Showcases -->
    <?php if($categories && $categories->count() > 0): ?>
    <section class="category-showcases">
        <div class="container">
            <div class="category-showcase-grid">
                <?php $__currentLoopData = $categories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="category-showcase">
                    <h3><?php echo e($category->name); ?></h3>
                    <div class="category-showcase-image">
                        <?php
                            $imageField = 'category_showcase_image_' . ($index + 1);
                            $themeImage = isset($theme) && $theme ? $theme->$imageField : null;
                        ?>
                        <?php if($themeImage): ?>
                            <img src="<?php echo e(asset('uploads/storefront/' . $themeImage)); ?>" alt="<?php echo e($category->name); ?>">
                        <?php else: ?>
                            <?php
                                $categoryProducts = \App\Models\Product::where('store_id', $store->id)
                                    ->where('category_id', $category->id)
                                    ->where('is_active', true)
                                    ->limit(3)
                                    ->get();
                            ?>
                            <?php if($categoryProducts->count() > 0 && $categoryProducts->first()->image): ?>
                                <img src="<?php echo e(asset('uploads/products/' . $categoryProducts->first()->image)); ?>" alt="<?php echo e($category->name); ?>">
                            <?php else: ?>
                                <div style="width: 100%; height: 200px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white;">
                                    <?php echo e($category->name); ?>

                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Featured Products -->
    <section class="featured-products" style="background: var(--off-white); padding: 80px 0;">
        <div class="container">
            <div class="section-header">
                <h2>Our Services</h2>
                <a href="<?php echo e(route('storefront.products', $store->slug)); ?>" class="view-all">View All Services</a>
            </div>
            
            <div class="products-grid">
                <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="product-card">
                    <a href="<?php echo e(route('storefront.product', [$store->slug, $product->slug])); ?>" class="product-link">
                        <div class="product-image">
                            <?php if($product->image): ?>
                                <img src="<?php echo e(asset('uploads/products/' . $product->image)); ?>" alt="<?php echo e($product->name); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(asset('build/img/placeholder.png')); ?>" alt="<?php echo e($product->name); ?>">
                            <?php endif; ?>
                            <?php if($product->discount_value > 0): ?>
                            <span class="product-badge">Sale</span>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3 class="product-name"><?php echo e($product->name); ?></h3>
                            <?php if($product->category): ?>
                            <p class="product-category"><?php echo e($product->category->name); ?></p>
                            <?php endif; ?>
                            <div class="product-price">
                                <?php if($product->discount_value > 0): ?>
                                    <?php
                                        $discountedPrice = $product->discount_type === 'percentage' 
                                            ? $product->price - ($product->price * $product->discount_value / 100)
                                            : $product->price - $product->discount_value;
                                    ?>
                                    <span class="price-old">MYR <?php echo e(number_format($product->price, 2)); ?></span>
                                    <span class="price-new">MYR <?php echo e(number_format($discountedPrice, 2)); ?></span>
                                <?php else: ?>
                                    <span class="price">MYR <?php echo e(number_format($product->price, 2)); ?><?php if($product->unit): ?> / <?php echo e($product->unit->short_name); ?><?php endif; ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    <button class="btn-add-cart" data-product-id="<?php echo e($product->id); ?>">Book Service</button>
                </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="no-products">
                    <p>No services available at the moment.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Shop By Scent Section -->
    <section class="shop-by-scent">
        <div class="container">
            <div class="section-header" style="text-align: center;">
                <h2>Service Categories</h2>
            </div>
            <div class="scent-grid">
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'normal-wash-dry-services'])); ?>" class="scent-card" style="text-decoration: none;">
                    <?php if(isset($theme) && $theme && $theme->scent_indulgent_image): ?>
                        <div class="scent-card-bg" style="background-image: url('<?php echo e(asset('uploads/storefront/' . $theme->scent_indulgent_image)); ?>'); background-size: cover; background-position: center;"></div>
                    <?php else: ?>
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #2d1b2e 0%, #1a1a2e 100%);"></div>
                    <?php endif; ?>
                    <div class="scent-card-content">
                        <h3>Wash & Dry</h3>
                        <p>Normal and express services</p>
                    </div>
                </a>
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'ironing-services'])); ?>" class="scent-card" style="text-decoration: none;">
                    <?php if(isset($theme) && $theme && $theme->scent_beauty_sleep_image): ?>
                        <div class="scent-card-bg" style="background-image: url('<?php echo e(asset('uploads/storefront/' . $theme->scent_beauty_sleep_image)); ?>'); background-size: cover; background-position: center;"></div>
                    <?php else: ?>
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #1a2332 0%, #0d1b2a 100%);"></div>
                    <?php endif; ?>
                    <div class="scent-card-content">
                        <h3>Ironing</h3>
                        <p>Professional pressing</p>
                    </div>
                </a>
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'dryclean-services'])); ?>" class="scent-card" style="text-decoration: none;">
                    <?php if(isset($theme) && $theme && $theme->scent_classic_image): ?>
                        <div class="scent-card-bg" style="background-image: url('<?php echo e(asset('uploads/storefront/' . $theme->scent_classic_image)); ?>'); background-size: cover; background-position: center;"></div>
                    <?php else: ?>
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #f5f5f0 0%, #e8e8e3 100%);"></div>
                    <?php endif; ?>
                    <div class="scent-card-content" style="color: var(--dark-green);">
                        <h3>Dryclean</h3>
                        <p>Expert drycleaning</p>
                    </div>
                </a>
                <a href="<?php echo e(route('storefront.category', [$store->slug, 'hand-wash-services'])); ?>" class="scent-card" style="text-decoration: none;">
                    <?php if(isset($theme) && $theme && $theme->scent_marine_image): ?>
                        <div class="scent-card-bg" style="background-image: url('<?php echo e(asset('uploads/storefront/' . $theme->scent_marine_image)); ?>'); background-size: cover; background-position: center;"></div>
                    <?php else: ?>
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #a8d5e2 0%, #7bb3c8 100%);"></div>
                    <?php endif; ?>
                    <div class="scent-card-content">
                        <h3>Hand Wash</h3>
                        <p>Gentle hand washing</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Expert Care Section -->
    <section class="expert-care-section">
        <div class="container">
            <div class="expert-care-header">
                <h2>Why Choose <?php echo e($store->name); ?>?</h2>
            </div>
            <div class="articles-grid">
                <div class="article-card">
                    <div class="article-image">
                        <?php if(isset($theme) && $theme && $theme->article_image_1): ?>
                            <img src="<?php echo e(asset('uploads/storefront/' . $theme->article_image_1)); ?>" alt="Quality Service" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                Quality Service
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="article-title">Professional Quality</h3>
                    <p class="article-excerpt">We use premium detergents and professional-grade equipment to ensure your clothes are cleaned to the highest standards.</p>
                </div>
                <div class="article-card">
                    <div class="article-image">
                        <?php if(isset($theme) && $theme && $theme->article_image_2): ?>
                            <img src="<?php echo e(asset('uploads/storefront/' . $theme->article_image_2)); ?>" alt="Fast Service" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                Fast Service
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="article-title">Fast & Reliable</h3>
                    <p class="article-excerpt">Choose from normal service (1-2 days) or express service (same day) to fit your schedule.</p>
                </div>
                <div class="article-card">
                    <div class="article-image">
                        <?php if(isset($theme) && $theme && $theme->article_image_3): ?>
                            <img src="<?php echo e(asset('uploads/storefront/' . $theme->article_image_3)); ?>" alt="Wide Range" style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                Wide Range
                            </div>
                        <?php endif; ?>
                    </div>
                    <h3 class="article-title">Complete Services</h3>
                    <p class="article-excerpt">From wash & dry to ironing, drycleaning, and hand wash - we handle all your laundry needs.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h2>Stay Updated</h2>
                <p><?php echo e((isset($theme) && $theme && $theme->newsletter_text) ? $theme->newsletter_text : 'Subscribe to our newsletter for exclusive offers and updates'); ?></p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('storefront.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry\resources\views/storefront/index.blade.php ENDPATH**/ ?>