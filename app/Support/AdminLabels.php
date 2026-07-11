<?php

namespace App\Support;

class AdminLabels
{
    public static function message(string $key, ?string $fallback = null): string
    {
        $messageKey = 'messages.' . $key;
        $label = __($messageKey);

        return $label === $messageKey ? ($fallback ?? $key) : $label;
    }

    public static function auditEvent(?string $event): string
    {
        return self::message('audit_event_' . $event, $event ?? '-');
    }

    public static function complaintType(?string $type): string
    {
        return self::message('complaint_type_' . $type, $type ?? '-');
    }

    public static function complaintStatus(?string $status): string
    {
        if (!$status) {
            return '-';
        }

        return self::message('complaint_status_' . $status, $status);
    }

    public static function sanctionType(?string $type): string
    {
        return self::message('sanction_type_' . $type, $type ?? '-');
    }

    public static function sanctionSeen(?string $seen): string
    {
        return self::message('sanction_seen_' . $seen, $seen ?? '-');
    }

    public static function mealType(?string $type): string
    {
        return self::message('meal_type_' . str_replace('-', '_', $type ?? ''), $type ?? '-');
    }

    public static function mealAdminStatus(?string $status): string
    {
        return self::message('meal_admin_status_' . $status, $status ?? '-');
    }

    public static function offerType(?string $type): string
    {
        return self::message('offer_type_' . $type, $type ?? '-');
    }
}
