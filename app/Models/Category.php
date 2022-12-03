<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory , HasTranslations;

    protected $table = 'categories';

    protected $fillable = [
        'id',
        'name',
        'icon',
    ];

    protected $appends = ['locale_name'];
    public function  getLocaleNameAttribute()
    {
        $t = $this->getTranslations('name');
        $lang = app()->getLocale();
        return  $t[$lang] ?? $t[0];
    }

    public $translatable = ['name'];

}
