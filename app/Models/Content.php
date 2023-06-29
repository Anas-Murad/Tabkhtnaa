<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $table = "contents";

    protected $fillable = [
      'id',
      'key',
      'en_text',
      'ar_text',
      'fr_text',
    ];
}
