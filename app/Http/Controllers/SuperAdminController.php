<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Store;
use App\Models\Order;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuperAdminController extends Controller
{
    /**
     * Display superadmin dashboard with SaaS statistics
     */
    public function dashboard()
    {
        // Get business_owner role ID
        $businessOwnerRoleId = DB::table('roles')->where('name', 'business_owner')->value('id');
        
        // Total Customers (Business Owners)
        $totalCustomers = DB::table('user_roles')
            ->where('role_id', $businessOwnerRoleId)
            ->distinct('user_id')
            ->count('user_id');
            
        $newCustomersThisMonth = DB::table('user_roles')
            ->join('users', 'user_roles.user_id', '=', 'users.id')
            ->where('user_roles.role_id', $businessOwnerRoleId)
            ->whereMonth('users.created_at', Carbon::now()->month)
            ->distinct('user_roles.user_id')
            ->count('user_roles.user_id');
        
        // Revenue Statistics
        $totalRevenue = SubscriptionPayment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = SubscriptionPayment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        $yearlyRevenue = SubscriptionPayment::where('status', 'completed')
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');
        
        // Active Subscriptions
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $trialSubscriptions = Subscription::where('status', 'trial')->count();
        $canceledSubscriptions = Subscription::where('status', 'canceled')->count();
        
        // Plan Distribution - Show all plans with their active subscription counts
        $planDistribution = Plan::leftJoin('subscriptions', function($join) {
                $join->on('plans.id', '=', 'subscriptions.plan_id')
                     ->where('subscriptions.status', '=', 'active');
            })
            ->selectRaw('plans.name as plan_name, plans.id as plan_id, COUNT(subscriptions.id) as count')
            ->groupBy('plans.id', 'plans.name')
            ->orderBy('plans.price', 'asc')
            ->get();
        
        // Total Stores Across All Businesses
        $totalStores = Store::count();
        
        // Total Orders Across All Businesses
        $totalOrders = Order::count();
        $ordersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)->count();
        
        // Recent Customers
        $recentCustomers = User::whereHas('roles', function($query) {
                $query->where('name', 'business_owner');
            })
            ->with('currentPlan')
            ->latest()
            ->take(10)
            ->get();
        
        // Recent Payments
        $recentPayments = SubscriptionPayment::with(['user', 'subscription.plan'])
            ->latest()
            ->take(10)
            ->get();
        
        // Upcoming Renewals (next 30 days)
        $upcomingRenewals = Subscription::where('status', 'active')
            ->whereBetween('next_billing_date', [Carbon::now(), Carbon::now()->addDays(30)])
            ->with(['user', 'plan'])
            ->orderBy('next_billing_date')
            ->take(10)
            ->get();
        
        // Churn Rate (canceled in last 30 days vs active at start of month)
        $canceledLast30Days = Subscription::where('status', 'canceled')
            ->where('updated_at', '>=', Carbon::now()->subDays(30))
            ->count();
        
        $activeAtMonthStart = Subscription::where('status', 'active')
            ->orWhere(function($query) {
                $query->where('status', 'canceled')
                      ->where('updated_at', '>=', Carbon::now()->startOfMonth());
            })
            ->count();
        
        $churnRate = $activeAtMonthStart > 0 ? round(($canceledLast30Days / $activeAtMonthStart) * 100, 1) : 0;
        
        // Revenue Chart Data (Last 12 months)
        $revenueChartData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenue = SubscriptionPayment::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
            
            $revenueChartData[] = [
                'month' => $month->format('M Y'),
                'revenue' => $revenue,
            ];
        }
        
        return view('superadmin.dashboard', compact(
            'totalCustomers',
            'newCustomersThisMonth',
            'totalRevenue',
            'monthlyRevenue',
            'yearlyRevenue',
            'activeSubscriptions',
            'trialSubscriptions',
            'canceledSubscriptions',
            'planDistribution',
            'totalStores',
            'totalOrders',
            'ordersThisMonth',
            'recentCustomers',
            'recentPayments',
            'upcomingRenewals',
            'canceledLast30Days',
            'churnRate',
            'revenueChartData'
        ));
    }

    /**
     * Display all business owners
     */
    public function customers()
    {
        $customers = User::whereHas('roles', function($query) {
                $query->where('name', 'business_owner');
            })
            ->latest()
            ->paginate(20);
        
        return view('superadmin.customers', compact('customers'));
    }

    /**
     * Impersonate a business owner
     */
    public function impersonate($userId)
    {
        $user = User::findOrFail($userId);
        
        // Store original user ID in session
        session(['impersonate_from' => auth()->id()]);
        
        // Login as the target user
        auth()->login($user);
        
        return redirect()->route('index')
            ->with('success', 'You are now viewing as: ' . $user->name);
    }

    /**
     * Stop impersonating
     */
    public function stopImpersonate()
    {
        $originalUserId = session('impersonate_from');
        
        if ($originalUserId) {
            $originalUser = User::find($originalUserId);
            auth()->login($originalUser);
            session()->forget('impersonate_from');
            
            return redirect()->route('superadmin.dashboard')
                ->with('success', 'Stopped impersonating. Back to superadmin view.');
        }
        
        return redirect()->route('superadmin.dashboard');
    }

    /**
     * Display all payments
     */
    public function payments()
    {
        $payments = SubscriptionPayment::with(['subscription.user', 'subscription.plan'])
            ->latest()
            ->paginate(20);
        
        // Statistics
        $totalPayments = SubscriptionPayment::where('status', 'completed')->sum('amount');
        $pendingPayments = SubscriptionPayment::where('status', 'pending')->count();
        $failedPayments = SubscriptionPayment::where('status', 'failed')->count();
        $paymentsThisMonth = SubscriptionPayment::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');
        
        return view('superadmin.payments', compact(
            'payments',
            'totalPayments',
            'pendingPayments',
            'failedPayments',
            'paymentsThisMonth'
        ));
    }

    /**
     * Display all subscriptions
     */
    public function subscriptions()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->paginate(20);
        
        return view('superadmin.subscriptions', compact('subscriptions'));
    }

    /**
     * Display all plans
     */
    public function plans()
    {
        $plans = Plan::with('subscriptions')
            ->orderBy('sort_order')
            ->get();
        
        return view('superadmin.plans', compact('plans'));
    }

    /**
     * Show the form for editing a plan
     */
    public function editPlan($id)
    {
        $plan = Plan::findOrFail($id);
        
        // Ensure features is an array (it should be cast automatically by the model)
        if (!is_array($plan->features)) {
            $plan->features = json_decode($plan->features, true) ?? [];
        }
        
        return view('superadmin.plans-edit', compact('plan'));
    }

    /**
     * Update the specified plan
     */
    public function updatePlan(Request $request, $id)
    {
        $plan = Plan::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',
            'max_stores' => 'nullable|integer',
            'unlimited_stores' => 'nullable|boolean',
            'qc_level' => 'required|in:basic,standard,advanced,full,full_sop',
            'audit_trail_level' => 'required|in:none,basic,full,advanced',
            'trial_days' => 'required|integer|min:0',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'has_sop_module' => 'nullable|boolean',
            'has_store_switcher' => 'nullable|boolean',
            'has_all_stores_view' => 'nullable|boolean',
            'features' => 'nullable|array',
        ]);
        
        // Handle unlimited stores
        $maxStores = $request->has('unlimited_stores') && $request->input('unlimited_stores') 
            ? 'unlimited' 
            : (int) $request->input('max_stores', 1);

        // Build features array
        $features = [];
        if ($request->has('features')) {
            foreach ($request->input('features') as $key => $value) {
                // Handle unlimited checkboxes
                if ($key === 'unlimited_users') {
                    $features['max_users'] = $value ? 'unlimited' : (int) $request->input('features.max_users', 5);
                }
                elseif ($key === 'unlimited_products') {
                    $features['max_products'] = $value ? 'unlimited' : (int) $request->input('features.max_products', 100);
                }
                elseif ($key === 'unlimited_orders') {
                    $features['max_orders_per_month'] = $value ? 'unlimited' : (int) $request->input('features.max_orders_per_month', 500);
                }
                // Skip the numeric values if unlimited is checked
                elseif (in_array($key, ['max_users', 'max_products', 'max_orders_per_month'])) {
                    // These are handled by the unlimited checkboxes above
                    continue;
                }
                // Handle customer_support as string
                elseif ($key === 'customer_support') {
                    $features[$key] = $value;
                }
                // Handle boolean features
                else {
                    $features[$key] = (bool) $value;
                }
            }
        }
        
        // Ensure numeric limits are set if not unlimited
        if (!isset($features['max_users'])) {
            $features['max_users'] = (int) $request->input('features.max_users', 5);
        }
        if (!isset($features['max_products'])) {
            $features['max_products'] = (int) $request->input('features.max_products', 100);
        }
        if (!isset($features['max_orders_per_month'])) {
            $features['max_orders_per_month'] = (int) $request->input('features.max_orders_per_month', 500);
        }
        
        // Always enable POS System
        $features['pos_system'] = true;

        $plan->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'annual_price' => $validated['annual_price'],
            'max_stores' => $maxStores,
            'qc_level' => $validated['qc_level'],
            'audit_trail_level' => $validated['audit_trail_level'],
            'trial_days' => $validated['trial_days'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $validated['is_active'],
            'has_sop_module' => $request->has('has_sop_module'),
            'has_store_switcher' => $request->has('has_store_switcher'),
            'has_all_stores_view' => $request->has('has_all_stores_view'),
            'features' => $features,
        ]);

        return redirect()->route('superadmin.plans')
            ->with('success', 'Plan updated successfully!');
    }

    /**
     * Show the form for creating a new plan
     */
    public function createPlan()
    {
        return view('superadmin.plans-create');
    }

    /**
     * Store a newly created plan
     */
    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:plans,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'annual_price' => 'nullable|numeric|min:0',
            'max_stores' => 'required|integer',
            'qc_level' => 'required|in:basic,standard,advanced',
            'audit_trail_level' => 'required|in:none,basic,full',
            'trial_days' => 'required|integer|min:0',
            'sort_order' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
        ]);

        // Build features array
        $features = [];
        if ($request->has('features')) {
            foreach ($request->input('features') as $key => $value) {
                $features[$key] = (bool) $value;
            }
        }

        Plan::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'annual_price' => $validated['annual_price'],
            'max_stores' => $validated['max_stores'],
            'qc_level' => $validated['qc_level'],
            'audit_trail_level' => $validated['audit_trail_level'],
            'trial_days' => $validated['trial_days'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $validated['is_active'],
            'has_sop_module' => $request->has('has_sop_module'),
            'has_store_switcher' => $request->has('has_store_switcher'),
            'has_all_stores_view' => $request->has('has_all_stores_view'),
            'features' => $features,
        ]);

        return redirect()->route('superadmin.plans')
            ->with('success', 'Plan created successfully!');
    }

    // ========== BUSINESS MANAGEMENT ==========
    
    public function businesses()
    {
        $businesses = Business::with(['owner', 'stores', 'subscriptions'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total' => Business::count(),
            'active' => Business::where('status', 'active')->count(),
            'trial' => Business::where('status', 'trial')->count(),
            'suspended' => Business::where('status', 'suspended')->count(),
        ];
        
        return view('superadmin.businesses.index', compact('businesses', 'stats'));
    }

    public function businessDetails($id)
    {
        $business = Business::with(['owner', 'stores', 'users', 'subscriptions.plan'])
            ->findOrFail($id);
        
        $usage = \App\Services\UsageTracker::getUsageSummary($business);
        
        return view('superadmin.businesses.show', compact('business', 'usage'));
    }

    public function suspendBusiness($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => 'suspended']);
        
        return redirect()->back()->with('success', 'Business suspended successfully.');
    }

    public function activateBusiness($id)
    {
        $business = Business::findOrFail($id);
        $business->update(['status' => 'active']);
        
        return redirect()->back()->with('success', 'Business activated successfully.');
    }

    // ========== FEATURE GATING ==========
    
    public function features()
    {
        $plans = Plan::with('features')->get();
        $allFeatures = [
            'laundry_qc' => 'Quality Control Module',
            'pos_system' => 'POS System',
            'machine_tracking' => 'Machine Usage Tracking',
            'sop_module' => 'SOP Management',
            'audit_trail' => 'Audit Trail',
            'store_switcher' => 'Store Switcher',
            'all_stores_view' => 'All Stores View',
            'advanced_reports' => 'Advanced Reports',
            'api_access' => 'API Access',
            'custom_branding' => 'Custom Branding',
        ];
        
        return view('superadmin.features.index', compact('plans', 'allFeatures'));
    }

    public function featureLogs()
    {
        $logs = \App\Models\FeatureAccessLog::with(['user', 'business'])
            ->latest()
            ->paginate(50);
        
        return view('superadmin.features.logs', compact('logs'));
    }

    // ========== SAAS BILLING ==========
    
    public function invoices()
    {
        $invoices = \App\Models\SaasInvoice::with(['business', 'subscription.plan'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total' => \App\Models\SaasInvoice::sum('total_amount'),
            'paid' => \App\Models\SaasInvoice::where('status', 'paid')->sum('total_amount'),
            'overdue' => \App\Models\SaasInvoice::where('status', 'overdue')->count(),
            'pending' => \App\Models\SaasInvoice::whereIn('status', ['draft', 'sent'])->count(),
        ];
        
        return view('superadmin.invoices.index', compact('invoices', 'stats'));
    }

    public function invoiceDetails($id)
    {
        $invoice = \App\Models\SaasInvoice::with(['business', 'subscription.plan'])
            ->findOrFail($id);
        
        return view('superadmin.invoices.show', compact('invoice'));
    }

    public function gracePeriods()
    {
        $gracePeriods = \App\Models\GracePeriod::with(['business', 'subscription'])
            ->latest()
            ->paginate(20);
        
        $active = \App\Models\GracePeriod::where('status', 'active')->count();
        $expired = \App\Models\GracePeriod::where('status', 'expired')->count();
        
        return view('superadmin.grace-periods', compact('gracePeriods', 'active', 'expired'));
    }

    // ========== USER & IDENTITY ==========
    
    public function users()
    {
        $users = User::with(['business', 'roles'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total' => User::count(),
            'superadmins' => User::whereHas('roles', fn($q) => $q->where('name', 'super_admin'))->count(),
            'owners' => User::whereHas('roles', fn($q) => $q->where('name', 'business_owner'))->count(),
            'active' => User::where('is_active', 1)->count(),
        ];
        
        return view('superadmin.users.index', compact('users', 'stats'));
    }

    public function userDetails($id)
    {
        $user = User::with(['business', 'roles', 'stores'])->findOrFail($id);
        
        return view('superadmin.users.show', compact('user'));
    }

    public function userProfiles()
    {
        $profiles = User::with(['business', 'roles'])
            ->whereHas('roles', fn($q) => $q->where('name', 'business_owner'))
            ->latest()
            ->paginate(20);
        
        return view('superadmin.user-profiles', compact('profiles'));
    }

    public function securitySettings()
    {
        // Global security settings
        $settings = [
            'mfa_enabled' => false, // To be implemented
            'password_min_length' => 8,
            'session_timeout' => 120,
            'max_login_attempts' => 5,
        ];
        
        return view('superadmin.security-settings', compact('settings'));
    }

    // ========== ROLES & PERMISSIONS ==========
    
    public function rolesPermissions()
    {
        $roles = \App\Models\Role::with('permissions')->get();
        
        return view('superadmin.roles-permissions', compact('roles'));
    }

    public function storeRoleMapping()
    {
        $stores = \App\Models\Store::with(['business', 'users.roles'])->get();
        
        return view('superadmin.store-role-mapping', compact('stores'));
    }

    // ========== STORE CONTAINERS ==========
    
    public function storeContainers()
    {
        $stores = \App\Models\Store::with(['business'])
            ->latest()
            ->paginate(20);
        
        $stats = [
            'total' => \App\Models\Store::count(),
            'active' => \App\Models\Store::where('status', 'active')->count(),
            'paused' => \App\Models\Store::where('status', 'paused')->count(),
        ];
        
        return view('superadmin.store-containers.index', compact('stores', 'stats'));
    }

    public function storeContainerDetails($id)
    {
        $store = \App\Models\Store::with(['business', 'users'])->findOrFail($id);
        
        return view('superadmin.store-containers.show', compact('store'));
    }

    // ========== USAGE & LIMITS ==========
    
    public function usageLimits()
    {
        $businesses = Business::with(['subscriptions.plan'])->get();
        
        $usageData = $businesses->map(function($business) {
            return [
                'business' => $business,
                'usage' => \App\Services\UsageTracker::getUsageSummary($business),
            ];
        });
        
        return view('superadmin.usage-limits.index', compact('usageData'));
    }

    public function businessUsage($businessId)
    {
        $business = Business::with(['subscriptions.plan'])->findOrFail($businessId);
        $usage = \App\Services\UsageTracker::getUsageSummary($business);
        
        return view('superadmin.usage-limits.business', compact('business', 'usage'));
    }

    // ========== SAAS CONFIGURATION ==========
    
    public function brandingSettings()
    {
        $settings = [
            'app_name' => config('app.name', 'RAPY'),
            'logo_url' => asset('assets/img/logo.png'),
            'primary_color' => '#6C5DD3',
            'secondary_color' => '#1E293B',
        ];
        
        return view('superadmin.settings.branding', compact('settings'));
    }

    public function updateBranding(Request $request)
    {
        // To be implemented with settings storage
        return redirect()->back()->with('success', 'Branding updated successfully.');
    }

    public function currencySettings()
    {
        $currencies = ['MYR', 'USD', 'SGD', 'EUR', 'GBP'];
        $current = 'MYR';
        
        return view('superadmin.settings.currency', compact('currencies', 'current'));
    }

    public function updateCurrency(Request $request)
    {
        // To be implemented
        return redirect()->back()->with('success', 'Currency updated successfully.');
    }

    public function taxSettings()
    {
        $settings = [
            'default_tax_rate' => 6, // 6% SST for Malaysia
            'tax_name' => 'SST',
        ];
        
        return view('superadmin.settings.tax', compact('settings'));
    }

    public function updateTax(Request $request)
    {
        // To be implemented
        return redirect()->back()->with('success', 'Tax settings updated successfully.');
    }

    public function timezoneSettings()
    {
        $timezones = \DateTimeZone::listIdentifiers();
        $current = config('app.timezone', 'Asia/Kuala_Lumpur');
        
        return view('superadmin.settings.timezone', compact('timezones', 'current'));
    }

    public function updateTimezone(Request $request)
    {
        // To be implemented
        return redirect()->back()->with('success', 'Timezone updated successfully.');
    }

    // ========== SUPPORT & LOGS ==========
    
    public function supportTickets()
    {
        // Placeholder for support ticket system
        $tickets = collect([]); // To be implemented
        
        return view('superadmin.support.tickets', compact('tickets'));
    }

    public function systemLogs()
    {
        $logs = \App\Models\ActivityLog::latest()->paginate(50);
        
        return view('superadmin.logs.system', compact('logs'));
    }

    public function errorLogs()
    {
        // Read Laravel log file
        $logPath = storage_path('logs/laravel.log');
        $logs = file_exists($logPath) ? array_slice(file($logPath), -100) : [];
        
        return view('superadmin.logs.error', compact('logs'));
    }

    public function impersonationHistory()
    {
        $history = \App\Models\ActivityLog::where('action', 'impersonate')
            ->latest()
            ->paginate(50);
        
        return view('superadmin.impersonation-history', compact('history'));
    }
}

