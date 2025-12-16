<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'user_id',
        'name',
        'code',
        'type',
        'discount',
        'limit',
        'used',
        'valid_from',
        'valid_until',
        'min_purchase',
        'max_discount',
        'is_active',
        'description',
    ];

    protected $casts = [
        'discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if coupon is valid
     */
    public function isValid($purchaseAmount = 0)
    {
        // Check if active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'Coupon is not active'];
        }

        // Check usage limit
        if ($this->limit && $this->used >= $this->limit) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        // Check valid dates
        $today = now()->startOfDay();
        if ($this->valid_from && $today->lt($this->valid_from)) {
            return ['valid' => false, 'message' => 'Coupon is not yet valid'];
        }
        if ($this->valid_until && $today->gt($this->valid_until)) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        // Check minimum purchase
        if ($this->min_purchase && $purchaseAmount < $this->min_purchase) {
            return ['valid' => false, 'message' => 'Minimum purchase of MYR ' . number_format($this->min_purchase, 2) . ' required'];
        }

        return ['valid' => true, 'message' => 'Coupon is valid'];
    }

    /**
     * Calculate discount amount
     */
    public function calculateDiscount($amount)
    {
        if ($this->type === 'percentage') {
            $discount = ($amount * $this->discount) / 100;
            // Apply max discount cap if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            $discount = $this->discount;
        }

        // Discount cannot be more than the amount
        return min($discount, $amount);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('used');
    }

    /**
     * Generate unique coupon code
     */
    public static function generateCode($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        do {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (self::where('code', $code)->exists());

        return $code;
    }
    
    /**
     * Generate coupon code from name
     * Example: "Grand Opening" -> "GRANDOP" or "GRANDOP1"
     */
    public static function generateCodeFromName($name)
    {
        // Remove special characters and convert to uppercase
        $cleanName = preg_replace('/[^a-zA-Z0-9]/', '', $name);
        $cleanName = strtoupper($cleanName);
        
        // Take first 6-8 characters
        $baseCode = substr($cleanName, 0, 8);
        
        // If name is too short, pad it
        if (strlen($baseCode) < 4) {
            $baseCode = str_pad($baseCode, 4, 'X');
        }
        
        // Check if code exists, if so add a number
        $code = $baseCode;
        $counter = 1;
        
        while (self::where('code', $code)->exists()) {
            $code = substr($baseCode, 0, 6) . $counter;
            $counter++;
        }
        
        return $code;
    }
}
