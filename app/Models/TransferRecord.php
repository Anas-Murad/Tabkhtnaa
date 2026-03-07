<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'to_type',
        'to_id',
        'order_id',
        'amount',
        'remainder',
        'transaction_id',
        'percent',
        'user_driver_cash_id',
        'admin_checked',
        'admin_notes',
        'transfer_status',
        'transfer_id',
        'transfer_date',
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
    ];

    public  function transfer(){
        return $this->belongsTo(Transfer::class , 'transfer_id') ;
    }


}
