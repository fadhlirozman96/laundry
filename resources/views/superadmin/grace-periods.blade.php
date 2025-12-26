@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Grace Periods</h4>
                <h6>Manage subscription grace periods</h6>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Active Grace Periods</h5>
                        <h2>{{ $active }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5>Expired</h5>
                        <h2>{{ $expired }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Business</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Reason</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gracePeriods as $grace)
                            <tr>
                                <td>{{ $grace->business->name ?? 'N/A' }}</td>
                                <td>{{ $grace->grace_start_date->format('d M Y') }}</td>
                                <td>{{ $grace->grace_end_date->format('d M Y') }}</td>
                                <td>
                                    @if($grace->status === 'active')
                                        <span class="badge bg-success">Active</span>
                                    @elseif($grace->status === 'expired')
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $grace->status }}</span>
                                    @endif
                                </td>
                                <td><small>{{ $grace->reason }}</small></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">No grace periods found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $gracePeriods->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


