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
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->status === 'active' && (!$this->ends_at || $this->ends_at->isFuture());
    }

    /**
     * Check if subscription is on trial
     */
    public function onTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired()
    {
        return $this->status === 'expired' || ($this->ends_at && $this->ends_at->isPast());
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

