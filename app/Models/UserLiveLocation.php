<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLiveLocation extends Model
{
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
    ];
}
