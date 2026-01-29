<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardMenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Get parent menu IDs by code_name
        $parentMenus = [
            'akuntansi' => DB::table('base_menus')->where('code_name', 'akuntansi')->value('id'),
            'anggaran' => DB::table('base_menus')->where('code_name', 'anggaran')->value('id'),
            'hukum-kepatuhan' => DB::table('base_menus')->where('code_name', 'hukum-kepatuhan')->value('id'),
            'investasi' => DB::table('base_menus')->where('code_name', 'investasi')->value('id'),
            'keuangan' => DB::table('base_menus')->where('code_name', 'keuangan')->value('id'),
            'sdm' => DB::table('base_menus')->where('code_name', 'sdm')->value('id'),
            'sekretariat' => DB::table('base_menus')->where('code_name', 'sekretariat')->value('id'),
            'logistik' => DB::table('base_menus')->where('code_name', 'logistik')->value('id'),
        ];

        // Dashboard menus to insert
        $dashboardMenus = [
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['akuntansi'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.akuntansi',
                'menu_name' => 'Dashboard Akuntansi',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/akuntansi',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['anggaran'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.anggaran',
                'menu_name' => 'Dashboard Anggaran',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/anggaran',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['hukum-kepatuhan'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.hukum-kepatuhan',
                'menu_name' => 'Dashboard Hukum & Kepatuhan',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/hukum-kepatuhan',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['investasi'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.investasi',
                'menu_name' => 'Dashboard Investasi',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/investasi',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['keuangan'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.keuangan',
                'menu_name' => 'Dashboard Keuangan',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/keuangan',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['sdm'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.sdm',
                'menu_name' => 'Dashboard SDM',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/sdm',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['sekretariat'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.sekretariat',
                'menu_name' => 'Dashboard Sekretariat',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/sekretariat',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id_group' => 0,
                'id_module' => 0,
                'parent_id' => $parentMenus['logistik'],
                'parent_sub_id' => null,
                'parent_sub_parent_id' => null,
                'code_name' => 'dashboard.logistik',
                'menu_name' => 'Dashboard Logistik',
                'menu_label' => 'Dashboard',
                'sequence' => 0,
                'path' => '/dashboard/logistik',
                'icon' => 'bi-pie-chart',
                'icon_type' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Insert dashboard menus
        foreach ($dashboardMenus as $menu) {
            // Check if menu already exists
            $exists = DB::table('base_menus')
                ->where('code_name', $menu['code_name'])
                ->exists();

            if (!$exists) {
                DB::table('base_menus')->insert($menu);
                $this->command->info("Created menu: {$menu['menu_name']}");
            } else {
                $this->command->warn("Menu already exists: {$menu['menu_name']}");
            }
        }

        // Optionally assign view privilege to Super Admin (role_id = 1)
        $viewFunctionId = DB::table('base_functions')->where('name', 'view')->value('id');
        $superAdminRoleId = 1; // Adjust if different

        if ($viewFunctionId) {
            $dashboardMenuIds = DB::table('base_menus')
                ->where('code_name', 'LIKE', 'dashboard.%')
                ->pluck('id');

            foreach ($dashboardMenuIds as $menuId) {
                $privilegeExists = DB::table('base_privileges')
                    ->where('role_id', $superAdminRoleId)
                    ->where('menu_id', $menuId)
                    ->where('function_id', $viewFunctionId)
                    ->exists();

                if (!$privilegeExists) {
                    DB::table('base_privileges')->insert([
                        'role_id' => $superAdminRoleId,
                        'menu_id' => $menuId,
                        'function_id' => $viewFunctionId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $this->command->info("Assigned view privileges to Super Admin for dashboard menus");
        }
    }
}
