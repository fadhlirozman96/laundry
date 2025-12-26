<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

