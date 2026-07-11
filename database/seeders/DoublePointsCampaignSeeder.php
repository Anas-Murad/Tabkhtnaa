<?php

namespace Database\Seeders;

use App\Models\DoublePointsCampaign;
use Illuminate\Database\Seeder;

class DoublePointsCampaignSeeder extends Seeder
{
    public function run(): void
    {
        DoublePointsCampaign::updateOrCreate(
            ['name' => 'عرض ترحيبي'],
            [
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonths(3)->toDateString(),
                'multiplier' => 2.0,
                'applies_to' => 'all',
                'is_active' => true,
            ]
        );
    }
}
