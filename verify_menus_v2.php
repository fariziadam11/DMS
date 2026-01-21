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

$menusWithChildren = $menus->filter(function ($menu) {
    return $menu->children->count() > 0;
});

echo "Menus with children: " . $menusWithChildren->count() . "\n";

foreach ($menusWithChildren as $menu) {
    echo "Parent: " . $menu->menu_name . " (ID: " . $menu->id . ")\n";
    foreach ($menu->children as $child) {
        echo " - Child: " . $child->menu_name . " (ID: " . $child->id . ")\n";
    }
    echo "----\n";
}

$functions = BaseFunction::all();
echo "Functions Count: " . $functions->count() . "\n";
foreach($functions as $f) {
    echo "Func: " . $f->function_name . " (ID: " . $f->id . ")\n";
}
