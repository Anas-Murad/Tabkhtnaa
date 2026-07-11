<?php

namespace Database\Seeders;

use App\Models\LoyaltyTier;
use Illuminate\Database\Seeder;

class LoyaltyTierSeeder extends Seeder
{
    public function run(): void
    {
        $tiers = [
            [
                'name' => 'Regular',
                'level' => 1,
                'min_lifetime_spending' => 0.00,
                'points_multiplier' => 1.00,
                'min_redemption_points' => 100,
                'birthday_bonus_multiplier' => 1.00,
                'description' => 'المستوى العادي',
                'is_active' => true,
            ],
            [
                'name' => 'Silver',
                'level' => 2,
                'min_lifetime_spending' => 500.00,
                'points_multiplier' => 1.20,
                'min_redemption_points' => 80,
                'birthday_bonus_multiplier' => 1.25,
                'description' => 'مستوى الفضي',
                'is_active' => true,
            ],
            [
                'name' => 'Gold',
                'level' => 3,
                'min_lifetime_spending' => 1500.00,
                'points_multiplier' => 1.50,
                'min_redemption_points' => 50,
                'birthday_bonus_multiplier' => 1.50,
                'description' => 'مستوى الذهبي',
                'is_active' => true,
            ],
            [
                'name' => 'Platinum',
                'level' => 4,
                'min_lifetime_spending' => 4000.00,
                'points_multiplier' => 2.00,
                'min_redemption_points' => 0,
                'birthday_bonus_multiplier' => 2.00,
                'description' => 'مستوى البلاتيني',
                'is_active' => true,
            ],
        ];

        foreach ($tiers as $tier) {
            LoyaltyTier::updateOrCreate(['name' => $tier['name']], $tier);
        }
    }
}
