<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory, Auditable;
    protected $table = "ratings";

    protected $fillable = [
      'id',
      'user_id',
      'chef_id',
      'order_id',
      'rating_chef',
      'rating_delivery',
      'rating_speed_chef',
      'rating_speed_delivery',
      'note',
      'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
    public function chef()
    {
        return $this->belongsTo(User::class , 'chef_id');
    }
}
