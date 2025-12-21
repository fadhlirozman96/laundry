<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'head_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }
}


