<?php $page = 'laundry-machines'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Machine Control</h4>
                <h6>Manage washers and dryers</h6>
            </div>
            <div class="page-btn">
                <a href="javascript:void(0);" class="btn btn-added" data-bs-toggle="modal" data-bs-target="#add-machine-modal">
                    <i data-feather="plus-circle" class="me-2"></i>Add Machine
                </a>
            </div>
        </div>

        <!-- Machine Status Overview -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h3>{{ $machines->where('status', 'available')->count() }}</h3>
                        <p class="mb-0">Available</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <h3>{{ $machines->where('status', 'in_use')->count() }}</h3>
                        <p class="mb-0">In Use</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body text-center">
                        <h3>{{ $machines->where('status', 'maintenance')->count() }}</h3>
                        <p class="mb-0">Maintenance</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h3>{{ $machines->where('status', 'out_of_order')->count() }}</h3>
                        <p class="mb-0">Out of Order</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Washers -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i data-feather="droplet" class="me-2"></i>Washers</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($washers as $washer)
                            <div class="col-md-6 mb-3">
                                <div class="card machine-card {{ $washer->status }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">{{ $washer->name }}</h5>
                                            {!! $washer->getStatusBadge() !!}
                                        </div>
                                        <p class="text-muted mb-1">
                                            <small>{{ $washer->brand }} {{ $washer->model }}</small>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Capacity:</strong> {{ $washer->capacity_kg }} kg<br>
                                            <strong>Cycle:</strong> {{ $washer->default_cycle_minutes }} min
                                        </p>
                                        
                                        @if($washer->status == 'in_use' && $washer->currentUsage)
                                            <div class="alert alert-warning mb-2 p-2">
                                                <small>
                                                    Started: {{ $washer->currentUsage->started_at->format('H:i') }}<br>
                                                    Load: {{ $washer->currentUsage->load_weight_kg ?? 'N/A' }} kg
                                                </small>
                                            </div>
                                        @endif

                                        <div class="btn-group w-100">
                                            @if($washer->isAvailable())
                                                <button class="btn btn-sm btn-primary" onclick="startMachine({{ $washer->id }}, 'washer')">
                                                    <i data-feather="play"></i> Start
                                                </button>
                                            @elseif($washer->status == 'in_use')
                                                <button class="btn btn-sm btn-success" onclick="endMachine({{ $washer->currentUsage->id ?? 0 }})">
                                                    <i data-feather="stop-circle"></i> End
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-secondary" onclick="editMachine({{ $washer->id }})">
                                                <i data-feather="settings"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-4">
                                <p>No washers configured. Add your first washer.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dryers -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="card-title mb-0"><i data-feather="wind" class="me-2"></i>Dryers</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @forelse($dryers as $dryer)
                            <div class="col-md-6 mb-3">
                                <div class="card machine-card {{ $dryer->status }}">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title mb-0">{{ $dryer->name }}</h5>
                                            {!! $dryer->getStatusBadge() !!}
                                        </div>
                                        <p class="text-muted mb-1">
                                            <small>{{ $dryer->brand }} {{ $dryer->model }}</small>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Capacity:</strong> {{ $dryer->capacity_kg }} kg<br>
                                            <strong>Cycle:</strong> {{ $dryer->default_cycle_minutes }} min
                                        </p>
                                        
                                        @if($dryer->status == 'in_use' && $dryer->currentUsage)
                                            <div class="alert alert-warning mb-2 p-2">
                                                <small>
                                                    Started: {{ $dryer->currentUsage->started_at->format('H:i') }}<br>
                                                    Load: {{ $dryer->currentUsage->load_weight_kg ?? 'N/A' }} kg
                                                </small>
                                            </div>
                                        @endif

                                        <div class="btn-group w-100">
                                            @if($dryer->isAvailable())
                                                <button class="btn btn-sm btn-primary" onclick="startMachine({{ $dryer->id }}, 'dryer')">
                                                    <i data-feather="play"></i> Start
                                                </button>
                                            @elseif($dryer->status == 'in_use')
                                                <button class="btn btn-sm btn-success" onclick="endMachine({{ $dryer->currentUsage->id ?? 0 }})">
                                                    <i data-feather="stop-circle"></i> End
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-secondary" onclick="editMachine({{ $dryer->id }})">
                                                <i data-feather="settings"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center text-muted py-4">
                                <p>No dryers configured. Add your first dryer.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage History Link -->
        <div class="text-center mt-4">
            <a href="{{ route('laundry.machine-usage') }}" class="btn btn-outline-primary">
                <i data-feather="clock" class="me-2"></i>View Usage History
            </a>
        </div>
    </div>
