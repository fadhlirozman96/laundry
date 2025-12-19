<?php $page = 'laundry-machines'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Machine Usage History</h4>
                <h6>Track machine usage and cycle times</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.machines') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left" class="me-2"></i>Back to Machines
                </a>
            </div>
        </div>

        <!-- Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laundry.machine-usage') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Machine</label>
                        <select class="form-control" name="machine_id">
                            <option value="">All Machines</option>
                            @foreach($machines as $machine)
                                <option value="{{ $machine->id }}" {{ request('machine_id') == $machine->id ? 'selected' : '' }}>
                                    {{ $machine->name }} ({{ ucfirst($machine->type) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                        <a href="{{ route('laundry.machine-usage') }}" class="btn btn-secondary">Reset</a>
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
                                <th>Machine</th>
                                <th>Type</th>
                                <th>Order</th>
                                <th>Staff</th>
                                <th>Load (kg)</th>
                                <th>Items</th>
                                <th>Started</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Issues</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr class="{{ $log->overload_warning ? 'table-warning' : '' }}">
                                <td><strong>{{ $log->machine->name ?? 'N/A' }}</strong></td>
                                <td>
                                    <span class="badge bg-{{ $log->machine->type == 'washer' ? 'info' : 'warning' }}">
                                        {{ ucfirst($log->machine->type ?? 'N/A') }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->laundryOrder)
                                        <a href="{{ route('laundry.show', $log->laundry_order_id) }}">
                                            {{ $log->laundryOrder->order_number }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $log->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $log->load_weight_kg ?? '-' }}
                                    @if($log->overload_warning)
                                        <span class="badge bg-danger">OVERLOAD</span>
                                    @endif
                                </td>
                                <td>{{ $log->items_count ?? '-' }}</td>
                                <td>{{ $log->started_at->format('d M H:i') }}</td>
                                <td>
                                    @if($log->duration_minutes)
                                        {{ $log->duration_minutes }} min
                                        @if($log->set_duration_minutes && $log->duration_minutes > $log->set_duration_minutes)
                                            <span class="badge bg-warning">+{{ $log->duration_minutes - $log->set_duration_minutes }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-warning">Running</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->status == 'completed')
                                        <span class="badge bg-success">{{ ucfirst($log->status) }}</span>
                                    @elseif($log->status == 'running')
                                        <span class="badge bg-warning">{{ ucfirst($log->status) }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ ucfirst($log->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->issues)
                                        <span class="text-danger" data-bs-toggle="tooltip" title="{{ $log->issues }}">
                                            <i data-feather="alert-circle"></i>
                                        </span>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">No usage records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links() }}
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
        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush

