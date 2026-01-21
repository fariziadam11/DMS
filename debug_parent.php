<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$child = App\Models\BaseMenu::where('code_name', 'akuntansi.aturan-kebijakan')->first();
if ($child) {
    echo "Child: " . $child->id . " (" . $child->menu_name . ") | Parent ID: " . $child->parent_id . "\n";
    if ($child->parent_id) {
        $parent = App\Models\BaseMenu::find($child->parent_id);
        echo "Parent: " . $parent->id . " (" . $parent->menu_name . ")\n";
    }
} else {
    echo "Child 'akuntansi.aturan-kebijakan' NOT FOUND.\n";
}
