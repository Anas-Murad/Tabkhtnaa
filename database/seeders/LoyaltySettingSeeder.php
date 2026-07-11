<?php

namespace Database\Seeders;

use App\Models\LoyaltySetting;
use Illuminate\Database\Seeder;

class LoyaltySettingSeeder extends Seeder
{
    public function run(): void
    {
        LoyaltySetting::firstOrCreate(['id' => 1], [
            'points_per_dinar' => 1,
            'min_redemption_points' => 100,
            'points_expiry_months' => 12,
            'referral_referrer_points' => 100,
            'referral_referred_points' => 50,
            'enable_points_system' => true,
            'enable_min_redemption' => true,
            'enable_expiry' => true,
            'enable_auto_redemption' => false,
            'enable_double_points' => true,
            'enable_tiers' => true,
            'enable_welcome_bonus' => true,
            'enable_birthday_bonus' => true,
            'enable_referral' => true,
            'welcome_bonus_points' => 100,
            'birthday_bonus_points' => 150,
        ]);
    }
}
