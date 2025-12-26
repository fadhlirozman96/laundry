@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>System Logs</h4>
                <h6>View system activity logs</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $log->user->name ?? 'System' }}</td>
                                <td>{!! $log->getActionBadge() !!}</td>
                                <td><small>{{ $log->description }}</small></td>
                                <td><code>{{ $log->ip_address }}</code></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No logs found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


