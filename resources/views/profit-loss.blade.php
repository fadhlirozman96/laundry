<?php $page = 'profit-loss'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Profit & Loss Report</h4>
                    <h6>View your business profitability</h6>
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
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('profit-loss') }}"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Date Filter -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('profit-loss') }}" class="row align-items-end">
                        <div class="col-lg-3 col-md-4">
                            <div class="input-blocks mb-0">
                                <label>Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ $startDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <div class="input-blocks mb-0">
                                <label>End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{ $endDate ?? '' }}">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="search" class="me-2"></i> Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Revenue</h6>
                                    <h3 class="mb-0 text-white">MYR {{ number_format($revenue ?? 0, 2) }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i data-feather="trending-up" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Total Expenses</h6>
                                    <h3 class="mb-0 text-white">MYR {{ number_format(($costOfGoodsSold ?? 0) + ($operatingExpenses ?? 0), 2) }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i data-feather="trending-down" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="card {{ ($netProfit ?? 0) >= 0 ? 'bg-primary' : 'bg-warning' }} text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-white-50 mb-1">Net {{ ($netProfit ?? 0) >= 0 ? 'Profit' : 'Loss' }}</h6>
                                    <h3 class="mb-0 text-white">MYR {{ number_format(abs($netProfit ?? 0), 2) }}</h3>
                                </div>
                                <div class="bg-white bg-opacity-25 rounded-circle p-3">
                                    <i data-feather="{{ ($netProfit ?? 0) >= 0 ? 'check-circle' : 'alert-circle' }}" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- P&L Statement -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profit & Loss Statement</h5>
                            <small class="text-muted">{{ date('d M Y', strtotime($startDate ?? 'today')) }} - {{ date('d M Y', strtotime($endDate ?? 'today')) }}</small>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <tbody>
                                        <!-- Revenue Section -->
                                        <tr class="table-light">
                                            <th colspan="2"><strong>REVENUE</strong></th>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">Sales Revenue</td>
                                            <td class="text-end">MYR {{ number_format($revenue ?? 0, 2) }}</td>
                                        </tr>
                                        <tr class="table-success">
                                            <th>Total Revenue</th>
                                            <th class="text-end">MYR {{ number_format($revenue ?? 0, 2) }}</th>
                                        </tr>

                                        <!-- Cost of Goods Sold -->
                                        <tr class="table-light">
                                            <th colspan="2"><strong>COST OF GOODS SOLD</strong></th>
                                        </tr>
                                        <tr>
                                            <td class="ps-4">Cost of Products Sold</td>
                                            <td class="text-end">MYR {{ number_format($costOfGoodsSold ?? 0, 2) }}</td>
                                        </tr>
                                        <tr class="table-secondary">
                                            <th>Total Cost of Goods Sold</th>
                                            <th class="text-end">MYR {{ number_format($costOfGoodsSold ?? 0, 2) }}</th>
                                        </tr>

                                        <!-- Gross Profit -->
                                        <tr class="{{ ($grossProfit ?? 0) >= 0 ? 'table-info' : 'table-warning' }}">
                                            <th>Gross Profit</th>
                                            <th class="text-end">MYR {{ number_format($grossProfit ?? 0, 2) }}</th>
                                        </tr>

                                        <!-- Operating Expenses -->
                                        <tr class="table-light">
                                            <th colspan="2"><strong>OPERATING EXPENSES</strong></th>
                                        </tr>
                                        @if(isset($expensesByCategory) && $expensesByCategory->count() > 0)
                                            @foreach($expensesByCategory as $category)
                                            <tr>
                                                <td class="ps-4">{{ $category->category_name ?? 'Other Expenses' }}</td>
                                                <td class="text-end">MYR {{ number_format($category->total, 2) }}</td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td class="ps-4 text-muted" colspan="2">No expenses recorded</td>
                                            </tr>
                                        @endif
                                        <tr class="table-secondary">
                                            <th>Total Operating Expenses</th>
                                            <th class="text-end">MYR {{ number_format($operatingExpenses ?? 0, 2) }}</th>
                                        </tr>

                                        <!-- Net Profit/Loss -->
                                        <tr class="{{ ($netProfit ?? 0) >= 0 ? 'table-success' : 'table-danger' }}">
                                            <th style="font-size: 1.1em;">NET {{ ($netProfit ?? 0) >= 0 ? 'PROFIT' : 'LOSS' }}</th>
                                            <th class="text-end" style="font-size: 1.1em;">MYR {{ number_format(abs($netProfit ?? 0), 2) }}</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Profit Margin -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Profit Margins</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Gross Margin</span>
                                    <span>{{ ($revenue ?? 0) > 0 ? number_format((($grossProfit ?? 0) / ($revenue ?? 1)) * 100, 1) : 0 }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-info" style="width: {{ ($revenue ?? 0) > 0 ? min(100, (($grossProfit ?? 0) / ($revenue ?? 1)) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Net Margin</span>
                                    <span>{{ ($revenue ?? 0) > 0 ? number_format((($netProfit ?? 0) / ($revenue ?? 1)) * 100, 1) : 0 }}%</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar {{ ($netProfit ?? 0) >= 0 ? 'bg-success' : 'bg-danger' }}" style="width: {{ ($revenue ?? 0) > 0 ? min(100, abs(($netProfit ?? 0) / ($revenue ?? 1)) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Related Reports</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('sales-report') }}" class="btn btn-outline-primary">
                                    <i data-feather="bar-chart" class="me-2"></i> Sales Report
                                </a>
                                <a href="{{ route('expense-report') }}" class="btn btn-outline-primary">
                                    <i data-feather="credit-card" class="me-2"></i> Expense Report
                                </a>
                                <a href="{{ route('income-report') }}" class="btn btn-outline-primary">
                                    <i data-feather="dollar-sign" class="me-2"></i> Income Report
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Breakdown -->
            @if(isset($monthlyData) && count($monthlyData) > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Breakdown</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>Month</th>
                                    <th class="text-end">Revenue</th>
                                    <th class="text-end">COGS</th>
                                    <th class="text-end">Gross Profit</th>
                                    <th class="text-end">Expenses</th>
                                    <th class="text-end">Net Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($monthlyData as $month)
                                <tr>
                                    <td>{{ $month['month'] }}</td>
                                    <td class="text-end">MYR {{ number_format($month['revenue'], 2) }}</td>
                                    <td class="text-end">MYR {{ number_format($month['cogs'], 2) }}</td>
                                    <td class="text-end {{ $month['gross_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        MYR {{ number_format($month['gross_profit'], 2) }}
                                    </td>
                                    <td class="text-end">MYR {{ number_format($month['expenses'], 2) }}</td>
                                    <td class="text-end {{ $month['net_profit'] >= 0 ? 'text-success' : 'text-danger' }}">
                                        <strong>MYR {{ number_format($month['net_profit'], 2) }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-primary">
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">MYR {{ number_format($revenue ?? 0, 2) }}</th>
                                    <th class="text-end">MYR {{ number_format($costOfGoodsSold ?? 0, 2) }}</th>
                                    <th class="text-end">MYR {{ number_format($grossProfit ?? 0, 2) }}</th>
                                    <th class="text-end">MYR {{ number_format($operatingExpenses ?? 0, 2) }}</th>
                                    <th class="text-end">MYR {{ number_format($netProfit ?? 0, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            @endif
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

