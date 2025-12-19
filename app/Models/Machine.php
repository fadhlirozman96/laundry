<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Machine extends Model
{
    use HasFactory, LogsActivity;

    // Activity Log methods
    public function getActivityLogName() { return 'Machine'; }
    public function getActivityIdentifier() { return $this->name; }
    public function getActivityModule() { return 'laundry'; }

    protected $fillable = [
        'store_id',
        'name',
        'code',
        'type',
        'brand',
        'model',
        'capacity_kg',
        'default_cycle_minutes',
        'status',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'capacity_kg' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    const TYPE_WASHER = 'washer';
    const TYPE_DRYER = 'dryer';

    const STATUS_AVAILABLE = 'available';
    const STATUS_IN_USE = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_OUT_OF_ORDER = 'out_of_order';

    const STATUS_COLORS = [
        self::STATUS_AVAILABLE => 'success',
        self::STATUS_IN_USE => 'warning',
        self::STATUS_MAINTENANCE => 'info',
        self::STATUS_OUT_OF_ORDER => 'danger',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function usageLogs()
    {
        return $this->hasMany(MachineUsageLog::class);
    }

    public function currentUsage()
    {
        return $this->hasOne(MachineUsageLog::class)
            ->where('status', 'running')
            ->latestOfMany();
    }

    public function getStatusBadge()
    {
        $color = self::STATUS_COLORS[$this->status] ?? 'secondary';
        $label = ucfirst(str_replace('_', ' ', $this->status));
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    public function isAvailable()
    {
        return $this->status === self::STATUS_AVAILABLE && $this->is_active;
    }
}

