<?php

namespace App\Http\Controllers;

use App\Models\FileAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MyDocumentsController extends Controller
{
    protected $modelMap = [
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
        'investasi_transaksi' => \App\Models\Investasi\Transaksi::class,
        'investasi_surat' => \App\Models\Investasi\Surat::class,
        'investasi_perencanaan_surat' => \App\Models\Investasi\PerencanaanSurat::class,
        'investasi_perencanaan_transaksi' => \App\Models\Investasi\PerencanaanTransaksi::class,
        'investasi_propensa_surat' => \App\Models\Investasi\PropensaSurat::class,
        'investasi_propensa_transaksi' => \App\Models\Investasi\PropensaTransaksi::class,

        // Keuangan
        'keuangan_surat_bayar' => \App\Models\Keuangan\SuratBayar::class,
        'keuangan_spb' => \App\Models\Keuangan\Spb::class,
        'keuangan_sppb' => \App\Models\Keuangan\Sppb::class,
        'keuangan_cashflow' => \App\Models\Keuangan\Cashflow::class,
        'keuangan_penempatan' => \App\Models\Keuangan\Penempatan::class,
        'keuangan_pemindahbukuan' => \App\Models\Keuangan\Pemindahbukuan::class,
        'keuangan_pajak' => \App\Models\Keuangan\Pajak::class,

        // Logistik
        'logistiksarpen_procurement' => \App\Models\Logistik\Procurement::class,
        'logistiksarpen_cleaning_service' => \App\Models\Logistik\CleaningService::class,
        'logistiksarpen_keamanan' => \App\Models\Logistik\Keamanan::class,
        'logistiksarpen_kendaraan' => \App\Models\Logistik\Kendaraan::class,
        'logistiksarpen_sarana_penunjang' => \App\Models\Logistik\SaranaPenunjang::class,
        'logistiksarpen_smk3' => \App\Models\Logistik\Smk3::class,
        'logistiksarpen_polis_asuransi' => \App\Models\Logistik\PolisAsuransi::class,
        'logistiksarpen_jaminan' => \App\Models\Logistik\Jaminan::class,
        'logistiksarpen_pelaporan_prbc' => \App\Models\Logistik\PelaporanPrbc::class,
        'logistiksarpen_user_satisfaction' => \App\Models\Logistik\UserSatisfaction::class,
        'logistiksarpen_vendor_satisfaction' => \App\Models\Logistik\VendorSatisfaction::class,

        // SDM
        'sdm_pks' => \App\Models\SDM\PKS::class,
        'sdm_peraturan' => \App\Models\SDM\Peraturan::class,
        'sdm_aspurjab' => \App\Models\Sdm\Aspurjab::class,
        'sdm_capeg_pegrus' => \App\Models\Sdm\CapegPegrus::class,
        'sdm_ikut_organisasi' => \App\Models\Sdm\IkutOrganisasi::class,
        'sdm_naik_gaji' => \App\Models\Sdm\NaikGaji::class,
        'sdm_penghargaan' => \App\Models\Sdm\Penghargaan::class,
        'sdm_promosi_mutasi' => \App\Models\Sdm\PromosiMutasi::class,
        'sdm_rarus' => \App\Models\Sdm\Rarus::class,
        'sdm_rekon' => \App\Models\Sdm\Rekon::class,
        'sdm_rekrut_masuk' => \App\Models\Sdm\RekrutMasuk::class,
        'sdm_surat_keluar' => \App\Models\Sdm\SuratKeluar::class,
        'sdm_surat_masuk' => \App\Models\Sdm\SuratMasuk::class,

        // Sekretariat
        'sekretariat_risalah_rapat' => \App\Models\Sekretariat\RisalahRapat::class,
        'sekretariat_materi' => \App\Models\Sekretariat\Materi::class,
        'sekretariat_laporan' => \App\Models\Sekretariat\Laporan::class,
        'sekretariat_surat' => \App\Models\Sekretariat\Surat::class,
        'sekretariat_pengadaan' => \App\Models\Sekretariat\Pengadaan::class,
        'sekretariat_remunerasi_pedoman' => \App\Models\Sekretariat\RemunerasiPedoman::class,
        'sekretariat_remunerasi_dokumen' => \App\Models\Sekretariat\RemunerasiDokumen::class,
    ];

    protected $routeMap = [
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
        'investasi_transaksi' => 'investasi.transaksi.show',
        'investasi_surat' => 'investasi.surat.show',
        'investasi_perencanaan_surat' => 'investasi.perencanaan-surat.show',
        'investasi_perencanaan_transaksi' => 'investasi.perencanaan-transaksi.show',
        'investasi_propensa_surat' => 'investasi.propensa-surat.show',
        'investasi_propensa_transaksi' => 'investasi.propensa-transaksi.show',

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

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display list of documents that user has been granted access to
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get all approved access requests for this user
        $approvedRequests = FileAccessRequest::with(['divisi', 'responder'])
            ->where('id_user', $user->id)
            ->where('status', 'approved')
            ->orderBy('responded_at', 'desc')
            ->paginate(20);

        // Enrich with document details
        $documents = $approvedRequests->map(function ($request) {
            $modelClass = $this->modelMap[$request->document_type] ?? null;

            if (!$modelClass || !class_exists($modelClass)) {
                return null;
            }

            try {
                $document = $modelClass::find($request->document_id);

                if (!$document) {
                    return null;
                }

                // Check permissions
                $canDownload = $request->hasPermission('download');

                return [
                    'request_id' => $request->id,
                    'document_type' => $request->document_type,
                    'document_id' => $request->document_id,
                    'title' => $document->judul ?? $document->perihal ?? $document->nama ?? $document->file_name ?? 'Dokumen #' . $document->id,
                    'type_label' => $this->formatTableName($request->document_type),
                    'division' => $request->divisi->nama_divisi ?? '-',
                    'classification' => $document->sifat_dokumen ?? '-',
                    'approved_at' => $request->responded_at,
                    'approved_by' => $request->responder->name ?? '-',
                    'url' => $this->getDocumentUrl($request->document_type, $request->document_id),
                    'has_download_permission' => $canDownload,
                    'download_url' => $canDownload ? $this->getDocumentDownloadUrl($request->document_type, $request->document_id) : null,
                ];
            } catch (\Exception $e) {
                return null;
            }
        })->filter(); // Remove null entries

        return view('my-documents.index', [
            'documents' => $documents,
            'pagination' => $approvedRequests,
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
     * Get document Download URL
     */
    protected function getDocumentDownloadUrl($documentType, $documentId)
    {
        $showRoute = $this->routeMap[$documentType] ?? null;

        if ($showRoute) {
            // Assume download route is same as show but with .download instead of .show
            $downloadRoute = str_replace('.show', '.download', $showRoute);

            if (\Route::has($downloadRoute)) {
                return route($downloadRoute, $documentId);
            }
        }

        return null;
    }

    /**
     * Get document URL
     */
    protected function getDocumentUrl($documentType, $documentId)
    {
        $routeName = $this->routeMap[$documentType] ?? null;

        if ($routeName && \Route::has($routeName)) {
            // Pass parameters as a single array: [route_param, query_param => value]
            return route($routeName, [$documentId, 'source' => 'my-documents']);
        }

        return null;
    }
}
