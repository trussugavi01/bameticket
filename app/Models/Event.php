<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'category',
        'image',
        'start_date',
        'end_date',
        'venue_name',
        'venue_address',
        'venue_lat',
        'venue_lng',
        'dress_code',
        'doors_open',
        'dinner_time',
        'status',
        'is_featured',
        'total_capacity',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'doors_open' => 'datetime:H:i',
        'dinner_time' => 'datetime:H:i',
        'is_featured' => 'boolean',
        'venue_lat' => 'decimal:8',
        'venue_lng' => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title);
            }
        });
    }

    public function ticketTypes(): HasMany
    {
        return $this->hasMany(TicketType::class)->orderBy('sort_order');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function sponsors(): HasMany
    {
        return $this->hasMany(Sponsor::class);
    }

    public function tables(): HasMany
    {
        return $this->hasMany(Table::class);
    }

    public function sponsorshipPackages(): HasMany
    {
        return $this->hasMany(SponsorshipPackage::class);
    }

    public function tickets(): HasManyThrough
    {
        return $this->hasManyThrough(Ticket::class, Order::class);
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->orders()
            ->where('payment_status', 'completed')
            ->sum('total_amount');
    }

    public function getTicketsSoldAttribute(): int
    {
        return $this->ticketTypes()->sum('quantity_sold');
    }

    public function getRemainingCapacityAttribute(): int
    {
        return $this->total_capacity - $this->tickets_sold;
    }

    public function getCapacityPercentageAttribute(): float
    {
        if ($this->total_capacity === 0) return 0;
        return round(($this->tickets_sold / $this->total_capacity) * 100, 1);
    }

    public function scopePublished($query)
    {
        return $query->whereIn('status', ['published', 'selling', 'sold_out']);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    public function scopePast($query)
    {
        return $query->where('end_date', '<', now());
    }

    public function scopeDrafts($query)
    {
        return $query->where('status', 'draft');
    }
}
