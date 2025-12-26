<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureAccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_id',
        'feature_key',
        'action',
        'reason',
        'accessed_at',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}


