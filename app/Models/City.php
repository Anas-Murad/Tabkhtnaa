<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{


    protected $fillable = [
        'name',
        'country_code',
        'iso2',
        'latitude',
        'longitude',
        'flag',
        'country_id',
    ] ;
}
