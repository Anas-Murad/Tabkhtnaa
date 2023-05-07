<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'code',
        'description',
        'image',
        'category_id',
        'type',
        'is_active',
        'days',
        'admin_status',
        'user_id',
        'preparation_time',
    ];


    public  function category(){
        return  $this->belongsTo(Category::class, 'category_id') ;

    }
    protected $casts = [
      'is_active'=>'boolean',
      'days'=>'array',
    ];

    public function accessories()
    {
        return $this->belongsToMany(Accessories::class, 'meal_accessories' ,
            'meal_id' ,
            'accessory_id' ,
        );
    }


}
