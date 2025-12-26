<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'annual_price',
        'max_stores',
        'qc_level',
        'has_sop_module',
        'audit_trail_level',
        'has_store_switcher',
        'has_all_stores_view',
        'features',
        'is_active',
        'trial_days',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'annual_price' => 'decimal:2',
        // max_stores can be 'unlimited' or numeric, so no cast
        'has_sop_module' => 'boolean',
        'has_store_switcher' => 'boolean',
        'has_all_stores_view' => 'boolean',
        'features' => 'array',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
        'sort_order' => 'integer',
    ];

    /**
     * Get subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get users on this plan
     */
    public function users()
    {
        return $this->hasMany(User::class, 'current_plan_id');
    }

    /**
     * Check if plan allows unlimited stores
     */
    public function hasUnlimitedStores()
    {
        return $this->max_stores === -1;
    }

    /**
     * Get feature value
     */
    public function getFeature($key, $default = null)
    {
        return data_get($this->features, $key, $default);
    }

    /**
     * Check if feature is available
     */
    public function hasFeature($feature)
    {
        return isset($this->features[$feature]) && $this->features[$feature];
    }
}

