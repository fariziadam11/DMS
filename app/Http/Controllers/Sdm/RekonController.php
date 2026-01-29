<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class RekonController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Rekon::class;
    protected $viewPath = 'sdm.rekon';
    protected $routePrefix = 'sdm.rekon';
    protected $moduleName = 'Rekon';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'ba' => 'nullable|string|max:100',
            'hasil' => 'nullable|string|max:100',
            'kategori' => 'required|string|max:100',
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
            'tanggal' => 1,
            'ba' => 2,
            'hasil' => 3,
            'kategori' => 4,
            'sifat_dokumen' => 5,
        ];
    }
}
