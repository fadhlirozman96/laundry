<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'user_id',
        'transaction_id',
        'amount',
        'currency',
        'payment_method',
        'status',
        'payment_details',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the subscription that owns the payment
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the user that made the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark payment as completed
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark payment as failed
     */
    public function markAsFailed()
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadge()
    {
        $badges = [
            'completed' => '<span class="badge bg-success">Completed</span>',
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'failed' => '<span class="badge bg-danger">Failed</span>',
            'refunded' => '<span class="badge bg-info">Refunded</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }
}


