<?php

namespace Database\Seeders;

use App\Models\BaseRole;
use App\Models\BaseUserRole;
use App\Models\MasterDivisi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create divisions
        $divisions = [
            ['kode_divisi' => 'AKT', 'nama_divisi' => 'Akuntansi'],
            ['kode_divisi' => 'ANG', 'nama_divisi' => 'Anggaran'],
            ['kode_divisi' => 'HKP', 'nama_divisi' => 'Hukum & Kepatuhan'],
            ['kode_divisi' => 'INV', 'nama_divisi' => 'Investasi'],
            ['kode_divisi' => 'KEU', 'nama_divisi' => 'Keuangan'],
            ['kode_divisi' => 'SDM', 'nama_divisi' => 'SDM'],
            ['kode_divisi' => 'SEK', 'nama_divisi' => 'Sekretariat'],
            ['kode_divisi' => 'LOG', 'nama_divisi' => 'Logistik & Sarpen'],
        ];

        foreach ($divisions as $div) {
            MasterDivisi::firstOrCreate(['kode_divisi' => $div['kode_divisi']], $div);
        }

        // Create roles (access_scope: global, division, department)
        $roles = [
            ['roles_name' => 'Super Admin', 'access_scope' => 'global'],
            ['roles_name' => 'Division Admin', 'access_scope' => 'division'],
            ['roles_name' => 'Staff', 'access_scope' => 'department'],
            ['roles_name' => 'Viewer', 'access_scope' => 'department'],
        ];

        foreach ($roles as $role) {
            BaseRole::firstOrCreate(['roles_name' => $role['roles_name']], $role);
        }

        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@dapen.com'],
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'is_active' => 1,
                'email_verified_at' => now(),
            ]
        );

        // Assign super admin role
        $superAdminRole = BaseRole::where('roles_name', 'Super Admin')->first();
        if ($superAdminRole && $admin) {
            BaseUserRole::firstOrCreate([
                'id_user' => $admin->id,
                'id_roles' => $superAdminRole->id,
            ]);
        }

        // Create sample staff users for each division
        $staffRole = BaseRole::where('roles_name', 'Staff')->first();
        $sampleUsers = [
            ['name' => 'Staff Akuntansi', 'email' => 'akuntansi@dapen.com', 'username' => 'staff_akt', 'divisi' => 'AKT'],
            ['name' => 'Staff Investasi', 'email' => 'investasi@dapen.com', 'username' => 'staff_inv', 'divisi' => 'INV'],
            ['name' => 'Staff SDM', 'email' => 'sdm@dapen.com', 'username' => 'staff_sdm', 'divisi' => 'SDM'],
        ];

        foreach ($sampleUsers as $userData) {
            $divisi = MasterDivisi::where('kode_divisi', $userData['divisi'])->first();

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'username' => $userData['username'],
                    'password' => Hash::make('password'),
                    'id_divisi' => $divisi ? $divisi->id : null,
                    'id_department' => $divisi ? $divisi->id_department : null,
                    'is_active' => 1,
                    'email_verified_at' => now(),
                ]
            );

            if ($staffRole && $user) {
                BaseUserRole::firstOrCreate([
                    'id_user' => $user->id,
                    'id_roles' => $staffRole->id,
                ]);
            }
        }

        $this->command->info('Initial data seeded successfully!');
        $this->command->info('Admin login: admin@dapen.com / password');
    }
}
