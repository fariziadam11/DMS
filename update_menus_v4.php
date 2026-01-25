<?php

use Illuminate\Contracts\Console\Kernel;
use App\Models\BaseMenu;
use App\Models\BaseMenuFunction;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

echo "Starting Menu Updates...\n";

// 1. Hide Menu 71 (Document Assignment)
$menu71 = BaseMenu::find(71);
if ($menu71) {
    $menu71->delete(); // Soft Delete to hide
    echo "Menu 71 (Document Assignment) Hidden (Soft Deleted).\n";
}

// 2. Refactor Menu 69 (Akses Dokumen) to be Parent
$menu69 = BaseMenu::find(69);
if ($menu69) {
    $menu69->update([
        'code_name' => 'access', // Keep access
        'menu_name' => 'Akses Dokumen',
        'route' => '#', // No route for parent
        'path' => '#',
        'parent_id' => null, // Ensure is root
    ]);
    echo "Menu 69 updated to Parent.\n";
} else {
    // Create if missing?
    $menu69 = BaseMenu::create([
        'id' => 69,
        'menu_name' => 'Akses Dokumen',
        'code_name' => 'access',
        'route' => '#',
        'parent_id' => null,
    ]);
     echo "Menu 69 Created.\n";
}

// 3. Create Child "Permintaan Akses" (Route: access.index)
// We need a code_name. 'access.index' maybe?
$permintaanAkses = BaseMenu::where('menu_name', 'Permintaan Akses')->where('parent_id', 69)->first();
if (!$permintaanAkses) {
    BaseMenu::create([
        'menu_name' => 'Permintaan Akses',
        'code_name' => 'access.index', // Route name prefix
        'parent_id' => 69,
        'id_module' => $menu69->id_module, // Same module
        'id_group' => $menu69->id_group,
        'sequence' => 1,
        'path' => 'access', // URL path
        'icon' => 'bi-file-earmark-lock',
    ]);
    echo "Child Menu 'Permintaan Akses' Created.\n";
}

// 4. Move "Dokumen Disetujui" (ID 73 - Dokumen Saya)
$menu73 = BaseMenu::find(73);
if ($menu73) {
    $menu73->update([
        'menu_name' => 'Dokumen Disetujui',
        'parent_id' => 69,
        'sequence' => 2,
    ]);
    echo "Menu 73 (Dokumen Saya) renamed to Dokumen Disetujui and moved to parent 69.\n";
}

// 5. Move "Riwayat Permintaan" (ID 74 - Permintaan Saya)
$menu74 = BaseMenu::find(74);
if ($menu74) {
    $menu74->update([
        'menu_name' => 'Riwayat Permintaan',
        'parent_id' => 69,
        'sequence' => 3,
    ]);
    echo "Menu 74 (Permintaan Saya) renamed to Riwayat Permintaan and moved to parent 69.\n";
}

// 6. Create "Document Version" (Old Archive)
// Insert to Base Menu.
// Check if exists
$docVerMenu = BaseMenu::where('menu_name', 'Document Version')->first();
if (!$docVerMenu) {
    BaseMenu::create([
        'menu_name' => 'Document Version',
        'code_name' => 'document-versions',
        'parent_id' => null, // Root
        'id_group' => 1, // Dashboard/General group
        'sequence' => 99, // At the end
        'path' => 'document-versions',
        'icon' => 'bi-archive',
    ]);
    echo "Menu 'Document Version' Created.\n";
} else {
    echo "Menu 'Document Version' already exists.\n";
}

echo "Menu Updates Completed.\n";
