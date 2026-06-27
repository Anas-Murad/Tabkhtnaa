<?php

namespace App\Observers;

use App\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

class AuditTrailObserver
{
    public function created(Model $model): void
    {
        AuditService::log('created', $model);
    }

    public function updated(Model $model): void
    {
        AuditService::log('updated', $model);
    }

    public function deleted(Model $model): void
    {
        AuditService::log('deleted', $model);
    }
}
