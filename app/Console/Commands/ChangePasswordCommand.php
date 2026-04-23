<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ChangePasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-password {user_code?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ganti password pengguna berdasarkan kode pengguna (user_code)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $userCode = $this->argument('user_code') ?: $this->ask('Masukkan Kode Pengguna (User Code)');

        if (empty($userCode)) {
            $this->error('Kode Pengguna tidak boleh kosong.');
            return 1;
        }

        $user = User::where('user_code', $userCode)->first();

        if (!$user) {
            $this->error("Pengguna dengan kode [{$userCode}] tidak ditemukan.");
            return 1;
        }

        $this->info("Mengganti password untuk: {$user->name} ({$user->email})");

        $password = $this->secret('Masukkan Password Baru');
        
        if (empty($password)) {
            $this->error('Password tidak boleh kosong.');
            return 1;
        }

        $confirmPassword = $this->secret('Konfirmasi Password Baru');

        if ($password !== $confirmPassword) {
            $this->error('Konfirmasi password tidak cocok.');
            return 1;
        }

        $user->password = $password; // Otomatis di-hash oleh model User karena ada casts
        $user->save();

        $this->info('Password berhasil diperbarui!');

        return 0;
    }
}
