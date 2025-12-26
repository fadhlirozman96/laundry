<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, LogsActivity;

    // Activity Log methods
    public function getActivityLogName() { return 'Product'; }
    public function getActivityIdentifier() { return $this->name; }
    public function getActivityModule() { return 'inventory'; }

    protected $fillable = [
        'store_id',
        'sku',
        'name',
        'slug',
        'category_id',
        'unit_id',
        'unit_type',
        'qc_mode',
        'set_components',
        'description',
        'price',
        'cost',
        'quantity',
        'track_quantity',
        'alert_quantity',
        'image',
        'images',
        'tax_type',
        'discount_value',
        'discount_type',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'cost' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'is_active' => 'boolean',
        'track_quantity' => 'boolean',
        'images' => 'array',
        'set_components' => 'array',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get QC display label
     */
    public function getQcLabel()
    {
        switch ($this->unit_type) {
            case 'piece':
                return 'pieces';
            case 'set':
                return 'set(s)';
            case 'kg':
                return 'kg';
            case 'sqft':
                return 'sqft';
            default:
                return 'items';
        }
    }
    
    /**
     * Check if item needs count-based QC
     */
    public function needsCountQc()
    {
        return $this->qc_mode === 'count';
    }
    
    /**
     * Check if item needs completeness QC (set-based)
     */
    public function needsCompletenessQc()
    {
        return $this->qc_mode === 'completeness';
    }
    
    /**
     * Check if item needs integrity QC (kg-based)
     */
    public function needsIntegrityQc()
    {
        return $this->qc_mode === 'integrity';
    }
    
    /**
     * Check if item needs identity QC (sqft-based)
     */
    public function needsIdentityQc()
    {
        return $this->qc_mode === 'identity';
    }
}
