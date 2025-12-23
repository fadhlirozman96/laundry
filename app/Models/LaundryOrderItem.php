<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaundryOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_order_id',
        'service_id',
        'service_name',
        'item_code',
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

    public function service()
    {
        return $this->belongsTo(Product::class, 'service_id');
    }

    // Generate unique item code
    public static function generateItemCode($orderId, $index)
    {
        return 'I' . $orderId . '-' . str_pad($index, 3, '0', STR_PAD_LEFT);
    }
}


