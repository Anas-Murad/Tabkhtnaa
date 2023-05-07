<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'user_id'
    ];

}
