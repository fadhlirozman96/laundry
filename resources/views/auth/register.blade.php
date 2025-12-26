<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - RAPY SaaS Platform</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ URL::asset('build/img/favicon.png') }}">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/bootstrap.min.css') }}">
    
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/plugins/fontawesome/css/all.min.css') }}">
    
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ URL::asset('build/css/style.css') }}">
    
    <style>
        .plan-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s;
            height: 100%;
        }
        .plan-card:hover {
            border-color: #ff9f43;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .plan-card.selected {
            border-color: #28a745;
            background-color: #f0f9f4;
        }
        .plan-card input[type="radio"] {
            display: none;
        }
        .plan-price {
            font-size: 2rem;
            font-weight: bold;
            color: #2e7d32;
        }
        .plan-features {
            list-style: none;
            padding: 0;
        }
        .plan-features li {
            padding: 8px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .plan-features li:last-child {
            border-bottom: none;
        }
        .register-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
        }
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
        }
        .step {
            display: flex;
            align-items: center;
            margin: 0 20px;
        }
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .step.active .step-number {
            background: #28a745;
            color: white;
        }
        .step.completed .step-number {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <div class="register-container">
            <div class="text-center mb-4">
                <img src="{{ URL::asset('build/img/logo.png') }}" alt="RAPY Logo" style="max-height: 60px;">
                <h2 class="mt-3">Create Your Account</h2>
                <p class="text-muted">Choose a plan and get started in minutes</p>
            </div>

            <!-- Step Indicator -->
            <div class="step-indicator">
                <div class="step active" id="step1-indicator">
                    <div class="step-number">1</div>
                    <span>Choose Plan</span>
                </div>
                <div class="step" id="step2-indicator">
                    <div class="step-number">2</div>
                    <span>Your Details</span>
                </div>
            </div>

            <form action="{{ route('register.submit') }}" method="POST" id="registerForm">
                @csrf
                
                <!-- Step 1: Choose Plan -->
                <div id="step1" class="step-content">
                    <h4 class="text-center mb-4">Choose Your Plan</h4>
                    <div class="row">
                        @foreach($plans as $plan)
                        <div class="col-md-3 mb-3">
                            <label class="plan-card" onclick="selectPlan(this, '{{ $plan->id }}')">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ $plan->slug == 'free' ? 'checked' : '' }}>
                                <div class="text-center">
                                    <h5>{{ $plan->name }}</h5>
                                    <div class="plan-price">
                                        @if($plan->price == 0)
                                            FREE
                                        @else
                                            MYR {{ number_format($plan->price, 0) }}
                                        @endif
                                    </div>
                                    <small class="text-muted">/month</small>
                                    <hr>
                                    <ul class="plan-features text-left">
                                        <li><i class="fas fa-store"></i> {{ $plan->max_stores == 'unlimited' ? 'Unlimited' : $plan->max_stores }} Store(s)</li>
                                        <li><i class="fas fa-users"></i> {{ $plan->getFeature('max_users') == 'unlimited' ? 'Unlimited' : $plan->getFeature('max_users') }} Users</li>
                                        <li><i class="fas fa-box"></i> {{ $plan->getFeature('max_products') == 'unlimited' ? 'Unlimited' : $plan->getFeature('max_products') }} Products</li>
                                        <li><i class="fas fa-shopping-cart"></i> {{ $plan->getFeature('max_orders_per_month') == 'unlimited' ? 'Unlimited' : $plan->getFeature('max_orders_per_month') }} Orders/mo</li>
                                    </ul>
                                </div>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary btn-lg" onclick="nextStep()">Continue</button>
                    </div>
                </div>

                <!-- Step 2: Account Details -->
                <div id="step2" class="step-content" style="display: none;">
                    <div class="row justify-content-center">
                        <div class="col-md-6">
                            <h4 class="text-center mb-4">Your Account Details</h4>
                            
                            <div class="form-group mb-3">
                                <label>Full Name *</label>
                                <input type="text" class="form-control" name="name" required value="{{ old('name') }}">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Email Address *</label>
                                <input type="email" class="form-control" name="email" required value="{{ old('email') }}">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Phone Number</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                            </div>

                            <div class="form-group mb-3">
                                <label>Company/Business Name *</label>
                                <input type="text" class="form-control" name="company_name" required value="{{ old('company_name') }}">
                                @error('company_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Password *</label>
                                <input type="password" class="form-control" name="password" required>
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Confirm Password *</label>
                                <input type="password" class="form-control" name="password_confirmation" required>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" target="_blank">Terms & Conditions</a>
                                </label>
                            </div>

                            <div class="text-center mt-4">
                                <button type="button" class="btn btn-secondary me-2" onclick="prevStep()">Back</button>
                                <button type="submit" class="btn btn-success btn-lg">Create Account</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="text-center mt-4">
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('build/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
        let currentStep = 1;

        function selectPlan(element, planId) {
            document.querySelectorAll('.plan-card').forEach(card => card.classList.remove('selected'));
            element.classList.add('selected');
            element.querySelector('input[type="radio"]').checked = true;
        }

        function nextStep() {
            if (currentStep === 1) {
                document.getElementById('step1').style.display = 'none';
                document.getElementById('step2').style.display = 'block';
                document.getElementById('step1-indicator').classList.add('completed');
                document.getElementById('step2-indicator').classList.add('active');
                currentStep = 2;
            }
        }

        function prevStep() {
            if (currentStep === 2) {
                document.getElementById('step2').style.display = 'none';
                document.getElementById('step1').style.display = 'block';
                document.getElementById('step2-indicator').classList.remove('active');
                document.getElementById('step1-indicator').classList.remove('completed');
                currentStep = 1;
            }
        }

        // Auto-select first plan (Free)
        document.addEventListener('DOMContentLoaded', function() {
            const firstPlan = document.querySelector('.plan-card');
            if (firstPlan) {
                firstPlan.classList.add('selected');
            }
        });
    </script>
</body>
</html>

