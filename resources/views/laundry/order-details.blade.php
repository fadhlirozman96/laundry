<?php $page = 'laundry-orders'; ?>
@extends('layout.mainlayout')
@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="page-title">
                <h4>Order: {{ $order->order_number }}</h4>
                <h6>Created {{ $order->created_at->format('d M Y H:i') }} by {{ $order->user->name }}</h6>
            </div>
            <div class="page-btn">
                <a href="{{ route('laundry.orders') }}" class="btn btn-secondary me-2">
                    <i data-feather="arrow-left" class="me-2"></i>Back
                </a>
                <button class="btn btn-info" onclick="printOrder()">
                    <i data-feather="printer" class="me-2"></i>Print
                </button>
            </div>
        </div>

        <div class="row">
            <!-- Order Info & Status -->
            <div class="col-lg-8">
                <!-- Status Tracker -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="order-status-tracker">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                @php
                                    $statuses = ['received', 'washing', 'drying', 'folding', 'ready', 'collected'];
                                    $currentIndex = array_search($order->status, $statuses);
                                @endphp
                                @foreach($statuses as $index => $status)
                                    <div class="text-center flex-fill">
                                        <div class="status-circle {{ $index <= $currentIndex ? 'active' : '' }} {{ $index == $currentIndex ? 'current' : '' }}">
                                            @if($index < $currentIndex)
                                                <i data-feather="check"></i>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <small class="d-block mt-1 {{ $index == $currentIndex ? 'fw-bold' : '' }}">
                                            {{ ucfirst($status) }}
                                        </small>
                                        @php
                                            $timeField = $status . '_at';
                                        @endphp
                                        @if($order->$timeField)
                                            <small class="text-muted d-block">
                                                {{ $order->$timeField->format('H:i') }}
                                            </small>
                                        @endif
                                    </div>
                                    @if($index < count($statuses) - 1)
                                        <div class="status-line {{ $index < $currentIndex ? 'active' : '' }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        @if($order->status !== 'collected')
                            <div class="text-center mt-4">
                                @php $nextStatus = $order->getNextStatus(); @endphp
                                @if($nextStatus)
                                    @if($nextStatus === 'ready' && !$order->qc_passed)
                                        <a href="{{ route('laundry.qc.create', $order->id) }}" class="btn btn-warning btn-lg">
                                            <i data-feather="check-square" class="me-2"></i>Complete Quality Check First
                                        </a>
                                    @else
                                        <button class="btn btn-primary btn-lg" onclick="updateStatus('{{ $nextStatus }}')">
                                            <i data-feather="arrow-right" class="me-2"></i>Move to {{ ucfirst($nextStatus) }}
                                        </button>
                                    @endif
                                @endif
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Items List -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Items ({{ $order->total_services }} items)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Item</th>
                                        <th>Color</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Subtotal</th>
                                        <th>Condition</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td><code>{{ $item->item_code }}</code></td>
                                        <td>
                                            <strong>{{ $item->service_name }}</strong>
                                            @if($item->service)
                                                <br><small class="text-muted">{{ $item->service->name }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->color ?? '-' }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>MYR {{ number_format($item->price, 2) }}</td>
                                        <td>MYR {{ number_format($item->subtotal, 2) }}</td>
                                        <td>
                                            @if($item->condition_notes)
                                                <span class="badge bg-info" data-bs-toggle="tooltip" title="{{ $item->condition_notes }}">
                                                    Note
                                                </span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Subtotal:</strong></td>
                                        <td colspan="2">MYR {{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Tax (6%):</strong></td>
                                        <td colspan="2">MYR {{ number_format($order->tax, 2) }}</td>
                                    </tr>
                                    @if($order->discount > 0)
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Discount:</strong></td>
                                        <td colspan="2">- MYR {{ number_format($order->discount, 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-primary">
                                        <td colspan="5" class="text-end"><strong>TOTAL:</strong></td>
                                        <td colspan="2"><strong>MYR {{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status History -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Status History</h5>
                    </div>
                    <div class="card-body">
                        <ul class="timeline">
                            @foreach($order->statusLogs as $log)
                            <li class="timeline-item">
                                <span class="timeline-point"></span>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ ucfirst($log->to_status) }}</strong>
                                        <small class="text-muted">{{ $log->created_at->format('d M Y H:i') }}</small>
                                    </div>
                                    <p class="mb-0">
                                        By: {{ $log->user->name }}
                                        @if($log->notes)
                                            <br><small class="text-muted">{{ $log->notes }}</small>
                                        @endif
                                    </p>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <!-- QR Code -->
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <div id="qrcode" class="mb-3"></div>
                        <p class="mb-0"><code>{{ $order->qr_code }}</code></p>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Customer</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>{{ $order->customer_name }}</strong></p>
                        @if($order->customer_phone)
                            <p class="mb-1"><i data-feather="phone" class="me-2"></i>{{ $order->customer_phone }}</p>
                        @endif
                        @if($order->customer_email)
                            <p class="mb-1"><i data-feather="mail" class="me-2"></i>{{ $order->customer_email }}</p>
                        @endif
                    </div>
                </div>

                <!-- QC Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Quality Check</h5>
                    </div>
                    <div class="card-body">
                        @if($order->qc_passed)
                            <div class="alert alert-success mb-0">
                                <i data-feather="check-circle" class="me-2"></i>
                                <strong>QC Passed</strong><br>
                                <small>By {{ $order->qcInspector->name ?? 'N/A' }} at {{ $order->qc_at ? $order->qc_at->format('d M H:i') : '-' }}</small>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i data-feather="alert-circle" class="me-2"></i>
                                <strong>QC Pending</strong><br>
                                <small>Order must pass QC before marking as Ready</small>
                            </div>
                            @if(in_array($order->status, ['folding', 'drying']))
                                <a href="{{ route('laundry.qc.create', $order->id) }}" class="btn btn-warning w-100 mt-2">
                                    Start QC
                                </a>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Payment</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Total:</span>
                            <strong>MYR {{ number_format($order->total, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Paid:</span>
                            <span class="text-success">MYR {{ number_format($order->amount_paid, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Balance:</span>
                            <span class="text-danger">MYR {{ number_format($order->total - $order->amount_paid, 2) }}</span>
                        </div>
                        <hr>
                        <div class="text-center">
                            @if($order->payment_status == 'paid')
                                <span class="badge bg-success fs-6">PAID</span>
                            @elseif($order->payment_status == 'partial')
                                <span class="badge bg-warning fs-6">PARTIAL</span>
                            @else
                                <span class="badge bg-danger fs-6">PENDING</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Special Instructions -->
                @if($order->special_instructions)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Special Instructions</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->special_instructions }}</p>
                    </div>
                </div>
                @endif

                <!-- Notes -->
                @if($order->notes)
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Notes</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .status-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #6c757d;
    }
    .status-circle.active {
        background: #28a745;
        color: white;
    }
    .status-circle.current {
        background: #007bff;
        color: white;
        animation: pulse 1.5s infinite;
    }
    .status-line {
        height: 3px;
        background: #e9ecef;
        flex: 1;
        margin: 0 5px;
    }
    .status-line.active {
        background: #28a745;
    }
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }
    .timeline {
        list-style: none;
        padding: 0;
        position: relative;
    }
    .timeline-item {
        padding-left: 30px;
        margin-bottom: 20px;
        position: relative;
    }
    .timeline-point {
        position: absolute;
        left: 0;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #007bff;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: 5px;
        top: 17px;
        height: calc(100% + 8px);
        width: 2px;
        background: #e9ecef;
    }
    .timeline-item:last-child::before {
        display: none;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function updateStatus(newStatus) {
        Swal.fire({
            title: 'Update Status',
            text: 'Move order to ' + newStatus.charAt(0).toUpperCase() + newStatus.slice(1) + '?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update',
            input: 'textarea',
            inputLabel: 'Notes (optional)',
            inputPlaceholder: 'Any notes about this status change...'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("laundry.update-status", $order->id) }}',
                    type: 'PUT',
                    data: { 
                        status: newStatus, 
                        notes: result.value,
                        _token: '{{ csrf_token() }}' 
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update status', 'error');
                    }
                });
            }
        });
    }

    function printOrder() {
        window.print();
    }

    $(document).ready(function() {
        // Generate QR code
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $order->qr_code }}",
            width: 150,
            height: 150
        });

        if (typeof feather !== 'undefined') {
            feather.replace();
        }

        $('[data-bs-toggle="tooltip"]').tooltip();
    });
</script>
@endpush


