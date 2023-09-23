<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Accessories extends Model
{
    public $timestamps =  false;

    protected $fillable = [
        'key',
        'default_name',
    ];

    protected $hidden =[
        'pivot',
    ];

    public  function scopeTrans($q){
        $lang = app()->getLocale() ;
        $q->select(
            'accessories.id',
            'accessories.key',
            'accessories.default_name',
            "translates.$lang as name",
        )->join('translates' ,  function ($join){
            $join->on('translates.key', '=', 'accessories.key')
                ->where('translates.model', '=', 'accessories');
        }) ;
    }
}
