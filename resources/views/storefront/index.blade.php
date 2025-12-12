@extends('storefront.layout')

@section('title', $store->name . ' - Home')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section" @if(isset($theme) && $theme && $theme->hero_background_image) style="background-image: url('{{ asset('uploads/storefront/' . $theme->hero_background_image) }}'); background-size: cover; background-position: center;" @endif>
        <div class="container">
            <div class="hero-slide active">
                <div class="hero-content">
                    <h1 class="hero-title">{{ $theme->hero_title ?? 'Holiday Sweaters, Perfected' }}</h1>
                    <p class="hero-subtitle">{{ $theme->hero_subtitle ?? 'Shop specialized care that keeps your holiday knits looking their absolute best.' }}</p>
                    <a href="{{ route('storefront.products', $store->slug) }}" class="btn">Shop Now</a>
                </div>
                <div class="hero-image">
                    <div class="hero-products">
                        @if(isset($theme) && $theme)
                            @if($theme->hero_image_1)
                            <div class="hero-product">
                                <img src="{{ asset('uploads/storefront/' . $theme->hero_image_1) }}" alt="Hero Product 1">
                            </div>
                            @endif
                            @if($theme->hero_image_2)
                            <div class="hero-product">
                                <img src="{{ asset('uploads/storefront/' . $theme->hero_image_2) }}" alt="Hero Product 2">
                            </div>
                            @endif
                            @if($theme->hero_image_3)
                            <div class="hero-product">
                                <img src="{{ asset('uploads/storefront/' . $theme->hero_image_3) }}" alt="Hero Product 3">
                            </div>
                            @endif
                        @endif
                        @if((!isset($theme) || !$theme || (!$theme->hero_image_1 && !$theme->hero_image_2 && !$theme->hero_image_3)) && $featuredProducts->count() > 0)
                            @foreach($featuredProducts->take(3) as $product)
                            <div class="hero-product">
                                @if($product->image)
                                    <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <img src="{{ asset('build/img/placeholder.png') }}" alt="{{ $product->name }}">
                                @endif
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gifts Section -->
    <section class="gifts-section">
        <div class="container">
            <div class="gifts-grid">
                <a href="{{ route('storefront.products', [$store->slug, 'price_max' => 25]) }}" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$25</div>
                </a>
                <a href="{{ route('storefront.products', [$store->slug, 'price_max' => 35]) }}" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$35</div>
                </a>
                <a href="{{ route('storefront.products', [$store->slug, 'price_max' => 50]) }}" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$50</div>
                </a>
                <a href="{{ route('storefront.products', [$store->slug, 'price_max' => 100]) }}" class="gift-banner">
                    <div class="gift-banner-text">GIFTS UNDER</div>
                    <div class="gift-banner-price">$100</div>
                </a>
            </div>
        </div>
    </section>

    <!-- Category Showcases -->
    @if($categories && $categories->count() > 0)
    <section class="category-showcases">
        <div class="container">
            <div class="category-showcase-grid">
                @foreach($categories->take(3) as $index => $category)
                <div class="category-showcase">
                    <h3>{{ $category->name }}</h3>
                    <div class="category-showcase-image">
                        @php
                            $imageField = 'category_showcase_image_' . ($index + 1);
                            $themeImage = isset($theme) && $theme ? $theme->$imageField : null;
                        @endphp
                        @if($themeImage)
                            <img src="{{ asset('uploads/storefront/' . $themeImage) }}" alt="{{ $category->name }}">
                        @else
                            @php
                                $categoryProducts = \App\Models\Product::where('store_id', $store->id)
                                    ->where('category_id', $category->id)
                                    ->where('is_active', true)
                                    ->limit(3)
                                    ->get();
                            @endphp
                            @if($categoryProducts->count() > 0 && $categoryProducts->first()->image)
                                <img src="{{ asset('uploads/products/' . $categoryProducts->first()->image) }}" alt="{{ $category->name }}">
                            @else
                                <div style="width: 100%; height: 200px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; color: white;">
                                    {{ $category->name }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- Featured Products -->
    <section class="featured-products" style="background: var(--off-white); padding: 80px 0;">
        <div class="container">
            <div class="section-header">
                <h2>Shop All Gifts</h2>
                <a href="{{ route('storefront.products', $store->slug) }}" class="view-all">View All</a>
            </div>
            
            <div class="products-grid">
                @forelse($featuredProducts as $product)
                <div class="product-card">
                    <a href="{{ route('storefront.product', [$store->slug, $product->slug]) }}" class="product-link">
                        <div class="product-image">
                            @if($product->image)
                                <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}">
                            @else
                                <img src="{{ asset('build/img/placeholder.png') }}" alt="{{ $product->name }}">
                            @endif
                            @if($product->discount_value > 0)
                            <span class="product-badge">Sale</span>
                            @endif
                        </div>
                        <div class="product-info">
                            <h3 class="product-name">{{ $product->name }}</h3>
                            @if($product->category)
                            <p class="product-category">{{ $product->category->name }}</p>
                            @endif
                            <div class="product-price">
                                @if($product->discount_value > 0)
                                    @php
                                        $discountedPrice = $product->discount_type === 'percentage' 
                                            ? $product->price - ($product->price * $product->discount_value / 100)
                                            : $product->price - $product->discount_value;
                                    @endphp
                                    <span class="price-old">${{ number_format($product->price, 2) }}</span>
                                    <span class="price-new">${{ number_format($discountedPrice, 2) }}</span>
                                @else
                                    <span class="price">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <button class="btn-add-cart" data-product-id="{{ $product->id }}">Add to Cart</button>
                </div>
                @empty
                <div class="no-products">
                    <p>No products available at the moment.</p>
                </div>
                @endforelse
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
                    @if(isset($theme) && $theme && $theme->scent_indulgent_image)
                        <div class="scent-card-bg" style="background-image: url('{{ asset('uploads/storefront/' . $theme->scent_indulgent_image) }}'); background-size: cover; background-position: center;"></div>
                    @else
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #2d1b2e 0%, #1a1a2e 100%);"></div>
                    @endif
                    <div class="scent-card-content">
                        <h3>Indulgent</h3>
                        <p>Rich, luxurious fragrances</p>
                    </div>
                </div>
                <div class="scent-card">
                    @if(isset($theme) && $theme && $theme->scent_beauty_sleep_image)
                        <div class="scent-card-bg" style="background-image: url('{{ asset('uploads/storefront/' . $theme->scent_beauty_sleep_image) }}'); background-size: cover; background-position: center;"></div>
                    @else
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #1a2332 0%, #0d1b2a 100%);"></div>
                    @endif
                    <div class="scent-card-content">
                        <h3>Beauty Sleep</h3>
                        <p>Serene scents for rest</p>
                    </div>
                </div>
                <div class="scent-card">
                    @if(isset($theme) && $theme && $theme->scent_classic_image)
                        <div class="scent-card-bg" style="background-image: url('{{ asset('uploads/storefront/' . $theme->scent_classic_image) }}'); background-size: cover; background-position: center;"></div>
                    @else
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #f5f5f0 0%, #e8e8e3 100%);"></div>
                    @endif
                    <div class="scent-card-content" style="color: var(--dark-green);">
                        <h3>Classic</h3>
                        <p>Timeless elegance</p>
                    </div>
                </div>
                <div class="scent-card">
                    @if(isset($theme) && $theme && $theme->scent_marine_image)
                        <div class="scent-card-bg" style="background-image: url('{{ asset('uploads/storefront/' . $theme->scent_marine_image) }}'); background-size: cover; background-position: center;"></div>
                    @else
                        <div class="scent-card-bg" style="background: linear-gradient(135deg, #a8d5e2 0%, #7bb3c8 100%);"></div>
                    @endif
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
                        @if(isset($theme) && $theme && $theme->article_image_1)
                            <img src="{{ asset('uploads/storefront/' . $theme->article_image_1) }}" alt="Article 1" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                Article Image
                            </div>
                        @endif
                    </div>
                    <h3 class="article-title">Inside Our Collection Launch</h3>
                    <p class="article-excerpt">Discover the inspiration behind our latest product line and the craftsmanship that goes into every bottle.</p>
                </a>
                <a href="#" class="article-card">
                    <div class="article-image">
                        @if(isset($theme) && $theme && $theme->article_image_2)
                            <img src="{{ asset('uploads/storefront/' . $theme->article_image_2) }}" alt="Article 2" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                Article Image
                            </div>
                        @endif
                    </div>
                    <h3 class="article-title">The Science of Scent</h3>
                    <p class="article-excerpt">How laundry detergent can actually change your mood and enhance your daily routine.</p>
                </a>
                <a href="#" class="article-card">
                    <div class="article-image">
                        @if(isset($theme) && $theme && $theme->article_image_3)
                            <img src="{{ asset('uploads/storefront/' . $theme->article_image_3) }}" alt="Article 3" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div style="width: 100%; height: 100%; background: var(--secondary-color); display: flex; align-items: center; justify-content: center; color: var(--text-light);">
                                Article Image
                            </div>
                        @endif
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
                <p>{{ (isset($theme) && $theme && $theme->newsletter_text) ? $theme->newsletter_text : 'Subscribe to our newsletter for exclusive offers and updates' }}</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Enter your email" required>
                    <button type="submit" class="btn btn-primary">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
@endsection

