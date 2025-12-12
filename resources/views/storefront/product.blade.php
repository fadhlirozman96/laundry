@extends('storefront.layout')

@section('title', $product->name . ' - ' . $store->name)

@section('content')
    <div class="product-page">
        <div class="container">
            <!-- Related Products -->
            @if($relatedProducts && $relatedProducts->count() > 0)
            <section class="related-products">
                <div class="section-header">
                    <h2>Related Services</h2>
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
                                    <span class="price">RM{{ number_format($relatedProduct->price, 2) }}@if($relatedProduct->unit) / {{ $relatedProduct->unit->short_name }}@endif</span>
                                </div>
                            </div>
                        </a>
                        <button class="btn-add-cart" data-product-id="{{ $relatedProduct->id }}">Book Service</button>
                    </div>
                    @endforeach
                </div>
            </section>
            @else
            <section class="related-products">
                <div class="section-header">
                    <h2>Related Services</h2>
                </div>
                <div class="no-products">
                    <p>No related services available.</p>
                </div>
            </section>
            @endif
        </div>
    </div>
@endsection


