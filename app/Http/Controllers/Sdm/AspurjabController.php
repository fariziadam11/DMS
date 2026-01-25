<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class AspurjabController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\Aspurjab::class;
    protected $viewPath = 'sdm.aspurjab';
    protected $routePrefix = 'sdm.aspurjab';
    protected $moduleName = 'Aspurjab';

    protected function validateRequest(Request $request, $id = null): array
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'nomor' => 'nullable|string|max:255', // Changed to string as binary input handling might be specific, but standard text input for now
            'tanggal' => 'nullable|date',
            'nama' => 'required|string|max:100',
            'mulai' => 'nullable|date',
            'akhir' => 'nullable|date',
            'kategori' => 'required|string|max:100',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx',
            'sifat_dokumen' => 'nullable|in:Umum,Internal,Rahasia',
        ]);
    }
}
