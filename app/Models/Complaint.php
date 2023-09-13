<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $table = 'complaints';

    protected $fillable = [
      'id',
      'user_id',
      'order_id',
      'type',
      'photo',
      'description',
      'status',
      'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
