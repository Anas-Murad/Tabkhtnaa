<?php

namespace App\Traits;

use App\Observers\AuditTrailObserver;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::observe(AuditTrailObserver::class);
    }

    public function getAuditExcluded(): array
    {
        return property_exists($this, 'auditExclude')
            ? $this->auditExclude
            : ['password', 'remember_token'];
    }
}
