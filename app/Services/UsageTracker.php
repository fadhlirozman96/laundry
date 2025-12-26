<?php

namespace App\Services;

use App\Models\Business;
use App\Models\UsageTracking;
use App\Models\LimitAlert;
use Carbon\Carbon;

class UsageTracker
{
    /**
     * Track store count
     */
    public static function trackStores(Business $business): void
    {
        $current = $business->stores()->count();
        $limit = $business->max_stores;
        
        self::updateTracking($business->id, 'stores', $current, $limit);
    }

    /**
     * Track users count
     */
    public static function trackUsers(Business $business): void
    {
        $current = $business->users()->count();
        $limit = $business->max_users_per_store * $business->max_stores;
        
        self::updateTracking($business->id, 'users', $current, $limit);
    }

    /**
     * Track orders (optional - for tier-based pricing)
     */
    public static function trackOrders(Business $business): void
    {
        $current = \App\Models\Order::whereIn('store_id', $business->stores->pluck('id'))->count();
        $limit = 0; // Unlimited by default, or set from plan
        
        self::updateTracking($business->id, 'orders', $current, $limit);
    }

    /**
     * Update or create tracking record
     */
    protected static function updateTracking(int $businessId, string $metricKey, int $current, int $limit): void
    {
        $periodStart = Carbon::now()->startOfMonth();
        $periodEnd = Carbon::now()->endOfMonth();
        
        $percentage = $limit > 0 ? round(($current / $limit) * 100) : 0;

        UsageTracking::updateOrCreate(
            [
                'business_id' => $businessId,
                'metric_key' => $metricKey,
                'period_start' => $periodStart,
            ],
            [
                'current_value' => $current,
                'limit_value' => $limit,
                'percentage_used' => $percentage,
                'period_end' => $periodEnd,
            ]
        );

        // Check for alerts
        self::checkLimitAlerts($businessId, $metricKey, $percentage, $current, $limit);
    }

    /**
     * Check and send limit alerts
     */
    protected static function checkLimitAlerts(int $businessId, string $metricKey, int $percentage, int $current, int $limit): void
    {
        $thresholds = [
            80 => ['type' => 'warning', 'message' => "You have used {$percentage}% of your {$metricKey} limit ({$current}/{$limit})."],
            90 => ['type' => 'critical', 'message' => "Critical: You are approaching your {$metricKey} limit at {$percentage}% ({$current}/{$limit})."],
            100 => ['type' => 'exceeded', 'message' => "Limit reached! You have reached your {$metricKey} limit ({$current}/{$limit}). Upgrade to add more."],
        ];

        foreach ($thresholds as $threshold => $config) {
            if ($percentage >= $threshold) {
                LimitAlert::firstOrCreate(
                    [
                        'business_id' => $businessId,
                        'metric_key' => $metricKey,
                        'threshold_percentage' => $threshold,
                        'is_sent' => false,
                    ],
                    [
                        'alert_type' => $config['type'],
                        'message' => $config['message'],
                    ]
                );
            }
        }
    }

    /**
     * Get usage summary for business
     */
    public static function getUsageSummary(Business $business): array
    {
        return [
            'stores' => [
                'current' => $business->stores()->count(),
                'limit' => $business->max_stores,
                'percentage' => round(($business->stores()->count() / max(1, $business->max_stores)) * 100),
            ],
            'users' => [
                'current' => $business->users()->count(),
                'limit' => $business->max_users_per_store * $business->max_stores,
                'percentage' => round(($business->users()->count() / max(1, $business->max_users_per_store * $business->max_stores)) * 100),
            ],
        ];
    }
}


