@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Feature Gating</h4>
                <h6>Manage feature access per plan</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('superadmin.features.logs') }}" class="btn btn-info">
                    <i data-feather="list"></i> View Access Logs
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Feature</th>
                                @foreach($plans as $plan)
                                <th class="text-center">
                                    {{ $plan->name }}<br>
                                    <small class="text-muted">MYR {{ number_format($plan->price, 0) }}/mo</small>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allFeatures as $key => $featureName)
                            <tr>
                                <td><strong>{{ $featureName }}</strong></td>
                                @foreach($plans as $plan)
                                <td class="text-center">
                                    @if($plan->hasFeature($key))
                                        <span class="badge bg-success">
                                            <i data-feather="check"></i> Enabled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i data-feather="x"></i> Disabled
                                        </span>
                                    @endif
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-4">
                    <i data-feather="info"></i>
                    <strong>Feature Gating is Active:</strong> Features are automatically checked based on the user's subscription plan. Access attempts are logged for auditing.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


