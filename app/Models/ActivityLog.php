<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'store_id',
        'store_name',
        'action',
        'action_label',
        'model_type',
        'model_name',
        'model_id',
        'model_identifier',
        'description',
        'old_values',
        'new_values',
        'changed_fields',
        'module',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'metadata',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changed_fields' => 'array',
        'metadata' => 'array',
    ];

    // Action constants
    const ACTION_CREATE = 'create';
    const ACTION_READ = 'read';
    const ACTION_UPDATE = 'update';
    const ACTION_DELETE = 'delete';
    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';
    const ACTION_EXPORT = 'export';
    const ACTION_IMPORT = 'import';
    const ACTION_OTHER = 'other';

    const ACTION_LABELS = [
        self::ACTION_CREATE => 'Created',
        self::ACTION_READ => 'Viewed',
        self::ACTION_UPDATE => 'Updated',
        self::ACTION_DELETE => 'Deleted',
        self::ACTION_LOGIN => 'Logged In',
        self::ACTION_LOGOUT => 'Logged Out',
        self::ACTION_EXPORT => 'Exported',
        self::ACTION_IMPORT => 'Imported',
        self::ACTION_OTHER => 'Action',
    ];

    const ACTION_COLORS = [
        self::ACTION_CREATE => 'success',
        self::ACTION_READ => 'info',
        self::ACTION_UPDATE => 'warning',
        self::ACTION_DELETE => 'danger',
        self::ACTION_LOGIN => 'primary',
        self::ACTION_LOGOUT => 'secondary',
        self::ACTION_EXPORT => 'info',
        self::ACTION_IMPORT => 'info',
        self::ACTION_OTHER => 'dark',
    ];

    const ACTION_ICONS = [
        self::ACTION_CREATE => 'plus-circle',
        self::ACTION_READ => 'eye',
        self::ACTION_UPDATE => 'edit',
        self::ACTION_DELETE => 'trash-2',
        self::ACTION_LOGIN => 'log-in',
        self::ACTION_LOGOUT => 'log-out',
        self::ACTION_EXPORT => 'download',
        self::ACTION_IMPORT => 'upload',
        self::ACTION_OTHER => 'activity',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Get the subject model
    public function subject()
    {
        if ($this->model_type && $this->model_id && class_exists($this->model_type)) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    // Get action badge
    public function getActionBadge()
    {
        $color = self::ACTION_COLORS[$this->action] ?? 'secondary';
        $label = self::ACTION_LABELS[$this->action] ?? ucfirst($this->action);
        $icon = self::ACTION_ICONS[$this->action] ?? 'activity';
        
        return '<span class="badge bg-' . $color . '"><i data-feather="' . $icon . '" style="width:12px;height:12px;"></i> ' . $label . '</span>';
    }

    // Parse user agent to get browser/platform info
    public static function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $platform = 'Unknown';

        // Detect browser
        if (preg_match('/MSIE/i', $userAgent)) $browser = 'Internet Explorer';
        elseif (preg_match('/Firefox/i', $userAgent)) $browser = 'Firefox';
        elseif (preg_match('/Chrome/i', $userAgent)) $browser = 'Chrome';
        elseif (preg_match('/Safari/i', $userAgent)) $browser = 'Safari';
        elseif (preg_match('/Opera/i', $userAgent)) $browser = 'Opera';
        elseif (preg_match('/Edge/i', $userAgent)) $browser = 'Edge';

        // Detect platform
        if (preg_match('/Windows/i', $userAgent)) $platform = 'Windows';
        elseif (preg_match('/Macintosh|Mac OS X/i', $userAgent)) $platform = 'Mac';
        elseif (preg_match('/Linux/i', $userAgent)) $platform = 'Linux';
        elseif (preg_match('/Android/i', $userAgent)) $platform = 'Android';
        elseif (preg_match('/iPhone|iPad/i', $userAgent)) $platform = 'iOS';

        return ['browser' => $browser, 'platform' => $platform];
    }

    // Scopes for querying
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForStore($query, $storeId)
    {
        return $query->where('store_id', $storeId);
    }

    public function scopeForStores($query, array $storeIds)
    {
        return $query->whereIn('store_id', $storeIds);
    }

    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        return $query;
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeBetweenDates($query, $from, $to)
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}




