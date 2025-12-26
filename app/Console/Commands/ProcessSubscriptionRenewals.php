<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Subscription, SubscriptionPayment, Notification};
use Carbon\Carbon;

class ProcessSubscriptionRenewals extends Command
{
    protected $signature = 'subscriptions:process-renewals';
    protected $description = 'Process subscription renewals and handle grace periods';

    public function handle()
    {
        $this->info('Processing subscription renewals...');
        
        $today = now()->startOfDay();
        
        // Process renewals due today
        $this->processRenewals($today);
        
        // Send renewal reminders (7 days before)
        $this->sendRenewalReminders($today);
        
        // Handle grace period expiry
        $this->handleGracePeriodExpiry($today);
        
        // Update subscription statuses
        $this->updateSubscriptionStatuses();
        
        $this->info('Subscription processing completed!');
        
        return 0;
    }
    
    /**
     * Process renewals due today
     */
    protected function processRenewals($today)
    {
        $renewals = Subscription::where('next_billing_date', '<=', $today->format('Y-m-d'))
            ->where('status', 'active')
            ->with(['user', 'plan'])
            ->get();
        
        $this->info("Found {$renewals->count()} subscriptions due for renewal");
        
        foreach ($renewals as $subscription) {
            try {
                // Simulate payment processing (dummy)
                $paymentSuccess = $this->processPayment($subscription);
                
                if ($paymentSuccess) {
                    // Renew subscription
                    $this->renewSubscription($subscription);
                    $this->info("✓ Renewed subscription ID: {$subscription->id} for user: {$subscription->user->name}");
                } else {
                    // Payment failed - move to grace period
                    $this->moveToGracePeriod($subscription);
                    $this->warn("✗ Payment failed for subscription ID: {$subscription->id}");
                }
                
            } catch (\Exception $e) {
                $this->error("Error processing subscription {$subscription->id}: " . $e->getMessage());
            }
        }
    }
    
    /**
     * Process payment (dummy implementation)
     */
    protected function processPayment($subscription)
    {
        // Simulate 90% success rate
        $success = rand(1, 100) <= 90;
        
        $payment = SubscriptionPayment::create([
            'subscription_id' => $subscription->id,
            'user_id' => $subscription->user_id,
            'transaction_id' => 'AUTO-' . strtoupper(uniqid()),
            'amount' => $subscription->amount,
            'currency' => 'MYR',
            'payment_method' => 'auto_renewal',
            'status' => $success ? 'completed' : 'failed',
            'paid_at' => $success ? now() : null,
            'payment_details' => json_encode([
                'auto_renewal' => true,
                'dummy_payment' => true,
            ]),
        ]);
        
        return $success;
    }
    
    /**
     * Renew subscription for next period
     */
    protected function renewSubscription($subscription)
    {
        // Calculate new dates
        $newStartDate = Carbon::parse($subscription->ends_at)->addDay();
        
        if ($subscription->billing_cycle === 'annual') {
            $newEndDate = $newStartDate->copy()->addYear()->subDay()->endOfDay();
        } else {
            $newEndDate = $newStartDate->copy()->addMonth()->subDay()->endOfDay();
        }
        
        $nextRenewalDate = $newEndDate->copy()->addDay()->startOfDay();
        $graceEndDate = $nextRenewalDate->copy()->addDays(7)->endOfDay();
        
        // Update subscription
        $subscription->update([
            'starts_at' => $newStartDate,
            'ends_at' => $newEndDate,
            'next_billing_date' => $nextRenewalDate,
            'status' => 'active',
            'metadata' => json_encode(array_merge(
                json_decode($subscription->metadata ?? '[]', true),
                ['grace_end_date' => $graceEndDate->format('Y-m-d H:i:s')]
            )),
        ]);
        
        // Create notification
        Notification::create([
            'user_id' => $subscription->user_id,
            'type' => 'subscription_renewal',
            'title' => 'Subscription Renewed Successfully',
            'message' => "Your {$subscription->plan->name} subscription has been renewed. Next renewal on " . $nextRenewalDate->format('d M Y'),
            'action_url' => route('business-owner.subscription'),
            'action_text' => 'View Subscription',
            'priority' => 'normal',
        ]);
    }
    
