<?php $page = 'roles-permissions'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>Roles & Permissions</h4>
                    <h6>Manage system roles</h6>
                </div>
                <div class="page-btn">
                    <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-role-modal">
                        <i data-feather="plus-circle" class="me-2"></i>Add New Role
                    </a>
                </div>
            </div>

            <!-- Role List -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Role Name</th>
                                    <th>Display Name</th>
                                    <th>Description</th>
                                    <th>Users</th>
                                    <th>Created On</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles ?? [] as $role)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" value="{{ $role->id }}">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $role->name)) }}</td>
                                    <td>{{ $role->display_name ?? '-' }}</td>
                                    <td>{{ $role->description ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $role->users_count ?? $role->users()->count() }}</span>
                                    </td>
                                    <td>{{ $role->created_at ? $role->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="javascript:void(0);" onclick="editRole({{ $role->id }}, '{{ $role->name }}', '{{ $role->display_name }}', '{{ $role->description }}')">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="p-2 me-2" href="{{ url('permissions') }}?role_id={{ $role->id }}" title="Manage Permissions">
                                                <i data-feather="shield" class="shield"></i>
                                            </a>
                                            @if(!in_array($role->name, ['super_admin', 'business_owner', 'admin', 'staff']))
                                            <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteRole({{ $role->id }})">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No roles found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- System Roles Info -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">System Roles Information</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Description</th>
                                    <th>Access Level</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger">Super Admin</span></td>
                                    <td>Full system administrator with complete access to all features</td>
                                    <td>All stores, all users, all settings</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-primary">Business Owner</span></td>
                                    <td>Owner of business who can create and manage their own stores</td>
                                    <td>Own stores only, own staff only</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Admin</span></td>
                                    <td>Store administrator with management capabilities</td>
                                    <td>Assigned stores only</td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">Staff</span></td>
                                    <td>Regular staff member with limited access</td>
                                    <td>Assigned stores, limited features</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Role Modal -->
    <div class="modal fade" id="add-role-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-role-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g., manager">
                            <small class="text-muted">Use lowercase letters and underscores only</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control" name="display_name" placeholder="e.g., Store Manager">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Brief description of this role"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="edit-role-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-role-form">
                    @csrf
                    <input type="hidden" name="role_id" id="edit_role_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit_role_name" required>
                            <small class="text-muted" id="system-role-note" style="display:none;">System roles cannot be renamed</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control" name="display_name" id="edit_role_display_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit_role_description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var systemRoles = ['super_admin', 'business_owner', 'admin', 'staff'];

    function editRole(id, name, displayName, description) {
        $('#edit_role_id').val(id);
        $('#edit_role_name').val(name);
        $('#edit_role_display_name').val(displayName);
        $('#edit_role_description').val(description);
        
        if (systemRoles.includes(name)) {
            $('#edit_role_name').prop('readonly', true);
            $('#system-role-note').show();
        } else {
            $('#edit_role_name').prop('readonly', false);
            $('#system-role-note').hide();
        }
        
        $('#edit-role-modal').modal('show');
    }

    function deleteRole(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/roles/' + id,
                    type: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON?.error || 'Something went wrong', 'error');
                    }
                });
            }
        });
    }

    $(document).ready(function() {
        // Add role form
        $('#add-role-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/roles',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#add-role-modal').modal('hide');
                        Swal.fire('Success!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON?.errors;
                    if (errors) {
                        var errorMsg = Object.values(errors).flat().join('<br>');
                        Swal.fire('Validation Error', errorMsg, 'error');
                    } else {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                    }
                }
            });
        });

        // Edit role form
        $('#edit-role-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#edit_role_id').val();
            $.ajax({
                url: '/roles/' + id,
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#edit-role-modal').modal('hide');
                        Swal.fire('Success!', response.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message, 'error');
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON?.errors;
                    if (errors) {
                        var errorMsg = Object.values(errors).flat().join('<br>');
                        Swal.fire('Validation Error', errorMsg, 'error');
                    } else {
                        Swal.fire('Error!', xhr.responseJSON?.message || 'Something went wrong', 'error');
                    }
                }
            });
        });

        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
