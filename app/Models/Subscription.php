<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'plan_id',
        'status',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'canceled_at',
        'amount',
        'billing_cycle',
        'next_billing_date',
        'metadata',
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
        'starts_at' => 'date',
        'ends_at' => 'date',
        'canceled_at' => 'date',
        'amount' => 'decimal:2',
        'next_billing_date' => 'date',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan for the subscription
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    /**
     * Get payments for this subscription
     */
    public function payments()
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    /**
     * 6️⃣ Calculate status based on dates (AUTOMATIC)
     * Status should be derived from dates, not manually set
     */
    public function calculateStatus()
    {
        $now = now();
        $metadata = is_string($this->metadata) ? json_decode($this->metadata, true) : $this->metadata;
        $graceEndDate = isset($metadata['grace_end_date']) ? \Carbon\Carbon::parse($metadata['grace_end_date']) : null;
        
        // Trial
        if ($this->trial_ends_at && $now->lte($this->trial_ends_at)) {
            return 'trial';
        }
        
        // Active (today <= end_date)
        if ($now->lte($this->ends_at)) {
            return 'active';
        }
        
        // Grace Period (today > end_date AND today <= grace_end)
        if ($graceEndDate && $now->gt($this->ends_at) && $now->lte($graceEndDate)) {
            return 'grace';
        }
        
        // Expired/Suspended (today > grace_end)
        if ($graceEndDate && $now->gt($graceEndDate)) {
            return 'expired';
        }
        
        // If no grace period defined, expired after end_date
        if ($now->gt($this->ends_at)) {
            return 'expired';
        }
        
        return $this->status;
    }
    
    /**
     * Get current status (auto-calculated)
     */
    public function getCurrentStatus()
    {
        return $this->calculateStatus();
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->calculateStatus() === 'active';
    }

    /**
     * Check if subscription is on trial
     */
    public function onTrial()
    {
        return $this->calculateStatus() === 'trial';
    }
    
    /**
     * Check if subscription is in grace period
     */
    public function inGracePeriod()
    {
        return $this->calculateStatus() === 'grace';
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired()
    {
        return $this->calculateStatus() === 'expired';
    }
    
    /**
     * Get days remaining in current period
     */
    public function daysRemaining()
    {
        if (!$this->ends_at) {
            return 0;
        }
        
        $now = now();
        if ($now->gt($this->ends_at)) {
            return 0;
        }
        
        return $now->diffInDays($this->ends_at);
    }
    
    /**
     * Get grace period days remaining
     */
    public function graceDaysRemaining()
    {
        $metadata = is_string($this->metadata) ? json_decode($this->metadata, true) : $this->metadata;
        $graceEndDate = isset($metadata['grace_end_date']) ? \Carbon\Carbon::parse($metadata['grace_end_date']) : null;
        
        if (!$graceEndDate) {
            return 0;
        }
        
        $now = now();
        if ($now->gt($graceEndDate)) {
            return 0;
        }
        
        return $now->diffInDays($graceEndDate);
    }

    /**
     * Cancel the subscription
     */
    public function cancel()
    {
        $this->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }

    /**
     * Resume the subscription
     */
    public function resume()
    {
        $this->update([
            'status' => 'active',
            'canceled_at' => null,
        ]);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadge()
    {
        $badges = [
            'active' => '<span class="badge bg-success">Active</span>',
            'trial' => '<span class="badge bg-info">Trial</span>',
            'canceled' => '<span class="badge bg-warning">Canceled</span>',
            'expired' => '<span class="badge bg-danger">Expired</span>',
            'suspended' => '<span class="badge bg-secondary">Suspended</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}


