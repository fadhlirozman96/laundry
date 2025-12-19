<?php $page = 'tax-reports'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Tax Reports</h4>
                    <h6>View tax collected from sales</h6>
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
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('tax-reports') }}"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-6 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Tax Collected</h6>
                                    <h4 class="mb-0">MYR {{ number_format($totalSalesTax ?? 0, 2) }}</h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
                                    <i data-feather="percent" class="text-white"></i>
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
                                    <h6 class="text-muted mb-1">Total Sales Amount</h6>
                                    <h4 class="mb-0">MYR {{ number_format($totalSalesAmount ?? 0, 2) }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i data-feather="dollar-sign" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Tax Summary -->
            @if(isset($monthlyTax) && $monthlyTax->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Monthly Tax Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Month</th>
                                            <th class="text-end">Total Sales</th>
                                            <th class="text-end">Tax Collected</th>
                                            <th class="text-end">Effective Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($monthlyTax as $month)
                                        <tr>
                                            <td>{{ date('F Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</td>
                                            <td class="text-end">MYR {{ number_format($month->total_sales, 2) }}</td>
                                            <td class="text-end">MYR {{ number_format($month->total_tax, 2) }}</td>
                                            <td class="text-end">
                                                {{ $month->total_sales > 0 ? number_format(($month->total_tax / $month->total_sales) * 100, 2) : 0 }}%
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-primary">
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-end">MYR {{ number_format($totalSalesAmount ?? 0, 2) }}</th>
                                            <th class="text-end">MYR {{ number_format($totalSalesTax ?? 0, 2) }}</th>
                                            <th class="text-end">
                                                {{ ($totalSalesAmount ?? 0) > 0 ? number_format((($totalSalesTax ?? 0) / ($totalSalesAmount ?? 1)) * 100, 2) : 0 }}%
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tax Details -->
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
                            <form method="GET" action="{{ route('tax-reports') }}" class="row">
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
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Order #</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Discount</th>
                                    <th>Tax Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($salesTaxData ?? [] as $order)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $order->customer_name ?? 'Walk-in' }}</td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="ref-number">{{ $order->order_number }}</td>
                                    <td>MYR {{ number_format($order->total, 2) }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                                    </td>
                                    <td>MYR {{ number_format($order->discount, 2) }}</td>
                                    <td><strong>MYR {{ number_format($order->tax, 2) }}</strong></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No tax data found for the selected period</td>
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
