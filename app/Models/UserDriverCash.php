<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDriverCash extends Model
{
    public $timestamps = false;
    protected $table = 'user_driver_cash';
    protected $fillable = [
        'user_id',
        'order_id',
        'total_cash',
        'status',
    ];
}
