<?php $page = 'shift'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Shift
                @endslot
                @slot('li_1')
                    Manage your employees shift
                @endslot
                @slot('li_2')
                    Add New Shift
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="shift-table">
                            <thead>
                                <tr>
                                    <th>Shift Name</th>
                                    <th>Time</th>
                                    <th>Break (min)</th>
                                    <th>Week off</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shifts as $shift)
                                <tr>
                                    <td>{{ $shift->name }}</td>
                                    <td>{{ $shift->time_range }}</td>
                                    <td>{{ $shift->break_duration }}</td>
                                    <td>{{ $shift->week_off_display }}</td>
                                    <td>{{ $shift->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($shift->is_active)
                                            <span class="badge badge-linesuccess">Active</span>
                                        @else
                                            <span class="badge badge-linedanger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-shift" href="javascript:void(0);" data-id="{{ $shift->id }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-text p-2 delete-shift" href="javascript:void(0);" data-id="{{ $shift->id }}">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Shift Modal -->
    <div class="modal fade" id="add-shift" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-shift-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Shift Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="start_time" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="end_time" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Break Duration (minutes)</label>
                            <input type="number" class="form-control" name="break_duration" value="60">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Week Off</label>
                            <div class="row">
                                @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="week_off[]" value="{{ $day }}" id="add-{{ $day }}">
                                        <label class="form-check-label" for="add-{{ $day }}">{{ $day }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Shift Modal -->
    <div class="modal fade" id="edit-shift-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-shift-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="shift_id" id="edit-shift-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Shift Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-shift-name" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="start_time" id="edit-shift-start" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">End Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" name="end_time" id="edit-shift-end" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Break Duration (minutes)</label>
                            <input type="number" class="form-control" name="break_duration" id="edit-shift-break">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Week Off</label>
                            <div class="row" id="edit-week-off-container">
                                @foreach(['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                                <div class="col-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="week_off[]" value="{{ $day }}" id="edit-{{ $day }}">
                                        <label class="form-check-label" for="edit-{{ $day }}">{{ $day }}</label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active" id="edit-shift-status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
    $('#shift-table').DataTable({ "bFilter": true, "sDom": 'fBtlpi' });

    $('#add-shift-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("shifts.store") }}',
            method: 'POST',
            data: $(this).serialize(),
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

    $(document).on('click', '.edit-shift', function() {
        var id = $(this).data('id');
        $.get('/shifts/' + id, function(response) {
            if (response.success) {
                var s = response.shift;
                $('#edit-shift-id').val(s.id);
                $('#edit-shift-name').val(s.name);
                $('#edit-shift-start').val(s.start_time ? s.start_time.substring(0,5) : '');
                $('#edit-shift-end').val(s.end_time ? s.end_time.substring(0,5) : '');
                $('#edit-shift-break').val(s.break_duration);
                $('#edit-shift-status').val(s.is_active ? '1' : '0');
                
                // Reset and set week off checkboxes
                $('#edit-week-off-container input[type="checkbox"]').prop('checked', false);
                if (s.week_off) {
                    s.week_off.forEach(function(day) {
                        $('#edit-' + day).prop('checked', true);
                    });
                }
                $('#edit-shift-modal').modal('show');
            }
        });
    });

    $('#edit-shift-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-shift-id').val();
        $.ajax({
            url: '/shifts/' + id,
            method: 'PUT',
            data: $(this).serialize(),
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

    $(document).on('click', '.delete-shift', function() {
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
                    url: '/shifts/' + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => location.reload());
                    }
                });
            }
        });
    });
});
</script>
@endsection
