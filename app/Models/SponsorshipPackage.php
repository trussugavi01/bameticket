<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SponsorshipPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'name',
        'slug',
        'description',
        'price',
        'features',
        'tables_included',
        'guests_per_table',
        'has_branding',
        'has_speaking_slot',
        'max_available',
        'quantity_sold',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'features' => 'array',
        'has_branding' => 'boolean',
        'has_speaking_slot' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($package) {
            if (empty($package->slug)) {
                $package->slug = Str::slug($package->name);
            }
        });
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(Sponsor::class);
    }

    public function getRemainingQuantityAttribute(): ?int
    {
        if ($this->max_available === null) {
            return null;
        }
        return $this->max_available - $this->quantity_sold;
    }

    public function getIsSoldOutAttribute(): bool
    {
        if ($this->max_available === null) {
            return false;
        }
        return $this->remaining_quantity <= 0;
    }

    public function getTotalPriceWithVatAttribute(): float
    {
        return $this->price * 1.20;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('max_available')
                    ->orWhereRaw('quantity_sold < max_available');
            });
    }
}
