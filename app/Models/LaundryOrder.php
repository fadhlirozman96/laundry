<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class LaundryOrder extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'store_id',
        'user_id',
        'customer_id',
        'order_number',
        'qr_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'status',
        'subtotal',
        'tax',
        'discount',
        'shipping',
        'coupon_code',
        'coupon_discount',
        'order_tax_percent',
        'total',
        'total_items',
        'total_services',
        'received_at',
        'washing_at',
        'drying_at',
        'folding_at',
        'ready_at',
        'collected_at',
        'expected_completion',
        'qc_passed',
        'qc_by',
        'qc_at',
        'payment_status',
        'payment_method',
        'amount_paid',
        'notes',
        'special_instructions',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'order_tax_percent' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'qc_passed' => 'boolean',
        'received_at' => 'datetime',
        'washing_at' => 'datetime',
        'drying_at' => 'datetime',
        'folding_at' => 'datetime',
        'ready_at' => 'datetime',
        'collected_at' => 'datetime',
        'expected_completion' => 'datetime',
        'qc_at' => 'datetime',
    ];

    // Status constants
    const STATUS_RECEIVED = 'received';
    const STATUS_WASHING = 'washing';
    const STATUS_DRYING = 'drying';
    const STATUS_FOLDING = 'folding';
    const STATUS_READY = 'ready';
    const STATUS_COLLECTED = 'collected';

    const STATUSES = [
        self::STATUS_RECEIVED => 'Received',
        self::STATUS_WASHING => 'Washing',
        self::STATUS_DRYING => 'Drying',
        self::STATUS_FOLDING => 'Folding',
        self::STATUS_READY => 'Ready for Collection',
        self::STATUS_COLLECTED => 'Collected',
    ];

    const STATUS_COLORS = [
        self::STATUS_RECEIVED => 'secondary',
        self::STATUS_WASHING => 'info',
        self::STATUS_DRYING => 'warning',
        self::STATUS_FOLDING => 'primary',
        self::STATUS_READY => 'success',
        self::STATUS_COLLECTED => 'dark',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(LaundryOrderItem::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(LaundryOrderStatusLog::class)->orderBy('created_at', 'desc');
    }

    public function qualityChecks()
    {
        return $this->hasMany(QualityCheck::class);
    }

    public function latestQualityCheck()
    {
        return $this->hasOne(QualityCheck::class)->latestOfMany();
    }

    public function qcInspector()
    {
        return $this->belongsTo(User::class, 'qc_by');
    }

    public function machineUsageLogs()
    {
        return $this->hasMany(MachineUsageLog::class);
    }

    // Generate unique order number
    public static function generateOrderNumber($storeId)
    {
        $prefix = 'LO';
        $date = date('Ymd');
        $count = self::where('store_id', $storeId)
            ->whereDate('created_at', today())
            ->count() + 1;
        return $prefix . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // Generate unique QR code
    public static function generateQRCode()
    {
        return 'QR-' . strtoupper(uniqid()) . '-' . time();
    }

    // Get next status in workflow
    public function getNextStatus()
    {
        $workflow = [
            self::STATUS_RECEIVED => self::STATUS_WASHING,
            self::STATUS_WASHING => self::STATUS_DRYING,
            self::STATUS_DRYING => self::STATUS_FOLDING,
            self::STATUS_FOLDING => self::STATUS_READY,
            self::STATUS_READY => self::STATUS_COLLECTED,
        ];

        return $workflow[$this->status] ?? null;
    }

    // Check if status can be updated to target
    public function canUpdateStatusTo($targetStatus)
    {
        // Can't update collected orders
        if ($this->status === self::STATUS_COLLECTED) {
            return false;
        }

        // Must pass QC before marking as ready
        if ($targetStatus === self::STATUS_READY && !$this->qc_passed) {
            return false;
        }

        return true;
    }

    // Update status with logging
    public function updateStatus($newStatus, $userId, $notes = null)
    {
        $oldStatus = $this->status;
        
        // Create status log
        LaundryOrderStatusLog::create([
            'laundry_order_id' => $this->id,
            'user_id' => $userId,
            'from_status' => $oldStatus,
            'to_status' => $newStatus,
            'notes' => $notes,
        ]);

        // Update timestamp based on status
        $timestampField = $newStatus . '_at';
        $updateData = [
            'status' => $newStatus,
        ];
        
        if (in_array($timestampField, ['received_at', 'washing_at', 'drying_at', 'folding_at', 'ready_at', 'collected_at'])) {
            $updateData[$timestampField] = now();
        }

        $this->update($updateData);

        return true;
    }

    // Get status badge HTML
    public function getStatusBadge()
    {
        $color = self::STATUS_COLORS[$this->status] ?? 'secondary';
        $label = self::STATUSES[$this->status] ?? ucfirst($this->status);
        return '<span class="badge bg-' . $color . '">' . $label . '</span>';
    }

    // Activity Log methods
    public function getActivityLogName()
    {
        return 'Laundry Order';
    }

    public function getActivityIdentifier()
    {
        return $this->order_number;
    }

    public function getActivityModule()
    {
        return 'laundry';
    }
}

