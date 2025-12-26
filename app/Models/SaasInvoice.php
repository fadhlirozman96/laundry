<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaasInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'subscription_id',
        'invoice_number',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'status',
        'issue_date',
        'due_date',
        'paid_at',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'date',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isOverdue()
    {
        return $this->status === 'overdue' || 
               ($this->status !== 'paid' && $this->due_date->isPast());
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'paid' => '<span class="badge bg-success">Paid</span>',
            'overdue' => '<span class="badge bg-danger">Overdue</span>',
            'sent' => '<span class="badge bg-info">Sent</span>',
            'draft' => '<span class="badge bg-secondary">Draft</span>',
            'cancelled' => '<span class="badge bg-dark">Cancelled</span>',
            default => '<span class="badge bg-secondary">Unknown</span>',
        };
    }
}


