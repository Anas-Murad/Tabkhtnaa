<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translate extends Model
{
    use HasFactory;

    protected $table = 'translates';

    protected $fillable = [
        'id',
        'key',
        'model',
        'en',
        'ar',
        'fr',
        'tr',
    ];

    public $timestamps = false;
}
