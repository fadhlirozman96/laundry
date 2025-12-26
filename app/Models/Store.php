<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'store_user')->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function theme()
    {
        return $this->hasOne(StoreTheme::class);
    }
}

