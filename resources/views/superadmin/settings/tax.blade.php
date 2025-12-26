@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Tax Settings</h4>
                <h6>Configure default tax settings</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.settings.tax.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tax Name</label>
                                <input type="text" name="tax_name" class="form-control" value="{{ $settings['tax_name'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Tax Rate (%)</label>
                                <input type="number" name="tax_rate" class="form-control" value="{{ $settings['default_tax_rate'] }}" step="0.01">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


