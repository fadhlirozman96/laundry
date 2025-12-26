<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'discount',
        'tax',
        'subtotal',
        'qc_completed',
        'qc_data',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'qc_completed' => 'boolean',
        'qc_data' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
