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
    Route::get('profile/edit', [App\Http\Controllers\UserProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile/update', [App\Http\Controllers\UserProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [App\Http\Controllers\UserProfileController::class, 'updatePassword'])->name('profile.password');

    // Global Search
    Route::get('/search', [GlobalSearchController::class, 'search'])->name('search');
    Route::post('/search/request-access', [GlobalSearchController::class, 'requestAccess'])->name('search.request-access');

    // My Documents (Approved Access)
    Route::get('/my-documents', [\App\Http\Controllers\MyDocumentsController::class, 'index'])->name('my-documents.index');

    // Document Version (Old Archives)
    Route::get('/document-versions', [\App\Http\Controllers\DocumentVersionController::class, 'index'])->name('document-versions.index');
    Route::get('/document-versions/{id}/download', [\App\Http\Controllers\DocumentVersionController::class, 'download'])->name('document-versions.download');


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
        Route::get('aturan-kebijakan/excel/template', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'downloadTemplate'])->name('aturan-kebijakan.template');
        Route::get('aturan-kebijakan/import', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'import'])->name('aturan-kebijakan.import');
        Route::post('aturan-kebijakan/import', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'storeImport'])->name('aturan-kebijakan.store-import');
        Route::resource('aturan-kebijakan', \App\Http\Controllers\Akuntansi\AturanKebijakanController::class);
        Route::get('aturan-kebijakan/{id}/download', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'download'])->name('aturan-kebijakan.download');
        Route::get('aturan-kebijakan/{id}/preview', [\App\Http\Controllers\Akuntansi\AturanKebijakanController::class, 'preview'])->name('aturan-kebijakan.preview');
        Route::get('jurnal-umum/excel/template', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'downloadTemplate'])->name('jurnal-umum.template');
        Route::get('jurnal-umum/import', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'import'])->name('jurnal-umum.import');
        Route::post('jurnal-umum/import', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'storeImport'])->name('jurnal-umum.store-import');
        Route::resource('jurnal-umum', \App\Http\Controllers\Akuntansi\JurnalUmumController::class);
        Route::get('jurnal-umum/{id}/download', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'download'])->name('jurnal-umum.download');
        Route::get('jurnal-umum/{id}/preview', [\App\Http\Controllers\Akuntansi\JurnalUmumController::class, 'preview'])->name('jurnal-umum.preview');
        Route::get('laporan-audit-investasi/excel/template', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'downloadTemplate'])->name('laporan-audit-investasi.template');
        Route::get('laporan-audit-investasi/import', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'import'])->name('laporan-audit-investasi.import');
        Route::post('laporan-audit-investasi/import', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'storeImport'])->name('laporan-audit-investasi.store-import');
        Route::resource('laporan-audit-investasi', \App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class);
        Route::get('laporan-audit-investasi/{id}/download', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'download'])->name('laporan-audit-investasi.download');
        Route::get('laporan-audit-investasi/{id}/preview', [\App\Http\Controllers\Akuntansi\LaporanAuditInvestasiController::class, 'preview'])->name('laporan-audit-investasi.preview');
        Route::get('laporan-audit-keuangan/excel/template', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'downloadTemplate'])->name('laporan-audit-keuangan.template');
        Route::get('laporan-audit-keuangan/import', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'import'])->name('laporan-audit-keuangan.import');
        Route::post('laporan-audit-keuangan/import', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'storeImport'])->name('laporan-audit-keuangan.store-import');
        Route::resource('laporan-audit-keuangan', \App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class);
        Route::get('laporan-audit-keuangan/{id}/download', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'download'])->name('laporan-audit-keuangan.download');
        Route::get('laporan-audit-keuangan/{id}/preview', [\App\Http\Controllers\Akuntansi\LaporanAuditKeuanganController::class, 'preview'])->name('laporan-audit-keuangan.preview');
        Route::get('laporan-bulanan/excel/template', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'downloadTemplate'])->name('laporan-bulanan.template');
        Route::get('laporan-bulanan/import', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'import'])->name('laporan-bulanan.import');
        Route::post('laporan-bulanan/import', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'storeImport'])->name('laporan-bulanan.store-import');
        Route::resource('laporan-bulanan', \App\Http\Controllers\Akuntansi\LaporanBulananController::class);
        Route::get('laporan-bulanan/{id}/download', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'download'])->name('laporan-bulanan.download');
        Route::get('laporan-bulanan/{id}/preview', [\App\Http\Controllers\Akuntansi\LaporanBulananController::class, 'preview'])->name('laporan-bulanan.preview');
    });

    // ======================
    // MODULE: ANGGARAN
    // ======================
    Route::prefix('anggaran')->name('anggaran.')->group(function () {
        Route::get('aturan-kebijakan/excel/template', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'downloadTemplate'])->name('aturan-kebijakan.template');
        Route::get('aturan-kebijakan/import', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'import'])->name('aturan-kebijakan.import');
        Route::post('aturan-kebijakan/import', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'storeImport'])->name('aturan-kebijakan.store-import');
        Route::resource('aturan-kebijakan', \App\Http\Controllers\Anggaran\AturanKebijakanController::class);
        Route::get('aturan-kebijakan/{id}/download', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'download'])->name('aturan-kebijakan.download');
        Route::get('aturan-kebijakan/{id}/preview', [\App\Http\Controllers\Anggaran\AturanKebijakanController::class, 'preview'])->name('aturan-kebijakan.preview');
        Route::get('dokumen-rra/excel/template', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'downloadTemplate'])->name('dokumen-rra.template');
        Route::get('dokumen-rra/import', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'import'])->name('dokumen-rra.import');
        Route::post('dokumen-rra/import', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'storeImport'])->name('dokumen-rra.store-import');
        Route::resource('dokumen-rra', \App\Http\Controllers\Anggaran\DokumenRraController::class);
        Route::get('dokumen-rra/{id}/download', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'download'])->name('dokumen-rra.download');
        Route::get('dokumen-rra/{id}/preview', [\App\Http\Controllers\Anggaran\DokumenRraController::class, 'preview'])->name('dokumen-rra.preview');
        Route::get('laporan-prbc/excel/template', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'downloadTemplate'])->name('laporan-prbc.template');
        Route::get('laporan-prbc/import', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'import'])->name('laporan-prbc.import');
        Route::post('laporan-prbc/import', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'storeImport'])->name('laporan-prbc.store-import');
        Route::resource('laporan-prbc', \App\Http\Controllers\Anggaran\LaporanPrbcController::class);
        Route::get('laporan-prbc/{id}/download', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'download'])->name('laporan-prbc.download');
        Route::get('laporan-prbc/{id}/preview', [\App\Http\Controllers\Anggaran\LaporanPrbcController::class, 'preview'])->name('laporan-prbc.preview');
        Route::get('rencana-kerja-direktorat/excel/template', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'downloadTemplate'])->name('rencana-kerja-direktorat.template');
        Route::get('rencana-kerja-direktorat/import', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'import'])->name('rencana-kerja-direktorat.import');
        Route::post('rencana-kerja-direktorat/import', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'storeImport'])->name('rencana-kerja-direktorat.store-import');
        Route::resource('rencana-kerja-direktorat', \App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class);
        Route::get('rencana-kerja-direktorat/{id}/download', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'download'])->name('rencana-kerja-direktorat.download');
        Route::get('rencana-kerja-direktorat/{id}/preview', [\App\Http\Controllers\Anggaran\RencanaKerjaDirektoratController::class, 'preview'])->name('rencana-kerja-direktorat.preview');
        Route::get('rencana-kerja-tahunan/excel/template', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'downloadTemplate'])->name('rencana-kerja-tahunan.template');
        Route::get('rencana-kerja-tahunan/import', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'import'])->name('rencana-kerja-tahunan.import');
        Route::post('rencana-kerja-tahunan/import', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'storeImport'])->name('rencana-kerja-tahunan.store-import');
        Route::resource('rencana-kerja-tahunan', \App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class);
        Route::get('rencana-kerja-tahunan/{id}/download', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'download'])->name('rencana-kerja-tahunan.download');
        Route::get('rencana-kerja-tahunan/{id}/preview', [\App\Http\Controllers\Anggaran\RencanaKerjaTahunanController::class, 'preview'])->name('rencana-kerja-tahunan.preview');
        Route::get('rencana-kerja-triwulan/excel/template', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'downloadTemplate'])->name('rencana-kerja-triwulan.template');
        Route::get('rencana-kerja-triwulan/import', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'import'])->name('rencana-kerja-triwulan.import');
        Route::post('rencana-kerja-triwulan/import', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'storeImport'])->name('rencana-kerja-triwulan.store-import');
        Route::resource('rencana-kerja-triwulan', \App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class);
        Route::get('rencana-kerja-triwulan/{id}/download', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'download'])->name('rencana-kerja-triwulan.download');
        Route::get('rencana-kerja-triwulan/{id}/preview', [\App\Http\Controllers\Anggaran\RencanaKerjaTriwulanController::class, 'preview'])->name('rencana-kerja-triwulan.preview');
    });

    // ======================
    // MODULE: HUKUM & KEPATUHAN
    // ======================
    Route::prefix('hukum-kepatuhan')->name('hukum-kepatuhan.')->group(function () {
        Route::get('kajian-hukum/excel/template', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'downloadTemplate'])->name('kajian-hukum.template');
        Route::get('kajian-hukum/import', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'import'])->name('kajian-hukum.import');
        Route::post('kajian-hukum/import', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'storeImport'])->name('kajian-hukum.store-import');
        Route::resource('kajian-hukum', \App\Http\Controllers\HukumKepatuhan\KajianHukumController::class);
        Route::get('kajian-hukum/{id}/download', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'download'])->name('kajian-hukum.download');
        Route::get('kajian-hukum/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\KajianHukumController::class, 'preview'])->name('kajian-hukum.preview');
        Route::get('legal-memo/excel/template', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'downloadTemplate'])->name('legal-memo.template');
        Route::get('legal-memo/import', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'import'])->name('legal-memo.import');
        Route::post('legal-memo/import', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'storeImport'])->name('legal-memo.store-import');
        Route::resource('legal-memo', \App\Http\Controllers\HukumKepatuhan\LegalMemoController::class);
        Route::get('legal-memo/{id}/download', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'download'])->name('legal-memo.download');
        Route::get('legal-memo/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\LegalMemoController::class, 'preview'])->name('legal-memo.preview');
        Route::get('regulasi-internal/excel/template', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'downloadTemplate'])->name('regulasi-internal.template');
        Route::get('regulasi-internal/import', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'import'])->name('regulasi-internal.import');
        Route::post('regulasi-internal/import', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'storeImport'])->name('regulasi-internal.store-import');
        Route::resource('regulasi-internal', \App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class);
        Route::get('regulasi-internal/{id}/download', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'download'])->name('regulasi-internal.download');
        Route::get('regulasi-internal/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\RegulasiInternalController::class, 'preview'])->name('regulasi-internal.preview');
        Route::get('regulasi-external/excel/template', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'downloadTemplate'])->name('regulasi-external.template');
        Route::get('regulasi-external/import', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'import'])->name('regulasi-external.import');
        Route::post('regulasi-external/import', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'storeImport'])->name('regulasi-external.store-import');
        Route::resource('regulasi-external', \App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class);
        Route::get('regulasi-external/{id}/download', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'download'])->name('regulasi-external.download');
        Route::get('regulasi-external/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\RegulasiExternalController::class, 'preview'])->name('regulasi-external.preview');
        Route::get('kontrak/excel/template', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'downloadTemplate'])->name('kontrak.template');
        Route::get('kontrak/import', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'import'])->name('kontrak.import');
        Route::post('kontrak/import', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'storeImport'])->name('kontrak.store-import');
        Route::resource('kontrak', \App\Http\Controllers\HukumKepatuhan\KontrakController::class);
        Route::get('kontrak/{id}/download', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'download'])->name('kontrak.download');
        Route::get('kontrak/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\KontrakController::class, 'preview'])->name('kontrak.preview');
        Route::get('putusan/excel/template', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'downloadTemplate'])->name('putusan.template');
        Route::get('putusan/import', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'import'])->name('putusan.import');
        Route::post('putusan/import', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'storeImport'])->name('putusan.store-import');
        Route::resource('putusan', \App\Http\Controllers\HukumKepatuhan\PutusanController::class);
        Route::get('putusan/{id}/download', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'download'])->name('putusan.download');
        Route::get('putusan/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\PutusanController::class, 'preview'])->name('putusan.preview');
        Route::get('compliance-check/excel/template', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'downloadTemplate'])->name('compliance-check.template');
        Route::get('compliance-check/import', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'import'])->name('compliance-check.import');
        Route::post('compliance-check/import', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'storeImport'])->name('compliance-check.store-import');
        Route::resource('compliance-check', \App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class);
        Route::get('compliance-check/{id}/download', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'download'])->name('compliance-check.download');
        Route::get('compliance-check/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\ComplianceCheckController::class, 'preview'])->name('compliance-check.preview');
        Route::get('executive-summary/excel/template', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'downloadTemplate'])->name('executive-summary.template');
        Route::get('executive-summary/import', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'import'])->name('executive-summary.import');
        Route::post('executive-summary/import', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'storeImport'])->name('executive-summary.store-import');
        Route::resource('executive-summary', \App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class);
        Route::get('executive-summary/{id}/download', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'download'])->name('executive-summary.download');
        Route::get('executive-summary/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\ExecutiveSummaryController::class, 'preview'])->name('executive-summary.preview');
        Route::get('lembar-keputusan/excel/template', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'downloadTemplate'])->name('lembar-keputusan.template');
        Route::get('lembar-keputusan/import', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'import'])->name('lembar-keputusan.import');
        Route::post('lembar-keputusan/import', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'storeImport'])->name('lembar-keputusan.store-import');
        Route::resource('lembar-keputusan', \App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class);
        Route::get('lembar-keputusan/{id}/download', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'download'])->name('lembar-keputusan.download');
        Route::get('lembar-keputusan/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\LembarKeputusanController::class, 'preview'])->name('lembar-keputusan.preview');
        Route::get('lembar-rekomendasi/excel/template', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'downloadTemplate'])->name('lembar-rekomendasi.template');
        Route::get('lembar-rekomendasi/import', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'import'])->name('lembar-rekomendasi.import');
        Route::post('lembar-rekomendasi/import', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'storeImport'])->name('lembar-rekomendasi.store-import');
        Route::resource('lembar-rekomendasi', \App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class);
        Route::get('lembar-rekomendasi/{id}/download', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'download'])->name('lembar-rekomendasi.download');
        Route::get('lembar-rekomendasi/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\LembarRekomendasiController::class, 'preview'])->name('lembar-rekomendasi.preview');
        Route::get('penomoran/excel/template', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'downloadTemplate'])->name('penomoran.template');
        Route::get('penomoran/import', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'import'])->name('penomoran.import');
        Route::post('penomoran/import', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'storeImport'])->name('penomoran.store-import');
        Route::resource('penomoran', \App\Http\Controllers\HukumKepatuhan\PenomoranController::class);
        Route::get('penomoran/{id}/download', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'download'])->name('penomoran.download');
        Route::get('penomoran/{id}/preview', [\App\Http\Controllers\HukumKepatuhan\PenomoranController::class, 'preview'])->name('penomoran.preview');
    });

    // ======================
    // MODULE: INVESTASI
    // ======================
    Route::prefix('investasi')->name('investasi.')->group(function () {
        Route::get('transaksi/excel/template', [\App\Http\Controllers\Investasi\TransaksiController::class, 'downloadTemplate'])->name('transaksi.template');
        Route::get('transaksi/import', [\App\Http\Controllers\Investasi\TransaksiController::class, 'import'])->name('transaksi.import');
        Route::post('transaksi/import', [\App\Http\Controllers\Investasi\TransaksiController::class, 'storeImport'])->name('transaksi.store-import');
        Route::resource('transaksi', \App\Http\Controllers\Investasi\TransaksiController::class);
        Route::get('transaksi/{id}/download', [\App\Http\Controllers\Investasi\TransaksiController::class, 'download'])->name('transaksi.download');
        Route::get('transaksi/{id}/preview', [\App\Http\Controllers\Investasi\TransaksiController::class, 'preview'])->name('transaksi.preview');
        Route::get('surat/excel/template', [\App\Http\Controllers\Investasi\SuratController::class, 'downloadTemplate'])->name('surat.template');
        Route::get('surat/import', [\App\Http\Controllers\Investasi\SuratController::class, 'import'])->name('surat.import');
        Route::post('surat/import', [\App\Http\Controllers\Investasi\SuratController::class, 'storeImport'])->name('surat.store-import');
        Route::resource('surat', \App\Http\Controllers\Investasi\SuratController::class);
        Route::get('surat/{id}/download', [\App\Http\Controllers\Investasi\SuratController::class, 'download'])->name('surat.download');
        Route::get('surat/{id}/preview', [\App\Http\Controllers\Investasi\SuratController::class, 'preview'])->name('surat.preview');
        Route::get('perencanaan-transaksi/excel/template', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'downloadTemplate'])->name('perencanaan-transaksi.template');
        Route::get('perencanaan-transaksi/import', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'import'])->name('perencanaan-transaksi.import');
        Route::post('perencanaan-transaksi/import', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'storeImport'])->name('perencanaan-transaksi.store-import');
        Route::resource('perencanaan-transaksi', \App\Http\Controllers\Investasi\PerencanaanTransaksiController::class);
        Route::get('perencanaan-transaksi/{id}/download', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'download'])->name('perencanaan-transaksi.download');
        Route::get('perencanaan-transaksi/{id}/preview', [\App\Http\Controllers\Investasi\PerencanaanTransaksiController::class, 'preview'])->name('perencanaan-transaksi.preview');
        Route::get('perencanaan-surat/excel/template', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'downloadTemplate'])->name('perencanaan-surat.template');
        Route::get('perencanaan-surat/import', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'import'])->name('perencanaan-surat.import');
        Route::post('perencanaan-surat/import', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'storeImport'])->name('perencanaan-surat.store-import');
        Route::resource('perencanaan-surat', \App\Http\Controllers\Investasi\PerencanaanSuratController::class);
        Route::get('perencanaan-surat/{id}/download', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'download'])->name('perencanaan-surat.download');
        Route::get('perencanaan-surat/{id}/preview', [\App\Http\Controllers\Investasi\PerencanaanSuratController::class, 'preview'])->name('perencanaan-surat.preview');
        Route::get('propensa-transaksi/excel/template', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'downloadTemplate'])->name('propensa-transaksi.template');
        Route::get('propensa-transaksi/import', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'import'])->name('propensa-transaksi.import');
        Route::post('propensa-transaksi/import', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'storeImport'])->name('propensa-transaksi.store-import');
        Route::resource('propensa-transaksi', \App\Http\Controllers\Investasi\PropensaTransaksiController::class);
        Route::get('propensa-transaksi/{id}/download', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'download'])->name('propensa-transaksi.download');
        Route::get('propensa-transaksi/{id}/preview', [\App\Http\Controllers\Investasi\PropensaTransaksiController::class, 'preview'])->name('propensa-transaksi.preview');
        Route::get('propensa-surat/excel/template', [\App\Http\Controllers\Investasi\PropensaSuratController::class, 'downloadTemplate'])->name('propensa-surat.template');
        Route::get('propensa-surat/import', [\App\Http\Controllers\Investasi\PropensaSuratController::class, 'import'])->name('propensa-surat.import');
        Route::post('propensa-surat/import', [\App\Http\Controllers\Investasi\PropensaSuratController::class, 'storeImport'])->name('propensa-surat.store-import');
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
        Route::get('pks/excel/template', [\App\Http\Controllers\Sdm\PksController::class, 'downloadTemplate'])->name('pks.template');
        Route::get('pks/import', [\App\Http\Controllers\Sdm\PksController::class, 'import'])->name('pks.import');
        Route::post('pks/import', [\App\Http\Controllers\Sdm\PksController::class, 'storeImport'])->name('pks.store-import');
        Route::resource('pks', \App\Http\Controllers\Sdm\PksController::class);
        Route::get('pks/{id}/download', [\App\Http\Controllers\Sdm\PksController::class, 'download'])->name('pks.download');
        Route::get('pks/{id}/preview', [\App\Http\Controllers\Sdm\PksController::class, 'preview'])->name('pks.preview');
        Route::get('rarus/excel/template', [\App\Http\Controllers\Sdm\RarusController::class, 'downloadTemplate'])->name('rarus.template');
        Route::get('rarus/import', [\App\Http\Controllers\Sdm\RarusController::class, 'import'])->name('rarus.import');
        Route::post('rarus/import', [\App\Http\Controllers\Sdm\RarusController::class, 'storeImport'])->name('rarus.store-import');
        Route::resource('rarus', \App\Http\Controllers\Sdm\RarusController::class);
        Route::get('rarus/{id}/download', [\App\Http\Controllers\Sdm\RarusController::class, 'download'])->name('rarus.download');
        Route::get('rarus/{id}/preview', [\App\Http\Controllers\Sdm\RarusController::class, 'preview'])->name('rarus.preview');
        Route::get('peraturan/excel/template', [\App\Http\Controllers\Sdm\PeraturanController::class, 'downloadTemplate'])->name('peraturan.template');
        Route::get('peraturan/import', [\App\Http\Controllers\Sdm\PeraturanController::class, 'import'])->name('peraturan.import');
        Route::post('peraturan/import', [\App\Http\Controllers\Sdm\PeraturanController::class, 'storeImport'])->name('peraturan.store-import');
        Route::resource('peraturan', \App\Http\Controllers\Sdm\PeraturanController::class);
        Route::get('peraturan/{id}/download', [\App\Http\Controllers\Sdm\PeraturanController::class, 'download'])->name('peraturan.download');
        Route::get('peraturan/{id}/preview', [\App\Http\Controllers\Sdm\PeraturanController::class, 'preview'])->name('peraturan.preview');
        Route::get('rekrut-masuk/excel/template', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'downloadTemplate'])->name('rekrut-masuk.template');
        Route::get('rekrut-masuk/import', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'import'])->name('rekrut-masuk.import');
        Route::post('rekrut-masuk/import', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'storeImport'])->name('rekrut-masuk.store-import');
        Route::resource('rekrut-masuk', \App\Http\Controllers\Sdm\RekrutMasukController::class);
        Route::get('rekrut-masuk/{id}/download', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'download'])->name('rekrut-masuk.download');
        Route::get('rekrut-masuk/{id}/preview', [\App\Http\Controllers\Sdm\RekrutMasukController::class, 'preview'])->name('rekrut-masuk.preview');
        Route::get('promosi-mutasi/excel/template', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'downloadTemplate'])->name('promosi-mutasi.template');
        Route::get('promosi-mutasi/import', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'import'])->name('promosi-mutasi.import');
        Route::post('promosi-mutasi/import', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'storeImport'])->name('promosi-mutasi.store-import');
        Route::resource('promosi-mutasi', \App\Http\Controllers\Sdm\PromosiMutasiController::class);
        Route::get('promosi-mutasi/{id}/download', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'download'])->name('promosi-mutasi.download');
        Route::get('promosi-mutasi/{id}/preview', [\App\Http\Controllers\Sdm\PromosiMutasiController::class, 'preview'])->name('promosi-mutasi.preview');
        Route::get('naik-gaji/excel/template', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'downloadTemplate'])->name('naik-gaji.template');
        Route::get('naik-gaji/import', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'import'])->name('naik-gaji.import');
        Route::post('naik-gaji/import', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'storeImport'])->name('naik-gaji.store-import');
        Route::resource('naik-gaji', \App\Http\Controllers\Sdm\NaikGajiController::class);
        Route::get('naik-gaji/{id}/download', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'download'])->name('naik-gaji.download');
        Route::get('naik-gaji/{id}/preview', [\App\Http\Controllers\Sdm\NaikGajiController::class, 'preview'])->name('naik-gaji.preview');
        Route::get('surat-masuk/excel/template', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'downloadTemplate'])->name('surat-masuk.template');
        Route::get('surat-masuk/import', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'import'])->name('surat-masuk.import');
        Route::post('surat-masuk/import', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'storeImport'])->name('surat-masuk.store-import');
        Route::resource('surat-masuk', \App\Http\Controllers\Sdm\SuratMasukController::class);
        Route::get('surat-masuk/{id}/download', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'download'])->name('surat-masuk.download');
        Route::get('surat-masuk/{id}/preview', [\App\Http\Controllers\Sdm\SuratMasukController::class, 'preview'])->name('surat-masuk.preview');
        Route::get('surat-keluar/excel/template', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'downloadTemplate'])->name('surat-keluar.template');
        Route::get('surat-keluar/import', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'import'])->name('surat-keluar.import');
        Route::post('surat-keluar/import', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'storeImport'])->name('surat-keluar.store-import');
        Route::resource('surat-keluar', \App\Http\Controllers\Sdm\SuratKeluarController::class);
        Route::get('surat-keluar/{id}/download', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'download'])->name('surat-keluar.download');
        Route::get('surat-keluar/{id}/preview', [\App\Http\Controllers\Sdm\SuratKeluarController::class, 'preview'])->name('surat-keluar.preview');
        Route::get('capeg-pegrus/excel/template', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'downloadTemplate'])->name('capeg-pegrus.template');
        Route::get('capeg-pegrus/import', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'import'])->name('capeg-pegrus.import');
        Route::post('capeg-pegrus/import', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'storeImport'])->name('capeg-pegrus.store-import');
        Route::resource('capeg-pegrus', \App\Http\Controllers\Sdm\CapegPegrusController::class);
        Route::get('capeg-pegrus/{id}/download', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'download'])->name('capeg-pegrus.download');
        Route::get('capeg-pegrus/{id}/preview', [\App\Http\Controllers\Sdm\CapegPegrusController::class, 'preview'])->name('capeg-pegrus.preview');
        Route::get('penghargaan/excel/template', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'downloadTemplate'])->name('penghargaan.template');
        Route::get('penghargaan/import', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'import'])->name('penghargaan.import');
        Route::post('penghargaan/import', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'storeImport'])->name('penghargaan.store-import');
        Route::resource('penghargaan', \App\Http\Controllers\Sdm\PenghargaanController::class);
        Route::get('penghargaan/{id}/download', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'download'])->name('penghargaan.download');
        Route::get('penghargaan/{id}/preview', [\App\Http\Controllers\Sdm\PenghargaanController::class, 'preview'])->name('penghargaan.preview');
        Route::get('ikut-organisasi/excel/template', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'downloadTemplate'])->name('ikut-organisasi.template');
        Route::get('ikut-organisasi/import', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'import'])->name('ikut-organisasi.import');
        Route::post('ikut-organisasi/import', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'storeImport'])->name('ikut-organisasi.store-import');
        Route::resource('ikut-organisasi', \App\Http\Controllers\Sdm\IkutOrganisasiController::class);
        Route::get('ikut-organisasi/{id}/download', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'download'])->name('ikut-organisasi.download');
        Route::get('ikut-organisasi/{id}/preview', [\App\Http\Controllers\Sdm\IkutOrganisasiController::class, 'preview'])->name('ikut-organisasi.preview');
        Route::get('aspurjab/excel/template', [\App\Http\Controllers\Sdm\AspurjabController::class, 'downloadTemplate'])->name('aspurjab.template');
        Route::get('aspurjab/import', [\App\Http\Controllers\Sdm\AspurjabController::class, 'import'])->name('aspurjab.import');
        Route::post('aspurjab/import', [\App\Http\Controllers\Sdm\AspurjabController::class, 'storeImport'])->name('aspurjab.store-import');
        Route::resource('aspurjab', \App\Http\Controllers\Sdm\AspurjabController::class);
        Route::get('aspurjab/{id}/download', [\App\Http\Controllers\Sdm\AspurjabController::class, 'download'])->name('aspurjab.download');
        Route::get('aspurjab/{id}/preview', [\App\Http\Controllers\Sdm\AspurjabController::class, 'preview'])->name('aspurjab.preview');
        Route::get('rekon/excel/template', [\App\Http\Controllers\Sdm\RekonController::class, 'downloadTemplate'])->name('rekon.template');
        Route::get('rekon/import', [\App\Http\Controllers\Sdm\RekonController::class, 'import'])->name('rekon.import');
        Route::post('rekon/import', [\App\Http\Controllers\Sdm\RekonController::class, 'storeImport'])->name('rekon.store-import');
        Route::resource('rekon', \App\Http\Controllers\Sdm\RekonController::class);
        Route::get('rekon/{id}/download', [\App\Http\Controllers\Sdm\RekonController::class, 'download'])->name('rekon.download');
        Route::get('rekon/{id}/preview', [\App\Http\Controllers\Sdm\RekonController::class, 'preview'])->name('rekon.preview');
    });

    // ======================
    // MODULE: SEKRETARIAT
    // ======================
    Route::prefix('sekretariat')->name('sekretariat.')->group(function () {
        Route::get('risalah-rapat/excel/template', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'downloadTemplate'])->name('risalah-rapat.template');
        Route::get('risalah-rapat/import', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'import'])->name('risalah-rapat.import');
        Route::post('risalah-rapat/import', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'storeImport'])->name('risalah-rapat.store-import');
        Route::resource('risalah-rapat', \App\Http\Controllers\Sekretariat\RisalahRapatController::class);
        Route::get('risalah-rapat/{id}/download', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'download'])->name('risalah-rapat.download');
        Route::get('risalah-rapat/{id}/preview', [\App\Http\Controllers\Sekretariat\RisalahRapatController::class, 'preview'])->name('risalah-rapat.preview');
        Route::get('materi/excel/template', [\App\Http\Controllers\Sekretariat\MateriController::class, 'downloadTemplate'])->name('materi.template');
        Route::get('materi/import', [\App\Http\Controllers\Sekretariat\MateriController::class, 'import'])->name('materi.import');
        Route::post('materi/import', [\App\Http\Controllers\Sekretariat\MateriController::class, 'storeImport'])->name('materi.store-import');
        Route::resource('materi', \App\Http\Controllers\Sekretariat\MateriController::class);
        Route::get('materi/{id}/download', [\App\Http\Controllers\Sekretariat\MateriController::class, 'download'])->name('materi.download');
        Route::get('materi/{id}/preview', [\App\Http\Controllers\Sekretariat\MateriController::class, 'preview'])->name('materi.preview');
        Route::get('laporan/excel/template', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'downloadTemplate'])->name('laporan.template');
        Route::get('laporan/import', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'import'])->name('laporan.import');
        Route::post('laporan/import', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'storeImport'])->name('laporan.store-import');
        Route::resource('laporan', \App\Http\Controllers\Sekretariat\LaporanController::class);
        Route::get('laporan/{id}/download', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'download'])->name('laporan.download');
        Route::get('laporan/{id}/preview', [\App\Http\Controllers\Sekretariat\LaporanController::class, 'preview'])->name('laporan.preview');
        Route::resource('surat', \App\Http\Controllers\Sekretariat\SuratController::class);
        Route::get('surat/excel/template', [\App\Http\Controllers\Sekretariat\SuratController::class, 'downloadTemplate'])->name('surat.template');
        Route::get('surat/excel/import', [\App\Http\Controllers\Sekretariat\SuratController::class, 'import'])->name('surat.import');
        Route::post('surat/excel/import', [\App\Http\Controllers\Sekretariat\SuratController::class, 'storeImport'])->name('surat.store-import');
        Route::get('surat/{id}/download', [\App\Http\Controllers\Sekretariat\SuratController::class, 'download'])->name('surat.download');
        Route::get('surat/{id}/preview', [\App\Http\Controllers\Sekretariat\SuratController::class, 'preview'])->name('surat.preview');
        Route::get('pengadaan/excel/template', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'downloadTemplate'])->name('pengadaan.template');
        Route::get('pengadaan/import', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'import'])->name('pengadaan.import');
        Route::post('pengadaan/import', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'storeImport'])->name('pengadaan.store-import');
        Route::resource('pengadaan', \App\Http\Controllers\Sekretariat\PengadaanController::class);
        Route::get('pengadaan/{id}/download', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'download'])->name('pengadaan.download');
        Route::get('pengadaan/{id}/preview', [\App\Http\Controllers\Sekretariat\PengadaanController::class, 'preview'])->name('pengadaan.preview');
        Route::get('remunerasi-pedoman/excel/template', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'downloadTemplate'])->name('remunerasi-pedoman.template');
        Route::get('remunerasi-pedoman/import', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'import'])->name('remunerasi-pedoman.import');
        Route::post('remunerasi-pedoman/import', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'storeImport'])->name('remunerasi-pedoman.store-import');
        Route::resource('remunerasi-pedoman', \App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class);
        Route::get('remunerasi-pedoman/{id}/download', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'download'])->name('remunerasi-pedoman.download');
        Route::get('remunerasi-pedoman/{id}/preview', [\App\Http\Controllers\Sekretariat\RemunerasiPedomanController::class, 'preview'])->name('remunerasi-pedoman.preview');
        Route::get('remunerasi-dokumen/excel/template', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'downloadTemplate'])->name('remunerasi-dokumen.template');
        Route::get('remunerasi-dokumen/import', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'import'])->name('remunerasi-dokumen.import');
        Route::post('remunerasi-dokumen/import', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'storeImport'])->name('remunerasi-dokumen.store-import');
        Route::resource('remunerasi-dokumen', \App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class);
        Route::get('remunerasi-dokumen/{id}/download', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'download'])->name('remunerasi-dokumen.download');
        Route::get('remunerasi-dokumen/{id}/preview', [\App\Http\Controllers\Sekretariat\RemunerasiDokumenController::class, 'preview'])->name('remunerasi-dokumen.preview');
    });

    // ======================
    // MODULE: LOGISTIK & SARPEN
    // ======================
    Route::prefix('logistik')->name('logistik.')->group(function () {
        Route::get('procurement/excel/template', [\App\Http\Controllers\Logistik\ProcurementController::class, 'downloadTemplate'])->name('procurement.template');
        Route::get('procurement/import', [\App\Http\Controllers\Logistik\ProcurementController::class, 'import'])->name('procurement.import');
        Route::post('procurement/import', [\App\Http\Controllers\Logistik\ProcurementController::class, 'storeImport'])->name('procurement.store-import');
        Route::resource('procurement', \App\Http\Controllers\Logistik\ProcurementController::class);
        Route::get('procurement/{id}/download', [\App\Http\Controllers\Logistik\ProcurementController::class, 'download'])->name('procurement.download');
        Route::get('procurement/{id}/preview', [\App\Http\Controllers\Logistik\ProcurementController::class, 'preview'])->name('procurement.preview');
        Route::get('cleaning-service/excel/template', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'downloadTemplate'])->name('cleaning-service.template');
        Route::get('cleaning-service/import', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'import'])->name('cleaning-service.import');
        Route::post('cleaning-service/import', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'storeImport'])->name('cleaning-service.store-import');
        Route::resource('cleaning-service', \App\Http\Controllers\Logistik\CleaningServiceController::class);
        Route::get('cleaning-service/{id}/download', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'download'])->name('cleaning-service.download');
        Route::get('cleaning-service/{id}/preview', [\App\Http\Controllers\Logistik\CleaningServiceController::class, 'preview'])->name('cleaning-service.preview');
        Route::get('keamanan/excel/template', [\App\Http\Controllers\Logistik\KeamananController::class, 'downloadTemplate'])->name('keamanan.template');
        Route::get('keamanan/import', [\App\Http\Controllers\Logistik\KeamananController::class, 'import'])->name('keamanan.import');
        Route::post('keamanan/import', [\App\Http\Controllers\Logistik\KeamananController::class, 'storeImport'])->name('keamanan.store-import');
        Route::resource('keamanan', \App\Http\Controllers\Logistik\KeamananController::class);
        Route::get('keamanan/{id}/download', [\App\Http\Controllers\Logistik\KeamananController::class, 'download'])->name('keamanan.download');
        Route::get('keamanan/{id}/preview', [\App\Http\Controllers\Logistik\KeamananController::class, 'preview'])->name('keamanan.preview');
        Route::get('kendaraan/excel/template', [\App\Http\Controllers\Logistik\KendaraanController::class, 'downloadTemplate'])->name('kendaraan.template');
        Route::get('kendaraan/import', [\App\Http\Controllers\Logistik\KendaraanController::class, 'import'])->name('kendaraan.import');
        Route::post('kendaraan/import', [\App\Http\Controllers\Logistik\KendaraanController::class, 'storeImport'])->name('kendaraan.store-import');
        Route::resource('kendaraan', \App\Http\Controllers\Logistik\KendaraanController::class);
        Route::get('kendaraan/{id}/download', [\App\Http\Controllers\Logistik\KendaraanController::class, 'download'])->name('kendaraan.download');
        Route::get('kendaraan/{id}/preview', [\App\Http\Controllers\Logistik\KendaraanController::class, 'preview'])->name('kendaraan.preview');
        Route::get('sarana-penunjang/excel/template', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'downloadTemplate'])->name('sarana-penunjang.template');
        Route::get('sarana-penunjang/import', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'import'])->name('sarana-penunjang.import');
        Route::post('sarana-penunjang/import', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'storeImport'])->name('sarana-penunjang.store-import');
        Route::resource('sarana-penunjang', \App\Http\Controllers\Logistik\SaranaPenunjangController::class);
        Route::get('sarana-penunjang/{id}/download', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'download'])->name('sarana-penunjang.download');
        Route::get('sarana-penunjang/{id}/preview', [\App\Http\Controllers\Logistik\SaranaPenunjangController::class, 'preview'])->name('sarana-penunjang.preview');
        Route::get('smk3/excel/template', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'downloadTemplate'])->name('smk3.template');
        Route::get('smk3/import', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'import'])->name('smk3.import');
        Route::post('smk3/import', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'storeImport'])->name('smk3.store-import');
        Route::resource('smk3', \App\Http\Controllers\Logistik\Smk3Controller::class);
        Route::get('smk3/{id}/download', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'download'])->name('smk3.download');
        Route::get('smk3/{id}/preview', [\App\Http\Controllers\Logistik\Smk3Controller::class, 'preview'])->name('smk3.preview');
        Route::get('polis-asuransi/excel/template', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'downloadTemplate'])->name('polis-asuransi.template');
        Route::get('polis-asuransi/import', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'import'])->name('polis-asuransi.import');
        Route::post('polis-asuransi/import', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'storeImport'])->name('polis-asuransi.store-import');
        Route::resource('polis-asuransi', \App\Http\Controllers\Logistik\PolisAsuransiController::class);
        Route::get('polis-asuransi/{id}/download', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'download'])->name('polis-asuransi.download');
        Route::get('polis-asuransi/{id}/preview', [\App\Http\Controllers\Logistik\PolisAsuransiController::class, 'preview'])->name('polis-asuransi.preview');
        Route::get('jaminan/excel/template', [\App\Http\Controllers\Logistik\JaminanController::class, 'downloadTemplate'])->name('jaminan.template');
        Route::get('jaminan/import', [\App\Http\Controllers\Logistik\JaminanController::class, 'import'])->name('jaminan.import');
        Route::post('jaminan/import', [\App\Http\Controllers\Logistik\JaminanController::class, 'storeImport'])->name('jaminan.store-import');
        Route::resource('jaminan', \App\Http\Controllers\Logistik\JaminanController::class);
        Route::get('jaminan/{id}/download', [\App\Http\Controllers\Logistik\JaminanController::class, 'download'])->name('jaminan.download');
        Route::get('jaminan/{id}/preview', [\App\Http\Controllers\Logistik\JaminanController::class, 'preview'])->name('jaminan.preview');
        Route::get('pelaporan-prbc/excel/template', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'downloadTemplate'])->name('pelaporan-prbc.template');
        Route::get('pelaporan-prbc/import', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'import'])->name('pelaporan-prbc.import');
        Route::post('pelaporan-prbc/import', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'storeImport'])->name('pelaporan-prbc.store-import');
        Route::resource('pelaporan-prbc', \App\Http\Controllers\Logistik\PelaporanPrbcController::class);
        Route::get('pelaporan-prbc/{id}/download', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'download'])->name('pelaporan-prbc.download');
        Route::get('pelaporan-prbc/{id}/preview', [\App\Http\Controllers\Logistik\PelaporanPrbcController::class, 'preview'])->name('pelaporan-prbc.preview');
        Route::get('user-satisfaction/excel/template', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'downloadTemplate'])->name('user-satisfaction.template');
        Route::get('user-satisfaction/import', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'import'])->name('user-satisfaction.import');
        Route::post('user-satisfaction/import', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'storeImport'])->name('user-satisfaction.store-import');
        Route::resource('user-satisfaction', \App\Http\Controllers\Logistik\UserSatisfactionController::class);
        Route::get('user-satisfaction/{id}/download', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'download'])->name('user-satisfaction.download');
        Route::get('user-satisfaction/{id}/preview', [\App\Http\Controllers\Logistik\UserSatisfactionController::class, 'preview'])->name('user-satisfaction.preview');
        Route::get('vendor-satisfaction/excel/template', [\App\Http\Controllers\Logistik\VendorSatisfactionController::class, 'downloadTemplate'])->name('vendor-satisfaction.template');
        Route::get('vendor-satisfaction/import', [\App\Http\Controllers\Logistik\VendorSatisfactionController::class, 'import'])->name('vendor-satisfaction.import');
        Route::post('vendor-satisfaction/import', [\App\Http\Controllers\Logistik\VendorSatisfactionController::class, 'storeImport'])->name('vendor-satisfaction.store-import');
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
        Route::get('users/ajax/divisions/{departmentId}', [\App\Http\Controllers\Admin\UserController::class, 'getDivisions'])->name('users.ajax.divisions');
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
