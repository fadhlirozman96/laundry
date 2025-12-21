<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'date',
        'end_date',
        'description',
        'is_active',
    ];

    protected $casts = [
        'date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function getDurationAttribute()
    {
        if (!$this->end_date || $this->date->equalTo($this->end_date)) {
            return '1 Day';
        }
        $days = $this->date->diffInDays($this->end_date) + 1;
        return $days . ' Days';
    }
}


