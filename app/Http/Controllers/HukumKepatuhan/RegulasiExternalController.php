<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RegulasiExternalController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\RegulasiExternal::class;
    protected $viewPath = 'hukum-kepatuhan.regulasi-external';
    protected $routePrefix = 'hukum-kepatuhan.regulasi-external';
    protected $moduleName = 'Regulasi External';
    protected $storagePath = 'documents/hukum-kepatuhan/regulasi-external';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'status' => 'nullable|string|max:100',
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
            'judul' => 2,
            'tanggal' => 3,
            'status' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
