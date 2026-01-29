<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PksController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Pks::class;
    protected $viewPath = 'sdm.pks';
    protected $routePrefix = 'sdm.pks';
    protected $moduleName = 'Pks';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'nama' => 'required|string|max:255', // Assuming nama is title/description equivalent
            'mulai' => 'nullable|date',
            'akhir' => 'nullable|date',
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
            'nama' => 2,
            'mulai' => 3,
            'akhir' => 4,
            'kategori' => 5,
            'sifat_dokumen' => 6,
        ];
    }
}
