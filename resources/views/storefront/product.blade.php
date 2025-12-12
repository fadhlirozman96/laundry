@extends('storefront.layout')

@section('title', $product->name . ' - ' . $store->name)

@section('content')
    <div class="product-page">
        <div class="container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb">
                <a href="{{ route('storefront.index', $store->slug) }}">Home</a>
                <span>/</span>
                <a href="{{ route('storefront.products', $store->slug) }}">Products</a>
                @if($product->category)
                <span>/</span>
                <a href="{{ route('storefront.category', [$store->slug, $product->category->slug]) }}">{{ $product->category->name }}</a>
                @endif
                <span>/</span>
                <span>{{ $product->name }}</span>
            </nav>

            <div class="product-detail">
                <div class="product-images">
                    <div class="main-image">
                        @if($product->image)
                            <img src="{{ asset('uploads/products/' . $product->image) }}" alt="{{ $product->name }}" id="mainProductImage">
                        @else
                            <img src="{{ asset('build/img/placeholder.png') }}" alt="{{ $product->name }}" id="mainProductImage">
                        @endif
                    </div>
                    @if($product->images && count($product->images) > 0)
                    <div class="thumbnail-images">
                        @foreach($product->images as $image)
                        <img src="{{ asset('uploads/products/' . $image) }}" alt="{{ $product->name }}" class="thumbnail">
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="product-info">
                    <h1 class="product-title">{{ $product->name }}</h1>
                    
                    @if($product->category || $product->brand)
                    <div class="product-meta">
                        @if($product->category)
                        <span class="meta-item">Category: <a href="{{ route('storefront.category', [$store->slug, $product->category->slug]) }}">{{ $product->category->name }}</a></span>
                        @endif
                        @if($product->brand)
                        <span class="meta-item">Brand: {{ $product->brand->name }}</span>
                        @endif
                    </div>
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
                            <span class="discount-badge">Save {{ $product->discount_type === 'percentage' ? $product->discount_value . '%' : '$' . number_format($product->discount_value, 2) }}</span>
                        @else
                            <span class="price">${{ number_format($product->price, 2) }}</span>
                        @endif
                    </div>

                    @if($product->description)
                    <div class="product-description">
                        <h3>Description</h3>
                        <p>{{ $product->description }}</p>
                    </div>
                    @endif

                    <div class="product-actions">
                        <div class="quantity-selector">
                            <button class="qty-btn" data-action="decrease">-</button>
                            <input type="number" id="productQuantity" value="1" min="1" max="{{ $product->quantity }}">
                            <button class="qty-btn" data-action="increase">+</button>
                        </div>
                        <button class="btn btn-primary btn-add-cart-large" data-product-id="{{ $product->id }}">
                            Add to Cart
                        </button>
                    </div>

                    @if($product->quantity <= 0)
                    <div class="stock-status out-of-stock">
                        <span>Out of Stock</span>
                    </div>
                    @elseif($product->quantity <= $product->alert_quantity)
                    <div class="stock-status low-stock">
                        <span>Only {{ $product->quantity }} left in stock</span>
                    </div>
                    @else
                    <div class="stock-status in-stock">
                        <span>In Stock ({{ $product->quantity }} available)</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedProducts && $relatedProducts->count() > 0)
            <section class="related-products">
                <div class="section-header">
                    <h2>Related Products</h2>
                </div>
                <div class="products-grid">
                    @foreach($relatedProducts as $relatedProduct)
                    <div class="product-card">
                        <a href="{{ route('storefront.product', [$store->slug, $relatedProduct->slug]) }}" class="product-link">
                            <div class="product-image">
                                @if($relatedProduct->image)
                                    <img src="{{ asset('uploads/products/' . $relatedProduct->image) }}" alt="{{ $relatedProduct->name }}">
                                @else
                                    <img src="{{ asset('build/img/placeholder.png') }}" alt="{{ $relatedProduct->name }}">
                                @endif
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">{{ $relatedProduct->name }}</h3>
                                <div class="product-price">
                                    <span class="price">${{ number_format($relatedProduct->price, 2) }}</span>
                                </div>
                            </div>
                        </a>
                        <button class="btn-add-cart" data-product-id="{{ $relatedProduct->id }}">Add to Cart</button>
                    </div>
                    @endforeach
                </div>
            </section>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Thumbnail image click handler
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.addEventListener('click', function() {
            document.getElementById('mainProductImage').src = this.src;
        });
    });

    // Quantity selector
    document.querySelectorAll('.qty-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = document.getElementById('productQuantity');
            const action = this.dataset.action;
            let value = parseInt(input.value);
            
            if (action === 'increase') {
                value = Math.min(value + 1, {{ $product->quantity }});
            } else if (action === 'decrease') {
                value = Math.max(value - 1, 1);
            }
            
            input.value = value;
        });
    });
</script>
@endpush

