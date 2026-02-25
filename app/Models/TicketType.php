<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'quantity_available',
        'quantity_sold',
        'early_bird_end_date',
        'early_bird_price',
        'max_per_order',
        'min_per_order',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'early_bird_price' => 'decimal:2',
        'early_bird_end_date' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getCurrentPriceAttribute(): float
    {
        if ($this->early_bird_price && $this->early_bird_end_date && now()->lt($this->early_bird_end_date)) {
            return $this->early_bird_price;
        }
        return $this->price;
    }

    public function getIsEarlyBirdActiveAttribute(): bool
    {
        return $this->early_bird_price && $this->early_bird_end_date && now()->lt($this->early_bird_end_date);
    }

    public function getRemainingQuantityAttribute(): int
    {
        return $this->quantity_available - $this->quantity_sold;
    }

    public function getIsSoldOutAttribute(): bool
    {
        return $this->remaining_quantity <= 0;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->whereRaw('quantity_sold < quantity_available');
    }
}
