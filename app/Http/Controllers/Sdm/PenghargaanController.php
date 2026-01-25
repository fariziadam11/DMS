<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PenghargaanController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Penghargaan::class;
    protected $viewPath = 'sdm.penghargaan';
    protected $routePrefix = 'sdm.penghargaan';
    protected $moduleName = 'Penghargaan';

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
}
