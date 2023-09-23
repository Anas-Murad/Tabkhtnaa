<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory ;

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'key',
        'icon',
    ];

    public function getIconAttribute($key)
    {
        if ($key != null)
            return asset($key);
        else
            return $key;
    }

    public  function scopeTrans($q){
        $lang = app()->getLocale() ;
        $q->select(
            'categories.id',
            'categories.key',
            'categories.icon',
            "translates.$lang as name",
        )->join('translates' ,  function ($join){
            $join->on('translates.key', '=', 'categories.key')
                ->where('translates.model', '=', 'categories');
        }) ;
    }

    public $timestamps = false;
}
