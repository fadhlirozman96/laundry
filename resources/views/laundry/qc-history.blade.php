<?php $page = 'laundry-qc'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>QC History</h4>
                <h6>View all quality check records</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.qc.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back to QC
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laundry.qc.history') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status">
                            <option value="">All Results</option>
                            <option value="passed" {{ request('status') == 'passed' ? 'selected' : '' }}>Passed</option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('laundry.qc.history') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Inspector</th>
                                <th>Cleanliness</th>
                                <th>Odour</th>
                                <th>Quantity</th>
                                <th>Folding</th>
                                <th>Overall</th>
                                <th>Result</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($qcRecords as $qc)
                            <tr>
                                <td>
                                    <a href="{{ route('laundry.show', $qc->laundry_order_id) }}">
                                        {{ $qc->laundryOrder->order_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $qc->laundryOrder->customer_name ?? 'N/A' }}</td>
                                <td>{{ $qc->user->name ?? 'N/A' }}</td>
                                <td>
                                    <div class="progress" style="width: 60px; height: 20px;">
                                        <div class="progress-bar bg-{{ $qc->cleanliness_rating >= 3 ? 'success' : 'danger' }}" 
                                             style="width: {{ ($qc->cleanliness_rating ?? 0) * 20 }}%">
                                            {{ $qc->cleanliness_rating ?? 0 }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="progress" style="width: 60px; height: 20px;">
                                        <div class="progress-bar bg-{{ $qc->odour_rating >= 3 ? 'success' : 'danger' }}" 
                                             style="width: {{ ($qc->odour_rating ?? 0) * 20 }}%">
                                            {{ $qc->odour_rating ?? 0 }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($qc->quantity_match)
                                        <span class="badge bg-success">✓ Match</span>
                                    @else
                                        <span class="badge bg-danger">{{ $qc->items_counted }}/{{ $qc->items_received }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="progress" style="width: 60px; height: 20px;">
                                        <div class="progress-bar bg-{{ $qc->folding_rating >= 3 ? 'success' : 'danger' }}" 
                                             style="width: {{ ($qc->folding_rating ?? 0) * 20 }}%">
                                            {{ $qc->folding_rating ?? 0 }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $qc->getOverallRating() }}/5</strong>
                                </td>
                                <td>
                                    @if($qc->passed)
                                        <span class="badge bg-success">PASSED</span>
                                    @else
                                        <span class="badge bg-danger">FAILED</span>
                                    @endif
                                </td>
                                <td>{{ $qc->created_at->format('d M Y H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No QC records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $qcRecords->links() }}
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

