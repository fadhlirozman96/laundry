@extends('layout.mainlayout')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Checkout</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('business-owner.subscription') }}">Subscription</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Plan Details</h5>
                        
                        <div class="p-4 bg-light rounded mb-4">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4 class="mb-2">{{ $plan->name }} Plan</h4>
                                    <p class="text-muted mb-0">{{ $plan->description }}</p>
                                </div>
                                <div class="col-auto">
                                    <span class="badge badge-lg bg-primary">{{ $plan->name }}</span>
                                </div>
                            </div>
                        </div>

                        <h5 class="mb-3">Select Billing Cycle</h5>
                        <form action="{{ route('business-owner.process-payment') }}" method="POST" id="paymentForm">
                            @csrf
                            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded {{ $plan->price > 0 ? '' : 'bg-light' }}">
                                        <input class="form-check-input" type="radio" name="billing_cycle" id="monthly" value="monthly" checked>
                                        <label class="form-check-label w-100" for="monthly">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>Monthly</strong>
                                                    <p class="text-muted small mb-0">Billed every month</p>
                                                </div>
                                                <div class="text-end">
                                                    <h5 class="mb-0">MYR {{ number_format($plan->price, 2) }}</h5>
                                                    <small class="text-muted">/month</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check p-3 border rounded">
                                        <input class="form-check-input" type="radio" name="billing_cycle" id="annual" value="annual">
                                        <label class="form-check-label w-100" for="annual">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <strong>Annual</strong>
                                                    <span class="badge bg-success ms-2">Save 17%</span>
                                                    <p class="text-muted small mb-0">Billed once per year</p>
                                                </div>
                                                <div class="text-end">
                                                    <h5 class="mb-0">MYR {{ number_format($plan->annual_price, 2) }}</h5>
                                                    <small class="text-muted">/year</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mb-3">Payment Method</h5>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="form-check p-3 border rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                        <label class="form-check-label w-100" for="credit_card">
                                            <i data-feather="credit-card"></i> Credit/Debit Card
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check p-3 border rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" id="fpx" value="fpx">
                                        <label class="form-check-label w-100" for="fpx">
                                            <i data-feather="smartphone"></i> FPX (Online Banking)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check p-3 border rounded">
                                        <input class="form-check-input" type="radio" name="payment_method" id="ewallet" value="ewallet">
                                        <label class="form-check-label w-100" for="ewallet">
                                            <i data-feather="smartphone"></i> E-Wallet
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Dummy Payment Info -->
                            <div class="alert alert-info">
                                <i data-feather="info"></i> <strong>Demo Mode:</strong> This is a dummy payment. Click "Complete Payment" to simulate a successful transaction.
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('business-owner.subscription') }}" class="btn btn-secondary">
                                    <i data-feather="arrow-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Complete Payment <i data-feather="arrow-right"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Order Summary</h5>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Plan</span>
                                <strong>{{ $plan->name }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Billing Cycle</span>
                                <strong id="summary_cycle">Monthly</strong>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span id="summary_subtotal">MYR {{ number_format($plan->price, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (0%)</span>
                                <span>MYR 0.00</span>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total</strong>
                            <h4 class="mb-0" id="summary_total">MYR {{ number_format($plan->price, 2) }}</h4>
                        </div>

                        @if($currentSubscription)
                        <div class="alert alert-warning small">
                            <strong>Note:</strong> Your current {{ $currentSubscription->plan->name }} plan will be replaced.
                        </div>
                        @endif

                        <div class="bg-light p-3 rounded">
                            <h6 class="mb-2">What you'll get:</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-1"><i data-feather="check" class="text-success" style="width: 16px; height: 16px;"></i> {{ $plan->max_stores }} Store(s)</li>
                                @php
                                    $features = json_decode($plan->features, true);
                                @endphp
                                <li class="mb-1"><i data-feather="check" class="text-success" style="width: 16px; height: 16px;"></i> {{ $features['max_users'] ?? 'Unlimited' }} Users</li>
                                <li class="mb-1"><i data-feather="check" class="text-success" style="width: 16px; height: 16px;"></i> {{ $features['max_orders_per_month'] ?? 'Unlimited' }} Orders/month</li>
                                <li class="mb-1"><i data-feather="check" class="text-success" style="width: 16px; height: 16px;"></i> {{ ucfirst($features['customer_support'] ?? 'Standard') }} Support</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyRadio = document.getElementById('monthly');
    const annualRadio = document.getElementById('annual');
    const summaryCycle = document.getElementById('summary_cycle');
    const summarySubtotal = document.getElementById('summary_subtotal');
    const summaryTotal = document.getElementById('summary_total');
    
    const monthlyPrice = {{ $plan->price }};
    const annualPrice = {{ $plan->annual_price }};
    
    function updateSummary() {
        if (annualRadio.checked) {
            summaryCycle.textContent = 'Annual';
            summarySubtotal.textContent = 'MYR ' + annualPrice.toFixed(2);
            summaryTotal.textContent = 'MYR ' + annualPrice.toFixed(2);
        } else {
            summaryCycle.textContent = 'Monthly';
            summarySubtotal.textContent = 'MYR ' + monthlyPrice.toFixed(2);
            summaryTotal.textContent = 'MYR ' + monthlyPrice.toFixed(2);
        }
    }
    
    monthlyRadio.addEventListener('change', updateSummary);
    annualRadio.addEventListener('change', updateSummary);
    
    // Initialize feather icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
});
</script>
@endsection

