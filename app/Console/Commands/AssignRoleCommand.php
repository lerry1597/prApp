<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class AssignRoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-role {user_code?} {role_name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role ke user berdasarkan user_code';

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

        $roleName = $this->argument('role_name') ?: $this->ask('Masukkan Nama Role (e.g. super_admin, admin, procurement_officer, technical_approver, vessel_crew_requester)');

        if (empty($roleName)) {
            $this->error('Nama Role tidak boleh kosong.');
            return 1;
        }

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            $this->error("Role [{$roleName}] tidak ditemukan.");
            return 1;
        }

        // Check if already assigned
        if ($user->roles()->where('role_id', $role->id)->exists()) {
            $this->comment("User {$user->name} sudah memiliki role {$role->name}.");
            return 0;
        }

        // Assign role
        $user->roles()->attach($role, [
            'assigned_by' => $user->id, // Fallback ke ID user itu sendiri
            'assigned_at' => now(),
        ]);

        $this->info("Role {$role->name} berhasil di-assign ke user {$user->name}.");

        return 0;
    }
}
