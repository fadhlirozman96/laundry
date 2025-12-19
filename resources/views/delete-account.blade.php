<?php $page = 'delete-account'; ?>
@extends('layout.mainlayout')
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="add-item d-flex">
                    <div class="page-title">
                        <h4>Delete Account Requests</h4>
                        <h6>Manage account deletion requests from business owners</h6>
                    </div>
                </div>
                <ul class="table-top-head">
                    <li>
                        <a data-bs-toggle="tooltip" data-bs-placement="top" title="Refresh" href="{{ route('delete-account') }}">
                            <i data-feather="rotate-ccw" class="feather-rotate-ccw"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Info Card -->
            <div class="alert alert-info mb-4">
                <i data-feather="info" class="me-2"></i>
                <strong>Note:</strong> Deleting a business owner's account will also delete all their stores, staff members, and related data. This action is irreversible.
            </div>

            <!-- Account List -->
            <div class="card table-list-card">
                <div class="card-body">
                    <div class="table-top table-top-two">
                        <div class="input-blocks search-set mb-0">
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
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Stores</th>
                                    <th>Staff Count</th>
                                    <th>Registered Date</th>
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
                                            <a href="javascript:void(0);" class="product-img">
                                                <img src="{{ URL::asset('/build/img/users/user-01.jpg') }}" alt="user">
                                            </a>
                                            <div>
                                                <a href="javascript:void(0);">{{ $user->name }}</a>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $user->ownedStores()->count() }} stores</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $user->subUsers()->count() }} staff</span>
                                    </td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td class="action-table-data">
                                        <div class="edit-delete-action">
                                            <a class="me-2 p-2" href="javascript:void(0);" onclick="viewAccountDetails({{ $user->id }})" title="View Details">
                                                <i data-feather="eye" class="action-eye"></i>
                                            </a>
                                            <a class="confirm-text p-2" href="javascript:void(0);" onclick="deleteAccount({{ $user->id }}, '{{ $user->name }}')" title="Delete Account">
                                                <i data-feather="trash-2" class="feather-trash-2"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No business owner accounts found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Account Details Modal -->
    <div class="modal fade" id="view-account-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="account-details-content">
                        <p class="text-center">Loading...</p>
                    </div>
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
    function viewAccountDetails(id) {
        $('#account-details-content').html('<p class="text-center">Loading...</p>');
        $('#view-account-modal').modal('show');
        
        $.get('/users/' + id, function(user) {
            var html = '<table class="table table-bordered">';
            html += '<tr><th width="30%">Name</th><td>' + user.name + '</td></tr>';
            html += '<tr><th>Email</th><td>' + user.email + '</td></tr>';
            html += '<tr><th>Phone</th><td>' + (user.phone || 'N/A') + '</td></tr>';
            html += '<tr><th>Registered</th><td>' + (user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A') + '</td></tr>';
            
            if (user.stores && user.stores.length > 0) {
                html += '<tr><th>Stores</th><td>';
                user.stores.forEach(function(store) {
                    html += '<span class="badge bg-primary me-1">' + store.name + '</span>';
                });
                html += '</td></tr>';
            }
            
            html += '</table>';
            
            $('#account-details-content').html(html);
        }).fail(function() {
            $('#account-details-content').html('<p class="text-center text-danger">Error loading details</p>');
        });
    }

    function deleteAccount(id, name) {
        Swal.fire({
            title: 'Delete Account: ' + name + '?',
            html: '<div class="text-start">' +
                  '<p class="text-danger"><strong>Warning:</strong> This will permanently delete:</p>' +
                  '<ul>' +
                  '<li>The user account</li>' +
                  '<li>All stores owned by this user</li>' +
                  '<li>All staff members under this account</li>' +
                  '<li>All related data (orders, invoices, etc.)</li>' +
                  '</ul>' +
                  '<p>This action <strong>cannot be undone</strong>!</p>' +
                  '</div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete everything!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Final Confirmation',
                    text: 'Type "DELETE" to confirm deletion of ' + name + "'s account",
                    input: 'text',
                    inputPlaceholder: 'Type DELETE',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    preConfirm: (value) => {
                        if (value !== 'DELETE') {
                            Swal.showValidationMessage('You must type DELETE to confirm');
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/delete-account/' + id,
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
        });
    }

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush
