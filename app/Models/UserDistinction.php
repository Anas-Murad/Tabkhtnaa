<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDistinction extends Model
{
    protected $fillable = [
        'user_id',
        'from_date',
        'to_date',
        'status',
    ];
}
