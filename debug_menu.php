<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Searching for 'Aturan Kebijakan'...\n";
$menus = App\Models\BaseMenu::where('menu_name', 'like', '%Aturan Kebijakan%')->get();
foreach($menus as $m) {
    echo "ID: " . $m->id . " | Name: " . $m->menu_name . " | Code: " . $m->code_name . "\n";
}
echo "Done.\n";
