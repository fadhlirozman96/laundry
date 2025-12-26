<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GracePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'subscription_id',
        'grace_start_date',
        'grace_end_date',
        'status',
        'reason',
    ];

    protected $casts = [
        'grace_start_date' => 'date',
        'grace_end_date' => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               now()->between($this->grace_start_date, $this->grace_end_date);
    }

    public function isExpired()
    {
        return $this->status === 'expired' || 
               now()->isAfter($this->grace_end_date);
    }
}


