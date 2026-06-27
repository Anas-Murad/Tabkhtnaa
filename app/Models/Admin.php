<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Auditable;

    protected $table = 'admins';

    protected $fillable = [
      'id',
      'name',
      'email',
      'residence_country_id',
      'country_code',
      'mobile',
      'dob',
      'gender',
      'def_lang',
      'profile_image',
      'online_status',
      'account_status',
      'password',
    ];

    protected array $auditExclude = ['password', 'remember_token'];
}
