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
        'receipt_path',
        'thermal_receipt_path',
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

    public function qualityCheck()
    {
        return $this->hasOne(QualityCheck::class);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadge()
    {
        $statusColors = [
            'pending' => 'bg-warning',
            'processing' => 'bg-info',
            'completed' => 'bg-success',
            'cancelled' => 'bg-danger',
        ];

        $color = $statusColors[$this->order_status] ?? 'bg-secondary';
        $label = ucfirst($this->order_status);

        return '<span class="badge ' . $color . '">' . $label . '</span>';
    }

    /**
     * Get payment status badge HTML
     */
    public function getPaymentStatusBadge()
    {
        $statusColors = [
            'pending' => 'bg-warning',
            'paid' => 'bg-success',
            'partial' => 'bg-info',
            'failed' => 'bg-danger',
        ];

        $color = $statusColors[$this->payment_status] ?? 'bg-secondary';
        $label = ucfirst($this->payment_status);

        return '<span class="badge ' . $color . '">' . $label . '</span>';
    }

    /**
     * Get QC status badge HTML
     */
    public function getQcStatusBadge()
    {
        if ($this->qualityCheck) {
            if ($this->qualityCheck->passed) {
                return '<span class="badge bg-success">Passed</span>';
            } else {
                return '<span class="badge bg-danger">Failed</span>';
            }
        } else {
            // No QC record exists
            switch ($this->order_status) {
                case 'pending':
                    return '<span class="badge bg-info">Pending Order Processing</span>';
                case 'processing':
                    return '<span class="badge bg-warning">Pending QC</span>';
                case 'completed':
                    return '<span class="badge bg-secondary">No QC</span>';
                default:
                    return '<span class="badge bg-secondary">-</span>';
            }
        }
    }
}
