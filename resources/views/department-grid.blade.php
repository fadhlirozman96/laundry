<?php $page = 'department-grid'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Department
                @endslot
                @slot('li_1')
                    Manage your departments
                @endslot
                @slot('li_2')
                    Add New Department
                @endslot
            @endcomponent

            <div class="card">
                <div class="card-body pb-0">
                    <div class="table-top table-top-new">
                        <div class="search-set mb-0">
                            <div class="total-employees">
                                <h6><i data-feather="users" class="feather-user"></i>Total Departments <span>{{ $departments->count() }}</span></h6>
                            </div>
                            <div class="search-input">
                                <input type="text" class="form-control" id="search-department" placeholder="Search...">
                            </div>
                        </div>
                        <div class="search-path d-flex align-items-center search-path-new">
                            <a href="{{ url('department-list') }}" class="btn-list"><i data-feather="list" class="feather-user"></i></a>
                            <a href="{{ url('department-grid') }}" class="btn-grid active"><i data-feather="grid" class="feather-user"></i></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="employee-grid-widget">
                <div class="row" id="departments-container">
                    @forelse($departments as $department)
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 department-card">
                        <div class="employee-grid-profile">
                            <div class="profile-head">
                                <div class="dep-name">
                                    <h5 class="{{ $department->is_active ? 'active' : 'inactive' }}">{{ $department->name }}</h5>
                                </div>
                                <div class="profile-head-action">
                                    <div class="dropdown profile-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i data-feather="more-vertical" class="feather-user"></i>
                                        </a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item edit-department" data-id="{{ $department->id }}">
                                                    <i data-feather="edit" class="info-img"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item delete-department mb-0" data-id="{{ $department->id }}">
                                                    <i data-feather="trash-2" class="info-img"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-info department-profile-info">
                                <div class="profile-pic">
                                    @if($department->head && $department->head->profile_photo)
                                        <img src="{{ asset('storage/' . $department->head->profile_photo) }}" alt="">
                                    @else
                                        <img src="{{ URL::asset('/build/img/users/user-01.jpg') }}" alt="">
                                    @endif
                                </div>
                                <h4>{{ $department->head ? $department->head->name : 'No Head Assigned' }}</h4>
                            </div>
                            <ul class="team-members">
                                <li>
                                    Total Members: {{ str_pad($department->employees->count(), 2, '0', STR_PAD_LEFT) }}
                                </li>
                                <li>
                                    <ul>
                                        @foreach($department->employees->take(4) as $index => $employee)
                                        <li>
                                            <a href="javascript:void(0);">
                                                @if($employee->profile_photo)
                                                    <img src="{{ asset('storage/' . $employee->profile_photo) }}" alt="">
                                                @else
                                                    <img src="{{ URL::asset('/build/img/users/user-0' . (($index % 9) + 1) . '.jpg') }}" alt="">
                                                @endif
                                                @if($index == 3 && $department->employees->count() > 4)
                                                    <span>+{{ $department->employees->count() - 4 }}</span>
                                                @endif
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info">No departments found. Click "Add New Department" to create one.</div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Department Modal -->
    <div class="modal fade" id="add-department" tabindex="-1" aria-labelledby="addDepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDepartmentLabel">Add New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="add-department-form">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Head</label>
                            <select class="form-control select" name="head_id">
                                <option value="">Select Head</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Department Modal -->
    <div class="modal fade" id="edit-department" tabindex="-1" aria-labelledby="editDepartmentLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDepartmentLabel">Edit Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit-department-form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="department_id" id="edit-department-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Department Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="edit-department-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="edit-department-description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department Head</label>
                            <select class="form-control select" name="head_id" id="edit-department-head">
                                <option value="">Select Head</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-control select" name="is_active" id="edit-department-status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Department</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    // Search functionality
    $('#search-department').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.department-card').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Add Department
    $('#add-department-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("departments.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    // Edit Department
    $(document).on('click', '.edit-department', function() {
        var id = $(this).data('id');
        $.get('/departments/' + id, function(response) {
            if (response.success) {
                var dept = response.department;
                $('#edit-department-id').val(dept.id);
                $('#edit-department-name').val(dept.name);
                $('#edit-department-description').val(dept.description);
                $('#edit-department-head').val(dept.head_id);
                $('#edit-department-status').val(dept.is_active ? '1' : '0');
                $('#edit-department').modal('show');
            }
        });
    });

    $('#edit-department-form').on('submit', function(e) {
        e.preventDefault();
        var id = $('#edit-department-id').val();
        $.ajax({
            url: '/departments/' + id,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
            }
        });
    });

    // Delete Department
    $(document).on('click', '.delete-department', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/departments/' + id,
                    method: 'DELETE',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Deleted!', response.message, 'success').then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'An error occurred', 'error');
                    }
                });
            }
        });
    });

    // Trigger add modal from breadcrumb button
    $('[data-bs-target="#add-department"]').on('click', function() {
        $('#add-department').modal('show');
    });
});
</script>
@endsection