</div>

<!-- Add Machine Modal -->
<div class="modal fade" id="add-machine-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Machine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="add-machine-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Machine Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="e.g. Washer 1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-control" name="type" required>
                            <option value="washer">Washer</option>
                            <option value="dryer">Dryer</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand</label>
                            <input type="text" class="form-control" name="brand" placeholder="e.g. Samsung">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Model</label>
                            <input type="text" class="form-control" name="model">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Capacity (kg) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="capacity_kg" required step="0.1" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Cycle Time (min)</label>
                            <input type="number" class="form-control" name="default_cycle_minutes" value="30" min="1">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Machine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Start Machine Modal -->
<div class="modal fade" id="start-machine-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Start Machine</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="start-machine-form">
                @csrf
                <input type="hidden" name="machine_id" id="start_machine_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Order (optional)</label>
                        <select class="form-control select2" name="order_id" id="start_order_id">
                            <option value="">-- Select Order --</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Load Weight (kg)</label>
                            <input type="number" class="form-control" name="load_weight_kg" step="0.1" min="0">
                            <small class="text-muted" id="capacity-note"></small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Items Count</label>
                            <input type="number" class="form-control" name="items_count" min="0">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" class="form-control" name="duration_minutes" id="start_duration">
                    </div>
                    <div class="row" id="washer-options" style="display:none;">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Wash Type</label>
                            <select class="form-control" name="wash_type">
                                <option value="">Select</option>
                                <option value="normal">Normal</option>
                                <option value="delicate">Delicate</option>
                                <option value="heavy">Heavy Duty</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Temperature</label>
                            <select class="form-control" name="temperature">
                                <option value="">Select</option>
                                <option value="cold">Cold</option>
                                <option value="warm">Warm</option>
                                <option value="hot">Hot</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Spin Speed</label>
                            <select class="form-control" name="spin_speed">
                                <option value="">Select</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" name="notes" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Start Machine</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .machine-card {
        border-left: 4px solid #6c757d;
    }
    .machine-card.available {
        border-left-color: #28a745;
    }
    .machine-card.in_use {
        border-left-color: #ffc107;
        animation: pulse-border 2s infinite;
    }
    .machine-card.maintenance {
        border-left-color: #17a2b8;
    }
    .machine-card.out_of_order {
        border-left-color: #dc3545;
    }
    @keyframes pulse-border {
        0%, 100% { border-left-width: 4px; }
        50% { border-left-width: 6px; }
    }
</style>
@endsection

@push('scripts')
<script>
    var machineData = {};

    function startMachine(machineId, type) {
        $('#start_machine_id').val(machineId);
        
        if (type === 'washer') {
            $('#washer-options').show();
        } else {
            $('#washer-options').hide();
        }
        
        // Load active orders for selection
        // You would typically load this via AJAX
        
        $('#start-machine-modal').modal('show');
    }

    function endMachine(usageLogId) {
        if (!usageLogId) {
            Swal.fire('Error', 'No active usage found', 'error');
            return;
        }
        
        Swal.fire({
            title: 'End Machine Cycle?',
            text: 'Mark this cycle as completed?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, end cycle',
            input: 'textarea',
            inputLabel: 'Any issues? (optional)',
            inputPlaceholder: 'Notes about this cycle...'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/laundry-machines/usage/' + usageLogId + '/end',
                    type: 'PUT',
                    data: { 
                        issues: result.value,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message + ' (Duration: ' + response.duration_minutes + ' min)', 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
                    }
                });
            }
        });
    }

    function editMachine(machineId) {
        // Load machine data and show edit modal
        Swal.fire('Coming Soon', 'Machine edit functionality', 'info');
    }

    $('#add-machine-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("laundry.machines.store") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#add-machine-modal').modal('hide');
                    Swal.fire('Success', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed', 'error');
            }
        });
    });

    $('#start-machine-form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("laundry.machines.start") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#start-machine-modal').modal('hide');
                    if (response.overload_warning) {
                        Swal.fire('Warning', response.message, 'warning').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Success', response.message, 'success').then(() => {
                            location.reload();
                        });
                    }
                }
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to start machine', 'error');
            }
        });
    });

    $(document).ready(function() {
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    });
</script>
@endpush



