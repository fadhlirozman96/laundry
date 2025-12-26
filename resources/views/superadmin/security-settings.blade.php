@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Security Settings</h4>
                <h6>Global security configuration</h6>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Password Minimum Length</label>
                                <input type="number" class="form-control" value="{{ $settings['password_min_length'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Session Timeout (minutes)</label>
                                <input type="number" class="form-control" value="{{ $settings['session_timeout'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Max Login Attempts</label>
                                <input type="number" class="form-control" value="{{ $settings['max_login_attempts'] }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Multi-Factor Authentication</label>
                                <select class="form-control">
                                    <option value="0">Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
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


