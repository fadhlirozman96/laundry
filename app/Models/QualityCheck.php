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
        'order_id',
        'user_id',
        'store_id',
        // 1. Cleanliness
        'cleanliness_check',
        'cleanliness_rating',
        'cleanliness_notes',
        // 3. Odour
        'odour_check',
        'odour_rating',
        'odour_notes',
        // 1. Quantity
        'quantity_check',
        'items_received',
        'items_counted',
        'quantity_match',
        'quantity_notes',
        // 2. Status & Workflow
        'status_wash_check',
        'status_dry_check',
        'status_iron_check',
        'status_notes',
        // 4. Dryness
        'dryness_check',
        'dryness_rating',
        'bulky_items_dry',
        'dryness_notes',
        // 5. Finishing/Ironing
        'ironing_check',
        'ironing_rating',
        'no_wrinkles',
        'ironing_notes',
        // 5. Folding
        'folding_check',
        'folding_rating',
        'folding_notes',
        // 6. Damage
        'stain_check',
        'stain_notes',
        'damage_check',
        'damage_notes',
        'damage_photos',
        // 7. Special Instructions
        'special_instructions_check',
        'special_instructions_followed',
        'special_instructions_notes',
        // 8. Packaging & Labeling
        'packaging_check',
        'label_correct',
        'qr_tag_intact',
        'packaging_notes',
        // Overall
        'passed',
        'overall_notes',
        'rejection_reason',
        'final_approval',
        'approved_at',
    ];

    protected $casts = [
        'cleanliness_check' => 'boolean',
        'odour_check' => 'boolean',
        'quantity_check' => 'boolean',
        'quantity_match' => 'boolean',
        'status_wash_check' => 'boolean',
        'status_dry_check' => 'boolean',
        'status_iron_check' => 'boolean',
        'dryness_check' => 'boolean',
        'bulky_items_dry' => 'boolean',
        'ironing_check' => 'boolean',
        'no_wrinkles' => 'boolean',
        'folding_check' => 'boolean',
        'stain_check' => 'boolean',
        'damage_check' => 'boolean',
        'special_instructions_check' => 'boolean',
        'special_instructions_followed' => 'boolean',
        'packaging_check' => 'boolean',
        'label_correct' => 'boolean',
        'qr_tag_intact' => 'boolean',
        'passed' => 'boolean',
        'final_approval' => 'boolean',
        'damage_photos' => 'array',
        'approved_at' => 'datetime',
    ];

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

    // Check if all mandatory checks are completed
    public function allChecksCompleted()
    {
        return $this->cleanliness_check 
            && $this->odour_check 
            && $this->quantity_check 
            && $this->quantity_match // Quantity must match
            && $this->status_wash_check 
            && $this->status_dry_check 
            && $this->dryness_check 
            && $this->folding_check
            && $this->packaging_check
            && $this->label_correct
            && $this->final_approval;
    }
    
    // Check if order has bulky items
    public function hasBulkyItems()
    {
        if ($this->order) {
            // Check if any item contains keywords like: toto, comforter, blanket, curtain
            $bulkyKeywords = ['toto', 'comforter', 'blanket', 'curtain', 'carpet', 'rug'];
            foreach ($this->order->items as $item) {
                $productName = strtolower($item->product_name);
                foreach ($bulkyKeywords as $keyword) {
                    if (str_contains($productName, $keyword)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    // Calculate overall rating
    public function getOverallRating()
    {
        $ratings = array_filter([
            $this->cleanliness_rating,
            $this->odour_rating,
            $this->dryness_rating,
            $this->ironing_rating,
            $this->folding_rating,
        ]);

        if (count($ratings) === 0) {
            return 0;
        }

        return round(array_sum($ratings) / count($ratings), 1);
    }
    
    // Get completion percentage
    public function getCompletionPercentage()
    {
        $mandatoryChecks = [
            $this->cleanliness_check,
            $this->odour_check,
            $this->quantity_check,
            $this->status_wash_check,
            $this->status_dry_check,
            $this->dryness_check,
            $this->folding_check,
            $this->packaging_check,
        ];
        
        $completed = count(array_filter($mandatoryChecks));
        $total = count($mandatoryChecks);
        
        return round(($completed / $total) * 100);
    }
}

