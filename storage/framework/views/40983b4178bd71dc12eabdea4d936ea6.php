

<?php $__env->startSection('title', $store->name . ' - Home'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-slide active">
                <div class="hero-content">
                    <h1 class="hero-title"><?php echo e($theme->hero_title ?? 'Holiday Sweaters, Perfected'); ?></h1>
                    <p class="hero-subtitle"><?php echo e($theme->hero_subtitle ?? 'Shop specialized care that keeps your holiday knits looking their absolute best.'); ?></p>
                    <a href="<?php echo e(route('storefront.products', $store->slug)); ?>" class="btn">Shop Now</a>
                </div>
                <div class="hero-image">
                    <div class="hero-products">
                        <?php if($featuredProducts->count() > 0): ?>
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
                <a href="<?php echo e(route('storefront.products', [$store->slug, 'price_max' => 25])); ?>" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$25</div>
                </a>
                <a href="<?php echo e(route('storefront.products', [$store->slug, 'price_max' => 35])); ?>" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$35</div>
                </a>
                <a href="<?php echo e(route('storefront.products', [$store->slug, 'price_max' => 50])); ?>" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$50</div>
                </a>
                <a href="<?php echo e(route('storefront.products', [$store->slug, 'price_max' => 100])); ?>" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$100</div>
                </a>
            </div>
        </div>
    </section>

    <!-- Category Showcases -->
    <?php if($categories && $categories->count() > 0): ?>
    <section class="category-showcases">
        <div class="container">
            <div class="category-showcase-grid">
                <?php $__currentLoopData = $categories->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="category-showcase">
                    <h3><?php echo e($category->name); ?></h3>
                    <div class="category-showcase-image">
                        <?php
                            $categoryProducts = \App\Models\Product::where('store_id', $store->id)
                                ->where('category_id', $category->id)
                                ->where('is_active', true)
                                ->limit(3)
                                ->get();
                        ?>
                        <?php if($categoryProducts->count() > 0): ?>
                            <?php if($categoryProducts->first()->image): ?>
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
                <h2>Shop All Gifts</h2>
                <a href="<?php echo e(route('storefront.products', $store->slug)); ?>" class="view-all">View All</a>
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
                                    <span class="price-old">$<?php echo e(number_format($product->price, 2)); ?></span>
                                    <span class="price-new">$<?php echo e(number_format($discountedPrice, 2)); ?></span>
                                <?php else: ?>
                                    <span class="price">$<?php echo e(number_format($product->price, 2)); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    <button class="btn-add-cart" data-product-id="<?php echo e($product->id); ?>">Add to Cart</button>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="no-products">
                    <p>No products available at the moment.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Shop By Scent Section -->
    <section class="shop-by-scent">
        <div class="container">
            <div class="section-header" style="text-align: center;">
                <h2>Shop By Scent</h2>
            </div>
            <div class="scent-grid">
                <div class="scent-card">
                    <div class="scent-card-bg" style="background: linear-gradient(135deg, #2d1b2e 0%, #1a1a2e 100%);"></div>
                    <div class="scent-card-content">
                        <h3>Indulgent</h3>
                        <p>Rich, luxurious fragrances</p>
                    </div>
                </div>
                <div class="scent-card">
                    <div class="scent-card-bg" style="background: linear-gradient(135deg, #1a2332 0%, #0d1b2a 100%);"></div>
                    <div class="scent-card-content">
                        <h3>Beauty Sleep</h3>
                        <p>Serene scents for rest</p>
                    </div>
                </div>
                <div class="scent-card">
                    <div class="scent-card-bg" style="background: linear-gradient(135deg, #f5f5f0 0%, #e8e8e3 100%);"></div>
                    <div class="scent-card-content" style="color: var(--dark-green);">
                        <h3>Classic</h3>
                        <p>Timeless elegance</p>
                    </div>
                </div>
                <div class="scent-card">
                    <div class="scent-card-bg" style="background: linear-gradient(135deg, #a8d5e2 0%, #7bb3c8 100%);"></div>
                    <div class="scent-card-content">
                        <h3>Marine</h3>
                        <p>Fresh, oceanic notes</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Expert Care Section -->
    <section class="expert-care-section">
        <div class="container">
            <div class="expert-care-header">
                <h2>Your go-to source for expert fabric & home care</h2>
            </div>
            <div class="articles-grid">
                <a href="#" class="article-card">
                    <div class="article-image">
                        <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                            Article Image
                        </div>
                    </div>
                    <h3 class="article-title">Inside Our Collection Launch</h3>
                    <p class="article-excerpt">Discover the inspiration behind our latest product line and the craftsmanship that goes into every bottle.</p>
                </a>
                <a href="#" class="article-card">
                    <div class="article-image">
                        <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                            Article Image
                        </div>
                    </div>
                    <h3 class="article-title">The Science of Scent</h3>
                    <p class="article-excerpt">How laundry detergent can actually change your mood and enhance your daily routine.</p>
                </a>
                <a href="#" class="article-card">
                    <div class="article-image">
                        <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                            Article Image
                        </div>
                    </div>
                    <h3 class="article-title">Laundry Detergent Scent For Every Mood</h3>
                    <p class="article-excerpt">Find your perfect fragrance match for every occasion and mood.</p>
                </a>
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


<?php echo $__env->make('storefront.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\laundry3\resources\views/storefront/index.blade.php ENDPATH**/ ?>