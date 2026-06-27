<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class UserLiveLocation extends Model
{
    use Auditable;
    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
    ];
}
