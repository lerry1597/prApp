<?php

namespace App\Filament\Pages\Auth;

use App\Constants\RoleConstant;
use App\Models\User;
use Filament\Auth\Pages\Login as BaseAuth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;
use App\Models\LoginActivity;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseAuth
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
                Hidden::make('latitude')
                    ->extraAttributes([
                        'x-on:location-captured.window' => '$wire.set(\'data.latitude\', $event.detail.lat)',
                    ]),
                Hidden::make('longitude')
                    ->extraAttributes([
                        'x-on:location-captured.window' => '$wire.set(\'data.longitude\', $event.detail.lng)',
                    ]),
            ]);
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('login')
            ->label(__('Email / Username / User Code / ID Karyawan / Nama Kapal'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();
            $login = $data['login'];

            $lat = $data['latitude'] ?? null;
            $lng = $data['longitude'] ?? null;

            if (empty($lat) || empty($lng)) {
                \Filament\Notifications\Notification::make()
                    ->warning()
                    ->color('warning')
                    ->title('Izin Lokasi Wajib')
                    ->body('Mohon izinkan akses lokasi terlebih dahulu melalui jendela yang muncul sebelum login.')
                    ->persistent()
                    ->send();
                
                return null;
            }

            $logData = [
                'identifier' => $login,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ];

            $googleLocation = null;
            $location = null;

            if ($lat && $lng) {
                $googleLocation = "https://google.com/maps?q={$lat},{$lng}";
                $location = "Browser Geolocation ({$lat}, {$lng})";
            }

            $logData['location'] = $location;

            // Log Info (Tanpa Password)
            Log::info('Upaya login di App Panel', $logData);

            // Log Debug (Termasuk Password - Hanya tercatat jika LOG_LEVEL=debug)
            Log::debug('Detail upaya login di App Panel (Debug Mode)', array_merge($logData, [
                'password' => $data['password']
            ]));

            // Cari user untuk mendapatkan user_id
            $user = User::where('email', $login)
                ->orWhere('username', $login)
                ->orWhere('user_code', $login)
                ->orWhere('id_employee', $login)
                ->orWhere(function ($query) use ($login) {
                    $query->whereHas('vessel', function ($q) use ($login) {
                        $q->where('name', $login);
                    })
                    ->where('is_primary', true)
                    ->whereHas('roles', function ($q) {
                        $q->where('name', RoleConstant::VESSEL_CREW_REQUESTER);
                    });
                })
                ->first();

            // Catat ke database tabel login_activities
            $activity = LoginActivity::create([
                'user_id' => $user?->id,
                'identifier' => $login,
                'ip_address' => $logData['ip_address'],
                'user_agent' => $logData['user_agent'],
                'location' => $logData['location'] ?? null,
                'latitude' => $lat,
                'longitude' => $lng,
                'google_location' => $googleLocation,
                'is_successful' => false,
            ]);

            $response = parent::authenticate();

            // Jika sukses, update status
            $activity->update(['is_successful' => true]);

            return $response;

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        $login = $data['login'];

        $user = User::where('email', $login)
            ->orWhere('username', $login)
            ->orWhere('user_code', $login)
            ->orWhere('id_employee', $login)
            ->orWhere(function ($query) use ($login) {
                $query->whereHas('vessel', function ($q) use ($login) {
                    $q->where('name', $login);
                })
                ->where('is_primary', true)
                ->whereHas('roles', function ($q) {
                    $q->where('name', RoleConstant::VESSEL_CREW_REQUESTER);
                });
            })
            ->first();

        return [
            'email' => $user ? $user->email : $login,
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.login' => __('Login gagal. Silakan periksa kembali data login dan password Anda.'),
        ]);
    }
}
