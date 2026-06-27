<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistoryStatus extends Model
{
    use Auditable;
    protected $fillable = [
        'order_id',
        'status',
        'action_by_type',
        'action_by_id',
    ];

    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class ,'action_by_id');
    }
}
