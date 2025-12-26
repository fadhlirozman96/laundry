@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>User Profiles</h4>
                <h6>Business owner profiles</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Business</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($profiles as $profile)
                            <tr>
                                <td><strong>{{ $profile->name }}</strong></td>
                                <td>{{ $profile->email }}</td>
                                <td>{{ $profile->business->name ?? 'N/A' }}</td>
                                <td>{{ $profile->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('superadmin.users.show', $profile->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No profiles found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $profiles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


