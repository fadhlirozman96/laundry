<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Boot the trait
     */
    public static function bootLogsActivity()
    {
        // Log when creating
        static::created(function ($model) {
            $model->logActivity(ActivityLog::ACTION_CREATE, 'Created ' . $model->getActivityLogName());
        });

        // Log when updating
        static::updated(function ($model) {
            $changes = $model->getChanges();
            unset($changes['updated_at']); // Don't log updated_at changes
            
            if (!empty($changes)) {
                $model->logActivity(
                    ActivityLog::ACTION_UPDATE, 
                    'Updated ' . $model->getActivityLogName(),
                    $model->getOriginal(),
                    $model->getAttributes(),
                    array_keys($changes)
                );
            }
        });

        // Log when deleting
        static::deleted(function ($model) {
            $model->logActivity(ActivityLog::ACTION_DELETE, 'Deleted ' . $model->getActivityLogName());
        });
    }

    /**
     * Log an activity
     */
    public function logActivity($action, $description, $oldValues = null, $newValues = null, $changedFields = null)
    {
        $user = Auth::user();
        $storeId = $this->getActivityStoreId();
        $store = $storeId ? \App\Models\Store::find($storeId) : null;

        $userAgent = Request::header('User-Agent');
        $agentInfo = ActivityLog::parseUserAgent($userAgent ?? '');

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'user_role' => $user ? $this->getUserRoleName($user) : 'System',
            'store_id' => $storeId,
            'store_name' => $store?->name,
            'action' => $action,
            'action_label' => ActivityLog::ACTION_LABELS[$action] ?? ucfirst($action),
            'model_type' => get_class($this),
            'model_name' => $this->getActivityLogName(),
            'model_id' => $this->getKey(),
            'model_identifier' => $this->getActivityIdentifier(),
            'description' => $description,
            'old_values' => $oldValues ? $this->filterLoggableAttributes($oldValues) : null,
            'new_values' => $newValues ? $this->filterLoggableAttributes($newValues) : null,
            'changed_fields' => $changedFields,
            'module' => $this->getActivityModule(),
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'browser' => $agentInfo['browser'],
            'platform' => $agentInfo['platform'],
        ]);
    }

    /**
     * Get the name for activity log (override in model)
     */
    public function getActivityLogName()
    {
        return class_basename($this);
    }

    /**
     * Get the identifier for activity log (override in model)
     */
    public function getActivityIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the store ID for activity log (override in model)
     */
    public function getActivityStoreId()
    {
        if (isset($this->store_id)) {
            return $this->store_id;
        }
        return session('store_id');
    }

    /**
     * Get the module for activity log (override in model)
     */
    public function getActivityModule()
    {
        return 'general';
    }

    /**
     * Get attributes that should be logged (override in model)
     */
    public function getLoggableAttributes()
    {
        return ['*']; // Log all by default
    }

    /**
     * Get attributes that should NOT be logged
     */
    public function getExcludedAttributes()
    {
        return ['password', 'remember_token', 'api_token', 'secret'];
    }

    /**
     * Filter attributes to only loggable ones
     */
    protected function filterLoggableAttributes($attributes)
    {
        $loggable = $this->getLoggableAttributes();
        $excluded = $this->getExcludedAttributes();

        // Remove excluded attributes
        foreach ($excluded as $attr) {
            unset($attributes[$attr]);
        }

        // If logging all, return filtered
        if ($loggable === ['*']) {
            return $attributes;
        }

        // Otherwise only return specified
        return array_intersect_key($attributes, array_flip($loggable));
    }

    /**
     * Get user's role name
     */
    protected function getUserRoleName($user)
    {
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return 'Super Admin';
        }
        if (method_exists($user, 'isBusinessOwner') && $user->isBusinessOwner()) {
            return 'Business Owner';
        }
        $role = $user->roles?->first();
        return $role ? ucfirst(str_replace('_', ' ', $role->name)) : 'User';
    }
}



