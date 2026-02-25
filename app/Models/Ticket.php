<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'ticket_type_id',
        'uuid',
        'ticket_number',
        'attendee_name',
        'attendee_email',
        'qr_code_path',
        'is_checked_in',
        'checked_in_at',
        'checked_in_by',
        'check_in_method',
        'status',
    ];

    protected $casts = [
        'is_checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->uuid)) {
                $ticket->uuid = (string) Str::uuid();
            }
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'NBHCA-' . date('Y') . '-' . strtoupper(Str::random(8));
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketType(): BelongsTo
    {
        return $this->belongsTo(TicketType::class);
    }

    public function tableAssignment(): HasOne
    {
        return $this->hasOne(TableAssignment::class);
    }

    public function getEventAttribute()
    {
        return $this->order?->event;
    }

    public function getIsValidAttribute(): bool
    {
        return $this->status === 'valid' && !$this->is_checked_in;
    }

    public function getCanCheckInAttribute(): bool
    {
        return $this->status === 'valid' && !$this->is_checked_in;
    }

    public function checkIn(string $method = 'qr', ?string $checkedInBy = null): bool
    {
        if (!$this->can_check_in) {
            return false;
        }

        $this->update([
            'is_checked_in' => true,
            'checked_in_at' => now(),
            'checked_in_by' => $checkedInBy,
            'check_in_method' => $method,
            'status' => 'used',
        ]);

        return true;
    }

    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    public function scopeCheckedIn($query)
    {
        return $query->where('is_checked_in', true);
    }

    public function scopeNotCheckedIn($query)
    {
        return $query->where('is_checked_in', false);
    }
}
