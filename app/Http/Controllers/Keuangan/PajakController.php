<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PajakController extends BaseDocumentController
{
    protected $model = \App\Models\Keuangan\Pajak::class;
    protected $viewPath = 'keuangan.pajak';
    protected $routePrefix = 'keuangan.pajak';
    protected $moduleName = 'Pajak';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'masa' => 'nullable|string|max:50',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'uraian' => 'nullable|string|max:255',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
