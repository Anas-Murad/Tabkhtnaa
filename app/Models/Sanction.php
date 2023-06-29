<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanction extends Model
{
    use HasFactory;

    protected $table = "sanctions";

    protected $fillable = [
        'id',
        'admin_id',
        'user_id',
        'type',
        'seen',
        'note',
        'photo',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class , 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
}
