@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Error Logs</h4>
                <h6>System error logs</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <pre style="max-height: 600px; overflow-y: auto; background: #1e293b; color: #e2e8f0; padding: 20px; border-radius: 8px;">@foreach($logs as $line){{ $line }}@endforeach</pre>
            </div>
        </div>
    </div>
</div>
@endsection

