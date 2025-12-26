<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Plan, Subscription, SubscriptionPayment, Notification};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BusinessOwnerController extends Controller
{
    /**
     * Show subscription dashboard
     */
    public function subscription()
    {
        $user = Auth::user();
        
        // Get current subscription
        $currentSubscription = $user->subscriptions()
            ->with('plan')
            ->whereIn('status', ['active', 'trial', 'grace'])
            ->latest()
            ->first();
        
        // Get all subscriptions history
        $subscriptionHistory = $user->subscriptions()
            ->with('plan')
            ->latest()
            ->get();
        
        // Get payment history
        $paymentHistory = SubscriptionPayment::with(['subscription.plan'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);
        
        // Get available plans for upgrade/downgrade
        $availablePlans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();
        
        // Calculate usage
        $usage = [
            'stores' => [
                'used' => $user->ownedStores->count(),
                'allowed' => $user->currentPlan->max_stores ?? 0,
            ],
            'users' => [
                'used' => $user->stores->sum(fn($store) => $store->users->count()),
                'allowed' => $user->currentPlan ? json_decode($user->currentPlan->features, true)['max_users'] ?? 0 : 0,
            ],
            'orders_this_month' => \App\Models\Order::whereIn('store_id', $user->ownedStores->pluck('id'))
                ->whereMonth('created_at', now()->month)
                ->count(),
            'orders_allowed' => $user->currentPlan ? json_decode($user->currentPlan->features, true)['max_orders_per_month'] ?? 0 : 0,
        ];
        
        return view('business-owner.subscription', compact(
            'currentSubscription',
            'subscriptionHistory',
            'paymentHistory',
            'availablePlans',
            'usage'
        ));
    }
    
    /**
     * Show checkout page for plan selection/upgrade
     */
    public function checkout(Request $request)
    {
        $planId = $request->get('plan_id');
        $plan = Plan::findOrFail($planId);
        $user = Auth::user();
        
        $currentSubscription = $user->subscriptions()
            ->whereIn('status', ['active', 'trial', 'grace'])
            ->latest()
            ->first();
        
        return view('business-owner.checkout', compact('plan', 'currentSubscription'));
    }
    
    /**
     * Process payment (dummy for now)
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,annual',
            'payment_method' => 'required|in:credit_card,fpx,ewallet',
        ]);
        
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $plan = Plan::findOrFail($request->plan_id);
            
            // Determine if this is upgrade/downgrade or new subscription
            $currentSubscription = $user->subscriptions()
                ->whereIn('status', ['active', 'trial', 'grace'])
                ->latest()
                ->first();
            
            $isUpgrade = $currentSubscription && $currentSubscription->plan_id != $plan->id;
            
            // Calculate dates
            $startDate = now();
            $billingCycle = $request->billing_cycle;
            
            if ($billingCycle === 'annual') {
                $endDate = $startDate->copy()->addYear()->subDay()->endOfDay();
            } else {
                $endDate = $startDate->copy()->addMonth()->subDay()->endOfDay();
            }
            
            $nextRenewalDate = $endDate->copy()->addDay()->startOfDay();
            $graceEndDate = $nextRenewalDate->copy()->addDays(7)->endOfDay();
            
            // Calculate amount
            $amount = $billingCycle === 'annual' ? $plan->annual_price : $plan->price;
            
            // Expire old subscriptions
            if ($currentSubscription) {
                $currentSubscription->update([
                    'status' => 'expired',
                    'canceled_at' => now()
                ]);
            }
            
            // Update user's current plan
            $user->current_plan_id = $plan->id;
            $user->save();
            
            // Create new subscription
            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => $startDate,
                'ends_at' => $endDate,
                'amount' => $amount,
                'billing_cycle' => $billingCycle,
                'next_billing_date' => $nextRenewalDate,
                'metadata' => json_encode([
                    'grace_end_date' => $graceEndDate->format('Y-m-d H:i:s'),
                    'payment_method' => $request->payment_method,
                    'purchased_at' => now()->format('Y-m-d H:i:s'),
                ]),
            ]);
            
            // Create payment record (DUMMY)
            $payment = SubscriptionPayment::create([
                'subscription_id' => $subscription->id,
                'user_id' => $user->id,
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'amount' => $amount,
                'currency' => 'MYR',
                'payment_method' => $request->payment_method,
                'status' => 'completed',
                'paid_at' => now(),
                'payment_details' => json_encode([
                    'card_last4' => '4242',
                    'card_brand' => 'Visa',
                    'dummy_payment' => true,
                ]),
            ]);
            
            // Create notification
            Notification::create([
                'user_id' => $user->id,
                'type' => $isUpgrade ? 'plan_upgraded' : 'payment_success',
                'title' => $isUpgrade ? 'Plan Upgraded Successfully!' : 'Payment Successful!',
                'message' => "Your subscription to {$plan->name} plan has been activated. Next renewal on " . $nextRenewalDate->format('d M Y'),
                'action_url' => route('business-owner.subscription'),
                'action_text' => 'View Subscription',
                'priority' => 'normal',
            ]);
            
            DB::commit();
            
            return redirect()->route('business-owner.subscription')
                ->with('success', 'Payment successful! Your ' . $plan->name . ' plan is now active.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment processing failed: ' . $e->getMessage());
            return back()->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Show notifications
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest()->paginate(20);
        
        return view('business-owner.notifications', compact('notifications'));
    }
    
    /**
     * Mark notification as read
     */
    public function markNotificationRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return back()->with('success', 'All notifications marked as read');
    }
}

