<?php $page = 'laundry-orders'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Order: {{ $order->order_number }}</h4>
                <h6>Created {{ $order->created_at->format('d M Y H:i') }} by {{ $order->user->name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.orders') }}" class="btn btn-secondary me-2">
                    <i data-feather="arrow-left" class="me-2"></i>Back
                </a>
                <button class="btn btn-info" onclick="printOrder()">
                    <i data-feather="printer" class="me-2"></i>Print
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Order Info & Status -->
            <div class="col-lg-8">
                <!-- Status Tracker -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="order-status-tracker">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                @php
                                    $statuses = ['pending', 'processing', 'completed'];
                                    $statusMap = ['pending' => 0, 'processing' => 1, 'completed' => 2, 'cancelled' => 3];
                                    $currentIndex = $statusMap[$order->order_status] ?? 0;
                                @endphp
                                @foreach($statuses as $index => $status)
                                    <div class="text-center flex-fill">
                                        <div class="status-circle {{ $index <= $currentIndex ? 'active' : '' }} {{ $index == $currentIndex ? 'current' : '' }}">
                                            @if($index < $currentIndex)
                                                <i data-feather="check"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <small class="d-block mt-1 {{ $index == $currentIndex ? 'fw-bold' : '' }}">
                                            {{ ucfirst($status) }}
                                        </small>
                                    </div>
                                    @if($index < count($statuses) - 1)
                                        <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        @if($order->order_status !== 'completed' && $order->order_status !== 'cancelled')
                            <div class="text-center mt-4">
                                <button class="btn btn-primary btn-lg me-2" onclick="updateStatusNext()">
                                    <i data-feather="arrow-right" class="me-2"></i>
                                    @if($order->order_status == 'pending')
                                        Move to Processing
                                    @elseif($order->order_status == 'processing')
                                        Mark as Completed
                                    @endif
                                </button>
                                <button class="btn btn-danger btn-lg" onclick="cancelOrder()">
                                    <i data-feather="x-circle" class="me-2"></i>Cancel Order
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Items ({{ $order->items->sum('quantity') }} items)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>SKU</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->product_sku }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>MYR {{ number_format($item->price, 2) }}</td>
                                        <td>MYR {{ number_format($item->subtotal, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                        <td>MYR {{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tax:</strong></td>
                                        <td>MYR {{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    @if($order->shipping > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Shipping:</strong></td>
                                        <td>MYR {{ number_format($order->shipping, 2) }}</td>
                                    </tr>
                                    @endif
                                    @if($order->discount > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Discount:</strong></td>
                                        <td>- MYR {{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <td colspan="4" class="text-end"><strong>TOTAL:</strong></td>
                                        <td><strong>MYR {{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Information</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <strong>Created:</strong> {{ $order->created_at->format('d M Y H:i') }}
                            </li>
                            <li class="mb-2">
                                <strong>Created By:</strong> {{ $order->user->name }}
                            </li>
                            <li class="mb-2">
                                <strong>Current Status:</strong> {!! $order->getStatusBadge() !!}
                            </li>
                            @if($order->expected_completion)
                            <li class="mb-2">
                                <strong>Expected Completion:</strong> {{ $order->expected_completion->format('d M Y H:i') }}
                            </li>
                            @endif
                            @if($order->notes)
                            <li class="mb-2">
                                <strong>Notes:</strong><br>{{ $order->notes }}
                            </li>
                            @endif
                            @if($order->special_instructions)
                            <li class="mb-2">
                                <strong>Special Instructions:</strong><br>{{ $order->special_instructions }}
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- Customer Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Customer</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>{{ $order->customer_name }}</strong>
                        </div>
                        @if($order->customer_phone)
                        <div class="mb-2">
                            <i data-feather="phone" class="feather-sm me-2"></i>
                            <span>{{ $order->customer_phone }}</span>
                        </div>
                        @endif
                        @if($order->customer_email)
                        <div class="mb-2">
                            <i data-feather="mail" class="feather-sm me-2"></i>
                            <span>{{ $order->customer_email }}</span>
                        </div>
                        @endif
                        @if($order->customer && $order->customer->address)
                        <div class="mb-2">
                            <i data-feather="map-pin" class="feather-sm me-2"></i>
                            <span>{{ $order->customer->address }}</span>
                        </div>
                        @endif
                        @if($order->customer && $order->customer->city)
                        <div class="mb-2">
                            <i data-feather="map" class="feather-sm me-2"></i>
                            <span>{{ $order->customer->city }}@if($order->customer->country), {{ $order->customer->country }}@endif</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total:</span>
                            <strong>MYR {{ number_format($order->total, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid:</span>
                            <span class="text-success">MYR {{ number_format($order->amount_paid, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Balance:</span>
                            <span class="text-danger">MYR {{ number_format($order->total - $order->amount_paid, 2) }}</span>
                        </div>
                        <hr>
                        <div class="text-center">
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success fs-6">PAID</span>
                            @elseif($order->payment_status == 'partial')
                                <span class="badge bg-warning fs-6">PARTIAL</span>
                            @else
                                <span class="badge bg-danger fs-6">PENDING</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Special Instructions -->
                @if($order->special_instructions)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Special Instructions</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->special_instructions }}</p>
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($order->notes)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .status-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6c757d;
    }
    .status-circle.active {
        background: #28a745;
        color: white;
    }
    .status-circle.current {
        background: #007bff;
        color: white;
        animation: pulse 1.5s infinite;
    }
    .status-line {
        height: 3px;
        background: #e9ecef;
        flex: 1;
        margin: 0 5px;
    }
    .status-line.active {
        background: #28a745;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .timeline {
        list-style: none;
        padding: 0;
        position: relative;
    }
    .timeline-item {
        padding-left: 30px;
        margin-bottom: 20px;
        position: relative;
    }
    .timeline-point {
        position: absolute;
        left: 0;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #007bff;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 17px;
        height: calc(100% + 8px);
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endsection

@push('scripts')
<script>
    function updateStatusNext() {
        const currentStatus = '{{ $order->order_status }}';
        let nextStatus = '';
        let statusLabel = '';
        
        if (currentStatus === 'pending') {
            nextStatus = 'processing';
            statusLabel = 'Processing';
        } else if (currentStatus === 'processing') {
            nextStatus = 'completed';
            statusLabel = 'Completed';
        }
        
        if (!nextStatus) {
            Swal.fire('Error', 'Cannot determine next status', 'error');
            return;
        }
        
        Swal.fire({
            title: 'Update Status',
            text: 'Move order to ' + statusLabel + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update'
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus(nextStatus, statusLabel);
            }
        });
    }
    
    function cancelOrder() {
        Swal.fire({
            title: 'Cancel Order',
            text: 'Are you sure you want to cancel this order?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, cancel order',
            confirmButtonColor: '#dc3545'
        }).then((result) => {
            if (result.isConfirmed) {
                updateStatus('cancelled', 'Cancelled');
            }
        });
    }

    function updateStatus(newStatus, statusLabel) {
        $.ajax({
            url: '{{ route("laundry.update-status", $order->id) }}',
            type: 'POST',
            data: { 
                status: newStatus,
                _token: '{{ csrf_token() }}',
                _method: 'PUT'
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', 'Order status updated to ' + statusLabel, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update status', 'error');
            }
        });
    }

    function printOrder() {
        window.print();
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush



