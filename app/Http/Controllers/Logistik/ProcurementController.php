<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class ProcurementController extends BaseDocumentController
{
    protected $model = \App\Models\Logistik\Procurement::class;
    protected $viewPath = 'logistik.procurement';
    protected $routePrefix = 'logistik.procurement';
    protected $moduleName = 'Procurement';
    protected $storagePath = 'documents/logistik/procurement';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'no_drp' => 'nullable|string|max:255',
            'nama_kegiatan' => 'nullable|string',
            'direktorat' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia'
        ]);
    }

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'no_drp' => 1,
            'nama_kegiatan' => 2,
            'direktorat' => 3,
            'sifat_dokumen' => 4,
        ];
    }
}
