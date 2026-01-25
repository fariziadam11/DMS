<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class SuratKeluarController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\SuratKeluar::class;
    protected $viewPath = 'sdm.surat-keluar';
    protected $routePrefix = 'sdm.surat-keluar';
    protected $moduleName = 'Surat Keluar';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'tanggal' => 'nullable|date',
            'perihal' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
