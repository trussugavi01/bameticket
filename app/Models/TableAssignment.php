<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'ticket_id',
        'attendee_name',
        'attendee_email',
        'organization',
        'dietary_type',
        'dietary_notes',
        'dietary_status',
        'seat_number',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function getHasDietaryRequirementsAttribute(): bool
    {
        return !empty($this->dietary_type) || !empty($this->dietary_notes);
    }

    public function scopeWithDietaryRequirements($query)
    {
        return $query->where(function ($q) {
            $q->whereNotNull('dietary_type')
                ->orWhereNotNull('dietary_notes');
        });
    }

    public function scopeByDietaryType($query, string $type)
    {
        return $query->where('dietary_type', $type);
    }
}
