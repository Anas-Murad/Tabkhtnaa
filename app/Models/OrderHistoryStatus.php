<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistoryStatus extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'action_by_type',
        'action_by_id',
    ];
}
