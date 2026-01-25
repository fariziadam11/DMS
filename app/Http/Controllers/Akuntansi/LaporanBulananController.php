<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Akuntansi\LaporanBulanan;
use Illuminate\Http\Request;

class LaporanBulananController extends BaseDocumentController
{
    protected $model = LaporanBulanan::class;
    protected $viewPath = 'akuntansi.laporan-bulanan';
    protected $routePrefix = 'akuntansi.laporan-bulanan';
    protected $moduleName = 'Akuntansi - Laporan Bulanan';
    protected $storagePath = 'documents/akuntansi/laporan-bulanan';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'judul' => 'required|string|max:100',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}
