@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Timezone Settings</h4>
                <h6>Set default timezone</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.settings.timezone.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Default Timezone</label>
                        <select name="timezone" class="form-control">
                            @foreach($timezones as $timezone)
                                <option value="{{ $timezone }}" {{ $current == $timezone ? 'selected' : '' }}>
                                    {{ $timezone }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">This will be the default timezone for all new businesses</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


