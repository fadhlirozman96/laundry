@extends('storefront.layout')

@section('title', $category->name . ' - ' . $store->name)

@section('content')
    <div class="category-page">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1>{{ $category->name }}</h1>
                @if($category->description)
                <p>{{ $category->description }}</p>
                @endif
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
                            <div class="product-price">
                                @if($product->discount_value > 0)
                                    @php
                                        $discountedPrice = $product->discount_type === 'percentage' 
                                            ? $product->price - ($product->price * $product->discount_value / 100)
                                            : $product->price - $product->discount_value;
                                    @endphp
                                    <span class="price-old">RM{{ number_format($product->price, 2) }}</span>
                                    <span class="price-new">${{ number_format($discountedPrice, 2) }}</span>
                                @else
                                    <span class="price">RM{{ number_format($product->price, 2) }}@if($product->unit) / {{ $product->unit->short_name }}@endif</span>
                                @endif
                            </div>
                        </div>
                    </a>
                    <button class="btn-add-cart" data-product-id="{{ $product->id }}">Add to Cart</button>
                </div>
                        @empty
                <div class="no-products">
                    <p>No services found in this category.</p>
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
@endsection

