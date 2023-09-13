<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Offer extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'offers';

    protected $fillable = [
        'id',
        'meal_id',
        'image',
        'number',
        'percent',
        'get_free',
        'type',
        'start_date',
        'end_date',
    ];

    public function meal()
    {
        return $this->belongsTo(Meal::class , 'meal_id');
    }
}
