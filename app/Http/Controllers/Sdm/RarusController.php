<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RarusController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Rarus::class;
    protected $viewPath = 'sdm.rarus';
    protected $routePrefix = 'sdm.rarus';
    protected $moduleName = 'Rarus';
    protected $storagePath = 'documents/sdm/rarus';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'perihal' => 'nullable|string|max:65535',
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
            'tanggal' => 1,
            'perihal' => 2,
            'kategori' => 3,
            'sifat_dokumen' => 4,
        ];
    }
}
