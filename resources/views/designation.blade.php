<?php $page = 'designation'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Designation
                @endslot
                @slot('li_1')
                    Manage your designations
                @endslot
                @slot('li_2')
                    Add New Designation
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body pb-0">
                    <div class="table-top table-top-new">
                        <div class="search-set mb-0">
                            <div class="total-employees">
                                <h6><i data-feather="users" class="feather-user"></i>Total Designations <span>{{ $designations->count() }}</span></h6>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table" id="designation-table">
                            <thead>
                                <tr>
                                    <th>Designation</th>
                                    <th>Department</th>
                                    <th>Total Members</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designations as $designation)
                                <tr>
                                    <td>{{ $designation->name }}</td>
                                    <td>{{ $designation->department ? $designation->department->name : '-' }}</td>
                                    <td>{{ str_pad($designation->employees->count(), 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $designation->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($designation->is_active)
                                            <span class="badge badge-linesuccess">Active</span>
                                        @else
                                            <span class="badge badge-linedanger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-designation" href="javascript:void(0);" data-id="{{ $designation->id }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-text p-2 delete-designation" href="javascript:void(0);" data-id="{{ $designation->id }}">
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

    <!-- Add Designation Modal -->
    <div class="modal fade" id="add-designation" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-designation-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-control select" name="department_id">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
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

    <!-- Edit Designation Modal -->
    <div class="modal fade" id="edit-designation-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Designation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-designation-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="designation_id" id="edit-designation-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Designation Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-designation-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department</label>
                            <select class="form-control" name="department_id" id="edit-designation-department">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit-designation-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active" id="edit-designation-status">
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
    $('#designation-table').DataTable({
        "bFilter": true,
        "sDom": 'fBtlpi',
    });

    $('#add-designation-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("designations.store") }}',
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

    $(document).on('click', '.edit-designation', function() {
        var id = $(this).data('id');
        $.get('/designations/' + id, function(response) {
            if (response.success) {
                var d = response.designation;
                $('#edit-designation-id').val(d.id);
                $('#edit-designation-name').val(d.name);
                $('#edit-designation-department').val(d.department_id);
                $('#edit-designation-description').val(d.description);
                $('#edit-designation-status').val(d.is_active ? '1' : '0');
                $('#edit-designation-modal').modal('show');
            }
        });
    });

    $('#edit-designation-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-designation-id').val();
        $.ajax({
            url: '/designations/' + id,
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

    $(document).on('click', '.delete-designation', function() {
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
                    url: '/designations/' + id,
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
