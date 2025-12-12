@extends('storefront.layout')

@section('title', 'Products - ' . $store->name)

@section('content')
    <div class="products-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>All Products</h1>
                <p>Discover our complete collection</p>
            </div>

            <div class="products-layout">
                <!-- Sidebar Filters -->
                <aside class="products-sidebar">
                    <div class="filter-section">
                        <h3>Filters</h3>
                        
                        <!-- Search -->
                        <div class="filter-group">
                            <form action="{{ route('storefront.products', $store->slug) }}" method="GET">
                                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="filter-input">
                                <button type="submit" class="btn btn-sm">Search</button>
                            </form>
                        </div>

                        <!-- Categories -->
                        @if($categories && $categories->count() > 0)
                        <div class="filter-group">
                            <h4>Categories</h4>
                            <ul class="filter-list">
                                <li>
                                    <a href="{{ route('storefront.products', $store->slug) }}" class="{{ !request('category') ? 'active' : '' }}">
                                        All Categories
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                <li>
                                    <a href="{{ route('storefront.products', [$store->slug, 'category' => $category->id]) }}" 
                                       class="{{ request('category') == $category->id ? 'active' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Brands -->
                        @if($brands && $brands->count() > 0)
                        <div class="filter-group">
                            <h4>Brands</h4>
                            <ul class="filter-list">
                                <li>
                                    <a href="{{ route('storefront.products', $store->slug) }}" class="{{ !request('brand') ? 'active' : '' }}">
                                        All Brands
                                    </a>
                                </li>
                                @foreach($brands as $brand)
                                <li>
                                    <a href="{{ route('storefront.products', [$store->slug, 'brand' => $brand->id]) }}" 
                                       class="{{ request('brand') == $brand->id ? 'active' : '' }}">
                                        {{ $brand->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                </aside>

                <!-- Products Grid -->
                <div class="products-content">
                    <!-- Sort Bar -->
                    <div class="sort-bar">
                        <p>{{ $products->total() }} products found</p>
                        <form method="GET" action="{{ route('storefront.products', $store->slug) }}" class="sort-form">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('brand'))
                                <input type="hidden" name="brand" value="{{ request('brand') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            <select name="sort" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                            </select>
                        </form>
                    </div>

                    <!-- Products Grid -->
                    <div class="products-grid">
                        @forelse($products as $product)
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
                            <p>No products found. Try adjusting your filters.</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($products->hasPages())
                    <div class="pagination-wrapper">
                        {{ $products->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

