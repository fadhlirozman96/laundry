<?php $page = 'store-list'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            @component('components.breadcrumb')
                @slot('title')
                    Store List
                @endslot
                @slot('li_1')
                    Manage your Store
                @endslot
                @slot('li_2')
                    Add Store
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
                            <a class="btn btn-filter" id="filter_search">
                                <i data-feather="filter" class="filter-icon"></i>
                                <span><img src="{{ URL::asset('/build/img/icons/closes.svg') }}" alt="img"></span>
                            </a>
                        </div>
                        <div class="form-sort">
                            <i data-feather="sliders" class="info-img"></i>
                            <select class="select">
                                <option>Sort by Date</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                        <div class="search-path ms-auto">
                            <a class="btn btn-filter" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#add-stores">
                                <img src="{{ URL::asset('/build/img/icons/plus.svg') }}" alt="img" class="me-2">
                                <span>Add Store</span>
                            </a>
                        </div>
                    </div>
                    <!-- /Filter -->
                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="zap" class="info-img"></i>
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
                                        <i data-feather="calendar" class="info-img"></i>
                                        <div class="input-groupicon">
                                            <input type="text" class="datetimepicker" placeholder="Choose Date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-6 col-12">
                                    <div class="input-blocks">
                                        <i data-feather="stop-circle" class="info-img"></i>
                                        <select class="select" id="filter-status">
                                            <option value="">All Status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
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
                    <div class="table-responsive">
                        <table class="table datanew" id="store-table">
                            <thead>
                                <tr>
                                    <th class="no-sort">
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Store name</th>
                                    <th>Owner</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th class="no-sort">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stores as $store)
                                <tr>
                                    <td>
                                        <label class="checkboxs">
                                            <input type="checkbox" value="{{ $store->id }}">
                                            <span class="checkmarks"></span>
                                        </label>
                                    </td>
                                    <td>{{ $store->name }}</td>
                                    <td>{{ $store->owner ? $store->owner->name : 'N/A' }}</td>
                                    <td>{{ $store->phone ?? 'N/A' }}</td>
                                    <td>{{ $store->email ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ $store->is_active ? 'badge-linesuccess' : 'badge-liner-danger' }}">
                                            {{ $store->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="javascript:void(0);" 
                                                data-bs-toggle="modal" data-bs-target="#edit-stores"
                                                onclick="editStore({{ $store->id }}, {{ json_encode($store->name) }}, {{ json_encode($store->phone) }}, {{ json_encode($store->email) }}, {{ json_encode($store->address) }})">
                                                <i data-feather="edit" class="feather-edit"></i>
                                            </a>
                                            <a class="confirm-text p-2" href="javascript:void(0);" 
                                                onclick="deleteStore({{ $store->id }})">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No stores found.</td>
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
@endsection

@push('scripts')
<script>
function editStore(id, name, phone, email, address) {
    document.getElementById('edit-store-id').value = id;
    document.getElementById('edit-store-name').value = name;
    document.getElementById('edit-store-phone').value = phone || '';
    document.getElementById('edit-store-email').value = email || '';
    document.getElementById('edit-store-address').value = address || '';
    document.getElementById('edit-store-form').action = '/stores/' + id;
}

function deleteStore(id) {
    if (confirm('Are you sure you want to delete this store?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/stores/' + id;
        
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

// Reinitialize feather icons after DataTables redraw
if ($('#store-table').hasClass('datanew')) {
    $('#store-table').on('draw.dt', function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
}
</script>
@endpush
