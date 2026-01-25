<?php

namespace App\Http\Controllers\Sdm;

use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;

class PromosiMutasiController extends BaseDocumentController
{
    protected $model = \App\Models\Sdm\PromosiMutasi::class;
    protected $viewPath = 'sdm.promosi-mutasi';
    protected $routePrefix = 'sdm.promosi-mutasi';
    protected $moduleName = 'Promosi Mutasi';

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
            'lokasi' => 'nullable|string|max:255',
        ]);
    }
}
