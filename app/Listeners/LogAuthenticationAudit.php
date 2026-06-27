<?php

namespace App\Listeners;

use App\Services\AuditService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;

class LogAuthenticationAudit
{
    public function handleLogin(Login $event): void
    {
        $user = $event->user;
        $guard = $event->guard;

        if ($guard === 'admin') {
            AuditService::log(
                'login',
                null,
                null,
                ['email' => $user->email ?? null, 'guard' => 'admin'],
                null,
                $user->id
            );

            return;
        }

        AuditService::log(
            'login',
            $user,
            null,
            ['email' => $user->email ?? null, 'guard' => $guard],
            $user->id
        );
    }

    public function handleLogout(Logout $event): void
    {
        $user = $event->user;

        if (!$user) {
            return;
        }

        $guard = $event->guard;

        if ($guard === 'admin') {
            AuditService::log(
                'logout',
                null,
                null,
                ['email' => $user->email ?? null, 'guard' => 'admin'],
                null,
                $user->id
            );

            return;
        }

        AuditService::log(
            'logout',
            $user,
            null,
            ['email' => $user->email ?? null, 'guard' => $guard],
            $user->id
        );
    }
}
