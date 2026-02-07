<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PemindahbukuanController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Pemindahbukuan::class;
    protected $viewPath = 'keuangan.pemindahbukuan';
    protected $routePrefix = 'keuangan.pemindahbukuan';
    protected $moduleName = 'Pemindahbukuan';
    protected $storagePath = 'documents/keuangan/pemindahbukuan';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tanggal' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'uraian' => 'nullable|string|max:255',
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
            'nominal' => 2,
            'uraian' => 3,
        ];
    }
}
