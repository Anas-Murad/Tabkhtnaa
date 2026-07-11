<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltySetting extends Model
{
    protected $fillable = [
        'points_per_dinar',
        'min_redemption_points',
        'points_expiry_months',
        'referral_referrer_points',
        'referral_referred_points',
        'enable_points_system',
        'enable_min_redemption',
        'enable_expiry',
        'enable_auto_redemption',
        'enable_double_points',
        'enable_tiers',
        'enable_welcome_bonus',
        'enable_birthday_bonus',
        'enable_referral',
        'welcome_bonus_points',
        'birthday_bonus_points',
    ];

    protected $casts = [
        'enable_points_system' => 'boolean',
        'enable_min_redemption' => 'boolean',
        'enable_expiry' => 'boolean',
        'enable_auto_redemption' => 'boolean',
        'enable_double_points' => 'boolean',
        'enable_tiers' => 'boolean',
        'enable_welcome_bonus' => 'boolean',
        'enable_birthday_bonus' => 'boolean',
        'enable_referral' => 'boolean',
    ];
}
