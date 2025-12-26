<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrderStatusLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_order_id',
        'user_id',
        'from_status',
        'to_status',
        'notes',
    ];

    public function laundryOrder()
    {
        return $this->belongsTo(LaundryOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}




