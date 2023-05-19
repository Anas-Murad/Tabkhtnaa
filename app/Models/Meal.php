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

    protected $hidden =[
        'deleted_at',
        'pivot',
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
    public function images()
    {
        return $this->hasMany(Gallery::class, 'meal_id' )->where('type', 'meal');
    }

    public function additions()
    {
        return $this->belongsToMany(Addition::class, 'meal_additions' ,
            'meal_id' ,
            'addition_id' ,
        );
    }


    public function setCategoriesAdditions()
    {
        $additionCategory = AdditionCategory::
            with([
                'additions'=>function($q) {
                    $q->whereHas('meals' ,  function ($q) {
                        $q->where('id' , $this->id) ;
                    });
                }
            ])
            ->  whereHas(
                'additions' , function($q) {
                $q->whereHas('meals' ,  function ($q) {
                    $q->where('id' , $this->id) ;
                });
            }
            )
            ->whereUserId(auth()->id())
            ->get();
        $this->setRelation('CategoriesAndAdditions' ,$additionCategory ) ;

    }
}
