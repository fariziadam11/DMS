<?php

namespace App\Http\Controllers;

use App\Models\MasterDivisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GlobalSearchController extends Controller
{
    protected $searchableModels = [
        // Akuntansi
        'akuntansi_aturan_kebijakan' => \App\Models\Akuntansi\AturanKebijakan::class,
        'akuntansi_jurnal_umum' => \App\Models\Akuntansi\JurnalUmum::class,
        'akuntansi_laporan_audit_investasi' => \App\Models\Akuntansi\LaporanAuditInvestasi::class,
        'akuntansi_laporan_audit_keuangan' => \App\Models\Akuntansi\LaporanAuditKeuangan::class,
        'akuntansi_laporan_bulanan' => \App\Models\Akuntansi\LaporanBulanan::class,

        // Anggaran
        'anggaran_aturan_kebijakan' => \App\Models\Anggaran\AturanKebijakan::class,
        'anggaran_dokumen_rra' => \App\Models\Anggaran\DokumenRra::class,
        'anggaran_laporan_prbc' => \App\Models\Anggaran\LaporanPrbc::class,
        'anggaran_rencana_kerja_direktorat' => \App\Models\Anggaran\RencanaKerjaDirektorat::class,
        'anggaran_rencana_kerja_tahunan' => \App\Models\Anggaran\RencanaKerjaTahunan::class,
        'anggaran_rencana_kerja_triwulan' => \App\Models\Anggaran\RencanaKerjaTriwulan::class,

        // Hukum & Kepatuhan
        'hukumkepatuhan_compliance_check' => \App\Models\HukumKepatuhan\ComplianceCheck::class,
        'hukumkepatuhan_executive_summary' => \App\Models\HukumKepatuhan\ExecutiveSummary::class,
        'hukumkepatuhan_kajian_hukum' => \App\Models\HukumKepatuhan\KajianHukum::class,
        'hukumkepatuhan_kontrak' => \App\Models\HukumKepatuhan\Kontrak::class,
        'hukumkepatuhan_legal_memo' => \App\Models\HukumKepatuhan\LegalMemo::class,
        'hukumkepatuhan_lembar_keputusan' => \App\Models\HukumKepatuhan\LembarKeputusan::class,
        'hukumkepatuhan_lembar_rekomendasi' => \App\Models\HukumKepatuhan\LembarRekomendasi::class,
        'hukumkepatuhan_penomoran' => \App\Models\HukumKepatuhan\Penomoran::class,
        'hukumkepatuhan_putusan' => \App\Models\HukumKepatuhan\Putusan::class,
        'hukumkepatuhan_regulasi_external' => \App\Models\HukumKepatuhan\RegulasiExternal::class,
        'hukumkepatuhan_regulasi_internal' => \App\Models\HukumKepatuhan\RegulasiInternal::class,

        // Investasi
        'investasi_perencanaan_surat' => \App\Models\Investasi\PerencanaanSurat::class,
        'investasi_perencanaan_transaksi' => \App\Models\Investasi\PerencanaanTransaksi::class,
        'investasi_propensa_surat' => \App\Models\Investasi\PropensaSurat::class,
        'investasi_propensa_transaksi' => \App\Models\Investasi\PropensaTransaksi::class,
        'investasi_surat' => \App\Models\Investasi\Surat::class,
        'investasi_transaksi' => \App\Models\Investasi\Transaksi::class,

        // Keuangan
        'keuangan_cashflow' => \App\Models\Keuangan\Cashflow::class,
        'keuangan_pajak' => \App\Models\Keuangan\Pajak::class,
        'keuangan_pemindahbukuan' => \App\Models\Keuangan\Pemindahbukuan::class,
        'keuangan_penempatan' => \App\Models\Keuangan\Penempatan::class,
        'keuangan_spb' => \App\Models\Keuangan\Spb::class,
        'keuangan_sppb' => \App\Models\Keuangan\Sppb::class,
        'keuangan_surat_bayar' => \App\Models\Keuangan\SuratBayar::class,

        // Logistik
        'logistiksarpen_cleaning_service' => \App\Models\Logistik\CleaningService::class,
        'logistiksarpen_jaminan' => \App\Models\Logistik\Jaminan::class,
        'logistiksarpen_keamanan' => \App\Models\Logistik\Keamanan::class,
        'logistiksarpen_kendaraan' => \App\Models\Logistik\Kendaraan::class,
        'logistiksarpen_pelaporan_prbc' => \App\Models\Logistik\PelaporanPrbc::class,
        'logistiksarpen_polis_asuransi' => \App\Models\Logistik\PolisAsuransi::class,
        'logistiksarpen_procurement' => \App\Models\Logistik\Procurement::class,
        'logistiksarpen_sarana_penunjang' => \App\Models\Logistik\SaranaPenunjang::class,
        'logistiksarpen_smk3' => \App\Models\Logistik\Smk3::class,
        'logistiksarpen_user_satisfaction' => \App\Models\Logistik\UserSatisfaction::class,
        'logistiksarpen_vendor_satisfaction' => \App\Models\Logistik\VendorSatisfaction::class,

        // SDM
        'sdm_aspurjab' => \App\Models\Sdm\Aspurjab::class,
        'sdm_capeg_pegrus' => \App\Models\Sdm\CapegPegrus::class,
        'sdm_ikut_organisasi' => \App\Models\Sdm\IkutOrganisasi::class,
        'sdm_naik_gaji' => \App\Models\Sdm\NaikGaji::class,
        'sdm_penghargaan' => \App\Models\Sdm\Penghargaan::class,
        'sdm_peraturan' => \App\Models\Sdm\Peraturan::class,
        'sdm_pks' => \App\Models\Sdm\Pks::class,
        'sdm_promosi_mutasi' => \App\Models\Sdm\PromosiMutasi::class,
        'sdm_rarus' => \App\Models\Sdm\Rarus::class,
        'sdm_rekon' => \App\Models\Sdm\Rekon::class,
        'sdm_rekrut_masuk' => \App\Models\Sdm\RekrutMasuk::class,
        'sdm_surat_keluar' => \App\Models\Sdm\SuratKeluar::class,
        'sdm_surat_masuk' => \App\Models\Sdm\SuratMasuk::class,

        // Sekretariat
        'sekretariat_laporan' => \App\Models\Sekretariat\Laporan::class,
        'sekretariat_materi' => \App\Models\Sekretariat\Materi::class,
        'sekretariat_pengadaan' => \App\Models\Sekretariat\Pengadaan::class,
        'sekretariat_remunerasi_dokumen' => \App\Models\Sekretariat\RemunerasiDokumen::class,
        'sekretariat_remunerasi_pedoman' => \App\Models\Sekretariat\RemunerasiPedoman::class,
        'sekretariat_risalah_rapat' => \App\Models\Sekretariat\RisalahRapat::class,
        'sekretariat_surat' => \App\Models\Sekretariat\Surat::class,
    ];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Global search across all documents
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $divisiFilter = $request->get('divisi');
        $classificationFilter = $request->get('classification');
        $moduleFilter = $request->get('module');

        $results = [];
        $user = auth()->user();

        // Prepare accessible division IDs for filtering confidential documents
        $accessibleDivisionIds = $user->isSuperAdmin()
            ? \App\Models\MasterDivisi::pluck('id')->toArray()
            : $user->getAccessibleDivisions()->pluck('id')->toArray();

        if (strlen($query) >= 2) {
            foreach ($this->searchableModels as $tableName => $modelClass) {
                if (!class_exists($modelClass)) continue;

                $modelQuery = $modelClass::query();

                // Check if the module/table name matches the query
                // e.g. "investasi" matches "investasi_surat"
                $isModuleMatch = stripos($tableName, str_replace(' ', '_', $query)) !== false;

                if ($isModuleMatch) {
                    // If the module matches, we want to show everything (subject to other filters)
                    // So we DON'T apply globalSearch($query) which filters by content
                } else {
                    // Apply global search content filter
                    $modelQuery->globalSearch($query);
                }

                // Apply classification filter
                if ($classificationFilter) {
                    $modelQuery->byClassification($classificationFilter);
                }

                // Apply division filter
                if ($divisiFilter) {
                    $modelQuery->byDivision($divisiFilter);
                }

                // Modified logic:
                // Include 'Rahasia' documents ONLY if the user belongs to that division
                // Other documents (Umum, Internal) remain searchable globally (metadata visible)
                $modelQuery->where(function ($q) use ($accessibleDivisionIds) {
                    $q->where('sifat_dokumen', '!=', 'Rahasia')
                      ->orWhere(function($subQ) use ($accessibleDivisionIds) {
                          $subQ->where('sifat_dokumen', 'Rahasia')
                               ->whereIn('id_divisi', $accessibleDivisionIds);
                      });
                });

                // Get results
                $items = $modelQuery->with('divisi')
                    ->orderBy('created_at', 'desc')
                    ->limit(10)
                    ->get();

                foreach ($items as $item) {
                    // Use model's consolidated logic for access check
                    // 'read' action implies being able to view the detail page
                    // But for search results, we want to know if they can CLICK "View".
                    // If it is Rahasia, they can 'read' (metadata) but NOT 'view' content or show page?
                    // Wait, existing logic in BaseDocumentController:
                    // show() -> authorizeAccess() -> 'read'.
                    // authorizeAccess('read') for Rahasia returns TRUE (to see metadata).
                    // BUT UI buttons 'Show', 'Edit', 'Delete' are hidden via canPerformAction.

                    // So, if we want "Search Result" -> "Click View" to behave like "List Index" -> "Click Show",
                    // We should check canPerformAction('read').
                    // If true -> Show "View" button.
                    // If false -> Show "Request Access" button.

                    $hasAccess = $item->canPerformAction('read', $user->id);

                    // Special case: if canPerformAction('read') is false (e.g. Rahasia sans permission),
                    // we show "Request Access".
                    // If true, we show "View".

                    $results[] = [
                        'id' => $item->id,
                        'table' => $tableName,
                        'type' => $this->formatTableName($tableName),
                        'title' => $item->judul ?? $item->perihal ?? $item->nama ?? $item->file_name ?? 'Dokumen #' . $item->id,
                        'division' => $item->divisi ? $item->divisi->nama_divisi : null,
                        'classification' => $item->sifat_dokumen,
                        'version' => $item->version,
                        'created_at' => $item->created_at->format('d M Y'),
                        'has_access' => $hasAccess,
                        'is_secret' => $item->isSecret(),
                        'url' => $hasAccess ? $this->getViewUrl($tableName, $item->id) : null,
                    ];
                }
            }

            // Sort by relevance and date
            usort($results, function ($a, $b) {
                return strtotime($b['created_at']) - strtotime($a['created_at']);
            });
        }

        $divisions = $user->isSuperAdmin()
            ? MasterDivisi::all()
            : $user->getAccessibleDivisions();

        if ($request->ajax()) {
            return response()->json([
                'results' => $results,
                'total' => count($results),
            ]);
        }

        return view('search.index', [
            'query' => $query,
            'results' => $results,
            'divisions' => $divisions,
            'selectedDivisi' => $divisiFilter,
            'selectedClassification' => $classificationFilter,
        ]);
    }

    /**
     * Format table name for display
     */
    protected function formatTableName($tableName)
    {
        $parts = explode('_', $tableName);
        $parts = array_map('ucfirst', $parts);
        return implode(' ', $parts);
    }

    /**
     * Get view URL for document
     */
    protected function getViewUrl($tableName, $id)
    {
        $routeMap = [
            // Akuntansi
            'akuntansi_aturan_kebijakan' => 'akuntansi.aturan-kebijakan.show',
            'akuntansi_jurnal_umum' => 'akuntansi.jurnal-umum.show',
            'akuntansi_laporan_audit_investasi' => 'akuntansi.laporan-audit-investasi.show',
            'akuntansi_laporan_audit_keuangan' => 'akuntansi.laporan-audit-keuangan.show',
            'akuntansi_laporan_bulanan' => 'akuntansi.laporan-bulanan.show',

            // Anggaran
            'anggaran_aturan_kebijakan' => 'anggaran.aturan-kebijakan.show',
            'anggaran_dokumen_rra' => 'anggaran.dokumen-rra.show',
            'anggaran_laporan_prbc' => 'anggaran.laporan-prbc.show',
            'anggaran_rencana_kerja_direktorat' => 'anggaran.rencana-kerja-direktorat.show',
            'anggaran_rencana_kerja_tahunan' => 'anggaran.rencana-kerja-tahunan.show',
            'anggaran_rencana_kerja_triwulan' => 'anggaran.rencana-kerja-triwulan.show',

            // Hukum & Kepatuhan
            'hukumkepatuhan_compliance_check' => 'hukum-kepatuhan.compliance-check.show',
            'hukumkepatuhan_executive_summary' => 'hukum-kepatuhan.executive-summary.show',
            'hukumkepatuhan_kajian_hukum' => 'hukum-kepatuhan.kajian-hukum.show',
            'hukumkepatuhan_kontrak' => 'hukum-kepatuhan.kontrak.show',
            'hukumkepatuhan_legal_memo' => 'hukum-kepatuhan.legal-memo.show',
            'hukumkepatuhan_lembar_keputusan' => 'hukum-kepatuhan.lembar-keputusan.show',
            'hukumkepatuhan_lembar_rekomendasi' => 'hukum-kepatuhan.lembar-rekomendasi.show',
            'hukumkepatuhan_penomoran' => 'hukum-kepatuhan.penomoran.show',
            'hukumkepatuhan_putusan' => 'hukum-kepatuhan.putusan.show',
            'hukumkepatuhan_regulasi_external' => 'hukum-kepatuhan.regulasi-external.show',
            'hukumkepatuhan_regulasi_internal' => 'hukum-kepatuhan.regulasi-internal.show',

            // Investasi
            'investasi_perencanaan_surat' => 'investasi.perencanaan-surat.show',
            'investasi_perencanaan_transaksi' => 'investasi.perencanaan-transaksi.show',
            'investasi_propensa_surat' => 'investasi.propensa-surat.show',
            'investasi_propensa_transaksi' => 'investasi.propensa-transaksi.show',
            'investasi_surat' => 'investasi.surat.show',
            'investasi_transaksi' => 'investasi.transaksi.show',

            // Keuangan
            'keuangan_cashflow' => 'keuangan.cashflow.show',
            'keuangan_pajak' => 'keuangan.pajak.show',
            'keuangan_pemindahbukuan' => 'keuangan.pemindahbukuan.show',
            'keuangan_penempatan' => 'keuangan.penempatan.show',
            'keuangan_spb' => 'keuangan.spb.show',
            'keuangan_sppb' => 'keuangan.sppb.show',
            'keuangan_surat_bayar' => 'keuangan.surat-bayar.show',

            // Logistik
            'logistiksarpen_cleaning_service' => 'logistik.cleaning-service.show',
            'logistiksarpen_jaminan' => 'logistik.jaminan.show',
            'logistiksarpen_keamanan' => 'logistik.keamanan.show',
            'logistiksarpen_kendaraan' => 'logistik.kendaraan.show',
            'logistiksarpen_pelaporan_prbc' => 'logistik.pelaporan-prbc.show',
            'logistiksarpen_polis_asuransi' => 'logistik.polis-asuransi.show',
            'logistiksarpen_procurement' => 'logistik.procurement.show',
            'logistiksarpen_sarana_penunjang' => 'logistik.sarana-penunjang.show',
            'logistiksarpen_smk3' => 'logistik.smk3.show',
            'logistiksarpen_user_satisfaction' => 'logistik.user-satisfaction.show',
            'logistiksarpen_vendor_satisfaction' => 'logistik.vendor-satisfaction.show',

            // SDM
            'sdm_aspurjab' => 'sdm.aspurjab.show',
            'sdm_capeg_pegrus' => 'sdm.capeg-pegrus.show',
            'sdm_ikut_organisasi' => 'sdm.ikut-organisasi.show',
            'sdm_naik_gaji' => 'sdm.naik-gaji.show',
            'sdm_penghargaan' => 'sdm.penghargaan.show',
            'sdm_peraturan' => 'sdm.peraturan.show',
            'sdm_pks' => 'sdm.pks.show',
            'sdm_promosi_mutasi' => 'sdm.promosi-mutasi.show',
            'sdm_rarus' => 'sdm.rarus.show',
            'sdm_rekon' => 'sdm.rekon.show',
            'sdm_rekrut_masuk' => 'sdm.rekrut-masuk.show',
            'sdm_surat_keluar' => 'sdm.surat-keluar.show',
            'sdm_surat_masuk' => 'sdm.surat-masuk.show',

            // Sekretariat
            'sekretariat_laporan' => 'sekretariat.laporan.show',
            'sekretariat_materi' => 'sekretariat.materi.show',
            'sekretariat_pengadaan' => 'sekretariat.pengadaan.show',
            'sekretariat_remunerasi_dokumen' => 'sekretariat.remunerasi-dokumen.show',
            'sekretariat_remunerasi_pedoman' => 'sekretariat.remunerasi-pedoman.show',
            'sekretariat_risalah_rapat' => 'sekretariat.risalah-rapat.show',
            'sekretariat_surat' => 'sekretariat.surat.show',
        ];

        $routeName = $routeMap[$tableName] ?? null;

        if ($routeName && \Route::has($routeName)) {
            return route($routeName, $id);
        }

        return null;
    }

    /**
     * Check if user has approved access request for document
     */
    protected function hasApprovedAccessRequest($userId, $documentType, $documentId): bool
    {
        return \App\Models\FileAccessRequest::where('id_user', $userId)
            ->where('document_type', $documentType)
            ->where('document_id', $documentId)
            ->where('status', 'approved')
            ->exists();
    }

    /**
     * Request access to document
     */
    public function requestAccess(Request $request)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'document_id' => 'required|integer',
            'reason' => 'required|string|max:500',
        ]);

        // Get document to find division
        $modelClass = $this->searchableModels[$validated['document_type']] ?? null;
        if (!$modelClass) {
            return back()->with('error', 'Tipe dokumen tidak valid.');
        }

        $document = $modelClass::findOrFail($validated['document_id']);

        \App\Models\FileAccessRequest::create([
            'id_user' => auth()->id(),
            'document_type' => $validated['document_type'],
            'document_id' => $validated['document_id'],
            'id_divisi' => $document->id_divisi,
            'request_reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Permintaan akses telah dikirim ke admin divisi.');
    }
}
