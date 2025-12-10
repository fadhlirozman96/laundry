<?php $page = 'product-details'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Details</h4>
                    <h6>Full details of a product</h6>
                </div>
            </div>
            <!-- /add -->
            <div class="row">
                <div class="col-lg-8 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="bar-code-view">
                                @if($product->barcode)
                                    <img src="{{ URL::asset('/build/img/barcode/' . $product->barcode . '.png') }}" alt="barcode">
                                @else
                                    <img src="{{ URL::asset('/build/img/barcode/barcode1.png') }}" alt="barcode">
                                @endif
                                <a class="printimg">
                                    <img src="{{ URL::asset('/build/img/icons/printer.svg') }}" alt="print">
                                </a>
                            </div>
                            <div class="productdetails">
                                <ul class="product-bar">
                                    <li>
                                        <h4>Product</h4>
                                        <h6>{{ $product->name }}</h6>
                                    </li>
                                    <li>
                                        <h4>Category</h4>
                                        <h6>{{ $product->category->name ?? 'N/A' }}</h6>
                                    </li>
                                    <li>
                                        <h4>Brand</h4>
                                        <h6>{{ $product->brand->name ?? 'None' }}</h6>
                                    </li>
                                    <li>
                                        <h4>Store</h4>
                                        <h6><span class="badge bg-primary">{{ $product->store->name ?? 'No Store Assigned' }}</span></h6>
                                    </li>
                                    <li>
                                        <h4>Unit</h4>
                                        <h6>{{ $product->unit->name ?? 'Piece' }}</h6>
                                    </li>
                                    <li>
                                        <h4>SKU</h4>
                                        <h6>{{ $product->sku }}</h6>
                                    </li>
                                    <li>
                                        <h4>Minimum Qty</h4>
                                        <h6>{{ $product->alert_quantity ?? 'N/A' }}</h6>
                                    </li>
                                    <li>
                                        <h4>Quantity</h4>
                                        <h6>{{ $product->quantity }}</h6>
                                    </li>
                                    <li>
                                        <h4>Tax Type</h4>
                                        <h6>{{ $product->tax_type ?? 'Exclusive' }}</h6>
                                    </li>
                                    <li>
                                        <h4>Discount</h4>
                                        <h6>{{ number_format($product->discount_value ?? 0, 2) }} ({{ ucfirst($product->discount_type ?? 'fixed') }})</h6>
                                    </li>
                                    <li>
                                        <h4>Cost</h4>
                                        <h6>${{ number_format($product->cost ?? 0, 2) }}</h6>
                                    </li>
                                    <li>
                                        <h4>Price</h4>
                                        <h6>${{ number_format($product->price, 2) }}</h6>
                                    </li>
                                    <li>
                                        <h4>Status</h4>
                                        <h6>{{ $product->is_active ? 'Active' : 'Inactive' }}</h6>
                                    </li>
                                    <li>
                                        <h4>Description</h4>
                                        <h6>{{ $product->description ?? 'No description available' }}</h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="slider-product-details">
                                <div class="owl-carousel owl-theme product-slide">
                                    <div class="slider-product">
                                        @if($product->image)
                                            <img src="{{ URL::asset($product->image) }}" alt="{{ $product->name }}">
                                            <h4>{{ basename($product->image) }}</h4>
                                            @php
                                                $imagePath = public_path($product->image);
                                                $imageSize = file_exists($imagePath) ? filesize($imagePath) : 0;
                                            @endphp
                                            <h6>{{ $imageSize > 0 ? number_format($imageSize / 1024, 0) . 'kb' : 'N/A' }}</h6>
                                        @else
                                            <img src="{{ URL::asset('/build/img/products/product69.jpg') }}" alt="img">
                                            <h4>No image available</h4>
                                            <h6>N/A</h6>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /add -->
        </div>
    </div>
@endsection
