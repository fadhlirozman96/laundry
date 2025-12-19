<?php $page = 'supplier-report'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Supplier/Product Source Report</h4>
                    <h6>View product inventory and value</h6>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Pdf"><img
                                src="{{ URL::asset('/build/img/icons/pdf.svg') }}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Excel"><img
                                src="{{ URL::asset('/build/img/icons/excel.svg') }}" alt="img"></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><i data-feather="printer"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('supplier-report') }}"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Notice -->
            <div class="alert alert-info mb-4">
                <i data-feather="info" class="me-2"></i>
                <strong>Note:</strong> This system doesn't have a dedicated supplier module. This report shows product inventory value based on product cost data.
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Inventory Value</h6>
                                    <h4 class="mb-0">MYR {{ number_format($totalValue ?? 0, 2) }}</h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
                                    <i data-feather="dollar-sign" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Products</h6>
                                    <h4 class="mb-0">{{ count($purchaseData ?? []) }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i data-feather="package" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product List -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>Category</label>
                                        <select class="select" id="filter_category">
                                            <option value="">All Categories</option>
                                            @php
                                                $categories = collect($purchaseData)->pluck('category_name')->unique()->filter();
                                            @endphp
                                            @foreach($categories as $category)
                                                <option value="{{ $category }}">{{ $category }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>&nbsp;</label>
                                        <a class="btn btn-filters w-100" onclick="$('.datanew').DataTable().search($('#filter_category').val()).draw();"> 
                                            <i data-feather="search" class="feather-search"></i> Search 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Stock Qty</th>
                                    <th>Unit Cost</th>
                                    <th>Unit Price</th>
                                    <th>Total Value</th>
                                    <th>Added Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseData ?? [] as $item)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td class="productimgname">
                                        <div class="view-product me-2">
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="product" style="width:40px;height:40px;object-fit:cover;">
                                            @else
                                                <img src="{{ URL::asset('/build/img/products/default.png') }}" alt="product">
                                            @endif
                                        </div>
                                        <a href="javascript:void(0);">{{ $item->product_name }}</a>
                                    </td>
                                    <td>{{ $item->sku ?? 'N/A' }}</td>
                                    <td>{{ $item->category_name ?? 'N/A' }}</td>
                                    <td>{{ number_format($item->quantity ?? 0) }}</td>
                                    <td>MYR {{ number_format($item->cost ?? 0, 2) }}</td>
                                    <td>MYR {{ number_format($item->price ?? 0, 2) }}</td>
                                    <td>MYR {{ number_format($item->total_value ?? 0, 2) }}</td>
                                    <td>{{ $item->created_at ? $item->created_at->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No product data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
