<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'user_id',
        'user_role',
        'action',
        'model_type',
        'model_id',
        'previous_state',
        'new_state',
        'impact_level',
        'ip_address',
        'user_agent',
        'location',
        'auth_method',
        'request_url',
        'request_id',
        'system_version',
        'is_flagged',
    ];

    protected $casts = [
        'previous_state' => 'array',
        'new_state' => 'array',
        'is_flagged' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            if (empty($log->transaction_id)) {
                $log->transaction_id = strtoupper(Str::random(10)) . '-X';
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSubjectAttribute()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    public function scopeHighImpact($query)
    {
        return $query->whereIn('impact_level', ['high', 'critical']);
    }

    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    public static function log(
        string $action,
        ?Model $model = null,
        ?array $previousState = null,
        ?array $newState = null,
        string $impactLevel = 'low'
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'user_role' => auth()->user()?->roles?->first()?->name,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'previous_state' => $previousState,
            'new_state' => $newState,
            'impact_level' => $impactLevel,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'request_url' => request()->fullUrl(),
            'request_id' => request()->header('X-Request-ID'),
            'system_version' => config('app.version', '1.0.0'),
        ]);
    }
}
