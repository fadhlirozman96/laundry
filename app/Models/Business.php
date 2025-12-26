<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'business_name',
        'business_slug',
        'business_email',
        'business_phone',
        'business_address',
        'business_city',
        'business_state',
        'business_country',
        'business_postcode',
        'registration_number',
        'tax_number',
        'owner_id',
        'billing_owner_id',
        'status',
        'suspended_at',
        'suspension_reason',
        'current_subscription_id',
        'logo_path',
        'primary_color',
        'secondary_color',
        'max_stores',
        'max_users_per_store',
        'current_store_count',
        'trial_ends_at',
        'trial_used',
    ];

    protected $casts = [
        'suspended_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'trial_used' => 'boolean',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function billingOwner()
    {
        return $this->belongsTo(User::class, 'billing_owner_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'current_subscription_id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Store::class, 'business_id', 'id', 'id', 'user_id');
    }

    // Status Helpers
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    public function isOnTrial()
    {
        return $this->status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function trialExpired()
    {
        return $this->trial_ends_at && $this->trial_ends_at->isPast();
    }

    // Limit Checks
    public function canAddStore()
    {
        return $this->current_store_count < $this->max_stores;
    }

    public function getRemainingStores()
    {
        return max(0, $this->max_stores - $this->current_store_count);
    }

    public function hasReachedStoreLimit()
    {
        return $this->current_store_count >= $this->max_stores;
    }

    // Badge Helpers
    public function getStatusBadge()
    {
        return match($this->status) {
            'active' => '<span class="badge bg-success">Active</span>',
            'suspended' => '<span class="badge bg-danger">Suspended</span>',
            'cancelled' => '<span class="badge bg-secondary">Cancelled</span>',
            'trial' => '<span class="badge bg-info">Trial</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}


