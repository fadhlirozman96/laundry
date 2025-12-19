<?php $page = 'users'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header justify-content-between">
                <div class="page-title">
                    <h4>User List</h4>
                    <h6>Manage Your Users</h6>
                </div>
                <div class="page-btn">
                    <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-user-modal">
                        <i data-feather="plus-circle" class="me-2"></i>Add New User
                    </a>
                </div>
            </div>

            <!-- /product list -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                            </div>
                        </div>
                        <div class="search-path">
                            <div class="d-flex align-items-center">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>Role</label>
                                        <select class="select" id="filter_role">
                                            <option value="">All Roles</option>
                                            @foreach($roles ?? [] as $role)
                                                <option value="{{ ucfirst(str_replace('_', ' ', $role->name)) }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <label>&nbsp;</label>
                                        <a class="btn btn-filters w-100" onclick="$('#users-table').DataTable().search($('#filter_role').val()).draw();"> 
                                            <i data-feather="search" class="feather-search"></i> Search 
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="table-responsive">
                        <table class="table datanew" id="users-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>User Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users ?? [] as $user)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" value="{{ $user->id }}">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="userimgname">
                                            <a href="javascript:void(0);" class="userslist-img bg-img">
                                                <img src="{{ URL::asset('/build/img/users/user-01.jpg') }}" alt="user">
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">{{ $user->name }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @php $role = $user->roles->first(); @endphp
                                        {{ $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'N/A' }}
                                    </td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td><span class="badge badge-linesuccess">Active</span></td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2 mb-0" href="javascript:void(0);" onclick="viewUser({{ $user->id }})">
                                                <i data-feather="eye" class="action-eye"></i>
                                            </a>
                                            <a class="me-2 p-2 mb-0" href="javascript:void(0);" onclick="editUser({{ $user->id }})">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            @if($user->id != auth()->id() && !$user->isSuperAdmin())
                                            <a class="me-2 confirm-text p-2 mb-0" href="javascript:void(0);" onclick="deleteUser({{ $user->id }})">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /product list -->
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="add-user-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-user-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-control" name="role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles ?? [] as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(isset($stores) && count($stores) > 0)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Assign to Stores</label>
                                    <select class="form-control select2" name="store_ids[]" multiple>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="edit-user-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-user-form">
                    @csrf
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="edit_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="edit_email" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" class="form-control" name="phone" id="edit_phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-control" name="role_id" id="edit_role_id" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles ?? [] as $role)
                                            <option value="{{ $role->id }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if(isset($stores) && count($stores) > 0)
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Assign to Stores</label>
                                    <select class="form-control" name="store_ids[]" id="edit_store_ids" multiple>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="view-user-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <tr><th>Name:</th><td id="view_name"></td></tr>
                        <tr><th>Email:</th><td id="view_email"></td></tr>
                        <tr><th>Phone:</th><td id="view_phone"></td></tr>
                        <tr><th>Role:</th><td id="view_role"></td></tr>
                        <tr><th>Stores:</th><td id="view_stores"></td></tr>
                        <tr><th>Created:</th><td id="view_created"></td></tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function viewUser(id) {
        $.get('/users/' + id, function(user) {
            $('#view_name').text(user.name);
            $('#view_email').text(user.email);
            $('#view_phone').text(user.phone || 'N/A');
            var role = user.roles && user.roles.length > 0 ? user.roles[0].name.replace('_', ' ') : 'N/A';
            $('#view_role').text(role.charAt(0).toUpperCase() + role.slice(1));
            var stores = user.stores ? user.stores.map(s => s.name).join(', ') : 'N/A';
            $('#view_stores').text(stores || 'N/A');
            $('#view_created').text(user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A');
            $('#view-user-modal').modal('show');
        });
    }

    function editUser(id) {
        $.get('/users/' + id, function(user) {
            $('#edit_user_id').val(user.id);
            $('#edit_name').val(user.name);
            $('#edit_email').val(user.email);
            $('#edit_phone').val(user.phone);
            if (user.roles && user.roles.length > 0) {
                $('#edit_role_id').val(user.roles[0].id);
            }
            if (user.stores) {
                var storeIds = user.stores.map(s => s.id);
                $('#edit_store_ids').val(storeIds);
            }
            $('#edit-user-modal').modal('show');
        });
    }

    function deleteUser(id) {
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
                    url: '/users/' + id,
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
        // Add user form
        $('#add-user-form').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/users',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#add-user-modal').modal('hide');
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

        // Edit user form
        $('#edit-user-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#edit_user_id').val();
            $.ajax({
                url: '/users/' + id,
                type: 'PUT',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#edit-user-modal').modal('hide');
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
