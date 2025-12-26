<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'action_text',
        'priority',
        'is_read',
        'read_at',
        'metadata',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the notification
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead()
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for read notifications
     */
    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    /**
     * Get icon based on type
     */
    public function getIcon()
    {
        $icons = [
            'subscription_renewal' => 'refresh-cw',
            'payment_success' => 'check-circle',
            'payment_failed' => 'x-circle',
            'grace_period' => 'alert-triangle',
            'subscription_expired' => 'alert-circle',
            'plan_upgraded' => 'trending-up',
            'plan_downgraded' => 'trending-down',
            'trial_ending' => 'clock',
        ];

        return $icons[$this->type] ?? 'bell';
    }

    /**
     * Get color based on priority
     */
    public function getColor()
    {
        $colors = [
            'low' => 'secondary',
            'normal' => 'info',
            'high' => 'warning',
            'urgent' => 'danger',
        ];

        return $colors[$this->priority] ?? 'info';
    }
}

