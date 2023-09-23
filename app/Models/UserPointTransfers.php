<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPointTransfers extends Model
{
    protected $fillable = [
        'user_id',
        'points',
        'offered_type',
        'offered_id',
        'validity_date',
        'discount',
        'status',
    ];

    protected $casts = [
        'validity_date' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
