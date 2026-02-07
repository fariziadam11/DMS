<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class SuratMasukController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\SuratMasuk::class;
    protected $viewPath = 'sdm.surat-masuk';
    protected $routePrefix = 'sdm.surat-masuk';
    protected $moduleName = 'Surat Masuk';
    protected $storagePath = 'documents/sdm/surat-masuk';

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

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'nomor' => 1,
            'tanggal' => 2,
            'perihal' => 3,
            'kategori' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
