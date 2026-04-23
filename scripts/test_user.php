<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

echo "Available Roles: " . Role::pluck('name')->implode(', ') . "\n";

$email = 'admin.best@patin.co.id';
$user = User::where('email', $email)->first();

if ($user) {
    echo "USER FOUND: {$user->email}\n";
    echo "Current Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
} else {
    echo "USER NOT FOUND\n";
}
