<?php

namespace App\HelperClasses;

use App\Models\BusinessSetting;

class BusinessSettingHelper
{
    private static array $cache = [];

    public static function get(string $key, mixed $default = null): mixed
    {
        if (!array_key_exists($key, self::$cache)) {
            self::$cache[$key] = BusinessSetting::where('key', $key)->value('value');
        }

        return self::$cache[$key] ?? $default;
    }

    public static function set(string $key, mixed $value): void
    {
        BusinessSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        self::$cache[$key] = $value;
    }

    public static function taxRate(): float
    {
        return (float) self::get('tax_percentage', 15) / 100;
    }

    public static function deliveryFee(): float
    {
        return (float) self::get('delivery_fee', 2.5);
    }

    public static function deliveryFeesFor(string $deliveryType): float
    {
        return $deliveryType === 'delivery' ? self::deliveryFee() : 0.0;
    }

    public static function companyInfo(): array
    {
        return [
            'phone' => (string) self::get('company_phone', ''),
            'email' => (string) self::get('company_email', ''),
            'address' => (string) self::get('company_address', ''),
            'whatsapp' => (string) self::get('company_whatsapp', ''),
        ];
    }

    public static function appSettings(): array
    {
        return [
            'tax_percentage' => (float) self::get('tax_percentage', 15),
            'delivery_fee' => self::deliveryFee(),
            'company' => self::companyInfo(),
        ];
    }
}
