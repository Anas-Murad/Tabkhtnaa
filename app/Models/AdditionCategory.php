<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdditionCategory extends Model
{
    use Auditable;
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


    public  function additions():HasMany
    {
        return  $this->hasMany(Addition::class , 'addition_category_id') ;
    }

}
