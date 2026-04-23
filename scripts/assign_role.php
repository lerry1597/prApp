<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Role;

$email = 'admin.best@patin.co.id';
$user = User::where('email', $email)->first();
$role = Role::where('name', 'super_admin')->first();

if ($user && $role) {
    $user->roles()->syncWithoutDetaching([
        $role->id => [
            'assigned_by' => $user->id,
            'assigned_at' => now(),
        ]
    ]);
    echo "SUCCESS: Role '{$role->name}' added to {$user->email}\n";
} else {
    echo "FAILED: User or Role not found.\n";
}
