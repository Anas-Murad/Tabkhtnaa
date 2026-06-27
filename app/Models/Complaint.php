<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory, Auditable;

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
      'admin_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
