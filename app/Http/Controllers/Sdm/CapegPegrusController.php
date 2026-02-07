<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class CapegPegrusController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\CapegPegrus::class;
    protected $viewPath = 'sdm.capeg-pegrus';
    protected $routePrefix = 'sdm.capeg-pegrus';
    protected $moduleName = 'Capeg Pegrus';
    protected $storagePath = 'documents/sdm/capeg-pegrus';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nik' => 'nullable|string|max:25',
            'nama' => 'required|string|max:100',
            'status' => 'nullable|integer',
            'perihal' => 'nullable|string|max:65535',
            'tanggal' => 'nullable|date',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
        ]);
    }

    /**
     * Configuration for Excel Import
     */
    protected function getImportConfig()
    {
        return [
            'nik' => 1,
            'nama' => 2,
            // 'status' => 3, // Status might be categorical, skip for now or verify values
            'perihal' => 3,
            'tanggal' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
