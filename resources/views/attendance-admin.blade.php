<?php $page = 'attendance-admin'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Attendance
                @endslot
                @slot('li_1')
                    Manage Employee Attendance
                @endslot
                @slot('li_2')
                    Add Attendance
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <table class="table" id="attendance-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Emp ID</th>
                                    <th>Date</th>
                                    <th>Shift</th>
                                    <th>Clock In</th>
                                    <th>Clock Out</th>
                                    <th>Total Hours</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attendance Modal -->
    <div class="modal fade" id="add-attendance" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Attendance</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-attendance-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Employee <span class="text-danger">*</span></label>
                            <select class="form-control select" name="user_id" required>
                                <option value="">Select Employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="date" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Shift</label>
                            <select class="form-control select" name="shift_id">
                                <option value="">Select Shift</option>
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}">{{ $shift->name }} ({{ $shift->time_range }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Clock In</label>
                                    <input type="time" class="form-control" name="clock_in">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Clock Out</label>
                                    <input type="time" class="form-control" name="clock_out">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half_day">Half Day</option>
                                <option value="on_leave">On Leave</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Attendance Modal -->
    <div class="modal fade" id="edit-attendance-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Attendance Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-attendance-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="attendance_id" id="edit-attendance-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="status" id="edit-attendance-status" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="late">Late</option>
                                <option value="half_day">Half Day</option>
                                <option value="on_leave">On Leave</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea class="form-control" name="notes" id="edit-attendance-notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var table = $('#attendance-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("attendances.data") }}',
        columns: [
            { data: 'employee_name', name: 'user.name' },
            { data: 'employee_id', name: 'user.employee_id' },
            { data: 'date', name: 'date' },
            { data: 'shift_name', name: 'shift.name' },
            { data: 'clock_in_time', name: 'clock_in' },
            { data: 'clock_out_time', name: 'clock_out' },
            { data: 'total', name: 'total_hours' },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[2, 'desc']],
        drawCallback: function() {
            feather.replace();
        }
    });

    $('#add-attendance-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("attendances.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-attendance').modal('hide');
                    Swal.fire('Success', response.message, 'success');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    $(document).on('click', '.edit-attendance', function() {
        var id = $(this).data('id');
        $.get('/attendances/' + id, function(response) {
            if (response.success) {
                var a = response.attendance;
                $('#edit-attendance-id').val(a.id);
                $('#edit-attendance-status').val(a.status);
                $('#edit-attendance-notes').val(a.notes);
                $('#edit-attendance-modal').modal('show');
            }
        });
    });

    $('#edit-attendance-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-attendance-id').val();
        $.ajax({
            url: '/attendances/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#edit-attendance-modal').modal('hide');
                    Swal.fire('Success', response.message, 'success');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    $(document).on('click', '.delete-attendance', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/attendances/' + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });
});
</script>
@endsection
