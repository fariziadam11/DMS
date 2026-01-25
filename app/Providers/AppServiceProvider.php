<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
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
        ]);
    }
}
