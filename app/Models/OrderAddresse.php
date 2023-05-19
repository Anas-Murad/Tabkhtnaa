<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddresse extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'address_id',
        'name',
        'place',
        'country_id',
        'city_id',
        'neighborhood',
        'build_address',
        'floor',
        'apartment_address',
        'details',
        'latitude',
        'longitude',
        'user_id',
    ];
}
