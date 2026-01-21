<?php

use App\Models\BaseMenu;
use App\Models\BaseFunction;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$menus = BaseMenu::with('children')
    ->whereNull('parent_id')
    ->orderBy('sequence')
    ->get();

$functions = BaseFunction::all();

echo "Total Root Menus: " . $menus->count() . "\n";
echo "Total Functions: " . $functions->count() . "\n";

foreach ($menus as $menu) {
    echo "Menu: " . $menu->menu_name . " (ID: " . $menu->id . ")\n";
    echo "  Children Count: " . $menu->children->count() . "\n";
    if ($menu->children->count() > 0) {
        foreach ($menu->children as $child) {
             echo "    Child: " . $child->menu_name . " (ID: " . $child->id . ")\n";
        }
    }
}
