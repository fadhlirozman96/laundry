<?php $page = 'attendance-employee'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    My Attendance
                @endslot
                @slot('li_1')
                    View your attendance records
                @endslot
                @slot('li_2')
                    Clock In/Out
                @endslot
            @endcomponent

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>Today's Attendance</h5>
                            <div class="mt-3">
                                <button class="btn btn-success btn-lg me-2" id="clock-in-btn">
                                    <i data-feather="log-in" class="me-2"></i> Clock In
                                </button>
                                <button class="btn btn-danger btn-lg" id="clock-out-btn">
                                    <i data-feather="log-out" class="me-2"></i> Clock Out
                                </button>
                            </div>
                            <p class="mt-3 text-muted" id="current-time"></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h5>Attendance Summary</h5>
                            <div class="row mt-3">
                                <div class="col-4 text-center">
                                    <h3 class="text-success">{{ $attendances->where('status', 'present')->count() }}</h3>
                                    <p>Present</p>
                                </div>
                                <div class="col-4 text-center">
                                    <h3 class="text-warning">{{ $attendances->where('status', 'late')->count() }}</h3>
                                    <p>Late</p>
                                </div>
                                <div class="col-4 text-center">
                                    <h3 class="text-danger">{{ $attendances->where('status', 'absent')->count() }}</h3>
                                    <p>Absent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card table-list-card">
                <div class="card-body">
                    <h5 class="mb-3">Attendance History</h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->date->format('d M Y') }}</td>
                                    <td>{{ $attendance->clock_in ? $attendance->clock_in->format('h:i A') : '-' }}</td>
                                    <td>{{ $attendance->clock_out ? $attendance->clock_out->format('h:i A') : '-' }}</td>
                                    <td>{{ $attendance->total_hours_display }}</td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'present' => 'success',
                                                'approved' => 'success',
                                                'absent' => 'danger',
                                                'late' => 'warning',
                                                'half_day' => 'info',
                                                'on_leave' => 'secondary',
                                                'pending' => 'warning',
                                            ];
                                            $color = $statusColors[$attendance->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge badge-line{{ $color }}">{{ ucfirst(str_replace('_', ' ', $attendance->status)) }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center mt-3">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Update current time
    function updateTime() {
        var now = new Date();
        $('#current-time').text('Current Time: ' + now.toLocaleTimeString());
    }
    updateTime();
    setInterval(updateTime, 1000);

    // Clock In
    $('#clock-in-btn').on('click', function() {
        $.ajax({
            url: '{{ route("attendances.clock-in") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    // Clock Out
    $('#clock-out-btn').on('click', function() {
        $.ajax({
            url: '{{ route("attendances.clock-out") }}',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => location.reload());
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });
});
</script>
@endsection
