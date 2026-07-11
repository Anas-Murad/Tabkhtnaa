<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyTier extends Model
{
    protected $fillable = [
        'name',
        'level',
        'min_lifetime_spending',
        'points_multiplier',
        'min_redemption_points',
        'birthday_bonus_multiplier',
        'description',
        'is_active',
    ];

    protected $casts = [
        'min_lifetime_spending' => 'decimal:2',
        'points_multiplier' => 'decimal:2',
        'birthday_bonus_multiplier' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}
