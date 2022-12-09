<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{

    public function  cities()
    {
        return  $this->hasMany(City::class);
    }

    protected $casts =[
        'flag'=>'boolean',
        'translations'=>'json',
    ] ;

    public function scopeApiData($q)
    {
        $q->select(
            'id',
            'iso3',
            'iso2',
            'phonecode',
            'native',
            'translations',
        )->where('flag' , true)->
        with('cities:id,country_id,name');

    }
}
