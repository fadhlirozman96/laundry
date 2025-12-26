<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'shift_id',
        'date',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'late_minutes',
        'overtime_minutes',
        'total_hours',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime',
        'clock_out' => 'datetime',
        'break_start' => 'datetime',
        'break_end' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function getProductionHoursAttribute()
    {
        if (!$this->clock_in || !$this->clock_out) return '0h 0m';
        
        $totalMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        $breakMinutes = 0;
        
        if ($this->break_start && $this->break_end) {
            $breakMinutes = $this->break_start->diffInMinutes($this->break_end);
        }
        
        $workMinutes = $totalMinutes - $breakMinutes;
        $hours = floor($workMinutes / 60);
        $minutes = $workMinutes % 60;
        
        return "{$hours}h {$minutes}m";
    }

    public function getOvertimeDisplayAttribute()
    {
        $hours = floor($this->overtime_minutes / 60);
        $minutes = $this->overtime_minutes % 60;
        return "{$hours}h {$minutes}m";
    }

    public function getTotalHoursDisplayAttribute()
    {
        $hours = floor($this->total_hours * 60 / 60);
        $minutes = ($this->total_hours * 60) % 60;
        return "{$hours}h {$minutes}m";
    }
}




