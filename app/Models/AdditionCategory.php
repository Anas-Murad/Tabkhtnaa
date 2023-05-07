<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdditionCategory extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
        'check_type',
        'display_name',
        'is_required',
        'user_id',

    ];

    protected $hidden = [
        'user_id'
    ];
    protected $casts = [
        'is_required' => 'boolean'
    ];


    public  function additions(){
        return  $this->hasMany(Addition::class , 'addition_category_id') ;
    }

}
