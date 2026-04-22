<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeFilamentUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Using the same name as the vendor command will cause the app command
     * to override the vendor one when registered by the application.
     *
     * @var string
     */
    protected $signature = 'make:filament-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a Filament user (prompts for username).';

    public function handle(): int
    {
        $name = $this->ask('Name');
        $email = $this->ask('Email');

        // Ask for username explicitly (non-nullable requirement)
        $username = $this->ask('Username');

        // Password (hidden)
        $password = $this->secret('Password');
        $passwordConfirm = $this->secret('Confirm Password');

        if ($password === null || $password === '') {
            $this->error('Password is required.');
            return 1;
        }

        if ($password !== $passwordConfirm) {
            $this->error('Passwords do not match.');
            return 1;
        }

        $modelClass = config('auth.providers.users.model', \App\Models\User::class);

        if (!class_exists($modelClass)) {
            $this->error("User model [$modelClass] not found.");
            return 1;
        }

        $data = [
            'name' => $name,
            'email' => $email,
            'username' => $username,
            'password' => $password,
        ];

        try {
            $modelClass::create($data);
            $this->info('User created successfully.');
            return 0;
        } catch (\Throwable $e) {
            $this->error('Failed to create user: ' . $e->getMessage());
            return 1;
        }
    }
}
