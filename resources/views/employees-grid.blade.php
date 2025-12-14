<?php $page = 'employees-grid'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Employees
                @endslot
                @slot('li_1')
                    Manage your employees
                @endslot
                @slot('li_2')
                    Add New Employee
                @endslot
            @endcomponent

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- /product list -->
            <div class="card">
                <div class="card-body pb-0">
                    <div class="table-top table-top-two table-top-new">
                        <div class="search-set mb-0">
                            <div class="total-employees">
                                <h6><i data-feather="users" class="feather-user"></i>Total Employees <span>{{ $employees->count() }}</span></h6>
                            </div>
                            <div class="search-input">
                                <a href="" class="btn btn-searchset"><i data-feather="search"
                                        class="feather-search"></i></a>
                                <input type="search" class="form-control" id="employee-search" placeholder="Search employees...">
                            </div>
                        </div>
                        <div class="search-path d-flex align-items-center search-path-new">
                            <div class="d-flex">
                                <a class="btn btn-filter" id="filter_search">
                                    <i data-feather="filter" class="filter-icon"></i>
                                    <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                                </a>
                                <a href="{{ url('employees-list') }}" class="btn-list"><i data-feather="list"
                                        class="feather-user"></i></a>
                                <a href="{{ url('employees-grid') }}" class="btn-grid active"><i data-feather="grid"
                                        class="feather-user"></i></a>
                            </div>
                            <div class="form-sort">
                                <i data-feather="sliders" class="info-img"></i>
                                <select class="select" id="sort-employees">
                                    <option value="">Sort by Date</option>
                                    <option value="newest">Newest</option>
                                    <option value="oldest">Oldest</option>
                                </select>
                            </div>
                            <div class="search-path ms-auto">
                                <a class="btn btn-filter" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-employee">
                                    <img src="{{ URL::asset('/build/img/icons/plus.svg') }}" alt="img" class="me-2">
                                    <span>Add Employee</span>
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
                                        <i data-feather="home" class="info-img"></i>
                                        <select class="select" id="filter-store">
                                            <option value="">All Stores</option>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="user" class="info-img"></i>
                                        <select class="select" id="filter-role">
                                            <option value="">All Roles</option>
                                            <option value="admin">Admin</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">All Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12 ms-auto">
                                    <div class="input-blocks">
                                        <a class="btn btn-filters ms-auto"> <i data-feather="search"
                                                class="feather-search"></i> Search </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Filter -->
                </div>
            </div>
            <!-- /product list -->

            <div class="employee-grid-widget">
                <div class="row" id="employee-grid-container">
                    @forelse($employees as $employee)
                    @php
                        $assignedStore = $employee->stores->first();
                        $empId = 'EMP' . str_pad($employee->id, 3, '0', STR_PAD_LEFT);
                    @endphp
                    <div class="col-xxl-3 col-xl-4 col-lg-6 col-md-6 employee-card" 
                         data-store-id="{{ $assignedStore ? $assignedStore->id : '' }}"
                         data-role="{{ $employee->role }}"
                         data-name="{{ strtolower($employee->name) }}"
                         data-email="{{ strtolower($employee->email) }}">
                        <div class="employee-grid-profile">
                            <div class="profile-head">
                                <label class="checkboxs">
                                    <input type="checkbox" value="{{ $employee->id }}">
                                    <span class="checkmarks"></span>
                                </label>
                                <div class="profile-head-action">
                                    <span class="badge badge-linesuccess text-center w-auto me-1">Active</span>
                                    <div class="dropdown profile-action">
                                        <a href="#" class="action-icon dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-expanded="false"><i data-feather="more-vertical"
                                                class="feather-user"></i></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item" 
                                                   data-bs-toggle="modal" data-bs-target="#edit-employee"
                                                   onclick="editEmployee({{ $employee->id }}, {{ json_encode($employee->name) }}, {{ json_encode($employee->email) }}, '{{ $employee->role }}', {{ $assignedStore ? $assignedStore->id : 'null' }})">
                                                    <i data-feather="edit" class="info-img"></i>Edit
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);" class="dropdown-item confirm-text mb-0"
                                                   onclick="deleteEmployee({{ $employee->id }})">
                                                    <i data-feather="trash-2" class="info-img"></i>Delete
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-info">
                                <div class="profile-pic active-profile">
                                    <img src="{{ URL::asset('/build/img/users/user-' . str_pad(($employee->id % 8) + 1, 2, '0', STR_PAD_LEFT) . '.jpg') }}" alt="{{ $employee->name }}">
                                </div>
                                <h5>EMP ID : {{ $empId }}</h5>
                                <h4>{{ $employee->name }}</h4>
                                <span>{{ ucfirst($employee->role) }}</span>
                            </div>
                            <ul class="department">
                                <li>
                                    Email
                                    <span>{{ $employee->email }}</span>
                                </li>
                                <li>
                                    Store
                                    <span>{{ $assignedStore ? $assignedStore->name : 'Not Assigned' }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <h5>No employees found</h5>
                            <p>Click "Add Employee" to create your first staff member.</p>
                        </div>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div class="modal fade" id="add-employee">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Add Employee</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form action="{{ route('employees.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control" required>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="form-control pass-input" required>
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" class="select" required>
                                        <option value="">Choose Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                    @error('role')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Store <span class="text-danger">*</span></label>
                                    <select name="store_id" class="select" required>
                                        <option value="">Choose Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Add Employee Modal -->

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="edit-employee">
        <div class="modal-dialog modal-dialog-centered custom-modal-two">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content">
                        <div class="modal-header border-0 custom-modal-header">
                            <div class="page-title">
                                <h4>Edit Employee</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body custom-modal-body">
                            <form id="edit-employee-form" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="edit-employee-id" name="employee_id">
                                <div class="mb-3">
                                    <label class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" id="edit-employee-name" class="form-control" required>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="edit-employee-email" class="form-control" required>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="pass-group">
                                        <input type="password" name="password" class="form-control pass-input" placeholder="Leave blank to keep current password">
                                        <span class="fas toggle-password fa-eye-slash"></span>
                                    </div>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role <span class="text-danger">*</span></label>
                                    <select name="role" id="edit-employee-role" class="select" required>
                                        <option value="">Choose Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                    @error('role')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Store <span class="text-danger">*</span></label>
                                    <select name="store_id" id="edit-employee-store" class="select" required>
                                        <option value="">Choose Store</option>
                                        @foreach($stores as $store)
                                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('store_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" id="edit-employee-phone" class="form-control">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="modal-footer-btn">
                                    <button type="button" class="btn btn-cancel me-2"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-submit">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Edit Employee Modal -->
@endsection

@push('scripts')
<script>
function editEmployee(id, name, email, role, storeId) {
    document.getElementById('edit-employee-id').value = id;
    document.getElementById('edit-employee-name').value = name;
    document.getElementById('edit-employee-email').value = email;
    document.getElementById('edit-employee-role').value = role;
    document.getElementById('edit-employee-store').value = storeId || '';
    document.getElementById('edit-employee-form').action = '/employees/' + id;
}

function deleteEmployee(id) {
    if (confirm('Are you sure you want to delete this employee?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/employees/' + id;
        
        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);
        
        var method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Simple search and filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('employee-search');
    const filterStore = document.getElementById('filter-store');
    const filterRole = document.getElementById('filter-role');
    const employeeCards = document.querySelectorAll('.employee-card');
    
    function filterEmployees() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedStore = filterStore.value;
        const selectedRole = filterRole.value;
        
        employeeCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const email = card.getAttribute('data-email');
            const storeId = card.getAttribute('data-store-id');
            const role = card.getAttribute('data-role');
            
            const matchesSearch = !searchTerm || name.includes(searchTerm) || email.includes(searchTerm);
            const matchesStore = !selectedStore || storeId === selectedStore;
            const matchesRole = !selectedRole || role === selectedRole;
            
            if (matchesSearch && matchesStore && matchesRole) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    if (searchInput) searchInput.addEventListener('input', filterEmployees);
    if (filterStore) filterStore.addEventListener('change', filterEmployees);
    if (filterRole) filterRole.addEventListener('change', filterEmployees);
});

// Reinitialize feather icons
if (typeof feather !== 'undefined') {
    feather.replace();
}
</script>
@endpush
