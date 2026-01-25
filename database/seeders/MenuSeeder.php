<?php

namespace Database\Seeders;

use App\Models\BaseMenu;
use App\Models\BaseFunction;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Create CRUD functions
        foreach (['View', 'Create', 'Edit', 'Delete', 'Download', 'Upload'] as $func) {
            BaseFunction::firstOrCreate(['function_name' => $func]);
        }

        $seq = 1;

        // Dashboard
        $this->createMenu('dashboard', 'Dashboard', 'Dashboard', $seq++, '/dashboard', 'bi-grid-1x2');

        // Modules
        $modules = [
            'akuntansi' => ['name' => 'Akuntansi', 'icon' => 'bi-calculator', 'children' => ['aturan-kebijakan' => 'Aturan Kebijakan', 'jurnal-umum' => 'Jurnal Umum', 'laporan-audit-investasi' => 'Laporan Audit Investasi', 'laporan-audit-keuangan' => 'Laporan Audit Keuangan', 'laporan-bulanan' => 'Laporan Bulanan']],
            'anggaran' => ['name' => 'Anggaran', 'icon' => 'bi-cash-stack', 'children' => ['aturan-kebijakan' => 'Aturan Kebijakan', 'dokumen-rra' => 'Dokumen RRA', 'laporan-prbc' => 'Laporan PRBC', 'rencana-kerja-direktorat' => 'Rencana Kerja Direktorat', 'rencana-kerja-tahunan' => 'Rencana Kerja Tahunan', 'rencana-kerja-triwulan' => 'Rencana Kerja Triwulan']],
            'hukum-kepatuhan' => ['name' => 'Hukum Kepatuhan', 'icon' => 'bi-shield-check', 'children' => ['kajian-hukum' => 'Kajian Hukum', 'legal-memo' => 'Legal Memo', 'regulasi-internal' => 'Regulasi Internal', 'regulasi-external' => 'Regulasi External', 'kontrak' => 'Kontrak', 'putusan' => 'Putusan', 'compliance-check' => 'Compliance Check']],
            'investasi' => ['name' => 'Investasi', 'icon' => 'bi-graph-up-arrow', 'children' => ['transaksi' => 'Transaksi', 'surat' => 'Surat', 'perencanaan-transaksi' => 'Perencanaan Transaksi', 'perencanaan-surat' => 'Perencanaan Surat', 'propensa-transaksi' => 'Propensa Transaksi', 'propensa-surat' => 'Propensa Surat']],
            'keuangan' => ['name' => 'Keuangan', 'icon' => 'bi-wallet2', 'children' => ['surat-bayar' => 'Surat Bayar', 'spb' => 'SPB', 'sppb' => 'SPPB', 'cashflow' => 'Cashflow', 'penempatan' => 'Penempatan', 'pemindahbukuan' => 'Pemindahbukuan', 'pajak' => 'Pajak']],
            'sdm' => ['name' => 'SDM', 'icon' => 'bi-people', 'children' => ['pks' => 'PKS', 'rarus' => 'Rarus', 'peraturan' => 'Peraturan', 'rekrut-masuk' => 'Rekrut Masuk', 'promosi-mutasi' => 'Promosi Mutasi', 'naik-gaji' => 'Naik Gaji', 'surat-masuk' => 'Surat Masuk', 'surat-keluar' => 'Surat Keluar']],
            'sekretariat' => ['name' => 'Sekretariat', 'icon' => 'bi-journal-text', 'children' => ['risalah-rapat' => 'Risalah Rapat', 'materi' => 'Materi', 'laporan' => 'Laporan', 'surat' => 'Surat', 'pengadaan' => 'Pengadaan']],
            'logistik' => ['name' => 'Logistik Sarpen', 'icon' => 'bi-box-seam', 'children' => ['procurement' => 'Procurement', 'cleaning-service' => 'Cleaning Service', 'keamanan' => 'Keamanan', 'kendaraan' => 'Kendaraan', 'sarana-penunjang' => 'Sarana Penunjang', 'smk3' => 'SMK3', 'polis-asuransi' => 'Polis Asuransi', 'jaminan' => 'Jaminan']],
        ];

        foreach ($modules as $code => $data) {
            $parent = $this->createMenu($code, $data['name'], $data['name'], $seq++, '#', $data['icon']);
            $childSeq = 1;
            foreach ($data['children'] as $childCode => $childName) {
                $this->createMenu("{$code}.{$childCode}", $childName, $childName, $childSeq++, "/{$code}/{$childCode}", null, $parent->id);
            }
        }

        // Master Data
        $master = $this->createMenu('master', 'Master Data', 'Master Data', $seq++, '#', 'bi-database');
        $this->createMenu('master.divisi', 'Divisi', 'Divisi', 1, '/master/divisi', null, $master->id);
        $this->createMenu('master.department', 'Department', 'Department', 2, '/master/department', null, $master->id);
        $this->createMenu('master.jabatan', 'Jabatan', 'Jabatan', 3, '/master/jabatan', null, $master->id);

        // System Admin
        $admin = $this->createMenu('admin', 'System Admin', 'System Admin', $seq++, '#', 'bi-gear');
        $this->createMenu('admin.menus', 'Menu', 'Menu', 1, '/admin/menus', null, $admin->id);
        $this->createMenu('admin.roles', 'Role', 'Role', 2, '/admin/roles', null, $admin->id);
        $this->createMenu('admin.users', 'User', 'User', 3, '/admin/users', null, $admin->id);
        $this->createMenu('admin.document-assignment', 'Document Assignment', 'Document Assignment', 4, '/admin/document-assignment', null, $admin->id);

        // Access Management
        $this->createMenu('access', 'Akses Dokumen', 'Akses Dokumen', $seq++, '/access', 'bi-key');

        // My Documents (Approved Access)
        $this->createMenu('my-documents', 'Dokumen Saya', 'Dokumen Saya', $seq++, '/my-documents', 'bi-folder');

        // My Requests
        $this->createMenu('my-requests', 'Permintaan Saya', 'Permintaan Saya', $seq++, '/my-requests', 'bi-file-earmark-lock');

        $this->command->info('✅ Menus seeded! Total: ' . BaseMenu::count());
    }

    protected function createMenu($code, $name, $label, $seq, $path, $icon = null, $parentId = null)
    {
        return BaseMenu::firstOrCreate(['code_name' => $code], [
            'code_name' => $code,
            'menu_name' => $name,
            'menu_label' => $label,
            'sequence' => $seq,
            'path' => $path,
            'icon' => $icon,
            'parent_id' => $parentId,
        ]);
    }
}
