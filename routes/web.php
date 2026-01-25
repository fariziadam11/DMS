<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\AccessController;
use App\Http\Controllers\MyRequestController;
use App\Http\Controllers\Master\DivisiController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\JabatanController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => redirect()->route('login'));

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // User Profile
    Route::get('profile', [App\Http\Controllers\UserProfileController::class, 'index'])->name('profile.index');
    Route::get('profile/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');

    // Global Search
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('search');
    Route::post('/search/request-access', [GlobalSearchController::class, 'requestAccess'])->name('search.request-access');

    // My Documents (Approved Access)
    Route::get('/my-documents', [\App\Http\Controllers\MyDocumentsController::class, 'index'])->name('my-documents.index');

    // Access Documents (Approval and Mgt)
    Route::prefix('access')->name('access.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AccessController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [\App\Http\Controllers\AccessController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\AccessController::class, 'reject'])->name('reject');
        Route::post('/assign', [\App\Http\Controllers\AccessController::class, 'assignAccess'])->name('assign');
        Route::delete('/{id}', [\App\Http\Controllers\AccessController::class, 'removeAccess'])->name('remove');
    });

    // My Requests
    Route::prefix('my-request')->name('my-request.')->group(function () {
        Route::get('/', [\App\Http\Controllers\MyRequestController::class, 'index'])->name('index');
    });

    // Master Data
    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('divisi', DivisiController::class);
        Route::resource('department', DepartmentController::class);
        Route::resource('jabatan', JabatanController::class);
        Route::get('jabatan/departments/{divisi}', [JabatanController::class, 'getDepartmentsByDivision'])->name('jabatan.departments');
    });

    // ======================
    // MODULE: AKUNTANSI
    // ======================
    Route::prefix('akuntansi')->name('akuntansi.')->group(function () {
        Route::resource('aturan-kebijakan', \App\Http\Controllers\Akuntansi\AturanKebijakanController::class);
        Route::get('aturan-kebijakan/{id}/download', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'download'])->name('aturan-kebijakan.download');
        Route::get('aturan-kebijakan/{id}/preview', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'preview'])->name('aturan-kebijakan.preview');
        Route::resource('jurnal-umum', \App\Http\Controllers\Akuntansi\JurnalUmumController::class);
        Route::get('jurnal-umum/{id}/download', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'download'])->name('jurnal-umum.download');
        Route::get('jurnal-umum/{id}/preview', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'preview'])->name('jurnal-umum.preview');
        Route::resource('laporan-audit-investasi', \App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class);
        Route::get('laporan-audit-investasi/{id}/download', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'download'])->name('laporan-audit-investasi.download');
        Route::get('laporan-audit-investasi/{id}/preview', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'preview'])->name('laporan-audit-investasi.preview');
        Route::resource('laporan-audit-keuangan', \App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class);
        Route::get('laporan-audit-keuangan/{id}/download', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'download'])->name('laporan-audit-keuangan.download');
        Route::get('laporan-audit-keuangan/{id}/preview', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'preview'])->name('laporan-audit-keuangan.preview');
        Route::resource('laporan-bulanan', \App\Http\Controllers\Akuntansi\LaporanBulananController::class);
        Route::get('laporan-bulanan/{id}/download', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'download'])->name('laporan-bulanan.download');
        Route::get('laporan-bulanan/{id}/preview', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'preview'])->name('laporan-bulanan.preview');
    });

    // ======================
    // MODULE: ANGGARAN
    // ======================
    Route::prefix('anggaran')->name('anggaran.')->group(function () {
        Route::resource('aturan-kebijakan', \App\Http\Controllers\Anggaran\AturanKebijakanController::class);
        Route::get('aturan-kebijakan/{id}/download', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'download'])->name('aturan-kebijakan.download');
        Route::get('aturan-kebijakan/{id}/preview', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'preview'])->name('aturan-kebijakan.preview');
        Route::resource('dokumen-rra', \App\Http\Controllers\Anggaran\DokumenRraController::class);
        Route::get('dokumen-rra/{id}/download', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'download'])->name('dokumen-rra.download');
        Route::get('dokumen-rra/{id}/preview', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'preview'])->name('dokumen-rra.preview');
        Route::resource('laporan-prbc', \App\Http\Controllers\Anggaran\LaporanPrbcController::class);
        Route::get('laporan-prbc/{id}/download', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'download'])->name('laporan-prbc.download');
        Route::get('laporan-prbc/{id}/preview', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'preview'])->name('laporan-prbc.preview');
        Route::resource('rencana-kerja-direktorat', \App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class);
        Route::get('rencana-kerja-direktorat/{id}/download', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'download'])->name('rencana-kerja-direktorat.download');
        Route::get('rencana-kerja-direktorat/{id}/preview', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'preview'])->name('rencana-kerja-direktorat.preview');
        Route::resource('rencana-kerja-tahunan', \App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class);
        Route::get('rencana-kerja-tahunan/{id}/download', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'download'])->name('rencana-kerja-tahunan.download');
        Route::get('rencana-kerja-tahunan/{id}/preview', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'preview'])->name('rencana-kerja-tahunan.preview');
        Route::resource('rencana-kerja-triwulan', \App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class);
        Route::get('rencana-kerja-triwulan/{id}/download', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'download'])->name('rencana-kerja-triwulan.download');
        Route::get('rencana-kerja-triwulan/{id}/preview', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'preview'])->name('rencana-kerja-triwulan.preview');
    });

    // ======================
    // MODULE: HUKUM & KEPATUHAN
    // ======================
    Route::prefix('hukum-kepatuhan')->name('hukum-kepatuhan.')->group(function () {
        Route::resource('kajian-hukum', \App\Http\Controllers\HukumKepatuhan\KajianHukumController::class);
        Route::get('kajian-hukum/{id}/download', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'download'])->name('kajian-hukum.download');
        Route::get('kajian-hukum/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'preview'])->name('kajian-hukum.preview');
        Route::resource('legal-memo', \App\Http\Controllers\HukumKepatuhan\LegalMemoController::class);
        Route::get('legal-memo/{id}/download', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'download'])->name('legal-memo.download');
        Route::get('legal-memo/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'preview'])->name('legal-memo.preview');
        Route::resource('regulasi-internal', \App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class);
        Route::get('regulasi-internal/{id}/download', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'download'])->name('regulasi-internal.download');
        Route::get('regulasi-internal/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'preview'])->name('regulasi-internal.preview');
        Route::resource('regulasi-external', \App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class);
        Route::get('regulasi-external/{id}/download', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'download'])->name('regulasi-external.download');
        Route::get('regulasi-external/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'preview'])->name('regulasi-external.preview');
        Route::resource('kontrak', \App\Http\Controllers\HukumKepatuhan\KontrakController::class);
        Route::get('kontrak/{id}/download', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'download'])->name('kontrak.download');
        Route::get('kontrak/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'preview'])->name('kontrak.preview');
        Route::resource('putusan', \App\Http\Controllers\HukumKepatuhan\PutusanController::class);
        Route::get('putusan/{id}/download', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'download'])->name('putusan.download');
        Route::get('putusan/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'preview'])->name('putusan.preview');
        Route::resource('compliance-check', \App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class);
        Route::get('compliance-check/{id}/download', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'download'])->name('compliance-check.download');
        Route::get('compliance-check/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'preview'])->name('compliance-check.preview');
        Route::resource('executive-summary', \App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class);
        Route::get('executive-summary/{id}/download', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'download'])->name('executive-summary.download');
        Route::get('executive-summary/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'preview'])->name('executive-summary.preview');
        Route::resource('lembar-keputusan', \App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class);
        Route::get('lembar-keputusan/{id}/download', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'download'])->name('lembar-keputusan.download');
        Route::get('lembar-keputusan/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'preview'])->name('lembar-keputusan.preview');
        Route::resource('lembar-rekomendasi', \App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class);
        Route::get('lembar-rekomendasi/{id}/download', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'download'])->name('lembar-rekomendasi.download');
        Route::get('lembar-rekomendasi/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'preview'])->name('lembar-rekomendasi.preview');
        Route::resource('penomoran', \App\Http\Controllers\HukumKepatuhan\PenomoranController::class);
        Route::get('penomoran/{id}/download', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'download'])->name('penomoran.download');
        Route::get('penomoran/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'preview'])->name('penomoran.preview');
    });

    // ======================
    // MODULE: INVESTASI
    // ======================
    Route::prefix('investasi')->name('investasi.')->group(function () {
        Route::resource('transaksi', \App\Http\Controllers\Investasi\TransaksiController::class);
        Route::get('transaksi/{id}/download', [\App\Http\Controllers\Investasi\TransaksiController::class, 'download'])->name('transaksi.download');
        Route::get('transaksi/{id}/preview', [\App\Http\Controllers\Investasi\TransaksiController::class, 'preview'])->name('transaksi.preview');
        Route::resource('surat', \App\Http\Controllers\Investasi\SuratController::class);
        Route::get('surat/{id}/download', [\App\Http\Controllers\Investasi\SuratController::class, 'download'])->name('surat.download');
        Route::get('surat/{id}/preview', [\App\Http\Controllers\Investasi\SuratController::class, 'preview'])->name('surat.preview');
        Route::resource('perencanaan-transaksi', \App\Http\Controllers\Investasi\PerencanaanTransaksiController::class);
        Route::get('perencanaan-transaksi/{id}/download', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'download'])->name('perencanaan-transaksi.download');
        Route::get('perencanaan-transaksi/{id}/preview', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'preview'])->name('perencanaan-transaksi.preview');
        Route::resource('perencanaan-surat', \App\Http\Controllers\Investasi\PerencanaanSuratController::class);
        Route::get('perencanaan-surat/{id}/download', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'download'])->name('perencanaan-surat.download');
        Route::get('perencanaan-surat/{id}/preview', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'preview'])->name('perencanaan-surat.preview');
        Route::resource('propensa-transaksi', \App\Http\Controllers\Investasi\PropensaTransaksiController::class);
        Route::get('propensa-transaksi/{id}/download', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'download'])->name('propensa-transaksi.download');
        Route::get('propensa-transaksi/{id}/preview', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'preview'])->name('propensa-transaksi.preview');
        Route::resource('propensa-surat', \App\Http\Controllers\Investasi\PropensaSuratController::class);
        Route::get('propensa-surat/{id}/download', [\App\Http\Controllers\Investasi\PropensaSuratController::class, 'download'])->name('propensa-surat.download');
        Route::get('propensa-surat/{id}/preview', [\App\Http\Controllers\Investasi\PropensaSuratController::class, 'preview'])->name('propensa-surat.preview');
    });

    // ======================
    // MODULE: KEUANGAN
    // ======================
    Route::prefix('keuangan')->name('keuangan.')->group(function () {
        Route::resource('surat-bayar', \App\Http\Controllers\Keuangan\SuratBayarController::class);
        Route::get('surat-bayar/{id}/download', [\App\Http\Controllers\Keuangan\SuratBayarController::class, 'download'])->name('surat-bayar.download');
        Route::get('surat-bayar/{id}/preview', [\App\Http\Controllers\Keuangan\SuratBayarController::class, 'preview'])->name('surat-bayar.preview');
        Route::resource('spb', \App\Http\Controllers\Keuangan\SpbController::class);
        Route::get('spb/{id}/download', [\App\Http\Controllers\Keuangan\SpbController::class, 'download'])->name('spb.download');
        Route::get('spb/{id}/preview', [\App\Http\Controllers\Keuangan\SpbController::class, 'preview'])->name('spb.preview');
        Route::resource('sppb', \App\Http\Controllers\Keuangan\SppbController::class);
        Route::get('sppb/{id}/download', [\App\Http\Controllers\Keuangan\SppbController::class, 'download'])->name('sppb.download');
        Route::get('sppb/{id}/preview', [\App\Http\Controllers\Keuangan\SppbController::class, 'preview'])->name('sppb.preview');
        Route::resource('cashflow', \App\Http\Controllers\Keuangan\CashflowController::class);
        Route::get('cashflow/{id}/download', [\App\Http\Controllers\Keuangan\CashflowController::class, 'download'])->name('cashflow.download');
        Route::get('cashflow/{id}/preview', [\App\Http\Controllers\Keuangan\CashflowController::class, 'preview'])->name('cashflow.preview');
        Route::resource('penempatan', \App\Http\Controllers\Keuangan\PenempatanController::class);
        Route::get('penempatan/{id}/download', [\App\Http\Controllers\Keuangan\PenempatanController::class, 'download'])->name('penempatan.download');
        Route::get('penempatan/{id}/preview', [\App\Http\Controllers\Keuangan\PenempatanController::class, 'preview'])->name('penempatan.preview');
        Route::resource('pemindahbukuan', \App\Http\Controllers\Keuangan\PemindahbukuanController::class);
        Route::get('pemindahbukuan/{id}/download', [\App\Http\Controllers\Keuangan\PemindahbukuanController::class, 'download'])->name('pemindahbukuan.download');
        Route::get('pemindahbukuan/{id}/preview', [\App\Http\Controllers\Keuangan\PemindahbukuanController::class, 'preview'])->name('pemindahbukuan.preview');
        Route::resource('pajak', \App\Http\Controllers\Keuangan\PajakController::class);
        Route::get('pajak/{id}/download', [\App\Http\Controllers\Keuangan\PajakController::class, 'download'])->name('pajak.download');
        Route::get('pajak/{id}/preview', [\App\Http\Controllers\Keuangan\PajakController::class, 'preview'])->name('pajak.preview');
    });

    // ======================
    // MODULE: SDM
    // ======================
    Route::prefix('sdm')->name('sdm.')->group(function () {
        Route::resource('pks', \App\Http\Controllers\Sdm\PksController::class);
        Route::get('pks/{id}/download', [\App\Http\Controllers\Sdm\PksController::class, 'download'])->name('pks.download');
        Route::get('pks/{id}/preview', [\App\Http\Controllers\Sdm\PksController::class, 'preview'])->name('pks.preview');
        Route::resource('rarus', \App\Http\Controllers\Sdm\RarusController::class);
        Route::get('rarus/{id}/download', [\App\Http\Controllers\Sdm\RarusController::class, 'download'])->name('rarus.download');
        Route::get('rarus/{id}/preview', [\App\Http\Controllers\Sdm\RarusController::class, 'preview'])->name('rarus.preview');
        Route::resource('peraturan', \App\Http\Controllers\Sdm\PeraturanController::class);
        Route::get('peraturan/{id}/download', [\App\Http\Controllers\Sdm\PeraturanController::class, 'download'])->name('peraturan.download');
        Route::get('peraturan/{id}/preview', [\App\Http\Controllers\Sdm\PeraturanController::class, 'preview'])->name('peraturan.preview');
        Route::resource('rekrut-masuk', \App\Http\Controllers\Sdm\RekrutMasukController::class);
        Route::get('rekrut-masuk/{id}/download', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'download'])->name('rekrut-masuk.download');
        Route::get('rekrut-masuk/{id}/preview', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'preview'])->name('rekrut-masuk.preview');
        Route::resource('promosi-mutasi', \App\Http\Controllers\Sdm\PromosiMutasiController::class);
        Route::get('promosi-mutasi/{id}/download', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'download'])->name('promosi-mutasi.download');
        Route::get('promosi-mutasi/{id}/preview', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'preview'])->name('promosi-mutasi.preview');
        Route::resource('naik-gaji', \App\Http\Controllers\Sdm\NaikGajiController::class);
        Route::get('naik-gaji/{id}/download', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'download'])->name('naik-gaji.download');
        Route::get('naik-gaji/{id}/preview', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'preview'])->name('naik-gaji.preview');
        Route::resource('surat-masuk', \App\Http\Controllers\Sdm\SuratMasukController::class);
        Route::get('surat-masuk/{id}/download', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'download'])->name('surat-masuk.download');
        Route::get('surat-masuk/{id}/preview', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'preview'])->name('surat-masuk.preview');
        Route::resource('surat-keluar', \App\Http\Controllers\Sdm\SuratKeluarController::class);
        Route::get('surat-keluar/{id}/download', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'download'])->name('surat-keluar.download');
        Route::get('surat-keluar/{id}/preview', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'preview'])->name('surat-keluar.preview');
        Route::resource('capeg-pegrus', \App\Http\Controllers\Sdm\CapegPegrusController::class);
        Route::get('capeg-pegrus/{id}/download', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'download'])->name('capeg-pegrus.download');
        Route::get('capeg-pegrus/{id}/preview', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'preview'])->name('capeg-pegrus.preview');
        Route::resource('penghargaan', \App\Http\Controllers\Sdm\PenghargaanController::class);
        Route::get('penghargaan/{id}/download', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'download'])->name('penghargaan.download');
        Route::get('penghargaan/{id}/preview', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'preview'])->name('penghargaan.preview');
        Route::resource('ikut-organisasi', \App\Http\Controllers\Sdm\IkutOrganisasiController::class);
        Route::get('ikut-organisasi/{id}/download', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'download'])->name('ikut-organisasi.download');
        Route::get('ikut-organisasi/{id}/preview', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'preview'])->name('ikut-organisasi.preview');
        Route::resource('aspurjab', \App\Http\Controllers\Sdm\AspurjabController::class);
        Route::get('aspurjab/{id}/download', [\App\Http\Controllers\Sdm\AspurjabController::class, 'download'])->name('aspurjab.download');
        Route::get('aspurjab/{id}/preview', [\App\Http\Controllers\Sdm\AspurjabController::class, 'preview'])->name('aspurjab.preview');
        Route::resource('rekon', \App\Http\Controllers\Sdm\RekonController::class);
        Route::get('rekon/{id}/download', [\App\Http\Controllers\Sdm\RekonController::class, 'download'])->name('rekon.download');
        Route::get('rekon/{id}/preview', [\App\Http\Controllers\Sdm\RekonController::class, 'preview'])->name('rekon.preview');
    });

    // ======================
    // MODULE: SEKRETARIAT
    // ======================
    Route::prefix('sekretariat')->name('sekretariat.')->group(function () {
        Route::resource('risalah-rapat', \App\Http\Controllers\Sekretariat\RisalahRapatController::class);
        Route::get('risalah-rapat/{id}/download', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'download'])->name('risalah-rapat.download');
        Route::get('risalah-rapat/{id}/preview', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'preview'])->name('risalah-rapat.preview');
        Route::resource('materi', \App\Http\Controllers\Sekretariat\MateriController::class);
        Route::get('materi/{id}/download', [\App\Http\Controllers\Sekretariat\MateriController::class, 'download'])->name('materi.download');
        Route::get('materi/{id}/preview', [\App\Http\Controllers\Sekretariat\MateriController::class, 'preview'])->name('materi.preview');
        Route::resource('laporan', \App\Http\Controllers\Sekretariat\LaporanController::class);
        Route::get('laporan/{id}/download', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'download'])->name('laporan.download');
        Route::get('laporan/{id}/preview', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'preview'])->name('laporan.preview');
        Route::resource('surat', \App\Http\Controllers\Sekretariat\SuratController::class);
        Route::get('surat/{id}/download', [\App\Http\Controllers\Sekretariat\SuratController::class, 'download'])->name('surat.download');
        Route::get('surat/{id}/preview', [\App\Http\Controllers\Sekretariat\SuratController::class, 'preview'])->name('surat.preview');
        Route::resource('pengadaan', \App\Http\Controllers\Sekretariat\PengadaanController::class);
        Route::get('pengadaan/{id}/download', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'download'])->name('pengadaan.download');
        Route::get('pengadaan/{id}/preview', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'preview'])->name('pengadaan.preview');
        Route::resource('remunerasi-pedoman', \App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class);
        Route::get('remunerasi-pedoman/{id}/download', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'download'])->name('remunerasi-pedoman.download');
        Route::get('remunerasi-pedoman/{id}/preview', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'preview'])->name('remunerasi-pedoman.preview');
        Route::resource('remunerasi-dokumen', \App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class);
        Route::get('remunerasi-dokumen/{id}/download', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'download'])->name('remunerasi-dokumen.download');
        Route::get('remunerasi-dokumen/{id}/preview', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'preview'])->name('remunerasi-dokumen.preview');
    });

    // ======================
    // MODULE: LOGISTIK & SARPEN
    // ======================
    Route::prefix('logistik')->name('logistik.')->group(function () {
        Route::resource('procurement', \App\Http\Controllers\Logistik\ProcurementController::class);
        Route::get('procurement/{id}/download', [\App\Http\Controllers\Logistik\ProcurementController::class, 'download'])->name('procurement.download');
        Route::get('procurement/{id}/preview', [\App\Http\Controllers\Logistik\ProcurementController::class, 'preview'])->name('procurement.preview');
        Route::resource('cleaning-service', \App\Http\Controllers\Logistik\CleaningServiceController::class);
        Route::get('cleaning-service/{id}/download', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'download'])->name('cleaning-service.download');
        Route::get('cleaning-service/{id}/preview', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'preview'])->name('cleaning-service.preview');
        Route::resource('keamanan', \App\Http\Controllers\Logistik\KeamananController::class);
        Route::get('keamanan/{id}/download', [\App\Http\Controllers\Logistik\KeamananController::class, 'download'])->name('keamanan.download');
        Route::get('keamanan/{id}/preview', [\App\Http\Controllers\Logistik\KeamananController::class, 'preview'])->name('keamanan.preview');
        Route::resource('kendaraan', \App\Http\Controllers\Logistik\KendaraanController::class);
        Route::get('kendaraan/{id}/download', [\App\Http\Controllers\Logistik\KendaraanController::class, 'download'])->name('kendaraan.download');
        Route::get('kendaraan/{id}/preview', [\App\Http\Controllers\Logistik\KendaraanController::class, 'preview'])->name('kendaraan.preview');
        Route::resource('sarana-penunjang', \App\Http\Controllers\Logistik\SaranaPenunjangController::class);
        Route::get('sarana-penunjang/{id}/download', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'download'])->name('sarana-penunjang.download');
        Route::get('sarana-penunjang/{id}/preview', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'preview'])->name('sarana-penunjang.preview');
        Route::resource('smk3', \App\Http\Controllers\Logistik\Smk3Controller::class);
        Route::get('smk3/{id}/download', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'download'])->name('smk3.download');
        Route::get('smk3/{id}/preview', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'preview'])->name('smk3.preview');
        Route::resource('polis-asuransi', \App\Http\Controllers\Logistik\PolisAsuransiController::class);
        Route::get('polis-asuransi/{id}/download', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'download'])->name('polis-asuransi.download');
        Route::get('polis-asuransi/{id}/preview', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'preview'])->name('polis-asuransi.preview');
        Route::resource('jaminan', \App\Http\Controllers\Logistik\JaminanController::class);
        Route::get('jaminan/{id}/download', [\App\Http\Controllers\Logistik\JaminanController::class, 'download'])->name('jaminan.download');
        Route::get('jaminan/{id}/preview', [\App\Http\Controllers\Logistik\JaminanController::class, 'preview'])->name('jaminan.preview');
        Route::resource('pelaporan-prbc', \App\Http\Controllers\Logistik\PelaporanPrbcController::class);
        Route::get('pelaporan-prbc/{id}/download', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'download'])->name('pelaporan-prbc.download');
        Route::get('pelaporan-prbc/{id}/preview', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'preview'])->name('pelaporan-prbc.preview');
        Route::resource('user-satisfaction', \App\Http\Controllers\Logistik\UserSatisfactionController::class);
        Route::get('user-satisfaction/{id}/download', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'download'])->name('user-satisfaction.download');
        Route::get('user-satisfaction/{id}/preview', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'preview'])->name('user-satisfaction.preview');
        Route::resource('vendor-satisfaction', \App\Http\Controllers\Logistik\VendorSatisfactionController::class);
        Route::get('vendor-satisfaction/{id}/download', [\App\Http\Controllers\Logistik\VendorSatisfactionController::class, 'download'])->name('vendor-satisfaction.download');
        Route::get('vendor-satisfaction/{id}/preview', [\App\Http\Controllers\Logistik\VendorSatisfactionController::class, 'preview'])->name('vendor-satisfaction.preview');
    });

    // ======================
    // ADMIN ROUTES
    // ======================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::get('users/ajax/departments/{divisiId}', [\App\Http\Controllers\Admin\UserController::class, 'getDepartments'])->name('users.ajax.departments');
        Route::get('users/ajax/jabatans/{divisiId}', [\App\Http\Controllers\Admin\UserController::class, 'getJabatans'])->name('users.ajax.jabatans');
        Route::get('api/jabatan/{jabatanId}/default-role', [\App\Http\Controllers\Admin\UserController::class, 'getDefaultRole'])->name('users.api.default-role');
        Route::resource('menus', \App\Http\Controllers\Admin\MenuController::class);
        Route::post('menus/reorder', [\App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('menus.reorder');
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::get('roles/{role}/privileges', [\App\Http\Controllers\Admin\RoleController::class, 'privileges'])->name('roles.privileges');
        Route::put('roles/{role}/privileges', [\App\Http\Controllers\Admin\RoleController::class, 'updatePrivileges'])->name('roles.privileges.update');
        Route::get('document-assignment', [\App\Http\Controllers\Admin\DocumentAssignmentController::class, 'index'])->name('document-assignment.index');
        Route::get('document-assignment/{module}', [\App\Http\Controllers\Admin\DocumentAssignmentController::class, 'showModule'])->name('document-assignment.module');
        Route::post('document-assignment/reassign', [\App\Http\Controllers\Admin\DocumentAssignmentController::class, 'reassign'])->name('document-assignment.reassign');
    });
});
