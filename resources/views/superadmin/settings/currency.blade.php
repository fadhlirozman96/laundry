@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Currency Settings</h4>
                <h6>Set default currency for the platform</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.settings.currency.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Default Currency</label>
                        <select name="currency" class="form-control">
                            @foreach($currencies as $currency)
                                <option value="{{ $currency }}" {{ $current == $currency ? 'selected' : '' }}>
                                    {{ $currency }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">This will be the default currency for all new businesses</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

