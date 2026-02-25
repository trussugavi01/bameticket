<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'sponsor_id',
        'table_number',
        'name',
        'capacity',
        'status',
        'position_x',
        'position_y',
    ];

    protected $casts = [
        'position_x' => 'decimal:2',
        'position_y' => 'decimal:2',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(Sponsor::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TableAssignment::class);
    }

    public function getAssignedCountAttribute(): int
    {
        return $this->assignments()->count();
    }

    public function getRemainingSeatsAttribute(): int
    {
        return $this->capacity - $this->assigned_count;
    }

    public function getIsFullAttribute(): bool
    {
        return $this->remaining_seats <= 0;
    }

    public function getIsSponsoredAttribute(): bool
    {
        return $this->sponsor_id !== null;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeReserved($query)
    {
        return $query->where('status', 'reserved');
    }
}
