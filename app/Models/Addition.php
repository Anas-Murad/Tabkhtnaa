<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Addition extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'addition_category_id',
        'name',
        'price',
        'user_id',
    ];
    protected $hidden = [
        'user_id',
        'pivot',
    ];

    public function additionCategory(): BelongsTo
    {
        return $this->belongsTo(AdditionCategory::class);
    }

    public function meals()
    {
        return $this->belongsToMany(Meal::class, 'meal_additions' ,
            'addition_id' ,
            'meal_id' ,
        );
    }



}
