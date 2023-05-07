<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';

    protected $fillable = [
        'id',
        'user_id',
        'file',
        'type',
        'status',
        'note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
