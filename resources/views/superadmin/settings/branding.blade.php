@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Global Branding Settings</h4>
                <h6>Manage global branding and appearance</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('superadmin.settings.branding.update') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Application Name</label>
                                <input type="text" class="form-control" name="app_name" value="{{ $settings['app_name'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Logo URL</label>
                                <input type="text" class="form-control" name="logo_url" value="{{ $settings['logo_url'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Primary Color</label>
                                <input type="color" class="form-control" name="primary_color" value="{{ $settings['primary_color'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Secondary Color</label>
                                <input type="color" class="form-control" name="secondary_color" value="{{ $settings['secondary_color'] }}">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