    /**
     * Move subscription to grace period
     */
    protected function moveToGracePeriod($subscription)
    {
        $subscription->update(['status' => 'grace']);
        
        $metadata = json_decode($subscription->metadata ?? '[]', true);
        $graceEndDate = isset($metadata['grace_end_date']) ? Carbon::parse($metadata['grace_end_date']) : now()->addDays(7);
        
        // Create notification
        Notification::create([
            'user_id' => $subscription->user_id,
            'type' => 'payment_failed',
            'title' => 'Payment Failed - Action Required',
            'message' => "Your subscription renewal payment failed. Please update your payment method by " . $graceEndDate->format('d M Y') . " to continue service.",
            'action_url' => route('business-owner.subscription'),
            'action_text' => 'Update Payment',
            'priority' => 'urgent',
        ]);
    }
    
    /**
     * Send renewal reminders
     */
    protected function sendRenewalReminders($today)
    {
        $reminderDate = $today->copy()->addDays(7)->format('Y-m-d');
        
        $upcomingRenewals = Subscription::where('next_billing_date', $reminderDate)
            ->where('status', 'active')
            ->with(['user', 'plan'])
            ->get();
        
        $this->info("Sending {$upcomingRenewals->count()} renewal reminders");
        
        foreach ($upcomingRenewals as $subscription) {
            // Check if reminder already sent
            $alreadySent = Notification::where('user_id', $subscription->user_id)
                ->where('type', 'subscription_renewal')
                ->where('created_at', '>=', now()->subDays(8))
                ->exists();
            
            if (!$alreadySent) {
                Notification::create([
                    'user_id' => $subscription->user_id,
                    'type' => 'subscription_renewal',
                    'title' => 'Subscription Renewal Reminder',
                    'message' => "Your {$subscription->plan->name} subscription will renew in 7 days. Amount: MYR " . number_format($subscription->amount, 2),
                    'action_url' => route('business-owner.subscription'),
                    'action_text' => 'View Details',
                    'priority' => 'normal',
                ]);
            }
        }
    }
    
    /**
     * Handle grace period expiry
     */
    protected function handleGracePeriodExpiry($today)
    {
        $expiredGrace = Subscription::where('status', 'grace')
            ->with(['user', 'plan'])
            ->get()
            ->filter(function($subscription) use ($today) {
                $metadata = json_decode($subscription->metadata ?? '[]', true);
                $graceEndDate = isset($metadata['grace_end_date']) ? Carbon::parse($metadata['grace_end_date']) : null;
                
                return $graceEndDate && $graceEndDate->lt($today);
            });
        
        $this->info("Expiring {$expiredGrace->count()} subscriptions past grace period");
        
        foreach ($expiredGrace as $subscription) {
            $subscription->update([
                'status' => 'expired',
                'canceled_at' => now()
            ]);
            
            // Update user's current plan to null or free
            $subscription->user->update(['current_plan_id' => null]);
            
            // Create notification
            Notification::create([
                'user_id' => $subscription->user_id,
                'type' => 'subscription_expired',
                'title' => 'Subscription Expired',
                'message' => "Your {$subscription->plan->name} subscription has expired due to payment failure. Please renew to continue using the service.",
                'action_url' => route('business-owner.subscription'),
                'action_text' => 'Renew Now',
                'priority' => 'urgent',
            ]);
        }
    }
    
    /**
     * Update all subscription statuses based on dates
     */
    protected function updateSubscriptionStatuses()
    {
        $allSubscriptions = Subscription::whereIn('status', ['active', 'trial', 'grace'])->get();
        
        foreach ($allSubscriptions as $subscription) {
            $calculatedStatus = $subscription->calculateStatus();
            
            if ($calculatedStatus !== $subscription->status) {
                $subscription->update(['status' => $calculatedStatus]);
                $this->info("Updated subscription {$subscription->id} status to: {$calculatedStatus}");
            }
        }
    }
}

