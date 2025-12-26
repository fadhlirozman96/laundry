<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    /**
     * Log a custom activity
     */
    public static function log(
        string $action,
        string $description,
        $model = null,
        array $oldValues = null,
        array $newValues = null,
        string $module = 'general',
        array $metadata = null
    ) {
        $user = Auth::user();
        $storeId = session('store_id');
        $store = $storeId ? Store::find($storeId) : null;

        $userAgent = Request::header('User-Agent');
        $agentInfo = ActivityLog::parseUserAgent($userAgent ?? '');

        $changedFields = null;
        if ($oldValues && $newValues) {
            $changedFields = array_keys(array_diff_assoc($newValues, $oldValues));
        }

        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'user_role' => $user ? self::getUserRoleName($user) : 'System',
            'store_id' => $model?->store_id ?? $storeId,
            'store_name' => $model?->store?->name ?? $store?->name,
            'action' => $action,
            'action_label' => ActivityLog::ACTION_LABELS[$action] ?? ucfirst($action),
            'model_type' => $model ? get_class($model) : null,
            'model_name' => $model ? class_basename($model) : null,
            'model_id' => $model?->id,
            'model_identifier' => self::getModelIdentifier($model),
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changed_fields' => $changedFields,
            'module' => $module,
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'browser' => $agentInfo['browser'],
            'platform' => $agentInfo['platform'],
            'metadata' => $metadata,
        ]);
    }

    /**
     * Log a login event
     */
    public static function logLogin($user)
    {
        $userAgent = Request::header('User-Agent');
        $agentInfo = ActivityLog::parseUserAgent($userAgent ?? '');

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => self::getUserRoleName($user),
            'action' => ActivityLog::ACTION_LOGIN,
            'action_label' => 'Logged In',
            'description' => $user->name . ' logged in',
            'module' => 'auth',
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'browser' => $agentInfo['browser'],
            'platform' => $agentInfo['platform'],
        ]);
    }

    /**
     * Log a logout event
     */
    public static function logLogout($user)
    {
        $userAgent = Request::header('User-Agent');
        $agentInfo = ActivityLog::parseUserAgent($userAgent ?? '');

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => self::getUserRoleName($user),
            'action' => ActivityLog::ACTION_LOGOUT,
            'action_label' => 'Logged Out',
            'description' => $user->name . ' logged out',
            'module' => 'auth',
            'ip_address' => Request::ip(),
            'user_agent' => $userAgent,
            'browser' => $agentInfo['browser'],
            'platform' => $agentInfo['platform'],
        ]);
    }

    /**
     * Log a view/read event
     */
    public static function logView($model, string $description = null)
    {
        return self::log(
            ActivityLog::ACTION_READ,
            $description ?? 'Viewed ' . class_basename($model),
            $model,
            null,
            null,
            self::guessModule($model)
        );
    }

    /**
     * Log an export event
     */
    public static function logExport(string $exportType, string $module = 'general', array $metadata = null)
    {
        return self::log(
            ActivityLog::ACTION_EXPORT,
            'Exported ' . $exportType,
            null,
            null,
            null,
            $module,
            $metadata
        );
    }

    /**
     * Log status change specifically
     */
    public static function logStatusChange($model, string $oldStatus, string $newStatus, string $module = null)
    {
        return self::log(
            ActivityLog::ACTION_UPDATE,
            'Status changed from "' . $oldStatus . '" to "' . $newStatus . '"',
            $model,
            ['status' => $oldStatus],
            ['status' => $newStatus],
            $module ?? self::guessModule($model)
        );
    }

    /**
     * Get model identifier
     */
    protected static function getModelIdentifier($model)
    {
        if (!$model) return null;

        // Try common identifier fields
        if (isset($model->order_number)) return $model->order_number;
        if (isset($model->invoice_number)) return $model->invoice_number;
        if (isset($model->reference_number)) return $model->reference_number;
        if (isset($model->code)) return $model->code;
        if (isset($model->name)) return $model->name;
        if (isset($model->title)) return $model->title;
        if (isset($model->email)) return $model->email;

        return $model->id;
    }

    /**
     * Get user's role name
     */
    protected static function getUserRoleName($user)
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

    /**
     * Guess module from model class
     */
    protected static function guessModule($model)
    {
        if (!$model) return 'general';
        
        $class = class_basename($model);
        
        $moduleMap = [
            'LaundryOrder' => 'laundry',
            'LaundryOrderItem' => 'laundry',
            'QualityCheck' => 'laundry',
            'Machine' => 'laundry',
            'MachineUsageLog' => 'laundry',
            'Order' => 'pos',
            'Invoice' => 'invoice',
            'Quotation' => 'quotation',
            'Product' => 'inventory',
            'Customer' => 'customers',
            'Employee' => 'hrm',
            'Payroll' => 'hrm',
            'Attendance' => 'hrm',
            'Leave' => 'hrm',
            'Expense' => 'expense',
            'User' => 'users',
            'Store' => 'stores',
        ];

        return $moduleMap[$class] ?? 'general';
    }
}




