<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserStatus extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'account_comment',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
