<?php

namespace App\Services;

use App\Models\User;
use App\Models\Business;
use App\Models\FeatureAccessLog;
use Illuminate\Support\Facades\Cache;

class FeatureGate
{
    /**
     * Check if user has access to a feature
     */
    public static function check(User $user, string $featureKey): bool
    {
        // Superadmin always has access
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Get user's business
        $business = self::getUserBusiness($user);
        if (!$business) {
            self::logAccess($user, null, $featureKey, 'denied', 'No business associated');
            return false;
        }

        // Check if business is active
        if (!$business->isActive() && !$business->isOnTrial()) {
            self::logAccess($user, $business->id, $featureKey, 'denied', 'Business suspended or inactive');
            return false;
        }

        // Get business subscription
        $subscription = $business->subscription;
        if (!$subscription) {
            self::logAccess($user, $business->id, $featureKey, 'denied', 'No active subscription');
            return false;
        }

        // Check if plan has the feature
        $hasFeature = self::planHasFeature($subscription->plan_id, $featureKey);
        
        if ($hasFeature) {
            self::logAccess($user, $business->id, $featureKey, 'allowed', null);
            return true;
        } else {
            self::logAccess($user, $business->id, $featureKey, 'denied', 'Feature not in plan');
            return false;
        }
    }

    /**
     * Check feature with exception throwing
     */
    public static function authorize(User $user, string $featureKey): void
    {
        if (!self::check($user, $featureKey)) {
            abort(403, 'This feature is not available in your current plan. Please upgrade.');
        }
    }

    /**
     * Get features for a plan
     */
    public static function getPlanFeatures(int $planId): array
    {
        return Cache::remember("plan_features_{$planId}", 3600, function () use ($planId) {
            return \App\Models\PlanFeature::where('plan_id', $planId)
                ->where('is_enabled', true)
                ->pluck('feature_key')
                ->toArray();
        });
    }

    /**
     * Check if plan has feature
     */
    protected static function planHasFeature(int $planId, string $featureKey): bool
    {
        $features = self::getPlanFeatures($planId);
        return in_array($featureKey, $features);
    }

    /**
     * Get user's business
     */
    protected static function getUserBusiness(User $user): ?Business
    {
        // Try to get business from user's owned stores
        $store = $user->getAccessibleStores()->first();
        return $store ? $store->business : null;
    }

    /**
     * Log feature access attempt
     */
    protected static function logAccess(User $user, ?int $businessId, string $featureKey, string $action, ?string $reason): void
    {
        try {
            FeatureAccessLog::create([
                'user_id' => $user->id,
                'business_id' => $businessId,
                'feature_key' => $featureKey,
                'action' => $action,
                'reason' => $reason,
                'accessed_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log feature access: ' . $e->getMessage());
        }
    }

    /**
     * Clear feature cache for a plan
     */
    public static function clearCache(int $planId): void
    {
        Cache::forget("plan_features_{$planId}");
    }
}


