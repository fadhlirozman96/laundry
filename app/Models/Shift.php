<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'start_time',
        'end_time',
        'break_duration',
        'week_off',
        'is_active',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'week_off' => 'array',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'shift_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getWeekOffDisplayAttribute()
    {
        if (!$this->week_off) return '-';
        return implode(', ', $this->week_off);
    }

    public function getTimeRangeAttribute()
    {
        return date('h:i A', strtotime($this->start_time)) . ' - ' . date('h:i A', strtotime($this->end_time));
    }
}

