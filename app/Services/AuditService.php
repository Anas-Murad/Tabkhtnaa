<?php

namespace App\Services;

use App\Models\AuditTrail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditService
{
    protected static array $sensitiveFields = [
        'password',
        'remember_token',
        'access_token',
        'reset_password_token',
    ];

    public static function log(
        string $event,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $userId = null,
        ?int $adminId = null
    ): ?AuditTrail {
        if ($model && $oldValues === null && $newValues === null) {
            [$oldValues, $newValues] = self::resolveModelChanges($event, $model);
        }

        $oldValues = self::sanitizeValues($oldValues);
        $newValues = self::sanitizeValues($newValues);

        if ($event === 'updated' && empty($oldValues) && empty($newValues)) {
            return null;
        }

        return AuditTrail::create([
            'user_id' => $userId ?? self::resolveUserId(),
            'admin_id' => $adminId ?? self::resolveAdminId(),
            'auditable_type' => $model ? get_class($model) : null,
            'auditable_id' => $model?->getKey(),
            'event' => $event,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'url' => Request::fullUrl(),
            'created_at' => now(),
        ]);
    }

    protected static function resolveModelChanges(string $event, Model $model): array
    {
        $excluded = method_exists($model, 'getAuditExcluded')
            ? $model->getAuditExcluded()
            : self::$sensitiveFields;

        return match ($event) {
            'created' => [null, self::filterAttributes($model->getAttributes(), $excluded)],
            'updated' => [
                self::filterAttributes($model->getOriginal(), $excluded),
                self::filterAttributes($model->getChanges(), $excluded),
            ],
            'deleted' => [self::filterAttributes($model->getAttributes(), $excluded), null],
            default => [null, null],
        };
    }

    protected static function filterAttributes(?array $attributes, array $extraExcluded = []): ?array
    {
        if ($attributes === null) {
            return null;
        }

        $excluded = array_merge(self::$sensitiveFields, ['updated_at'], $extraExcluded);

        return collect($attributes)
            ->except($excluded)
            ->filter(fn ($value) => $value !== null)
            ->all();
    }

    protected static function sanitizeValues(?array $values): ?array
    {
        if ($values === null) {
            return null;
        }

        return collect($values)
            ->map(function ($value, $key) {
                if (in_array($key, self::$sensitiveFields, true)) {
                    return '[REDACTED]';
                }

                return $value;
            })
            ->all();
    }

    protected static function resolveUserId(): ?int
    {
        $user = Auth::guard('sanctum')->user()
            ?? Auth::guard('web')->user()
            ?? Auth::user();

        return $user instanceof \App\Models\User ? $user->id : null;
    }

    protected static function resolveAdminId(): ?int
    {
        $admin = Auth::guard('admin')->user();

        return $admin?->id;
    }
}
