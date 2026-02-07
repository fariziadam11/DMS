<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RegulasiInternalController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\RegulasiInternal::class;
    protected $viewPath = 'hukum-kepatuhan.regulasi-internal';
    protected $routePrefix = 'hukum-kepatuhan.regulasi-internal';
    protected $moduleName = 'Regulasi Internal';
    protected $storagePath = 'documents/hukum-kepatuhan/regulasi-internal';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:100',
            'judul' => 'nullable|string',
            'tanggal' => 'nullable|date',
            'inisiator' => 'nullable|string|max:100',
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
            'inisiator' => 4,
            'status' => 5,
            'sifat_dokumen' => 6,
        ];
    }
}
