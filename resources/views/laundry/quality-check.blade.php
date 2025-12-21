<?php $page = 'laundry-qc'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Quality Control</h4>
                <h6>Inspect orders before marking as ready</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.qc.history') }}" class="btn btn-outline-primary">
                    <i data-feather="clock" class="me-2"></i>QC History
                </a>
            </div>
        </div>

        <!-- Pending QC Orders -->
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="card-title mb-0">
                    <i data-feather="alert-circle" class="me-2"></i>
                    Orders Pending QC ({{ count($pendingOrders) }})
                </h5>
            </div>
            <div class="card-body">
                @if(count($pendingOrders) > 0)
                <div class="row">
                    @foreach($pendingOrders as $order)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h5 class="card-title mb-0">{{ $order->order_number }}</h5>
                                        <small class="text-muted">{{ $order->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    {!! $order->getStatusBadge() !!}
                                </div>
                                
                                <p class="mb-1"><strong>Customer:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Items:</strong> {{ $order->total_garments }} pcs</p>
                                <p class="mb-3"><strong>Total:</strong> MYR {{ number_format($order->total, 2) }}</p>
                                
                                <div class="d-grid">
                                    <a href="{{ route('laundry.qc.create', $order->id) }}" class="btn btn-warning">
                                        <i data-feather="check-square" class="me-2"></i>Start QC
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-5">
                    <i data-feather="check-circle" style="width: 64px; height: 64px;" class="text-success mb-3"></i>
                    <h5 class="text-muted">No orders pending QC</h5>
                    <p class="text-muted">All orders have been inspected. Great work!</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent QC -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Quality Checks</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Inspector</th>
                                <th>Cleanliness</th>
                                <th>Odour</th>
                                <th>Quantity</th>
                                <th>Folding</th>
                                <th>Result</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentQC as $qc)
                            <tr>
                                <td>
                                    <a href="{{ route('laundry.show', $qc->laundry_order_id) }}">
                                        {{ $qc->laundryOrder->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $qc->user->name ?? 'N/A' }}</td>
                                <td>
                                    @if($qc->cleanliness_check)
                                        <span class="badge bg-{{ $qc->cleanliness_rating >= 3 ? 'success' : 'danger' }}">
                                            {{ $qc->cleanliness_rating }}/5
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($qc->odour_check)
                                        <span class="badge bg-{{ $qc->odour_rating >= 3 ? 'success' : 'danger' }}">
                                            {{ $qc->odour_rating }}/5
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($qc->quantity_check)
                                        <span class="badge bg-{{ $qc->quantity_match ? 'success' : 'danger' }}">
                                            {{ $qc->items_counted }}/{{ $qc->items_received }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($qc->folding_check)
                                        <span class="badge bg-{{ $qc->folding_rating >= 3 ? 'success' : 'danger' }}">
                                            {{ $qc->folding_rating }}/5
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($qc->passed)
                                        <span class="badge bg-success">PASSED</span>
                                    @else
                                        <span class="badge bg-danger">FAILED</span>
                                    @endif
                                </td>
                                <td>{{ $qc->created_at->format('d M H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No QC records yet</td>
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


