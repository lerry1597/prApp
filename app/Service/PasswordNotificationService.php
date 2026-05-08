<?php

namespace App\Service;

use App\Jobs\SendPasswordNotification;
use App\Models\User;

class PasswordNotificationService
{
    /**
     * Dispatch a non-blocking notification containing the user's plain-text password
     * via email and WhatsApp.
     *
     * This is fire-and-forget: the caller does NOT wait for delivery.
     * Implement the actual channels inside SendPasswordNotification::handle().
     */
    public static function dispatch(User $user, string $plainPassword): void
    {
        SendPasswordNotification::dispatch($user->id, $plainPassword);
    }
}
