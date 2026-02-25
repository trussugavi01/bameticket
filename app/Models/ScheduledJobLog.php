<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledJobLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_name',
        'job_class',
        'status',
        'execution_time',
        'output',
        'error',
        'started_at',
        'completed_at',
        'next_run_at',
    ];

    protected $casts = [
        'execution_time' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_run_at' => 'datetime',
    ];

    public function getIsSuccessAttribute(): bool
    {
        return $this->status === 'success';
    }

    public function getIsFailedAttribute(): bool
    {
        return $this->status === 'failed';
    }

    public function getDurationAttribute(): ?float
    {
        if ($this->started_at && $this->completed_at) {
            return $this->started_at->diffInSeconds($this->completed_at);
        }
        return null;
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, int $limit = 10)
    {
        return $query->orderBy('started_at', 'desc')->limit($limit);
    }

    public static function logStart(string $jobName, ?string $jobClass = null): self
    {
        return self::create([
            'job_name' => $jobName,
            'job_class' => $jobClass,
            'status' => 'success',
            'started_at' => now(),
        ]);
    }
}
