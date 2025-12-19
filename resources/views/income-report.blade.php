<?php $page = 'income-report'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Income Report</h4>
                    <h6>View income from sales</h6>
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
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('income-report') }}"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Income</h6>
                                    <h4 class="mb-0">MYR {{ number_format($totalIncome ?? 0, 2) }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i data-feather="dollar-sign" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Orders</h6>
                                    <h4 class="mb-0">{{ number_format($totalOrders ?? 0) }}</h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
                                    <i data-feather="shopping-cart" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Average Order Value</h6>
                                    <h4 class="mb-0">MYR {{ number_format($avgOrderValue ?? 0, 2) }}</h4>
                                </div>
                                <div class="bg-warning rounded-circle p-3">
                                    <i data-feather="trending-up" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income by Payment Method -->
            @if(isset($byPaymentMethod) && $byPaymentMethod->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Income by Payment Method</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($byPaymentMethod as $method)
                                <div class="col-md-3 col-sm-6 mb-3">
                                    <div class="border rounded p-3 text-center">
                                        <h6 class="text-muted mb-1">{{ ucfirst($method->payment_method ?? 'N/A') }}</h6>
                                        <h4 class="mb-1">MYR {{ number_format($method->total, 2) }}</h4>
                                        <small class="text-muted">{{ $method->count }} orders</small>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Income List -->
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
                            <form method="GET" action="{{ route('income-report') }}" class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" name="start_date" value="{{ $startDate ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="end_date" value="{{ $endDate ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>Payment Method</label>
                                        <select class="select" name="payment_method">
                                            <option value="">All Methods</option>
                                            <option value="cash">Cash</option>
                                            <option value="card">Card</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="e-wallet">E-Wallet</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-filters w-100"> 
                                            <i data-feather="search" class="feather-search"></i> Search 
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                                    <th>Date</th>
                                    <th>Order #</th>
                                    <th>Customer</th>
                                    <th>Payment Method</th>
                                    <th>Subtotal</th>
                                    <th>Tax</th>
                                    <th>Discount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($incomeData ?? [] as $order)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->customer_name ?? 'Walk-in' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                                    </td>
                                    <td>MYR {{ number_format($order->subtotal, 2) }}</td>
                                    <td>MYR {{ number_format($order->tax, 2) }}</td>
                                    <td>MYR {{ number_format($order->discount, 2) }}</td>
                                    <td><strong>MYR {{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No income data found for the selected period</td>
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
    });
</script>
@endpush
