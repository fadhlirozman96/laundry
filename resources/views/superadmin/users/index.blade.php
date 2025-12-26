@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Management</h4>
                <h6>Manage all users across the platform</h6>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-primary">
                                <i data-feather="users"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Total Users</h6>
                            <h3>{{ $stats['total'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-danger">
                                <i data-feather="shield"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Superadmins</h6>
                            <h3>{{ $stats['superadmins'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-success">
                                <i data-feather="briefcase"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">Business Owners</h6>
                            <h3>{{ $stats['owners'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6 col-12">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <div class="dash-widget-header">
                            <span class="dash-widget-icon bg-info">
                                <i data-feather="package"></i>
                            </span>
                        </div>
                        <div class="dash-widget-info">
                            <h6 class="text-muted">With Plan</h6>
                            <h3>{{ $stats['with_plan'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User List -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Email</th>
                                <th>Business</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->business->name ?? 'N/A' }}</td>
                                <td>
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-secondary">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                        <i data-feather="eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No users found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


