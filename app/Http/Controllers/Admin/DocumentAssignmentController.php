<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterDivisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentAssignmentController extends Controller
{
    /**
     * List all document tables with division assignment overview
     */
    public function index(Request $request)
    {
        $divisions = MasterDivisi::withCount([
            'aturanKebijakanAkuntansi' => fn($q) => $q->whereNull('deleted_at'),
            'jurnalUmum' => fn($q) => $q->whereNull('deleted_at'),
            'transaksiInvestasi' => fn($q) => $q->whereNull('deleted_at'),
        ])->get();

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

        // Get documents based on module
        $documents = $this->getDocumentsByModule($module, $selectedDivision, $request->input('search'));

        return view('admin.document-assignment.module', [
            'module' => $module,
            'moduleName' => $this->getModuleName($module),
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
        $tables = [
            'akuntansi_aturan_kebijakan' => 'Akuntansi - Aturan Kebijakan',
            'akuntansi_jurnal_umum' => 'Akuntansi - Jurnal Umum',
            'akuntansi_laporan_audit_investasi' => 'Akuntansi - Lap. Audit Investasi',
            'akuntansi_laporan_audit_keuangan' => 'Akuntansi - Lap. Audit Keuangan',
            'akuntansi_laporan_bulanan' => 'Akuntansi - Laporan Bulanan',
            'anggaran_aturan_kebijakan' => 'Anggaran - Aturan Kebijakan',
            'anggaran_dokumen_rra' => 'Anggaran - Dokumen RRA',
            'anggaran_laporan_prbc' => 'Anggaran - Laporan PRBC',
            'investasi_transaksi' => 'Investasi - Transaksi',
            'investasi_surat' => 'Investasi - Surat',
            'keuangan_surat_bayar' => 'Keuangan - Surat Bayar',
            'sdm_pks' => 'SDM - PKS',
            'sdm_peraturan' => 'SDM - Peraturan',
            'sekretariat_risalah_rapat' => 'Sekretariat - Risalah Rapat',
            'logistik_procurement' => 'Logistik - Procurement',
        ];

        $stats = [];
        foreach ($tables as $table => $name) {
            try {
                $query = DB::table($table)->whereNull('deleted_at');

                if ($divisionId) {
                    $query->where('id_divisi', $divisionId);
                }

                $total = $query->count();
                $unassigned = DB::table($table)->whereNull('deleted_at')->whereNull('id_divisi')->count();

                $stats[] = [
                    'table' => $table,
                    'name' => $name,
                    'total' => $total,
                    'unassigned' => $unassigned,
                ];
            } catch (\Exception $e) {
                // Table might not exist
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
            });
        }

        return $query->orderByDesc("{$table}.created_at")->paginate(20);
    }

    protected function getTableName(string $module): ?string
    {
        $map = [
            'akuntansi-aturan-kebijakan' => 'akuntansi_aturan_kebijakan',
            'akuntansi-jurnal-umum' => 'akuntansi_jurnal_umum',
            'anggaran-aturan-kebijakan' => 'anggaran_aturan_kebijakan',
            'investasi-transaksi' => 'investasi_transaksi',
            'keuangan-surat-bayar' => 'keuangan_surat_bayar',
            'sdm-pks' => 'sdm_pks',
            'sekretariat-risalah-rapat' => 'sekretariat_risalah_rapat',
            'logistik-procurement' => 'logistik_procurement',
        ];

        return $map[$module] ?? null;
    }

    protected function getModuleName(string $module): string
    {
        return ucwords(str_replace('-', ' ', $module));
    }
}
