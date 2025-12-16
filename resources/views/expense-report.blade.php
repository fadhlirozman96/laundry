<?php $page = 'expense-report'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Expense Report</h4>
                    <h6>View expense summary and reports</h6>
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
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('expense-report') }}"><i data-feather="rotate-ccw"
                                class="feather-rotate-ccw"></i></a>
                    </li>
                </ul>
            </div>

            <!-- Summary Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Expenses</h6>
                                    <h4 class="mb-0">MYR {{ number_format($totalExpenses, 2) }}</h4>
                                </div>
                                <div class="bg-primary rounded-circle p-3">
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
                                    <h6 class="text-muted mb-1">This Month</h6>
                                    <h4 class="mb-0">MYR {{ number_format($thisMonthExpenses, 2) }}</h4>
                                </div>
                                <div class="bg-warning rounded-circle p-3">
                                    <i data-feather="calendar" class="text-white"></i>
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
                                    <h6 class="text-muted mb-1">This Year</h6>
                                    <h4 class="mb-0">MYR {{ number_format($thisYearExpenses, 2) }}</h4>
                                </div>
                                <div class="bg-success rounded-circle p-3">
                                    <i data-feather="trending-up" class="text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses by Category -->
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Expenses by Category</h5>
                        </div>
                        <div class="card-body">
                            @if($byCategory->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Category</th>
                                                <th class="text-end">Total Amount</th>
                                                <th class="text-end">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($byCategory as $category)
                                                <tr>
                                                    <td>{{ $category->category ? $category->category->name : 'Unknown' }}</td>
                                                    <td class="text-end">MYR {{ number_format($category->total, 2) }}</td>
                                                    <td class="text-end">
                                                        {{ $totalExpenses > 0 ? number_format(($category->total / $totalExpenses) * 100, 1) : 0 }}%
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="table-primary">
                                                <th>Total</th>
                                                <th class="text-end">MYR {{ number_format($totalExpenses, 2) }}</th>
                                                <th class="text-end">100%</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-2">No expense data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Category Distribution</h5>
                        </div>
                        <div class="card-body">
                            @if($byCategory->count() > 0)
                                <div id="expense-chart"></div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i data-feather="pie-chart" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-2">No data to display chart</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex gap-3">
                                <a href="{{ route('expense-list') }}" class="btn btn-primary">
                                    <i data-feather="list" class="me-2"></i> View All Expenses
                                </a>
                                <a href="{{ route('expense-category') }}" class="btn btn-secondary">
                                    <i data-feather="tag" class="me-2"></i> Manage Categories
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    $(document).ready(function() {
        @if($byCategory->count() > 0)
        var options = {
            series: [
                @foreach($byCategory as $category)
                    {{ $category->total }},
                @endforeach
            ],
            chart: {
                type: 'donut',
                height: 300
            },
            labels: [
                @foreach($byCategory as $category)
                    "{{ $category->category ? $category->category->name : 'Unknown' }}",
                @endforeach
            ],
            legend: {
                position: 'bottom'
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val.toFixed(1) + '%';
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return 'MYR ' + val.toFixed(2);
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#expense-chart"), options);
        chart.render();
        @endif

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
