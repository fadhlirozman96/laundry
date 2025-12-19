<?php $page = 'leave-types'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Leave Types
                @endslot
                @slot('li_1')
                    Manage your Leave Types
                @endslot
                @slot('li_2')
                    Add Leave Type
                @endslot
            @endcomponent

            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="leave-type-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Leave Quota</th>
                                    <th>Paid/Unpaid</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaveTypes as $type)
                                <tr>
                                    <td>{{ $type->name }}</td>
                                    <td>{{ str_pad($type->days_allowed, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        @if($type->is_paid)
                                            <span class="badge badge-linesuccess">Paid</span>
                                        @else
                                            <span class="badge badge-linewarning">Unpaid</span>
                                        @endif
                                    </td>
                                    <td>{{ $type->created_at->format('d M Y') }}</td>
                                    <td>
                                        @if($type->is_active)
                                            <span class="badge badge-linesuccess">Active</span>
                                        @else
                                            <span class="badge badge-linedanger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 edit-leave-type" href="javascript:void(0);" data-id="{{ $type->id }}">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-text p-2 delete-leave-type" href="javascript:void(0);" data-id="{{ $type->id }}">
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

    <!-- Add Leave Type Modal -->
    <div class="modal fade" id="add-leave-type" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="add-leave-type-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Leave Type Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Days Allowed <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="days_allowed" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_paid" value="1" id="add-is-paid" checked>
                                <label class="form-check-label" for="add-is-paid">Paid Leave</label>
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

    <!-- Edit Leave Type Modal -->
    <div class="modal fade" id="edit-leave-type-modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Leave Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="edit-leave-type-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="leave_type_id" id="edit-leave-type-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Leave Type Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-leave-type-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Days Allowed <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="days_allowed" id="edit-leave-type-days" min="0" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit-leave-type-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_paid" value="1" id="edit-is-paid">
                                <label class="form-check-label" for="edit-is-paid">Paid Leave</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control" name="is_active" id="edit-leave-type-status">
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
    $('#leave-type-table').DataTable({ "bFilter": true, "sDom": 'fBtlpi' });

    $('#add-leave-type-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("leave-types.store") }}',
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

    $(document).on('click', '.edit-leave-type', function() {
        var id = $(this).data('id');
        $.get('/leave-types/' + id, function(response) {
            if (response.success) {
                var t = response.leaveType;
                $('#edit-leave-type-id').val(t.id);
                $('#edit-leave-type-name').val(t.name);
                $('#edit-leave-type-days').val(t.days_allowed);
                $('#edit-leave-type-description').val(t.description);
                $('#edit-is-paid').prop('checked', t.is_paid);
                $('#edit-leave-type-status').val(t.is_active ? '1' : '0');
                $('#edit-leave-type-modal').modal('show');
            }
        });
    });

    $('#edit-leave-type-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-leave-type-id').val();
        $.ajax({
            url: '/leave-types/' + id,
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

    $(document).on('click', '.delete-leave-type', function() {
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
                    url: '/leave-types/' + id,
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
