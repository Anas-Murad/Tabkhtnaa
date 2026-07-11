<?php

namespace App\Support;

class LoyaltyLabels
{
    public static function transactionType(?string $type): string
    {
        if (!$type) {
            return '-';
        }

        $key = 'messages.loyalty_type_' . $type;
        $label = __($key);

        return $label === $key ? $type : $label;
    }

    public static function tierName(?string $name): string
    {
        if (!$name) {
            return '-';
        }

        $key = 'messages.loyalty_tier_' . $name;
        $label = __($key);

        return $label === $key ? $name : $label;
    }

    public static function appliesTo(?string $value): string
    {
        if (!$value) {
            return '-';
        }

        $key = 'messages.loyalty_applies_' . $value;
        $label = __($key);

        return $label === $key ? $value : $label;
    }

    public static function transactionTypes(): array
    {
        return [
            'earn' => self::transactionType('earn'),
            'redeem' => self::transactionType('redeem'),
            'bonus' => self::transactionType('bonus'),
            'expiry' => self::transactionType('expiry'),
            'welcome' => self::transactionType('welcome'),
            'birthday' => self::transactionType('birthday'),
            'referral' => self::transactionType('referral'),
        ];
    }
}
