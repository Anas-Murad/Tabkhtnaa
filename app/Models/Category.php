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

    public $timestamps = false;
}
