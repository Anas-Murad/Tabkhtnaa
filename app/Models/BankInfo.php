<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankInfo extends Model
{
    use HasFactory , SoftDeletes;

    protected $table ='bank_infos';

    protected $fillable = [
      'id',
      'user_id',
      'country_id',
      'city_id',
      'type',
      'bank_name',
      'iban',
      'swift_code',
      'details',
      'shop_exchange_name',
      'wallet_name',
      'wallet_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
