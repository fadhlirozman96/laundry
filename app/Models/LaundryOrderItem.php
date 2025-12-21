<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_order_id',
        'garment_type_id',
        'garment_name',
        'garment_code',
        'quantity',
        'color',
        'brand',
        'condition_notes',
        'price',
        'subtotal',
        'status',
        'issue_notes',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function laundryOrder()
    {
        return $this->belongsTo(LaundryOrder::class);
    }

    public function garmentType()
    {
        return $this->belongsTo(GarmentType::class);
    }

    // Generate unique garment code
    public static function generateGarmentCode($orderId, $index)
    {
        return 'G' . $orderId . '-' . str_pad($index, 3, '0', STR_PAD_LEFT);
    }
}


