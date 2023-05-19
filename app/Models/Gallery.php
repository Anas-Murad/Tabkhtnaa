<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gallery extends Model
{
    use HasFactory;

    protected $table = 'gallery';

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'image',
        'meal_id',
    ];



    protected $hidden =[
        'created_at',
        'updated_at',
        'pivot',
    ];



    public function user():BelongsTo
    {
       return $this->belongsTo(User::class);
    }
}
