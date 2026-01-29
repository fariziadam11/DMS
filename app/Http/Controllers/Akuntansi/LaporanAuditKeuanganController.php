<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Akuntansi\LaporanAuditKeuangan;
use Illuminate\Http\Request;

class LaporanAuditKeuanganController extends BaseDocumentController
{
    protected $model = LaporanAuditKeuangan::class;
    protected $viewPath = 'akuntansi.laporan-audit-keuangan';
    protected $routePrefix = 'akuntansi.laporan-audit-keuangan';
    protected $moduleName = 'Akuntansi - Laporan Audit Keuangan';
    protected $storagePath = 'documents/akuntansi/laporan-audit-keuangan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'judul' => 'required|string',
            'nama_kap' => 'nullable|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'judul' => 1,
            'tanggal' => 2,
            'nama_kap' => 3,
            'sifat_dokumen' => 4,
        ];
    }
}
