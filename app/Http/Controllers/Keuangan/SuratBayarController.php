<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class SuratBayarController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\SuratBayar::class;
    protected $viewPath = 'keuangan.surat-bayar';
    protected $routePrefix = 'keuangan.surat-bayar';
    protected $moduleName = 'Surat Bayar';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal_surat_bayar' => 'nullable|date',
            'nomor_surat_bayar' => 'nullable|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
