<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditTrail extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'admin_id',
        'auditable_type',
        'auditable_id',
        'event',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'url',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActorNameAttribute(): string
    {
        if ($this->admin) {
            return $this->admin->name . ' (مدير)';
        }

        if ($this->user) {
            return $this->user->name;
        }

        return 'النظام';
    }

    public function getAuditableLabelAttribute(): string
    {
        if (!$this->auditable_type) {
            return '-';
        }

        $short = class_basename($this->auditable_type);

        return $this->auditable_id
            ? "{$short} #{$this->auditable_id}"
            : $short;
    }
}

