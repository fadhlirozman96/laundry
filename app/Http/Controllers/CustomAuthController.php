<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLogger;

class CustomAuthController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('signin');
    }  
      

    public function customSignin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],
        [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',

        ]

    );
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            // Log login activity
            ActivityLogger::logLogin(Auth::user());
            
            return redirect()->intended('index')
                        ->withSuccess('Signed in');
        }
         
      
        return redirect("/login")->withErrors([
            'email' => 'The provided credentials are incorrect.',
            'login' => 'Login failed! Please check your email and password.'
        ])->withInput($request->only('email'));
    }
    public function registration()
    {
        return view('register');
    }
      

    public function showRegister()
    {
        $plans = \App\Models\Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        return view('auth.register', compact('plans'));
    }

    public function customRegister(Request $request)
    {  
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'company_name' => 'required|min:3',
            'plan_id' => 'required|exists:plans,id',
            'phone' => 'nullable|string|max:20',
        ]);

        \DB::beginTransaction();
        try {
            // Get the selected plan
            $plan = \App\Models\Plan::findOrFail($request->plan_id);
            
            // Get business owner role
            $businessOwnerRole = \App\Models\Role::where('name', 'business_owner')->first();
            if (!$businessOwnerRole) {
                throw new \Exception('Business Owner role not found. Please run database seeders.');
            }
            
            // Create the business owner user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'current_plan_id' => $plan->id,
                'company_name' => $request->company_name,
            ]);

            // Assign business owner role
            $user->roles()->attach($businessOwnerRole->id);

            // Create active subscription
            $billingCycle = 'monthly';
            $endsAt = now()->addDays($plan->trial_days > 0 ? $plan->trial_days : 365);
            $nextBillingDate = $billingCycle === 'annual' ? now()->addYear() : now()->addMonth();
            
            $subscription = \App\Models\Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => $plan->price == 0 ? 'active' : 'trial', // Free plan = active, paid = trial
                'starts_at' => now(),
                'ends_at' => $endsAt,
                'trial_ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : null,
                'amount' => $plan->price,
                'billing_cycle' => $billingCycle,
                'next_billing_date' => $nextBillingDate,
            ]);

            // Create first store automatically
            $store = \App\Models\Store::create([
                'name' => $request->company_name,
                'slug' => \Illuminate\Support\Str::slug($request->company_name),
                'email' => $request->email,
                'phone' => $request->phone,
                'created_by' => $user->id,
                'is_active' => true,
            ]);

            // Assign user to their own store
            $store->users()->attach($user->id);

            \DB::commit();

            // Auto-login the user
            \Auth::login($user);
            
            return redirect()->route('index')->with('success', 'Account created successfully! Welcome to RAPY.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return back()->withInput()->with('error', 'Registration failed: ' . $e->getMessage());
        }
    }


    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'confirmpassword' => Hash::make($data['confirmpassword'])
      ]);
    }    
    

    public function dashboard()
    {
        if(Auth::check()){
            return view('index');
        }
  
        return redirect("/login")->withSuccess('You are not allowed to access');
    }
    

    public function signOut() {
        // Log logout activity before logging out
        if (Auth::check()) {
            ActivityLogger::logLogout(Auth::user());
        }
        
        Session::flush();
        Auth::logout();
  
        return redirect('/login');
    }
}
