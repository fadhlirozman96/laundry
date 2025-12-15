<?php $page = 'index'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash1.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>MYR <span class="counters" data-count="{{ number_format($totalSalesToday, 2, '.', '') }}">{{ number_format($totalSalesToday, 2) }}</span></h5>
                            <h6>Total Sales Today</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash1 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash2.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5><span class="counters" data-count="{{ $totalOrderToday }}">{{ number_format($totalOrderToday) }}</span></h5>
                            <h6>Total Order Today</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash2 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash3.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>MYR <span class="counters" data-count="{{ number_format($totalSalesThisYear, 2, '.', '') }}">{{ number_format($totalSalesThisYear, 2) }}</span></h5>
                            <h6>Total Sales This Year</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-widget dash3 w-100">
                        <div class="dash-widgetimg">
                            <span><img src="{{ URL::asset('/build/img/icons/dash4.svg') }}" alt="img"></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>MYR <span class="counters" data-count="{{ number_format($totalSalesOverall, 2, '.', '') }}">{{ number_format($totalSalesOverall, 2) }}</span></h5>
                            <h6>Total Sales Overall</h6>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count">
                        <div class="dash-counts">
                            <h4>{{ number_format($customerCount) }}</h4>
                            <h5>Customers</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="user"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>{{ number_format($purchaseInvoiceCount) }}</h4>
                            <h5>Purchase Invoice</h5>
                        </div>
                        <div class="dash-imgs">
                            <img src="{{ URL::asset('/build/img/icons/file-text-icon-01.svg') }}" class="img-fluid"
                                alt="icon">
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das2">
                        <div class="dash-counts">
                            <h4>{{ number_format($salesInvoiceCount) }}</h4>
                            <h5>Sales Invoice</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="file"></i>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das3">
                        <div class="dash-counts">
                            <h4>{{ number_format($totalOrderToday) }}</h4>
                            <h5>Orders Today</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="shopping-cart"></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Button trigger modal -->

            <div class="row">
                <div class="col-xl-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Sales Statistics</h5>
                            <div class="graph-sets">
                                <ul class="mb-0">
                                    <li>
                                        <span>Sales</span>
                                    </li>
                                </ul>
                                <div class="dropdown dropdown-wraper">
                                    <button class="btn btn-light btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="selected-year-display">{{ $selectedYear }}</span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach($availableYears as $year)
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item year-filter {{ $year == $selectedYear ? 'active' : '' }}" data-year="{{ $year }}">{{ $year }}</a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="sales_charts" class="chart-initialized" data-chart-months="{{ json_encode($chartData['months'] ?? []) }}" data-chart-data="{{ json_encode($chartData['data'] ?? []) }}"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill default-cover mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Top Services</h4>
                            <div class="view-all-link">
                                <span class="text-muted" style="font-size: 12px;">Most Popular Services</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table dashboard-top-services">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Service Name</th>
                                            <th>Orders</th>
                                            <th>Total Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topServices as $index => $service)
                                        <tr>
                                            <td style="vertical-align: middle;">{{ $index + 1 }}</td>
                                            <td class="productimgname" style="vertical-align: middle;">
                                                <a href="javascript:void(0);" class="product-img">
                                                    <i data-feather="package" style="width: 40px; height: 40px;"></i>
                                                </a>
                                                <a href="javascript:void(0);">{{ $service->product_name ?? 'N/A' }}</a>
                                            </td>
                                            <td style="vertical-align: middle; text-align: center;">
                                                @php
                                                    $orderCount = 0;
                                                    if (is_object($service)) {
                                                        $orderCount = property_exists($service, 'order_count') ? (int)$service->order_count : (isset($service->order_count) ? (int)$service->order_count : 0);
                                                    } elseif (is_array($service)) {
                                                        $orderCount = isset($service['order_count']) ? (int)$service['order_count'] : 0;
                                                    }
                                                    // Debug: uncomment to see what we're getting
                                                    // \Log::info('Service data:', ['product' => $service->product_name ?? 'N/A', 'order_count' => $orderCount, 'type' => gettype($service)]);
                                                @endphp
                                                <span class="badge badge-light-info" style="color: #000000 !important; background-color: #e7f3ff !important; padding: 5px 10px !important; border-radius: 4px !important; display: inline-block !important; min-width: 30px !important; font-weight: 600 !important;">{{ $orderCount }}</span>
                                            </td>
                                            <td style="vertical-align: middle; text-align: right;">
                                                <strong>MYR {{ number_format($service->total_revenue ?? 0, 2) }}</strong>
                                                <small class="text-muted d-block">Qty: {{ number_format($service->total_quantity ?? 0) }}</small>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <p class="text-muted mb-0">No service data available.</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Latest Order</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive dataview">
                        <table class="table dashboard-latest-orders">
                            <thead>
                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestOrders as $order)
                                <tr>
                                    <td>
                                        <a href="javascript:void(0);">{{ $order->order_number }}</a>
                                    </td>
                                    <td>
                                        <div class="productimgname">
                                            <a href="javascript:void(0);">{{ $order->customer_name ?? 'N/A' }}</a>
                                            @if($order->customer_email)
                                                <span class="text-muted d-block" style="font-size: 12px;">{{ $order->customer_email }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-info">{{ $order->items->count() }} item(s)</span>
                                        @if($order->items->count() > 0)
                                            <div class="mt-1" style="font-size: 12px; color: #6c757d;">
                                                @foreach($order->items->take(2) as $item)
                                                    {{ $item->product_name }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                                @if($order->items->count() > 2)
                                                    +{{ $order->items->count() - 2 }} more
                                                @endif
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>MYR {{ number_format($order->total, 2) }}</strong>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'badge-light-info';
                                            $statusText = 'Pending';
                                            if($order->order_status == 'completed') {
                                                $statusClass = 'badge-light-success';
                                                $statusText = 'Completed';
                                            } elseif($order->order_status == 'pending') {
                                                $statusClass = 'badge-light-warning';
                                                $statusText = 'Pending';
                                            } elseif($order->order_status == 'cancelled') {
                                                $statusClass = 'badge-light-danger';
                                                $statusText = 'Cancelled';
                                            } else {
                                                $statusText = ucfirst($order->order_status ?? 'pending');
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }}" style="color: #000000 !important; padding: 5px 10px !important; border-radius: 4px !important; display: inline-block !important; font-weight: 600 !important; background-color: {{ $order->order_status == 'completed' ? '#d4edda' : ($order->order_status == 'pending' ? '#fff3cd' : ($order->order_status == 'cancelled' ? '#f8d7da' : '#e7f3ff')) }} !important;">{{ $statusText }}</span>
                                    </td>
                                    <td>{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="{{ route('purchase-list') }}" title="View Orders">
                                                <i data-feather="eye" class="feather-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <p class="text-muted mb-0">No orders found for this store.</p>
                                    </td>
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
    var currentChart = null;
    
    // Function to update chart with new data
    function updateChart(months, salesData) {
        // Ensure all values are positive
        if (Array.isArray(salesData)) {
            salesData = salesData.map(function(value) {
                var numValue = parseFloat(value) || 0;
                return Math.max(0, numValue);
            });
        }
        
        // Ensure we have exactly 12 months
        var defaultMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        if (!Array.isArray(months) || months.length !== 12) {
            months = defaultMonths;
        }
        if (!Array.isArray(salesData) || salesData.length !== 12) {
            var newData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
            if (Array.isArray(salesData)) {
                for (var i = 0; i < Math.min(salesData.length, 12); i++) {
                    newData[i] = parseFloat(salesData[i]) || 0;
                }
            }
            salesData = newData;
        }
        
        // Calculate max value for y-axis
        var maxValue = Math.max(...salesData);
        var yAxisMax = maxValue > 0 ? Math.ceil(maxValue * 1.2) : 100;
        
        // Destroy existing chart
        if (currentChart) {
            currentChart.destroy();
        }
        
        // Create new chart
        var options = {
            series: [{
                name: 'Sales',
                data: salesData
            }],
            colors: ['#28C76F'],
            chart: {
                type: 'bar',
                height: 320,
                id: 'sales_charts',
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    borderRadiusApplication: "end",
                    columnWidth: '60%',
                },
            },
            dataLabels: {
                enabled: false
            },
            yaxis: {
                min: 0,
                max: yAxisMax,
                tickAmount: 5,
                labels: {
                    formatter: function(val) {
                        return "MYR " + val.toFixed(0);
                    }
                }
            },
            xaxis: {
                categories: months,
            },
            legend: {
                show: true,
                position: 'top',
                horizontalAlign: 'right'
            },
            fill: {
                opacity: 1,
                type: 'solid'
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "MYR " + val.toFixed(2);
                    }
                }
            }
        };
        
        currentChart = new ApexCharts(document.querySelector("#sales_charts"), options);
        currentChart.render();
    }
    
    // Initialize chart on page load
    function initializeChart() {
        // Wait a bit to ensure chart-data.js has loaded and been skipped
        setTimeout(function() {
            if ($('#sales_charts').length > 0 && typeof ApexCharts !== 'undefined') {
                // Destroy any existing chart first
                try {
                    var existingChart = ApexCharts.getChartByID('sales_charts');
                    if (existingChart) {
                        existingChart.destroy();
                    }
                } catch(e) {
                    // Ignore
                }
                
                var chartElement = document.querySelector("#sales_charts");
                if (chartElement) {
                var chartMonths = chartElement.getAttribute('data-chart-months');
                var chartData = chartElement.getAttribute('data-chart-data');
                
                var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                var salesData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                
                try {
                    if (chartMonths && chartMonths.trim() !== '') {
                        var parsedMonths = JSON.parse(chartMonths);
                        if (Array.isArray(parsedMonths) && parsedMonths.length === 12) {
                            months = parsedMonths;
                        }
                    }
                } catch(e) {
                    console.warn('Error parsing chart months:', e);
                }
                
                try {
                    if (chartData && chartData.trim() !== '') {
                        var parsedData = JSON.parse(chartData);
                        if (Array.isArray(parsedData) && parsedData.length === 12) {
                            salesData = parsedData;
                        }
                    }
                } catch(e) {
                    console.warn('Error parsing chart data:', e);
                }
                
                    updateChart(months, salesData);
                }
            }
        }, 100);
    }
    
    // Year filter functionality with AJAX (no page refresh)
    $(document).on('click', '.year-filter', function(e) {
        e.preventDefault();
        var year = $(this).data('year');
        var $button = $(this);
        
        // Update selected year display
        $('#selected-year-display').text(year);
        
        // Update active state
        $('.year-filter').removeClass('active');
        $button.addClass('active');
        
        // Close dropdown
        $('.dropdown-toggle').dropdown('hide');
        
        // Show loading indicator
        var $chartContainer = $('#sales_charts');
        var originalHeight = $chartContainer.height() || 320;
        $chartContainer.html('<div class="text-center p-4" style="height: ' + originalHeight + 'px; display: flex; align-items: center; justify-content: center;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        
        // Make AJAX request
        $.ajax({
            url: '{{ route("index") }}',
            method: 'GET',
            data: {
                year: year
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                try {
                    var data = typeof response === 'string' ? JSON.parse(response) : response;
                    
                    if (data.chartData && data.chartData.months && data.chartData.data) {
                        // Update chart data attributes
                        $('#sales_charts').attr('data-chart-months', JSON.stringify(data.chartData.months));
                        $('#sales_charts').attr('data-chart-data', JSON.stringify(data.chartData.data));
                        
                        // Update chart with new data
                        updateChart(data.chartData.months, data.chartData.data);
                    } else {
                        console.error('Invalid chart data received');
                        $chartContainer.html('<div class="text-center p-4 text-danger">Error: Invalid data received</div>');
                    }
                } catch(e) {
                    console.error('Error parsing response:', e);
                    $chartContainer.html('<div class="text-center p-4 text-danger">Error loading chart data</div>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                $('#sales_charts').html('<div class="text-center p-4 text-danger">Error loading chart data. Please try again.</div>');
            }
        });
    });
    
    // Initialize chart on page load
    initializeChart();
    
    // Reinitialize feather icons after page load
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
    
    // Ensure chart is rendered after page load
    setTimeout(function() {
        if (typeof ApexCharts !== 'undefined' && $('#sales_charts').length > 0) {
            // Chart will be rendered by chart-data.js
            // Force re-render if needed
            var chartElement = document.querySelector("#sales_charts");
            if (chartElement && !chartElement.querySelector('.apexcharts-canvas')) {
                // Chart not rendered yet, trigger render
                if (typeof window.renderSalesChart === 'function') {
                    window.renderSalesChart();
                }
            }
        }
    }, 500);
});
</script>
@endpush
