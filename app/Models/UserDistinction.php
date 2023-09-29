<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDistinction extends Model
{
    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'status',
    ];


    protected $casts = [
//        "from_date" =>'date',
//        "to_date" =>'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}

