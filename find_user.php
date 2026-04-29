<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Department;

foreach (Department::all() as $d) {
    echo "ID: {$d->id} - NAME: {$d->name}\n";
}
