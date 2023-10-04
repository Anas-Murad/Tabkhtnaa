<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $table = "ratings";

    protected $fillable = [
      'id',
      'user_id',
      'chef_id',
      'delivery_id',
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
    public function order()
    {
        return $this->belongsTo(Order::class , 'order_id');
    }
}
