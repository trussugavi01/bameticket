<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'event_id',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'stripe_customer_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'billing_address',
        'subtotal',
        'vat_amount',
        'vat_rate',
        'transaction_fee',
        'total_amount',
        'currency',
        'payment_status',
        'refund_status',
        'refunded_amount',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'transaction_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refunded_amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(Str::random(8));
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Refund::class);
    }

    public function getTicketCountAttribute(): int
    {
        return $this->tickets()->count();
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function getIsRefundedAttribute(): bool
    {
        return $this->refund_status === 'full';
    }

    public function getCanRefundAttribute(): bool
    {
        return $this->is_paid && $this->refund_status !== 'full';
    }

    public function getRefundableAmountAttribute(): float
    {
        return $this->total_amount - $this->refunded_amount;
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeRefunded($query)
    {
        return $query->where('refund_status', 'full');
    }
}
