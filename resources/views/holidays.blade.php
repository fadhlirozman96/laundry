<?php $page = 'holidays'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Holidays
                @endslot
                @slot('li_1')
                    Manage your Holidays
                @endslot
                @slot('li_2')
                    Add New Holiday
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body pb-0">
                    <div class="table-responsive">
                        <table class="table" id="holiday-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Duration</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($holidays as $holiday)
                                <tr>
                                    <td>{{ $holiday->name }}</td>
                                    <td>{{ $holiday->date->format('d M Y') }}</td>
                                    <td>{{ $holiday->duration }}</td>
                                    <td>{{ $holiday->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($holiday->is_active)
                                            <span class="badge badge-linesuccess">Active</span>
                                        @else
                                            <span class="badge badge-linedanger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-holiday" href="javascript:void(0);" data-id="{{ $holiday->id }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-text p-2 delete-holiday" href="javascript:void(0);" data-id="{{ $holiday->id }}">
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

    <!-- Add Holiday Modal -->
    <div class="modal fade" id="add-holiday" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-holiday-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Holiday Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
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

    <!-- Edit Holiday Modal -->
    <div class="modal fade" id="edit-holiday-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Holiday</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-holiday-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="holiday_id" id="edit-holiday-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Holiday Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-holiday-name" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="date" id="edit-holiday-date" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="edit-holiday-end-date">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit-holiday-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active" id="edit-holiday-status">
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
    $('#holiday-table').DataTable({ "bFilter": true, "sDom": 'fBtlpi' });

    $('#add-holiday-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("holidays.store") }}',
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

    $(document).on('click', '.edit-holiday', function() {
        var id = $(this).data('id');
        $.get('/holidays/' + id, function(response) {
            if (response.success) {
                var h = response.holiday;
                $('#edit-holiday-id').val(h.id);
                $('#edit-holiday-name').val(h.name);
                $('#edit-holiday-date').val(h.date.split('T')[0]);
                $('#edit-holiday-end-date').val(h.end_date ? h.end_date.split('T')[0] : '');
                $('#edit-holiday-description').val(h.description);
                $('#edit-holiday-status').val(h.is_active ? '1' : '0');
                $('#edit-holiday-modal').modal('show');
            }
        });
    });

    $('#edit-holiday-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-holiday-id').val();
        $.ajax({
            url: '/holidays/' + id,
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

    $(document).on('click', '.delete-holiday', function() {
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
                    url: '/holidays/' + id,
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
