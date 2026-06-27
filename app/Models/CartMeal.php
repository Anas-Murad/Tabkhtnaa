<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartMeal extends Model
{
    use SoftDeletes, Auditable;

    protected $fillable = [
        'cart_id',
        'user_id',
        'meal_id',
        'quantity',
        'note',
    ];




    public   function meal(){
        return  $this->belongsTo(Meal::class , 'meal_id') ;
    }


    public   function mealAccessories(){
        return  $this->hasMany(CartMealAccessory::class , 'cart_meal_id') ;
    }

    public   function mealAdditions(){
        return  $this->hasMany(CartMealAddition::class , 'cart_meal_id') ;
    }



    public function accessories()
    {
        return $this->belongsToMany(Accessories::class,
            'cart_meal_accessories' ,
            'cart_meal_id' ,
            'accessory_id' ,
        );
    }



    public function additions()
    {
        return $this->belongsToMany(Addition::class, 'cart_meal_additions' ,
            'cart_meal_id' ,
            'addition_id' ,
        );
    }





}
