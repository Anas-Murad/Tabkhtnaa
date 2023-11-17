<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'order_id',
        'payment_id',
        'payment_method',
        'service_type',
        'amount',
        'currency',
        'status',
        'admin_status',
        'admin_notes',
        'tracking_data',
        'webhook',
        'tried_again',
    ];

    protected $casts =[
        'tried_again'=>'boolean',
        'tracking_data'=>'array',
        'webhook'=>'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function transferRecords(): HasMany
    {
        return $this->hasMany(TransferRecord::class, 'transaction_id');
    }


}
