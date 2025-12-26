<?php $page = 'laundry-dashboard'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laundry Operations Dashboard</h4>
                <h6>Monitor and manage laundry orders</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.create') }}" class="btn btn-added">
                    <i data-feather="plus-circle" class="me-2"></i>New Order
                </a>
            </div>
        </div>

        <!-- Status Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-6 mb-3">
                <a href="{{ route('laundry.orders') }}?status=pending" class="text-decoration-none">
                    <div class="dash-widget dash1" style="cursor: pointer; transition: transform 0.2s;">
                        <div class="dash-widgetimg">
                            <span><i data-feather="clock" class="text-warning"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>{{ $pendingCount }}</h5>
                            <h6>Pending</h6>
                            <p class="text-muted mb-0">New orders</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-6 mb-3">
                <a href="{{ route('laundry.orders') }}?status=processing" class="text-decoration-none">
                    <div class="dash-widget dash2" style="cursor: pointer; transition: transform 0.2s;">
                        <div class="dash-widgetimg">
                            <span><i data-feather="refresh-cw" class="text-info"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>{{ $processingCount }}</h5>
                            <h6>Processing</h6>
                            <p class="text-muted mb-0">In progress</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-6 mb-3">
                <a href="{{ route('laundry.orders') }}?status=completed" class="text-decoration-none">
                    <div class="dash-widget dash1" style="cursor: pointer; transition: transform 0.2s;">
                        <div class="dash-widgetimg">
                            <span><i data-feather="check-circle" class="text-success"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>{{ $completedCount }}</h5>
                            <h6>Completed</h6>
                            <p class="text-muted mb-0">Ready for collection</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-sm-6 col-6 mb-3">
                <a href="{{ route('laundry.qc.index') }}" class="text-decoration-none">
                    <div class="dash-widget dash2" style="cursor: pointer; transition: transform 0.2s;">
                        <div class="dash-widgetimg">
                            <span><i data-feather="check-square" class="text-danger"></i></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>{{ $pendingQC }}</h5>
                            <h6>Pending QC</h6>
                            <p class="text-muted mb-0">Needs inspection</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions & Stats -->
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('laundry.create') }}" class="btn btn-primary btn-lg">
                                <i data-feather="plus" class="me-2"></i>New Laundry Order
                            </a>
                            <a href="{{ route('laundry.qc.index') }}" class="btn btn-warning btn-lg">
                                <i data-feather="check-square" class="me-2"></i>Quality Check ({{ $pendingQC }})
                            </a>
                            <a href="{{ route('laundry.machines') }}" class="btn btn-info btn-lg">
                                <i data-feather="settings" class="me-2"></i>Machine Control
                            </a>
                            <button class="btn btn-secondary btn-lg" onclick="scanQRCode()">
                                <i data-feather="camera" class="me-2"></i>Scan QR Code
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Today's Summary</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Orders Received</strong>
                                    <br><small class="text-muted">New orders today</small>
                                </div>
                                <span class="badge bg-primary rounded-pill fs-6">{{ $todayOrders }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Today's Revenue</strong>
                                    <br><small class="text-muted">Paid orders</small>
                                </div>
                                <span class="badge bg-success rounded-pill fs-6">MYR {{ number_format($todayRevenue, 2) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Pending QC</strong>
                                    <br><small class="text-muted">Awaiting inspection</small>
                                </div>
                                <span class="badge bg-warning rounded-pill fs-6">{{ $pendingQC }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>QC Passed</strong>
                                    <br><small class="text-muted">Ready to collect</small>
                                </div>
                                <span class="badge bg-success rounded-pill fs-6">{{ $qcPassed }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Statistics</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Total Orders</strong>
                                    <br><small class="text-muted">All time</small>
                                </div>
                                <span class="badge bg-info rounded-pill fs-6">{{ $totalOrders }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Total Revenue</strong>
                                    <br><small class="text-muted">Paid orders</small>
                                </div>
                                <span class="badge bg-success rounded-pill fs-6">MYR {{ number_format($totalRevenue, 2) }}</span>
                            </li>
                            @if($cancelledCount > 0)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Cancelled</strong>
                                    <br><small class="text-muted">Cancelled orders</small>
                                </div>
                                <span class="badge bg-danger rounded-pill fs-6">{{ $cancelledCount }}</span>
                            </li>
                            @endif
                        </ul>
                        <hr>
                        <form id="search-order-form">
                            <div class="mb-3">
                                <label class="form-label">Find Order</label>
                                <input type="text" class="form-control" id="search_order_number" placeholder="e.g. ORD-20251226-0004">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i data-feather="search" class="me-2"></i>Search
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Orders</h5>
                <a href="{{ route('laundry.orders') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Status</th>
                                <th>QC</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('laundry.show', $order->id) }}">
                                        <strong>{{ $order->order_number }}</strong>
                                    </a>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    @php
                                        $itemSummary = [];
                                        foreach($order->items as $item) {
                                            $product = $item->product;
                                            $unitType = $product ? ($product->unit_type ?? 'piece') : 'piece';
                                            if (!isset($itemSummary[$unitType])) {
                                                $itemSummary[$unitType] = 0;
                                            }
                                            $itemSummary[$unitType] += $item->quantity;
                                        }
                                        $displayParts = [];
                                        foreach($itemSummary as $type => $qty) {
                                            $label = '';
                                            switch($type) {
                                                case 'piece': $label = 'pcs'; break;
                                                case 'set': $label = 'set'; break;
                                                case 'kg': $label = 'kg'; break;
                                                case 'sqft': $label = 'sqft'; break;
                                                default: $label = 'pcs';
                                            }
                                            $displayParts[] = $qty . ' ' . $label;
                                        }
                                        echo !empty($displayParts) ? implode(', ', $displayParts) : '0 pcs';
                                    @endphp
                                </td>
                                <td>{!! $order->getStatusBadge() !!}</td>
                                <td>
                                    {!! $order->getQcStatusBadge() !!}
                                </td>
                                <td>{{ $order->created_at->format('d M H:i') }}</td>
                                <td>
                                    <a href="{{ route('laundry.show', $order->id) }}" class="btn btn-sm btn-primary">
                                        <i data-feather="eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">No orders yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Scanner Modal -->
<div class="modal fade" id="qr-scanner-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Scan QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <div id="qr-reader" style="width: 100%;"></div>
                <p class="mt-3">Or enter QR code manually:</p>
                <input type="text" class="form-control" id="manual_qr_code" placeholder="Enter QR code">
                <button class="btn btn-primary mt-2" onclick="findByQR()">Find Order</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function scanQRCode() {
        $('#qr-scanner-modal').modal('show');
    }

    function findByQR() {
        var qrCode = $('#manual_qr_code').val();
        if (!qrCode) {
            Swal.fire('Error', 'Please enter a QR code', 'error');
            return;
        }
        
        $.ajax({
            url: '{{ route("laundry.scan-qr") }}',
            type: 'POST',
            data: { qr_code: qrCode, _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    window.location.href = '/laundry-orders/' + response.order.id;
                } else {
                    Swal.fire('Not Found', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Order not found', 'error');
            }
        });
    }

    $('#search-order-form').on('submit', function(e) {
        e.preventDefault();
        var orderNumber = $('#search_order_number').val();
        if (!orderNumber) {
            Swal.fire('Error', 'Please enter order number', 'error');
            return;
        }
        
        $.ajax({
            url: '{{ route("laundry.find-order") }}',
            type: 'POST',
            data: { order_number: orderNumber, _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    window.location.href = '/laundry-orders/' + response.order.id;
                } else {
                    Swal.fire('Not Found', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Order not found', 'error');
            }
        });
    });

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush

@push('styles')
<style>
    .dash-widget {
        transition: all 0.3s ease;
    }
    
    a:hover .dash-widget {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.15) !important;
    }
    
    .dash-widgetcontent h5 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
    }
    
    .dash-widgetcontent h6 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 3px;
    }
    
    .dash-widgetcontent p {
        font-size: 12px;
    }
</style>
@endpush



