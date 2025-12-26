<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'feature_key',
        'feature_name',
        'feature_description',
        'is_enabled',
        'usage_limit',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}


