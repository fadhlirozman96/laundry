<?php $page = 'leaves-admin'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Leave Management
                @endslot
                @slot('li_1')
                    Manage Employee Leaves
                @endslot
                @slot('li_2')
                    Add Leave Request
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <table class="table" id="leaves-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Date Range</th>
                                    <th>Duration</th>
                                    <th>Reason</th>
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

    <!-- Add Leave Modal -->
    <div class="modal fade" id="add-leave" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Leave Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-leave-form">
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
                            <label class="form-label">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-control select" name="leave_type_id" required>
                                <option value="">Select Leave Type</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->days_allowed }} days)</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="start_date" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="end_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" name="reason" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    var table = $('#leaves-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("leaves.data") }}',
        columns: [
            { data: 'employee_name', name: 'user.name' },
            { data: 'leave_type_name', name: 'leaveType.name' },
            { data: 'date_range', name: 'start_date' },
            { data: 'duration', name: 'days' },
            { data: 'reason', name: 'reason', render: function(data) { return data || '-'; } },
            { data: 'status_badge', name: 'status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[2, 'desc']],
        drawCallback: function() {
            feather.replace();
        }
    });

    $('#add-leave-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("leaves.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-leave').modal('hide');
                    Swal.fire('Success', response.message, 'success');
                    table.ajax.reload();
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    $(document).on('click', '.approve-leave', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Approve Leave?',
            text: "Are you sure you want to approve this leave request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Yes, approve it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/leaves/' + id + '/approve',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Approved!', response.message, 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });

    $(document).on('click', '.reject-leave', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Reject Leave?',
            input: 'textarea',
            inputLabel: 'Rejection Reason',
            inputPlaceholder: 'Enter reason for rejection...',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Reject'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/leaves/' + id + '/reject',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', rejection_reason: result.value },
                    success: function(response) {
                        Swal.fire('Rejected!', response.message, 'success');
                        table.ajax.reload();
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete-leave', function() {
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
                    url: '/leaves/' + id,
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
