<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderAddress extends Model
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


    public function  cities()
    {
        return  $this->belongsTo(City::class , 'city_id');
    }


    public function  city()
    {
        return  $this->belongsTo(City::class , 'city_id');
    }


    public function  country()
    {
        return  $this->belongsTo(Country::class);
    }

}
