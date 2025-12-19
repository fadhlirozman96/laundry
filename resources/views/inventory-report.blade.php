<?php $page = 'inventory-report'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Inventory Report</h4>
                    <h6>View current stock levels</h6>
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
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('inventory-report') }}"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Products</h6>
                                    <h4 class="mb-0">{{ number_format($totalProducts ?? 0) }}</h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
                                    <i data-feather="box" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Stock</h6>
                                    <h4 class="mb-0">{{ number_format($totalStock ?? 0) }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i data-feather="package" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Low Stock</h6>
                                    <h4 class="mb-0 text-warning">{{ number_format($lowStockProducts ?? 0) }}</h4>
                                </div>
                                <div class="bg-warning rounded-circle p-3">
                                    <i data-feather="alert-triangle" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Out of Stock</h6>
                                    <h4 class="mb-0 text-danger">{{ number_format($outOfStock ?? 0) }}</h4>
                                </div>
                                <div class="bg-danger rounded-circle p-3">
                                    <i data-feather="x-circle" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- /product list -->
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
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="input-blocks">
                                        <label>Product</label>
                                        <select class="select" id="filter_product">
                                            <option value="">All Products</option>
                                            @foreach($products ?? [] as $product)
                                                <option value="{{ $product->name }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-blocks">
                                        <label>Category</label>
                                        <select class="select" id="filter_category">
                                            <option value="">All Categories</option>
                                            @foreach($categories ?? [] as $category)
                                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="input-blocks">
                                        <label>Stock Status</label>
                                        <select class="select" id="filter_stock">
                                            <option value="">All</option>
                                            <option value="in_stock">In Stock</option>
                                            <option value="low_stock">Low Stock</option>
                                            <option value="out_of_stock">Out of Stock</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>&nbsp;</label>
                                        <a class="btn btn-filters w-100" id="apply-filters"> 
                                            <i data-feather="search" class="feather-search"></i> Search 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table datanew" id="inventory-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Unit</th>
                                    <th>In Stock</th>
                                    <th>Alert Qty</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventoryData ?? [] as $item)
                                <tr data-stock-status="{{ $item->quantity == 0 ? 'out_of_stock' : ($item->track_quantity && $item->quantity <= $item->alert_quantity ? 'low_stock' : 'in_stock') }}">
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
                                        <a href="javascript:void(0);">{{ $item->name }}</a>
                                    </td>
                                    <td>{{ $item->sku ?? 'N/A' }}</td>
                                    <td>{{ $item->category_name ?? 'N/A' }}</td>
                                    <td>{{ $item->unit_name ?? 'pc' }}</td>
                                    <td>{{ number_format($item->quantity ?? 0) }}</td>
                                    <td>{{ number_format($item->alert_quantity ?? 0) }}</td>
                                    <td>
                                        @if($item->quantity == 0)
                                            <span class="badge badge-linedanger">Out of Stock</span>
                                        @elseif($item->track_quantity && $item->quantity <= $item->alert_quantity)
                                            <span class="badges-warning">Low Stock</span>
                                        @else
                                            <span class="badge-linesuccess">In Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No inventory data found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        $('#apply-filters').on('click', function() {
            var table = $('.datanew').DataTable();
            var productFilter = $('#filter_product').val();
            var categoryFilter = $('#filter_category').val();
            
            // Combine filters
            var searchTerm = '';
            if (productFilter) searchTerm += productFilter + ' ';
            if (categoryFilter) searchTerm += categoryFilter;
            
            table.search(searchTerm.trim()).draw();
        });
    });
</script>
@endpush
