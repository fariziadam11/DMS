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
            'akuntansi'       => DB::table('base_menus')->where('code_name', 'akuntansi')->value('id'),
            'anggaran'        => DB::table('base_menus')->where('code_name', 'anggaran')->value('id'),
            'hukum-kepatuhan' => DB::table('base_menus')->where('code_name', 'hukum-kepatuhan')->value('id'),
            'investasi'       => DB::table('base_menus')->where('code_name', 'investasi')->value('id'),
            'keuangan'        => DB::table('base_menus')->where('code_name', 'keuangan')->value('id'),
            'sdm'             => DB::table('base_menus')->where('code_name', 'sdm')->value('id'),
            'sekretariat'     => DB::table('base_menus')->where('code_name', 'sekretariat')->value('id'),
            'logistik'        => DB::table('base_menus')->where('code_name', 'logistik')->value('id'),
        ];

        // Dashboard menus to insert
        // Set sequence to 0 so it appears first in the submenu list
        $dashboardMenus = [
            [
                'code_name' => 'dashboard.akuntansi',
                'menu_name' => 'Dashboard Akuntansi',
                'parent_key' => 'akuntansi',
                'path' => '/dashboard/akuntansi',
            ],
            [
                'code_name' => 'dashboard.anggaran',
                'menu_name' => 'Dashboard Anggaran',
                'parent_key' => 'anggaran',
                'path' => '/dashboard/anggaran',
            ],
            [
                'code_name' => 'dashboard.hukum-kepatuhan',
                'menu_name' => 'Dashboard Hukum & Kepatuhan',
                'parent_key' => 'hukum-kepatuhan',
                'path' => '/dashboard/hukum-kepatuhan',
            ],
            [
                'code_name' => 'dashboard.investasi',
                'menu_name' => 'Dashboard Investasi',
                'parent_key' => 'investasi',
                'path' => '/dashboard/investasi',
            ],
            [
                'code_name' => 'dashboard.keuangan',
                'menu_name' => 'Dashboard Keuangan',
                'parent_key' => 'keuangan',
                'path' => '/dashboard/keuangan',
            ],
            [
                'code_name' => 'dashboard.sdm',
                'menu_name' => 'Dashboard SDM',
                'parent_key' => 'sdm',
                'path' => '/dashboard/sdm',
            ],
            [
                'code_name' => 'dashboard.sekretariat',
                'menu_name' => 'Dashboard Sekretariat',
                'parent_key' => 'sekretariat',
                'path' => '/dashboard/sekretariat',
            ],
            [
                'code_name' => 'dashboard.logistik',
                'menu_name' => 'Dashboard Logistik',
                'parent_key' => 'logistik',
                'path' => '/dashboard/logistik',
            ],
        ];

        // Insert dashboard menus
        foreach ($dashboardMenus as $menu) {
            // Check if menu already exists
            $exists = DB::table('base_menus')
                ->where('code_name', $menu['code_name'])
                ->exists();

            if (!$exists) {
                // Determine parent ID
                $parentId = $parentMenus[$menu['parent_key']] ?? null;

                if ($parentId) {
                    $insertedId = DB::table('base_menus')->insertGetId([
                        'id_group' => 0,
                        'id_module' => 0,
                        'parent_id' => $parentId,
                        'parent_sub_id' => null,
                        'parent_sub_parent_id' => null,
                        'code_name' => $menu['code_name'],
                        'menu_name' => $menu['menu_name'],
                        'menu_label' => 'Dashboard',
                        'sequence' => 0, // Top of the list
                        'path' => $menu['path'],
                        'icon' => 'bi-pie-chart',
                        'icon_type' => null,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);

                    $this->command->info("Created menu: {$menu['menu_name']} (ID: $insertedId)");
                } else {
                    $this->command->error("Parent Not Found for: {$menu['menu_name']}");
                }
            } else {
                $this->command->warn("Menu already exists: {$menu['menu_name']}");
            }
        }

        // Assign view privilege to Super Admin (role_id = 1)
        // Check BaseFunction table for 'view' function id. Usually 'View' or 'view'
        $viewFunctionId = DB::table('base_functions')->where('function_name', 'View')->value('id');

        // If not found with Capitalized, try lowercase
        if (!$viewFunctionId) {
             $viewFunctionId = DB::table('base_functions')->where('function_name', 'view')->value('id');
        }

        $superAdminRoleId = 1; // Standard ID for Super Admin

        if ($viewFunctionId) {
            $dashboardMenuIds = DB::table('base_menus')
                ->where('code_name', 'LIKE', 'dashboard.%')
                ->pluck('id');

            foreach ($dashboardMenuIds as $menuId) {
                // Check using correct column names from BasePrivilege model
                // id_roles, id_menu, id_function
                $privilegeExists = DB::table('base_privileges')
                    ->where('id_roles', $superAdminRoleId)
                    ->where('id_menu', $menuId)
                    ->where('id_function', $viewFunctionId)
                    ->exists();

                if (!$privilegeExists) {
                    DB::table('base_privileges')->insert([
                        'id_roles' => $superAdminRoleId,
                        'id_menu'  => $menuId,
                        'id_function' => $viewFunctionId,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }

            $this->command->info("Assigned view privileges to Super Admin for dashboard menus");
        } else {
            $this->command->error("Function 'View' not found. Cannot assign privileges.");
        }
    }
}
