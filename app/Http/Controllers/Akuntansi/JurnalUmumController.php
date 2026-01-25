<?php

namespace App\Http\Controllers\Akuntansi;

use App\Http\Controllers\BaseDocumentController;
use App\Models\Akuntansi\JurnalUmum;
use Illuminate\Http\Request;

class JurnalUmumController extends BaseDocumentController
{
    protected $model = JurnalUmum::class;
    protected $viewPath = 'akuntansi.jurnal-umum';
    protected $routePrefix = 'akuntansi.jurnal-umum';
    protected $moduleName = 'Akuntansi - Jurnal Umum';
    protected $storagePath = 'documents/akuntansi/jurnal-umum';

    protected function validateRequest(Request $request, $id = null)
    {
        return $request->validate([
            'id_divisi' => 'required|exists:master_divisi,id',
            'tahun' => 'nullable|integer|min:2000|max:2100',
            'bulan' => 'nullable|integer|min:1|max:12',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string|max:255',
            'sifat_dokumen' => 'required|in:Rahasia,Internal,Umum',
            'file' => ($id ? 'nullable' : 'required') . '|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);
    }
}
