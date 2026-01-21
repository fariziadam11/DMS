<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$privs = App\Models\BasePrivilege::where('id_menu', 3)->get();
echo "Privileges for Menu ID 3 (Aturan Kebijakan): " . $privs->count() . "\n";
foreach($privs as $p) {
    echo "Role: " . $p->id_roles . " | Func: " . $p->id_function . "\n";
}

$privs2 = App\Models\BasePrivilege::where('id_menu', 2)->get();
echo "Privileges for Menu ID 2 (Akuntansi): " . $privs2->count() . "\n";
foreach($privs2 as $p) {
    echo "Role: " . $p->id_roles . " | Func: " . $p->id_function . "\n";
}
