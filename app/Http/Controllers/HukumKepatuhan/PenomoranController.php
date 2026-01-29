<?php

namespace App\Http\Controllers\HukumKepatuhan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PenomoranController extends BaseDocumentController
{
    protected $model = \App\Models\HukumKepatuhan\Penomoran::class;
    protected $viewPath = 'hukum-kepatuhan.penomoran';
    protected $routePrefix = 'hukum-kepatuhan.penomoran';
    protected $moduleName = 'Penomoran';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'perihal' => 'nullable|string',
            'keterangan' => 'nullable|string|max:100',
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
            'perihal' => 1,
            'keterangan' => 2,
            'sifat_dokumen' => 3,
        ];
    }
}
