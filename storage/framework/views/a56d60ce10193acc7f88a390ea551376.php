<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', $store->name ?? 'Store'); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Storefront CSS -->
    <link rel="stylesheet" href="<?php echo e(asset('build/css/storefront.css')); ?>">
    
    <!-- Theme Customizations -->
    <?php if(isset($theme) && $theme): ?>
    <style>
        :root {
            --primary-color: <?php echo e($theme->primary_color ?? '#1a2a2a'); ?>;
            --dark-green: <?php echo e($theme->primary_color ?? '#1a2a2a'); ?>;
            --secondary-color: <?php echo e($theme->secondary_color ?? '#f5f5f0'); ?>;
            --off-white: <?php echo e($theme->secondary_color ?? '#fafaf5'); ?>;
            --text-color: <?php echo e($theme->text_color ?? '#1a2a2a'); ?>;
            --background-color: <?php echo e($theme->background_color ?? '#ffffff'); ?>;
            --accent-color: <?php echo e($theme->accent_color ?? '#1a2a2a'); ?>;
        }
        
        body {
            font-family: '<?php echo e($theme->body_font ?? 'Inter'); ?>', sans-serif;
            font-size: <?php echo e($theme->body_size ?? 16); ?>px;
        }
        
        h1, h2, h3, h4, h5, h6, .hero-title {
            font-family: '<?php echo e($theme->heading_font ?? 'Playfair Display'); ?>', serif;
            font-size: <?php echo e($theme->heading_size ?? 36); ?>px;
        }
        
        <?php if($theme->custom_css): ?>
        <?php echo $theme->custom_css; ?>

        <?php endif; ?>
    </style>
    <?php endif; ?>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Top Utility Bar -->
    <div class="top-utility-bar">
        <div class="container">
            <div class="top-utility-bar-left">
                FREE SHIPPING ON ALL ORDERS $75+ | FREE RETURNS
            </div>
            <div class="top-utility-bar-right">
                <a href="#">Sign In</a>
                <a href="#">Register</a>
                <a href="#" class="nav-icon cart-icon">
                    <svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 7H15L14 13H6L5 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="7" cy="17" r="1" fill="currentColor"/>
                        <circle cx="13" cy="17" r="1" fill="currentColor"/>
                    </svg>
                    <span class="cart-count">0</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="storefront-nav">
        <div class="container">
            <div class="nav-wrapper">
                <div class="nav-left">
                    <a href="<?php echo e(route('storefront.index', $store->slug)); ?>" class="logo">
                        <span class="logo-text"><?php echo e(strtoupper($store->name)); ?></span>
                    </a>
                </div>
                
                <div class="nav-center">
                    <ul class="nav-menu">
                        <li class="has-dropdown">
                            <a href="<?php echo e(route('storefront.products', $store->slug)); ?>">Shop</a>
                            <?php if(isset($categories) && $categories->count() > 0): ?>
                            <ul class="dropdown-menu">
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><a href="<?php echo e(route('storefront.category', [$store->slug, $category->slug])); ?>"><?php echo e($category->name); ?></a></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <?php endif; ?>
                        </li>
                        <li><a href="<?php echo e(route('storefront.products', $store->slug)); ?>">Best Sellers</a></li>
                        <li><a href="<?php echo e(route('storefront.products', $store->slug)); ?>">Gift Shop</a></li>
                        <li><a href="#about">Our Story</a></li>
                        <li><a href="#more">More</a></li>
                    </ul>
                </div>
                
                <div class="nav-right">
                    <a href="#" class="nav-icon search-icon" id="searchToggle">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 17C13.4183 17 17 13.4183 17 9C17 4.58172 13.4183 1 9 1C4.58172 1 1 4.58172 1 9C1 13.4183 4.58172 17 9 17Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M19 19L14.65 14.65" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                    <a href="#" class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="10" cy="10" r="8" stroke="currentColor" stroke-width="2"/>
                            <path d="M10 6V10L13 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                    <a href="#" class="nav-icon cart-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 7H15L14 13H6L5 7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="7" cy="17" r="1" fill="currentColor"/>
                            <circle cx="13" cy="17" r="1" fill="currentColor"/>
                        </svg>
                        <span class="cart-count">0</span>
                    </a>
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Promotional Banner -->
        <?php if(isset($theme) && $theme && $theme->promo_banner_text): ?>
        <div class="promo-banner">
            <div class="container">
                <div class="promo-banner-content">
                    <h3><?php echo e($theme->promo_banner_text); ?></h3>
                    <p>It's the perfect finishing touch.</p>
                    <a href="<?php echo e(route('storefront.products', $store->slug)); ?>" class="btn">Shop Now</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Search Bar -->
        <div class="search-bar" id="searchBar">
            <div class="container">
                <form action="<?php echo e(route('storefront.products', $store->slug)); ?>" method="GET">
                    <input type="text" name="search" placeholder="Search products..." value="<?php echo e(request('search')); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="storefront-main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Footer -->
    <footer class="storefront-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4><?php echo e($store->name); ?></h4>
                    <p><?php echo e((isset($theme) && $theme && $theme->footer_text) ? $theme->footer_text : ($store->address ?? 'Premium quality products for your everyday needs.')); ?></p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo e(route('storefront.index', $store->slug)); ?>">Home</a></li>
                        <li><a href="<?php echo e(route('storefront.products', $store->slug)); ?>">All Products</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contact</h4>
                    <ul>
                        <?php if($store->email): ?>
                        <li><a href="mailto:<?php echo e($store->email); ?>"><?php echo e($store->email); ?></a></li>
                        <?php endif; ?>
                        <?php if($store->phone): ?>
                        <li><a href="tel:<?php echo e($store->phone); ?>"><?php echo e($store->phone); ?></a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook">FB</a>
                        <a href="#" aria-label="Instagram">IG</a>
                        <a href="#" aria-label="Twitter">TW</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($store->name); ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Storefront JS -->
    <script src="<?php echo e(asset('build/js/storefront.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\laragon\www\laundry3\resources\views/storefront/layout.blade.php ENDPATH**/ ?>