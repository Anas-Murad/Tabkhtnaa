<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'to_type',
        'to_id',
        'amount',
        'from_type',
        'from_id',
    ];

    public  function to(){
        return  $this->belongsTo(User::class , 'to_id');
    }

    public  function from(){
        return  $this->belongsTo(User::class , 'from_id');
    }


    public  function transferRecords(){
        return $this->hasMany(TransferRecord::class , 'transfer_id') ;
    }


}
