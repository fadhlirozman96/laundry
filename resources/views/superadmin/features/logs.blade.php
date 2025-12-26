@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Feature Access Logs</h4>
                <h6>Track feature access attempts</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.features.index') }}" class="btn btn-secondary">
                    <i data-feather="arrow-left"></i> Back to Features
                </a>
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
                                <th>Business</th>
                                <th>Feature</th>
                                <th>Result</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $log->user->name ?? 'N/A' }}</td>
                                <td>{{ $log->business->name ?? 'N/A' }}</td>
                                <td><code>{{ $log->feature_name }}</code></td>
                                <td>
                                    @if($log->access_granted)
                                        <span class="badge bg-success">Granted</span>
                                    @else
                                        <span class="badge bg-danger">Denied</span>
                                    @endif
                                </td>
                                <td><small>{{ $log->reason }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No logs found</td>
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


