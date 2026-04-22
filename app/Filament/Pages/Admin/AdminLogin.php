<?php

namespace App\Filament\Pages\Admin;

use Faker\Provider\Base;
use Filament\Auth\Http\Responses\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Notifications\Notification;
use Illuminate\Validation\ValidationException;

class AdminLogin extends BaseLogin
{
    public function authenticate(): ?LoginResponse
    {
        try {
            return parent::authenticate();
        } catch (\Throwable $e) {
            report($e);

            // If it's a validation exception (field-related), rethrow so
            // the form shows errors on the appropriate fields (email/password).
            if ($e instanceof ValidationException) {
                throw $e;
            }

            // For other errors, show a notification (alert) and stop gracefully.
            Notification::make()
                ->title(__('Login failed (Admin)'))
                ->body(__('A system error has occurred. Please try again.'))
                ->danger()
                ->send();

            return null;
        }
    }
}
