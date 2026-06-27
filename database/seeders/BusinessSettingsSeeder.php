<?php

namespace Database\Seeders;

use App\HelperClasses\BusinessSettingHelper;
use Illuminate\Database\Seeder;

class BusinessSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_phone' => '+962 6 123 4567',
            'company_email' => 'support@tabkhtnaa.com',
            'company_address' => 'عمان، الأردن',
            'company_whatsapp' => '+962791234567',
            'tax_percentage' => '15',
            'delivery_fee' => '2.5',
        ];

        foreach ($defaults as $key => $value) {
            if (BusinessSettingHelper::get($key) === null) {
                BusinessSettingHelper::set($key, $value);
            }
        }
    }
}
