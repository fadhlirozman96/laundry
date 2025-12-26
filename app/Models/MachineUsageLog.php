<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineUsageLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'order_id',
        'user_id',
        'store_id',
        'load_weight_kg',
        'items_count',
        'overload_warning',
        'started_at',
        'ended_at',
        'duration_minutes',
        'set_duration_minutes',
        'wash_type',
        'temperature',
        'spin_speed',
        'status',
        'notes',
        'issues',
    ];

    protected $casts = [
        'load_weight_kg' => 'decimal:2',
        'overload_warning' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Check if load exceeds machine capacity
    public function checkOverload()
    {
        if ($this->machine && $this->load_weight_kg) {
            return $this->load_weight_kg > $this->machine->capacity_kg;
        }
        return false;
    }

    // Calculate actual duration
    public function calculateDuration()
    {
        if ($this->started_at && $this->ended_at) {
            return $this->started_at->diffInMinutes($this->ended_at);
        }
        return null;
    }
}



