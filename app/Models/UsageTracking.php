<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsageTracking extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'metric_key',
        'current_value',
        'limit_value',
        'percentage_used',
        'period_start',
        'period_end',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function isNearLimit($threshold = 80)
    {
        return $this->percentage_used >= $threshold;
    }

    public function hasExceededLimit()
    {
        return $this->current_value >= $this->limit_value;
    }

    public function getRemainingQuota()
    {
        return max(0, $this->limit_value - $this->current_value);
    }
}

