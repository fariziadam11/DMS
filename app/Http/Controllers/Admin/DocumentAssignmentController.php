<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterDivisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DocumentAssignmentController extends Controller
{
    /**
     * Map of module slug to configuration
     *
     * @var array
     */
    protected $modules = [
        // Akuntansi
        'akuntansi-aturan-kebijakan' => ['table' => 'akuntansi_aturan_kebijakan', 'name' => 'Akuntansi - Aturan Kebijakan'],
        'akuntansi-jurnal-umum' => ['table' => 'akuntansi_jurnal_umum', 'name' => 'Akuntansi - Jurnal Umum'],
        'akuntansi-laporan-audit-investasi' => ['table' => 'akuntansi_laporan_audit_investasi', 'name' => 'Akuntansi - Lap. Audit Investasi'],
        'akuntansi-laporan-audit-keuangan' => ['table' => 'akuntansi_laporan_audit_keuangan', 'name' => 'Akuntansi - Lap. Audit Keuangan'],
        'akuntansi-laporan-bulanan' => ['table' => 'akuntansi_laporan_bulanan', 'name' => 'Akuntansi - Laporan Bulanan'],

        // Anggaran
        'anggaran-aturan-kebijakan' => ['table' => 'anggaran_aturan_kebijakan', 'name' => 'Anggaran - Aturan Kebijakan'],
        'anggaran-dokumen-rra' => ['table' => 'anggaran_dokumen_rra', 'name' => 'Anggaran - Dokumen RRA'],
        'anggaran-laporan-prbc' => ['table' => 'anggaran_laporan_prbc', 'name' => 'Anggaran - Laporan PRBC'],
        'anggaran-rencana-kerja-direktorat' => ['table' => 'anggaran_rencana_kerja_direktorat', 'name' => 'Anggaran - Renja Direktorat'],
        'anggaran-rencana-kerja-tahunan' => ['table' => 'anggaran_rencana_kerja_tahunan', 'name' => 'Anggaran - Renja Tahunan'],
        'anggaran-rencana-kerja-triwulan' => ['table' => 'anggaran_rencana_kerja_triwulan', 'name' => 'Anggaran - Renja Triwulan'],

        // Hukum & Kepatuhan
        'hukumkepatuhan-compliance-check' => ['table' => 'hukumkepatuhan_compliance_check', 'name' => 'Hukum & Kepatuhan - Compliance Check'],
        'hukumkepatuhan-executive-summary' => ['table' => 'hukumkepatuhan_executive_summary', 'name' => 'Hukum & Kepatuhan - Executive Summary'],
        'hukumkepatuhan-kajian-hukum' => ['table' => 'hukumkepatuhan_kajian_hukum', 'name' => 'Hukum & Kepatuhan - Kajian Hukum'],
        'hukumkepatuhan-kontrak' => ['table' => 'hukumkepatuhan_kontrak', 'name' => 'Hukum & Kepatuhan - Kontrak'],
        'hukumkepatuhan-legal-memo' => ['table' => 'hukumkepatuhan_legal_memo', 'name' => 'Hukum & Kepatuhan - Legal Memo'],
        'hukumkepatuhan-lembar-keputusan' => ['table' => 'hukumkepatuhan_lembar_keputusan', 'name' => 'Hukum & Kepatuhan - Lembar Keputusan'],
        'hukumkepatuhan-lembar-rekomendasi' => ['table' => 'hukumkepatuhan_lembar_rekomendasi', 'name' => 'Hukum & Kepatuhan - Lembar Rekomendasi'],
        'hukumkepatuhan-penomoran' => ['table' => 'hukumkepatuhan_penomoran', 'name' => 'Hukum & Kepatuhan - Penomoran'],
        'hukumkepatuhan-putusan' => ['table' => 'hukumkepatuhan_putusan', 'name' => 'Hukum & Kepatuhan - Putusan'],
        'hukumkepatuhan-regulasi-external' => ['table' => 'hukumkepatuhan_regulasi_external', 'name' => 'Hukum & Kepatuhan - Regulasi External'],
        'hukumkepatuhan-regulasi-internal' => ['table' => 'hukumkepatuhan_regulasi_internal', 'name' => 'Hukum & Kepatuhan - Regulasi Internal'],

        // Investasi
        'investasi-surat' => ['table' => 'surat', 'name' => 'Investasi - Surat'],
        'investasi-transaksi' => ['table' => 'transaksi', 'name' => 'Investasi - Transaksi'],
        'investasi-perencanaan-surat' => ['table' => 'investasi_perencanaan_surat', 'name' => 'Investasi - Perencanaan Surat'],
        'investasi-perencanaan-transaksi' => ['table' => 'investasi_perencanaan_transaksi', 'name' => 'Investasi - Perencanaan Transaksi'],
        'investasi-propensa-surat' => ['table' => 'investasi_propensa_surat', 'name' => 'Investasi - Propensa Surat'],
        'investasi-propensa-transaksi' => ['table' => 'investasi_propensa_transaksi', 'name' => 'Investasi - Propensa Transaksi'],

        // Keuangan
        'keuangan-surat-bayar' => ['table' => 'keuangan_surat_bayar', 'name' => 'Keuangan - Surat Bayar'],
        'keuangan-spb' => ['table' => 'keuangan_spb', 'name' => 'Keuangan - SPB'],
        'keuangan-sppb' => ['table' => 'keuangan_sppb', 'name' => 'Keuangan - SPPB'],
        'keuangan-cashflow' => ['table' => 'keuangan_cashflow', 'name' => 'Keuangan - Cashflow'],
        'keuangan-penempatan' => ['table' => 'keuangan_penempatan', 'name' => 'Keuangan - Penempatan'],
        'keuangan-pemindahbukuan' => ['table' => 'keuangan_pemindahbukuan', 'name' => 'Keuangan - Pemindahbukuan'],
        'keuangan-pajak' => ['table' => 'keuangan_pajak', 'name' => 'Keuangan - Pajak'],

        // Logistik
        'logistiksarpen-procurement' => ['table' => 'logistiksarpen_procurement', 'name' => 'Logistik - Procurement'],
        'logistiksarpen-cleaning-service' => ['table' => 'logistiksarpen_cleaning_service', 'name' => 'Logistik - Cleaning Service'],
        'logistiksarpen-keamanan' => ['table' => 'logistiksarpen_keamanan', 'name' => 'Logistik - Keamanan'],
        'logistiksarpen-kendaraan' => ['table' => 'logistiksarpen_kendaraan', 'name' => 'Logistik - Kendaraan'],
        'logistiksarpen-sarana-penunjang' => ['table' => 'logistiksarpen_sarana_penunjang', 'name' => 'Logistik - Sarana Penunjang'],
        'logistiksarpen-smk3' => ['table' => 'logistiksarpen_smk3', 'name' => 'Logistik - SMK3'],
        'logistiksarpen-polis-asuransi' => ['table' => 'logistiksarpen_polis_asuransi', 'name' => 'Logistik - Polis Asuransi'],
        'logistiksarpen-jaminan' => ['table' => 'logistiksarpen_jaminan', 'name' => 'Logistik - Jaminan'],
        'logistiksarpen-pelaporan-prbc' => ['table' => 'logistiksarpen_pelaporan_prbc', 'name' => 'Logistik - Pelaporan PRBC'],
        'logistiksarpen-user-satisfaction' => ['table' => 'logistiksarpen_user_satisfaction', 'name' => 'Logistik - User Satisfaction'],
        'logistiksarpen-vendor-satisfaction' => ['table' => 'logistiksarpen_vendor_satisfaction', 'name' => 'Logistik - Vendor Satisfaction'],

        // SDM
        'sdm-pks' => ['table' => 'sdm_pks', 'name' => 'SDM - PKS'],
        'sdm-peraturan' => ['table' => 'sdm_peraturan', 'name' => 'SDM - Peraturan'],
        'sdm-aspurjab' => ['table' => 'sdm_aspurjab', 'name' => 'SDM - Aspurjab'],
        'sdm-capeg-pegrus' => ['table' => 'sdm_capeg_pegrus', 'name' => 'SDM - Capeg Pegrus'],
        'sdm-ikut-organisasi' => ['table' => 'sdm_ikut_organisasi', 'name' => 'SDM - Ikut Organisasi'],
        'sdm-naik-gaji' => ['table' => 'sdm_naik_gaji', 'name' => 'SDM - Naik Gaji'],
        'sdm-penghargaan' => ['table' => 'sdm_penghargaan', 'name' => 'SDM - Penghargaan'],
        'sdm-promosi-mutasi' => ['table' => 'sdm_promosi_mutasi', 'name' => 'SDM - Promosi Mutasi'],
        'sdm-rarus' => ['table' => 'sdm_rarus', 'name' => 'SDM - Rarus'],
        'sdm-rekon' => ['table' => 'sdm_rekon', 'name' => 'SDM - Rekon'],
        'sdm-rekrut-masuk' => ['table' => 'sdm_rekrut_masuk', 'name' => 'SDM - Rekrut Masuk'],
        'sdm-surat-keluar' => ['table' => 'sdm_surat_keluar', 'name' => 'SDM - Surat Keluar'],
        'sdm-surat-masuk' => ['table' => 'sdm_surat_masuk', 'name' => 'SDM - Surat Masuk'],

        // Sekretariat
        'sekretariat-risalah-rapat' => ['table' => 'sekretariat_risalah_rapat', 'name' => 'Sekretariat - Risalah Rapat'],
        'sekretariat-materi' => ['table' => 'sekretariat_materi', 'name' => 'Sekretariat - Materi'],
        'sekretariat-laporan' => ['table' => 'sekretariat_laporan', 'name' => 'Sekretariat - Laporan'],
        'sekretariat-surat' => ['table' => 'sekretariat_surat', 'name' => 'Sekretariat - Surat'],
        'sekretariat-pengadaan' => ['table' => 'sekretariat_pengadaan', 'name' => 'Sekretariat - Pengadaan'],
        'sekretariat-remunerasi-pedoman' => ['table' => 'sekretariat_remunerasi_pedoman', 'name' => 'Sekretariat - Remunerasi Pedoman'],
        'sekretariat-remunerasi-dokumen' => ['table' => 'sekretariat_remunerasi_dokumen', 'name' => 'Sekretariat - Remunerasi Dokumen'],
    ];

    /**
     * List all document tables with division assignment overview
     */
    public function index(Request $request)
    {
        $divisions = MasterDivisi::get(); // Removing specific relationship counts as they were incomplete

        // Get document statistics per division
        $documentStats = $this->getDocumentStats($request->input('id_divisi'));

        return view('admin.document-assignment.index', [
            'divisions' => $divisions,
            'documentStats' => $documentStats,
            'selectedDivision' => $request->input('id_divisi'),
        ]);
    }

    /**
     * Show documents for a specific module that can be reassigned
     */
    public function showModule(Request $request, string $module)
    {
        $divisions = MasterDivisi::orderBy('nama_divisi')->get();
        $selectedDivision = $request->input('id_divisi');

        if (!array_key_exists($module, $this->modules)) {
            abort(404, 'Modul tidak ditemukan');
        }

        // Get documents based on module
        $documents = $this->getDocumentsByModule($module, $selectedDivision, $request->input('search'));

        return view('admin.document-assignment.module', [
            'module' => $module,
            'moduleName' => $this->modules[$module]['name'],
            'divisions' => $divisions,
            'documents' => $documents,
            'selectedDivision' => $selectedDivision,
        ]);
    }

    /**
     * Bulk reassign documents to a different division
     */
    public function reassign(Request $request)
    {
        $validated = $request->validate([
            'document_ids' => 'required|array|min:1',
            'document_ids.*' => 'integer',
            'module' => 'required|string',
            'new_divisi_id' => 'required|exists:master_divisi,id',
        ]);

        $table = $this->getTableName($validated['module']);

        if (!$table) {
            return back()->with('error', 'Modul tidak valid');
        }

        $count = DB::table($table)
            ->whereIn('id', $validated['document_ids'])
            ->update([
                'id_divisi' => $validated['new_divisi_id'],
                'updated_at' => now(),
                'updated_by' => auth()->id(),
            ]);

        return back()->with('success', "{$count} dokumen berhasil dipindahkan ke divisi baru");
    }

    /**
     * Get statistics for documents per division
     */
    protected function getDocumentStats(?int $divisionId = null): array
    {
        $stats = [];
        foreach ($this->modules as $slug => $config) {
            $table = $config['table'];
            $name = $config['name'];

            try {
                $query = DB::table($table)->whereNull('deleted_at');

                if ($divisionId) {
                    $query->where('id_divisi', $divisionId);
                }

                $total = $query->count();
                $unassigned = DB::table($table)->whereNull('deleted_at')->whereNull('id_divisi')->count();

                $stats[] = [
                    'table' => $table, // Keep generic key
                    'module_slug' => $slug, // Send slug for linking
                    'name' => $name,
                    'total' => $total,
                    'unassigned' => $unassigned,
                ];
            } catch (\Exception $e) {
                // Table might not exist or err
                continue;
            }
        }

        return $stats;
    }

    protected function getDocumentsByModule(string $module, ?int $divisionId = null, ?string $search = null)
    {
        $table = $this->getTableName($module);

        if (!$table) {
            return new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20);
        }

        $query = DB::table($table)
            ->leftJoin('master_divisi', "{$table}.id_divisi", '=', 'master_divisi.id')
            ->whereNull("{$table}.deleted_at")
            ->select([
                "{$table}.id",
                "{$table}.id_divisi",
                "{$table}.sifat_dokumen",
                "{$table}.created_at",
                'master_divisi.nama_divisi',
            ]);

        // Add optional columns if they exist
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        if (in_array('judul', $columns)) {
            $query->addSelect("{$table}.judul");
        }
        if (in_array('perihal', $columns)) {
            $query->addSelect("{$table}.perihal");
        }
        if (in_array('nomor', $columns)) {
            $query->addSelect("{$table}.nomor");
        }
        if (in_array('tanggal', $columns)) {
            $query->addSelect("{$table}.tanggal");
        }
        if (in_array('nama', $columns)) {
            $query->addSelect("{$table}.nama");
        }
        if (in_array('file_name', $columns)) {
            $query->addSelect("{$table}.file_name");
        }

        if ($divisionId) {
            if ($divisionId == -1) {
                // Unassigned documents
                $query->whereNull("{$table}.id_divisi");
            } else {
                $query->where("{$table}.id_divisi", $divisionId);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($table, $search, $columns) {
                if (in_array('judul', $columns)) {
                    $q->orWhere("{$table}.judul", 'like', "%{$search}%");
                }
                if (in_array('perihal', $columns)) {
                    $q->orWhere("{$table}.perihal", 'like', "%{$search}%");
                }
                if (in_array('nomor', $columns)) {
                    $q->orWhere("{$table}.nomor", 'like', "%{$search}%");
                }
                if (in_array('nama', $columns)) {
                    $q->orWhere("{$table}.nama", 'like', "%{$search}%");
                }
                if (in_array('file_name', $columns)) {
                    $q->orWhere("{$table}.file_name", 'like', "%{$search}%");
                }
            });
        }

        return $query->orderByDesc("{$table}.created_at")->paginate(20);
    }

    protected function getTableName(string $module): ?string
    {
        return $this->modules[$module]['table'] ?? null;
    }

    protected function getModuleName(string $module): string
    {
        return $this->modules[$module]['name'] ?? ucwords(str_replace('-', ' ', $module));
    }
}
