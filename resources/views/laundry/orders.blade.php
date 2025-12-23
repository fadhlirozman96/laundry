<?php $page = 'laundry-orders'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Laundry Orders</h4>
                <h6>Manage all laundry orders</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.create') }}" class="btn btn-added">
                    <i data-feather="plus-circle" class="me-2"></i>New Order
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laundry.orders') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="">All Status</option>
                            <option value="received" {{ request('status') == 'received' ? 'selected' : '' }}>Received</option>
                            <option value="washing" {{ request('status') == 'washing' ? 'selected' : '' }}>Washing</option>
                            <option value="drying" {{ request('status') == 'drying' ? 'selected' : '' }}>Drying</option>
                            <option value="folding" {{ request('status') == 'folding' ? 'selected' : '' }}>Folding</option>
                            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
                            <option value="collected" {{ request('status') == 'collected' ? 'selected' : '' }}>Collected</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('laundry.orders') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>QC</th>
                                <th>Payment</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('laundry.show', $order->id) }}">
                                        <strong>{{ $order->order_number }}</strong>
                                    </a>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_phone ?? '-' }}</td>
                                <td>{{ $order->total_services }} pcs</td>
                                <td>MYR {{ number_format($order->total, 2) }}</td>
                                <td>{!! $order->getStatusBadge() !!}</td>
                                <td>
                                    @if($order->qc_passed)
                                        <span class="badge bg-success">✓</span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($order->payment_status == 'paid')
                                        <span class="badge bg-success">Paid</span>
                                    @elseif($order->payment_status == 'partial')
                                        <span class="badge bg-warning">Partial</span>
                                    @else
                                        <span class="badge bg-danger">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <div class="edit-delete-action">
                                        <a class="me-2 p-2" href="{{ route('laundry.show', $order->id) }}" title="View">
                                            <i data-feather="eye" class="action-eye"></i>
                                        </a>
                                        @if($order->status !== 'collected')
                                        <a class="me-2 p-2" href="javascript:void(0);" onclick="updateOrderStatus({{ $order->id }}, '{{ $order->getNextStatus() }}')" title="Next Status">
                                            <i data-feather="arrow-right" class="text-primary"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No orders found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function updateOrderStatus(orderId, newStatus) {
        if (!newStatus) {
            Swal.fire('Info', 'Order is already at final status', 'info');
            return;
        }
        
        Swal.fire({
            title: 'Update Status',
            text: 'Move order to ' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/laundry/orders/' + orderId + '/status',
                    type: 'PUT',
                    data: { status: newStatus, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
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
        });
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush

