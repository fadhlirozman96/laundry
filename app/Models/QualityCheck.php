<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class QualityCheck extends Model
{
    use HasFactory, LogsActivity;

    // Activity Log methods
    public function getActivityLogName() { return 'Quality Check'; }
    public function getActivityIdentifier() { return 'QC #' . $this->id; }
    public function getActivityModule() { return 'laundry'; }

    protected $fillable = [
        'laundry_order_id',
        'user_id',
        'store_id',
        'cleanliness_check',
        'cleanliness_rating',
        'cleanliness_notes',
        'odour_check',
        'odour_rating',
        'odour_notes',
        'quantity_check',
        'items_received',
        'items_counted',
        'quantity_match',
        'quantity_notes',
        'folding_check',
        'folding_rating',
        'folding_notes',
        'stain_check',
        'stain_notes',
        'damage_check',
        'damage_notes',
        'passed',
        'overall_notes',
        'rejection_reason',
    ];

    protected $casts = [
        'cleanliness_check' => 'boolean',
        'odour_check' => 'boolean',
        'quantity_check' => 'boolean',
        'quantity_match' => 'boolean',
        'folding_check' => 'boolean',
        'stain_check' => 'boolean',
        'damage_check' => 'boolean',
        'passed' => 'boolean',
    ];

    public function laundryOrder()
    {
        return $this->belongsTo(LaundryOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Check if all mandatory checks are completed
    public function allChecksCompleted()
    {
        return $this->cleanliness_check 
            && $this->odour_check 
            && $this->quantity_check 
            && $this->folding_check;
    }

    // Calculate overall rating
    public function getOverallRating()
    {
        $ratings = array_filter([
            $this->cleanliness_rating,
            $this->odour_rating,
            $this->folding_rating,
        ]);

        if (count($ratings) === 0) {
            return 0;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }
}

