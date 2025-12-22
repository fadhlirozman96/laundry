<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory, LogsActivity;

    // Activity Log methods
    public function getActivityLogName() { return 'Order'; }
    public function getActivityIdentifier() { return $this->order_number; }
    public function getActivityModule() { return 'pos'; }

    protected $fillable = [
        'order_number',
        'invoice_id',
        'coupon_id',
        'user_id',
        'customer_id',
        'store_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'subtotal',
        'tax',
        'discount',
        'shipping',
        'total',
        'payment_method',
        'payment_status',
        'order_status',
        'notes',
        'expected_completion',
        'special_instructions',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
        'expected_completion' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
