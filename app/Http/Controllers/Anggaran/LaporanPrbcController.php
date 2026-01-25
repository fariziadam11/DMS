<?php

namespace App\Http\Controllers\Anggaran;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class LaporanPrbcController extends BaseDocumentController
{
    protected $model = \App\Models\Anggaran\LaporanPrbc::class;
    protected $viewPath = 'anggaran.laporan-prbc';
    protected $routePrefix = 'anggaran.laporan-prbc';
    protected $moduleName = 'Laporan PRBC';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'bulan' => 'nullable|integer|min:1|max:12',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
