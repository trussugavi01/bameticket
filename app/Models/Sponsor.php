<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sponsor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sponsorship_package_id',
        'event_id',
        'company_name',
        'contact_name',
        'contact_email',
        'contact_phone',
        'billing_address',
        'logo_path',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'amount_paid',
        'vat_amount',
        'service_fee',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'service_fee' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function sponsorshipPackage(): BelongsTo
    {
        return $this->belongsTo(SponsorshipPackage::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function getTotalPaidAttribute(): float
    {
        return $this->amount_paid + $this->vat_amount + $this->service_fee;
    }

    public function getIsPaidAttribute(): bool
    {
        return $this->payment_status === 'completed';
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'completed');
    }
}
