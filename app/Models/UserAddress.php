<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $guarded =[];


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


    public function scopeAllData($q)
    {
        $q-> with('cities:id,name');
        $q-> with('country:id,name,native,translations');

    }




}
