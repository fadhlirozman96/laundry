@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Impersonation History</h4>
                <h6>Track superadmin impersonation activities</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Impersonator</th>
                                <th>Target User</th>
                                <th>Action</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($history as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $log->user->name ?? 'N/A' }}</td>
                                <td>{{ $log->description }}</td>
                                <td>{!! $log->getActionBadge() !!}</td>
                                <td><code>{{ $log->ip_address }}</code></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No impersonation history found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


